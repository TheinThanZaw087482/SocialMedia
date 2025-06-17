<?php
session_start(); 
include("../includes/db.php");

if(isset($_POST['post_id'])){
    $postID = intval($_POST['post_id']);
    $userid = $_SESSION['userid'];
    $sql = "INSERT INTO `savepost` (`postID`, `userID`) VALUES (?, ?);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is",$postID,$userid);
    if($stmt->execute()){
        echo 'success';
    }else{
        echo 'error';
    }


}

?>