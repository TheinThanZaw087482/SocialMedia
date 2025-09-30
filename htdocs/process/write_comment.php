<?php
session_start(); 
include("../includes/db.php");
include("fetch_comment.php");

if (isset($_POST['post_id'], $_POST['content']) && isset($_SESSION['userid'])) {
    $postID = intval($_POST['post_id']);
    $content = trim($_POST['content']); // keep as string
    $userid = $_SESSION['userid'];
    $commentID = genereate_commandID();

    $sql = "INSERT INTO `comment` (`commentID`,`postID`, `userID`, `content`) VALUES (?, ?, ?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss",$commentID, $postID, $userid, $content);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'missing_data';
}
?>
