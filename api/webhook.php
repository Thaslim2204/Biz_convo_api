<?php

// Set your verification token
define('WEBHOOK_VERIFY_TOKEN', 'Happy');

// Get the request method
$method = $_SERVER["REQUEST_METHOD"];

// print_r($_GET);
// ✅ New API to allow React to fetch webhook data
if ($method === "GET" && isset($_GET['fetch'])) {
    if (file_exists("webhook_data.json")) {
        header('Content-Type: application/json');
        echo file_get_contents("webhook_data.json");
        exit;
    } else {
        echo json_encode(["error" => "No data found"]);
        exit;
    }
}

//  Handle GET request for Facebook webhook verification
if ($method === "GET") {
    // Log the request
    file_put_contents("webhook_log.txt", "Verification Request: " . print_r($_GET, true) . "\n", FILE_APPEND);

    // Extract query parameters
    $mode = $_GET['hub_mode'] ?? '';
    $token = $_GET['hub_verify_token'] ?? '';
    $challenge = $_GET['hub_challenge'] ?? '';

    if ($mode === "subscribe" && $token === WEBHOOK_VERIFY_TOKEN) {
        //  Webhook verified
        file_put_contents("webhook_log.txt", " Webhook verified!\n", FILE_APPEND);
        echo $challenge;
        http_response_code(200);
        exit;
    } else {
        //  Verification failed
        file_put_contents("webhook_log.txt", " Verification failed!\n", FILE_APPEND);
        http_response_code(403);
        exit;
    }
}

//  Handle POST request for webhook events
if ($method === "POST") {
    // Read the JSON payload from Facebook
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    // Log the request data
    if ($method === "POST") {
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        // Save the webhook data in a JSON file (or use a database)
        file_put_contents("webhook_data.json", json_encode($data, JSON_PRETTY_PRINT));

        http_response_code(200);
        echo json_encode(["status" => "Webhook received", "data" => $data]);
        exit;
    }

    
    // Respond with a 200 OK status
    http_response_code(200);
    echo json_encode(["status" => "Webhook received"]);
    exit;
}


// Return 404 for unsupported requests
http_response_code(404);
echo "Invalid request";
exit;
