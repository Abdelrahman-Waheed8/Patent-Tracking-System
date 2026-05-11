<?php 
include "../../src/examiner.php"; 
include "../../src/view/examinerView.php";
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
                        <tr data-id="<?= $row['disc_ID'] ?>" 
                            data-title="<?= htmlspecialchars($row['Title']) ?>"
                            data-desc="<?= htmlspecialchars($row['Description']) ?>" 
                            data-appnum="<?= $row['appNum'] ?>"
                            data-date="<?= $row['FilingDate'] ?>" 
                            data-status="<?= $row['Status'] ?>"
                            data-inventors="<?= htmlspecialchars($row['Inventors'] ?? '') ?>" 
                            data-companies="<?= htmlspecialchars($row['ExternalCompanies'] ?? '') ?>"
                            data-jurisdictions="<?= htmlspecialchars($row['Jurisdictions'] ?? '') ?>"
                            data-files="<?= htmlspecialchars($row['FilePaths'] ?? '') ?>">

                            <td>
                                <strong><?= htmlspecialchars($row['Title']) ?></strong><br>
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
                    <button type="submit" class="approve-btn" onclick="setStatus('Legal_Review')">
                        Approve
                    </button>
                    <button type="button" class="reject-btn" onclick="openRejectModal()">
                        Reject
                    </button>
                    <button type="submit" class="revision-btn" onclick="setStatus('pending')">
                        Need Revision
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div class="modal-overlay" id="reject-modal">
        <div class="modal">
            <div class="modal-header">
                <h2>Rejection Reason</h2>
                <i class="fas fa-times close-modal" onclick="closeRejectModal()"></i>
            </div>

            <form action="../../src/examiner.php" method="POST">
                <input type="hidden" name="disc_id" id="reject_disc_id">
                <input type="hidden" name="status" value="rejected">
                
                <div style="padding:25px;">
                    <div class="detail-item" style="margin-bottom: 15px;">
                        <label for="prior-art" style="font-weight: bold; display: block; margin-bottom: 5px;">Prior art refrence</label>
                        <input type="text" name="prior-art" placeholder="Ex: Patent ID" style="width: 100%; font-weight: bold; display: block; margin-bottom: 5px;">
                    </div>
                    
                    <div class="detail-item">
                        <label for="additional_comments" style="font-weight: bold; display: block; margin-bottom: 5px;">Reason For Rejection:</label>
                        <textarea name="additional_comments" id="additional_comments" rows="5" style="width:100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd; resize: vertical;" placeholder="Provide details for the rejection..."></textarea>
                    </div>
                </div>

                <?php
                $view = new ExaminerView();
                $view->displayErrors();
                ?>

                <div class="decision-buttons" style="padding:0 25px 25px;">
                    <button type="submit" class="reject-btn">
                        Confirm Rejection
                    </button>
                    <button type="button" class="revision-btn" onclick="closeRejectModal()">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="examiner.js"></script>

</body>
</html>