<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

define('WHATSAPP_API_URL', 'https://graph.facebook.com/v22.0/556740220861937/messages');
define('ACCESS_TOKEN', 'YOUR_ACCESS_TOKEN_HERE'); // Replace with a valid token

class WHATSAPPCHATMODEL extends APIRESPONSE
{
    public function processMethod($data, $loginData)
    {
        $urlPath = isset($_GET['url']) ? $_GET['url'] : '';
        $urlParam = explode('/', $urlPath);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                if (isset($urlParam[1]) && $urlParam[1] === 'send') {
                    return $this->sendMessage($data);
                } else {
                    throw new Exception("Invalid POST request!");
                }
                break;
            default:
                return ['status' => 'error', 'message' => 'Unsupported request method.'];
        }
    }

    private function dbConnect()
    {
        $conn = new DBCONNECTION();
        return $conn->connect();
    }

    public function sendMessage($request)
    {
        $vendorId = isset($request['vendorId']) ? $request['vendorId'] : $this->getVendorId();
        $messageBody = isset($request['messageBody']) ? $request['messageBody'] : null;
        $contactUid = isset($request['contactUid']) ? $request['contactUid'] : null;
        $isMediaMessage = isset($request['isMediaMessage']) ? $request['isMediaMessage'] : false;

        if (!$messageBody && !$isMediaMessage) {
            return ['status' => 'error', 'message' => 'Message body is required!'];
        }

        // Ensure contact fetching logic exists
        $contact = $this->getContactByUid($contactUid, $vendorId);
        if (!$contact) {
            return ['status' => 'error', 'message' => 'Contact not found'];
        }

        if ($isMediaMessage) {
            $fileUrl = isset($request['media_url']) ? $request['media_url'] : null;
            $mediaType = isset($request['media_type']) ? $request['media_type'] : null;
            $caption = isset($request['caption']) ? $request['caption'] : '';

            return $this->sendMediaMessage($contact['wa_id'], $mediaType, $fileUrl, $caption);
        } else {
            return $this->sendTextMessage($contact['wa_id'], $messageBody);
        }
    }

    function sendMediaMessage($recipientPhone, $mediaType, $fileUrl, $caption = '')
    {
        if (!$recipientPhone || !$mediaType || !$fileUrl) {
            return ['success' => false, 'message' => 'Missing required parameters.'];
        }

        $messageData = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhone,
            'type' => $mediaType
        ];

        if (in_array($mediaType, ["image", "video", "audio"])) {
            $messageData[$mediaType] = ['link' => $fileUrl, 'caption' => $caption];
        } elseif ($mediaType === "document") {
            $messageData["document"] = [
                'link' => $fileUrl,
                'filename' => basename($fileUrl),
                'caption' => $caption
            ];
        } else {
            return ['success' => false, 'message' => 'Invalid media type.'];
        }

        return $this->sendRequest($messageData);
    }

    function sendTextMessage($recipientNumber, $message)
    {
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipientNumber,
            "type" => "text",
            "text" => ["body" => $message]
        ];

        return $this->sendRequest($data);
    }

    private function sendRequest($data)
    {
        $ch = curl_init(WHATSAPP_API_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . ACCESS_TOKEN,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = json_decode($response, true);

        if ($httpCode == 200 || $httpCode == 201) {
            return [
                'success' => true,
                'message' => 'Message sent successfully.',
                'wamid' => $responseData['messages'][0]['id'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'message' => $responseData['error']['message'] ?? 'Failed to send message.',
                'error_code' => $responseData['error']['code'] ?? null
            ];
        }
    }

    private function getVendorId()
    {
        return 2; // Example static ID
    }

    private function getContactByUid($contactUid, $vendorId)
    {
        // Simulate fetching contact from DB
        if ($contactUid) {
            return ['wa_id' => '919025714445']; // Replace with real data
        }
        return null;
    }

    public function processList($request, $token)
    {
        try {
            $responseData = $this->processMethod($request, $token);
            return $this->response($responseData);
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage(),
                ],
            ];
        }
    }
}
