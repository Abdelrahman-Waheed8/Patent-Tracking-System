<?php

include "../../src/config/config_session.php";
include "../../src/config/config.php";
include "../../src/model/DocumentHistoryModel.php";
include "../../src/control/DocumentHistoryControl.php";
include "../../src/view/DocumentHistoryView.php";
$controller = new DocumentHistoryControl();
$documents = $controller->getDocuments(); 
$view = new DocumentHistoryView();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./document-history.css">
    <title>Document History</title>
</head>

<body>
    <div class="container">

        <nav class="panel-header">

            <div class="left">
                <i class="fa-solid fa-arrow-left" onclick="goBack()"></i>
                <h2>Document History</h2>
            </div>

            <div class="right">
                <a class="logout-btn" href="../index.php">
                    <button class="logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </a>
            </div>

        </nav>
        <div class="table_page">
            <table>
                <thead>
                    <tr>
                        <th>Document ID</th>
                        <th>Evidence ID</th>
                        <th>File Path</th>
                        <th>Version</th>
                        <th>Doc Type</th>
                        <th>Upload Date</th>
                        <th>Original Name</th>
                        <th>Disc ID</th>
                        <th>Evidence Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $view->renderDocumentsTable($documents); 
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="./document-history.js"></script>
</body>

</html>