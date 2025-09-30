<?php
include("../includes/db.php");
function get_all_users()
{
    global $conn;
    $stmt = $conn->prepare("SELECT u.userid,
    u.name,
    u.email, 
    u.gender, 
    u.birthdate, 
    u.Batch, 
    u.userType, 
    u.approve, 
    pro.ProfileimagePath, 
    pro.coverPhoto, 
    pro.Address, 
    pro.login_date, 
    pro.online, 
    pro.nickname, 
    pro.bio FROM users u LEFT JOIN profile pro ON u.userid = pro.userid WHERE u.approve = 1;
");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // return as array
    }
    return [];
}

function get_user_by_userID($userID)
{
    global $conn;
    $sql = "SELECT u.userid,
    u.name,
    u.email, 
    u.gender, 
    u.birthdate, 
    u.Batch, 
    u.userType, 
    u.approve, 
    pro.ProfileimagePath, 
    pro.coverPhoto, 
    pro.Address, 
    pro.login_date, 
    pro.online, 
    pro.nickname, 
    pro.bio FROM users u LEFT JOIN profile pro ON u.userid = pro.userid
    WHERE u.userid = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return null;

    $stmt->bind_param("s", $userID);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }
    return null;
}

function get_search_users($search){
    global $conn;
    $sql = "SELECT * FROM users u 
            LEFT JOIN profile pro ON u.userid = pro.userid
            WHERE u.name LIKE ? 
               OR pro.bio LIKE ? 
               OR u.userid LIKE ? 
               OR pro.Address LIKE ? 
               OR u.Batch LIKE ? 
               OR u.userType LIKE ? 
               OR pro.nickname LIKE ?";

    $stmt = $conn->prepare($sql);
    $search_term = "%" . $search . "%";
    $stmt->bind_param("sssssss", $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
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

function get_all_story(){
    global $conn;
    $sql = "SELECT * FROM story";
    $result = mysqli_query($conn, $sql);
    $stories = []; // Initialize an empty array to hold all stories

    if ($result) {
        // Loop through each row in the result set
        while ($row = $result->fetch_assoc()) {
            $stories[] = $row; // Add each row (story) to the array
        }
        $result->free(); // Free the result set memory
        return $stories;
    } else {
        // Log the error for debugging purposes (optional but recommended)
        error_log("Error fetching stories: " . mysqli_error($conn));
        return [];
    }
}
