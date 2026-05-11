<?php

class Examiner extends examinerModel {
    private $uid;
    private $patentID;
    private $action;
    private $description;
    private $discID;
    public $errors = [];

    public function __construct($uid = null, $action = null,$patentID = null, $description = null, $discID = null )
    {
        $this->uid = $uid;
        $this->patentID = $patentID;
        $this->action = $action;
        $this->description = $description;
        $this->discID = $discID;
    }

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

    public function validateInput()
    {
        if(empty($this->patentID) || empty($this->description))
        {
            $this->errors["empty"] = "Fill in all fields";
            return false;
        }
        return true;
    }

    private function patentFound()
    {
        if(!$this->checkPatent($this->patentID))
            {
                $this->errors["notfound"] = "Patent not found";
                return false;
            }
        return true;
    }

    private function recordRejectionReason()
    {
        if($this->validateInput() && $this->patentFound())
            {
                $this->documentExaminer($this->uid, $this->action, "Reject Application", $this->description, $this->patentID, $this->discID);
                return true;
            }
        return false;
    }

    private function recordExaminerApprove()
    {
        $this->documentExaminer($this->uid, $this->action, "Approve Application", "Application approved");
    }

    public function SubmitExaminerAction()
    {
        if ($this->action === 'rejected') {
            if (!$this->validateInput() || !$this->patentFound()) {
                return false;
            }
            return $this->recordRejectionReason();
        }

        if (in_array($this->action, ['Legal_Review', 'approved', 'pending'])) {
            $this->recordExaminerApprove();
            return true;
        }

        $this->errors['action'] = 'Invalid examiner action';
        return false;
    }
}