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
                        <th>Days Left</th>
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

    <div id="payment-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Payment Details</h2>
                <i class="fas fa-times close-modal"></i>
            </div>
            <div class="modal-body">
                <div class="payment-info">
                    <div class="info-row">
                        <label>Patent Number:</label>
                        <span id="pay-patent-number"></span>
                    </div>
                    <div class="info-row">
                        <label>Patent Title:</label>
                        <span id="pay-patent-title"></span>
                    </div>
                    <div class="info-row">
                        <label>Due Date:</label>
                        <span id="pay-due-date"></span>
                    </div>
                    <div class="info-row">
                        <label>Amount:</label>
                        <span id="pay-amount" style="font-size: 18px; font-weight: bold; color: #28a745;"></span>
                    </div>
                    <div class="info-row">
                        <label>Currency:</label>
                        <span id="pay-currency">USD</span>
                    </div>
                    <div class="info-row">
                        <label>Status:</label>
                        <span id="pay-status"></span>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary close-modal">Close</button>
                    <button type="button" class="btn-primary" id="confirm-pay-btn">Confirm Payment</button>
                </div>
            </div>
        </div>
    </div>

    <div id="renewal-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Add New Renewal</h2>
                <i class="fas fa-times close-modal"></i>
            </div>
            <div class="modal-body">
                <form id="renewal-form">
                    <input type="hidden" id="renewal-id">
                    <div class="form-group">
                        <label for="patent-number">Patent Number</label>
                        <input type="text" id="patent-number" placeholder="e.g., US1234567" required>
                    </div>
                    <div class="form-group">
                        <label for="patent-title">Patent Title</label>
                        <input type="text" id="patent-title" required>
                    </div>
                    <div class="form-group">
                        <label for="due-date">Due Date</label>
                        <input type="date" id="due-date" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount (USD)</label>
                        <input type="number" id="amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status">
                            <option value="Upcoming">Upcoming</option>
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                            <option value="Overdue">Overdue</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-secondary close-modal">Cancel</button>
                        <button type="submit" class="btn-primary">Save Renewal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- <script src="./renewals.js"></script> -->
</body>

</html>