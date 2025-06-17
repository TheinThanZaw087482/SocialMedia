<?php
// process/upload_file.php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php';

session_start();

$myUserId = $_SESSION['userid'] ?? null;
if (!$myUserId) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Authentication required."]);
    exit();
}

$receiverId = $_POST['receiver_id'] ?? null;
if ($receiverId === null || ($receiverId = filter_var($receiverId, FILTER_VALIDATE_INT)) === false) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid or missing receiver ID."]);
    exit();
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No file uploaded or upload error occurred."]);
    exit();
}

$file = $_FILES['file'];
$fileName = basename($file['name']);
$fileTmpName = $file['tmp_name'];
$fileSize = $file['size'];
$fileType = $file['type'];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

$allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$allowedFileExtensions = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
$allowedExtensions = array_merge($allowedImageExtensions, $allowedFileExtensions);

$maxFileSize = 10 * 1024 * 1024; // 10MB limit

if (!in_array($fileExt, $allowedExtensions)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Unsupported file type."]);
    exit();
}
if ($fileSize > $maxFileSize) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "File size exceeds the limit (" . ($maxFileSize / (1024 * 1024)) . "MB)."]);
    exit();
}

$uploadDir = __DIR__ . '/../uploads/chat_files/';
$fileUrlBase = '/uploads/chat_files/';

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0775, true)) {
        error_log("Failed to create upload directory: " . $uploadDir);
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Server could not prepare upload directory."]);
        exit();
    }
}

$newFileName = uniqid('chat_file_') . '.' . $fileExt;
$filePath = $uploadDir . $newFileName;
$fileUrl = $fileUrlBase . $newFileName;

if (move_uploaded_file($fileTmpName, $filePath)) {
    try {
        $conn->beginTransaction();

        $messageType = (in_array($fileExt, $allowedImageExtensions)) ? 'image' : 'file';
        $messageContent = $fileName;

        $stmt = $conn->prepare("
            INSERT INTO messages (sender_id, receiver_id, message_content, message_type, file_url, created_at)
            VALUES (:sender_id, :receiver_id, :message_content, :message_type, :file_url, NOW())
        ");
        $stmt->bindParam(':sender_id', $myUserId, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $receiverId, PDO::PARAM_INT);
        $stmt->bindParam(':message_content', $messageContent, PDO::PARAM_STR);
        $stmt->bindParam(':message_type', $messageType, PDO::PARAM_STR);
        $stmt->bindParam(':file_url', $fileUrl, PDO::PARAM_STR);
        $stmt->execute();

        $messageId = $conn->lastInsertId();

        $user1 = min($myUserId, $receiverId);
        $user2 = max($myUserId, $receiverId);

        $previewText = ($messageType === 'image') ? '📷 Image' : '📎 File';
        $stmt_conv = $conn->prepare("
            INSERT INTO conversations (user1_id, user2_id, last_message_id, last_message_content, last_message_timestamp)
            VALUES (:user1, :user2, :msg_id, :msg_content, NOW())
            ON DUPLICATE KEY UPDATE
                last_message_id = :msg_id,
                last_message_content = :msg_content,
                last_message_timestamp = NOW();
        ");
        $stmt_conv->bindParam(':user1', $user1, PDO::PARAM_INT);
        $stmt_conv->bindParam(':user2', $user2, PDO::PARAM_INT);
        $stmt_conv->bindParam(':msg_id', $messageId, PDO::PARAM_INT);
        $stmt_conv->bindParam(':msg_content', $previewText, PDO::PARAM_STR);
        $stmt_conv->execute();

        $conn->commit();

        echo json_encode([
            "status" => "success",
            "message" => "File uploaded and message sent.",
            "message_id" => $messageId,
            "file_url" => $fileUrl,
            "message_type" => $messageType
        ]);

    } catch (PDOException $e) {
        $conn->rollBack();
        error_log("Database error after file upload: " . $e->getMessage());
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to record message in database after upload."]);
    }
} else {
    error_log("Failed to move uploaded file from {$fileTmpName} to {$filePath}");
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to store the uploaded file on the server."]);
}
?>