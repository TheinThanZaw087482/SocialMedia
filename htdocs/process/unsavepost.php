<?php
session_start();
include("../includes/db.php");

if(isset($_POST['post_id'])){
    $userid = $_SESSION['userid'];
    $postid = $_POST['post_id'];

    $sql = "Delete from savepost where userID =? and postID =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$userid,$postid);
    if($stmt->execute()){
        echo 'success';
    }else{
        echo 'error';
    }

}
