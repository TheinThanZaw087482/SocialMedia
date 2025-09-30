<?php
include("../includes/db.php");
include("../process/post.php");
include("../includes/post_section.php");
$user = get_user_by_userID($_SESSION['userid']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assests/css/profile.css">
    <link rel="stylesheet" href="../assests/css/style.css">
    <!-- font-awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Metro Book</title>

    <style>
        /* Dialog styling to center and size the modal */
        #addMyDay .modal-dialog {
            max-width: 380px;
            width: 90%;
            margin: auto;
        }

        /* Remove default modal styles */
        #addMyDay .modal-content {
            background-color: transparent;
            border: none;
            box-shadow: none;
        }

        /* Main story container with consistent rounded corners */
        .story-area {
            position: relative;
            width: 100%;
            height: 550px;
            background-image: linear-gradient(60deg, rgb(77, 148, 255), rgb(35, 99, 222));
            border-radius: 16px;
            /* Symmetrical corners */
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Gallery picker icon and text */
        .gallery-picker {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
        }

        .gallery-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
        }

        .gallery-picker-text {
            color: white;
            font-weight: 500;
            font-size: 16px;
        }

        /* Image preview to show full image, fit to width */
        #imagePreview {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Center the image */
            width: 100%;
            /* Fill container width */
            height: auto;
            /* Maintain aspect ratio */
            max-height: 100%;
            /* Do not exceed container height */
            object-fit: contain;
            /* Ensure full image is visible */
            display: none;
            z-index: 1;
        }

        /* Close button styling */
        .close-modal-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.4);
            border: none;
            color: white;
            font-size: 24px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
            z-index: 4;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Action buttons at the bottom */
        .action-buttons {
            position: absolute;
            bottom: 20px;
            display: flex;
            gap: 15px;
            z-index: 3;
        }

        .discard-btn,
        .upload-btn {
            padding: 10px 20px;
            background-color: white;
            color: rgb(35, 99, 222);
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
        }
    </style>
</head>

<body style=" margin-top: 15px;">
    <div>
        <!-- nav start -->
        <?php
        include("../includes/header.php")
        ?>

        <?php if (isset($_GET['openAddMyDay']) && $_GET['openAddMyDay'] === 'true'): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const addMyDayModal = new bootstrap.Modal(document.getElementById('addMyDay'));
                    addMyDayModal.show();
                });
            </script>
        <?php endif; ?>


        <div class="container mt-4">

            <div class="profile-header rounded-3">
                <form enctype="multipart/form-data" action="../process/update_cover_photo.php" method="POST"
                    id="coverPhotoForm">
                    <div class="cover-photo rounded-top">
                        <img src="../assests/images/post_images/<?php echo getCoverPhotoByID($_SESSION["userid"]) ?>"
                            alt="Cover Photo" id="coverImg">
                        <div class="camera-icon-cover">
                            <i class="fa-solid fa-camera"
                                onclick="document.getElementById('coverPhotoInput').click();"></i>

                            <input type="file" id="coverPhotoInput" accept="image/*" style="display: none;"
                                name="coverPhoto" onchange="submitCoverPhotoForm()">
                        </div>
                    </div>

                </form>

                <form id="profilePicForm" method="POST" enctype="multipart/form-data"
                    action="../process/update_profile_pic.php">
                    <div class="profile-picture-container">
                        <img src="../assests/images/post_images/<?php echo getUserPorfileImageByID($_SESSION["userid"]) ?>"
                            alt="Profile" class="profile-picture" id="previewImage">

                        <div class="camera-icon-profile" onclick="document.getElementById('profilePicInput').click();">
                            <i class="fa-solid fa-camera"></i>
                            <input type="file" id="profilePicInput" accept="image/*" name="profile_img"
                                style="display: none;" onchange="submitProfilePicForm()">
                        </div>
                    </div>
                </form>


                <div class="profile-info">
                    <h1 class="profile-name" style="display:inline;"><?php echo $_SESSION['username'] ?></h1>
                    <?php if (!empty($user['nickname'])) { ?>
                        <span>(<?php echo $user['nickname']; ?>)</span>
                    <?php } ?>
                    <?php if (!empty($user['bio'])) { ?>
                        <h4><?php echo $user['bio']; ?></h4>
                    <?php } ?>
                </div>

                <div class="profile-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMyDay">
                        Add to story
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="addMyDay" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="storyUploadForm">
                                    <div class="story-area" id="storyArea">
                                        <button type="button" class="close-modal-btn" data-bs-dismiss="modal"
                                            aria-label="Close">&times;</button>

                                        <label for="imageInput" class="gallery-picker" id="pickerArea">
                                            <img src="../assests/images/icon/gallery-icon.png" class="gallery-icon"
                                                alt="Choose Image" />
                                            <span class="gallery-picker-text">Choose from gallery</span>
                                        </label>
                                        <input type="file" id="imageInput" accept="image/*" style="display: none;" />

                                        <img id="imagePreview" src="" alt="Preview" />

                                        <div class="action-buttons" id="actionButtons" style="display: none;">
                                            <button type="button" class="discard-btn" id="discardBtn">Discard</button>
                                            <button type="submit" class="upload-btn">Upload Story</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-fill me-2" viewBox="0 0 16 16">
                            <path
                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 15.5v.5H.5a.5.5 0 0 1-.5-.5V.5a.5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 0 1H1v14h14V9.5a.5.5 0 0 1 1 0v6a.5.5 0 0 1-.5.5H6.018a.5.5 0 0 1-.458.307z" />
                        </svg>
                        Edit profile
                    </button>

                    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="../process/editprofile.php"
                                        enctype="multipart/form-data">
                                        <div class="mb-2">
                                            <label for="profileName" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="profileName"
                                                value="<?php echo $user['name']; ?>">
                                        </div>

                                        <div class="mb-2">
                                            <label for="profileName" class="form-label"></label>
                                            <input type="text" class="form-control" id="profileName" name="nickName"
                                                value="<?php echo $user['nickname']; ?>">
                                        </div>

                                        <div class="mb-2">
                                            <label for="profileBio" class="form-label">Bio</label>
                                            <textarea class="form-control" name="profileBio" id="profileBio" rows="3"
                                                value=""><?php echo $user['bio']; ?></textarea>
                                        </div>

                                        <div class="mb-2">
                                            <label for="profileEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="profileEmail"
                                                id="profileEmail" value="<?php echo $user['email']; ?>">
                                        </div>

                                        <div class="mb-2">
                                            <label for="profileBirthday" class="form-label">Birthday</label>
                                            <input type="date" class="form-control" name="profileBirthday"
                                                id="profileBirthday" value="<?php echo $user['birthdate']; ?>">
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="btn_edit_profile">Save
                                        changes</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assests/js/script.js"></script>
    <script src="script.js"></script>
    <script src="path/to/bootstrap.bundle.min.js"></script>
    <script>
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const pickerArea = document.getElementById('pickerArea');
        const actionButtons = document.getElementById('actionButtons');
        const discardBtn = document.getElementById('discardBtn');
        const storyUploadForm = document.getElementById('storyUploadForm'); // Get the form
        const uploadStoryBtn = storyUploadForm.querySelector('.upload-btn'); // Get the upload button within the form

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    actionButtons.style.display = 'flex';
                    pickerArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        discardBtn.addEventListener('click', resetModal);

        // Also reset when the modal is closed
        document.getElementById('addMyDay').addEventListener('hidden.bs.modal', resetModal);

        function resetModal() {
            imagePreview.src = '';
            imagePreview.style.display = 'none';
            actionButtons.style.display = 'none';
            pickerArea.style.display = 'flex';
            imageInput.value = ''; // Clear the file input
        }

        // Handle story upload form submission
        storyUploadForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const file = imageInput.files[0];
            if (!file) {
                alert('Please select an image to upload as your story.');
                return;
            }

            const formData = new FormData();
            formData.append('story_image', file); // 'story_image' will be the $_FILES key in PHP

            // Disable the button and show a loading indicator if desired
            uploadStoryBtn.disabled = true;
            uploadStoryBtn.textContent = 'Uploading...';

            fetch('../process/post_story.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        // Check for non-2xx status codes
                        return response.text().then(text => {
                            throw new Error(text)
                        });
                    }
                    return response.json(); // Assuming your PHP will return JSON, e.g., success message
                })
                .then(data => {
                    alert(data.message || 'Story uploaded successfully!'); // Display message from PHP
                    resetModal(); // Reset the modal after successful upload
                    // Close the modal if successful
                    const myModalEl = document.getElementById('addMyDay');
                    const modal = bootstrap.Modal.getInstance(myModalEl) || new bootstrap.Modal(myModalEl);
                    modal.hide();
                    window.location.href = "../pages/Dashboard.php";
                    // You might want to refresh the stories section on the page here
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error uploading story: ' + error.message);
                })
                .finally(() => {
                    uploadStoryBtn.disabled = false;
                    uploadStoryBtn.textContent = 'Upload Story';
                });
        });

        // Functions to submit cover and profile photos
        function submitCoverPhotoForm() {
            document.getElementById('coverPhotoForm').submit();
        }

        function submitProfilePicForm() {
            document.getElementById('profilePicForm').submit();
        }
    </script>



</body>

</html>