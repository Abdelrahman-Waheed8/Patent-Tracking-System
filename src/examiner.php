<?php

include "config/config.php";
include "model/examinerModel.php";
include "control/examinerControl.php";
include "config/config_session.php";

$controller1 = new Examiner();


// Handle approve and create patent action
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'approve_patent' && isset($_POST['AppID'])) {
    
    $AppID = intval($_POST['AppID']);
    
    try {
        $examinerModel = new examinerModel();
        if ($examinerModel->approveAndCreatePatent($AppID)) {
            header("Location: ../public/examiner/examiner.php?approve=success");
        } else {
            header("Location: ../public/examiner/examiner.php?approve=failed");
        }
    } catch (Exception $e) {
        $_SESSION['errorsExaminer'] = array($e->getMessage());
        header("Location: ../public/examiner/examiner.php?approve=failed");
    }
    exit();
}

// Handle existing status update action
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['disc_id'], $_POST['status'])) {
    
    $discID = intval($_POST['disc_id']);
    $decision = $_POST['status'];
    $priorArt = $_POST['prior-art'];
    $comments = $_POST['additional_comments'];
    
    $controller = new Examiner($_SESSION['user_id'], $decision ,$priorArt, $comments, $discID);

    if($controller->SubmitExaminerAction())
        {
            if ($controller->processDecision($discID, $decision)) {
            header("Location: ../public/examiner/examiner.php?update=success");
        } else {
            header("Location: ../public/examiner/examiner.php?update=failed");
        }
        }
    else
        {
            $_SESSION['errorsExaminer'] = $controller->errors;
            header("Location: ../public/examiner/examiner.php?update=failed");
            exit();
        }

        exit();
    }

$allRequests = $controller1->fetchAllRequests();