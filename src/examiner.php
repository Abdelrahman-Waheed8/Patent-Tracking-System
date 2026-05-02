<?php

include "config/config.php";
include "model/examinerModel.php";
include "control/examinerControl.php";
include "config/config_session.php";

$controller = new ExaminerController();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['disc_id'], $_POST['status'])) {

    $discID = intval($_POST['disc_id']);
    $decision = $_POST['status'];

    if ($controller->processDecision($discID, $decision)) {
        header("Location: ../public/examiner/examiner.php?update=success");
    } else {
        header("Location: ../public/examiner/examiner.php?update=failed");
    }

    exit();
}

$allRequests = $controller->fetchAllRequests();