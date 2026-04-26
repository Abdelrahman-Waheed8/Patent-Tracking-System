<?php
include "../../src/config/config_session.php";
include "../../src/view/disclosureView.php";

if(!isset($_SESSION["user_id"]))
    {
        header("Location: ../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - IP System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="disclosure.css">
</head>

<body>

    <div class="container">

        <!-- Sidebar -->
        <div class="sidebar">
            <h2 class="logo">IP System</h2>

            <ul class="menu">
                <li><a href="./dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="../inventors/inventors.php"><i class="fas fa-user-astronaut"></i>inventors</a></li>
                <li><a href="../invention_disclosure/disclosure.php"><i class="fas fa-lightbulb"></i> Disclosure</a>
                </li>
                <li><a href="#"><i class="fas fa-file"></i> Patents</a></li>
                <li><a href="#"><i class="fas fa-sync"></i> Renewals</a></li>
                <li><a href="#"><i class="fas fa-handshake"></i> Licensing</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
            </ul>

            <button class="logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </div>

        <!-- Main Content -->
        <div class="page_structure">

            <!-- Navbar -->
            <nav class="panel-header">
                <div class="left">
                    <i class="fas fa-list icon-btn" onclick="toggleSidebar()"></i>
                    <h6>Dashboard</h6>
                </div>

                <div class="right">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <i class="fas fa-bell icon-btn"></i>
                    <div class="user icon-btn">
                        <i class="fas fa-user"></i>
                        <i class="fas fa-chevron-down small"></i>
                    </div>
                </div>
            </nav>

            <?php
                $disclosureView = new disclosureView();
                $disclosureView->displaySessionInfo();
            ?>

            <!-- Cards -->
            <div class="cards">
                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-file-alt"></i>
                        <span>Total Patents</span>
                    </div>
                    <h1>20</h1>
                    <p>All registered patents</p>
                </div>

                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-clock"></i>
                        <span>Pending</span>
                    </div>
                    <h1>4</h1>
                    <p>Waiting for approval</p>
                </div>

                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-sync"></i>
                        <span>Renewals</span>
                    </div>
                    <h1>12</h1>
                    <p>Upcoming renewals</p>
                </div>

                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-handshake"></i>
                        <span>Deals</span>
                    </div>
                    <h1>7</h1>
                    <p>Active licensing deals</p>
                </div>
            </div>

            <!-- Deadlines -->
            <div class="deadlines-box">
                <div class="deadlines-header">
                    <h3>Upcoming Deadlines</h3>
                    <button class="view-all">View All</button>
                </div>
                <div class="deadline-list">
                </div>
            </div>
            <!-- Quick Actions --->
            <div class="quick_action">
                <div class="title_quick">
                    <h3>Quick Actions</h3>
                </div>
                <div class="bodyAction">
                    <div class="action-card">
                        <a href="../inventor/inventor.php">
                            <div class="icon-wrapper">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </a>
                        <span>New Invention</span>
                    </div>



                    <div class="action-card">
                        <a href="#">
                            <div class="icon-wrapper">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                        </a>
                        <span>New Patent App</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="js.js"></script>

</body>

</html>