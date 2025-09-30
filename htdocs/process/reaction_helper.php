<?php
include("../includes/db.php");



function getReactionSummary($postID, $currentUserID, $conn)
{
    $query = "SELECT r.userID, r.type, u.name
              FROM reaction r
              JOIN users u ON r.userID = u.userID
              WHERE r.postID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();

    $userReacted = false;
    $others = [];

    while ($row = $result->fetch_assoc()) {
        if ($row['userID'] == $currentUserID) {
            $userReacted = true;
        } else {
            $others[] = htmlspecialchars($row['name']);
        }
    }

    $countOthers = count($others);
    $summary = '';

    if ($userReacted) {
        if ($countOthers > 0) {
            $summary = "You and $countOthers others";
        } else {
            $summary = "You";
        }
    } else {
        if ($countOthers == 1) {
            $summary = $others[0];
        } elseif ($countOthers == 2) {
            $summary = "{$others[0]} and {$others[1]}";
        } elseif ($countOthers > 2) {
            $summary = "{$others[0]}, {$others[1]} and " . ($countOthers - 2) . " others";
        }
    }

    return $summary;
}
function have_like($postid)
{
    global $conn;

    $postid = intval($postid);
    $sql = "SELECT COUNT(*) AS total FROM reaction WHERE postID = $postid AND type = 'Like'";
    $result = mysqli_query($conn, $sql);

    if (!$result) return; 
    $row = mysqli_fetch_assoc($result);

    // Echo the number of likes
    echo $row['total'];
}







?>