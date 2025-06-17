<?php
session_start();
include("../includes/db.php");
include("../includes/get_users.php");

$users = get_all_users();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assests/css/style.css">
  <link rel="stylesheet" href="../assests/css/friend-add-style.css">
  <!-- font-awesome link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <title>Metro Book</title>

  <style>
        /* Custom CSS to fine-tune Bootstrap components and match the image */
        body {
            font-family: 'Inter', sans-serif; /* Apply Inter font */
            background-color: #f0f2f5; /* Light background similar to social platforms */
        }
        .friend-suggestion-card {
            /* Card styling to mimic the image: subtle border, rounded corners, shadow */
            border: 1px solid #ddd;
            border-radius: 12px; /* More rounded corners */
            padding: 20px; /* Increased padding for better spacing */
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); /* Stronger shadow */
            height: 100%; /* Ensures all cards in a row have consistent height */
            display: flex;
            flex-direction: column; /* Arrange content vertically */
            justify-content: space-between; /* Push buttons to the bottom */
            background-color: #fff; /* White background for cards */
            transition: transform 0.2s ease-in-out; /* Smooth hover effect */
        }

        .friend-suggestion-card:hover {
            transform: translateY(-3px); /* Slightly lift card on hover */
        }

        .profile-img-container {
            width: 120px; /* Larger size for profile image container */
            height: 120px;
            margin: 0 auto 15px auto; /* Center image and add bottom margin */
            border-radius: 50%; /* Make container circular */
            overflow: hidden; /* Ensure image stays within circular boundary */
            border: 2px solid #e0e0e0; /* Subtle border around profile image */
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0; /* Fallback background for image container */
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures image covers the area without distortion */
            border-radius: 50%; /* Make image circular if not already */
            /* Fallback in case of image load error */
            background-color: #e0e0e0; /* Placeholder color */
            color: #666; /* Placeholder text color */
            font-size: 0.8em;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .card-name {
            font-weight: 600; /* Semi-bold for names */
            font-size: 1.2em; /* Slightly larger name font */
            margin-bottom: 5px;
            color: #212529; /* Darker text for names */
        }

        .mutual-friends {
            color: #65676B; /* Grey color for mutual friends count */
            font-size: 0.9em;
            margin-bottom: 20px; /* More space before buttons */
        }

        .btn-group-custom {
            /* Custom styling for the button group to ensure stacking and consistent width */
            display: flex;
            flex-direction: column; /* Stack buttons vertically */
            gap: 8px; /* Space between buttons */
            width: 100%; /* Ensure buttons take full width of card */
        }

        /* Adjust button styles */
        .btn-add-friend {
            background-color: #1877f2; /* Facebook-like blue */
            border-color: #1877f2;
            color: #fff;
            font-weight: 500;
            padding: 8px 15px; /* Adjust padding */
            border-radius: 8px; /* Rounded buttons */
            transition: background-color 0.2s ease;
        }
        .btn-add-friend:hover {
            background-color: #166fe5; /* Darker blue on hover */
            border-color: #166fe5;
        }

        .btn-remove {
            background-color: #e4e6eb; /* Light grey for remove button */
            border-color: #e4e6eb;
            color: #4b4b4b; /* Dark grey text */
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }
        .btn-remove:hover {
            background-color: #d8dade; /* Darker grey on hover */
            border-color: #d8dade;
        }

        /* Ensure consistent height of cards in a row for aesthetic alignment */
        .row.g-4 {
            align-items: stretch;
        }
    </style>

</head>

<body>

  <!-- Navigation bar -->
  <?php include("../includes/header.php"); ?>

  

<div class="container py-5"> <!-- py-5 adds vertical padding -->
        <h2 class="mb-4 text-center">People You May Know</h2>
        <!-- Updated row classes:
             row-cols-2: 2 cards on extra small screens (<576px) and up to large screens
             row-cols-lg-5: 5 cards on large screens (>=992px) and larger
        -->
        <div class="row row-cols-2 row-cols-lg-5 g-4">
            <!-- Card 1: HaemannMoe -->
            <div class="col">
                <div class="card friend-suggestion-card">
                    <div class="profile-img-container">
                        <!-- Placeholder image from placehold.co, adjust dimensions and colors as needed -->
                        <img src="https://placehold.co/120x120/FF0000/FFFFFF?text=HM" alt="Profile Picture" class="profile-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/666666?text=Image+Error';">
                    </div>
                    <h5 class="card-name">HaemannMoe</h5>
                    <p class="mutual-friends">22 mutual friends</p>
                    <div class="btn-group-custom">
                        <button class="btn btn-add-friend">Add friend</button>
                        <button class="btn btn-remove">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Card 2: Zwe Min Khant Zaw -->
            <div class="col">
                <div class="card friend-suggestion-card">
                    <div class="profile-img-container">
                        <img src="https://placehold.co/120x120/0000FF/FFFFFF?text=ZMKZ" alt="Profile Picture" class="class="profile-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/666666?text=Image+Error';">
                    </div>
                    <h5 class="card-name">Zwe Min Khant Zaw</h5>
                    <p class="mutual-friends">1 mutual friend</p>
                    <div class="btn-group-custom">
                        <button class="btn btn-add-friend">Add friend</button>
                        <button class="btn btn-remove">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Card 3: Thar Nge -->
            <div class="col">
                <div class="card friend-suggestion-card">
                    <div class="profile-img-container">
                        <img src="https://placehold.co/120x120/008000/FFFFFF?text=TN" alt="Profile Picture" class="profile-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/666666?text=Image+Error';">
                    </div>
                    <h5 class="card-name">Thar Nge</h5>
                    <p class="mutual-friends">20 mutual friends</p>
                    <div class="btn-group-custom">
                        <button class="btn btn-add-friend">Add friend</button>
                        <button class="btn btn-remove">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Card 4: Zin Min Thant -->
            <div class="col">
                <div class="card friend-suggestion-card">
                    <div class="profile-img-container">
                        <img src="https://placehold.co/120x120/FFFF00/000000?text=ZMT" alt="Profile Picture" class="profile-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/666666?text=Image+Error';">
                    </div>
                    <h5 class="card-name">Zin Min Thant</h5>
                    <p class="mutual-friends">1 mutual friend</p>
                    <div class="btn-group-custom">
                        <button class="btn btn-add-friend">Add friend</button>
                        <button class="btn btn-remove">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Card 5: Ying Gau -->
            <div class="col">
                <div class="card friend-suggestion-card">
                    <div class="profile-img-container">
                        <img src="https://placehold.co/120x120/FFC0CB/000000?text=YG" alt="Profile Picture" class="profile-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/666666?text=Image+Error';">
                    </div>
                    <h5 class="card-name">Ying Gau</h5>
                    <p class="mutual-friends">16 mutual friends</p>
                    <div class="btn-group-custom">
                        <button class="btn btn-add-friend">Add friend</button>
                        <button class="btn btn-remove">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Example of an additional card to demonstrate scalability -->
            <div class="col">
                <div class="card friend-suggestion-card">
                    <div class="profile-img-container">
                        <img src="https://placehold.co/120x120/800080/FFFFFF?text=New" alt="Profile Picture" class="profile-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/666666?text=Image+Error';">
                    </div>
                    <h5 class="card-name">New Friend</h5>
                    <p class="mutual-friends">5 mutual friends</p>
                    <div class="btn-group-custom">
                        <button class="btn btn-add-friend">Add friend</button>
                        <button class="btn btn-remove">Remove</button>
                    </div>
                </div>
            </div>

            <!-- You can easily duplicate the 'col' div to add more cards -->

        </div>
    </div>

    <!-- Bootstrap Bundle with Popper - CDN link for Bootstrap 5.3.3 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>