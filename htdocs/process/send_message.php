<?php
// Add this at the very top for general logging
error_log("send_message.php: Script started. Current time: " . date('Y-m-d H:i:s'));

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
const OPENROUTER_MODEL = 'openai/chatgpt-4o-latest';

// Optional: Your site URL and title for OpenRouter rankings.
const YOUR_SITE_URL = 'https://your-messenger-app.com'; // IMPORTANT: Change this to your live site URL
const YOUR_SITE_NAME = 'My Awesome Messenger'; // IMPORTANT: Change this to your app's name

// Check if the database connection is successful
if (!$conn) {
    error_log("send_message.php: Database connection failed: " . mysqli_connect_error());
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed. Please try again later.']);
    exit();
}
error_log("send_message.php: Database connected.");

$input = json_decode(file_get_contents('php://input'), true);

$receiver_id = $input['receiver_id'] ?? null;
$sender_id = $_SESSION['userid'] ?? null; // Securely get sender_id from session
$message = $input['message'] ?? null; // Optional: for text messages or captions
$message_type = $input['message_type'] ?? 'text'; // Default to 'text' if not provided
$file_url = $input['file_url'] ?? null; // For image/video/file attachments

// Validate inputs
// If message_type is 'text', 'message' is mandatory.
// If message_type is 'image', 'video', or 'file', 'file_url' is mandatory.
if (empty($receiver_id) || empty($sender_id)) {
    error_log("send_message.php: Missing receiver_id or sender_id. Receiver: $receiver_id, Sender: $sender_id");
    echo json_encode(['status' => 'error', 'message' => 'Missing required chat parameters or unauthorized sender.']);
    exit();
}

if ($message_type === 'text' && empty($message)) {
    error_log("send_message.php: Message is empty for text type.");
    echo json_encode(['status' => 'error', 'message' => 'Text message cannot be empty.']);
    exit();
} elseif (in_array($message_type, ['image', 'video', 'file']) && empty($file_url)) {
    error_log("send_message.php: File URL is empty for media/file type ($message_type).");
    echo json_encode(['status' => 'error', 'message' => 'File attachment is missing.']);
    exit();
}

error_log("send_message.php: Inputs validated. Type: $message_type, File URL: $file_url, Message: $message");

// --- Function to interact with OpenRouter.ai API ---
function getOpenRouterResponse($prompt) {
    // ... (Your existing getOpenRouterResponse function remains the same) ...
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

    $ch = curl_init($url);

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
error_log("send_message.php: Preparing to save user message (Type: $message_type).");

// Modified SQL query to include message_type and file_url
$sql_user_message = "INSERT INTO messages (sender_id, receiver_id, message, message_type, file_url, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt_user_message = $conn->prepare($sql_user_message);

if ($stmt_user_message === false) {
    error_log("send_message.php: Failed to prepare user message statement: " . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to process message (DB prepare error).']);
    exit();
}

// Bind parameters: sender_id (string), receiver_id (string), message (string), message_type (string), file_url (string)
// Note: Even if message or file_url are null, bind_param expects 's' and will treat null as empty string, which is fine for VARCHAR.
$stmt_user_message->bind_param("sssss", $sender_id, $receiver_id, $message, $message_type, $file_url);

if (!$stmt_user_message->execute()) {
    error_log("send_message.php: Failed to save user message: " . $stmt_user_message->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to save your message.']);
    $stmt_user_message->close();
    $conn->close();
    exit();
}
$stmt_user_message->close();
error_log("send_message.php: User message (Type: $message_type) saved successfully.");

// --- Chatbot Interaction Logic (if message is for the chatbot) ---
// Chatbot currently only responds to 'text' messages for simplicity.
// You could extend this to process image content if your AI model supports it (e.g., multimodal models).
if (strval($receiver_id) === strval(CHATBOT_ID) && $message_type === 'text') {
    error_log("send_message.php: Initiating chatbot interaction for text message.");
    $chatbot_response_text = getOpenRouterResponse($message);
    error_log("send_message.php: Chatbot response received.");

    // Prepare SQL to insert the chatbot's response
    $sql_chatbot_response = "INSERT INTO messages (sender_id, receiver_id, message, message_type, file_url, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt_chatbot_response = $conn->prepare($sql_chatbot_response);

    if ($stmt_chatbot_response === false) {
        error_log("send_message.php: Failed to prepare chatbot response statement: " . $conn->error);
    } else {
        $chatbotSenderId = CHATBOT_ID;
        $chatbotMessageType = 'text'; // Chatbot always sends text for now
        $chatbotFileUrl = null; // Chatbot doesn't send files currently

        // Bind parameters for the chatbot's response
        $stmt_chatbot_response->bind_param("sssss", $chatbotSenderId, $sender_id, $chatbot_response_text, $chatbotMessageType, $chatbotFileUrl);
        if (!$stmt_chatbot_response->execute()) {
            error_log("send_message.php: Failed to insert chatbot response: " . $stmt_chatbot_response->error);
        }
        $stmt_chatbot_response->close();
    }
    error_log("send_message.php: Chatbot response (attempted) saved.");
} else if (strval($receiver_id) === strval(CHATBOT_ID) && $message_type !== 'text') {
    // Optionally, send a message back to the user if they send a file to the chatbot
    error_log("send_message.php: Chatbot received non-text message. No response generated.");
    $chatbot_response_text = "Sorry, I can only process text messages at the moment. I'm still learning about images and files!";
    $sql_chatbot_response = "INSERT INTO messages (sender_id, receiver_id, message, message_type, file_url, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt_chatbot_response = $conn->prepare($sql_chatbot_response);

    if ($stmt_chatbot_response === false) {
        error_log("send_message.php: Failed to prepare chatbot non-text response statement: " . $conn->error);
    } else {
        $chatbotSenderId = CHATBOT_ID;
        $chatbotMessageType = 'text';
        $chatbotFileUrl = null;
        $stmt_chatbot_response->bind_param("sssss", $chatbotSenderId, $sender_id, $chatbot_response_text, $chatbotMessageType, $chatbotFileUrl);
        if (!$stmt_chatbot_response->execute()) {
            error_log("send_message.php: Failed to insert chatbot non-text response: " . $stmt_chatbot_response->error);
        }
        $stmt_chatbot_response->close();
    }
}


// --- Final Success Response ---
error_log("send_message.php: Script finished successfully.");
echo json_encode(['status' => 'success', 'message' => 'Message processed successfully.']);

// Close the main database connection
$conn->close();

?>