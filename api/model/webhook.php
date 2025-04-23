<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

define('WEBHOOK_VERIFY_TOKEN', 'Happy');
define('GRAPH_API_TOKEN', 'EAAQDIH4PiBoBO6vBZAClCmyRyYIqGs73jMiC8krCZCorG2ENws1skHh1t5pjkdvXdi9kk0nsD9ljaOTiSQZB8nSj03fM22MyeLdUXmTNlw7dSfZB4Rb4LwDJOOFZBZBZBGN2jcyNkNmytjTqD16V1PL6WCGXS2HbafMcGJep5WuvtdjpC1FOWi1ruVvjiI2BcFZAWwZDZD');

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
        file_put_contents("webhook_log.txt", date("Y-m-d H:i:s") . " - " . json_encode($data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);
        file_put_contents("webhook_data.json", json_encode($data, JSON_PRETTY_PRINT));

        if (isset($data['entry'])) {
            foreach ($data['entry'] as $entry) {

                foreach ($entry['changes'] as $change) {
                    if (isset($change['value']['messages'])) {
                        foreach ($change['value']['messages'] as $message) {
                            // Process each message
                            $this->processMessage($change['value']['metadata']['phone_number_id'], $message, $data);
                        }
                    }
                }
            }
        }
        if (isset($data['entry'])) {
            foreach ($data['entry'] as $entry) {

                foreach ($entry['changes'] as $change) {
                    if (isset($change['value']['statuses'])) {
                        foreach ($change['value']['statuses'] as $message) {
                            // Process each message
                            $this->processMessage($change['value']['metadata']['phone_number_id'], $message, $data);
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
    private function processMessage($businessPhoneNumberId, $message, $data)
    {
        $sender = $message['from'];  // Sender's phone number
        $messageText = $message['text']['body'] ?? 'No text';
        $messageId = $message['id'];
    
        // Store the incoming message in the database
        $this->storeMessageInDB($message, $data);
    
        // ✅ Add this block for Auto-Bot-Reply
        if (!empty($messageText) && $messageText != 'No text') {
            $db = $this->dbConnect();
            
            // 1. Search in cmp_bot_replies
            $safeText = mysqli_real_escape_string($db, $messageText);
            $botQuery = "
                SELECT * FROM cmp_bot_replies 
                WHERE intent LIKE '%$safeText%' 
                AND status = 1 
                ORDER BY id DESC 
                LIMIT 1
            ";
            // print_r($botQuery);exit;
            $result = mysqli_query($db, $botQuery);
    
            if ($row = mysqli_fetch_assoc($result)) {
                // 2. If found, decode the response
                $botResponses = json_decode($row['message_body'], true);
                // print_r(json_decode($row['message_body'], true));exit;
                $replyText = $botResponses[0] ?? "Thank you for your message."; // fallback if empty
    print_r($replyText);exit;
                // 3. Send back WhatsApp Message
                $this->sendWhatsAppMessage($businessPhoneNumberId, $sender, $replyText, $messageId);
            } else {
                // Optional: send a fallback message if no intent matched
                $this->sendWhatsAppMessage($businessPhoneNumberId, $sender, "Sorry, I didn't understand. Please try again.", $messageId);
            }
            
            mysqli_close($db);
        }
    
        // Mark the message as read
        $this->markMessageAsRead($businessPhoneNumberId, $messageId);
    }

    public function storeMessageInDB($message = null, $data)
    {
        $db = $this->dbConnect();

        // Get raw input if $message not passed
        // if (!$message) {
        //     $rawData = file_get_contents("php://input");
        //     $data = json_decode($rawData, true);
        // } else {
        //     $data = $message;
        // }

        $entry = $data['entry'][0]['changes'][0]['value'];

        $created_date = date('Y-m-d H:i:s');
        //  USER message handler
        if (isset($entry['messages'])) {
            $msg = $entry['messages'][0];
            $sender = mysqli_real_escape_string($db, $msg['from']);
            $wam_id = mysqli_real_escape_string($db, $msg['id']);
            $messageType = mysqli_real_escape_string($db, $msg['type']);
            $messageStatus = 'pending';
            $timestamp = $msg['timestamp'];
            $date = new DateTime("@$timestamp");
            $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $message_datetime = $date->format('Y-m-d H:i:s');
            $agent = 'user';
            $agent_contact = $sender;
            $messageText = '';  // caption or body
            $mediaLink = '';    // media id
        
            // Handling based on message type
            if ($messageType == 'text') {
                $messageText = isset($msg['text']['body']) ? mysqli_real_escape_string($db, $msg['text']['body']) : '';
                $mediaLink = '';
            } elseif ($messageType == 'image') {
                $messageText = isset($msg['image']['caption']) ? mysqli_real_escape_string($db, $msg['image']['caption']) : '';
                $mediaLink = mysqli_real_escape_string($db, $msg['image']['id']);
            } elseif ($messageType == 'video') {
                $messageText = isset($msg['video']['caption']) ? mysqli_real_escape_string($db, $msg['video']['caption']) : '';
                $mediaLink = mysqli_real_escape_string($db, $msg['video']['id']);
            } elseif ($messageType == 'document') {
                $messageText = isset($msg['document']['caption']) ? mysqli_real_escape_string($db, $msg['document']['caption']) : '';
                $mediaLink = mysqli_real_escape_string($db, $msg['document']['id']);
            } elseif ($messageType == 'location') {
                $latitude = $msg['location']['latitude'];
                $longitude = $msg['location']['longitude'];
                $messageText = "Location: Lat $latitude, Long $longitude";
                $mediaLink = '';
            } else {
                $messageText = "Unsupported message type: $messageType";
                $mediaLink = '';
            }
        
            $phoneNumberId = $data['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
            $checkQuery = "SELECT id FROM cmp_whatsapp_messages WHERE wam_id = '$wam_id' LIMIT 1";
            $result = mysqli_query($db, $checkQuery);
        
            if (mysqli_num_rows($result) > 0) {
                $updateQuery = "
                    UPDATE cmp_whatsapp_messages 
                    SET message_status = '$messageStatus', updated_date = '$message_datetime'
                    WHERE wam_id = '$wam_id'
                ";
                mysqli_query($db, $updateQuery);
            } else {
                $vendorQuery = "SELECT vendor_id FROM cmp_vendor_fb_credentials WHERE phone_no_id = '$phoneNumberId' LIMIT 1";
                $vendorResult = mysqli_query($db, $vendorQuery);
                $vendorRow = mysqli_fetch_assoc($vendorResult);
                $vendor_id = $vendorRow['vendor_id'];
        
                $insertQuery = "
                    INSERT INTO cmp_whatsapp_messages 
                    (vendor_id, agent, agent_contact, message_type, wam_id, message_body, media_link, message_status, created_date)
                    VALUES 
                    ('$vendor_id', '$agent', '$agent_contact', '$messageType', '$wam_id', '$messageText', '$mediaLink', '$messageStatus', '$message_datetime')
                ";
                mysqli_query($db, $insertQuery);
            }
        }
        
        

        //  BOT status handler
        if (isset($entry['statuses'])) {
            $status = $entry['statuses'][0];
            $wam_id = mysqli_real_escape_string($db, $status['id']);
            $messageStatus = mysqli_real_escape_string($db, $status['status']);
            $agent_contact = mysqli_real_escape_string($db, $status['recipient_id']);
            $agent = 'bot';
            $messageType = 'text';
            $messageText = '';
            // $created_by = $agent;
            $timestamp = $status['timestamp']; // WhatsApp timestamp for status
            $date = new DateTime("@$timestamp");
            $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $updated_date = $date->format('Y-m-d H:i:s'); // BOT status time

            $checkQuery = "SELECT id FROM cmp_whatsapp_messages WHERE wam_id = '$wam_id' ";
            // print_r($checkQuery);
            $result = mysqli_query($db, $checkQuery);

            if (mysqli_num_rows($result) > 0) {
                $updateQuery = "
                UPDATE cmp_whatsapp_messages 
                SET message_status = '$messageStatus',
                agent='$agent',
                agent_contact='$agent_contact',
                message_type='$messageType',
                updated_date='$updated_date'
                WHERE wam_id = '$wam_id'
            ";
                mysqli_query($db, $updateQuery);
                // } else {
                // $insertQuery = "
                //     INSERT INTO cmp_whatsapp_messages 
                //     (agent, agent_contact, message_type, wam_id, message_body, message_status, created_by, created_date)
                //     VALUES 
                //     ('$agent', '$agent_contact', '$messageType', '$wam_id', '$messageText', '$messageStatus', '$created_by', '$created_date')
                // ";
                // mysqli_query($db, $insertQuery);
            }
        }
    }



    // Store message in database
    // private function storeMessageInDB($sender, $messageText)
    // {
    //     $db = $this->dbConnect();
    //     $stmt = $db->prepare("INSERT INTO whatsapp_messages (sender, message, received_at) VALUES (?, ?, NOW())");
    //     $stmt->bind_param("ss", $sender, $messageText);
    //     $stmt->execute();
    //     $stmt->close();
    // }

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
// print_r($data);exit;
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
// print_r($response);exit;
        return $response;
    }
}
