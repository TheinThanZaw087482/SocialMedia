<?php
include("../includes/db.php");
include("../process/post.php");
$userid = $_SESSION['userid'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assests/css/profile.css">
    <link rel="stylesheet" href="../assests/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Metro Book</title>
    <style>
        /* Optional: Custom styling for the modal or button */
        .modal-body img#storyImagePreview {
            /* More specific ID for story image preview */
            max-width: 100%;
            margin-top: 10px;
        }

        #left-settings-panel {
            position: -webkit-sticky;
            /* For Safari */
            position: sticky;
            /* Adjust 'top' based on your fixed header's height.
               Body has margin-top: 80px. If your header is fixed and, say, 60px tall,
               this panel would effectively start 20px below the header.
               If you want it to stick right below a 60px fixed header that is within the 80px body margin,
               a 'top' value like 80px or 90px might be more suitable.
               Let's use 90px as an example, assuming header takes up part of the 80px margin.
            */
            top: 90px;
            height: calc(100vh - 110px);
            /* e.g., 100vh - top_value - bottom_margin_you_want (20px) */
            overflow-y: auto;
            /* Allows content within the sidebar to scroll if it's too long */
            align-self: flex-start;
            /* Ensures it aligns to the top if the row is a flex container */
            background-color: #f8f9fa;
            /* Light background for visibility */
            padding: 15px;
        }

        #left-settings-panel::-webkit-scrollbar {
            display: none;
        }

        /* Custom CSS for the gradient icon */
        .icon-saved {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin-right: 15px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 18px 0;
            margin-bottom: 5px;
            border-radius: 8px;
            /* Rounded corners for menu items */
            transition: background-color 0.3s ease;
        }

        .menu-item:hover {
            background-color: #252426;
            transform: scale(1.02);
        }

        .menu-item-text {
            font-weight: 500;
            color: #333;
            font-size: 1.1rem;
            /* Slightly larger text */
        }

        .textWhite {
            color: white;
        }

        .custom-password-modal .modal-content {
            background-color: #ffffff;
            border-radius: 1.25rem;
            padding: 1rem;
        }

        .toggle-password {
            position: absolute;
            top: 38px;
            right: 15px;
            cursor: pointer;
            color: #6c757d;
        }

        .toggle-password:hover {
            color: #000;
        }
    </style>
</head>

<body style="background: #252426;">
    <div> <?php

            include("../includes/header.php")
            ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3" id="left-settings-panel" style="background: #252426;">
                    <h4 class="textWhite">Settings Section</h4>
                    <ul class="list-unstyled mt-3" style="list-style-type: none;">
                        <li class="mb-2 ">
                            <a href="../pages/savepost.php" class="menu-item text-decoration-none">
                                <div class="icon-saved"
                                    style="background: linear-gradient(to right,rgb(255, 52, 52), #2575fc);">
                                    <i class="fas fa-bookmark"></i>
                                </div>
                                <span class="menu-item-text textWhite">Saved</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="../pages/hidepost.php" class="menu-item text-decoration-none">
                                <div class="icon-saved"
                                    style="background: linear-gradient(to right, #dc3545, #fd7e14);">
                                    <i class="fas fa-eye-slash"></i>
                                </div>
                                <span class="menu-item-text textWhite">Hide Post</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="../pages/report-notifications.php" class="menu-item text-decoration-none">
                                <div class="icon-saved"
                                    style="background: linear-gradient(to right, #dc3545, #ef476f);">
                                    <i class="fas fa-flag"></i>
                                </div>
                                <span class="menu-item-text textWhite">Report Notification</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="../pages/request-notifications.php" class="menu-item text-decoration-none">
                                <div class="icon-saved"
                                    style="background: linear-gradient(to right, #28a745, #82e0aa);">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <span class="menu-item-text textWhite">Request Notification</span>
                            </a>
                        </li>
                        <li>
                            <a href="../process/logout.php" class="menu-item text-decoration-none">
                                <div class="icon-saved"
                                    style="background: linear-gradient(to right, #dc3545, #fd7e14);">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <span class="menu-item-text textWhite">Log Out</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="menu-item text-decoration-none" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal">
                                <div class="icon-saved"
                                    style="background: linear-gradient(to right,rgba(162, 255, 100, 1),rgba(134, 136, 255, 1));">
                                    <i class="fa-solid fa-key textWhite"></i>
                                </div>
                                <span class="menu-item-text textWhite">Change Password</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade custom-password-modal" id="changePasswordModal" tabindex="-1"
        aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form id="changePasswordForm" class="needs-validation" novalidate>
                        <!-- Old Password Field -->
                        <div class="mb-3 position-relative">
                            <label for="oldPassword" class="form-label">Old Password</label>
                            <input type="password" class="form-control pe-5" id="oldPassword" required>
                            <i class="fas fa-eye toggle-password" data-target="oldPassword"></i>
                            <div class="invalid-feedback">Please enter your old password.</div>
                        </div>

                        <!-- New Password Field -->
                        <div class="mb-3 position-relative">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control pe-5" id="newPassword" required>
                            <i class="fas fa-eye toggle-password" data-target="newPassword"></i>
                            <div class="invalid-feedback">Please enter a new password.</div>
                        </div>

                        <!-- Confirm New Password Field -->
                        <div class="mb-3 position-relative">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control pe-5" id="confirmPassword" required>
                            <i class="fas fa-eye toggle-password" data-target="confirmPassword"></i>
                            <div class="invalid-feedback">Please confirm your new password.</div>
                        </div>

                        <div id="passwordChangeMessage"></div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary rounded-pill">Update
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', () => {
                const targetId = icon.getAttribute('data-target');
                const input = document.getElementById(targetId);

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>