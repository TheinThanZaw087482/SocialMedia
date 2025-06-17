<?php
session_start();
include '../includes/db.php'; // Ensure this sets $conn

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('Please log in to update profile picture'); window.location.href='../index.php';</script>";
    exit();
}

$imageName = null;
if (!empty($_FILES['profile_img']['name'])) {
    if ($_FILES['profile_img']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Upload failed with error code: {$_FILES['profile_img']['error']}');</script>";
        exit();
    }

    $uploadDir = '../assests/images/post_images/';
    $ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
    $imageName = time() . '_' . uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $imageName;

    if (getimagesize($_FILES['profile_img']['tmp_name'])) {
        if (!move_uploaded_file($_FILES['profile_img']['tmp_name'], $targetPath)) {
            echo "<script>alert('Failed to move uploaded file.');</script>";
            exit();
        }
    } else {
        echo "<script>alert('Uploaded file is not a valid image.');</script>";
        exit();
    }
} else {
    echo "<script>alert('No image selected.');</script>";
    exit();
}

$stmt = $conn->prepare("UPDATE `users` SET `ProfileimagePath` = ? WHERE `userid` = ?");
$stmt->bind_param("ss", $imageName, $_SESSION['userid']);

if ($stmt->execute()) {
    echo "<script>alert('✅ Profile picture updated successfully'); window.location.href='../pages/profile.php';</script>";
} else {
    echo "<script>alert('❌ Failed to update profile: " . $stmt->error . "');</script>";
}

$stmt->close();
?>
