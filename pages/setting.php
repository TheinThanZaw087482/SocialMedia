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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Metro Book</title>
    <style>
        /* Optional: Custom styling for the modal or button */
        .modal-body img#storyImagePreview { /* More specific ID for story image preview */
            max-width: 100%;
            margin-top: 10px;
        }

        #left-settings-panel {
            position: -webkit-sticky; /* For Safari */
            position: sticky;
            /* Adjust 'top' based on your fixed header's height.
               Body has margin-top: 80px. If your header is fixed and, say, 60px tall,
               this panel would effectively start 20px below the header.
               If you want it to stick right below a 60px fixed header that is within the 80px body margin,
               a 'top' value like 80px or 90px might be more suitable.
               Let's use 90px as an example, assuming header takes up part of the 80px margin.
            */
            top: 90px;
            height: calc(100vh - 110px); /* e.g., 100vh - top_value - bottom_margin_you_want (20px) */
            overflow-y: auto; /* Allows content within the sidebar to scroll if it's too long */
            align-self: flex-start; /* Ensures it aligns to the top if the row is a flex container */
            background-color: #f8f9fa; /* Light background for visibility */
            padding: 15px;
        }

        .stories-section .add-story {
            cursor: pointer; /* Indicate it's clickable */
        }
        /* Ensure reaction panel displays correctly */
        .group:hover .reaction-panel {
            display: flex !important;
        }
        .reaction-panel {
            bottom: 100%; /* Position above the like button */
            margin-bottom: 5px; /* Add some space */
        }

        /* Custom CSS for the gradient icon */
        .icon-saved {
            width: 32px; /* Increased size */
            height: 32px; /* Increased size */
            background: linear-gradient(to right, #ee0979, #ff6a00); /* Example gradient */
            border-radius: 8px; /* Slightly more rounded */
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px; /* Larger icon inside */
            margin-right: 15px; /* More space between icon and text */
        }

        /* Styling for the list item itself */
        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 20px; /* Increased padding for a larger overall item */
            cursor: pointer;
            border-radius: 10px; /* Slightly more rounded overall item */
            transition: background-color 0.2s;
        }

        .menu-item:hover {
            background-color: #f0f2f5; /* Light grey on hover, similar to Facebook */
        }

        .menu-item-text {
            font-weight: 500;
            color: #333;
            font-size: 1.1rem; /* Slightly larger text */
        }
    </style>
</head>

<body style="margin-top: 80px;">
    <div> <?php
        // Assuming header.php contains your <nav> element
        include("../includes/header.php")
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3" id="left-settings-panel">
                    <h4>Settings Section</h4>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2">
                            <a href="#" class="menu-item text-decoration-none">
                                <div class="icon-saved">
                                    <i class="fas fa-bookmark"></i>
                                </div>
                                <span class="menu-item-text">Saved</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="menu-item text-decoration-none">
                                <div class="icon-saved" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
                                    <i class="fas fa-history"></i>
                                </div>
                                <span class="menu-item-text">Memories</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="menu-item text-decoration-none">
                                <div class="icon-saved" style="background: linear-gradient(to right, #dc3545, #fd7e14);">
                                    <i class="fas fa-eye-slash"></i>
                                </div>
                                <span class="menu-item-text">Hide Post</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="menu-item text-decoration-none">
                                <div class="icon-saved" style="background: linear-gradient(to right, #dc3545, #ef476f);">
                                    <i class="fas fa-flag"></i>
                                </div>
                                <span class="menu-item-text">Report Notification</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="request-notifications.php" class="menu-item text-decoration-none">
                                <div class="icon-saved" style="background: linear-gradient(to right, #28a745, #82e0aa);">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <span class="menu-item-text">Request Notification</span>
                            </a>
                        </li>
                        <li> <a href="#" class="menu-item text-decoration-none">
                                <div class="icon-saved" style="background: linear-gradient(to right, #6c757d, #adb5bd);">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <span class="menu-item-text">Help & Support</span>
                            </a>
                        </li>
                        <li>
    <a href="#" class="menu-item text-decoration-none">
        <div class="icon-saved" style="background: linear-gradient(to right, #dc3545, #fd7e14);">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <span class="menu-item-text">Log Out</span>
    </a>
</li>
                    </ul>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>