<?php
session_start();
include("db.php");
include("../process/reaction_helper.php");

if (isset($_GET['post_id']) && isset($_SESSION['userid'])) {
    $postID = intval($_GET['post_id']);
    $userID = $_SESSION['userid'];

    echo getReactionSummary($postID, $userID, $conn);
} else {
    echo "Invalid request.";
}
