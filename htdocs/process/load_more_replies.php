<?php
// process/load_more_replies.php

// Include necessary files (db_connection, and the file containing fetch_reply, getUserPorfileImageByID)
session_start();
include("../includes/db.php"); // Your DB connection file
include("../process/fetch_comment.php"); // Assuming fetch_reply, getUserPorfileImageByID are here

// --- Simplified display function for AJAX partials ---
// This function needs to be able to render a single reply item for AJAX appending.
// It should *not* make recursive calls to display_comment_tree as JavaScript will append these directly.
function render_ajax_reply_item($item) {
    $current_item_id = $item['replyID']; // For AJAX loaded replies, it's always a reply
    $profile_image_path = $item['ProfileimagePath'];
    $author_name = $item['name'];
    $content = $item['content'];

    $currentUserProfilePic = '';
    if (isset($_SESSION['userid'])) {
        $currentUserProfilePic = getUserPorfileImageByID($_SESSION['userid']);
    }
    ?>
    <div class="comment-item" data-comment-id="<?php echo htmlspecialchars($current_item_id); ?>">
        <img src="../assests/images/post_images/<?php echo htmlspecialchars($profile_image_path); ?>" alt="Profile Picture" class="comment-profile-pic">
        <div class="comment-content-bubble">
            <div class="comment-author"><?php echo htmlspecialchars($author_name); ?></div>
            <div class="comment-text">
                <?php
                if (strpos($content, '@') === 0) {
                    $parts = explode(' ', $content, 2);
                    if (count($parts) > 1) {
                        echo '<span class="tag-user">' . htmlspecialchars($parts[0]) . '</span> ' . htmlspecialchars($parts[1]);
                    } else {
                        echo htmlspecialchars($parts[0]);
                    }
                } else {
                    echo htmlspecialchars($content);
                }
                ?>
            </div>
        </div>
        <div class="comment-actions">
            <span class="comment-time">Some Time Ago</span>
            <span class="comment-action-link like-button">Like</span>
            <span class="comment-action-link reply-button">Reply</span>
            <span class="comment-reactions">0</span>
        </div>
        <div class="reply-input-container" style="display: none;">
            <img src="../assests/images/post_images/<?php echo htmlspecialchars($currentUserProfilePic); ?>" class="profile-pic">
            <input type="text" class="form-control" placeholder="Write a reply...">
            <button class="send-btn" type="button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
    <?php
}

// Check if it's an AJAX POST request with required parameters
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['parent_id']) && isset($_POST['offset']) && isset($_POST['limit'])) {
    $parent_id = $_POST['parent_id'];
    $offset = (int)$_POST['offset'];
    $limit = (int)$_POST['limit'];

    $more_replies = fetch_reply($parent_id, $limit, $offset);

    if (!empty($more_replies)) {
        foreach ($more_replies as $reply) {
            render_ajax_reply_item($reply); // Render each reply item as HTML
        }
    }
    // If no replies, nothing is outputted, which is fine for the AJAX response.
} else {
    http_response_code(400); // Bad Request
    echo "Missing or invalid parameters.";
}
?>