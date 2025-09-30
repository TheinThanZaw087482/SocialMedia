<?php
session_start(); // Start the session if needed for authorization/authentication
include("../includes/db.php");

header('Content-Type: application/json'); // Crucial: Tell the client to expect JSON

// Check if database connection was successful
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

if (!isset($_POST['post_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Post ID is missing.']);
    exit();
}

$postID = intval($_POST['post_id']);

try {
    $sql = "UPDATE `post` SET `is_ban` = '1' WHERE `post`.`postID` = ?;";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("i", $postID);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Post successfully banned.']);
        } else {
            // This case means the postID exists but the row was already banned or postID didn't match
            echo json_encode(['status' => 'info', 'message' => 'Post was already banned or Post ID not found.']);
        }
    } else {
        throw new Exception('Error executing statement: ' . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if ($conn) {
        $conn->close();
    }
}
?>