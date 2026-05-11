<?php
class DocumentHistoryModel extends DBH{
    public function fetchAllDocuments()
    {
        $pdo = $this->connect();
        $query = "SELECT 
            d.document_id, 
            d.evidence_id, 
            d.filePath, 
            d.Version, 
            d.docType, 
            d.UploadDate, 
            d.original_name,
            ev.disc_id, 
            ev.evidence_type
        FROM document d
        INNER JOIN evidence_vault ev ON d.evidence_id = ev.evidence_ID
        ORDER BY d.UploadDate DESC;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>