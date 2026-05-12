<?php
class DocumentHistoryControl extends DocumentHistoryModel
{
    public function getDocuments()
    {
        return $this->fetchAllDocuments();
    }
}
?>