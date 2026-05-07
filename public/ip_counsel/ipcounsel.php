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
                        <!-- Cards -->
<div class="cards">
    <div class="card">
        <div class="card-top">
            <i class="fas fa-hourglass-half"></i>
            <span>Pending Review</span>
        </div>
        <h1 id="pending-review-patents">0</h1>
        <p>Waiting for examiner review</p>
    </div>

    <div class="card">
        <div class="card-top">
            <i class="fas fa-check-circle"></i>
            <span>Active Patents</span>
        </div>
        <h1 id="active-patents">0</h1>
        <p>Approved and active</p>
    </div>

    <div class="card">
        <div class="card-top">
            <i class="fas fa-ban"></i>
            <span>Rejected</span>
        </div>
        <h1 id="rejected-patents">0</h1>
        <p>Applications rejected</p>
    </div>

    <div class="card">
        <div class="card-top">
            <i class="fas fa-sync-alt"></i>
            <span>Upcoming Renewals</span>
        </div>
        <h1 id="upcoming-renewals-patents">0</h1>
        <p>Expiring soon</p>
    </div>
</div>
            <div class="header-actions">
                <h2>Pending Patent Reviews</h2>
            </div>
            <section>
                <table id="examiner-table">
                    <thead>
                        <tr>
                            <th>TITLE</th>
                            <th>INVENTOR</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                        <thead>
                        <tbody id="table_body">

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
    <script src="./ipcounsel.js"></script>
</body>

</html>