<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="patent.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2 class="logo">IP System</h2>

            <ul class="menu">
                <li><a href="../dashboard/dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="../inventors/inventors.php"><i class="fas fa-user-astronaut"></i>inventore</a></li>
                <li><a href="../invention_disclosure/disclosure.php"><i class="fas fa-lightbulb"></i> Disclosure</a>
                </li>
                <li><a href="./patent.php" class="active"><i class="fas fa-file"></i> Patents</a></li>
                <li><a href="../renewals/renewals.php"><i class="fas fa-sync"></i> Renewals</a></li>
                <li><a href="../licensing/licensing.php"><i class="fas fa-handshake"></i> Licensing</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
            </ul>
            <a class="logout-btn" href="../index.php">
                <button class="logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </a>
        </div>
        <!-- Main Content -->
        <div class="page_structure">

            <!-- Navbar -->
            <nav class="panel-header">
                <div class="left">
                    <i class="fas fa-list icon-btn" onclick="toggleSidebar()"></i>
                    <h6>Patent</h6>
                </div>

                <div class="right">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search-input" placeholder="Search by status, country...">
                    </div>
                    <i class="fas fa-bell icon-btn"></i>
                    <div class="user icon-btn">
                        <i class="fas fa-user"></i>
                        <i class="fas fa-chevron-down small"></i>
                    </div>
                </div>
            </nav>
            <!-- Cards -->
            <div class="cards">
                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-file-alt"></i>
                        <span>Total Patents</span>
                    </div>
                    <h1 id="total-patents">0</h1>
                    <p>All registered patents</p>
                </div>

                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-check-circle"></i>
                        <span>Active</span>
                    </div>
                    <h1 id="active-patents">0</h1>
                    <p>Currently active patents</p>
                </div>

                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-times-circle"></i>
                        <span>Expired</span>
                    </div>
                    <h1 id="expired-patents">0</h1>
                    <p>Expired patents</p>
                </div>

                <div class="card">
                    <div class="card-top">
                        <i class="fas fa-trash-alt"></i>
                        <span>Abandoned</span>
                    </div>
                    <h1 id="abandoned-patents">0</h1>
                    <p>Patents no longer pursued</p>
                </div>
            </div>
            <div id="patents-table"></div>
        </div>
    </div>
    <!-- Modal for viewing patent details -->
    <div id="view-patent-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Patent Details</h2>
                <i class="fas fa-times close-modal"></i>
            </div>
            <div class="modal-body" id="view-patent-details">
                <!-- Details will be injected here by JS -->
            </div>
            <div class="form-actions" style="padding: 20px;">
                <button type="button" class="btn-secondary close-modal">Close</button>
            </div>
        </div>
    </div>
    <script src="patent.js"></script>
</body>

</html>