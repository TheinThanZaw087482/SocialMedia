<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include("../includes/db.php"); // Path to your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    $storyOwnerId = isset($data['storyOwnerId']) ? $data['storyOwnerId'] : null;
    $storyId = isset($data['storyId']) ? $data['storyId'] : null;
    $viewerId = isset($data['viewerId']) ? $data['viewerId'] : null;
    $reactionType = isset($data['reactionType']) ? $data['reactionType'] :'view_icon';

    // Basic validation
    if ($storyOwnerId === null || $viewerId === null) {
        echo json_encode(['success' => false, 'message' => 'Missing storyOwnerId or viewerId.']);
        exit();
    }

    // Validate reaction type if it's provided (not null)
    if ($reactionType !== null) {
        $allowedReactions = ['Like', 'Heart', 'Haha', 'Wow', 'Sad', 'Angry','Care','view_icon']; // Match your button titles
        if (!in_array($reactionType, $allowedReactions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid reaction type.']);
            exit();
        }
    }

    $conn->begin_transaction();

    try {
    
        $stmt = $conn->prepare("INSERT INTO story_views (story_owner_id, story_id, viewer_id, reaction_type)
                                 VALUES (?, ?, ?, ?)
                                 ON DUPLICATE KEY UPDATE viewed_at = CURRENT_TIMESTAMP, reaction_type = VALUES(reaction_type)");
        $stmt->bind_param("siss", $storyOwnerId, $storyId, $viewerId, $reactionType);
        $stmt->execute();
        $stmt->close();
        error_log("Story view/reaction recorded for owner: $storyOwnerId, story: " . ($storyId ?? 'N/A') . " by viewer: $viewerId with reaction: " . ($reactionType ?? 'None'));

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Operation successful.']);

    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        error_log("Database Error recording story view/reaction: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]); // For debugging
    } catch (Exception $e) {
        $conn->rollback();
        error_log("General Error recording story view/reaction: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>