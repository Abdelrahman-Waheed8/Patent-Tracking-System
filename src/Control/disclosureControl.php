<?php 

class Disclosure extends disclosureModel
{
    private $title;
    private $description;
    private $file;
    private $maxFileSize = 10 * 1024 * 1024;
    public $errors =[];

    public function __construct($title, $description, $file)
    {
        $this->title = $title;
        $this->description = $description;
        $this->file = $file;
    }

    public function submitDisclosure()
    {
        $this->emptyFields();
        $this->charctersLimit();
        $this->handleUpload();

        if(!empty($this->errors))
            {
                return false;
                exit();
            }
        
        $this->setDisclosure($this->title, $this->description);
        return true;
    }

    private function emptyFields()
    {
        if(empty($this->title) || empty($this->description))
            {
                $this->errors['emptyInput'] = 'Fill in all the fields !';
            }
    }

    private function charctersLimit()
    {
        if(strlen($this->title) > 255)
            {
                $this->errors['tooLong'] = 'This title is too long Max characters is 255!';
            }
    }

    private function handleUpload()
    {
        $allowedExtentions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt'];
        $allowedMIMEs = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
            'text/plain',] ;
            
        if(!isset($this->file)  || $this->file['error'] === UPLOAD_ERR_NO_FILE)
        {
            $this->errors['Nofile'] = 'No File Selected!' ;
        }

        if($this->file['error'] !== UPLOAD_ERR_OK)
            {
            $this->errors['uploadError'] = 'Failed to Upload File!' ;
            return false ;
            }
            
        if($this->file['size'] > $this->maxFileSize)
        {
            $this->errors['exceedMaxSize'] = 'File is too big to upload!' ;
            return false ;
        }
            
        $ext = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $allowedExtentions))
        {
            $this->errors['notAllowedext'] = 'This extention is not allowed!';
            return false;
        }
            
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimes = $finfo->file($this->file['tmp_name']);
        if(!in_array($mimes,$allowedMIMEs))
        {
            $this->errors['notAllowedMime'] = 'Mime not allowed!';
            return false;
        }

        $dir = 'uploads/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $basename = preg_replace("/[^\w-]/", "_", pathinfo($this->file['name'], PATHINFO_EXTENSION));
        $uniqueName = $basename . bin2hex(random_bytes(4)) . "." . $ext;
        $targetdest = $dir . $uniqueName ;

        if (!move_uploaded_file($this->file['tmp_name'], $targetdest)) {
            $this->errors['uploadFailed'] = 'Failed to upload!' ;
            exit();
        }

    }
}