<?php
// Assuming db.php is included and $conn is available
// Assuming formatTimeAgo function is also defined


// ... (Your existing includes and session_start)

// Ensure PHP's default timezone is set correctly at the very top of your script
date_default_timezone_set('Asia/Yangon');

function formatTimeAgo($datetime)
{
    // Define the timezone for consistent calculations and interpretation
    $timezone = new DateTimeZone('Asia/Yangon');

    // Get current time in the specified timezone
    $now = new DateTime('now', $timezone);

    // Create the past DateTime object, explicitly stating it's in Asia/Yangon time
    $ago = new DateTime($datetime, $timezone);

    // --- START DEBUGGING LOGS ---
    error_log("DEBUG: Original DB datetime string: " . $datetime);
    error_log("DEBUG: \$now (current time): " . $now->format('Y-m-d H:i:s P (e)')); // P includes timezone offset, e includes timezone name
    error_log("DEBUG: \$ago (story time):   " . $ago->format('Y-m-d H:i:s P (e)'));

    $diff = $now->diff($ago);
    error_log("DEBUG: Diff invert property: " . $diff->invert); // Should be 0 or false for past times
    error_log("DEBUG: Diff days: " . $diff->d . ", hours: " . $diff->h . ", minutes: " . $diff->i . ", seconds: " . $diff->s);
    // --- END DEBUGGING LOGS ---

    // Handle future dates (if $ago is later than $now)
    if ($diff->invert) {
        // Based on the '6 hours ago' and now 'in the future', this is likely the issue
        // Let's be more specific here. If invert is true, it means $ago > $now.
        // We'll calculate the *positive* difference here to show how far *into the future* it is.
        $futureDiff = $ago->diff($now); // Calculate diff from ago to now
        if ($futureDiff->y > 0) return  $futureDiff->y . ' year' . ($futureDiff->y > 1 ? 's' : '');
        if ($futureDiff->m > 0) return  $futureDiff->m . ' month' . ($futureDiff->m > 1 ? 's' : '');
        if ($futureDiff->d > 0) return  $futureDiff->d . ' day' . ($futureDiff->d > 1 ? 's' : '');
        if ($futureDiff->h > 0) return  $futureDiff->h . ' hour' . ($futureDiff->h > 1 ? 's' : '');
        if ($futureDiff->i > 0) return  $futureDiff->i . ' minute' . ($futureDiff->i > 1 ? 's' : '');
        if ($futureDiff->s > 0) return  $futureDiff->s . ' second' . ($futureDiff->s > 1 ? 's' : '');
        return 'just now'; // Or 'a moment from now' if you want to be precise for tiny differences
    }

    // Get total days for 'yesterday' and 'days ago' for robustness
    $totalDays = $diff->days;

    if ($diff->y > 0) {
        return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    }
    if ($diff->m > 0) {
        return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    }
    if ($totalDays == 1) { // Check for exactly one full day difference (yesterday)
        return 'yesterday';
    }
    if ($diff->d > 0) { // For more than one day
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

function get_stories()
{
    global $conn; // Make sure $conn is accessible

    $groupedStories = []; // This will be the final output array
    $usersData = []; // Temporary array to group stories by user

    // IMPORTANT: Check if $_SESSION['userid'] is set before using it
    if (!isset($_SESSION['userid'])) {
        error_log("Attempted to call get_stories() without a logged-in user.");
        return []; // Return empty array if user is not logged in
    }

    $current_user_id = $_SESSION['userid']; // Get user ID safely

    $sql = "
    SELECT
        u.userid,
        u.name AS user_name,
        pro.ProfileimagePath AS user_avatar,
        s.story_ID,
        s.time,
        s.imagePath AS content_src
    FROM
        users u
    INNER JOIN
        story s ON u.userid = s.userid
    LEFT JOIN
        profile pro ON u.userid = pro.userid
    WHERE
        s.time >= (NOW() - INTERVAL 1 DAY) AND u.userid != ?
    ORDER BY
        u.userid ASC, s.time DESC
    LIMIT 0, 25;
    ";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Check if the statement preparation was successful
    if ($stmt === FALSE) {
        error_log("Failed to prepare statement in get_stories: " . $conn->error);
        return [];
    }

    // Bind the parameter: 'i' for integer type, $current_user_id is the value
    $stmt->bind_param("i", $current_user_id);

    // Execute the statement
    $execute_success = $stmt->execute();

    // Check if execution was successful
    if (!$execute_success) {
        error_log("Failed to execute statement in get_stories: " . $stmt->error);
        $stmt->close();
        return [];
    }

    // Get the result set
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $userId = $row['userid'];
            $storyID = $row['story_ID'];

            // Initialize user data if not already present
            if (!isset($usersData[$userId])) {
                $usersData[$userId] = [
                    'id' => $userId,
                    'user_name' => $row['user_name'],
                    'user_avatar' => "../assests/images/post_images/" . ($row['user_avatar'] ? $row['user_avatar'] : 'profileimage.png'),
                    // Make sure $_SESSION['userid'] is set when calling is_story_viewed
                    'has_new' => isset($_SESSION['userid']) ? is_story_viewed($storyID, $_SESSION['userid']) : true,
                    'segments' => []
                ];
            }

            // Create a segment for each story
            $usersData[$userId]['segments'][] = [
                'type' => 'image',
                'content' => "../assests/images/story_images/" . $row['content_src'],
                'duration' => 5000,
                'story_ID' => $row['story_ID'],
                'time_ago' => formatTimeAgo($row['time'])
            ];
        }

        // Convert the associative array to a numerically indexed array
        $groupedStories = array_values($usersData);
    }

    // Close the statement
    $stmt->close();

    return $groupedStories;
}



function get_current_user_stories()
{
    global $conn;

    if (!isset($_SESSION['userid'])) {
        // Corrected error log message
        error_log("Attempted to call get_current_user_stories() without a logged-in user.");
        return null; // Return null if not logged in to indicate no data
    }

    $current_user_id = $_SESSION['userid'];

    $sql = "
    SELECT
        u.userid,
        u.name AS user_name,
        pro.ProfileimagePath AS user_avatar,
        s.story_ID,
        s.time,
        s.imagePath AS content_src
    FROM
        users u
    INNER JOIN
        story s ON u.userid = s.userid
    LEFT JOIN
        profile pro ON u.userid = pro.userid
    WHERE
        s.time >= (NOW() - INTERVAL 1 DAY) AND u.userid = ?
    ORDER BY
        s.time ASC; -- Order by time ascending for chronological display/playback
    ";

    $stmt = $conn->prepare($sql);

    if ($stmt === FALSE) {
        // Corrected error log message
        error_log("Failed to prepare statement in get_current_user_stories: " . $conn->error);
        return null;
    }

    $stmt->bind_param("i", $current_user_id);
    $execute_success = $stmt->execute();

    if (!$execute_success) {
        // Corrected error log message
        error_log("Failed to execute statement in get_current_user_stories: " . $stmt->error);
        $stmt->close();
        return null;
    }

    $result = $stmt->get_result();

    $user_stories_data = null; // Initialize as null, will become an array if stories are found

    if ($result->num_rows > 0) {
        // Process the first row to get user details (since it's only one user)
        $first_row_data = $result->fetch_assoc();
        $user_stories_data = [
            'id' => $first_row_data['userid'],
            'user_name' => $first_row_data['user_name'],
            'user_avatar' => "../assests/images/post_images/" . ($first_row_data['user_avatar'] ? $first_row_data['user_avatar'] : 'default_profile.jpg'),

            'has_new' => false,
            'segments' => []
        ];
        $result->data_seek(0);

        while ($row = $result->fetch_assoc()) {
            $user_stories_data['segments'][] = [
                'type' => 'image',
                'content' => "../assests/images/story_images/" . $row['content_src'], // Correct path
                'duration' => 5000,
                'story_ID' => $row['story_ID'],
                'time_ago' => formatTimeAgo($row['time'])
            ];
        }
    }

    $stmt->close();

    return $user_stories_data; // Returns null if no stories, or the user's data array
}


function is_story_viewed($storyID, $userID)
{
    global $conn; // Assuming $conn is your mysqli connection object

    // Select 1 as we only care about existence
    $sql = "SELECT 1 FROM story_views WHERE story_id = ? AND viewer_id = ?";
    $stmt = $conn->prepare($sql);

    // Handle prepare errors
    if (!$stmt) {
        error_log("Failed to prepare statement in is_story_viewed_reversed: " . $conn->error);
        return true; // If prepare fails, perhaps assume it's NOT viewed (or handle as appropriate for your app)
    }

    // Bind parameters (assuming story_id is integer, viewer_id is integer)
    // Adjusted from "is" to "ii" if viewer_id (from $_SESSION['userid']) is an integer.
    // If your viewer_id column is varchar, keep "is".
    $stmt->bind_param("is", $storyID, $userID);

    // Execute the statement
    $execute_success = $stmt->execute();

    // Handle execution errors
    if (!$execute_success) {
        error_log("Failed to execute statement in is_story_viewed_reversed: " . $stmt->error);
        $stmt->close();
        return true; // If execute fails, perhaps assume it's NOT viewed
    }

    // Get the result set
    $result = $stmt->get_result();

    // Check if any rows were returned
    $has_been_viewed = ($result && $result->num_rows > 0);

    // Free resources
    if ($result) {
        $result->free();
    }
    $stmt->close();

    // *** THE REVERSAL HAPPENS HERE ***
    return !$has_been_viewed;
}

function get_story_viewer($storyID)
{
    global $conn;
    $sql = "SELECT
sv.viewer_id AS user_id,
u.name AS user_name,
p.ProfileImagePath AS user_avatar,
sv.reaction_type AS reaction_emoji,
p.online AS status
FROM story_views sv
JOIN users u ON sv.viewer_id = u.userid
LEFT JOIN
profile p ON u.userid = p.userid
WHERE 
sv.story_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Failed to prepare statement: " . $conn->error);

        return [];
    }
    $stmt->bind_param("i", $storyID);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        $views = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $views;
    } else {

        $stmt->close();

        return [];
    }
}
