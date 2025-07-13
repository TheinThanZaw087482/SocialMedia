<?php
session_start();
include("db.php");
include("get_users.php");
$current_user = get_user_by_userID($_SESSION['userid']);
$same_batch_users = get_same_batch_users($current_user['Batch'], $_SESSION['userid']);
$admin_user = get_users_by_userType("admin");
$teacher = get_users_by_userType("teacher");
$student = get_users_by_userType("student");
function addNotification($senderID, $reciverID, $type, $link = null) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO notifications (senderID, reciverID, type, link, is_read) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $senderID, $reciverID, $type, $link);
    return $stmt->execute(); 
}

function send_noti_to_sameBatch($type, $link) {
    global $same_batch_users;
    foreach ($same_batch_users as $user) {
        addNotification($_SESSION['userid'], $user['userid'], $type, $link);
    }
}
function send_noti_to_Admin($senderID, $type, $link) {
    global $admin_user;
    foreach ($admin_user as $user) {
        addNotification($senderID, $user['userid'], $type, $link);
    }
}
function send_noti_to_Teacher($senderID, $type, $link) {
    global $teachers;
    foreach ($teachers as $teacher) {
        addNotification($senderID, $teacher['userid'], $type, $link);
    }
}
function send_noti_to_Student($senderID, $type, $link){
    global $students;
     foreach ($students as $student) {
        addNotification($senderID, $student['userid'], $type, $link);
    }

}


function delete_noti($notiID){
    global $conn;
    $stmt = $conn->prepare("Delete from notifications where id =?");
    $stmt->bind_param("i",$notiID);
    $stmt->execute();
}

?>

