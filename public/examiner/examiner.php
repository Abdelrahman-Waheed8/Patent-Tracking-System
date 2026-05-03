<?php 
include "../../src/examiner.php"; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Examiner Panel</title>

    <link rel="stylesheet" href="examiner.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="container">

        <!-- Navbar -->
        <nav class="panel-header">

            <div class="left">
                <i class="fas fa-list icon-btn"></i>
                <h2>Examiner Panel</h2>
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

            <div class="header-actions">
                <h2>Pending Patent Reviews</h2>
            </div>

            <section>
                <table>
                    <thead>
                        <tr>
                            <th>TITLE</th>
                            <th>APP. NUMBER</th>
                            <th>FILING DATE</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if(!empty($allRequests)): ?>
                        <?php foreach($allRequests as $row): ?>
                        <tr data-id="<?= $row['disc_ID'] ?>" data-title="<?= htmlspecialchars($row['Title']) ?>"
                            data-desc="<?= htmlspecialchars($row['Description']) ?>" data-appnum="<?= $row['appNum'] ?>"
                            data-date="<?= $row['FilingDate'] ?>" data-status="<?= $row['Status'] ?>">

                            <td>
                                <strong><?= $row['Title'] ?></strong><br>
                                <small>ID: <?= $row['disc_ID'] ?></small>
                            </td>

                            <td><?= $row['appNum'] ?></td>

                            <td><?= $row['FilingDate'] ?></td>

                            <td>
                                <span class="status-badge status-<?= strtolower($row['Status']) ?>">
                                    <?= $row['Status'] ?>
                                </span>
                            </td>

                            <td>
                                <button class="review-btn" onclick="openModal(this)">
                                    Review
                                </button>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">
                                No pending reviews found.
                            </td>
                        </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </section>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="modal">

        <div class="modal">

            <div class="modal-header">
                <h2>Patent Review</h2>
                <i class="fas fa-times close-modal" onclick="closeModal()"></i>
            </div>

            <form action="../../src/examiner.php" method="POST">

                <input type="hidden" name="disc_id" id="disc_id">
                <input type="hidden" name="status" id="status_input">

                <div id="review-details" style="padding:25px;"></div>

                <div class="decision-buttons" style="padding:0 25px 25px;">
                    <button type="submit" class="approve-btn" onclick="setStatus('approved')">
                        Approve
                    </button>

                    <button type="submit" class="reject-btn" onclick="setStatus('rejected')">
                        Reject
                    </button>

                    <button type="submit" class="revision-btn" onclick="setStatus('pending')">
                        Need Revision
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script src="examiner.js"></script>

</body>

</html>