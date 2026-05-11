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
    <div class="containerAll">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2 class="logo">IP System</h2>

            <ul class="menu">
                <li><a href="../document-history/document-history.php" class="active"><i
                            class="fa-solid fa-clock-rotate-left"></i>
                        document history</a></li>
                <li><a href="../categories/categories.html" class="active"><i
                            class="fa-solid fa-layer-group"></i>Categories</a></li>
            </ul>


            <a class="logout-btn" href="../index.php">
                <button class="logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </a>
        </div>
        <div class="container">

            <!-- Navbar -->
            <nav class="panel-header">

                <div class="left">
                    <i class="fas fa-list icon-btn" onclick="toggleSidebar()"></i>
                    <h2>IP Counsel Panel</h2>
                </div>



            </nav>

            <!-- Content -->
            <div class="content-wrapper">
                <section class="table-wrapper">
                    <div class="table-wrapper">
                        <table id="examiner-table">
                            <thead>
                                <tr>
                                    <th>Application ID</th>
                                    <th>Disclosure ID</th>
                                    <th>Application Number</th>
                                    <th>Filing Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                <thead>
                                <tbody id="table_body">
                                    <?php
                            $view = new IPcounselView();
                            $view->ViewTable();
                            ?>
                                </tbody>
                        </table>
                    </div>
                </section>

                <section id="rejection-section">
                    <h2>Rejection Details</h2>
                    <div class="table_wrapper">
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
                    </div>

                </section>
            </div>
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