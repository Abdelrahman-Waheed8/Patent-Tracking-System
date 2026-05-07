<?php
include "../../src/config/config_session.php";
include "../../src/config/config.php";
include "../../src/model/patentModel.php";
include "../../src/control/patentControl.php";
include "../../src/view/patentView.php";

$controller = new patentControl();
$userData = $controller->filterData($_SESSION["user_id"])
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renewals - IP System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../inventors/inventors.css">
    <link rel="stylesheet" href="./renewals.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <h2 class="logo">IP System</h2>

            <ul class="menu">
                <li><a href="../dashboard/dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="../inventors/inventors.php"><i class="fas fa-user-astronaut"></i> Inventors</a></li>
                <li><a href="../invention_disclosure/disclosure.php"><i class="fas fa-lightbulb"></i> Disclosure</a>
                </li>
                <li><a href="../patent/patent.php"><i class="fas fa-file"></i> Patents</a></li>
                <li><a href="./renewals.php" class="active"><i class="fas fa-sync"></i> Renewals</a></li>
                <li><a href="../licensing/licensing.php"><i class="fas fa-handshake"></i> Licensing</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
            </ul>

            <a href="../index.php" class="logout_btn">
                <button class="logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button></a>
        </aside>

        <main class="page_structure">
            <nav class="panel-header">
                <div class="left">
                    <i class="fas fa-list icon-btn" onclick="toggleSidebar()"></i>
                    <h6>Renewals</h6>
                </div>

                <div class="right">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search-input" placeholder="Search...">
                    </div>
                    <i class="fas fa-bell icon-btn"></i>
                    <div class="user icon-btn">
                        <i class="fas fa-user"></i>
                        <i class="fas fa-chevron-down small"></i>
                    </div>
                </div>
            </nav>

            <div class="cards">
                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-file-alt"></i>
                        <span>Total Patents</span>
                    </div>
                    <?php
                    $view = new patentView();
                    $view->displayCardTotal();
                    ?>
                    <p>Patents requiring renewal</p>
                </div>

                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-clock"></i>
                        <span>Due Soon</span>
                    </div>
                    <?php
                    $view = new patentView();
                    $view->displayCardDueSoon();
                    ?>
                    <p>Due within 30 days</p>
                </div>

                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Overdue</span>
                    </div>
                    <?php
                    $view = new patentView();
                    $view->displayCardOverDue();
                    ?>
                    <p>Overdue renewals</p>
                </div>
            </div>

            <div class="content-wrapper">
                <div class="header-actions">
                    <h2>Patent Renewals</h2>
                </div>

                <div id="renewals-table" class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patent</th>
                                <th>GrantDate</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th>DueDate</th>
                                <th>Days Left</th>
                                <th>Estimated Fee</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                    $view = new patentView();
                    $view->displayContent();
                    ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="alert-container" class="alert-container"></div>

    <div id="view-patent-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Patent Renewal Details</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div id="view-patent-details">
                    <div class="detail-item">
                        <strong>ID</strong>
                        <span id="modal-patent-id"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Patent</strong>
                        <span id="modal-patent-title"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Grant Date</strong>
                        <span id="modal-grant-date"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Status</strong>
                        <span id="modal-status"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Deadline</strong>
                        <span id="modal-deadline"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Due Date</strong>
                        <span id="modal-due-date"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Days Left</strong>
                        <span id="modal-days-left"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Estimated Fee</strong>
                        <span id="modal-estimated-fee"></span>
                    </div>
                    <form action="../../src/process_payment.php" method="POST" id="payment-form">
                        <input type="hidden" name="patent_id" id="form-patent-id" value="">
                        
                        <div class="detail-item" id="transaction-container" style="display: none;">
                            <strong>Transaction ID</strong>
                            <input type="text" id="transaction-input" name="transaction_id" placeholder="Enter Transaction ID" style="padding: 5px; width: 150px;" required>
                        </div>

                        <div class="btn-pay">
                            <button type="submit" id="pay-btn">Pay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./renewals.js"></script>
</body>

</html>