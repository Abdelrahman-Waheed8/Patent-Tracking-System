<?php

include "../src/config/config_session.php";
include "../src/config/config.php";
include "../src/model/patentModel.php";
include "../src/control/patentControl.php";

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../public/index.php"); // Redirect to login if not authenticated
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patentId = $_POST["patent_id"] ?? '';
    $transactionId = $_POST["transaction_id"] ?? '';

    if (!empty($patentId) && !empty($transactionId)) {
        $controller = new Patent();
        
        // This relies on the processPayment method added in the previous step
        $result = $controller->processPayment($patentId, $transactionId, $_SESSION["user_id"]);

        if ($result) {
            // Redirect back with a success flag in the URL
            header("Location: ../public/renewals/renewals.php?status=success");
            exit();
        } else {
            // Redirect back with an error flag
            header("Location: ../public/renewals/renewals.php?status=error");
            exit();
        }
    } else {
        header("Location: ../public/renewals/renewals.php?status=missing_fields");
        exit();
    }
} else {
    // If someone tries to access this file directly without posting a form
    header("Location: ../public/renewals/renewals.php");
    exit();
}
?>