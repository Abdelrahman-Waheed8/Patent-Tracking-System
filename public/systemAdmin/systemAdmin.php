<?php
// Load DB connection and app config first so models can extend DBH
include "../../src/config/config.php";
include "../../src/config/config_session.php";

// Licensing model/view for displaying licenses in the admin panel
include "../../src/Model/licensingModel.php";
include "../../src/View/licensingView.php";

// Admin view (UI helpers)
include "../../src/view/adminView.php";

$licensingView = new licensingView();
$licenses = $licensingView->showAllLicenses();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>Admin Panel</title>
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="panel-header">
            <div class="left">
                <i class="fas fa-list icon-btn" onclick="toggleSidebar()"></i>
                <h6>System Admin</h6>
            </div>
            <div class="right">
                <a class="logout-btn" href="../index.php">
                    <button class="logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </a>
            </div>
        </nav>

        <div class="structure_page">
            <!-- Create User Form -->
            <section>
                <form action="../../src/admin.php" method="POST">
                    <div class="first_name">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="fname">
                    </div>

                    <div class="last_name">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="lname">
                    </div>

                    <div class="email">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                    </div>

                    <div class="password">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <select id="role" name="role">
                        <option value="" disabled selected hidden>Select Role</option>
                        <option value="IP Counsel">IP Counsel</option>
                        <option value="Examiner">Examiner</option>
                    </select>
                    <button type="submit">
                        <i class="fas fa-user"></i>create account
                    </button>

                    <?php
                $adminView = new adminView();
                $adminView->displayErrorcreateUser();
                ?>
                </form>
            </section>

            <!-- Licenses Table -->
            <div class="card">
                <h2>All Licenses</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Patent</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Territory</th>
                            <th>Revenue Model</th>
                            <th>Rate/Value</th>
                            <th>Amount</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($licenses) && count($licenses) > 0) {
                            foreach ($licenses as $license) {
                                $status = strtolower($license['status'] ?? 'terminated');
                                $statusClass = $status === 'active' ? 'status-active' : ($status === 'expired' ? 'status-expired' : 'status-terminated');
                        ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($license['patent_number'] ?? 'N/A'); ?></strong></td>
                                    <td><?php echo htmlspecialchars($license['company'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($license['license_type'] ?? ''); ?></td>
                                    <td><span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($license['status'] ?? ''); ?></span></td>
                                    <td><?php echo htmlspecialchars($license['territory'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($license['revenue_model'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($license['revenue_value'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($license['amount'] ?? '0.00'); ?></td>
                                    <td><?php echo htmlspecialchars($license['end_date'] ?? ''); ?></td>
                                    <td>
                                        <?php if (strcasecmp(($license['status'] ?? ''), 'Pending') === 0) : ?>
                                            <button class="btn-accept" onclick="adminApproveLicense(<?php echo (int)$license['id']; ?>)">Accept</button>
                                            <button class="btn-reject" onclick="adminRejectLicense(<?php echo (int)$license['id']; ?>)">Reject</button>
                                            <button class="btn-reject" onclick="adminDeleteLicense(<?php echo (int)$license['id']; ?>)">Delete</button>
                                        <?php else: ?>
                                            <button class="btn-reject" onclick="adminDeleteLicense(<?php echo (int)$license['id']; ?>)">Delete</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="10" style="text-align: center; color: #999;">No licenses found</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModalOverlay" class="modal-overlay"></div>
    <div id="editModal" class="modal">
        <div class="modal-header">
            <h2>Edit User</h2>
            <i class="fas fa-times icon-btn" onclick="closeModal()"></i>
        </div>
        <div class="modal-body">
            <form id="editForm">
                <div class="first_name">
                    <label for="edit_first_name">First Name</label>
                    <input type="text" id="edit_first_name">
                </div>
                <div class="last_name">
                    <label for="edit_last_name">Last Name</label>
                    <input type="text" id="edit_last_name">
                </div>
                <div class="email">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email">
                </div>
                <select id="edit_role">
                    <option value="IP Counsel">IP Counsel</option>
                    <option value="Examiner">Examiner</option>
                </select>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button type="button" class="btn-save" onclick="saveChanges()">Save Changes</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="js.js"></script>
</body>

</html>