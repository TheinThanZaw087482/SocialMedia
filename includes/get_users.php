<?php
include("../includes/db.php");
function get_all_users()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // return as array
    }
    return [];
}

function get_user_by_userID($userID)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return null;

    $stmt->bind_param("s", $userID);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }
    return null;
}

function get_same_batch_users($batch, $excludeUserID)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE batch = ? AND userid != ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];

    $stmt->bind_param("ss", $batch, $excludeUserID);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

function get_users_by_userType($userType)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE userType = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];

    $stmt->bind_param("s", $userType);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

function get_last_message_by_userID($myUserId,$receiverId)
{
    global $conn;
     $sql = "SELECT
        m.id,
        m.sender_id,
        m.receiver_id,
        m.message, 
        m.created_at,
        s.name AS sender_name,
        s.ProfileimagePath AS sender_avatar 
    FROM
        messages m
    JOIN
        users s ON m.sender_id = s.userid
    WHERE
        (m.sender_id = ? AND m.receiver_id = ?)
        OR
        (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY
        m.created_atDESC LIMIT 1";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssss", $myUserId, $receiverId, $receiverId, $myUserId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}
