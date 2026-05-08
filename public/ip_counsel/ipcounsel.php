<?php
include "../../src/config/config_session.php";
include "../../src/config/config.php";
include "../../src/model/ipcounselModel.php";
include "../../src/control/ipcounselControl.php";
include "../../src/view/ipcounselView.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./ipcounsel.css">
    <title>IP Counsel Panel</title>
</head>

<body>

    <div class="container">

        <!-- Navbar -->
        <nav class="panel-header">

            <div class="left">
                <i class="fas fa-list icon-btn" onclick="toggleSidebar()"></i>
                <h2>IP Counsel Panel</h2>
            </div>

            <div class="right">
                <i class="fas fa-bell icon-btn"></i>

                <div class="user icon-btn">
                    <i class="fas fa-user"></i>
                    <i class="fas fa-chevron-down small"></i>
                </div>
            </div>

        </nav>

        <!-- Content -->
        <div class="content-wrapper">
            <section>
                <table id="examiner-table">
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Disclosure ID</th>
                            <th>Application Number</th>
                            <th>Filing Date</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        <thead>
                        <tbody id="table_body">
                            <?php
                            $view = new IPcounselView();
                            $view->ViewTable();
                            ?>
                        </tbody>
                </table>
            </section>

            <section id="rejection-section">
                <h2>Rejection Details</h2>
                <table id="rejection-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Application Number</th>
                            <th>Rejection Reason</th>
                            <th>Deadline</th>
                        </tr>
                    </thead>
                    <tbody id="rejection_body">
                    <?php
                        $view->showRejectedApps();
                    ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
    <!-- Review Modal -->
    <div class="modal-overlay" id="review-modal">
        <div class="modal">
            <div class="modal-header">
                <h2>Patent Review</h2>
                <i class="fas fa-times close-modal"></i>
            </div>
            <div id="review-details"></div>
        </div>
    </div>
    <script>
    
    </script>
    <script src="./ipcounsel.js"></script>
</body>

</html>