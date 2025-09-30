<?php
session_start();
include("../includes/db.php"); // Ensure this path is correct
header("Content-Type: application/json");

$response = ['success' => false, 'error' => []];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userid = trim($_POST["userID"] ?? '');
    $username = trim($_POST["userName"] ?? '');
    $email = trim($_POST["Email"] ?? '');
    $password = $_POST["password"] ?? '';
    $gender = $_POST["gender"] ?? '';
    $role = $_POST["role"] ?? ''; // Get the role (student/other) from the frontend
    $batch = trim($_POST["batch"] ?? ''); // Get the batch (bt11, admin, teacher, etc.)

    $userType = ''; // Initialize userType

    // --- Determine userType based on the 'role' and 'batch' from the frontend
    if ($role === 'student') {
        $userType = 'student';
    } elseif ($role === 'other') {
        if ($batch === 'admin') {
            $userType = 'admin';
        } elseif ($batch === 'teacher') {
            $userType = 'teacher';
        } else {
            // Fallback or error if 'other' role is selected but batch is not admin/teacher
            $response['error']['batch'] = "Please select a valid user type (Admin/Teacher) for 'Other' role.";
        }
    } else {
        $response['error']['role'] = "Invalid role selected.";
    }

    // --- Basic validations ---
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['error']['email'] = "Invalid email format.";
    }
    if (strlen($password) < 6) {
        $response['error']['password'] = "Password must be at least 6 characters.";
    }
    if (empty($gender)) {
        $response['error']['gender'] = "Please select your gender.";
    }
    if (empty($_POST["dob"])) { // Check raw POST data for DOB
        $response['error']['dob'] = "Please enter your date of birth.";
    }
    $DOB = $_POST["dob"] ?? ''; // Assign DOB after validation check

    // Check for existing email and ID only if initial validations pass
    if (empty($response['error'])) {
        if (haveMail($email)) {
            $response['error']['email'] = "Your email is already registered. Please wait for approval from the administrator or login.";
        }
        if (haveID($userid)) {
            $response['error']['id'] = "Your ID is already registered. Please wait for approval from the administrator.";
        }
    }

    if (empty($response['error'])) {
        $conn->begin_transaction();

        try {
            // Prepare SQL based on userType and batch
            $sql_user = "";
            if ($userType === 'admin' || $userType === 'teacher') {
                // If admin or teacher, store 'admin'/'teacher' as userType and 'ALL' as batch, or their specific batch if different
                $sql_user = "INSERT INTO users (userid, name, email, password, gender, userType, Batch) VALUES (?, ?, ?, ?, ?, ?, ?)";
            } else { // Student
                $sql_user = "INSERT INTO users (userid, name, email, password, gender, Batch, userType) VALUES (?, ?, ?, ?, ?, ?, ?)";
            }

            $stmt_user = $conn->prepare($sql_user);

            if (!$stmt_user) {
                throw new Exception("Failed to prepare user statement: " . $conn->error);
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Bind parameters based on the SQL query
            if ($userType === 'admin' || $userType === 'teacher') {
                $stmt_user->bind_param("sssssss", $userid, $username, $email, $hashedPassword, $gender, $userType, $batch);
            } else { // Student
                $stmt_user->bind_param("sssssss", $userid, $username, $email, $hashedPassword, $gender, $batch, $userType);
            }


            if (!$stmt_user->execute()) {
                throw new Exception("Failed to register user: " . $stmt_user->error);
            }
            $stmt_user->close(); // Close the user statement

            $sql_profile = "INSERT INTO `profile` (`ProfileimagePath`, `userid`, `coverPhoto`, `Address`, `login_date`, `online`, `nickname`, `bio`)
                            VALUES ('profileimage.png', ?, '1748073185_68317ae194dd8.jpg', NULL, NOW(), '0', NULL, NULL)";
            $stmt_profile = $conn->prepare($sql_profile);

            if (!$stmt_profile) {
                throw new Exception("Failed to prepare profile statement: " . $conn->error);
            }

            $stmt_profile->bind_param("s", $userid);

            if (!$stmt_profile->execute()) {
                throw new Exception("Failed to create user profile: " . $stmt_profile->error);
            }

            $stmt_profile->close(); // Close the profile statement

            // If both inserts were successful, commit the transaction
            $conn->commit();
            $response['success'] = true;

            // Send notification to admin (only if both inserts succeeded)
            // Ensure send_noti_to_Admin can handle null for link if needed
            send_noti_to_Admin($userid, "Register", null); // Pass null if you want a NULL in DB for link

        } catch (Exception $e) {
            // Rollback the transaction on any error
            $conn->rollback();
            error_log("Registration Transaction Error: " . $e->getMessage());
            $response['error']['message'] = "Registration failed. Please try again later. " . $e->getMessage(); // Added message for debugging
        }
    }
}

// Ensure these functions are defined or included.
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
    // Changed "ssss" to "sssi" if link can be NULL or an integer ID, adjust as per your DB column type
    // If link is always string, "ssss" is fine. If it can be NULL, you might need to handle it.
    // For simplicity, assuming link can be string or NULL, and DB column is VARCHAR.
    $stmt = $conn->prepare("INSERT INTO notifications (senderID, reciverID, type, link, is_read) VALUES (?, ?, ?, ?, 0)");
    // Use 's' for link if it's a string, even an empty one. If you want a true SQL NULL, you might need to bind_param differently or pass null directly.
    $stmt->bind_param("ssss", $senderID, $reciverID, $type, $link);
    return $stmt->execute();
}

function send_noti_to_Admin($senderID, $type, $link)
{
    global $conn; // Added global $conn if get_users_by_userType doesn't pass it.
    $admins = get_users_by_userType("Admin");
    foreach ($admins as $admin) {
        addNotification($senderID, $admin['userid'], $type, $link);
    }
}

function get_users_by_userType($userType)
{
    global $conn;
    $sql = "SELECT userid FROM users WHERE userType = ?"; // Only fetch userid for notification
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userType);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

echo json_encode($response);
?>