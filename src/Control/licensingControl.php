<?php

class licensingControl extends licensingModel
{
    public $errors = [];

    private function sanitizeStatus(string $status): string
    {
        $allowed = ['Active', 'Expired', 'Terminated'];
        return in_array($status, $allowed, true) ? $status : 'Active';
    }

    private function validatePayload(array $payload, bool $requireId = false): bool
    {
        if ($requireId && ((int)($payload['id'] ?? 0) <= 0)) {
            $this->errors[] = "Invalid license id";
            return false;
        }

        $required = ['revenue_model', 'revenue_value'];
        foreach ($required as $field) {
            if (trim((string)($payload[$field] ?? '')) === '') {
                $this->errors[] = "Revenue model and value are required";
                return false;
            }
        }

        return true;
    }

    private function getDefaultString(string $value, string $fallback): string
    {
        $trimmed = trim($value);
        return $trimmed !== '' ? $trimmed : $fallback;
    }

    private function normalizePayload(array $payload, bool $withId = false): array
    {
        $patentNumber = $this->getDefaultString((string)($payload['patent_number'] ?? ''), 'DUMMY-' . strtoupper(substr(md5(uniqid('', true)), 0, 8)));
        $company = $this->getDefaultString((string)($payload['company'] ?? ''), 'Inventor Company');
        $licenseType = $this->getDefaultString((string)($payload['license_type'] ?? ''), 'Non-Exclusive');
        $territory = $this->getDefaultString((string)($payload['territory'] ?? ''), 'Global');
        $revenueModel = $this->getDefaultString((string)($payload['revenue_model'] ?? ''), 'Fixed');
        $revenueValue = $this->getDefaultString((string)($payload['revenue_value'] ?? ''), '1000');
        $endDate = trim((string)($payload['end_date'] ?? '')) !== ''
            ? trim((string)($payload['end_date'] ?? ''))
            : (new DateTime('+1 year'))->format('Y-m-d');
        $defaultSales = $this->getDefaultSalesValues($revenueModel);
        $autoStatus = (new DateTime($endDate) < new DateTime('today')) ? 'Expired' : 'Active';
        $status = $withId
            ? $this->sanitizeStatus((string)($payload['status'] ?? $autoStatus))
            : $autoStatus;

        $data = [
            'patent_number' => $patentNumber,
            'company' => $company,
            'license_type' => $licenseType,
            'territory' => $territory,
            'revenue_model' => $revenueModel,
            'revenue_value' => $revenueValue,
            'net_sales' => trim((string)($payload['net_sales'] ?? '')) !== '' ? trim((string)($payload['net_sales'] ?? '')) : $defaultSales['net_sales'],
            'units_sold' => trim((string)($payload['units_sold'] ?? '')) !== '' ? trim((string)($payload['units_sold'] ?? '')) : $defaultSales['units_sold'],
            'min_net_sales_clause' => trim((string)($payload['min_net_sales_clause'] ?? '')) !== ''
                ? (float)trim((string)($payload['min_net_sales_clause']))
                : null,
            'end_date' => $endDate,
            'status' => $status
        ];

        if ($withId) {
            $data['id'] = (int)$payload['id'];
        }

        return $data;
    }

    public function listLicensesPayload(): array
    {
        $records = $this->getAllLicenses();
        $computed = $this->buildSummaryAndAlerts($records);
        return [
            'success' => true,
            'data' => $computed['records'],
            'summary' => $computed['summary'],
            'alerts' => $computed['alerts']
        ];
    }

    public function createLicense(array $payload): array
    {
        if (!$this->validatePayload($payload)) {
            return ['success' => false, 'message' => $this->errors[0] ?? 'Validation failed'];
        }

        $data = $this->normalizePayload($payload);
        $this->insertLicense($data);
        return ['success' => true, 'message' => 'License created successfully'];
    }

    public function updateLicenseRecord(array $payload): array
    {
        if (!$this->validatePayload($payload, true)) {
            return ['success' => false, 'message' => $this->errors[0] ?? 'Validation failed'];
        }

        $data = $this->normalizePayload($payload, true);
        $this->updateLicense($data);
        return ['success' => true, 'message' => 'License updated successfully'];
    }

    public function deleteLicenseById(int $id): array
    {
        if ($id <= 0) {
            return ['success' => false, 'message' => 'Invalid license id'];
        }

        $this->deleteLicense($id);
        return ['success' => true, 'message' => 'License deleted successfully'];
    }

    public function approveLicenseById(int $id): array
    {
        if ($id <= 0) {
            return ['success' => false, 'message' => 'Invalid license id'];
        }

        $license = $this->getLicenseById($id);
        if (!$license) {
            return ['success' => false, 'message' => 'License not found'];
        }


        // If there is any existing non-exclusive license for the same patent, block approval
        if ($this->hasLicenseWithNonExclusive((int)$license['Patent_ID'], $id)) {
            return ['success' => false, 'message' => 'Cannot approve — a non-exclusive license already exists for this patent'];
        }

        // If trying to approve an Exclusive license but another Exclusive exists -> reject
        if ((int)$license['IsExclusive'] === 1 && $this->hasLicenseWithExclusive((int)$license['Patent_ID'], $id)) {
            return ['success' => false, 'message' => 'An exclusive license already exists for this patent'];
        }

        $ok = $this->updateDistributionStatus($id, 'Active');
        return $ok ? ['success' => true, 'message' => 'License approved'] : ['success' => false, 'message' => 'Failed to update license status'];
    }

    public function rejectLicenseById(int $id): array
    {
        if ($id <= 0) {
            return ['success' => false, 'message' => 'Invalid license id'];
        }

        $license = $this->getLicenseById($id);
        if (!$license) {
            return ['success' => false, 'message' => 'License not found'];
        }

        $ok = $this->updateDistributionStatus($id, 'Rejected');
        return $ok ? ['success' => true, 'message' => 'License rejected'] : ['success' => false, 'message' => 'Failed to update license status'];
    }
}
