<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../includes/db.php");

$userid = $_SESSION['userid'];

$sql = "SELECT 
            n.id,
            n.senderID,
            u.name AS sender_name,
            u.email,
            u.gender,
            u.birthdate,
            pro.Address,
            pro.ProfileimagePath AS sender_profile,
            u.Batch,
            n.type,
            n.link,
            n.created_at
        FROM notifications n
        JOIN users u ON n.senderID = u.userid 
        JOIN profile pro ON u.userid = pro.userid
        WHERE n.reciverID = ?
        ORDER BY n.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();
$notifications = [];

while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        "senderID" => $row["senderID"],
        "name" => $row["sender_name"],
        "profile" => $row["sender_profile"] ?: "assests/images/default.png",
        "type" => $row["type"],
        "link" => $row["link"],
        "created_at" => $row["created_at"],
        "email" =>$row["email"],
        "gender"=> $row["gender"],
        "birthdate" => $row["birthdate"],
        "address" => $row["Address"],
        "batch"  => $row["Batch"]
    ];
}

header('Content-Type: application/json');
echo json_encode($notifications);
