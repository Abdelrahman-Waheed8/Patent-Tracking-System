<?php

class DocumentHistoryView
{

    public function renderDocumentsTable(array $documents)
    {
        if (empty($documents)) {
            echo '<tr><td colspan="9" style="text-align: center;">No documents found.</td></tr>';
            return;
        }

        foreach ($documents as $doc) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($doc['document_id']) . "</td>";
            echo "<td>" . htmlspecialchars($doc['evidence_id']) . "</td>";
            echo "<td><a href='../../" . htmlspecialchars($doc['filePath']) . "' target='_blank'>" . htmlspecialchars($doc['filePath']) . "</a></td>";
            echo "<td>" . htmlspecialchars($doc['Version']) . "</td>";
            echo "<td>" . htmlspecialchars($doc['docType']) . "</td>";
            echo "<td>" . htmlspecialchars($doc['UploadDate']) . "</td>";
            echo "<td>" . htmlspecialchars($doc['original_name']) . "</td>";
            echo "<td>" . htmlspecialchars($doc['disc_id']) . "</td>";
            echo "<td>" . htmlspecialchars($doc['evidence_type']) . "</td>";
            echo "</tr>";
        }
    }
}