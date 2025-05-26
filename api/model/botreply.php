<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

class BOTREPLYMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getBotReply($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "active") {
                    $result = $this->BotReplyActive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactive") {
                    $result = $this->BotReplyDeactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "triggerdropdown") {
                    $result = $this->getTriggerTypeDropdown($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] === 'create') {
                    $result = $this->createBotReply($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'duplicatebotreply') {
                    $result = $this->duplicateBotReply($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    $result = $this->getBotReplyDetails($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'mediaupload') {
                    $result = $this->BotMediaUpload($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    $result = $this->updateBotReply($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    $result = $this->deleteBotReply($data);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            default:
                $result = $this->handle_error();
                return $result;
                break;
        }
    }
    // Initiate db connection
    private function dbConnect()
    {
        $conn = new DBCONNECTION();
        $db = $conn->connect();
        return $db;
    }

    /**
     * Function is to get the for particular record
     *
     * @param array $data
     * @return multitype:
     */

    public function getBotReplyDetails($data, $loginData)
    {
        try {
            $responseArray = ''; // Initializing response variable
            $db = $this->dbConnect();


            // Check if pageIndex and dataLength are not empty
            if ($data['pageIndex'] === "") {
                throw new Exception("PageIndex should not be empty!");
            }
            if ($data['dataLength'] == "") {
                throw new Exception("dataLength should not be empty!");
            }

            $start_index = $data['pageIndex'] * $data['dataLength'];
            $end_index = $data['dataLength'];

            //count for total records
            $queryCount = "SELECT COUNT(*) AS total FROM cmp_bot_replies WHERE status = 1 AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = " . $loginData['user_id'] . ")";
            $resultCount = $db->query($queryCount);
            $rowCount = $resultCount->fetch_assoc();
            $totalRecordCount = $rowCount['total'];

            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT mbt.name AS triggerName, br.*
                    FROM cmp_bot_replies AS br
                    LEFT JOIN cmp_bot_trigger_type AS mbt ON mbt.id = br.trigger_type_id
             WHERE  br.status = 1  AND br.vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = " . $loginData['user_id'] . ")
                 ORDER BY br.id DESC 
                 LIMIT $start_index, $end_index";

            //   print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);

            $BotReply = [];
            if ($row_cnt > 0) {
                while ($row = $result->fetch_assoc()) {
                    $BotReply[] = array(
                        "id" => $row['id'],
                        "name" => $row['name'],
                        "intent" => json_decode($row['intent'], true),
                        "message_type" => $row['message_type'],
                        "message_body" => json_decode($row['message_body'], true),
                        "trigger" => array(
                            "type" => $row['trigger_type_id'],
                            "name" => $row['triggerName'], // Corrected from 'tiggerName'
                        ),
                        "created_by" => $row['created_by'],
                        "created_date" => $row['created_date'],
                        "updated_by" => $row['updated_by'],
                        "updated_date" => $row['updated_date'],
                        "active_status" => $row['active_status'],
                    );
                }
            }

            // Construct the final response array
            $responseArray = array(
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $totalRecordCount,
                "BotReplyData" => $BotReply,
            );

            // Prepare the result array with response status
            if (!empty($BotReply)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Bot reply details fetched successfully",
                    ),
                    "result" => $responseArray,
                );
            } else {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "No data found...",
                    ),
                );
            }

            // Return the result array
            return $resultArray;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }



    /**
     * Function is to get the for particular record
     *
     * @param array $data
     * @return multitype:
     */
    public function getBotReply($data, $loginData)
    {
        try {
            $id = $data[2];
            if (empty($id)) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $sql = "SELECT mbt.name AS triggerName, br.*
                    FROM cmp_bot_replies AS br
                    LEFT JOIN cmp_bot_trigger_type AS mbt ON mbt.id = br.trigger_type_id
             WHERE br.id = $id AND br.status = 1  AND br.vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = " . $loginData['user_id'] . ")";
            // print_r($sql);exit;
            // Execute the query

            $result = $db->query($sql);

            // Check if Store exists
            if ($result->num_rows > 0) {
                $BotReply = array();


                while ($row = $result->fetch_assoc()) {
                    $BotReply = array(
                        "id" => $row['id'],
                        "name" => $row['name'],
                        "intent" => json_decode($row['intent'], true),
                        "message_type" => $row['message_type'],
                        "message_body" => json_decode($row['message_body'], true),
                        "trigger" => array(  // Change this line to use array()
                            "type" => $row['trigger_type_id'],
                            "name" => $row['triggerName'],
                        ),
                        "created_by" => $row['created_by'],
                        "created_date" => $row['created_date'],
                        "updated_by" => $row['updated_by'],
                        "updated_date" => $row['updated_date'],
                        "active_status" => $row['active_status'],
                    );
                }
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Bot Reply detail fetched successfully",
                    ),
                    "result" => $BotReply
                );
            } else {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "Bot Reply not found",
                    )
                );
            }

            // Close the database connection
            $db->close();

            // Return the result array
            return $resultArray;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => $e->getMessage(),
                )
            );
        }
    }
    public function BotMediaUpload($loginData)
    {
        try {
            // Access the uploaded file
            if (!isset($_FILES['file']) || empty($_FILES['file']['name'])) {
                throw new Exception("No file uploaded or file name is empty.");
            }
    
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $tempPath = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileType = mime_content_type($tempPath); // More reliable type check
    
            $db = $this->dbConnect();
    
            // Define the base upload path
            $basePath = 'uploads/botmedia/';
            $uploadPath = '';
    
            // Prepare the file extension
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
            // Allowed types for validation
            $allowedImages = ['jpg', 'jpeg', 'png'];
            $allowedVideos = ['mp4', 'mov', 'avi', 'mkv'];
            $allowedDocuments = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
    
            // Determine file category by mime type and extension
            if (strpos($fileType, 'image') !== false && in_array($fileExt, $allowedImages)) {
                $uploadPath = $basePath . 'images/';
            } elseif (strpos($fileType, 'video') !== false && in_array($fileExt, $allowedVideos)) {
                $uploadPath = $basePath . 'videos/';
            } elseif (
                (strpos($fileType, 'application/pdf') !== false || 
                 strpos($fileType, 'application/msword') !== false || 
                 strpos($fileType, 'application/vnd.openxmlformats-officedocument') !== false) 
                 && in_array($fileExt, $allowedDocuments)
            ) {
                $uploadPath = $basePath . 'documents/';
            } else {
                throw new Exception("Unsupported or mismatched file type.");
            }
    
            // Create directory if not exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            // Validate file size (limit 10MB)
            if ($fileSize > 10000000) {
                throw new Exception("File is too large! Maximum allowed size is 10 MB.");
            }
    
            // Clean and rename file
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);
            $timeStamp = date('ymdHis');
            $cleanName = preg_replace('/\s+/', '', $baseName);
            $alteredName = $cleanName . "_" . $timeStamp . "." . $fileExt;
    
            $finalPath = $uploadPath . $alteredName;
    
            // Move the uploaded file
            if (!move_uploaded_file($tempPath, $finalPath)) {
                throw new Exception("Failed to move uploaded file.");
            }
    
            // Success Response
            $responseData = array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "File Uploaded Successfully",
                ),
                "result" => array(
                    "FileOriginalName" => $fileName,
                    "FileAlteredName" => $alteredName,
                    "Path" => $finalPath,
                ),
            );
    
            return $responseData;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }
    

    public function getTriggerTypeDropdown($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $queryService = "SELECT id, name FROM cmp_bot_trigger_type WHERE status = 1 AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = " . $loginData['user_id'] . ") ORDER BY name ASC";
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);

            $triggerTypeDrop = array();
            if ($row_cnt > 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $triggerTypeDrop[] = $row;
                }
            }

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Trigger Type Dropdown details fetched successfully",
                ),
                "result" => array(
                    "totalRecordCount" => $row_cnt,
                    "TriggerTypeDataDropDown" => $triggerTypeDrop,
                ),
            );
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => "Error: " . $e->getMessage(),
                ),
            );
        }
    }


    /**
     * Post/Add tenant
     *
     * @param array $data
     * @return multitype:string
     */
    public function createBotReply($data, $loginData)
    {
        $resultArray = array();
        try {
            $db = $this->dbConnect();
            $botAction = $data['botAction'];

            // Validate required input
            $validationData = array(
                "Name" => $botAction['botName'],
                "Intent" => json_encode($botAction['intents']),
                "Message Body" => json_encode($botAction['responses']),
            );
            $this->validateInputDetails($validationData);

            $user_id = $loginData['user_id'];
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id";
            $result = $db->query($sql);
            $vendor_id = $result->fetch_assoc()['vendor_id'];
            if (empty($vendor_id)) {
                throw new Exception("Vendor ID not found for user ID: $user_id");
            }

            // Check if bot name already exists
            $sql = "SELECT id FROM cmp_bot_replies WHERE name = '" . $botAction['botName'] . "' AND Status=1 AND vendor_id = " . $vendor_id;
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
                throw new Exception("Bot Name already exists");
            }

            // Validate trigger type
            $triggerTypeName = $botAction['trigger']['type'];
            $botReplyQuery = "SELECT id FROM cmp_bot_trigger_type WHERE name = '" . $triggerTypeName . "' AND vendor_id=".$vendor_id." AND status = 1";
            $botReplyResult = $db->query($botReplyQuery);
            if ($botReplyResult->num_rows === 0) {
                throw new Exception("Invalid trigger type: " . $triggerTypeName);
            }
            $triggerType = $botReplyResult->fetch_assoc()['id'];

            // Extract and validate message type
            $response = $botAction['responses'][0];
            $mainType = $response['type'];
            $subType = $response['sub_type'] ?? ''; // For interactive messages
            $messageType = $mainType === 'interactive' && $subType ? "{$mainType}_{$subType}" : $mainType;

            $validTypes = ['text', 'image', 'video','document','interactive_reply', 'interactive_cta', 'interactive_list'];
            if (!in_array($messageType, $validTypes)) {
                throw new Exception("Invalid message type: " . $messageType);
            }

            // Type-specific validation
            switch ($mainType) {
                case 'text':
                    if (empty($response['text'])) {
                        throw new Exception("Text response cannot be empty.");
                    }
                    break;

                case 'image':
                case 'video':
                case 'document':
                    if (empty($response['url']) || empty($response['caption'])) {
                        throw new Exception("Image URL and caption are required.");
                    }
                    break;

                case 'interactive':
                    if (empty($response['url']) || empty($response['caption'])) {
                        throw new Exception("Interactive message must have image URL and caption.");
                    }

                    if ($subType === 'reply') {
                        if (empty($response['buttons']) || !is_array($response['buttons'])) {
                            throw new Exception("Reply buttons are required for reply type.");
                        }
                    } elseif ($subType === 'cta') {
                        if (empty($response['buttons']) || !is_array($response['buttons'])) {
                            throw new Exception("CTA buttons (with URLs) are required.");
                        }
                        foreach ($response['buttons'] as $btn) {
                            if (empty($btn['title']) || empty($btn['url'])) {
                                throw new Exception("Each CTA button must have a title and URL.");
                            }
                        }
                    } elseif ($subType === 'list') {
                        if (empty($response['listSections']) || !is_array($response['listSections'])) {
                            throw new Exception("List sections are required for list type.");
                        }
                        foreach ($response['listSections'] as $section) {
                            if (empty($section['title']) || empty($section['rows']) || !is_array($section['rows'])) {
                                throw new Exception("Each list section must have a title and rows.");
                            }
                            foreach ($section['rows'] as $row) {
                                if (empty($row['id']) || empty($row['title'])) {
                                    throw new Exception("Each row must have an ID and title.");
                                }
                            }
                        }
                    } else {
                        throw new Exception("Unsupported interactive sub_type: " . $subType);
                    }
                    break;
            }

            // Save the bot
            $name = $db->real_escape_string($botAction['botName']);
            $intent = $db->real_escape_string(json_encode($botAction['intents']));
            $messageBody = $db->real_escape_string(json_encode($botAction['responses']));

            $insertStoreQuery = "INSERT INTO cmp_bot_replies 
                (vendor_id, trigger_type_id, name, intent, message_type, message_body, created_by)
                VALUES 
                ('$vendor_id', '$triggerType', '$name', '$intent', '$messageType', '$messageBody', '$user_id')";

            if ($db->query($insertStoreQuery) === true) {
                $db->close();
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Bot Reply successfully created.",
                    ),
                );
            } else {
                throw new Exception("Error while inserting Bot Reply: " . $db->error);
            }
        } catch (Exception $e) {
            if (isset($db)) {
                $db->close();
            }
            $resultArray = array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
        return $resultArray;
    }
    public function duplicateBotReply($data, $loginData)
    {
        $resultArray = array();
        try {
            $db = $this->dbConnect();
    $botReplyId = $data['id'];
            // Validate input
            if (empty($botReplyId)) {
                throw new Exception("Bot Reply ID is required for duplication.");
            }
    
            $user_id = $loginData['user_id'];
    
            // Fetch the existing bot reply
            $fetchQuery = "SELECT * FROM cmp_bot_replies WHERE id = '$botReplyId'";
            $result = $db->query($fetchQuery);
    
            if ($result->num_rows === 0) {
                throw new Exception("Bot Reply not found for the given ID.");
            }
    
            $botData = $result->fetch_assoc();
    
            // Generate new name with random number
            $randomNumber = rand(1000, 9999);
            $newName = $botData['name'] . " " . $randomNumber;
    
            // Insert duplicated bot with new name
            $insertQuery = "INSERT INTO cmp_bot_replies 
                (vendor_id, trigger_type_id, name, intent, message_type, message_body, created_by)
                VALUES 
                (
                    '{$botData['vendor_id']}',
                    '{$botData['trigger_type_id']}',
                    '" . $db->real_escape_string($newName) . "',
                    '" . $db->real_escape_string($botData['intent']) . "',
                    '{$botData['message_type']}',
                    '" . $db->real_escape_string($botData['message_body']) . "',
                    '$user_id'
                )";
    
            if ($db->query($insertQuery) === true) {
                $db->close();
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Bot Reply duplicated successfully.",
                    ),
                );
            } else {
                throw new Exception("Error while duplicating Bot Reply: " . $db->error);
            }
        } catch (Exception $e) {
            if (isset($db)) {
                $db->close();
            }
            $resultArray = array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
        return $resultArray;
    }
    



    public function updateBotReply($data, $loginData)
    {
        $resultArray = array();
        try {
            $db = $this->dbConnect();
            $botAction = $data['botAction'];

            // Check if the Bot Reply ID exists and is active
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_bot_replies WHERE id = '{$botAction['id']}' AND status = 1";
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            if ($rowCount == 0) {
                throw new Exception("Bot Reply does not exist.");
            }

            // Check if bot name already exists for another entry
            $checkNameQuery = "SELECT COUNT(*) AS count FROM cmp_bot_replies WHERE name = '{$botAction['botName']}' AND id != '{$botAction['id']}' AND Status=1 AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = " . $loginData['user_id'] . ")";

            // print_r($checkNameQuery);exit;
            $result = $db->query($checkNameQuery);
            $count = $result->fetch_assoc()['count'];
            if ($count > 0) {
                throw new Exception("Bot Name already exists.");
            }

            // Validate required input
            $validationData = array(
                "Name" => $botAction['botName'],
                "Intent" => json_encode($botAction['intents']),
                "Message Body" => json_encode($botAction['responses']),
            );
            $this->validateInputDetails($validationData);

            // Validate trigger type
            $triggerTypeName = $botAction['trigger']['type'];
            $botReplyQuery = "SELECT id FROM cmp_bot_trigger_type WHERE name = '" . $triggerTypeName . "' AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = " . $loginData['user_id'] . ") AND status = 1";
            $botReplyResult = $db->query($botReplyQuery);
            if ($botReplyResult->num_rows === 0) {
                throw new Exception("Invalid trigger type: " . $triggerTypeName);
            }
            $triggerType = $botReplyResult->fetch_assoc()['id'];

            // Extract and validate message type
            $response = $botAction['responses'][0];
            $mainType = $response['type'];
            $subType = $response['sub_type'] ?? '';
            $messageType = $mainType === 'interactive' && $subType ? "{$mainType}_{$subType}" : $mainType;

            $validTypes = ['text', 'image','video','document', 'interactive_reply', 'interactive_cta', 'interactive_list'];
            if (!in_array($messageType, $validTypes)) {
                throw new Exception("Invalid message type: " . $messageType);
            }

            // Type-specific validation
            switch ($mainType) {
                case 'text':
                    if (empty($response['text'])) {
                        throw new Exception("Text response cannot be empty.");
                    }
                    break;
                case 'image':
                case 'video':
                case 'document':
                    if (empty($response['url']) || empty($response['caption'])) {
                        throw new Exception("Image URL and caption are required.");
                    }
                    break;
                case 'interactive':
                    if (empty($response['url']) || empty($response['caption'])) {
                        throw new Exception("Interactive message must have image URL and caption.");
                    }

                    if ($subType === 'reply') {
                        if (empty($response['buttons']) || !is_array($response['buttons'])) {
                            throw new Exception("Reply buttons are required for reply type.");
                        }
                    } elseif ($subType === 'cta') {
                        if (empty($response['buttons']) || !is_array($response['buttons'])) {
                            throw new Exception("CTA buttons (with URLs) are required.");
                        }
                        foreach ($response['buttons'] as $btn) {
                            if (empty($btn['title']) || empty($btn['url'])) {
                                throw new Exception("Each CTA button must have a title and URL.");
                            }
                        }
                    } elseif ($subType === 'list') {
                        if (empty($response['listSections']) || !is_array($response['listSections'])) {
                            throw new Exception("List sections are required for list type.");
                        }
                        foreach ($response['listSections'] as $section) {
                            if (empty($section['title']) || empty($section['rows']) || !is_array($section['rows'])) {
                                throw new Exception("Each list section must have a title and rows.");
                            }
                            foreach ($section['rows'] as $row) {
                                if (empty($row['id']) || empty($row['title'])) {
                                    throw new Exception("Each row must have an ID and title.");
                                }
                            }
                        }
                    } else {
                        throw new Exception("Unsupported interactive sub_type: " . $subType);
                    }
                    break;
            }

            // Final data preparation
            $user_id = $loginData['user_id'];
            $dateNow = date("Y-m-d H:i:s", strtotime('+4 hours 30 minutes'));

            $name = $db->real_escape_string($botAction['botName']);
            $intent = $db->real_escape_string(json_encode($botAction['intents']));
            $messageBody = $db->real_escape_string(json_encode($botAction['responses']));

            $updateQuery = "UPDATE cmp_bot_replies SET
            name = '$name',
            trigger_type_id = '$triggerType',
            intent = '$intent',
            message_type = '$messageType',
            message_body = '$messageBody',
            updated_by = '$user_id',
            updated_date = '$dateNow'";

            if (isset($data['activeStatus'])) {
                $updateQuery .= ", active_status = '{$data['activeStatus']}'";
            }

            $updateQuery .= " WHERE id = '{$botAction['id']}' AND status = 1
            AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '$user_id')";

            if ($db->query($updateQuery) === true) {
                $db->close();
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Bot Reply successfully updated.",
                    ),
                );
            } else {
                throw new Exception("Error while updating Bot Reply: " . $db->error);
            }
        } catch (Exception $e) {
            if (isset($db)) {
                $db->close();
            }
            $resultArray = array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }

        return $resultArray;
    }



    private function deleteBotReply($data)
    {
        try {

            $id = $data[2];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_bot_replies WHERE id = $id AND status=1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Bot Reply does not exist",
                    ),
                );
            }

            //update delete query

            $deleteQuery = "UPDATE cmp_bot_replies
            SET status = 0 
            WHERE id = " . $id . "";

            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Bot Reply details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete Bot Reply details, please try again later";
            }
            $resultArray = array(
                "apiStatus" => array(
                    "code" => $statusCode,
                    "message" => $statusMessage,
                ),
            );
            return $resultArray;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function BotReplyActive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_bot_replies WHERE id = $id AND status=1";

            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "User ID does not exist",
                    ),
                );
            }
            $ActiveQuery = "UPDATE cmp_bot_replies SET active_status = 1 WHERE status = 1 AND id = $id";

            if ($db->query($ActiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Bot Reply activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate Bot Reply, please try again later.";
            }
            $resultArray = array(
                "apiStatus" => array(
                    "code" => $statusCode,
                    "message" => $statusMessage,
                ),
            );
            return $resultArray;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function BotReplyDeactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }
            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_bot_replies WHERE id = $id AND active_status=1 AND status=1";
            // print_r($checkIdQuery);exit;

            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                $statusCode = "400";
                $statusMessage = "Bot Reply ID does not exist.";
                return array(
                    "apiStatus" => array(
                        "code" => $statusCode,
                        "message" => $statusMessage,
                    ),
                );
            }
            $deactiveQuery = "UPDATE cmp_bot_replies SET active_status = 0 WHERE status = 1 AND id = $id";

            if ($db->query($deactiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Bot Reply Deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to Deactivate Bot Reply, please try again later.";
            }
            $resultArray = array(
                "apiStatus" => array(
                    "code" => $statusCode,
                    "message" => $statusMessage,
                ),
            );
            return $resultArray;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    /**
     * Validate function for tenant create
     *
     * @param array $data
     * @throws Exception
     * @return multitype:string NULL
     */
    public function validateInputDetails($validationData)
    {
        foreach ($validationData as $key => $value) {
            if (empty($value) || trim($value) == "") {
                throw new Exception($key . " should not be empty!");
            }
        }
    }

    // private function getTotalCount($loginData)
    // {
    //     try {
    //         $db = $this->dbConnect();
    //         $sql = "SELECT COUNT(cs.id) as totalStores
    //     FROM cmp_store cs
    //     INNER JOIN cmp_vendor_store_mapping cvsm ON cs.id = cvsm.store_id
    //     INNER JOIN cmp_vendor_user_mapping cvum ON cvsm.vendor_id = cvum.vendor_id
    //     WHERE cs.status = 1 AND cvum.user_id = " . $loginData['user_id'];


    //         $result = $db->query($sql);
    //         $row = $result->fetch_assoc();

    //         return $row['totalStores'];
    //     } catch (Exception $e) {
    //         return array(
    //             "result" => "401",
    //             "message" => $e->getMessage(),
    //         );
    //     }
    // }

    // public function getStoredropdown($data, $loginData)
    // {
    //     try {
    //         $StoreDrop = array();
    //         $db = $this->dbConnect();

    //         // Get user_id from loginData
    //         $userId = $loginData['user_id'];

    //         // Query to get store details based on user_id -> vendor_id -> store_id
    //         $queryService = "SELECT cs.id, cs.store_name, cs.active_status
    //                      FROM cmp_store cs
    //                      INNER JOIN cmp_vendor_store_mapping cvsm ON cs.id = cvsm.store_id
    //                      INNER JOIN cmp_vendor_user_mapping cvum ON cvsm.vendor_id = cvum.vendor_id
    //                      WHERE cs.status = 1 
    //                      AND cs.active_status = 1
    //                      AND cvum.user_id = $userId"; // Directly using user_id

    //         $result = $db->query($queryService);

    //         if (!$result) {
    //             throw new Exception("Database Query Failed: " . $db->error);
    //         }

    //         $row_cnt = mysqli_num_rows($result);

    //         while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    //             $StoreDrop[] = $row;
    //         }


    //         $responseArray = array(
    //             "totalRecordCount" => $row_cnt,
    //             "StoreDataDropDown" => $StoreDrop,
    //         );

    //         return array(
    //             "apiStatus" => array(
    //                 "code" => "200",
    //                 "message" => "Store Dropdown details fetched successfully",
    //             ),
    //             "result" => $responseArray,
    //         );
    //     } catch (Exception $e) {
    //         return array(
    //             "apiStatus" => array(
    //                 "code" => "500",
    //                 "message" => "Error: " . $e->getMessage(),
    //             ),
    //         );
    //     }
    // }


    // Unautherized api request
    private function handle_error() {}
    /**
     * Function is to process the crud request
     *
     * @param array $request
     * @return array
     */
    public function processList($request, $token)
    {
        try {
            $responseData = $this->processMethod($request, $token);
            $result = $this->response($responseData);
            return $responseData;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }
}
