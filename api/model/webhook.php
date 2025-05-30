<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

define('WEBHOOK_VERIFY_TOKEN', 'Happy');
// define('GRAPH_API_TOKEN', 'EAAQDIH4PiBoBO6vBZAClCmyRyYIqGs73jMiC8krCZCorG2ENws1skHh1t5pjkdvXdi9kk0nsD9ljaOTiSQZB8nSj03fM22MyeLdUXmTNlw7dSfZB4Rb4LwDJOOFZBZBZBGN2jcyNkNmytjTqD16V1PL6WCGXS2HbafMcGJep5WuvtdjpC1FOWi1ruVvjiI2BcFZAWwZDZD');

class WEBHOOKMODEL extends APIRESPONSE
{
    // Initiate db connection
    private function dbConnect()
    {
        $conn = new DBCONNECTION();
        return $conn->connect();
    }

    //get the vendor id from the session
    public function fbCredentials($phoneId)
    {
        try {
            $db = $this->dbConnect();
            $phoneId = mysqli_real_escape_string($db, $phoneId); // Secure input
            $query = "SELECT * FROM cmp_vendor_fb_credentials WHERE phone_no_id = '$phoneId'";
            // print_r($query);exit;
            $result = mysqli_query($db, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $rows = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    $rows = $row; // Add each row to the array
                }
                mysqli_close($db);

                return $rows; // Return the full array
            } else {
                throw new Exception("Vendor ID not found for the given phone number ID.");
            }
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => $e->getMessage(),
                ),
            );
        }
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
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);
        // print_r($data);exit;
        // Logging
        file_put_contents("webhook_log.txt", date("Y-m-d H:i:s") . " - " . json_encode($data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);
        file_put_contents("webhook_data.json", json_encode($data, JSON_PRETTY_PRINT));

        if (!isset($data['entry'])) {
            http_response_code(200);
            return json_encode(["status" => "No entries"]);
        }
        // print_r($data);exit;
        foreach ($data['entry'] as $entry) {
            foreach ($entry['changes'] as $change) {
                $phoneId = $change['value']['metadata']['phone_number_id'] ?? null;
                // print_r($change['value']['metadata']['phone_number_id']);exit;
                // Handle incoming messages
                if (!empty($change['value']['messages'])) {
                    foreach ($change['value']['messages'] as $message) {
                        $messageId = $message['id'] ?? null;
                        // if ($messageId && !$this->isMessageProcessed($messageId)) {
                        // print_r($messageId);exit;
                        $this->markMessageAsProcessed($messageId);
                        $this->processMessage($phoneId, $message, $data);
                    }
                    // }
                }

                // Handle status updates
                if (!empty($change['value']['statuses'])) {

                    foreach ($change['value']['statuses'] as $status) {
                        $messageId = $status['id'] ?? null;
                        // if ($messageId && !$this->isMessageProcessed($messageId)) {
                        // print_r($messageId);exit;
                        // print_r("status");exit;
                        $this->markMessageAsProcessed($messageId);
                        // print_r()
                        $this->processMessage($phoneId, $status, $data); // Optional: You can skip status reply
                    }
                    // }
                }
            }
        }


        http_response_code(200);
        return json_encode(["status" => "Webhook received"]);
    }



    private function isMessageProcessed($messageId)
    {
        // $file = "processed_ids.log";

        // if (!file_exists($file)) return false;

        // $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // // print_r($lines);exit;
        // return in_array($messageId, $lines);

        $db = $this->dbConnect();
        $query = "SELECT id FROM cmp_whatsapp_messages WHERE wam_id = '$messageId' AND is_reply = 1";
        $result = mysqli_query($db, $query);
        $rowCount = mysqli_num_rows($result);
        if ($rowCount > 0) {
            echo "Message already processed\n";
            return true;
        } else {
            echo "Message not processed\n";
            return false; 
        }

    }

    private function markMessageAsProcessed($messageId)
    {
        file_put_contents("processed_ids.log", $messageId . "\n", FILE_APPEND);
    }


    // Process individual message
    private function processMessage($businessPhoneNumberId, $message, $data)
    {
        // print_r(json_encode($data));exit;
        $sender = $message['from'];
        $messageText = $message['text']['body'] ?? 'No text';
        $messageId = $message['id'];

        // Detect the actual message text (text, button, or list reply)
        $messageText = 'No text';

        if (isset($message['text']['body'])) {
            $messageText = $message['text']['body'];
        } elseif (isset($message['interactive']['type'])) {
            $interactiveType = $message['interactive']['type'];

            if ($interactiveType === 'button_reply') {
                $messageText = $message['interactive']['button_reply']['title'] ?? 'No text';
            } elseif ($interactiveType === 'list_reply') {
                $messageText = $message['interactive']['list_reply']['title'] ?? 'No text';
            }
        }
        // print_r($messageText);exit;
        //get the vendor id from the phone number id
        $CredentialsData = $this->fbCredentials($businessPhoneNumberId);
        $vendorId = $CredentialsData['vendor_id'];
        $this->storeMessageInDB($message, $data);

        if ($messageId && !$this->isMessageProcessed($messageId)) {

            echo "comes in negative case";
            if (!empty($messageText) && $messageText !== 'No text') {
                $db = $this->dbConnect();
                $lowerMsg = strtolower(trim($messageText));
                $words = array_filter(explode(' ', $lowerMsg));

                $conditions = [];
                foreach ($words as $word) {
                    $word = mysqli_real_escape_string($db, trim($word));
                    $conditions[] = "LOWER(r.intent) LIKE '%$word%'";
                }


                $conditionStr = implode(' OR ', $conditions);
                $botQuery = "
                    SELECT r.*, t.name AS trigger_type 
                    FROM cmp_bot_replies r
                    JOIN cmp_bot_trigger_type t ON t.id = r.trigger_type_id
                    WHERE r.status = 1 AND r.active_status = 1 
                AND r.vendor_id = $vendorId
                    AND ($conditionStr)
                    ORDER BY r.id DESC
                    LIMIT 1
                ";

                $result = mysqli_query($db, $botQuery);
                $matchedReply = null;


                if ($row = mysqli_fetch_assoc($result)) {
                    $triggerType = strtolower($row['trigger_type']);
                    $rawIntent = json_decode($row['intent'], true);
                    if (is_array($rawIntent)) {
                        $intents = $rawIntent;
                    } else {
                        $intents = array_map('trim', explode(',', $row['intent']));
                    }

                    $matched = false;


                    foreach ($intents as $intent) {
                        $intent = strtolower(trim($intent));

                        switch ($triggerType) {
                            case 'contains':
                                if (strpos($lowerMsg, $intent) !== false) $matched = true;
                                break;

                            case 'starts_with':
                                if (substr($lowerMsg, 0, strlen($intent)) == $intent) $matched = true;
                                break;

                            case 'ends_with':
                                if (substr($lowerMsg, -strlen($intent)) === $intent) $matched = true;
                                break;

                            case 'exact':
                                if ($lowerMsg === $intent) $matched = true;
                                break;

                            default:
                                $matched = true;
                                break;
                        }

                        // if ($matched  ) {
                        $matchedReply = $row;
                        //     break;
                        // }
                    }
                }
                // print_r(    $matchedReply);exit;
                if ($matchedReply) {

                    $botResponses = json_decode($matchedReply['message_body'], true) ?? [];
                    $messageType = $matchedReply['message_type'];
                    // print_r($messageType);exit;
                    switch ($messageType) {
                        case 'text':
                            $replyText = $botResponses[0]['text'] ?? "Thank you for your message.";
                            $this->sendWhatsAppMessage($businessPhoneNumberId, $sender, $replyText, $messageId);
                            break;

                        // case 'image':
                        //     $imageUrl = $botResponses[0]['url'] ?? '';
                        //     $caption = $botResponses[0]['caption'] ?? '';
                        //     $this->sendImageMessage($businessPhoneNumberId, $sender, $imageUrl, $caption, $messageId);
                        //     break;
                        case 'image':
                        case 'video':
                        case 'document':
                            $mediaUrl = $botResponses[0]['url'] ?? '';
                            $caption = $botResponses[0]['caption'] ?? '';
                            // print_r($botResponses);exit;
                            $this->sendMediaMessage($businessPhoneNumberId, $sender, $botResponses[0]['type'], $mediaUrl, $caption, $messageId);
                            break;


                        case 'interactive_list':
                            $imageUrl = $botResponses[0]['url'] ?? '';
                            $caption = $botResponses[0]['caption'] ?? '';
                            $buttonType = $botResponses[0]['sub_type'] ?? 'reply'; // defaulting if needed
                            $buttons = $botResponses[0]['buttons'] ?? []; // optional fallback
                            $listSections = $botResponses[0]['listSections'] ?? [];
                            $footer = $botResponses[0]['footer'] ?? ''; // optional fallback
                            // print_r(json_encode($footer));exit;
                            // Remove print_r if everything works
                            $this->sendInteractiveMessage($businessPhoneNumberId, $sender, $imageUrl, $caption, $buttons, $messageId, $buttonType, $listSections, $footer);
                            break;


                        case 'interactive_reply':
                            $imageUrl = $botResponses[0]['url'] ?? '';
                            $caption = $botResponses[0]['caption'] ?? '';
                            $buttonType = $botResponses[0]['sub_type'] ?? 'reply'; // defaulting if needed
                            $buttons = $botResponses[0]['buttons'] ?? []; // optional fallback
                            $listSections = $botResponses[0]['listSections'] ?? [];
                            $footer = $botResponses[0]['footer'] ?? ''; // optional fallback
                            $this->sendInteractiveMessage($businessPhoneNumberId, $sender, $imageUrl, $caption, $buttons, $messageId, $buttonType, $listSections, $footer);
                            break;
                        case 'interactive_cta':
                            $imageUrl = $botResponses[0]['url'] ?? '';
                            $caption = $botResponses[0]['caption'] ?? '';
                            $buttonType = $botResponses[0]['sub_type'] ?? 'reply'; // defaulting if needed
                            $buttons = $botResponses[0]['buttons'] ?? []; // optional fallback
                            $listSections = $botResponses[0]['listSections'] ?? [];
                            $footer = $botResponses[0]['footer'] ?? ''; // optional fallback
                            // print_r(json_encode($footer));exit;
                            $this->sendInteractiveMessage($businessPhoneNumberId, $sender, $imageUrl, $caption, $buttons, $messageId, $buttonType, $listSections, $footer);
                            break;
                        default:
                            $this->sendWhatsAppMessage($businessPhoneNumberId, $sender, "Thank you for your message.", $messageId);
                            break;
                    }
                } else {
                    $this->sendWhatsAppMessage($businessPhoneNumberId, $sender, "Sorry, I didn't understand. Please try again.", $messageId);
                }
                mysqli_close($db);
            }

            $this->markMessageAsRead($businessPhoneNumberId, $messageId);
        }
    }


    public function storeMessageInDB($message = null, $data)
    {
        $db = $this->dbConnect();
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
            // echo"12121";exit;
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
                    (vendor_id, agent, agent_contact, message_type, wam_id, message_body, media_id, message_status, created_date)
                    VALUES 
                    ('$vendor_id', '$agent', '$agent_contact', '$messageType', '$wam_id', '$messageText', '$mediaLink', '$messageStatus', '$message_datetime')
                ";
                // print_r($insertQuery);
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
            }

            //query to update the queue status in db
            // Check in queue table
            $checkQueueQuery = "SELECT id FROM cmp_whatsapp_message_queue WHERE wam_id = '$wam_id' LIMIT 1";
            // print_r($checkQueueQuery);?
            $queueResult = mysqli_query($db, $checkQueueQuery);
            $updated_date = date('Y-m-d H:i:s');
            if (mysqli_num_rows($queueResult) > 0) {
                // Update queue table

                $updateQueueQuery = "
                UPDATE cmp_whatsapp_message_queue
                SET message_status = '$messageStatus',
                updated_date = '$updated_date'
                
            ";
                if (isset($status['errors'])) {
                    $updateQueueQuery .= " ,error_message = '" . mysqli_real_escape_string($db, $status['errors'][0]['error_data']['details']) . "'";
                }
                $updateQueueQuery .= " WHERE wam_id = '$wam_id'";
                mysqli_query($db, $updateQueueQuery);
            }
            print_r($updateQueueQuery);
        }
    }

    // Send WhatsApp message
    private function sendWhatsAppMessage($businessPhoneNumberId, $recipient, $text, $contextMessageId)
    {
        // print_r($contextMessageId);exit;
        $url = "https://graph.facebook.com/v18.0/{$businessPhoneNumberId}/messages";
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipient,
            "text" => ["body" => $text],
            "context" => ["message_id" => $contextMessageId]
        ];
        // print_r($data);exit;
        $this->makeCurlRequest($url, $data, $businessPhoneNumberId);
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

        $this->makeCurlRequest($url, $data, $businessPhoneNumberId);
    }
    private function sendMediaMessage($businessPhoneNumberId, $recipient, $mediaType, $mediaUrl, $caption = '', $contextMessageId = null)
    {
        $url = "https://graph.facebook.com/v22.0/{$businessPhoneNumberId}/messages";

        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipient,
            "type" => $mediaType,
            $mediaType => [
                "link" => $mediaUrl
            ]
        ];
        // print_r($mediaType);exit;
        // Add caption if provided (only applicable for image/video/document)
        if (!empty($caption) && in_array($mediaType, ['image', 'video', 'document'])) {
            $data[$mediaType]['caption'] = $caption;
        }
        // Add filename only for document
        if ($mediaType === 'document') {
            // Extract filename from URL
            $parsedUrl = parse_url($mediaUrl);
            $path = $parsedUrl['path'] ?? '';
            $filename = basename($path);
            $data[$mediaType]['filename'] = $filename;
        }

        // Add context if replying to a specific message
        if (!empty($contextMessageId)) {
            $data["context"] = ["message_id" => $contextMessageId];
        }

        // Debug print
        // print_r(json_encode($data)); exit;

        $this->makeCurlRequest($url, $data, $businessPhoneNumberId);
    }

    private function sendInteractiveMessage($businessPhoneNumberId, $recipient, $imageUrl, $caption, $buttons, $contextMessageId, $buttonType = 'reply', $listSections = [], $footer = '')
    {
        // print_r($buttonType);exit;
        $url = "https://graph.facebook.com/v22.0/{$businessPhoneNumberId}/messages";
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipient,
            "type" => "interactive",
            "context" => ["message_id" => $contextMessageId]
        ];
        // print_r(json_encode($data));exit;
        if ($buttonType === 'reply') {
            $buttonComponents = [];
            foreach ($buttons as $index => $btn) {
                $buttonComponents[] = [
                    "type" => "reply",
                    "reply" => [
                        "id" => "btn_" . ($index + 1),
                        "title" => $btn['title']
                    ]
                ];
            }

            $data["interactive"] = [
                "type" => "button",
                "body" => ["text" => $caption],
                "footer" => ["text" => $footer],

                "header" => [
                    "type" => "image",
                    "image" => ["link" => $imageUrl]
                ],
                "action" => [
                    "buttons" => $buttonComponents
                ]
            ];
            // print_r(json_encode($data));exit;
        } elseif ($buttonType === 'cta') {
            $buttonComponents = [];
            foreach ($buttons as $btn) {
                if (isset($btn['url'])) {
                    $buttonComponents[] = [
                        "type" => "cta_url", // The type for CTA URL
                        "action" => [
                            "name" => "cta_url",
                            "parameters" => [
                                "display_text" => $btn['title'],
                                "url" => $btn['url']
                            ]
                        ]
                    ];
                }
            }

            $data["interactive"] = [
                "type" => "cta_url", // The type for CTA URL
                // "header" => [
                //     "type" => "text",
                //     "text" => "Header Text" // Optional: You can customize the header
                // ],
                "body" => [
                    "text" => $caption // The body text
                ],
                "footer" => [
                    "text" => $footer // 
                ],
                "action" => [
                    "name" => "cta_url",
                    "parameters" => [
                        "display_text" => $btn['title'],
                        "url" => $btn['url']
                    ]
                ]
            ];
            // print_r(json_encode($data));exit;
        } elseif ($buttonType === 'list') {
            $sections = [];
            foreach ($listSections as $section) {
                $rows = [];
                foreach ($section['rows'] as $row) {
                    $rows[] = [
                        "id" => $row['id'],
                        "title" => $row['title'],
                        "description" => $row['description'] ?? ''
                    ];
                }
                $sections[] = [
                    "title" => $section['title'],
                    "rows" => $rows
                ];
            }

            $data["interactive"] = [
                "type" => "list",
                "body" => ["text" => $caption],
                "footer" => ["text" => $footer],
                "action" => [
                    "button" => "Choose an option",
                    "sections" => $sections
                ]
            ];
            // print_r(json_encode($data));exit;
        }

        $this->makeCurlRequest($url, $data, $businessPhoneNumberId);
    }


    // Helper function to make cURL request
    private function makeCurlRequest($url, $data, $businessPhoneNumberId)
    {
        // print_r(json_encode($data));
        // exit;
        $CredentialsData = $this->fbCredentials($businessPhoneNumberId);
        $graphApiToken = $CredentialsData['access_token']; // Get access_token value from array
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $graphApiToken,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        // print_r($response);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $db = $this->dbConnect();

        if ($httpCode == 200) {

            $update = "UPDATE cmp_whatsapp_messages
                        SET is_reply = '1',
                            updated_date = NOW()
                        WHERE  wam_id = '" . $data['context']['message_id'] . "' AND agent_contact = '" . $data['to'] . "'
                    ";
            mysqli_query($db, $update);
            //     $update = "INSERT INTO cmp_whatsapp_messages 
            //     (vendor_id, campaign_id, agent, agent_contact, wam_id, message_type, message_body, message_status)
            //     VALUES ('$vendorId', '$campaignId', 'bot', '$contactMobile', '$wamId', 'template', '" . json_encode($payload) . "', '$messageStatus')
            // ";
        }
        return $response;
    }
}
