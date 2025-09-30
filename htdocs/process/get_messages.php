<?php
session_start();
header('Content-Type: application/json'); // Tell the client we're sending JSON

// Include database connection
include("../includes/db.php");

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit();
}

$receiverId = $_GET['receiver_id'] ?? null;
$myUserId = $_GET['my_user_id'] ?? null; 
// Validate inputs
if (empty($receiverId) || empty($myUserId)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required chat parameters.']);
    exit();
}

$sql = "
    SELECT
        m.id,
        m.sender_id,
        m.receiver_id,
        m.message,
        m.created_at,
        s.name AS sender_name,
        pro.ProfileimagePath AS sender_avatar
    FROM
        messages m
    JOIN
        users s ON m.sender_id = s.userid
    LEFT JOIN
        profile pro ON m.sender_id = pro.userid 
    WHERE
        (m.sender_id = ? AND m.receiver_id = ?)
        OR
        (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY
        m.created_at";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ssss", $myUserId, $receiverId, $receiverId, $myUserId);

if ($stmt->execute()) {
    $result = $stmt->get_result(); // Get the result set
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    // *** MODIFICATION START ***
    echo json_encode(['status' => 'success', 'messages' => $messages]); // Wrap messages in a 'messages' key
    // *** MODIFICATION END ***
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch messages: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();

?>