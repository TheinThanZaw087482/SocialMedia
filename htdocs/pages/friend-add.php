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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Metro Book</title>

    <style>
        /* Custom CSS to fine-tune Bootstrap components and match the image */
        body {
            font-family: 'Inter', sans-serif;
            /* Apply Inter font */
            background-color: #252426;
            /* Light background similar to social platforms */
        }

        .friend-suggestion-card {
            /* Card styling to mimic the image: subtle border, rounded corners, shadow */
            border: 1px solid #ddd;
            border-radius: 12px;
            /* More rounded corners */
            padding: 20px;
            /* Increased padding for better spacing */
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            /* Stronger shadow */
            height: 100%;
            /* Ensures all cards in a row have consistent height */
            display: flex;
            flex-direction: column;
            /* Arrange content vertically */
            justify-content: space-between;
            /* Push buttons to the bottom */
            background-color: #fff;
            /* White background for cards */
            transition: transform 0.2s ease-in-out;
            /* Smooth hover effect */
        }

        .friend-suggestion-card:hover {
            transform: translateY(-3px);
            /* Slightly lift card on hover */
        }

        .profile-img-container {
            width: 100px;
            /* Larger size for profile image container */
            height: 100px;
            margin: 0 auto 15px auto;
            /* Center image and add bottom margin */
            border-radius: 50%;
            /* Make container circular */
            overflow: hidden;
            /* Ensure image stays within circular boundary */
            border: 2px solid #e0e0e0;
            /* Subtle border around profile image */
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            /* Fallback background for image container */
        }

        .profile-img {
            width: 80%;
            height: 80%;
            object-fit: cover;
            /* Ensures image covers the area without distortion */
            border-radius: 50%;
            /* Make image circular if not already */
            /* Fallback in case of image load error */
            background-color: #e0e0e0;
            /* Placeholder color */
            color: #666;
            /* Placeholder text color */
            font-size: 0.8em;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .card-name {
            font-weight: 600;
            /* Semi-bold for names */
            font-size: 1.2em;
            /* Slightly larger name font */
            margin-bottom: 5px;
            color: #212529;
            /* Darker text for names */
        }

        .mutual-friends {
            color: #65676B;
            /* Grey color for mutual friends count */
            font-size: 0.9em;
            margin-bottom: 20px;
            /* More space before buttons */
        }

        .btn-group-custom {
            /* Custom styling for the button group to ensure stacking and consistent width */
            display: flex;
            flex-direction: column;
            /* Stack buttons vertically */
            gap: 8px;
            /* Space between buttons */
            width: 100%;
            /* Ensure buttons take full width of card */
        }

        /* Adjust button styles */
        .btn-add-friend {
            background-color: #1877f2;
            /* Facebook-like blue */
            border-color: #1877f2;
            color: #fff;
            font-weight: 500;
            padding: 8px 15px;
            /* Adjust padding */
            border-radius: 8px;
            /* Rounded buttons */
            transition: background-color 0.2s ease;
        }

        .btn-add-friend:hover {
            background-color: #166fe5;
            /* Darker blue on hover */
            border-color: #166fe5;
        }

        .btn-remove {
            background-color: #e4e6eb;
            /* Light grey for remove button */
            border-color: #e4e6eb;
            color: #4b4b4b;
            /* Dark grey text */
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }

        .btn-remove:hover {
            background-color: #d8dade;
            /* Darker grey on hover */
            border-color: #d8dade;
        }

        /* Ensure consistent height of cards in a row for aesthetic alignment */
        .row.g-4 {
            align-items: stretch;
        }

        .textWhite {
            color: white;
        }

        .profileCardBorder {
            border: 2px solid rgb(117, 118, 119) !important;
            box-shadow:
                0 0 10px rgb(86, 89, 92),
                0 0 15px rgb(62, 63, 65),
                inset 0 0 10px rgba(33, 34, 35, 0.4);
        }

        .profileViewBorder {
            border: 2px solid rgb(132, 188, 245) !important;
            box-shadow:
                0 0 10px rgb(129, 182, 236),
                0 0 15px rgb(133, 164, 224),
                inset 0 0 10px rgba(76, 130, 183, 0.4);
        }

        /*Suiko*/
        .modern-btn {
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }

        .modern-btn:hover {
            background: linear-gradient(135deg, #2193b0, #6dd5ed);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .modern-btn.outline {
            background: transparent;
            border: 2px solid #6dd5ed;
            color: #6dd5ed;
        }

        .modern-btn.outline:hover {
            background: #6dd5ed;
            color: white;
            transform: translateY(-2px);
        }
    </style>

</head>

<body style="background: #252426;">

    <!-- Navigation bar -->
    <?php include("../includes/header.php");
    ?>



    <div class="container py-5"> <!-- py-5 adds vertical padding -->
        <h2 class="mb-4 text-center textWhite">All Metro Students</h2>
        <div class="row row-cols-2 row-cols-lg-5 g-4">

            <?php
            $all_user = get_all_users();
            foreach ($all_user as $user) {
                if ($user['userid'] != $_SESSION['userid']) {


                    ?>

                    <div class="col" style="background: #252426;">
                        <a href="otherprofile.php?user_id=<?php echo $user['userid']; ?>" class="btn btn-add-friend">
                            <div class="card friend-suggestion-card profileCardBorder" style="background: #333031;">
                                <div class="profile-img-container  profileViewBorder">
                                    <!-- Placeholder image from placehold.co, adjust dimensions and colors as needed -->
                                    <img src="../assests/images/post_images/<?php echo $user['ProfileimagePath'] ?>"
                                        alt="Profile Picture" class="profile-img"
                                        onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/666666?text=Image+Error';">
                                </div>
                                <h5 class="card-name textWhite"><?php echo $user['name'] ?></h5>
                                <p class="mutual-friends textWhite"><?php echo $user['Batch'] ?></p>
                                <div class="btn-group-custom d-flex justify-content-between mt-3">
                                    <a href="otherprofile.php?user_id=<?php echo $user['userid']; ?>" class="modern-btn">View
                                        Profile</a>
                                    <a href="../pages/messanger.php"><button class="modern-btn outline" >Message</button></a>
                                </div>

                            </div>
                    </div>

                <?php }
            }


            ?>


        </div>
    </div>

    <!-- Bootstrap Bundle with Popper - CDN link for Bootstrap 5.3.3 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>