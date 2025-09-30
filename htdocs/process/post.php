<?php
include("../includes/db.php");
include("../includes/noti_functions.php");
function getAllpost($viewerID)
{
    global $conn;

    $sql = "SELECT 
                p.postID,
                p.postdate,
                p.privacy,
                p.content,
                p.userID 
            FROM post p
            JOIN users poster ON p.userID = poster.userID
            JOIN users viewer ON viewer.userID = ?
            WHERE 
                (viewer.userType = 'admin' AND p.privacy != 'only_me')
                OR (p.privacy = 'public')
                OR (p.privacy = 'batch' AND poster.Batch = viewer.Batch) AND p.is_ban = 0
            ORDER BY p.postdate DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $viewerID);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // ✅ fetch the result set
    $result = $stmt->get_result();

    return $result;
}

function get_other_profile_post($viewerID, $posterID)
{
    global $conn;

    $sql = "SELECT 
    p.postID,
    p.postdate,
    p.privacy,
    p.content,
    p.userID 
FROM post p
JOIN users poster ON p.userID = poster.userID
JOIN users viewer ON viewer.userID = ?
WHERE poster.userID = ? AND (
    (viewer.userType = 'admin' AND p.privacy != 'only_me') 
    OR (p.privacy = 'public') 
    OR (p.privacy = 'batch' AND poster.Batch = viewer.Batch)
)
ORDER BY p.postdate DESC;
";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $viewerID, $posterID);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();

    return $result;
}

function get_current_user_post($userid) {
    global $conn;

    $sql = "SELECT * FROM post WHERE userID = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $userid);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    return $stmt->get_result();
}




function getHidePost()
{
    global $conn;

    $sql = "SELECT 
                p.postID,
                p.postdate,
                p.privacy,
                p.content,
                p.userID 
            FROM post p
            JOIN hidden_posts s ON s.postID = p.postID WHERE s.userID = ? ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $_SESSION['userid']);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // ✅ fetch the result set
    $result = $stmt->get_result();

    return $result;

}

function getSavePost()
{
    global $conn;

    $sql = "SELECT 
                p.postID,
                p.postdate,
                p.privacy,
                p.content,
                p.userID 
            FROM post p
            JOIN savepost s ON s.postID = p.postID WHERE s.userID = ? ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $_SESSION['userid']);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // ✅ fetch the result set
    $result = $stmt->get_result();

    return $result;

}




function is_hide_post($postID)
{
    $allpost = getHidePost();
    foreach ($allpost as $post) {
        if ($postID == $post['postID']) {
            return true;
        }
    }
    return false;
}

function getUserNameByID($userid)
{
    global $conn;
    $userid = mysqli_real_escape_string($conn, $userid);

    $query = "SELECT name FROM users WHERE userid = '$userid'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['name'];
    } else {
        return "Unknown User"; // or return null;
    }
}
function getTimeAgo($datetime)
{
    date_default_timezone_set('Asia/Yangon');

    $timestamp = strtotime($datetime);
    $currentTime = time();
    $diff = $currentTime - $timestamp;

    if ($diff < 60) {
        return "Just now";
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . " min" . ($minutes > 1 ? "s" : "") . " ago";
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . "h ago";
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . " day" . ($days > 1 ? "s" : "") . " ago";
    } else {
        return date("M j, Y", $timestamp);
    }
}
function getUserPorfileImageByID($userid)
{
    global $conn;

    $userid = mysqli_real_escape_string($conn, $userid);
    $sql = "SELECT ProfileimagePath FROM profile WHERE userid = '$userid'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return !empty($row['ProfileimagePath']) ? $row['ProfileimagePath'] : 'profileimage.png';
    } else {
        return 'profileimage.png';
    }
}
function getCoverPhotoByID($userid)
{
    global $conn;

    $userid = mysqli_real_escape_string($conn, $userid);
    $sql = "SELECT coverPhoto FROM profile WHERE userid = '$userid'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return !empty($row['coverPhoto']) ? $row['coverPhoto'] : 'porfileimage.png';
    } else {
        return 'porfileimage.png';
    }
}
function newstory()
{
    global $conn;
    if (!isset($_SESSION['userid'])) {
        echo "<scirpt>alert('Please log in to Story') window.location.href='../index.php';</script>";
        exit();
    }
}

function newpost()
{
    global $conn;

    if (!isset($_SESSION['userid'])) {
        echo "<script>alert('Please log in to post'); window.location.href='index.php';</script>";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['post-button'])) {
        $userid = $_SESSION['userid'];
        $content = trim($_POST['post-textarea'] ?? '');
        $privacy = trim($_POST['privacy']);
        date_default_timezone_set('Asia/Yangon');
        $currentTime = date("Y-m-d H:i:s");

        if (empty($content) && empty($_FILES['post_image']['name'][0])) {
            echo "<script>alert('Post content or image is required!'); history.back();</script>";
            exit();
        }

        $safeContent = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        $stmt = $conn->prepare("INSERT INTO post (postdate, userID, content, privacy) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $currentTime, $userid, $safeContent, $privacy);

        if ($stmt->execute()) {
            $postID = $conn->insert_id;

            $imageNames = [];
            if (!empty($_FILES['post_image']['name'][0])) {
                foreach ($_FILES['post_image']['name'] as $index => $filename) {
                    if ($_FILES['post_image']['error'][$index] !== UPLOAD_ERR_OK) continue;

                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $newName = time() . '_' . uniqid() . '.' . $ext;
                    $targetPath = '../assests/images/post_images/' . $newName;

                    if (getimagesize($_FILES['post_image']['tmp_name'][$index])) {
                        if (move_uploaded_file($_FILES['post_image']['tmp_name'][$index], $targetPath)) {
                            $imageNames[] = $newName;
                        }
                    }
                }
            }

            foreach ($imageNames as $img) {
                save_img($postID, $img);
            }

            echo "<script>alert('✅ Successfully posted');window.location.href='../pages/Dashboard.php';</script>";
            $message = "Hello";
            $type = "post";
            $link = $postID;
            if($privacy = "batch"){
                send_noti_to_sameBatch($type, $link, $message);
            }else if($privacy = "public"){
                send_noti_to_Student($type, $link, $message);
                send_noti_to_Teacher($type, $link, $message);
                send_noti_to_Admin($type, $link, $message);

            }
            
        } else {
            echo "<script>alert('❌ Failed to post: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}

function get_post_by_postID($postID)
{
    global $conn;
    $sql = "SELECT * FROM post WHERE postID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }

    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}


function save_img($postID, $image)
{
    global $conn;
    $sql = "INSERT INTO image (postID, img_url) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $postID, $image);
    return $stmt->execute();
}

function get_images_by_post_id($postID)
{
    global $conn;
    $sql = "SELECT img_url FROM image WHERE postID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row['img_url'];
    }
    return $images;
}

// HTML Snippet to Display Images with "+X more"
function display_post_images($postID)
{
    $imgs = get_images_by_post_id($postID);
    $count = count($imgs);
    $maxDisplay = 4;
    $remaining = $count - $maxDisplay;

    echo '<div class="image-grid">';
    foreach ($imgs as $index => $img) {
        if ($index == $maxDisplay - 1 && $remaining > 0) {
            echo '<div class="img-wrapper">';
            echo "<img src='assests/images/post_images/$img' alt=''>";
            echo "<div class='more-overlay'>+{$remaining}</div>";
            echo '</div>';
            break;
        }
        if ($index >= $maxDisplay) break;
        echo '<div class="img-wrapper">';
        echo "<img src='assests/images/post_images/$img' alt=''>";
        echo '</div>';
    }
    echo '</div>';
}



if (isset($_POST['post-button'])) {
    newpost();
}

function already_react($postID, $userid, $conn)
{
    $checkSql = "SELECT `type` FROM reaction WHERE userID = '$userid' AND postID = $postID";
    $result = mysqli_query($conn, $checkSql);
    if (mysqli_num_rows($result) > 0) {
        return $result;
    } else {
        return null;
    }
}


function have_reaction($postid)
{
    global $conn;

    $postid = intval($postid);

    $sql = "SELECT DISTINCT type FROM reaction WHERE postID = $postid LIMIT 3";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $validReactions = ["Like", "Haha", "Love", "Sad", "Wow", "Angry"];

        while ($row = mysqli_fetch_assoc($result)) {
            $type = $row['type'];
            if (in_array($type, $validReactions)) {
                echo '<img src="../assests/images/icon/' . $type . '.png" class="reaction-img w-8 h-8 cursor-pointer inline-block mr-1" />';
            }
        }
    }
}

function getUserIDByPost($postid)
{
    global $conn;
    $postid = intval($postid);

    $stmt = $conn->prepare("SELECT userID FROM post WHERE postID = ?
");
    $stmt->bind_param("i", $postid);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_comment_count($postID)
{
    global $conn;
    $postID = (int)$postID;
    $sql = "SELECT COUNT(*) AS count FROM comment WHERE postID = $postID";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        if ($count > 0) {
            echo $count . " comment" . ($count == 1 ? "" : "s");
        } else {
            echo "comment";
        }
    } else {
        echo "comment";
    }
}
