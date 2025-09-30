<?php
include("../includes/db.php");

function total_Users() {
    global $conn;

    $stmt = $conn->prepare("SELECT COUNT(*) AS totalUsers FROM users");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['totalUsers']; // This correctly returns an integer
    } else {
        return 0;
    }
}

function new_Users(){
    global $conn;

    $stmt = $conn->prepare("SELECT COUNT(*) AS newUsers FROM users WHERE approve = '0'");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['newUsers']; // This correctly returns an integer
    } else {
        return 0;
    }
}


function today_Posts($tdate) {
    global $conn;

    $stmt = $conn->prepare("SELECT COUNT(*) AS tposts FROM post WHERE DATE(postdate) = ?");
    $stmt->bind_param("s", $tdate);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['tposts'];
    } else {
        return 0;
    }
}

function active_Users(){
    global $conn;

    $stmt = $conn->prepare("SELECT COUNT(*) AS activeUsers FROM users WHERE approve = '1'");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['activeUsers']; // This correctly returns an integer
    } else {
        return 0;
    }
}

?>
