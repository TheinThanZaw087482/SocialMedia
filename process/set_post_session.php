<?php
session_start();
if (isset($_POST['post_id'])) {
    $_SESSION['postID'] = $_POST['post_id'];
}
?>
