<?php
include '../includes/db.php';

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);

    $stmt = $conn->prepare("SELECT type, COUNT(*) AS count FROM reaction WHERE postID = ? GROUP BY type");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reactionCounts = [
    'Like' => 0,
    'Love' => 0,
    'Haha' => 0,
    'Wow'  => 0,
    'Angry'=> 0,
    'Sad'  => 0
];

    while ($row = $result->fetch_assoc()) {
        $type = $row['type'];
        $count = $row['count'];
        if (isset($reactionCounts[$type])) {
            $reactionCounts[$type] = $count;
        }
    }

    echo json_encode($reactionCounts);
    exit;
}
?>
