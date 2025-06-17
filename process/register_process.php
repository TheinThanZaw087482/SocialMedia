<?php
session_start();
include("../includes/db.php");
header("Content-Type: application/json");

$response = ['success' => false, 'error' => []];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userid = trim($_POST["userID"] ?? '');
    $username = trim($_POST["userName"] ?? '');
    $email = trim($_POST["Email"] ?? '');
    $password = $_POST["password"] ?? '';
    $gender = $_POST["gender"] ?? '';
    $batch = $_POST["batch"] ?? '';
    $DOB = $_POST["dob"] ?? '';

    // Basic validations
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['error']['email'] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $response['error']['password'] = "Password must be at least 6 characters.";
    }

    if (haveMail($email)) {
        $response['error']['email'] = "Your email is already registered. Please wait for approval from the administrator or login.";
    }

    if (haveID($userid)) {
        $response['error']['id'] = "Your ID is already registered. Please wait for approval from the administrator.";
    }

    if (empty($response['error'])) {
        $sql = "INSERT INTO users (userid, name, email, password, gender, birthdate, Batch) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sssssss", $userid, $username, $email, $hashedPassword, $gender, $DOB, $batch);

            if ($stmt->execute()) {
                $response['success'] = true;

                // Send notification to admin
                send_noti_to_Admin($userid, "Register", "");
            } else {
                $response['error']['message'] = "Failed to register: " . $stmt->error;
            }
        } else {
            $response['error']['message'] = "Failed to prepare statement: " . $conn->error;
        }
    }
}

// --- Utility Functions ---
function haveMail($mail)
{
    global $conn;
    $sql = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mail);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result && $result->num_rows > 0);
}

function haveID($ID)
{
    global $conn;
    $sql = "SELECT userid FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result && $result->num_rows > 0);
}

function addNotification($senderID, $reciverID, $type, $link = null)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO notifications (senderID, reciverID, type, link, is_read) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $senderID, $reciverID, $type, $link);
    return $stmt->execute();
}

function send_noti_to_Admin($senderID, $type, $link)
{
    $admins = get_users_by_userType("Admin");
    foreach ($admins as $admin) {
        addNotification($senderID, $admin['userid'], $type, $link);
    }
}

function get_users_by_userType($userType)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE userType = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userType);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

echo json_encode($response);
