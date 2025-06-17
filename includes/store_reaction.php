<?php
session_start();
include("db.php");
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['post_id']) || !isset($input['reaction'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$postID = $input['post_id'];
$reaction = $input['reaction'];
$userid = $_SESSION["userid"];

// Check if reaction already exists
$checkSql = "SELECT * FROM reaction WHERE userID = '$userid' AND postID = $postID";
$result = mysqli_query($conn, $checkSql);

if (mysqli_num_rows($result) > 0) {
    // Reaction exists, update it
    $updateSql = "UPDATE reaction SET type = '$reaction' WHERE userID = '$userid' AND postID = $postID";
    mysqli_query($conn, $updateSql);
} else {
    // Insert new reaction
    $insertSql = "INSERT INTO reaction(userID, postID, type) VALUES('$userid', $postID, '$reaction')";
    mysqli_query($conn, $insertSql);
}


echo json_encode([
    "status" => "success",
    "post_id" => $postID,
    "reaction" => $reaction
]);


