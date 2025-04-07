<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

define('WEBHOOK_VERIFY_TOKEN', 'Happy');
define('GRAPH_API_TOKEN', 'EAA5HzO0nYi0BOZBaf6zlgEBEJCcT5lZAUOiAZCnobKiUkOeDdnLjxcyGNSlivoMsvSv9ZBY73gwSIyOx4rcOZAZBo0Hwtj0JbotvN7CxfyOsOZBZBnEZBg8KTtfGoBl1M0qnkH4e1sj83qzZCkVu3h3U1jqaysQvPXxF2OFZBP4mSWBYmg3PlJ9tzSZBVz07tFnr7yOrZCgZDZD');

class WEBHOOKMODEL extends APIRESPONSE
{
    // Initiate db connection
    private function dbConnect()
    {
        $conn = new DBCONNECTION();
        return $conn->connect();
    }

    // Webhook Verification (GET request)
    public function verifyWebhook()
    {
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

    // Process incoming POST messages
    public function processWebhookData()
    {
        // Parse the incoming data
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        // Log incoming webhook data for debugging
        file_put_contents("webhook_log.txt", date("Y-m-d H:i:s") . " - " . print_r($data, true) . "\n", FILE_APPEND);
        file_put_contents("webhook_data.json", json_encode($data, JSON_PRETTY_PRINT));
        
        if (isset($data['entry'])) {
            foreach ($data['entry'] as $entry) {
                foreach ($entry['changes'] as $change) {
                    if (isset($change['value']['messages'])) {
                        foreach ($change['value']['messages'] as $message) {
                            // Process each message
                            $this->processMessage($change['value']['metadata']['phone_number_id'], $message);
                        }
                    }
                }
            }
        }

        // Send 200 response to Facebook
        http_response_code(200);
        return json_encode(["status" => "Webhook received"]);


        // $method = $_SERVER["REQUEST_METHOD"];
        // // Read the JSON payload from Facebook
        // $rawData = file_get_contents("php://input");
        // $data = json_decode($rawData, true);

        // // Log the request data
        // if ($method === "POST") {
        //     $rawData = file_get_contents("php://input");
        //     $data = json_decode($rawData, true);

        //     // Save the webhook data in a JSON file (or use a database)
        //     file_put_contents("webhook_data.json", json_encode($data, JSON_PRETTY_PRINT));

        //     http_response_code(200);
        //     echo json_encode(["status" => "Webhook received", "data" => $data]);
        //     exit;
        // }


        // // Respond with a 200 OK status
        // http_response_code(200);
        // echo json_encode(["status" => "Webhook received"]);
        // exit;
    }

    // Process individual message
    private function processMessage($businessPhoneNumberId, $message)
    {
        $sender = $message['from'];  // Sender's phone number
        $messageText = $message['text']['body'] ?? 'No text';
        $messageId = $message['id'];

        // Store the incoming message in the database
        $this->storeMessageInDB($sender, $messageText);

        // Send an auto-reply message
        $replyText = "Echo: " . $messageText;
        $this->sendWhatsAppMessage($businessPhoneNumberId, $sender, $replyText, $messageId);

        // Mark the message as read
        $this->markMessageAsRead($businessPhoneNumberId, $messageId);
    }

    // Store message in database
    private function storeMessageInDB($sender, $messageText)
    {
        $db = $this->dbConnect();
        $stmt = $db->prepare("INSERT INTO whatsapp_messages (sender, message, received_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $sender, $messageText);
        $stmt->execute();
        $stmt->close();
    }

    // Send WhatsApp message
    private function sendWhatsAppMessage($businessPhoneNumberId, $recipient, $text, $contextMessageId)
    {
        $url = "https://graph.facebook.com/v18.0/{$businessPhoneNumberId}/messages";
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipient,
            "text" => ["body" => $text],
            "context" => ["message_id" => $contextMessageId]
        ];

        $this->makeCurlRequest($url, $data);
    }

    // Mark message as read
    private function markMessageAsRead($businessPhoneNumberId, $messageId)
    {
        $url = "https://graph.facebook.com/v18.0/{$businessPhoneNumberId}/messages";
        $data = [
            "messaging_product" => "whatsapp",
            "status" => "read",
            "message_id" => $messageId
        ];

        $this->makeCurlRequest($url, $data);
    }

    // Helper function to make cURL request
    private function makeCurlRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . GRAPH_API_TOKEN,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
?>
