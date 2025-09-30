<?php
session_start();
include '../includes/db.php'; // Ensure this sets $conn

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('Please log in to update profile picture'); window.location.href='../index.php';</script>";
    exit();
}

$imageName = null;
if (!empty($_FILES['coverPhoto']['name'])) {
    if ($_FILES['coverPhoto']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Upload failed with error code: {$_FILES['coverPhoto']['error']}');</script>";
        exit();
    }

    $uploadDir = '../assests/images/post_images/';
    $ext = pathinfo($_FILES['coverPhoto']['name'], PATHINFO_EXTENSION);
    $imageName = time() . '_' . uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $imageName;

    if (getimagesize($_FILES['coverPhoto']['tmp_name'])) {
        if (!move_uploaded_file($_FILES['coverPhoto']['tmp_name'], $targetPath)) {
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

$sqlcheck = "SELECT * FROM profile WHERE userid = ?";
$stmt = $conn->prepare($sqlcheck);
$stmt->bind_param("s", $_SESSION['userid']);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $stmt->close(); 
    $stmt = $conn->prepare("UPDATE profile SET coverPhoto = ? WHERE userid = ?");
} else {
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO profile (coverPhoto, userid) VALUES (?, ?)");
}

$stmt->bind_param("ss", $imageName, $_SESSION['userid']);

if ($stmt->execute()) {?>
   <div id="loadingSpinner" style="display: none;">Loading...</div><?php
    echo "<script>alert('✅ Cover Photo updated successfully'); window.location.href='../pages/profile.php';</script>";
} else {
    echo "<script>alert('❌ Failed to update Cover Photo: " . $stmt->error . "');</script>";
}

$stmt->close();
?>
