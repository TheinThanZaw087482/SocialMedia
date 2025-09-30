<?php
session_start();
include("../includes/db.php");
header("Content-Type: application/json");

$response = ['success' => false, 'error' => []];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // CHANGE THESE LINES:
    $mail_or_ID = trim($_POST["identifier"] ?? ''); // Use 'identifier'
    $password = $_POST["password"] ?? '';          // Use 'password'
    $mail = null;
    $ID = null;

    if (empty($mail_or_ID)) {
        $response['error']['email'] = "Email or ID is required!";
    }

    if (empty($password)) {
        $response['error']['password'] = "Password is required.";
    }

    // Determine if it's email or ID
    if (!filter_var($mail_or_ID, FILTER_VALIDATE_EMAIL)) {
        $ID = $mail_or_ID;
    } else {
        $mail = $mail_or_ID;
    }

    if (empty($response['error'])) {
        if ($mail === null) {
            $sql = "SELECT userid, name, email, password,approve FROM users WHERE userid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $ID);
        } else {
            $sql = "SELECT userid, name, email, password,approve FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $mail);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                if ((int)$user['approve'] === 0) {
                    $response['error']['approve'] = "Your account has not been approved yet. Please contact the administrator or wait for approval.";
                } else {
                    $_SESSION['userid'] = $user['userid'];
                    $_SESSION['username'] = $user['name'];
                    $response['success'] = true;
                    http_response_code(200);
                }
            } else {
                $response['error']['password'] = "Invalid password.";
                http_response_code(401);
            }
        } else {
            $response['error']['email'] = "No user found with that email or ID.";
            http_response_code(404);
        }

        $stmt->close();
    }
    $conn->close();
} else {
    $response['message'] = "Invalid request method.";
    http_response_code(405); // Method Not Allowed
}

echo json_encode($response);
exit;

?>