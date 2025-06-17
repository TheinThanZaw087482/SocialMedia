<?php
include("../includes/db.php");

function fetch_comment($postID) {
    global $conn;
    $sql = "SELECT u.name,u.ProfileimagePath,c.commentID,c.content,c.commentDate FROM comment c JOIN users u ON u.userid =c.userID WHERE c.postID = ? ORDER BY c.commentDate DESC; ;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postID);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Return all comments for that postID
    } else {
        return false;
    }
}
function genereate_commandID(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM comment";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $nextNumber = $row['total'] + 1;
    $commandID = 'C' . $nextNumber;
    return $commandID;
}

function genereate_ReplyID(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM reply";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $nextNumber = $row['total'] + 1;
    $ReplyID = 'R' . $nextNumber;
    return $ReplyID;

}
function fetch_reply($parentID){
    global $conn;
    $sql = "SELECT u.name ,u.ProfileimagePath, r.replyID,r.content,r.replyDate,r.ParentID 
    FROM reply r JOIN users u ON r.replyUserID=u.userid WHERE r.ParentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$parentID);
    if($stmt->execute()){
        $result = $stmt->get_result();
       return $result->fetch_all(MYSQLI_ASSOC);
    }else{
        return[];
    }
}

// Make sure your database connection ($conn) and getUserPorfileImageByID function are accessible
// You might need to include them here or ensure they are global/passed.

/**
 * Recursively displays a comment or reply and its children.
 *
 * @param array $item The comment or reply data array.
 * @param bool $is_reply True if this item is a reply (from the 'reply' table), false if a main comment.
 */
function display_comment_tree($item, $is_reply = false) {
    // Determine the unique ID field based on whether it's a main comment or a reply
    // If your main comments have 'commentID' and replies have 'replyID'
    $current_item_id = $is_reply ? $item['replyID'] : $item['commentID'];

    // Assuming these fields ('ProfileimagePath', 'name', 'content') are consistent across both comment and reply objects
    $profile_image_path = $item['ProfileimagePath'];
    $author_name = $item['name'];
    $content = $item['content'];

    // You might also need to pass $postID if your write_reply function still depends on it
    // For the JS write_reply, it needs the parentID and the element itself.
    // The $postID here is primarily for the initial fetch_comment.
    global $postID; // Make $postID accessible if needed for the JS function call

    ?>
    <div class="comment-item" data-comment-id="<?php echo htmlspecialchars($current_item_id); ?>">
        <img src="../assests/images/post_images/<?php echo htmlspecialchars($profile_image_path); ?>" alt="Profile Picture" class="comment-profile-pic">
        <div class="comment-content-bubble">
            <div class="comment-author"><?php echo htmlspecialchars($author_name); ?></div>
            <div class="comment-text">
                <?php
                // Logic to display @mentions if the content starts with @
                if ($is_reply && strpos($content, '@') === 0) {
                    $parts = explode(' ', $content, 2);
                    if (count($parts) > 1) {
                        echo '<span class="tag-user">' . htmlspecialchars($parts[0]) . '</span> ' . htmlspecialchars($parts[1]);
                    } else {
                        echo htmlspecialchars($content); // Just the tag if no other content
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
            <span class="comment-reactions">0</span> </div>
        <div class="reply-input-container" style="display: none;">
            <img src="../assests/images/post_images/<?php echo htmlspecialchars(getUserPorfileImageByID($_SESSION['userid'])); ?>" class="profile-pic">
            <input type="text" class="form-control" placeholder="Write a reply...">
            <button class="send-btn" type="button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>

        <?php
        // Fetch direct replies for the current item
        // This is crucial: Use the $current_item_id as the ParentID for the next level
        $child_replies = fetch_reply($current_item_id);

        if (!empty($child_replies)) {
        ?>
            <div class="replies-container">
                <?php
                foreach ($child_replies as $child_reply) {
                    // Recursively call the function for each child reply
                    display_comment_tree($child_reply, true);
                }
                ?>
            </div>
        <?php
        } // End if !empty($child_replies)
        ?>
    </div>
    <?php
}
?>


