<?php
session_start();
include("../includes/db.php");

if (!isset($_POST['post_id'])) {
    echo 'no post id';
    exit;
}

$postID = intval($_POST['post_id']);

// 1. Delete from reaction table
$stmt1 = $conn->prepare("DELETE FROM reaction WHERE postID = ?");
if (!$stmt1) {
    echo 'error preparing reaction delete: ' . $conn->error;
    exit;
}
$stmt1->bind_param("i", $postID);
$stmt1->execute();
$stmt1->close();

// 2. Delete from post table
$stmt2 = $conn->prepare("DELETE FROM post WHERE postID = ?");
if (!$stmt2) {
    echo 'error preparing post delete: ' . $conn->error;
    exit;
}
$stmt2->bind_param("i", $postID);
if ($stmt2->execute()) {
    echo 'success';
} else {
    echo 'error executing post delete: ' . $stmt2->error;
}
$stmt2->close();
?>
