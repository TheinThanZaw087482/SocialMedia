<?php
include '../includes/db.php';
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $type = $_GET['type'];

    if ($type === 'All') {
        // Fetch all reactions
        $stmt = $conn->prepare("SELECT u.name, pro.ProfileimagePath, r.type 
                                FROM reaction r 
                                JOIN users u ON r.userID = u.userid JOIN profile pro ON u.userid = pro.userid
                                WHERE r.postID = ?");
        $stmt->bind_param("i", $post_id);
    } else {
        $stmt = $conn->prepare("SELECT u.name, pro.ProfileimagePath, r.type 
                                FROM reaction r 
                                JOIN users u ON r.userID = u.userid JOIN profile pro ON u.userid = pro.userid
                                WHERE r.postID = ? AND r.type = ?");
        $stmt->bind_param("is", $post_id, $type);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
    exit;
}
