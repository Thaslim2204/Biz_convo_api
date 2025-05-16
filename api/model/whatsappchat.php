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
                } elseif ($urlParam[1] == "sidelist") {
                    $result = $this->sidecontactlist($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "clearchathistory") {
                    $result = $this->clearChatHistory($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "contactinfo") {
                    $result = $this->contactinfo($data, $loginData);
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
    private function fbCredentials($vendor_id)
    {
        // print_r($loginData);
        $db = $this->dbConnect();

        $this->facebook_base_url = "https://graph.facebook.com";
        $this->facebook_base_version = "v22.0";

        //get private tokens from DB
        $sql = "SELECT id,whatsapp_business_acc_id, phone_no_id, access_token , app_id from cmp_vendor_fb_credentials where vendor_id = $vendor_id and status = 1";
        $result = $db->query($sql);
        $fbData = mysqli_fetch_assoc($result);
        // print_r( $fbData['access_token']);exit;

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

                if (isset($_FILES['file'])) {
                    $relativePath = $this->handleMediaUpload($_FILES['file'], $mediaType); // Store only path like "uploads/..."
                } else {
                    return [
                        "apiStatus" => ["code" => "400", "message" => "Uploaded file is required."]
                    ];
                }

                if (!$relativePath) {
                    return [
                        "apiStatus" => ["code" => "400", "message" => "Failed to upload media."]
                    ];
                }
                // ✅ This is what goes to WhatsApp API
                $mediaId = $this->getMediaUrl($relativePath);
                // print_r($mediaId);exit;

                if (!$mediaId) {
                    return [
                        "apiStatus" => [
                            "code" => "400",
                            "message" => "Failed to upload media to Meta."
                        ]
                    ];
                }

                return $this->sendMediaMessage($recipient, $mediaType, $mediaId, $caption, $loginData, $relativePath);
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
        $uploadDir = "uploads/whatsappMedia/";

        switch ($mediaType) {
            case "image":
                $targetDir = $uploadDir . "images/";
                break;
            case "video":
                $targetDir = $uploadDir . "videos/";
                break;
            case "audio":
                $targetDir = $uploadDir . "audios/";
                break;
            case "document":
                $targetDir = $uploadDir . "documents/";
                break;
            default:
                return false;
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = time() . "_" . basename($file['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $targetFile;  // ✅ Return only relative path
        }

        return false;
    }


    private function getMediaUrl($relativePath)
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        $basePath = rtrim($basePath, '/');

        return $protocol . $host . $basePath . '/' . $relativePath;
    }



    function sendMediaMessage($recipientPhone, $mediaType, $mediaId, $caption = '', $loginData, $relativePath)
    {
        // print_r($relativePath);exit;

        if (!$recipientPhone || !$mediaType || !$mediaId) {
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
        // print_r($messageData);exit;

        if (in_array($mediaType, ["image", "video", "audio"])) {
            $messageData[$mediaType] = [
                'link' => $mediaId,
                'caption' => $caption
            ];
        } elseif ($mediaType === "document") {
            $fileName = basename(parse_url($mediaId, PHP_URL_PATH)); // Extract actual file name from the URL

            $messageData["document"] = [
                'link' => $mediaId,
                'caption' => $caption,
                'filename' => $fileName
            ];
        } else {
            return [
                "apiStatus" => [
                    "code" => "400",
                    "message" => "Invalid media type."
                ]
            ];
        }
        // print_r($relativePath);exit;
        return $this->sendRequest($messageData, $loginData, $relativePath);
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

    private function sendRequest($data, $loginData, $relativePath = null)
    {
        // print_r($relativePath);exit;
        try {
            $db = $this->dbConnect();
            $user_id = $loginData['user_id'];

            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id";
            $result = $db->query($sql);

            if ($result) {
                $row = $result->fetch_assoc();
                if (!$row || !isset($row['vendor_id'])) {
                    throw new Exception("Vendor ID not found for user ID: $user_id");
                }
                $vendor_id = $row['vendor_id'];
            } else {
                throw new Exception("Database query failed: " . $db->error);
            }

            $this->fbCredentials($vendor_id);

            $url =  $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->phone_no_id . '/' . "messages";
            // print_r($url);exit;
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
            // print_r(json_encode($httpCode));exit;
            if ($httpCode == 200 || $httpCode == 201) {
                $result = [
                    "message" => "Message sent successfully.",
                    "wamid" => $responseData['messages'][0]['id'] ?? null
                ];

                $agentContact = $data['to'];
                $wamId = $responseData['messages'][0]['id'];
                $createdBy = $loginData['user_id'];
                $messageStatus = 'sent';
                $messageType = $data['type'];

                if (isset($data['text'])) {
                    $messageBody = $data['text']['body'];
                    $mediaLink = null;
                } else {
                    $messageBody = $data[$messageType]['caption'] ?? '';
                    $mediaLink = $data[$messageType]['link'] ?? '';
                    $mediaid = $data[$messageType]['id'] ?? '';
                }
                $dateNow = date("Y-m-d H:i:s");
                $insertmsgquery = "INSERT INTO `cmp_whatsapp_messages` 
                    (agent, agent_contact, vendor_id, wam_id, message_type, message_body,media_id, media_link, message_status, created_by,created_date) 
                    VALUES ('bot', '$agentContact', '$vendor_id', '$wamId', '$messageType', '$messageBody','$mediaid', '$relativePath', '$messageStatus', '$createdBy', '$dateNow')";
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


            // print_r($this->fb_auth_token);exit;
            $recipient = isset($data['filter']['to']) ? $data['filter']['to'] : null;
            $user_id = $loginData['user_id'];


            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id";
            $result = $db->query($sql);

            if ($result) {
                $row = $result->fetch_assoc();
                if (!$row || !isset($row['vendor_id'])) {
                    throw new Exception("Vendor ID not found for user ID: $user_id");
                }
                $vendor_id = $row['vendor_id'];
            } else {
                throw new Exception("Database query failed: " . $db->error);
            }
            $this->fbCredentials($vendor_id);
            $access_token = $this->fb_auth_token;
            $facebook_base_version = $this->facebook_base_version;

            // print_r($access_token);exit;
            // Get total count
            $countQuery = "SELECT COUNT(*) AS totalCount FROM cmp_whatsapp_messages WHERE status=1";
            if ($recipient) {
                $countQuery .= " AND agent_contact = '$recipient'";
            }
            $countResult = $db->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $recordCount = $countRow['totalCount'];

            // Get paginated messages
            $query = "SELECT agent, message_type, wam_id, agent_contact, message_body, media_id,media_link, message_status, created_date, updated_date
                  FROM cmp_whatsapp_messages  
                  WHERE status = 1 AND vendor_id = '$vendor_id'";
            if ($recipient) {
                $query .= " AND agent_contact = '$recipient'";
            }
            $query .= " ORDER BY created_by ASC";
            // print_r($query);exit;
            $result = $db->query($query);

            $messages = [];
            while ($row = $result->fetch_assoc()) {
                // print_r($row);exit;
                $media_url = "";
                // If media_id is set and the message is from the user
                if (!empty($row['media_id']) && $row['agent'] == "user") {
                    // Call getMetaMediaUrl for each media_id individually
                    $media_url = $this->getMetaMediaUrl($row['media_id'], $access_token, $facebook_base_version);
                } elseif (!empty($row['media_link'])) {
                    $media_url = $this->getMediaUrl($row['media_link']);
                }
                // print_r($media_url);exit;
                $messages[] = [
                    "messageAgent" => $row['agent'],
                    "messageBody" => [
                        "messageText" => $row['message_body'] ?? "",
                        "MessageMedia" => $media_url ?? ""
                    ],
                    "messageStatus" => $row['message_status'],
                    "time" => date("H:i:s", strtotime($row['created_date'])),
                ];
            }
            // print_r(json_encode($messages));exit;
            if (!empty($messages)) {
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Message details fetched successfully"
                    ],
                    "result" => [
                        "totalRecordCount" => $recordCount,
                        "AccessToken" => $access_token,
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

    // Fetch Media URL from Meta
    private function getMetaMediaUrl($media_id, $access_token, $facebook_base_version)
    {
        // print_r($media_id);exit;
        $url = "https://graph.facebook.com/{$facebook_base_version}/{$media_id}";
        // print_r($url);exit;
        $headers = [
            "Authorization: Bearer {$access_token}"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);

        return $responseData['url'] ?? "";
    }

    // Download and Save Media to your server
    // private function downloadAndSaveMedia($media_url)
    // {
    //     $fileContent = @file_get_contents($media_url);

    //     if ($fileContent === false) {
    //         return false;
    //     }

    //     // Create uploads folder if not exists
    //     $uploadDir = __DIR__ . "/uploads/";
    //     if (!file_exists($uploadDir)) {
    //         mkdir($uploadDir, 0777, true);
    //     }

    //     $fileName = uniqid('media_') . ".jpg"; // you can change extension if needed
    //     $filePath = $uploadDir . $fileName;

    //     file_put_contents($filePath, $fileContent);

    //     // Assuming your uploads are accessible like "https://yourdomain.com/uploads/"
    //     $server_url = "https://yourdomain.com/uploads/"; // <<<--- change this URL
    //     return $server_url . $fileName;
    // }


    //side contact side

    public function sidecontactlist($data, $loginData)
    {
        try {
            // print_r();exit;
            $db = $this->dbConnect();

            $userId = $loginData['user_id'];

            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $userId";
            $result = $db->query($sql);

            if ($result) {
                $row = $result->fetch_assoc();
                if (!$row || !isset($row['vendor_id'])) {
                    throw new Exception("Vendor ID not found for user ID: $userId");
                }
                $vendor_id = $row['vendor_id'];
            } else {
                throw new Exception("Database query failed: " . $db->error);
            }

            // Get total count
            $countQuery = "SELECT COUNT(*) AS totalCount FROM cmp_whatsapp_messages WHERE status=1 and vendor_id = '$vendor_id'";
            $countResult = $db->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $recordCount = $countRow['totalCount'];

            // Prepare the base query
            $query = "SELECT c.id, wm.agent_contact, c.first_name, c.last_name, wm.created_date
                    FROM cmp_whatsapp_messages AS wm
                    LEFT JOIN cmp_contact AS c ON wm.agent_contact = c.mobile
                    WHERE wm.vendor_id = '$vendor_id'  
                      AND c.vendor_id = '$vendor_id'";

            // Add the filtering condition dynamically if `$data['fiterBy']` is set
            if (!empty($data['filter']['search'])) {
                $filter = mysqli_real_escape_string($db, $data['filter']['search']);
                $query .= " AND (wm.agent_contact LIKE '%$filter%' 
           OR c.first_name LIKE '%$filter%' 
           OR c.last_name LIKE '%$filter%')";
            }

            // Add GROUP BY and ORDER BY clauses
            $query .= " GROUP BY wm.agent_contact
  ORDER BY wm.created_date ASC";  // Assuming you want to order by 'created_date', not 'created_by'


            $result = $db->query($query);
            // print_r($query);exit;
            $messages = [];
            while ($row = $result->fetch_assoc()) {
                $createdDateTime = strtotime(trim($row['created_date']));
                $currentDateTime = time();
                $timeDifference = $currentDateTime - $createdDateTime;
                // Calculate "time ago" format
                if ($timeDifference < 60) {
                    $lastMessageTime = "Just now";
                } elseif ($timeDifference < 3600) {
                    $minutes = floor($timeDifference / 60);
                    $lastMessageTime = $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
                } elseif ($timeDifference < 86400) {
                    $hours = floor($timeDifference / 3600);
                    $lastMessageTime = $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
                } else {
                    $days = floor($timeDifference / 86400);
                    $lastMessageTime = $days . " day" . ($days > 1 ? "s" : "") . " ago";
                }

                $messages[] = [
                    "contactId" => $row['id'],
                    "contactName" => $row['first_name'] . " " . $row['last_name'],
                    "contactNumber" => $row['agent_contact'],
                    "time" => date("H:i:s", $createdDateTime),
                    "lastMessageTime" => $lastMessageTime
                ];
            }

            if (!empty($messages)) {
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Side Contact details fetched successfully"
                    ],
                    "result" => [
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


    public function contactinfo($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $userId = $loginData['user_id'];

            if (empty($data['contactNumber'])) {
                throw new Exception("Contact number is required.");
            }
            // Get vendor_id for the user
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $userId";
            $result = $db->query($sql);

            if ($result) {
                $row = $result->fetch_assoc();
                if (!$row || !isset($row['vendor_id'])) {
                    throw new Exception("Vendor ID not found for user ID: $userId");
                }
                $vendor_id = $row['vendor_id'];
            } else {
                throw new Exception("Database query failed: " . $db->error);
            }

            // Get contact info
            $query = "SELECT * FROM cmp_contact 
                  WHERE status = 1 
                  AND vendor_id = '$vendor_id' 
                  AND mobile = '" . $db->real_escape_string($data['contactNumber']) . "'";
            // print_r($query);exit;
            // print_r($query);exit;
            $result = $db->query($query);

            if (!$result) {
                throw new Exception("Database query failed: " . $db->error);
            }

            if ($result->num_rows == 0) {
                // No data found, throw exception
                throw new Exception("No contact found with the given number.");
            }

            $contact = $result->fetch_assoc();
            // print_r($contact);exit;
            $messages = [
                [
                    "contactId" => $contact['id'],
                    "contactName" => $contact['first_name'] . " " . $contact['last_name'],
                    "contactNumber" => $contact['mobile'],
                    "contactEmail" => $contact['email'],
                    "lanaguage" => $contact['language_code'],
                    "country" => $contact['country'],
                    "contactDob" => $contact['date_of_birth'],
                    "contactAddress" => $contact['address'],
                    "contactCountry" => $contact['country'],
                    "salesamount" => $contact['sales_amount'],
                    "anniversary" => $contact['anniversary'],
                    "loyalitypoints" => $contact['loyality'],
                ]
            ];

            return [
                "apiStatus" => [
                    "code" => "200",
                    "message" => "Contact Info details fetched successfully"
                ],
                "result" => [
                    "ContactInfoData" => $messages
                ]
            ];
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "500",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }
    public function clearChatHistory($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $userId = (int)$loginData['user_id'];

            if (empty($data['contactNumber'])) {
                throw new Exception("Contact number is required.");
            }

            $contactNumber = $db->real_escape_string($data['contactNumber']);

            // Get vendor_id for the user
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $userId";
            $result = $db->query($sql);

            if (!$result) {
                throw new Exception("Database query failed: " . $db->error);
            }

            $row = $result->fetch_assoc();
            if (!$row || !isset($row['vendor_id'])) {
                throw new Exception("Vendor ID not found for user ID: $userId");
            }

            $vendor_id = $db->real_escape_string($row['vendor_id']);

            // Check if contact exists
            $query = "SELECT * FROM cmp_whatsapp_messages 
                  WHERE status = 1 
                  AND vendor_id = '$vendor_id' 
                  AND agent_contact = '$contactNumber'";
            //   print_r($query);exit;
            $result = $db->query($query);

            if (!$result) {
                throw new Exception("Database query failed: " . $db->error);
            }

            if ($result->num_rows == 0) {
                throw new Exception("No contact found with the given number.");
            }

            // Soft delete: update status = 0
            $updateQuery = "UPDATE cmp_whatsapp_messages 
                        SET status = 0 
                        WHERE agent_contact = '$contactNumber' 
                        AND vendor_id = '$vendor_id'";
            // print_r($updateQuery);exit;
            $updateResult = $db->query($updateQuery);

            if (!$updateResult) {
                throw new Exception("Failed to update chat history status: " . $db->error);
            }

            return [
                "apiStatus" => [
                    "code" => "200",
                    "message" => "Chat history deleted successfully"
                ],
                "result" => [
                    "deletedContact" => $contactNumber
                ]
            ];
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
