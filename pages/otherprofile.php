<?php
session_start();
include("../includes/db.php");
include("../includes/get_users.php");
$user = null;
$userId = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
}

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
                <img src="../assests/images/post_images/<?php echo $user['coverPhoto']?>" alt="Cover Photo">
            </div>

            <div class="profile-picture-container">
                <img src="../assests/images/post_images/<?php echo $user['ProfileimagePath']?>" alt="" class="profile-picture">
            </div>
           
            <div class="profile-info">
                <h1 class="profile-name"><?php echo $user['name']?></h1>
               
            </div>

            


            
            </div>
        </div>
    </div>
    
    <div class="post-section">
        <div class="post-header">
            <img src="../assests/images/icon/m.jpg" alt="User Profile Picture" class="profile-pic">
            <div class="user-info">
                <div class="user-name">Myo Aung</div>
                <div class="post-time">20h ago <i class="fa-solid fa-earth-americas"></i></div>
            </div>
        </div>
        <div class="post-content">
            <p>Metro Social media</p>
            <img src="../assests/images/icon/m.jpg" alt="Post Image">
        </div>
        <div class="post-interactions-count">
            <div class="likes-count">
                <i class="fa-solid fa-thumbs-up"></i> <span>John Muriithi and 108 others</span>
            </div>
            <div class="comments-count">
                <span>1k Comments</span>
            </div>
        </div>
        <div class="post-interactions-buttons">
            <div class="interaction-button">
                <i class="fa-regular fa-thumbs-up"></i> <span>Like</span>
            </div>
            <div class="interaction-button">
                <i class="fa-regular fa-comment"></i> <span>Comment</span>
            </div>
            <div class="interaction-button">
                <i class="fa-solid fa-share"></i> <span>Share</span>
            </div>
        </div>
    </div>

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