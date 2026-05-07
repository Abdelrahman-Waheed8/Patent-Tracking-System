<?php

class ExaminerController extends examinerModel {
    
    public function fetchAllRequests() {
        return $this->getPendingDisclosures();
    }

    public function processDecision($discID, $decision) {

        $allowedStatuses = ['Legal_Review', 'rejected', 'pending'];

        if (!in_array($decision, $allowedStatuses)) {
            return false;
        }

        return $this->updateStatus($discID, $decision);
    }
}