<?php
include "../../src/config/config_session.php";
include "../../src/view/disclosureView.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="disclosure.css">

    <title>IP System</title>
</head>

<body>

    <div class="container">

        <!-- Sidebar -->
        <aside class="sidebar">
            <h2 class="logo">IP System</h2>

            <ul class="menu">
                <li><a href="../dashboard/dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="../inventors/inventors.php"><i class="fas fa-user-astronaut"></i>inventors</a></li>
                <li><a href="./disclosure.php" class="active"><i class="fas fa-lightbulb"></i> Disclosure</a></li>
                <li><a href="../patent/patent.php"><i class="fas fa-file"></i> Patents</a></li>
                <li><a href="../renewal/renewal.php"><i class="fas fa-sync"></i> Renewals</a></li>
                <li><a href="../licensing/licensing.php"><i class="fas fa-handshake"></i> Licensing</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
            </ul>


            <a class="logout-btn" href="../index.php">
                <button class="logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </a>
        </aside>

        <!-- Main Content -->
        <main class="page_structure">

            <!-- Navbar -->
            <nav class="panel-header">
                <div class="left">
                    <i class="fas fa-list icon-btn" onclick="toggleSidebar()"></i>
                    <h6>Invention Disclosure</h6>
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

            <!-- Content -->
            <section class="content">

                <form action="../../src/disclosure.php" class="disclosure-form" enctype="multipart/form-data" method="POST">
                    <h2>Invention Disclosure</h2>

                    <div class="input-group">
                        <label>Invention Title</label>
                        <input type="text" name="title">
                    </div>

                    <div class="input-group">
                        <label>Description</label>
                        <textarea rows="6" name="description"></textarea>
                    </div>

                    <div class="contributors-section">
                        <h3>Contributors</h3>
                        <div id="contributors-list">
                            <div class="contributor-row" style="display:flex; gap:10px; margin-bottom:10px;">
                                <input type="text" name="ContributorIDs[]" placeholder="Contributor ID" style="flex:2; padding:5px;">
                                <input type="text" name="contributionPercentages[]" placeholder="%" style="flex:1; padding:5px;">
                                <button type="button" onclick="removeRow(this)">-</button>
                            </div>
                        </div>
                        <button type="button" onclick="addContributor()" class="submit-btn">+ Add Contributor</button>
                    </div>

                    <div class="upload-container">
                        <label class="upload-box">
                            <input type="file" name="file" id="file-upload">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Upload Files</span>
                        </label>
                    </div>

                    <?php
                    $disclosureView = new disclosureView();
                    $disclosureView->displaySessionInfo();
                    ?>

                    <div class="form-footer">
                        <button type="submit" class="submit-btn">Submit</button>
                    </div>


                </form>

            </section>

        </main>

    </div>

    <script src="disclosure.js"></script>
</body>

</html>