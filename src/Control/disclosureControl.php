<?php

class Disclosure extends disclosureModel
{
    private $title;
    private $description;
    private $contributors;
    private $file;

    private $maxFileSize = 10 * 1024 * 1024;
    public $errors = [];

    public function __construct($title, $description, $file, $contributors)
    {
        $this->title = $title;
        $this->description = $description;
        $this->file = $file;
        $this->contributors = $contributors;
    }

    public function submitDisclosure()
    {
        $this->validateFields();
        $this->handleUpload();

        if (!empty($this->errors)) {
            return false;
        }

        $this->setDisclosure($this->title, $this->description, $this->contributors);

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }

    private function validateFields()
    {
        if (empty($this->title) || empty($this->description)) {
            $this->errors['emptyInput'] = "Title and Description are required";
        }

        if (strlen($this->title) > 255) {
            $this->errors['titleLength'] = "Title too long (max 255)";
        }

        if (
            !isset($this->contributors['ContributorIDs']) ||
            !is_array($this->contributors['ContributorIDs']) ||
            count($this->contributors['ContributorIDs']) == 0
        ) {
            $this->errors['contributors'] = "At least one contributor is required";
        }

        if (
            isset($this->contributors['contributionPercentages']) &&
            is_array($this->contributors['contributionPercentages'])
        ) {
            $total = 0;

            foreach ($this->contributors['contributionPercentages'] as $p) {
                $total += (int)$p;
            }

            if ($total != 100) {
                $this->errors['percentage'] = "Total contribution must equal 100%";
            }
        }

        if (isset($this->contributors['ContributorIDs'])) {
            foreach ($this->contributors['ContributorIDs'] as $id) {
                if (!$this->userExists($id)) {
                    $this->errors['invalidContributor'] = "One or more contributor IDs do not exist";
                    break;
                }
            }
        }
    }

    private function handleUpload()
    {
        $allowedExt = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt'];

        if (!isset($this->file) || $this->file['error'] === UPLOAD_ERR_NO_FILE) {
            $this->errors['noFile'] = 'No file selected!';
            return false;
        }

        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            $this->errors['uploadError'] = 'Upload failed!';
            return false;
        }

        $ext = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            $this->errors['badExt'] = 'Invalid file type!';
            return false;
        }

        $dir = "uploads/";
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = preg_replace("/[^\w-]/", "_", pathinfo($this->file['name'], PATHINFO_FILENAME));
        $unique = $name . "_" . bin2hex(random_bytes(4)) . "." . $ext;

        $path = $dir . $unique;

        if (!move_uploaded_file($this->file['tmp_name'], $path)) {
            $this->errors['uploadFail'] = 'Upload failed!';
            return false;
        }

        return $path;
    }
}