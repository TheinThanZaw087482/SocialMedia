<?php
session_start();
include("../includes/db.php"); // assumes $conn is a mysqli connection

header("Content-Type: application/json");

$response = ["success" => false, "message" => "Invalid request."];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "add_batch") {

    $batch_name = trim($_POST["new_batch_name"]);

    if (empty($batch_name)) {
        echo json_encode(["success" => false, "message" => "Batch name is required."]);
        exit;
    }

    // extract number and build batch_id
    if (!preg_match('/(\d+)/', $batch_name, $matches)) {
        echo json_encode(["success" => false, "message" => "Batch name must contain a number (e.g., 'Batch 101')."]);
        exit;
    }

    $number = $matches[1];
    $batch_id = 'bt' . $number;

    // check if batch_name already exists
    $check_sql = "SELECT COUNT(*) FROM batch WHERE batch_name = ?";
    $stmt = $conn->prepare($check_sql);
    if (!$stmt) {
        error_log("Failed to prepare statement for batch_name check: " . $conn->error);
        echo json_encode(["success" => false, "message" => "Server error during batch name check."]);
        exit;
    }

    $stmt->bind_param("s", $batch_name);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(["success" => false, "message" => "Batch name '{$batch_name}' already exists."]);
        exit;
    }

    // insert new batch
    $insert_sql = "INSERT INTO batch (batch_id, batch_name) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_sql);
    if (!$stmt) {
        error_log("Failed to prepare statement for batch insert: " . $conn->error);
        echo json_encode(["success" => false, "message" => "Server error during batch insert."]);
        exit;
    }

    $stmt->bind_param("ss", $batch_id, $batch_name);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Batch added successfully!", "batch_id" => $batch_id, "batch_name" => $batch_name]);
    } else {
        error_log("Database error inserting batch: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Failed to save batch due to a database error."]);
    }

    $stmt->close();
    exit;
}

echo json_encode($response);
exit;
?>