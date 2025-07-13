<?php
include("../includes/db.php");
include("../process/post.php");
include("../includes/post_section.php");

$user = null;
$userId = null;

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
}

$user = get_user_by_userID($userId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assests/css/profile.css">
    <link rel="stylesheet" href="../assests/css/style.css">
    <!-- font-awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Metro Book</title>
</head>

<body style=" margin-top: 80px;">
    <div>
        <!-- nav start -->
        <?php
        include("../includes/header.php")
        ?>

        <div class="container mt-4">
            <div class="profile-header rounded-3">
                <div class="cover-photo rounded-top">
                    <img src="../assests/images/post_images/<?php echo $user['coverPhoto'] ?>" alt="Cover Photo">
                </div>

                <div class="profile-picture-container">
                    <img src="../assests/images/post_images/<?php echo $user['ProfileimagePath'] ?>" alt="" class="profile-picture">
                </div>

                <div class="profile-info">
                    <h1 class="profile-name"><?php echo $user['name'] ?></h1>

                </div>





            </div>
        </div>
    </div>

    <?php

    $allpost = get_other_profile_post($_SESSION['userid'], $userId);
    while ($row = $allpost->fetch_assoc()) {
        post_section($row, $conn);
    }

    ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </div>
    <script>
        // Function to handle image selection and preview
        function handleImageUpload(event, imageElementId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(imageElementId).src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Get references to the camera icons and hidden file inputs
        const cameraIconProfile = document.querySelector('.profile-picture-container .camera-icon-profile');
        const profilePicInput = document.getElementById('profilePicInput');
        const profilePicture = document.querySelector('.profile-picture');

        const cameraIconCover = document.querySelector('.cover-photo .camera-icon-cover');
        const coverPhotoInput = document.getElementById('coverPhotoInput');
        const coverPhotoImg = document.querySelector('.cover-photo img');


        // Add event listeners to the camera icons
        cameraIconProfile.addEventListener('click', () => {
            profilePicInput.click(); // Trigger click on hidden file input
        });

        cameraIconCover.addEventListener('click', () => {
            coverPhotoInput.click(); // Trigger click on hidden file input
        });

        // Add event listeners to the hidden file inputs for when a file is selected
        profilePicInput.addEventListener('change', (event) => {
            handleImageUpload(event, 'profilePicture'); // 'profilePicture' is the id of your <img> tag for the profile picture
        });

        coverPhotoInput.addEventListener('change', (event) => {
            handleImageUpload(event, 'coverPhoto'); // 'coverPhoto' is the id of your <img> tag for the cover photo
        });

        // Add IDs to your img tags so they can be referenced by the JS function
        document.querySelector('.profile-picture').id = 'profilePicture';
        document.querySelector('.cover-photo img').id = 'coverPhoto';
    </script>
</body>

</html>