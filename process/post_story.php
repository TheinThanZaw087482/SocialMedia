<?php
session_start();
include("../includes/db.php"); // Make sure db.php correctly establishes $conn

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    echo 'error: not logged in';
    exit();
}

$userid = $_SESSION['userid'];

// Get content from POST data
$content = isset($_POST['content']) ? $_POST['content'] : '';

$newImagePath = null; // Initialize image path to null, will store just the filename

// Handle image upload
if (isset($_FILES['storyImage']) && $_FILES['storyImage']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['storyImage'];

    // Validate file type (optional but recommended)
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedMimeTypes)) {
        echo 'error: invalid file type';
        exit();
    }

    $uploadDir = '../assests/images/story_images/';
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = time() . '_' . uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $newName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // *** THIS IS THE ONLY LINE THAT CHANGES ***
        $newImagePath = $newName; // Store ONLY the filename in the database
    } else {
        echo 'error: failed to move uploaded file';
        exit();
    }
}

$time = date("Y-m-d H:i:s");
$status = 1; // Assuming 1 for active, 0 for inactive

// Insert into story table
$sql = "INSERT INTO story(userid, content, imagePath, time, status) VALUES(?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo 'error: prepare failed - ' . $conn->error;
    exit();
}

// Ensure the data types for bind_param match your database schema
// For VARCHAR/TEXT, use 's'; for INT, use 'i'
$stmt->bind_param("isssi", $userid, $content, $newImagePath, $time, $status);

if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'error: execute failed - ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>