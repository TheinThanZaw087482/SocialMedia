<?php
include("../includes/db.php");
include("../process/post.php");
require_once("../includes/get_users.php");
$user = get_user_by_userID($_SESSION['userid']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assests/css/profile.css">
    <link rel="stylesheet" href="../assests/css/style.css">
    <link rel="stylesheet" href="../assests/css/story-style.css">
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
                <form enctype="multipart/form-data" action="../process/update_cover_photo.php" method="POST" id="coverPhotoForm">
                    <div class="cover-photo rounded-top">
                        <img src="../assests/images/post_images/<?php echo getCoverPhotoByID($_SESSION["userid"]) ?>" alt="Cover Photo" id="coverImg">
                        <div class="camera-icon-cover">
                            <i class="fa-solid fa-camera" onclick="document.getElementById('coverPhotoInput').click();"></i>

                            <input type="file" id="coverPhotoInput" accept="image/*" style="display: none;" name="coverPhoto" onchange="submitCoverPhotoForm()">
                        </div>
                    </div>

                </form>

                <form id="profilePicForm" method="POST" enctype="multipart/form-data" action="../process/update_profile_pic.php">
                    <div class="profile-picture-container">
                        <img src="../assests/images/post_images/<?php echo getUserPorfileImageByID($_SESSION["userid"]) ?>"
                            alt="Profile" class="profile-picture" id="previewImage">

                        <div class="camera-icon-profile" onclick="document.getElementById('profilePicInput').click();">
                            <i class="fa-solid fa-camera"></i>
                            <input type="file" id="profilePicInput" accept="image/*" name="profile_img" style="display: none;" onchange="submitProfilePicForm()">
                        </div>
                    </div>
                </form>





                <div class="profile-info">
                    <h1 class="profile-name" style="display:inline;"><?php echo $_SESSION['username'] ?></h1>
                    <span>(<?php echo $user['nickname']; ?>)</span>

                    <h4><?php echo $user['bio']; ?></h4>
                </div>

                <div class="profile-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMyDay">
                            Add to story
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="addMyDay" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered custom-modal-size">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title" id="modal-title">Create Story</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="modal-body">
                                        <label for="imageInput">
                                            <img src="../assests/images/icon/gallery-icon.png" class="gallery-icon"
                                                alt="Choose Image" />
                                        </label>
                                        <input type="file" id="imageInput" accept="image/*" style="display: none;" />

                                        <div class="story-area">
                                            <img id="imagePreview" style="display: none;" />
                                            <textarea class="story-textarea" placeholder="Write here"></textarea>
                                            <button id="removeImageBtn" title="Remove image">&times;</button>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <button type="button" class="custom-upload-btn" id="uploadBtn">
                                        Upload Story
                                    </button>
                                </div>
                            </div>
                        </div>

                    <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill me-2" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 15.5v.5H.5a.5.5 0 0 1-.5-.5V.5a.5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 0 1H1v14h14V9.5a.5.5 0 0 1 1 0v6a.5.5 0 0 1-.5.5H6.018a.5.5 0 0 1-.458.307z" />
                        </svg>
                        Edit profile
                    </button>

                    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="../process/editprofile.php" enctype="multipart/form-data">
                                        <div class="mb-2">
                                            <label for="profileName" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="profileName" value="<?php echo $user['name']; ?>">
                                        </div>

                                        <div class="mb-2">
                                            <label for="profileName" class="form-label"></label>
                                            <input type="text" class="form-control" id="profileName" name="nickName" value="<?php echo $user['nickname']; ?>">
                                        </div>

                                        <div class="mb-2">
                                            <label for="profileBio" class="form-label">Bio</label>
                                            <textarea class="form-control" name="profileBio" id="profileBio" rows="3" value=""><?php echo $user['bio']; ?></textarea>
                                        </div>

                                        <div class="mb-2">
                                            <label for="profileEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="profileEmail" id="profileEmail" value="<?php echo $user['email']; ?>">
                                        </div>

                                        <div class="mb-2">
                                            <label for="profileBirthday" class="form-label">Birthday</label>
                                            <input type="date" class="form-control" name="profileBirthday" id="profileBirthday" value="<?php echo $user['birthdate'];?>">
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="btn_edit_profile">Save changes</button>
                                </div>
                                </form>
                            </div>
                        </div>
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

    <script src="../assests/js/script.js"></script>

    <script src="../assests/js/script.js"></script>
        <script>
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');

            imageInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        removeImageBtn.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });

            removeImageBtn.addEventListener('click', function () {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                removeImageBtn.style.display = 'none';
                imageInput.value = null;
            });
        </script>

</body>

</html>