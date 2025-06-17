<?php
session_start();
include("../includes/db.php");

if (isset($_POST['post_id'])) {
    $postId = intval($_POST['post_id']);
    $userId = $_SESSION['userid'];

    $stmt = $conn->prepare("INSERT INTO hidden_posts(userID, postID) VALUES(?, ?)");
    $stmt->bind_param("ii", $userId, $postId);

    if ($stmt->execute()) {
        echo 'success';  // 🔙 This goes back to JS
    } else {
        echo 'error';
    }
}
?>