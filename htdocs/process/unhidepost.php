<?php
session_start();
include("../includes/db.php");

if (isset($_POST['post_id'])) {
    $hidden_post_ID = intval($_POST['post_id']);
    $userId = $_SESSION['userid'];

    global $conn;
    $sql = "DELETE FROM hidden_posts WHERE `hidden_posts`.`hidden_post_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$hidden_post_ID);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>

