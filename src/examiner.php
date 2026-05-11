<?php

include "config/config.php";
include "model/examinerModel.php";
include "control/examinerControl.php";
include "config/config_session.php";

<<<<<<< HEAD
$controller1 = new ExaminerController();
=======
$controller1 = new Examiner();
>>>>>>> 464c9faf0a2c3baf7a27a165fdfb66d77c292f3e


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['disc_id'], $_POST['status'])) {
    
    $discID = intval($_POST['disc_id']);
    $decision = $_POST['status'];
    $priorArt = $_POST['prior-art'];
    $comments = $_POST['additional_comments'];
    
<<<<<<< HEAD
    $controller = new ExaminerController($_SESSION['user_id'], $decision ,$priorArt, $comments, $discID);
=======
    $controller = new Examiner($_SESSION['user_id'], $decision ,$priorArt, $comments, $discID);
>>>>>>> 464c9faf0a2c3baf7a27a165fdfb66d77c292f3e

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