<?php
session_start();
include("../includes/db.php"); // Your database connection file - ensure it sets up $conn

// Set content type header for JSON response
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['story_image'])) {
    if (!isset($_SESSION['userid'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    $user_id = $_SESSION['userid'];
    $target_dir = "../assests/images/story_images/"; // Make sure this directory exists and is writable

    // Create the directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory with full permissions recursively
    }

    $original_file_name = basename($_FILES["story_image"]["name"]);
    $imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

    // Generate a unique filename to prevent conflicts
    $unique_file_name = uniqid('story_', true) . '.' . $imageFileType;
    $target_file = $target_dir . $unique_file_name;

    $uploadOk = 1;

    // Check if image file is an actual image
    $check = getimagesize($_FILES["story_image"]["tmp_name"]);
    if($check === false) {
        echo json_encode(['success' => false, 'message' => 'File is not an image.']);
        $uploadOk = 0;
        exit;
    }

    // Check file size (e.g., 5MB limit)
    if ($_FILES["story_image"]["size"] > 5000000) {
        echo json_encode(['success' => false, 'message' => 'Sorry, your file is too large (max 5MB).']);
        $uploadOk = 0;
        exit;
    }

    // Allow certain file formats
    $allowed_types = ["jpg", "png", "jpeg", "gif"];
    if(!in_array($imageFileType, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.']);
        $uploadOk = 0;
        exit;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo json_encode(['success' => false, 'message' => 'Sorry, your file was not uploaded due to an error.']);
    } else {
        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES["story_image"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now save the unique file name to the database
            $story_path_for_db = $unique_file_name; // This is the image name you want to store

            // Prepare and execute the database insert statement
            // Assuming your database table is named 'stories' and has columns 'user_id', 'image_name', 'created_at'
            $stmt = $conn->prepare("INSERT INTO story (userid , imagePath, time) VALUES (?, ?, NOW())");

            if ($stmt) {
                // 'i' for integer (user_id), 's' for string (image_name)
                $stmt->bind_param("ss", $user_id, $story_path_for_db);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Story uploaded and saved successfully!']);
                    
                } else {
                    // Log database error for debugging, but provide a generic message to the user
                    error_log("Database error during story insert: " . $stmt->error);
                    echo json_encode(['success' => false, 'message' => 'Failed to save story to database.']);
                }
                $stmt->close();
            } else {
                // Log preparation error
                error_log("Failed to prepare statement for story insert: " . $conn->error);
                echo json_encode(['success' => false, 'message' => 'Internal server error: Could not prepare statement.']);
            }
        } else {
            // Failed to move the uploaded file
            echo json_encode(['success' => false, 'message' => 'Sorry, there was an error moving your uploaded file. Check directory permissions.']);
        }
    }
} else {
    
    echo json_encode(['success' => false, 'message' => 'Invalid request: No file uploaded or wrong method.']);
}
?>