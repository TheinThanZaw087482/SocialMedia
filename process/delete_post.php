<?php
session_start();
include("../includes/db.php");

header('Content-Type: application/json'); // Set header to indicate JSON response

// Check if database connection was successful
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

if (!isset($_POST['post_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Post ID is missing.']);
    exit;
}

$postID = intval($_POST['post_id']);

try {
    // Temporarily disable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 0;");

    // Start a transaction for atomicity
    $conn->begin_transaction();

    // 1. Delete from reaction table
    $stmt1 = $conn->prepare("DELETE FROM reaction WHERE postID = ?");
    if (!$stmt1) {
        throw new Exception('Error preparing reaction delete: ' . $conn->error);
    }
    $stmt1->bind_param("i", $postID);
    if (!$stmt1->execute()) {
        throw new Exception('Error executing reaction delete: ' . $stmt1->error);
    }
    $stmt1->close();

    // 2. Delete from comment table
    $stmt3 = $conn->prepare("DELETE FROM comment WHERE postID = ?");
    if (!$stmt3) {
        throw new Exception('Error preparing comment delete: ' . $conn->error);
    }
    $stmt3->bind_param("i", $postID);
    if (!$stmt3->execute()) {
        throw new Exception('Error executing comment delete: ' . $stmt3->error);
    }
    $stmt3->close();

    // 3. Delete from image table
    $stmt4 = $conn->prepare("DELETE FROM image WHERE postID = ?");
    if (!$stmt4) {
        throw new Exception('Error preparing image delete: ' . $conn->error);
    }
    $stmt4->bind_param("i", $postID);
    if (!$stmt4->execute()) {
        throw new Exception('Error executing image delete: ' . $stmt4->error);
    }
    $stmt4->close();

    // 4. Finally, delete from post table
    $stmt2 = $conn->prepare("DELETE FROM post WHERE postID = ?");
    if (!$stmt2) {
        throw new Exception('Error preparing post delete: ' . $conn->error);
    }
    $stmt2->bind_param("i", $postID);
    if (!$stmt2->execute()) {
        throw new Exception('Error executing post delete: ' . $stmt2->error);
    }
    $stmt2->close();

    // If all deletions were successful, commit the transaction
    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Post and all associated data deleted successfully (foreign key checks temporarily disabled).']);

} catch (Exception $e) {
    // If any error occurred, rollback the transaction
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    // Re-enable foreign key checks regardless of success or failure
    if ($conn) {
        $conn->query("SET FOREIGN_KEY_CHECKS = 1;");
        $conn->close();
    }
}
?>