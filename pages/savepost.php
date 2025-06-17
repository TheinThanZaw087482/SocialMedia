<?php
include("../includes/db.php");
include("../process/post.php");
include("../process/reaction_helper.php");
include("../includes/image_gallery.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Metro Book</title>
    <style>
        /* Optional: Custom styling for the modal or button */
        .modal-body img#storyImagePreview { /* More specific ID for story image preview */
            max-width: 100%;
            margin-top: 10px;
        }

        #left-settings-panel {
            /* --- Sticky Sidebar CSS --- */
            position: -webkit-sticky; /* For Safari */
            position: sticky;
            top: 20px; /* Adjust this value based on your header's height + desired top margin.
                                    If your header is fixed and, for example, 60px tall, you might set top: 70px (60px + 10px margin).
                                    If you have no fixed header, you might use a smaller value like 10px or 20px for some spacing. */
            height: calc(100vh - 40px); /* Calculates height to fill viewport minus top and some bottom space.
                                            Adjust 40px (e.g., top value + desired bottom margin).
                                            For example, if top is 70px, you might use height: calc(100vh - 80px); */
            overflow-y: auto; /* Allows content within the sidebar to scroll if it's too long */
            align-self: flex-start; /* Ensures it aligns to the top if the row is a flex container */
            /* --- End Sticky Sidebar CSS --- */

            background-color: #f8f9fa; /* Light background for visibility */
            padding: 15px;
            /* min-height: 100vh; */ /* Remove or comment out min-height if using calculated height for sticky */
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

        /* Here */
        .story {
            flex: 0 0 160px;
            height: 240px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            background-color: #fff;
            transition: transform 0.4s ease, box-shadow 0.4s ease, filter 0.3s ease;
            transform-origin: center;
            will-change: transform, box-shadow;
        }

        .stories-container {
            padding: 10px 15px;
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .story:hover {
            transform: scale(1.08) rotateZ(-0.5deg);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
        }

        .stories-section {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            margin-top: 2px;
            padding: 10px;
        }

        .comment-box {
            display: flex;
            align-items: center;
            background-color: #f0f2f5;
            border-radius: 999px;
            padding: 8px 12px;
            margin-right: 90px;
            max-width: 700px;
            flex: 1;
            min-width: 100px;
            transition: box-shadow 0.3s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .comment-box:hover {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .comment-box .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .comment-box .comment-input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 18px;
            color: #333;
            flex: 1;
        }

        .send-btn {
            background: transparent;
            border: none;
            color: rgb(60, 87, 121);
            font-size: 18px;
            margin-left: 10px;
            cursor: pointer;
            padding: 4px;
            transition: transform 0.2s ease;
        }

        .send-btn:hover {
            transform: scale(1.1);
        }

        @media (max-width: 576px) {
            .post .d-flex {
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                align-items: center;
                justify-content: start;
                gap: 8px;
            }

            .comment-box {
                margin-right: 0 !important;
                flex-grow: 1;
            }

            .group {
                flex-shrink: 0;
            }
        }

        @media (max-width: 768px) {
            .create-post-section {
                max-width: 100%;
                margin: 10px auto;
            }

            .post-section {
                max-width: 100%;
                margin: 10px auto;
                padding: 10px;
            }
        }


    </style>
</head>

<body>

    <div class="container-fluid">
        <?php

        // Assuming header.php is a full-width navigation bar
        if (file_exists("../includes/header.php")) {
            include("../includes/header.php");
        } else {
            echo '<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3"><div class="container-fluid"><a class="navbar-brand" href="#">Metro Book (Header Placeholder)</a></div></nav>';
        }
        ?>

        <div class="stories-section ">
            <div class="stories-container">
                <div class="story add-story">
                    <img src="image/upload.png" alt="Add Your Story Placeholder">
                    <div class="add-story-icon"><i class="fa-solid fa-plus"></i></div>
                    <span>Add Your Story</span>
                </div>

                <div class="story">
                    <img src="../assests/images/post_images/story-img-1.jpg" alt="User Story">
                    <div class="story-profile"><img src="../assests/images/post_images/story-img-6.jpg" alt="User Profile"></div> <span class="story-user-name">Benjamin Martinez</span>
                </div>

                <div class="story">
                    <img src="../assests/images/post_images/story-img-2.jpeg" alt="User Story">
                    <div class="story-profile"><img src="../assests/images/post_images/story-img-5.jpg" alt="User Profile"></div> <span class="story-user-name">Ethan Davis</span>
                </div>

                <div class="story">
                    <img src="../assests/images/post_images/story-img-3.jpg" alt="User Story">
                    <div class="story-profile"><img src="../assests/images/post_images/story-img-4.jpg" alt="User Profile"></div> <span class="story-user-name">Liam Wilson</span>
                </div>

                <div class="story">
                    <img src="../assests/images/post_images/story-img-4.jpg" alt="User Story">
                    <div class="story-profile"><img src="../assests/images/post_images/story-img-3.jpg" alt="User Profile"></div> <span class="story-user-name">Liam Wilson</span>
                </div>

                <div class="story">
                    <img src="../assests/images/post_images/story-img-5.jpg" alt="User Story">
                    <div class="story-profile"><img src="../assests/images/post_images/story-img-2.jpeg" alt="User Profile"></div> <span class="story-user-name">Liam Wilson</span>
                </div>

                <div class="story">
                    <img src="../assests/images/post_images/story-img-6.jpg" alt="User Story">
                    <div class="story-profile"><img src="../assests/images/post_images/story-img-1.jpg" alt="User Profile"></div> <span class="story-user-name">Liam Wilson</span>
                </div>


            </div>


        </div>
    </div>
    </div>
    <div class="container-fluid " >
        <div class="row">
            <div class="col-lg-3 d-none d-lg-block" id="left-settings-panel">
                <h4>Settings Section</h4>
                                  <li>
                <a href="#" class="menu-item text-decoration-none">
                    <div class="icon-saved">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <span class="menu-item-text">Saved</span>
                </a>
            </li>
        
             <li>
                <a href="../pages/storyhistory.php" class="menu-item text-decoration-none">
                    <div class="icon-saved" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
                         <i class="fas fa-history"></i>
                    </div>
                    <span class="menu-item-text">Story History</span>
                </a>
            </li>

            <li>
    <a href="hidepost.php" class="menu-item text-decoration-none">
        <div class="icon-saved" style="background: linear-gradient(to right, #dc3545, #fd7e14);">
            <i class="fas fa-eye-slash"></i>
        </div>
        <span class="menu-item-text">Hide Post</span>
    </a>
</li>
<?php 
$user = get_user_by_userID($_SESSION['userid']);
$userType = $user['userType'];
if($userType=="admin"){?>
<li>
    <a href="report-notifications.php" class="menu-item text-decoration-none">
        <div class="icon-saved" style="background: linear-gradient(to right, #dc3545, #ef476f);">
            <i class="fas fa-flag"></i>
        </div>
        <span class="menu-item-text">Report Notification</span>
    </a>
</li>

<li>
    <a href="request-notifications.php" class="menu-item text-decoration-none">
        <div class="icon-saved" style="background: linear-gradient(to right, #28a745, #82e0aa);">
            <i class="fas fa-bell"></i>
        </div>
        <span class="menu-item-text">Request Notification</span>
    </a>
</li>

<?php } ?>

<li>
    <a href="#" class="menu-item text-decoration-none">
        <div class="icon-saved" style="background: linear-gradient(to right, #6c757d, #adb5bd);">
            <i class="fas fa-question-circle"></i>
        </div>
        <span class="menu-item-text">Help & Support</span>
    </a>
</li>
<li>
    <a href="#" class="menu-item text-decoration-none">
        <div class="icon-saved" style="background: linear-gradient(to right, #17a2b8, #5bc0de);">
            <i class="fas fa-info-circle"></i>
        </div>
        <span class="menu-item-text">About Us</span>
    </a>
</li>
            </div>
            <div class="col-lg-9">
                <div class="create-post-section">
                    <div class="create-post-top">
                        <img src="../assests/images/post_images/<?= getUserPorfileImageByID($_SESSION['userid']) ?>" alt="User Profile Picture" class="profile-pic">
                        <div class="post-input-container">
                            <p>What's on your mind?</p>
                        </div>
                    </div>
                    <div class="create-post-bottom">
                        <div class="post-option">
                            <i class="fa-solid fa-video live-video"></i>
                            <span>Live Video</span>
                        </div>
                        <div class="post-option">
                            <i class="fa-solid fa-images photo-video"></i>
                            <span>Photo / Video</span>
                        </div>
                        <div class="post-option">
                            <i class="fa-solid fa-face-smile feeling-activity"></i>
                            <span>Feeling / Activity</span>
                        </div>
                    </div>
                </div>


                <?php

                $allpost = getSavePost();
                foreach($allpost as $row){?>
                    <div class="post-section" id="<?= $row['postID'] ?>">
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

                        <div class="post-header">
                            <a href="<?= $profileLink ?>">
                                <img src="../assests/images/post_images/<?= getUserPorfileImageByID($postuserID) ?>" alt="User Profile Picture" class="profile-pic">
                            </a>
                            <div class="user-info">
                                <a href="<?= $profileLink ?>" class="user-name">
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

                        <div class="post-content">
                            <p><?php echo $row["content"]; ?></p>
                        </div>
                        <div class="dropdown ms-auto position-absolute top-0 end-0 mt-2" style="margin-right: 60px;">
                            <button class="btn btn-light btn-sm menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                     <button class="dropdown-item" onclick="hidePost(<?= $row['postID'] ?>)">Hide</button>
                                    <button class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#reportModal">Report</button>
                                    <button class="dropdown-item" onclick="unsavepost(<?= $row['postID']?>)">Unsave</button>
                            </ul>
                        </div>
                          <?php $images = getImagesByPostId($conn, $row['postID']); ?>
                            <?php renderGallery($images); ?>
                        <div class="post-interactions-count">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#reactionModal"
                                onclick="setSessionAndLoad(<?= $row['postID'] ?>,'All')">
                                <div class="likes-count" data-postid="<?= $row['postID'] ?>">
                                    <?php have_reaction($row['postID']); ?>
                                    <span>
                                        <h4 id="like_text">
                                            <?php
                                            $summary = getReactionSummary($row["postID"], $_SESSION["userid"], $conn);
                                            if (!empty($summary)) {
                                                echo $summary;
                                            }
                                            ?>
                                        </h4>
                                    </span>
                                </div>
                            </a>

                            <div class="comments-count">
                                <span>1k Comments</span>
                            </div>
                        </div>

                        <form method="post" class="post">
                            <input type="hidden" name="post_id" value="<?php echo $row['postID']; ?>">

                            
<div class="d-flex justify-content-between m-2 gap-3 flex-wrap">
                                <!-- LIKE BUTTON -->
                                <div class="group position-relative">
                                    <button type="button"
                                        class="btn btn-outline-primary d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm">
                                        <div class="icon-container d-flex align-items-center gap-2">
                                            <?php
                                            $have_react = already_react($row['postID'], $_SESSION['userid'], $conn);
                                            $currentReaction = $have_react ? mysqli_fetch_assoc($have_react)['type'] : null;
                                            ?>
                                            <?php if ($currentReaction): ?>
                                                <img src="../assests/images/icon/<?php echo $currentReaction; ?>.png"
                                                    alt="<?php echo $currentReaction; ?>" class="reaction-img" />
                                                <span class="text-muted"><?php echo $currentReaction; ?></span>
                                            <?php else: ?>
                                                <i class="fa-regular fa-thumbs-up"></i>
                                                <span class="text-muted">Like</span>
                                            <?php endif; ?>
                                        </div>
                                    </button>

                                    <!-- Reaction Panel -->
                                    <div class="reaction-panel">
                                        <?php foreach (["Like", "Love", "Haha", "Wow", "Sad", "Angry"] as $reaction): ?>
                                            <img src="../assests/images/icon/<?php echo $reaction; ?>.png" class="reaction-img"
                                                title="<?php echo $reaction; ?>" data-reaction="<?php echo $reaction; ?>" />
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Comment box -->
                                <div class="comment-box">
                                    <img src="../assests/images/post_images/1747928434_682f457218aad.png" alt="profile"
                                        class="avatar">
                                    <input type="text" class="comment-input" placeholder="Write a comment...">
                                    <button class="send-btn" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                <?php
                }
              ?>
                

                <form action="../process/post.php" method="post" enctype="multipart/form-data">
                    <div class="post-modal-overlay">
                        <div class="post-modal">
                            <div class="post-modal-header">
                                <h3>Create Post</h3>
                                <span class="close-modal">&times;</span>
                            </div>
                            <div class="post-modal-body">
                                <div class="modal-user-info">
                                    <img src="../assests/images/post_images/<?php echo getUserPorfileImageByID($_SESSION["userid"]) ?>" alt="Your Profile Picture">
                                    <div>
                                        <div class="user-name"><?php echo $_SESSION["username"] ?></div>

                                        <div class="dropdown">
                                            <button type="button" class="dropdown-toggle" id="selected" name="privacy">Public</button>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item" onclick="choice_privacy('public')">Public</button>
                                                <button type="button" class="dropdown-item" onclick="choice_privacy('batch')">Batch</button>
                                                <button type="button" class="dropdown-item" onclick="choice_privacy('only_me')">Only Me</button>
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
                                <textarea class="post-textarea" name="post-textarea" placeholder="What's on your mind, <?php echo htmlspecialchars($firstWord); ?>?"></textarea>

                                <img id="image" src="#" alt="Preview Image" style="display:none; max-width:200px;">

                                <input type="file" name="post_image" id="upload" accept="image/*" style="display:none;" onchange="readUrl(this)">

                                <div class="modal-add-to-post">
                                    <span>Add to your post</span>
                                    <div class="add-icons">
                                        <label for="upload" style="cursor:pointer;">
                                            <i class="fa-solid fa-images"></i>
                                        </label>
                                        <i class="fa-solid fa-user-plus"></i>
                                        <i class="fa-solid fa-face-smile"></i>
                                        <i class="fa-solid fa-location-dot"></i>
                                        <i class="fa-solid fa-flag"></i>
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="post-modal-footer">
                                <button type="submit" name="post-button" class="post-button">Post</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal fade" id="reactionModal" tabindex="-1" aria-labelledby="reactionModalLabel" aria-hidden="true">
                    <div class="modal-dialog wide-modal modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reactionModalLabel">People who reacted</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="reaction-tabs">
                                    <div class="reaction-tab active" onclick="specific_reacted_user('All')"><span>All</span></div>
                                    <div class="reaction-tab" onclick="specific_reacted_user('Like')"><img src="../assests/images/icon/Like.png" id="like_react" ><span></span></div>
                                    <div class="reaction-tab" onclick="specific_reacted_user('Love')"><img src="../assests/images/icon/Love.png" id="love_react" ><span></span></div>
                                    <div class="reaction-tab" onclick="specific_reacted_user('Haha')"><img src="../assests/images/icon/Haha.png" id="haa_react" ><span></span></div>
                                    <div class="reaction-tab" onclick="specific_reacted_user('Angry')"><img src="../assests/images/icon/Angry.png" id="angry_react" ><span></span></div>
                                    <div class="reaction-tab" onclick="specific_reacted_user('Sad')"><img src="../assests/images/icon/Sad.png" id="sad_react"> <span></span></div>
                                    <div class="reaction-tab" onclick="specific_reacted_user('Wow')"><img src="../assests/images/icon/Wow.png" id="wow_react" ><span></span></div>
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

    <script src="../assests/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        function submitReport() {
            const form = document.getElementById('reportForm');
            const reasons = Array.from(form.querySelectorAll('input[name="reason[]"]:checked'))
                .map(cb => cb.value);
            const note = form.querySelector('#additionalNote').value;

            console.log("Report submitted:");
            console.log("Reasons:", reasons);
            console.log("Note:", note);

            alert("Report submitted!");
            const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
            modal.hide();
        }
    </script>

</body>

</html>