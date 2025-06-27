<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include("../includes/db.php");

$groupedStories = [];
$usersData = [];

/**
 * Converts a datetime string to a human-readable "X time ago" format.
 * Assumes $datetime is a valid date/time string parseable by DateTime.
 *
 * @param string $datetime The datetime string from the database (e.g., 'YYYY-MM-DD HH:MM:SS').
 * @return string The human-readable time difference.
 */
function formatTimeAgo($datetime) {
    $now = new DateTime('now', new DateTimeZone('Asia/Yangon'));
    $ago = new DateTime($datetime); 
    $diff = $now->diff($ago);

    if ($diff->y > 0) {
        return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    }
    if ($diff->m > 0) {
        return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    }
    if ($diff->d > 0) {
        if ($diff->d == 1) {
            return 'yesterday';
        }
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    }
    if ($diff->h > 0) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    }
    if ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    }
    // If less than a minute, show seconds or "just now"
    if ($diff->s > 0) {
        return $diff->s . ' second' . ($diff->s > 1 ? 's' : '') . ' ago';
    }
    return 'just now';
}


// SQL Query to get only users who have at least one story,
// and filter stories to those posted within the last 24 hours.
$sql = "
    SELECT
        u.userid,
        u.name AS user_name,
        u.ProfileimagePath AS user_avatar,
        s.story_ID,
        s.time, -- Select the raw time for calculation
        s.imagePath AS content_src
    FROM
        users u
    INNER JOIN           
        story s ON u.userid = s.userid
    WHERE
        s.time >= (NOW() - INTERVAL 1 DAY) 
    ORDER BY
        u.userid ASC, s.time DESC 
";

$result = $conn->query($sql);

if ($result === FALSE) {
    echo json_encode(["error" => "SQL query failed: " . $conn->error]);
    $conn->close();
    exit();
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $userId = $row['userid'];

        if (!isset($usersData[$userId])) {
            $usersData[$userId] = [
                'userId' => $userId,
                'userName' => $row['user_name'],
                'userAvatar' => "../assests/images/post_images/" . $row['user_avatar'],
                'stories' => []
            ];
        }
        $formattedTime = formatTimeAgo($row['time']);

        $usersData[$userId]['stories'][] = [
            'id' => $row['story_ID'],
            'time' => $formattedTime, // Use the formatted time
            'content' => [
                'type' => 'image', // Adjust if you have a content_type column in your DB
                'src' => "../assests/images/story_images/" . $row['content_src'] // Path for story images
            ]
        ];
    }

    $groupedStories = array_values($usersData);
}

$conn->close();

echo json_encode($groupedStories);
?>