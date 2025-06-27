<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

include("../includes/db.php"); // This must provide a MySQLi $conn object

$myUserId = $_SESSION['userid'] ?? null;
if (!$myUserId) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Authentication required."]);
    exit();
}

$receiverId = $_POST['receiver_id'] ?? null;

if (!is_numeric($receiverId) || $receiverId <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid receiver ID."]);
    exit();
}
$receiverId = (int)$receiverId;

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No file uploaded or an upload error occurred. Error code: " . ($_FILES['file']['error'] ?? 'N/A')]);
    exit();
}

$file = $_FILES['file'];
$fileName = basename($file['name']);
$fileTmpName = $file['tmp_name'];
$fileSize = $file['size'];
$fileType = $file['type'];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

$allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$allowedFileExtensions = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'mp4', 'mov', 'avi', 'webm'];
$allowedExtensions = array_merge($allowedImageExtensions, $allowedFileExtensions);

$maxFileSize = 25 * 1024 * 1024;

if (!in_array($fileExt, $allowedExtensions)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Unsupported file type. Allowed types: " . implode(', ', $allowedExtensions) . "."]);
    exit();
}

if ($fileSize > $maxFileSize) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "File size exceeds the limit (" . ($maxFileSize / (1024 * 1024)) . "MB)."]);
    exit();
}

$baseUploadPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'assests' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'chat_files';
$uploadDir = realpath($baseUploadPath);

if ($uploadDir === false) {
    error_log("Base upload directory does not exist or is inaccessible. Attempted path: " . $baseUploadPath);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Server could not determine upload directory path. Check folder existence and permissions."]);
    exit();
}
$uploadDir .= DIRECTORY_SEPARATOR;

$fileUrlBase = '/assests/uploads/chat_files/';

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0775, true)) {
        error_log("Failed to create upload directory: " . $uploadDir);
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Server could not prepare upload directory."]);
        exit();
    }
}

$newFileName = uniqid('chat_file_', true) . '.' . $fileExt;
$filePath = $uploadDir . $newFileName;
$fileUrl = $fileUrlBase . $newFileName;

if (move_uploaded_file($fileTmpName, $filePath)) {
    // --- MySQLi Transaction and Statement Handling ---
    try {
        $conn->begin_transaction(); // Start MySQLi transaction

        $messageType = 'file';
        $allowedVideoExtensions = ['mp4', 'mov', 'avi', 'webm'];

        if (in_array($fileExt, $allowedImageExtensions)) {
            $messageType = 'image';
        } elseif (in_array($fileExt, $allowedVideoExtensions)) {
            $messageType = 'video';
        }

        $messageContent = $fileName;

        $stmt = $conn->prepare("
            INSERT INTO messages (sender_id, receiver_id, message, message_type, file_url, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");

        // MySQLi bind_param uses type definition string ('i' for int, 's' for string)
        $stmt->bind_param('iisss', $myUserId, $receiverId, $messageContent, $messageType, $fileUrl);

        $stmt->execute();

        $messageId = $conn->insert_id; // Get the ID of the newly inserted message (MySQLi)

        $conn->commit(); // Commit MySQLi transaction

        echo json_encode([
            "status" => "success",
            "message" => "File uploaded and message sent.",
            "message_id" => $messageId,
            "file_url" => $fileUrl,
            "message_type" => $messageType
        ]);

    } catch (Exception $e) { // Catch general Exception for MySQLi errors, or check $stmt->error
        $conn->rollBack(); // Rollback MySQLi transaction
        error_log("Database error after file upload: " . $e->getMessage() . " (MySQLi Error: " . ($conn->error ?? 'N/A') . ")");
        if (file_exists($filePath)) {
            unlink($filePath);
            error_log("Deleted uploaded file due to DB error: " . $filePath);
        }
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to record message in database after upload. Please try again."]);
    } finally {
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close(); // Close statement if it was successfully prepared
        }
    }
} else {
    error_log("Failed to move uploaded file from {$fileTmpName} to {$filePath}");
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to store the uploaded file on the server. Please check server logs."]);
}
?>