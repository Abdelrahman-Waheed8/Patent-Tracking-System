<?php
class loginView
{
    public function sendJsonResponseError($errors)
    {
        if(!empty($errors))
            header('Content-Type: application/json');
            echo json_encode([
        "status" => "error",
        "message" => implode("<br>", $errors)
    ]);
        
    }

    public function sendJsonResponse($status, $url)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $status,
            'redirect' => $url
        ]);
    }
}