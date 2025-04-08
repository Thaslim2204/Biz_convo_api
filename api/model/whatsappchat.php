<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

define('WHATSAPP_API_URL', 'https://graph.facebook.com/v22.0/637421792778479/messages');
define('ACCESS_TOKEN', 'EAA5HzO0nYi0BOZBaf6zlgEBEJCcT5lZAUOiAZCnobKiUkOeDdnLjxcyGNSlivoMsvSv9ZBY73gwSIyOx4rcOZAZBo0Hwtj0JbotvN7CxfyOsOZBZBnEZBg8KTtfGoBl1M0qnkH4e1sj83qzZCkVu3h3U1jqaysQvPXxF2OFZBP4mSWBYmg3PlJ9tzSZBVz07tFnr7yOrZCgZDZD');

class WHATSAPPCHATMODEL extends APIRESPONSE
{
    public function processMethod($data, $loginData)
    {
        $urlPath = isset($_GET['url']) ? $_GET['url'] : '';
        $urlParam = explode('/', $urlPath);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                if (isset($urlParam[1]) && $urlParam[1] === 'send') {
                    return $this->sendMessage($_REQUEST);
                } else {
                    throw new Exception("Invalid POST request!");
                }
            default:
                return [
                    "apiStatus" => [
                        "code" => "405",
                        "message" => "Unsupported request method."
                    ]
                ];
        }
    }

    private function dbConnect()
    {
        $conn = new DBCONNECTION();
        return $conn->connect();
    }

    public function sendMessage($request)
    {
    // print_r($request);exit;
        if (empty($request)) {
            return [
                "apiStatus" => [
                    "code" => "400",
                    "message" => "Invalid request data."
                ]
            ];
        }
        try {
            $recipient = isset($request['to']) ? $request['to'] : null;
            $messageBody = isset($request['text']) ? $request['text'] : null;
            $isMediaMessage = isset($request['isMediaMessage']) ? $request['isMediaMessage'] : false;

            if (!$messageBody && !$isMediaMessage) {
                return [
                    "apiStatus" => [
                        "code" => "401",
                        "message" => "Message body is required!"
                    ]
                ];
            }

            if (!$recipient) {
                return [
                    "apiStatus" => [
                        "code" => "401",
                        "message" => "Recipient is required!"
                    ]
                ];
            }

            if ($isMediaMessage) {
                $mediaType = $request['media_type'] ?? null;
                $caption = $request['caption'] ?? '';

                // Handle file upload if file is present
                if (isset($_FILES['file'])) {
                    $fileUrl = $this->handleMediaUpload($_FILES['file'], $mediaType);
                } else {
                    $fileUrl = $request['media_url'] ?? null;
                }

                if (!$fileUrl) {
                    return [
                        "apiStatus" => [
                            "code" => "400",
                            "message" => "Media URL or uploaded file is required."
                        ]
                    ];
                }

                return $this->sendMediaMessage($recipient, $mediaType, $fileUrl, $caption);
            } else {
                return $this->sendTextMessage($recipient, $messageBody);
            }
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "500",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }

    private function handleMediaUpload($file, $mediaType)
    {
        $uploadDir = "uploads/";

        if ($mediaType == "image") {
            $targetDir = $uploadDir . "image/";
        } elseif ($mediaType == "video") {
            $targetDir = $uploadDir . "video/";
        } else {
            return false;
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = time() . "_" . basename($file['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return "http://localhost/Biz_convo/api/" . $targetFile;
        } else {
            return false;
        }
    }

    function sendMediaMessage($recipientPhone, $mediaType, $fileUrl, $caption = '')
    {
        if (!$recipientPhone || !$mediaType || !$fileUrl) {
            return [
                "apiStatus" => [
                    "code" => "400",
                    "message" => "Missing required parameters."
                ]
            ];
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
            return [
                "apiStatus" => [
                    "code" => "400",
                    "message" => "Invalid media type."
                ]
            ];
        }

        return $this->sendRequest($messageData);
    }

    function sendTextMessage($recipientNumber, $message)
    {
        $data = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $recipientNumber,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => $message
            ]
        ];

        return $this->sendRequest($data);
    }

    private function sendRequest($data)
    {
        try {
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
                $result = [
                    "message" => "Message sent successfully.",
                    "wamid" => $responseData['messages'][0]['id'] ?? null
                ];
                $insertmsgquery = "INSERT INTO `cmp_whatsapp_messages` (agent_contact, wam_id, message_body,message_status) VALUES ('".$data['to']."', '".$responseData['messages'][0]['id']."', '".$data['text']['body']."', 'sent')";

                // print_r($insertmsgquery);exit;
                $conn = $this->dbConnect();
                $result11 = $conn->query($insertmsgquery);

                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Success"
                    ],
                    "result" => $result
                ];
            } else {
                return [
                    "apiStatus" => [
                        "code" => (string)($responseData['error']['code'] ?? "400"),
                        "message" => $responseData['error']['message'] ?? "Failed to send message."
                    ]
                ];
            }
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "500",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }

    private function getVendorId()
    {
        return 1; // Example static ID
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
                    "message" => $e->getMessage()
                ]
            ];
        }
    }
}
