<?php

class licensingModel extends DBH
{

    protected function parseNumber(string $value): float
    {
        $normalized = preg_replace('/[^0-9.\-]/', '', $value);
        return $normalized === '' ? 0.0 : (float)$normalized;
    }

    protected function getDefaultSalesValues(string $model): array
    {
        $normalized = strtolower(trim($model));

        if ($normalized === 'per unit') {
            return ['net_sales' => '0', 'units_sold' => (string)mt_rand(20, 150)];
        }

        if ($normalized === 'percentage') {
            return ['net_sales' => (string)mt_rand(50000, 250000), 'units_sold' => '0'];
        }

        return ['net_sales' => '0', 'units_sold' => '0'];
    }

    protected function calculateRoyaltyAmount(string $model, float $rateValue, float $netSales, int $unitsSold): float
    {
        $normalizedModel = strtolower(trim($model));

        if ($normalizedModel === 'per unit') {
            return round($rateValue * max(0, $unitsSold), 2);
        }

        if ($normalizedModel === 'percentage') {
            return round(($netSales * $rateValue) / 100, 2);
        }

        return round($rateValue, 2);
    }

    protected function determineDistributionStatus(string $endDate, ?float $minNetSalesClause, float $netSales): string
    {
        $today = new DateTime('today');
        $contractEnd = DateTime::createFromFormat('Y-m-d', $endDate);

        if ($contractEnd !== false && $contractEnd < $today) {
            return 'Expired';
        }

        if ($minNetSalesClause !== null && $netSales < $minNetSalesClause) {
            return 'Terminated';
        }

        return 'Active';
    }

    protected function getPatentIdByNumber(string $patentNum): ?int
    {
        $sql = "SELECT Patent_ID FROM patent WHERE Number = ? LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$patentNum]);
        $row = $stmt->fetch();
        return $row ? (int)$row['Patent_ID'] : null;
    }

    protected function createPatent(PDO $pdo, string $patentNum): int
    {
        $sql = "INSERT INTO patent (Number, Status) VALUES (?, 'Active')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$patentNum]);
        return (int)$pdo->lastInsertId();
    }

    protected function getOrCreatePatentId(PDO $pdo, string $patentNum): int
    {
        $patentId = $this->getPatentIdByNumber($patentNum);
        if ($patentId !== null) {
            return $patentId;
        }

        return $this->createPatent($pdo, $patentNum);
    }

    protected function getAllLicenses(): array
    {
        $sql = "SELECT
                    la.LicenseID AS id,
                    p.Number AS patent_number,
                    COALESCE(la.CompanyName, '') AS company,
                    la.Type AS license_type,
                    COALESCE(rp.DistributionStatus, CASE WHEN la.EndDate < CURDATE() THEN 'Expired' ELSE 'Active' END) AS status,
                    la.Territory AS territory,
                    COALESCE(rp.calculationMethod, '') AS revenue_model,
                    COALESCE(CAST(rp.RateValue AS CHAR), '') AS revenue_value,
                    COALESCE(CAST(rp.Amount AS CHAR), '0') AS amount,
                    COALESCE(CAST(rp.NetSales AS CHAR), '') AS net_sales,
                    COALESCE(CAST(rp.UnitsSold AS CHAR), '') AS units_sold,
                    COALESCE(CAST(la.MinNetSalesClause AS CHAR), '') AS min_net_sales_clause,
                    la.EndDate AS end_date
                FROM licenseagreement la
                JOIN patent p ON p.Patent_ID = la.Patent_ID
                LEFT JOIN royaltypayment rp ON rp.royaltyID = (
                    SELECT r2.royaltyID
                    FROM royaltypayment r2
                    WHERE r2.LicenseID = la.LicenseID
                    ORDER BY r2.royaltyID DESC
                    LIMIT 1
                )
                ORDER BY la.LicenseID DESC";

        return $this->connect()->query($sql)->fetchAll() ?: [];
    }

    protected function insertLicense(array $data): bool
    {
        $pdo = $this->connect();
        $pdo->beginTransaction();

        try {
            $patentId = $this->getOrCreatePatentId($pdo, $data['patent_number']);
            $isExclusive = strcasecmp($data['license_type'], 'Exclusive') === 0 ? 1 : 0;

            $insertAgreement = $pdo->prepare(
                "INSERT INTO licenseagreement (Patent_ID, Type, StartDate, Territory, EndDate, IsExclusive, CompanyName, MinNetSalesClause)
                 VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?)"
            );
            $insertAgreement->execute([
                $patentId,
                $data['license_type'],
                $data['territory'],
                $data['end_date'],
                $isExclusive,
                $data['company'],
                $data['min_net_sales_clause']
            ]);
            $licenseId = (int)$pdo->lastInsertId();

            $rateValue = $this->parseNumber($data['revenue_value']);
            $netSales = $this->parseNumber($data['net_sales']);
            $unitsSold = (int)$this->parseNumber($data['units_sold']);
            $amount = $this->calculateRoyaltyAmount($data['revenue_model'], $rateValue, $netSales, $unitsSold);
            $distributionStatus = ($data['request_status'] ?? 'Pending') === 'Pending'
                ? 'Pending'
                : $this->determineDistributionStatus($data['end_date'], $data['min_net_sales_clause'], $netSales);

            $insertRoyalty = $pdo->prepare(
                "INSERT INTO royaltypayment (LicenseID, Amount, Currency, calculationMethod, DistributionStatus, PmtDate, NetSales, UnitsSold, RateValue)
                 VALUES (?, ?, 'USD', ?, ?, CURDATE(), ?, ?, ?)"
            );
            $insertRoyalty->execute([
                $licenseId,
                $amount,
                $data['revenue_model'],
                $distributionStatus,
                $netSales > 0 ? $netSales : null,
                $unitsSold > 0 ? $unitsSold : null,
                $rateValue
            ]);

            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    protected function updateLicense(array $data): bool
    {
        $pdo = $this->connect();
        $pdo->beginTransaction();

        try {
            $patentId = $this->getOrCreatePatentId($pdo, $data['patent_number']);
            $isExclusive = strcasecmp($data['license_type'], 'Exclusive') === 0 ? 1 : 0;

            $updateAgreement = $pdo->prepare(
                "UPDATE licenseagreement
                 SET Patent_ID = ?, Type = ?, Territory = ?, EndDate = ?, IsExclusive = ?, CompanyName = ?, MinNetSalesClause = ?
                 WHERE LicenseID = ?"
            );
            $updateAgreement->execute([
                $patentId,
                $data['license_type'],
                $data['territory'],
                $data['end_date'],
                $isExclusive,
                $data['company'],
                $data['min_net_sales_clause'],
                $data['id']
            ]);

            $rateValue = $this->parseNumber($data['revenue_value']);
            $netSales = $this->parseNumber($data['net_sales']);
            $unitsSold = (int)$this->parseNumber($data['units_sold']);
            $amount = $this->calculateRoyaltyAmount($data['revenue_model'], $rateValue, $netSales, $unitsSold);
            $distributionStatus = $this->determineDistributionStatus($data['end_date'], $data['min_net_sales_clause'], $netSales);

            $findRoyalty = $pdo->prepare(
                "SELECT royaltyID
                 FROM royaltypayment
                 WHERE LicenseID = ?
                 ORDER BY royaltyID DESC
                 LIMIT 1"
            );
            $findRoyalty->execute([$data['id']]);
            $existingRoyalty = $findRoyalty->fetch(PDO::FETCH_ASSOC);

            if ($existingRoyalty) {
                $updateRoyalty = $pdo->prepare(
                    "UPDATE royaltypayment
                     SET Amount = ?, Currency = 'USD', calculationMethod = ?, DistributionStatus = ?, PmtDate = CURDATE(), NetSales = ?, UnitsSold = ?, RateValue = ?
                     WHERE royaltyID = ?"
                );
                $updateRoyalty->execute([
                    $amount,
                    $data['revenue_model'],
                    $distributionStatus,
                    $netSales > 0 ? $netSales : null,
                    $unitsSold > 0 ? $unitsSold : null,
                    $rateValue,
                    (int)$existingRoyalty['royaltyID']
                ]);
            } else {
                $insertRoyalty = $pdo->prepare(
                    "INSERT INTO royaltypayment (LicenseID, Amount, Currency, calculationMethod, DistributionStatus, PmtDate, NetSales, UnitsSold, RateValue)
                     VALUES (?, ?, 'USD', ?, ?, CURDATE(), ?, ?, ?)"
                );
                $insertRoyalty->execute([
                    $data['id'],
                    $amount,
                    $data['revenue_model'],
                    $distributionStatus,
                    $netSales > 0 ? $netSales : null,
                    $unitsSold > 0 ? $unitsSold : null,
                    $rateValue
                ]);
            }

            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    protected function deleteLicense(int $id): bool
    {
        $stmt = $this->connect()->prepare("DELETE FROM licenseagreement WHERE LicenseID = ?");
        return $stmt->execute([$id]);
    }

    // Approve/reject helpers: manage distribution status and checks
    protected function getLicenseById(int $id): ?array
    {
        $stmt = $this->connect()->prepare("SELECT LicenseID, Patent_ID, IsExclusive, Type FROM licenseagreement WHERE LicenseID = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    protected function hasLicenseWithExclusive(int $patentId, int $excludeLicenseId = 0): bool
    {
        $sql = "SELECT COUNT(1) AS cnt FROM licenseagreement WHERE Patent_ID = ? AND IsExclusive = 1";
        if ($excludeLicenseId > 0) {
            $sql .= " AND LicenseID != ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$patentId, $excludeLicenseId]);
        } else {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$patentId]);
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && (int)$row['cnt'] > 0;
    }

    protected function hasLicenseWithNonExclusive(int $patentId, int $excludeLicenseId = 0): bool
    {
        $sql = "SELECT COUNT(1) AS cnt FROM licenseagreement WHERE Patent_ID = ? AND IsExclusive = 0";
        if ($excludeLicenseId > 0) {
            $sql .= " AND LicenseID != ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$patentId, $excludeLicenseId]);
        } else {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$patentId]);
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && (int)$row['cnt'] > 0;
    }

    protected function updateDistributionStatus(int $licenseId, string $status): bool
    {
        $pdo = $this->connect();
        $findRoyalty = $pdo->prepare(
            "SELECT royaltyID FROM royaltypayment WHERE LicenseID = ? ORDER BY royaltyID DESC LIMIT 1"
        );
        $findRoyalty->execute([$licenseId]);
        $existing = $findRoyalty->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $update = $pdo->prepare("UPDATE royaltypayment SET DistributionStatus = ? WHERE royaltyID = ?");
            return $update->execute([$status, (int)$existing['royaltyID']]);
        }

        $insert = $pdo->prepare(
            "INSERT INTO royaltypayment (LicenseID, Amount, Currency, calculationMethod, DistributionStatus, PmtDate) VALUES (?, 0, 'USD', 'Fixed', ?, CURDATE())"
        );
        return $insert->execute([$licenseId, $status]);
    }

    protected function buildSummaryAndAlerts(array $records): array
    {
        $today = new DateTime('today');
        $totalRevenue = 0.0;
        $activeCount = 0;
        $expiringSoonCount = 0;
        $alerts = [];

        foreach ($records as &$record) {
            $amount = (float)($record['amount'] ?? 0);
            $totalRevenue += $amount;

            $endDate = DateTime::createFromFormat('Y-m-d', (string)($record['end_date'] ?? ''));
            $netSales = (float)($record['net_sales'] !== '' ? $record['net_sales'] : 0);
            $minClause = $record['min_net_sales_clause'] !== '' ? (float)$record['min_net_sales_clause'] : null;
            $breached = $minClause !== null && $netSales < $minClause;
            $label = $record['company'] !== '' ? $record['company'] : $record['patent_number'];

            $record['termination_status'] = 'Healthy';
            $record['termination_reason'] = '';

            if ($breached) {
                $record['termination_status'] = 'Breached';
                $record['termination_reason'] = 'Net sales are below the minimum contractual clause.';
                $alerts[] = ['type' => 'error', 'message' => "License #{$record['id']} for {$label} breached the minimum net sales clause."];
            }

            if ($endDate !== false) {
                $daysLeft = (int)$today->diff($endDate)->format('%r%a');

                if ($daysLeft < 0) {
                    $record['termination_status'] = 'Expired';
                    $record['termination_reason'] = 'Contract end date passed.';
                    $alerts[] = ['type' => 'error', 'message' => "License #{$record['id']} for {$label} is expired."];
                } elseif ($daysLeft <= 30 && $record['termination_status'] === 'Healthy') {
                    $record['termination_status'] = 'Nearing Expiry';
                    $record['termination_reason'] = "Contract expires in {$daysLeft} day(s).";
                    $alerts[] = ['type' => 'warning', 'message' => "License #{$record['id']} for {$label} expires in {$daysLeft} day(s)."];
                }

                if ($daysLeft >= 0 && $daysLeft <= 30) {
                    $expiringSoonCount++;
                }
            }

            if (($record['status'] ?? '') === 'Active') {
                $activeCount++;
            }
        }
        unset($record);

        return [
            'records' => $records,
            'summary' => [
                'totalLicenses' => count($records),
                'activeLicenses' => $activeCount,
                'expiringSoonLicenses' => $expiringSoonCount,
                'totalRevenue' => $totalRevenue
            ],
            'alerts' => $alerts
        ];
    }
}
