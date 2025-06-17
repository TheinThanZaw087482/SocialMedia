<?php
include '../includes/db.php';

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);

    $stmt = $conn->prepare("SELECT u.name, u.ProfileimagePath, r.type 
                            FROM reaction r 
                            JOIN users u ON r.userID = u.userid 
                            WHERE r.postID = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
    exit;
}
?>
