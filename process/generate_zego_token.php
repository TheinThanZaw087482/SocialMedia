<?php
require_once("../zego_sdk/auto_loader.php");

use ZEGO\ZegoServerAssistant;

// ✅ Set your actual values here
$appId = 382312408; // Your App ID (a number)
$userId = "user1";   // The user you're generating token for
$secret = 'a4f705b95a44776f9ede9c37ec8e5b6b'; // 32 bytes (not hex!) raw string
$expireTime = 3600; // Valid for 1 hour
$payload = ""; // Optional JSON string

// ✅ If your secret is in HEX (64 characters), convert it to binary
// $secret = hex2bin('your_64_char_hex_string');

$tokenObj = ZegoServerAssistant::generateToken04($appId, $userId, $secret, $expireTime, $payload);

// ✅ Output the result
if ($tokenObj->token) {
    echo "ZEGOCLOUD Token: " . $tokenObj->token;
} else {
    echo "❌ Error [{$tokenObj->code}]: {$tokenObj->message}";
}
