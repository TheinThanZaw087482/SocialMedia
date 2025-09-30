<?php
include("../includes/db.php");
function get_all_users() {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE approve = 1");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        return $users;
    } else {
        return [];
    }
}
function get_user_by_userID($userID) {
    global $conn;
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return null; // query failed
    }

    $stmt->bind_param("s", $userID);
    if ($stmt->execute()) {
        $result = $stmt->get_result(); // fetch result set
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // return 1 user as array
        }
    }

    return null; // user not found or error
}

