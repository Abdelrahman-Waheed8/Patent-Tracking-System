<?php

class Disclosure extends disclosureModel
{
    private $title;
    private $description;
    private $contributors;
    private $files;
    private $uploadedFiles = [];
    private $priorArt;
    private $companyNames = [];

    private $maxFileSize = 10 * 1024 * 1024;
    public $errors = [];

    public function __construct($title, $description, $files, $contributors, $priorArt = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->files = $files;
        $this->contributors = $contributors;
        $this->priorArt = $priorArt;

        if (isset($contributors['companyNames'])) {
            $this->companyNames = $contributors['companyNames'];
        }
    }

    public function submitDisclosure()
    {
        $this->validateFields();

        if(empty($this->errors)){
            $this->handleUpload();
        }

        if (!empty($this->errors)) {
            return false;
        }

        $this->setDisclosure($this->title, $this->description, $this->contributors, $this->uploadedFiles, $this->priorArt, $this->companyNames);

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
        $dir = "uploads/";

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (empty($this->files)) {
            $this->errors['noFile'] = 'No file selected!';
            return false;
        }

        foreach($this->files as $file)
        {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $this->errors['uploadError_' . $file['name']] = 'Upload failed for ' . $file['name'];
                continue;
            }

            if($file['size'] > $this->maxFileSize)
            {
                $this->errors['size_' . $file['name']] = $file['name'] . ' is too large';
                continue;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                $this->errors['badExt_' . $file['name']] = 'Invalid file type: ' . $file['name'];
                continue;
            }

            $name = preg_replace("/[^\w-]/", "_", pathinfo($file['name'], PATHINFO_FILENAME));
            $unique = $name . "_" . bin2hex(random_bytes(4)) . "." . $ext;

            $path = $dir . $unique;

            if (!move_uploaded_file($file['tmp_name'], $path)) {
                $this->errors['uploadFail_' . $file['name']] = 'Could not save: ' . $file['name'];
            }
            else{
                $this->uploadedFiles[] = $path;
            }
        }
    }
}
