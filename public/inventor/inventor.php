<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="inventor.css">

    <title>IP System</title>
</head>

<body>

    <div class="container">

        <!-- Sidebar -->
        <aside class="sidebar">
            <h2 class="logo">IP System</h2>

            <ul class="menu">
                <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="./inventor.php"><i class="fas fa-user-astronaut"></i>inventore</a></li>

                <li><a href="../disclosure/disclosure.php"><i class="fas fa-lightbulb"></i> Disclosure</a></li>
                <li><a href="#"><i class="fas fa-file"></i> Patents</a></li>
                <li><a href="#"><i class="fas fa-sync"></i> Renewals</a></li>
                <li><a href="#"><i class="fas fa-handshake"></i> Licensing</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
            </ul>

            <button class="logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </aside>

        <!-- Main Content -->
        <main class="page_structure">

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

            <!-- Content -->
            <section class="content">

                <form class="disclosure-form">
                    <h2>Invention Disclosure</h2>

                    <div class="input-group">
                        <label>Invention Title</label>
                        <input type="text" required>
                    </div>

                    <div class="input-group">
                        <label>Description</label>
                        <textarea rows="6"></textarea>
                    </div>

                    <div class="input-group">
                        <label>Prior Art References</label>
                        <input type="text">
                    </div>

                    <div class="upload-container">
                        <label class="upload-box">
                            <input type="file" multiple>
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Upload Files</span>
                        </label>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="submit-btn">Submit</button>
                    </div>
                </form>

            </section>

        </main>

    </div>

    <script src="inventore.js"></script>
</body>

</html>