<?php
session_start();
include("../includes/db.php"); // Ensure this path is correct

header("Content-Type: application/json");

$response = ['success' => false, 'error' => []];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mail_or_ID = trim($_POST["signin_email"] ?? '');
    $password = $_POST["signin_password"] ?? '';
    $mail = null;
    $ID = null;

    // --- Input Validation ---
    if (empty($mail_or_ID)) {
        $response['error']['email'] = "Email or ID is required.";
    }

    if (empty($password)) {
        $response['error']['password'] = "Password is required.";
    }

    // Determine if it's email or ID
    if (!empty($mail_or_ID)) { // Only proceed if mail_or_ID is not empty
        if (filter_var($mail_or_ID, FILTER_VALIDATE_EMAIL)) {
            $mail = $mail_or_ID;
        } else {
            $ID = $mail_or_ID;
        }
    }

    // --- Database Query and Authentication ---
    if (empty($response['error'])) {
        $sql = "";
        $stmt = null;

        if ($mail === null) { // It's an ID
            $sql = "SELECT userid, name, email, password, approve FROM users WHERE userid = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $ID);
            }
        } else { // It's an email
            $sql = "SELECT userid, name, email, password, approve FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $mail);
            }
        }

        if (!$stmt) {
            // Handle prepare error
            $response['message'] = "Database query preparation failed.";
            http_response_code(500); // Internal Server Error
        } else {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $user = $result->fetch_assoc()) {
                if (password_verify($password, $user['password'])) {
                    if ((int)$user['approve'] === 0) {
                        $response['error']['approve'] = "Your account has not been approved yet. Please contact the administrator or wait for approval.";
                        http_response_code(403); // Forbidden
                    } else {
                        $_SESSION['userid'] = $user['userid'];
                        $_SESSION['username'] = $user['name'];
                        $response['success'] = true;
                        http_response_code(200); // OK
                    }
                } else {
                    $response['error']['password'] = "Invalid password.";
                    http_response_code(401); // Unauthorized
                }
            } else {
                $response['error']['email'] = "No user found with that email or ID.";
                http_response_code(404); // Not Found
            }
            $stmt->close();
        }
    } else {
        // If there were validation errors before database interaction
        http_response_code(400); // Bad Request
    }

    $conn->close();
} else {
    // Handle non-POST requests
    $response['message'] = "Invalid request method.";
    http_response_code(405); // Method Not Allowed
}

echo json_encode($response);
exit;
?>