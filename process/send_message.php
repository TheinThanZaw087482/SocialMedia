<?php
// Add this at the very top for general logging
error_log("send_message.php: Script started.");

session_start();
header('Content-Type: application/json'); // Tell the client we're sending JSON

include("../includes/db.php"); // Ensure this path is correct

// --- IMPORTANT: CHATBOT CONFIGURATION ---
// Define your chatbot's user ID here. Ensure it matches the ID in your 'users' table.
// This must be a string if your user IDs are VARCHAR in the database.
const CHATBOT_ID = '99999'; // Example: Replace with your actual chatbot's user ID (e.g., 'openrouter_deepseek_bot')

// Your OpenRouter.ai API Key.
// !!! IMPORTANT !!! In a production environment, NEVER hardcode your API key directly.
// Use environment variables (e.g., getenv('OPENROUTER_API_KEY')) or a secure config file outside the web root.
const OPENROUTER_API_KEY = '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'; // <<< REPLACE THIS WITH YOUR ACTUAL OPENROUTER API KEY

const OPENROUTER_API_ENDPOINT = 'https://openrouter.ai/api/v1/chat/completions';
// CORRECTED MODEL NAME: Use the exact model string provided by OpenRouter
const OPENROUTER_MODEL = 'deepseek/deepseek-r1-0528:free'; 

// Optional: Your site URL and title for OpenRouter rankings.
// Replace with your actual site URL and name.
const YOUR_SITE_URL = 'https://your-messenger-app.com'; // IMPORTANT: Change this to your live site URL
const YOUR_SITE_NAME = 'My Awesome Messenger'; // IMPORTANT: Change this to your app's name

// Check if the database connection is successful
if (!$conn) {
    error_log("send_message.php: Database connection failed: " . mysqli_connect_error());
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed. Please try again later.']);
    exit();
}
error_log("send_message.php: Database connected."); // Log after successful connection

$input = json_decode(file_get_contents('php://input'), true);

$receiver_id = $input['receiver_id'] ?? null;
$sender_id = $_SESSION['userid'] ?? null;
$message = $input['message'] ?? null;

if (empty($receiver_id) || empty($sender_id) || empty($message)) {
    error_log("send_message.php: Missing message parameters. Receiver: $receiver_id, Sender: $sender_id, Message: $message");
    echo json_encode(['status' => 'error', 'message' => 'Missing required message parameters or unauthorized sender.']);
    exit();
}
error_log("send_message.php: Inputs validated."); // Log after validation

// --- Function to interact with OpenRouter.ai API ---
function getOpenRouterResponse($prompt) {
    $api_key = OPENROUTER_API_KEY;
    $url = OPENROUTER_API_ENDPOINT;

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
        'HTTP-Referer: ' . YOUR_SITE_URL,
        'X-Title: ' . YOUR_SITE_NAME
    ];

    $data = [
        'model' => OPENROUTER_MODEL,
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 500,
        'temperature' => 0.7
    ];

    $ch = curl_init($url); // Initialize $ch here

    // IMPORTANT: Check if curl_init was successful
    if ($ch === false) {
        error_log("cURL Error: Could not initialize cURL for URL: " . $url);
        return "Oops! I couldn't connect to the AI service. (cURL Init Error)";
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        error_log("cURL Error for OpenRouter API: " . $curl_error);
        return "Oops! I seem to be having technical difficulties right now. (cURL Error)";
    }

    if ($http_code !== 200) {
        error_log("OpenRouter API Error: HTTP Code " . $http_code . " Response: " . $response);
        $error_details = json_decode($response, true);
        if (isset($error_details['error']['message'])) {
            return "Sorry, OpenRouter ran into an issue: " . $error_details['error']['message'];
        }
        return "Sorry, I'm currently unavailable. (API Error Code: " . $http_code . ")";
    }

    $decoded_response = json_decode($response, true);

    if (isset($decoded_response['choices'][0]['message']['content'])) {
        return $decoded_response['choices'][0]['message']['content'];
    }

    error_log("OpenRouter API Error: Unexpected response structure or empty content: " . $response);
    return "I couldn't generate a response. Please try again.";
}

// --- Main Logic: Save User Message ---
error_log("send_message.php: Preparing to save user message."); // Log before user message save
$sql_user_message = "INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())";
$stmt_user_message = $conn->prepare($sql_user_message);

if ($stmt_user_message === false) {
    error_log("send_message.php: Failed to prepare user message statement: " . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to process message (DB prepare error).']);
    exit();
}

$stmt_user_message->bind_param("sss", $sender_id, $receiver_id, $message);

if (!$stmt_user_message->execute()) {
    error_log("send_message.php: Failed to save user message: " . $stmt_user_message->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to save your message.']);
    $stmt_user_message->close();
    $conn->close();
    exit();
}
$stmt_user_message->close();
error_log("send_message.php: User message saved successfully."); // Log after user message save

// --- Chatbot Interaction Logic (if message is for the chatbot) ---
// Compare IDs as strings to avoid type juggling issues
if (strval($receiver_id) === strval(CHATBOT_ID)) {
    error_log("send_message.php: Initiating chatbot interaction."); // Log before chatbot call
    $chatbot_response_text = getOpenRouterResponse($message);
    error_log("send_message.php: Chatbot response received."); // Log after chatbot call

    // Prepare SQL to insert the chatbot's response
    $sql_chatbot_response = "INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt_chatbot_response = $conn->prepare($sql_chatbot_response);

    if ($stmt_chatbot_response === false) {
        error_log("send_message.php: Failed to prepare chatbot response statement: " . $conn->error);
        // Continue to send success for user message, but log the chatbot response failure
    } else {
        // *** FIX START ***
        // Assign CHATBOT_ID to a temporary variable so it can be passed by reference
        $chatbotSenderId = CHATBOT_ID; // <--- ADD THIS LINE
        // *** FIX END ***

        // Bind parameters for the chatbot's response.
        // Sender for this message is the chatbot (CHATBOT_ID), receiver is the original sender ($sender_id).
        // Use "sss" as all IDs and message are strings.
        // Change CHATBOT_ID to $chatbotSenderId here:
        $stmt_chatbot_response->bind_param("sss", $chatbotSenderId, $sender_id, $chatbot_response_text); // <--- MODIFIED LINE
        if (!$stmt_chatbot_response->execute()) {
            error_log("send_message.php: Failed to insert chatbot response: " . $stmt_chatbot_response->error);
        }
        $stmt_chatbot_response->close();
    }
    error_log("send_message.php: Chatbot response (attempted) saved."); // Log after chatbot response save attempt
}

// --- Final Success Response ---
// If we reach here, the user's message was saved successfully.
// If it was for the chatbot, we also attempted to get and save its response.
error_log("send_message.php: Script finished successfully."); // Log at the end
echo json_encode(['status' => 'success', 'message' => 'Message processed successfully.']);

// Close the main database connection
$conn->close();

