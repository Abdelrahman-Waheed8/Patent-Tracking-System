<?php
include "../../src/config/config_session.php";
include "../../src/view/adminView.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>Document</title>
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
            <!-- Users Table -->
            <div class="card">
                <h2>Users List</h2>

                <table>
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="usersTable">
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
                    <option value="ip">IP Counsel</option>
                    <option value="examiner">Examiner</option>
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