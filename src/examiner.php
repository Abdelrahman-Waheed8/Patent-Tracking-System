<?php

include "config/config.php";
include "model/examinerModel.php";
include "control/examinerControl.php";
include "config/config_session.php";

$controller1 = new Examiner();


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