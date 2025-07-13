<?php
include("../includes/db.php");
include("../includes/noti_functions.php");

if (isset($_POST["btn_edit_profile"])) {
    $userid = $_SESSION['userid'];
    $name  = $_POST['profileName'];
    $bio  = $_POST['profileBio'];
    $nickName = $_POST['nickName'];
    $mail = $_POST['profileEmail'];
    $DOB = $_POST['profileBirthday'];


    $stmt = $conn->prepare("UPDATE users JOIN profile SET users.name = ?, users.email = ?,profile.nickname = ?, users.birthdate = ? ,profile.bio = ? WHERE `users`.`userid` = ?;");
    $stmt->bind_param("ssssss", $name,$mail,$nickName,$DOB,$bio, $userid);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Profile updated successfully Please login again'); window.location.href='../';</script>";
    } else {
        echo "<script>alert('❌ Failed to update profile: " . $stmt->error . "');</script>";
    }
}
if (isset($_POST["btn_user_accept"])) {
    $userid = $_POST['profileId'];
    $userName = $_POST['profileName'];
    $userMail = $_POST['profileEmail'];
    $gender = $_POST['profileGender'];
    $Dob = $_POST['profileBirthday'];
    $batch = $_POST['profileBatch'];
    $userType = $_POST['profileRole'];
    $senderID = $_POST['profileId'];

    $stmt = $conn->prepare("UPDATE `users` SET `name` = ?, `email` = ?, `gender` = ?, `birthdate` = ?, `Batch` = ?, `userType` = ?, `approve` = 1 WHERE `userid` = ?");
    $stmt->bind_param("sssssss", $userName, $userMail, $gender, $Dob, $batch, $userType, $userid);

    if ($stmt->execute()) {
        // Delete the related notification
        $deleteStmt = $conn->prepare("DELETE FROM notifications WHERE senderID = ? AND type = 'Register'");
        $deleteStmt->bind_param("s", $senderID);
        $deleteStmt->execute();

        echo "<script>alert('✅ Access successful'); window.location.href='../pages/notione.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to Access: " . $stmt->error . "');</script>";
    }
}

