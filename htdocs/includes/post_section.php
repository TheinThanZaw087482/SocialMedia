<?php
include("../includes/image_gallery.php");
include("../process/reaction_helper.php");
function post_section($row, $conn)

{ ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assests/css/style.css">
        <link rel="stylesheet" href="../assests/css/reaction-style.css">
        <link rel="stylesheet" href="../assests/css/image.css">
        <link rel="stylesheet" href="../assests/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    </head>

    <body>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script src="../assests/js/clickImage.js"></script>
        <script src="../assests/js/script.js"></script>

    </body>

    </html>

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

        <div class="post-content">
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

                        <div class="icon-content">
                            <a data-social="pinterest" onclick="deletePost(<?= $row['postID'] ?>)">
                                <div class="filled"></div>
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <div class="tooltip">EDIT</div>
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
                            <a href="#" data-social="telegram"
                                data-bs-toggle="modal" data-bs-target="#reportModal" data-post-id="<?= $row['postID'] ?>">
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
                                <i class="fa-regular fa-thumbs-up"></i>
                                <span class="text">Like</span>
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
                    <img src="../assests/images/post_images/<?php echo getUserPorfileImageByID($sessionUserID) ?>" alt="profile"
                        class="avatar">
                    <input type="text" class="comment-input" style="color: white;"
                        placeholder="Write a comment...">
                    <button class="send-btn" type="button"
                        onclick="write_comment(<?php echo $row['postID']; ?>)">
                        <i class="fas fa-paper-plane iconColor"></i>
                    </button>
                </div>
            </div>

        </form>

    </div>

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

<?php }




?>