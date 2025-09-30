<?php
include("../includes/db.php");
include("../process/post.php");
include("../process/reaction_helper.php");
include("../includes/image_gallery.php");

$loggedInUserId = $_SESSION['userid'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assests/css/style.css">
    <link rel="stylesheet" href="../assests/css/friend-add-style.css">
    <link rel="stylesheet" href="../assests/css/reaction-style.css">
    <link rel="stylesheet" href="../assests/css/image.css">
    <link rel="stylesheet" href="../assests/css/story-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <title>Metro Book</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap');

        body {
            font-family: "Dosis", sans-serif;
        }

        /* Custom CSS for the sticky sidebar and scrollbar hiding */
        #left-settings-panel {
            position: -webkit-sticky;
            /* For Safari */
            position: sticky;
            top: 20px;
            /* Adjust based on your header's height + desired top margin */
            height: calc(100vh - 40px);
            /* Calculates height to fill viewport minus top and some bottom space */
            /* overflow-y: auto; */
            /* Removed to prevent default scrollbar */
            align-self: flex-start;
            /* Ensures it aligns to the top if the row is a flex container */
            background-color: #252426;
            /* Dark background */
            padding: 15px;
            border-radius: 10px;
            /* Rounded corners */
        }

        /* Hide scrollbar for Webkit browsers (Chrome, Safari) */
        #left-settings-panel::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for Firefox */
        #left-settings-panel {
            scrollbar-width: none;
            /* Firefox */
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
            padding: 5px 0;
            margin-bottom: 5px;
            border-radius: 8px;
            /* Rounded corners for menu items */
            transition: background-color 0.3s ease;
        }

        .menu-item:hover {
            background-color: #252426;
            transform: scale(1.07);
        }

        .menu-item-text {
            font-weight: 500;
            color: #333;
            font-size: 1.1rem;
            /* Slightly larger text */
        }

        /*Suiko */
        .comment-box {
            display: flex;
            align-items: center;
            background-color: #f0f2f5;
            border-radius: 999px;
            padding: 8px 12px;
            margin-right: 10px;
            max-width: 650px;
            flex: 1;
            min-width: 100px;
            transition: box-shadow 0.3s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .social-icons-wrapper {
            position: absolute;

            top: 0;
            /* Adjust as needed */
            right: 0;
            /* Adjust as needed */
            margin-top: 10px;
            /* Adjust as needed */
            margin-right: 10px;
            /* Adjust as needed */
            z-index: 1000;
            /* Ensure it's on top */
            display: flex;
            /* Allows positioning of toggle and content */
            flex-direction: column;
            /* Stacks toggle and content vertically */
            align-items: flex-end;
            /* Aligns content to the right */
        }

        .circular-dropdown-toggle {
            width: 40px;
            /* Same size as your social icons */
            height: 40px;
            border-radius: 50%;
            background-color: #333;
            /* Dark background similar to your example */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 1.2em;
            /* Size of the three dots icon */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .circular-dropdown-toggle:hover {
            background-color: #555;
            /* Slightly lighter on hover */
        }

        .social-icons-content {
            /* Styles for the container of the social icons */
            margin-top: 10px;
            /* Space below the toggle icon */

            background-color: white;
            /* Or transparent, depending on desired look */
            border-radius: 8px;
            /* Slightly rounded corners */
            padding: 8px;
            /* Padding inside the container */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            /* Initially hidden, will be controlled by JS */
            display: none;
            flex-direction: column;
            /* This will stack the icons vertically */
            align-items: center;
            /* Center the icons within their new vertical stack */
        }


        /* Add these new styles (from previous response) */
        .social-icons-wrapper {
            position: absolute;
            top: 0;
            /* Adjust as needed */
            right: 0;
            /* Adjust as needed */
            margin-top: 10px;
            /* Adjust as needed */
            margin-right: 10px;
            /* Adjust as needed */
            z-index: 1000;
            /* Ensure it's on top */
            display: flex;
            /* Allows positioning of toggle and content */
            flex-direction: column;
            /* Stacks toggle and content vertically */
            align-items: flex-end;
            /* Aligns content to the right */
        }

        .circular-dropdown-toggle {
            width: 40px;
            /* Same size as your social icons */
            height: 40px;
            border-radius: 50%;
            background-color: #333;
            /* Dark background similar to your example */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 1.2em;
            /* Size of the three dots icon */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .circular-dropdown-toggle:hover {
            background-color: #555;
            /* Slightly lighter on hover */
        }

        .social-icons-content {
            /* Styles for the container of the social icons */
            margin-top: 10px;
            /* Space below the toggle icon */
            background-color: white;
            /* Or transparent, depending on desired look */
            border-radius: 8px;
            /* Slightly rounded corners */
            padding: 8px;
            /* Padding inside the container */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            /* Initially hidden, will be controlled by JS */
            display: none;
            flex-direction: column;
            /* This will stack the icons vertically */
            align-items: center;
            /* Center the icons within their new vertical stack */
        }


        /* Modify your existing .example-2 styles */
        .example-2.vertical-layout {
            /* Apply this specific style when the vertical-layout class is present */
            display: flex;
            /* Keep flex for children arrangement */
            flex-direction: column;
            /* Stack icons vertically */
            align-items: center;
            /* Center them horizontally */
            gap: 2px;
            /* Space between vertical icons */
        }

        /* Ensure individual icon content still displays correctly */
        .example-2 .icon-content {
            margin: 0;
            /* Remove horizontal margin for vertical layout */
            margin-bottom: 5px;
            /* Add some vertical spacing if needed */
            position: relative;
            /* Keep for tooltips */
            display: flex;
            /* Make icon content a flex container to align icon and tooltip */
            align-items: center;
            /* Vertically align icon and tooltip */
            width: 100%;
            /* Ensure it takes full width for alignment */
            justify-content: flex-start;
            /* Align icon to the start */
        }

        /* MODIFY THIS SECTION FOR TOOLTIP PLACEMENT */
        .example-2 .icon-content .tooltip {
            position: absolute;
            left: auto;
            /* Reset left */
            right: calc(100% + 10px);
            /* Position to the right of the icon-content */
            top: 50%;
            /* Center vertically relative to icon-content */
            transform: translateY(-50%);
            /* Adjust for perfect vertical centering */

            color: #fff;
            padding: 6px 10px;
            border-radius: 5px;
            opacity: 0;
            visibility: hidden;
            font-size: 14px;
            transition: all 0.3s ease;
            white-space: nowrap;
            /* Prevent text from wrapping */
        }

        .example-2 .icon-content:hover .tooltip {
            opacity: 1;
            visibility: visible;
            /* top: -50px; */
            /* Remove or comment this out */
            /* The new top/left/transform properties defined above will take effect */
        }

        .example-2 .icon-content a {
            position: relative;
            overflow: hidden;
            display: flex;
            /* Keep this as flex for the icon itself */
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: #4d4d4d;
            background-color: #fff;
            transition: all 0.3s ease-in-out;
        }

        .example-2 .icon-content a:hover {
            box-shadow: 3px 2px 45px 0px rgb(0 0 0 / 12%);
            color: white;
        }

        .example-2 .icon-content a svg,
        .example-2 .icon-content a i {
            /* Added 'i' for Font Awesome icons */
            position: relative;
            z-index: 1;
            width: 20px;
            height: 20px;
        }

        .example-2 .icon-content a .filled {
            position: absolute;
            top: auto;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            background-color: #000;
            transition: all 0.3s ease-in-out;
        }

        .example-2 .icon-content a:hover .filled {
            height: 100%;
        }

        /* Color specific tooltips and filled backgrounds */
        .example-2 .icon-content a[data-social="spotify"] .filled,
        .example-2 .icon-content a[data-social="spotify"]~.tooltip {
            background-color: #1db954;
        }

        .example-2 .icon-content a[data-social="pinterest"] .filled,
        .example-2 .icon-content a[data-social="pinterest"]~.tooltip {
            background-color: #bd081c;
        }

        .example-2 .icon-content a[data-social="dribbble"] .filled,
        .example-2 .icon-content a[data-social="dribbble"]~.tooltip {
            background-color: #ea4c89;
        }

        .example-2 .icon-content a[data-social="telegram"] .filled,
        .example-2 .icon-content a[data-social="telegram"]~.tooltip {
            background-color: #0088cc;
        }

        /* postmodal scroll */
        /* Styles for the overall modal container */
        .post-modal {
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 90%;
            /* Adjust as needed */
            max-width: 500px;
            /* Adjust max width */
            display: flex;
            flex-direction: column;
            /* This is important for header, body, footer stacking */
            max-height: 90vh;
            /* Limit the overall height of the modal (e.g., 90% of viewport height) */
        }

        /* Styles for the modal header and footer */
        .post-modal-header,
        .post-modal-footer {
            /* Your existing styles for header/footer */
            padding: 15px 20px;
            background-color: #f0f2f5;
            /* Example background */
            flex-shrink: 0;
            /* Prevents header/footer from shrinking */
        }

        .post-modal-header {
            border-bottom: 1px solid #ddd;
        }

        .post-modal-footer {
            border-top: 1px solid #ddd;
            text-align: right;
            /* Example */
        }

        /* THE KEY STYLES FOR THE SCROLLABLE BODY */
        .post-modal-body {
            flex-grow: 1;
            /* Allows the body to take up available space */
            overflow-y: auto;
            /* Enables vertical scrollbar if content overflows */
            padding: 20px;
        }

        /* Optional: Style the scrollbar (for better aesthetics) */
        .post-modal-body::-webkit-scrollbar {
            width: 8px;
            /* Width of the scrollbar */
        }

        .post-modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Color of the scrollbar track */
            border-radius: 10px;
        }

        .post-modal-body::-webkit-scrollbar-thumb {
            background: #888;
            /* Color of the scrollbar thumb */
            border-radius: 10px;
        }

        .post-modal-body::-webkit-scrollbar-thumb:hover {
            background: #555;
            /* Color of the scrollbar thumb on hover */
        }

        #preview-container {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            max-height: 300px;
            /* You can adjust this height */
            overflow-y: auto;
        }

        #preview-container img {
            width: 100px;
            /* Sets a fixed width for the preview */
            height: 100px;
            /* Sets a fixed height for the preview */
            object-fit: cover;
            /* Ensures the image covers the area without distortion */
            border-radius: 5px;
            /* Adds slightly rounded corners to the preview */
        }

        /* More specific selector */
        .post-modal-body .post-textarea {
            height: 80px !important;
            /* Or a smaller pixel value like 50px */
            min-height: 50px !important;
            /* Ensure it doesn't shrink smaller than this */
            box-sizing: border-box;
            /* Important for consistent sizing */
            resize: none;
            /* Allow user to resize vertically, if desired */
        }

        .top-right-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }

        .top-right-close:hover {
            color: #000;
        }

        .dropdown-menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
            /* space between items */
            padding: 8px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            border: none;
            min-width: 200px;
            z-index: 999;
            animation: dropdownFade 0.2s ease-out;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background-color: #f9f9f9;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .dropdown-item i {
            margin-right: 10px;
            font-size: 16px;
        }

        .dropdown-item:hover {
            background-color: #f0f0f0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transform: translateY(-1px);
        }

        .check-icon {
            color: green;
            font-size: 14px;
            display: none;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .post-modal {
                height: 75%;
            }
        }

        /**end */

        /* post dropdown */

        /* Styling for the dropdown container */
        .dropdown {
            position: relative;
            display: inline-block;
            font-family: 'Inter', sans-serif;
        }

        /* Styling for the dropdown toggle button */
        .dropdown-toggle {
            background-color: #e0e0e0;
            /* Light gray background for a modern look */
            color: #333;
            /* Darker text for better contrast */
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            /* Slightly rounded corners */
            cursor: pointer;
            font-size: 15px;
            /* Slightly smaller font for a sleek look */
            font-weight: 500;
            /* Medium weight */
            display: flex;
            align-items: center;
            gap: 8px;
            /* Space between icon and text */
            transition: background-color 0.3s ease, box-shadow 0.2s ease, color 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            outline: none;
            /* Remove default focus outline */
        }

        .dropdown-toggle:hover {
            background-color: #d0d0d0;
            /* Slightly darker on hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            /* More pronounced shadow on hover */
        }

        .dropdown-toggle:focus {
            background-color: #c0c0c0;
            /* Even darker on focus */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #1a1a1a;
        }

        /* Styling for the dropdown menu */
        .dropdown-menu {
            display: none;
            /* Hidden by default */
            position: absolute;
            background-color: #ffffff;
            /* White background for the menu */
            min-width: 180px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            z-index: 1000;
            /* Ensure it's above other content */
            border-radius: 8px;
            overflow: hidden;
            /* Ensures rounded corners apply to content */
            margin-top: 4px;
            /* Space below the toggle button */
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            left: 0;
            /* Align menu to the left of the toggle button */
        }

        /* Show the dropdown menu when the dropdown container is hovered or the toggle button is focused */
        .dropdown:hover .dropdown-menu,
        .dropdown-toggle:focus+.dropdown-menu,
        /* For keyboard navigation */
        .dropdown:focus-within .dropdown-menu {
            /* More robust for focus inside dropdown */
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* Styling for individual dropdown items */
        .dropdown-item {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #ffffff;
            border: none;
            width: 100%;
            /* Make button fill the width */
            text-align: left;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease;
            font-size: 15px;
            font-weight: 400;
            margin: 0;
            padding: 0;
        }

        .dropdown-item:hover {
            background-color: #f0f0f0;
            /* Light gray on hover */
            color: #007bff;
            /* Primary blue text on hover */
        }

        /* Styling for the check icon */
        .check-icon {
            margin-left: auto;
            /* Pushes the checkmark to the right */
            color: #28a745;
            /* Green color for checkmark */
            display: none;
            /* Hidden by default */
            font-size: 0.9em;
            /* Slightly smaller checkmark */
        }

        /* Show check icon when the specific dropdown item is hovered or focused */
        .dropdown-item:hover .check-icon,
        .dropdown-item:focus .check-icon {
            display: inline-block;
        }

        /* Icons styling */
        .fa-solid,
        .fas {
            font-size: 1em;
            color: #666;
            /* Slightly muted icon color */
        }

        .dropdown-item:hover .fa-solid,
        .dropdown-item:hover .fas {
            color: #007bff;
            /* Blue icon on hover */
        }

        /* Rotate chevron icon when dropdown is open (via hover/focus on parent) */
        .dropdown:hover .fa-chevron-down,
        .dropdown:focus-within .fa-chevron-down {
            transform: rotate(180deg);
        }

        .fa-chevron-down {
            transition: transform 0.3s ease;
        }

        /* Ensure text is visible by adjusting color and removing unnecessary display: none */
        .dropdown-toggle::after {
            display: none !important;
            /* Keep this if you don't want the default Bootstrap-like caret */
        }

        .textWhite {
            color: white;
        }

        .iconColor {
            background: linear-gradient(20deg, #00E1FD, #FC007A);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
            font-size: 1.5rem;
        }

        .borderStyle {
            border: 1px solid;
            padding: 20px;
            border-image: linear-gradient(to left, rgb(107, 212, 219), rgb(224, 130, 180)) 1 / 1 / 0 stretch;
            box-shadow:
                rgba(107, 212, 219, 0.35) 0px 30px 80px -10px,
                rgba(224, 130, 180, 0.3) 0px 15px 30px -15px,
                rgba(221, 219, 224, 0.4) 0px -2px 3px 0px inset;

        }

        .postBorder {
            border: 1px solid;
            padding: 20px;
            border-image: linear-gradient(to left, #e0aa3e, #f9f295) 1 / 1 / 0 stretch;
            box-shadow:
                rgba(107, 212, 219, 0.35) 0px 30px 80px -10px,
                rgba(224, 130, 180, 0.3) 0px 15px 30px -15px,
                rgba(221, 219, 224, 0.4) 0px -2px 3px 0px inset;
        }

        .btnDesign {
            border: 2px solid #C5558E !important;
            border-radius: 999px !important;
            background: rgb(255, 255, 255);
            color: white;
        }

        .btnDesign:hover {
            border-color: #0D47A1 !important;
            box-shadow:
                0 0 15px #a855f7,
                0 0 30px #9333ea,
                inset 0 0 20px rgba(168, 85, 247, 0.4);
        }

        .cmtBoxStyle {
            background-color: #252426;
            border: 2px solid #C5558E !important;
            border-radius: 999px !important;
        }

        /* new */
        .post-option:hover {
            background-color: #252426;
            transform: scale(1.07);
            color: black;
        }

        /* Add this to style the <a> tag as the hoverable area */
        .create-post-bottom>a {
            display: block;
            /* Make the anchor tag take up space */
            text-decoration: none;
            /* Keep original style */
            color: inherit;
            /* Inherit color from parent or define a base color */
        }

        /* This is the key: Apply the hover to the <a> and let it affect its child .post-option */
        .create-post-bottom>a:hover .post-option {
            background-color: #252426;
            transform: scale(1.07);
            color: black;
        }

        /* Also ensure the color on the span and icon changes if needed */
        .create-post-bottom>a:hover .post-option span {
            color: black;
            /* Or your desired hover color */
        }

        .create-post-bottom>a:hover .post-option .iconColor {
            color: black;
            /* Or your desired hover color */
        }
    </style>

</head>

<body style="background: #252426;">

    <div class="container-fluid">
        <?php

        // Assuming header.php is a full-width navigation bar
        if (file_exists("../includes/header.php")) {
            include("../includes/header.php");
        } else {
            echo '<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3"><div class="container-fluid"><a class="navbar-brand" href="#">Metro Book (Header Placeholder)</a></div></nav>';
        }
        ?>

        <?php

        // Assuming header.php is a full-width navigation bar
        if (file_exists("../includes/mainStory.php")) {
            include("../includes/mainStory.php");
        } else {
            echo '<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3"><div class="container-fluid"><a class="navbar-brand" href="#">Metro Book (Header Placeholder)</a></div></nav>';
        }
        ?>

        <div class="container-fluid" style="background: #252426;">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block" id="left-settings-panel"
                    style="padding: none; background: #252426;">
                    <h2 class="textWhite">Settings</h2>
                    <li>
                        <a href="savepost.php" class="menu-item text-decoration-none">
                            <div class="icon-saved"
                                style="background: linear-gradient(to right,rgb(255, 25, 25),rgb(48, 165, 255));">
                                <i class="fas fa-bookmark textWhite"></i>
                            </div>
                            <span class="menu-item-text textWhite">Saved</span>
                        </a>
                    </li>

                    <li>
                        <a href="hidepost.php" class="menu-item text-decoration-none">
                            <div class="icon-saved" style="background: linear-gradient(to right, #dc3545, #fd7e14);">
                                <i class="fas fa-eye-slash textWhite"></i>
                            </div>
                            <span class="menu-item-text textWhite">Hide Post</span>
                        </a>
                    </li>
                    <?php
                    $user = get_user_by_userID($_SESSION['userid']);
                    $userType = $user['userType'];
                    if ($userType == "admin") { ?>
                        <li>
                            <a href="report-notifications.php" class="menu-item text-decoration-none">
                                <div class="icon-saved" style="background: linear-gradient(to right, #dc3545, #ef476f);">
                                    <i class="fas fa-flag textWhite"></i>
                                </div>
                                <span class="menu-item-text textWhite">Report Notification</span>
                            </a>
                        </li>

                        <li>
                            <a href="request-notifications.php" class="menu-item text-decoration-none">
                                <div class="icon-saved" style="background: linear-gradient(to right, #28a745, #82e0aa);">
                                    <i class="fas fa-bell textWhite"></i>
                                </div>
                                <span class="menu-item-text textWhite">Request Notification</span>
                            </a>
                        </li>

                    <?php } ?>

                    <li>
                        <a href="#" class="menu-item text-decoration-none">
                            <div class="icon-saved"
                                style="background: linear-gradient(to right,rgb(31, 241, 94),rgb(148, 44, 196));">
                                <i class="fas fa-question-circle textWhite"></i>
                            </div>
                            <span class="menu-item-text textWhite">Help & Support</span>
                        </a>
                    </li>
                    <li>
                        <a href="./aboutus.php" class="menu-item text-decoration-none">
                            <div class="icon-saved" style="background: linear-gradient(to right,rgb(255, 52, 153),rgb(57, 60, 255));">
                                <i class="fas fa-info-circle textWhite"></i>
                            </div>
                            <span class="menu-item-text textWhite">About Us</span>
                        </a>
                    </li>

                    <li>
                        <a href="" class="menu-item text-decoration-none">
                            <div class="icon-saved" style="background: linear-gradient(60deg,rgb(226, 224, 82),rgb(238, 180, 22));">
                                <i class="fa-solid fa-star textWhite"></i>
                            </div>
                            <span class="menu-item-text textWhite">Rate Our App</span>
                        </a>
                    </li>
                    <!-- Admin Panel -->
                    <li>
                        <a href="#" class="menu-item text-decoration-none" data-bs-toggle="modal"
                            data-bs-target="#adminPanelModal">
                            <div class="icon-saved" style="background: linear-gradient(60deg,rgb(16, 119, 134),rgb(56, 205, 250));">
                                <i class="fa-solid fa-user-tie textWhite"></i>
                            </div>
                            <span class="menu-item-text textWhite">Admin Panel</span>
                        </a>
                    </li>
                </div>

                <div class="modal" id="adminPanelModal" tabindex="-1" aria-labelledby="adminPanelModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="adminPanelModalLabel">Admin Panel</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-5">
                                    <label for="loginIdentifier"
                                        class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                                    <input type="email" id="loginIdentifier"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out placeholder-gray-400"
                                        placeholder="your email@example.com" aria-required="true">
                                </div>
                                <div class="mb-6">
                                    <label for="password"
                                        class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                                    <input type="password" id="password"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out placeholder-gray-400"
                                        placeholder="Enter your password" aria-required="true">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="create-post-section borderStyle" style="background: #252426;">
                        <div class="create-post-top">
                            <img src="../assests/images/post_images/<?= getUserPorfileImageByID($_SESSION['userid']) ?>"
                                alt="User Profile Picture" class="profile-pic">
                            <div class="post-input-container">
                                <p>What's on your mind?</p>
                            </div>
                        </div>
                        <div class="create-post-bottom">
                            <a href="../pages/groupcall.php" style="text-decoration: none;">
                                <div class="post-option">
                                    <i class="fa-solid fa-video live-video iconColor"></i>
                                    <span style="color: white;">Group Call</span>
                                </div>
                            </a>

                            <a href="#" style="text-decoration: none;">
                                <div class="post-option">
                                    <i class="fa-solid fa-images photo-video iconColor"></i>
                                    <span style="color: white;">Photo</span>
                                </div>
                            </a>
                        </div>
                    </div>


                    <?php

                    $allpost = getSavePost($_SESSION['userid']);
                    while ($row = $allpost->fetch_assoc()) { ?>

                        <div class="post-section postBorder" style="background: #252426;" id="<?= $row['postID'] ?>">
                            <?php
                            $postuserID = $row['userID'];
                            $sessionUserID = $_SESSION['userid'] ?? null; // make sure session userid is set
                        
                            // Decide link target
                            if ($postuserID == $sessionUserID) {
                                $profileLink = "profile.php";
                            } else {
                                $profileLink = "otherprofile.php?user_id=" . urlencode($postuserID);
                            }
                            ?>

                            <div class="post-header ">
                                <a href="<?= $profileLink ?>">
                                    <img src="../assests/images/post_images/<?= getUserPorfileImageByID($postuserID) ?>"
                                        alt="User Profile Picture" class="profile-pic">
                                </a>
                                <div class="user-info">
                                    <a href="<?= $profileLink ?>" class="user-name"
                                        style="text-decoration: none; color: white;">
                                        <?php echo htmlspecialchars(getUserNamebyID($postuserID)); ?>
                                    </a>
                                    <div class="post-time">
                                        <?php
                                        $postDate = $row['postdate'];
                                        echo getTimeAgo($postDate);
                                        ?>
                                        <?php if ($row["privacy"] == "public"): ?>
                                            <i class="fa-solid fa-earth-americas"></i>
                                        <?php elseif ($row["privacy"] == "batch"): ?>
                                            <i class="fa-solid fa-users"></i>
                                        <?php elseif ($row["privacy"] == "only_me"): ?>
                                            <i class="fa-solid fa-lock"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="post-content textWhite">
                                <p><?php echo $row["content"]; ?></p>
                            </div>


                            <!-- Custom dropdown content styled like the second one -->
                            <div class="social-icons-wrapper position-absolute top-0 end-0 mt-2 me-4">

                                <div class="circular-dropdown-toggle" id="socialDropdownToggle_<?= $row['postID'] ?>">
                                    <i class="fas fa-ellipsis-h"></i>
                                </div>

                                <div class="social-icons-content" id="socialIconsContent_<?= $row['postID'] ?>"
                                    style="display: none; background-color:grey;">
                                    <div class="example-2 vertical-layout">
                                        <?php
                                        $user = get_user_by_userID($_SESSION['userid']);
                                        if ($_SESSION['userid'] == $row['userID']) { ?>
                                            <div class="icon-content">
                                                <a data-social="pinterest" onclick="deletePost(<?= $row['postID'] ?>)">
                                                    <div class="filled"></div>
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                                <div class="tooltip">DELETE</div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="icon-content">
                                                <a data-social="pinterest" onclick="hidePost(<?= $row['postID'] ?>)">
                                                    <div class="filled"></div>
                                                    <i class="fas fa-eye-slash"></i>
                                                </a>
                                                <div class="tooltip">HIDE</div>
                                            </div>
                                            <div class="icon-content">
                                                <a data-social="dribbble">
                                                    <div class="filled"></div>
                                                    <i class="fas fa-bookmark" onclick="savepost(<?= $row['postID'] ?>)"></i>
                                                </a>
                                                <div class="tooltip">SAVE</div>
                                            </div>


                                        <?php }
                                        if ($user['userType'] == "admin" && $_SESSION['userid'] != $row['userID']) { ?>
                                            <div class="icon-content">
                                                <a data-social="pinterest" onclick="ban_post(<?= $row['postID'] ?>)">
                                                    <div class="filled"></div>
                                                    <i class="fa-solid fa-ban"></i>
                                                </a>
                                                <div class="tooltip">BAN</div>
                                            </div>

                                        <?php }
                                        if ($user['userType'] != "admin" && $_SESSION['userid'] != $row['userID']) { ?>
                                            <div class="icon-content">
                                                <a href="#" data-social="telegram" data-bs-toggle="modal"
                                                    data-bs-target="#reportModal" data-post-id="<?= $row['postID'] ?>">
                                                    <div class="filled"></div>
                                                    <i class="fas fa-flag"></i>
                                                </a>
                                                <div class="tooltip">REPORT</div>
                                            </div>

                                        <?php }
                                        ?>


                                    </div>
                                </div>
                            </div>



                            <?php $images = getImagesByPostId($conn, $row['postID']); ?>
                            <?php renderGallery($images, $row['postID']); ?>

                            <div class="post-interactions-count">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#reactionModal"
                                    onclick="setSessionAndLoad(<?= $row['postID'] ?>,'All')" style="text-decoration: none;">
                                    <div class="likes-count" data-postid="<?= $row['postID'] ?>">
                                        <?php have_reaction($row['postID']); ?>
                                        <span>
                                            <h7 id="like_text" style="color: white;">
                                                <?php
                                                $summary = getReactionSummary($row["postID"], $_SESSION["userid"], $conn);
                                                if (!empty($summary)) {
                                                    echo $summary;
                                                }
                                                ?>
                                            </h7>
                                        </span>
                                    </div>
                                </a>

                                <div class="comments-count">
                                    <a href="../pages/comment_postframe.php?postID=<?= $row['postID'] ?> "
                                        style="text-decoration: none; color: white;"><?php get_comment_count($row["postID"]) ?></a>
                                </div>
                            </div>


                            <form method="post" class="post">
                                <input type="hidden" name="post_id" value="<?php echo $row['postID']; ?>">

                                <div class="d-flex justify-content-between m-2 gap-3 flex-wrap">
                                    <!-- LIKE BUTTON -->
                                    <div class="group position-relative">
                                        <button type="button"
                                            class="btn btnDesign d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm">
                                            <div class="icon-container d-flex align-items-center gap-2">
                                                <?php
                                                $have_react = already_react($row['postID'], $_SESSION['userid'], $conn);
                                                $currentReaction = $have_react ? mysqli_fetch_assoc($have_react)['type'] : null;
                                                ?>
                                                <?php if ($currentReaction): ?>
                                                    <img src="../assests/images/icon/<?php echo $currentReaction; ?>.png"
                                                        alt="<?php echo $currentReaction; ?>" class="reaction-img" />
                                                    <span class="text"
                                                        style="color: white;"><?php echo $currentReaction; ?></span>
                                                <?php else: ?>
                                                    <i class="fa-regular fa-thumbs-up textWhite"></i>
                                                    <span class="text" style="color: white;">Like</span>
                                                <?php endif; ?>
                                            </div>
                                        </button>

                                        <!-- Reaction Panel -->
                                        <div class="reaction-panel">
                                            <?php foreach (["Like", "Love", "Haha", "Wow", "Sad", "Angry"] as $reaction): ?>
                                                <img src="../assests/images/icon/<?php echo $reaction; ?>.png"
                                                    class="reaction-img" title="<?php echo $reaction; ?>"
                                                    data-reaction="<?php echo $reaction; ?>" />
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <!-- Comment box -->
                                    <div class="comment-box cmtBoxStyle">
                                        <img src="../assests/images/post_images/<?php echo getUserPorfileImageByID($sessionUserID) ?>"
                                            alt="profile" class="avatar">
                                        <input type="text" class="comment-input" id="comment_input" style="color: white;"
                                            placeholder="Write a comment...">
                                        <button class="send-btn" type="button"
                                            onclick="write_comment(<?php echo $row['postID'] ?>)">
                                            <i class="fas fa-paper-plane iconColor"></i>
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    <?php } ?>
    

            <form action="../process/post.php" method="post" enctype="multipart/form-data" style="margin-top:20px;">
                <div class="post-modal-overlay">
                    <div class="post-modal">
                        <div class="post-modal-header">
                            <h3>Create Post</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="post-modal-body">
                            <div class="modal-user-info">
                                <img src="../assests/images/post_images/<?php echo getUserPorfileImageByID($_SESSION["userid"]) ?>"
                                    alt="Your Profile Picture">
                                <div>
                                    <div class="user-name"><?php echo $_SESSION["username"] ?></div>
                                    <div class="dropdown">
                                        <button type="button" class="dropdown-toggle" id="selected" name="privacy">
                                            <i class="fa-solid fa-earth-americas"></i> Public
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <!-- The onclick attributes will still be present but won't have any effect without JavaScript -->
                                            <button type="button" class="dropdown-item"
                                                onclick="choice_privacy('public')">
                                                <i class="fa-solid fa-earth-americas"></i> Public
                                                <span class="check-icon" id="check-public"><i
                                                        class="fa-solid fa-check"></i></span>
                                            </button>
                                            <button type="button" class="dropdown-item"
                                                onclick="choice_privacy('batch')">
                                                <i class="fa-solid fa-user-graduate"></i> Batch
                                                <span class="check-icon" id="check-batch"><i
                                                        class="fa-solid fa-check"></i></span>
                                            </button>
                                            <button type="button" class="dropdown-item"
                                                onclick="choice_privacy('only_me')">
                                                <i class="fa-solid fa-lock"></i> Only Me
                                                <span class="check-icon" id="check-only_me"><i
                                                        class="fa-solid fa-check"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="privacy-input" name="privacy" value="public">
                                </div>
                            </div>

                            <?php
                            $fullName = trim($_SESSION['username']); // e.g. "San Min Htike"
                            $parts = explode(' ', $fullName);
                            $firstWord = $parts[0]; // get first word
                            ?>
                            <textarea class="post-textarea" name="post-textarea"
                                placeholder="What's on your mind, <?php echo htmlspecialchars($firstWord); ?>?"></textarea>


                            <div id="preview-container" class="preview-container"></div>

                            <input type="file" name="post_image[]" id="upload" accept="image/*" multiple
                                style="display:none;" onchange="readUrl(this)">



                            <div class="modal-add-to-post">
                                <span>Add to your post</span>
                                <div class="add-icons">
                                    <label for="upload" style="cursor:pointer;">
                                        <i class="fa-solid fa-images"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="post-modal-footer">
                            <button type="submit" name="post-button" class="post-button">Post</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal fade" id="reactionModal" tabindex="-1" aria-labelledby="reactionModalLabel"
                aria-hidden="true">
                <div class="modal-dialog wide-modal modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reactionModalLabel">People who reacted</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="reaction-tabs">
                                <div class="reaction-tab active" onclick="specific_reacted_user('All')"><span>All</span>
                                </div>
                                <div class="reaction-tab" onclick="specific_reacted_user('Like')"><img
                                        src="../assests/images/icon/Like.png" id="like_react"><span></span></div>
                                <div class="reaction-tab" onclick="specific_reacted_user('Love')"><img
                                        src="../assests/images/icon/Love.png" id="love_react"><span></span></div>
                                <div class="reaction-tab" onclick="specific_reacted_user('Haha')"><img
                                        src="../assests/images/icon/Haha.png" id="haa_react"><span></span></div>
                                <div class="reaction-tab" onclick="specific_reacted_user('Angry')"><img
                                        src="../assests/images/icon/Angry.png" id="angry_react"><span></span></div>
                                <div class="reaction-tab" onclick="specific_reacted_user('Sad')"><img
                                        src="../assests/images/icon/Sad.png" id="sad_react"> <span></span></div>
                                <div class="reaction-tab" onclick="specific_reacted_user('Wow')"><img
                                        src="../assests/images/icon/Wow.png" id="wow_react"><span></span></div>
                            </div>
                            <div id="loadingSpinner" style="display: none;">Loading...</div>
                            <ul id="reactionGiversList" class="list-group"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div id="image-Modal" class="image-modal" style="display:none;">
        <button id="closeBtn" class="nav-button close-btn">&times;</button>
        <button id="prevBtn" class="nav-button prev-btn">&#10094;</button>
        <img id="modal-Image" src="" alt="Modal Image">
        <button id="nextBtn" class="nav-button next-btn">&#10095;</button>
    </div>
    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Tell me what's your problem with this post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="reportForm">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reason[]" value="Sexual harassment"
                                id="reason1">
                            <label class="form-check-label" for="reason1">Sexual harassment</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reason[]" value="Hate speech"
                                id="reason2">
                            <label class="form-check-label" for="reason2">Hate speech</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reason[]" value="Spam" id="reason3">
                            <label class="form-check-label" for="reason3">Spam</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reason[]" value="Violent content"
                                id="reason4">
                            <label class="form-check-label" for="reason4">Violent content</label>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="additionalNote" class="form-label">Additional note</label>
                            <textarea class="form-control" id="additionalNote" name="note" rows="3"
                                placeholder="Write something..." style="resize: none; height: 200px;"></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="submitReport()">Submit</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        let currentReportedPostId = null;
        const reportModal = document.getElementById('reportModal');
        if (reportModal) {
            reportModal.addEventListener('show.bs.modal', function (event) {

                const button = event.relatedTarget;
                if (button) {
                    currentReportedPostId = button.getAttribute('data-post-id');
                    console.log('Modal opened for Post ID:', currentReportedPostId);
                }
            });

            reportModal.addEventListener('hidden.bs.modal', function () {
                currentReportedPostId = null;
                const form = document.getElementById('reportForm');
                if (form) {
                    form.reset();
                }
            });
        }

        function submitReport() {
            const form = document.getElementById('reportForm');
            const reasons = Array.from(form.querySelectorAll('input[name="reason[]"]:checked'))
                .map(cb => cb.value);

            const note = form.querySelector('#additionalNote').value;

            // Use the stored postId
            const postId = currentReportedPostId;

            if (!postId) {
                alert("Error: Post ID not found for report. Please try again.");
                console.error("Post ID is missing for report submission.");
                return; // Stop the function if postId is not available
            }

            const reportData = {
                postId: postId,
                reasons: reasons,
                note: note
            };

            console.log("Submitting Report Data:", reportData);

            // Send the data to your backend API
            fetch('../process/report_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(reportData),
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Something went wrong on the server.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Report submitted successfully:', data);
                    alert("Report submitted successfully!");
                    const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
                })
                .catch(error => {
                    console.error('Error submitting report:', error);
                    alert(`Error submitting report: ${error.message || 'Please try again.'}`);
                });
        }


        // Wait for the document to be fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            // Get a reference to the modal element
            var storyModal = document.getElementById('storyModal');

            // Check if the modal exists to avoid errors
            if (storyModal) {
                // Listen for the Bootstrap 'shown.bs.modal' event
                // This event fires after the modal has been made visible to the user
                storyModal.addEventListener('shown.bs.modal', function (event) {
                    // 'event.relatedTarget' is the element that triggered the modal (our story card)
                    var storyCard = event.relatedTarget;

                    // Add the 'viewed' class to the story card
                    if (storyCard && storyCard.classList.contains('story-card')) {
                        storyCard.classList.add('viewed');
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            // We attach one listener to the document
            document.addEventListener('click', function (event) {
                // Use event.target.closest() to check if the clicked element (or its parent)
                // is one of our dropdown toggles.
                const toggleButton = event.target.closest('.circular-dropdown-toggle');

                if (toggleButton) {
                    // A dropdown toggle was clicked
                    event.stopPropagation(); // Prevent the document click listener from immediately hiding it

                    // Extract the postID from the unique ID (e.g., "socialDropdownToggle_123" -> "123")
                    const postId = toggleButton.id.replace('socialDropdownToggle_', '');
                    const socialIconsContent = document.getElementById('socialIconsContent_' + postId);

                    if (socialIconsContent) {
                        // First, hide any other open dropdowns (improves user experience)
                        document.querySelectorAll('.social-icons-content').forEach(content => {
                            // Make sure not to hide the one we just clicked to open/close
                            if (content.id !== socialIconsContent.id) {
                                content.style.display = 'none';
                            }
                        });

                        // Then, toggle the display of the current dropdown
                        if (socialIconsContent.style.display === 'none' || socialIconsContent.style.display === '') {
                            socialIconsContent.style.display = 'flex';
                        } else {
                            socialIconsContent.style.display = 'none';
                        }
                    }
                } else {
                    // If the click was anywhere else on the document AND not inside an open dropdown content,
                    // close all open dropdowns.
                    document.querySelectorAll('.social-icons-content').forEach(content => {
                        if (!content.contains(event.target)) { // Check if click was outside this specific content area
                            content.style.display = 'none';
                        }
                    });
                }
            });
        });

        function readUrl(input) {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = ''; // Clear previous previews

            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    const reader = new FileReader();
                    const file = input.files[i];

                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-image'); // Add a class for styling
                        previewContainer.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            const modalOverlay = document.querySelector(".post-modal-overlay");
            const modal = document.querySelector(".post-modal");
            const closeButtons = document.querySelectorAll(".btn-close, .close-modal");
            const textarea = document.querySelector(".post-textarea");
            const fileInput = document.querySelector("#upload");
            const previewContainer = document.querySelector("#preview-container");

            function closeModal() {
                modalOverlay.style.display = "none";
                // Clear text
                textarea.value = "";
                // Clear file input
                fileInput.value = "";
                // Remove previews
                previewContainer.innerHTML = "";
            }

            closeButtons.forEach(btn => {
                btn.addEventListener("click", closeModal);
            });

            modalOverlay.addEventListener("click", function (e) {
                if (!modal.contains(e.target)) {
                    closeModal();
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../assests/js/clickImage.js"></script>
    <script src="../assests/js/script.js"></script>
    <script src="../assests/js/commend.js"></script>
    <!-- Bootstrap Bundle with Popper -->

    <script>
        const CURRENT_LOGGED_IN_USER_ID = "<?php echo htmlspecialchars($loggedInUserId); ?>";
    </script>


</body>

</html>