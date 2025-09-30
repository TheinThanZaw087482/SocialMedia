<?php
session_start();
include("../includes/db.php");
include("fetch_comment.php"); 

if (isset($_POST['parent_id'], $_POST['content']) && isset($_SESSION['userid'])) {
    $parentID = trim($_POST['parent_id']); // This would be the commentID or replyID of the parent
    $content = trim($_POST['content']);
    $userid = $_SESSION['userid'];
    $replyID = genereate_ReplyID(); 

    $idType = getIDType($parentID);
    $sql = '';
    if($idType == "comment"){
        $sql = "INSERT INTO reply (replyID, replyUserID, content, Parent_commentID) VALUES (?, ?, ?, ?)";

    }else if($idType == "reply"){
        $sql = "INSERT INTO reply (replyID, replyUserID, content, Parent_ReplyID) VALUES (?, ?, ?, ?)";

    }else{
        echo "ParentID error";
    }


     // Added createdAt
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $replyID, $userid, $content, $parentID);

        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
        $stmt->close();
    } else {
        echo 'prepare_error';
    }
    $conn->close();
} else {
    echo 'missing_data';
}
?>