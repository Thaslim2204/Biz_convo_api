<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

// define('WHATSAPP_API_URL', 'https://graph.facebook.com/v22.0/637421792778479/messages');
// define('ACCESS_TOKEN', 'EAA5HzO0nYi0BOZBaf6zlgEBEJCcT5lZAUOiAZCnobKiUkOeDdnLjxcyGNSlivoMsvSv9ZBY73gwSIyOx4rcOZAZBo0Hwtj0JbotvN7CxfyOsOZBZBnEZBg8KTtfGoBl1M0qnkH4e1sj83qzZCkVu3h3U1jqaysQvPXxF2OFZBP4mSWBYmg3PlJ9tzSZBVz07tFnr7yOrZCgZDZD');

class WHATSAPPCHATMODEL extends APIRESPONSE
{
    public function processMethod($data, $loginData)
    {
        $urlPath = isset($_GET['url']) ? $_GET['url'] : '';
        $urlParam = explode('/', $urlPath);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "send") {
                    $result = $this->sendMessage($_REQUEST, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "list") {
                    $result = $this->messagelist($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
            default:
                $result = $this->handle_error();
                return $result;
                break;
        }
    }

    private function dbConnect()
    {
        $conn = new DBCONNECTION();
        return $conn->connect();
    }
    private $facebook_base_url;
    private $facebook_base_version;
    // private $whatsapp_business_id;
    private $phone_no_id;
    private $fb_auth_token;
    // private $facebook_app_id;
    private function fbCredentials($loginData)
    {
        // print_r($loginData);
        $db = $this->dbConnect();

        $this->facebook_base_url = "https://graph.facebook.com";
        $this->facebook_base_version = "v22.0";

        //get private tokens from DB
        $sql = "SELECT whatsapp_business_acc_id, phone_no_id, access_token , app_id from cmp_vendor_fb_credentials where vendor_id = '1' and status = 1";
        $result = $db->query($sql);
        $fbData = mysqli_fetch_assoc($result);
        // print_r($fbData);exit;

        if ($fbData) {
            // $this->whatsapp_business_id = $fbData['whatsapp_business_acc_id'];
            $this->phone_no_id = $fbData['phone_no_id'];
            $this->fb_auth_token = $fbData['access_token'];
            // $this->facebook_app_id = $fbData['app_id'];
        } else {
            throw new Exception("Failed to fetch Facebook credentials from the database.");
        }
    }


    public function sendMessage($request, $loginData)
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

                return $this->sendMediaMessage($recipient, $mediaType, $fileUrl, $caption, $loginData);
            } else {
                return $this->sendTextMessage($recipient, $messageBody, $loginData);
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

    function sendMediaMessage($recipientPhone, $mediaType, $fileUrl, $caption = '', $loginData)
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

        return $this->sendRequest($messageData, $loginData);
    }

    function sendTextMessage($recipientNumber, $message, $loginData)
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

        return $this->sendRequest($data, $loginData);
    }

    private function sendRequest($data, $loginData)
    {
        // print_r($loginData);exit;
        try {
            $this->fbCredentials($loginData);
            $url =  $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->phone_no_id . '/' . "messages";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->fb_auth_token,
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
                //insert the data into the database
                $agentContact = $data['to'];
                $wamId = $responseData['messages'][0]['id'];
                $createdBy = $loginData['user_id'];
                $messageStatus = 'sent';
                $messageType = $data['type'];
                // $agent = 'bot';

                if (isset($data['text'])) {
                    $messageBody = $data['text']['body'];
                    $mediaLink = null;
                } else {
                    // For media messages like image, video, etc.
                    $messageBody = $data[$messageType]['caption'] ?? '';
                    $mediaLink = $data[$messageType]['link'] ?? '';
                }

                // Insert into database
                $insertmsgquery = "INSERT INTO `cmp_whatsapp_messages` 
    (agent,agent_contact, wam_id, message_type, message_body, media_link, message_status, created_by) 
    VALUES ('bot' ,'$agentContact', '$wamId', '$messageType', '$messageBody', '$mediaLink', '$messageStatus', '$createdBy')";


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


    public function messagelist($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $recipient = isset($data['filter']['to']) ? $data['filter']['to'] : null;
            $userId = $loginData['user_id'];

            if ($data['pageIndex'] === "") {
                throw new Exception("pageIndex should not be empty!");
            }
            if ($data['dataLength'] === "") {
                throw new Exception("dataLength should not be empty!");
            }

            $pageIndex = (int)$data['pageIndex'];
            $dataLength = (int)$data['dataLength'];
            $start_index = $pageIndex * $dataLength;

            // Get total count
            $countQuery = "SELECT COUNT(*) AS totalCount FROM cmp_whatsapp_messages WHERE status=1";
            if ($recipient) {
                $countQuery .= " AND agent_contact = '$recipient'";
            }
            $countResult = $db->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $recordCount = $countRow['totalCount'];

            // Get paginated messages
            $query = "SELECT agent,message_type,wam_id,agent_contact, message_body,media_link, message_status, created_date,updated_date
                      FROM cmp_whatsapp_messages  
                      WHERE status=1";
            if ($recipient) {
                $query .= " AND agent_contact = '$recipient'";
            }
            $query .= " ORDER BY created_by ASC 
                        LIMIT $start_index, $dataLength";
            // print_r($query);exit;

            $result = $db->query($query);

            $messages = [];
            while ($row = $result->fetch_assoc()) {
                // $messageBody = json_decode($row['message_body'], true); // Assuming message_body is JSON
                // print_r($row);exit;
                $messages[] = [
                    "messageAgent" => $row['agent'], // You can adjust this based on your logic
                    "messageBody" => [
                        "messageText" => $row['message_body'] ?? "",
                        "MessageMedia" => $row['media_link'] ?? ""
                    ],
                    "messageStatus" => $row['message_status'],
                    "time" => date("H:i:s", strtotime($row['updated_date'])),
                ];
            }

            if (!empty($messages)) {
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Message details fetched successfully"
                    ],
                    "result" => [
                        "pageIndex" => $pageIndex,
                        "dataLength" => $dataLength,
                        "totalRecordCount" => $recordCount,
                        "MessageData" => $messages
                    ]
                ];
            } else {
                return [
                    "apiStatus" => [
                        "code" => "404",
                        "message" => "No data found..."
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
    private function handle_error() {}
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
