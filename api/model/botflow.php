<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

class BOTFLOWMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getBotFlow($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "active") {
                    $result = $this->BotFlowActive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactive") {
                    $result = $this->BotFlowDeactive($data, $loginData);
                    return $result;
                    // } elseif ($urlParam[1] == "triggerdropdown") {
                    //     $result = $this->getTriggerTypeDropdown($data, $loginData);
                    //     return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] === 'create') {
                    $result = $this->createBotFlow($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    $result = $this->getBotFlowDetails($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'duplicatebotflow') {
                    $result = $this->duplicateBotFlow($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    $result = $this->updateBotFlow($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    $result = $this->deleteBotFlow($data);
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

    public function getBotFlowDetails($data, $loginData)
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

            //total record count query
            $queryCount = "SELECT COUNT(*) as totalRecordCount FROM cmp_bot_trigger_type WHERE status = 1 AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = " . $loginData['user_id'] . ")";
            $resultCount = $db->query($queryCount);
            $rowCount = $resultCount->fetch_assoc();
            $totalRecordCount = $rowCount['totalRecordCount'];
            // Check if totalRecordCount is greater than 0
            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT *
                    FROM cmp_bot_trigger_type
             WHERE  status = 1  AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = " . $loginData['user_id'] . ")
                 ORDER BY id DESC 
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
                        "description" => $row['description'],
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
                "BotFlowData" => $BotReply,
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
    public function getBotFlow($data, $loginData)
    {
        try {
            $id = $data[2];
            if (empty($id)) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $sql = "SELECT *
                    FROM cmp_bot_trigger_type 
                  
             WHERE id = $id AND status = 1  AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = " . $loginData['user_id'] . ")";
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
                        "description" => $row['description'],
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
                        "message" => "Bot Flow detail fetched successfully",
                    ),
                    "result" => $BotReply
                );
            } else {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "Bot Flow not found",
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

    public function duplicateBotFlow($data, $loginData)
    {
        $resultArray = array();
        try {
            $db = $this->dbConnect();
            $botReplyId = $data['id'];
            // Validate input
            if (empty($botReplyId)) {
                throw new Exception("Bot Flow ID is required for duplication.");
            }

            $user_id = $loginData['user_id'];

            // Fetch the existing bot reply
            $fetchQuery = "SELECT * FROM cmp_bot_trigger_type WHERE id = '$botReplyId'";
            $result = $db->query($fetchQuery);
            if ($result->num_rows === 0) {
                throw new Exception("Bot Flow not found for the given ID.");
            }

            $botData = $result->fetch_assoc();

            // Generate new name with random number
            $randomNumber = rand(1000, 9999);
            $newName = $botData['name'] . " " . $randomNumber;
            $dateNow = date("Y-m-d H:i:s");
            // Insert duplicated bot with new name
            $insertQuery = "INSERT INTO cmp_bot_trigger_type
                (vendor_id, name, description, created_by, created_date)
                VALUES
                (
                   
                    '{$botData['vendor_id']}',
                    '" . $db->real_escape_string($newName) . "',
                    '" . $db->real_escape_string($botData['description']) . "',
                    '$user_id',
                    '$dateNow'
                )";

            if ($db->query($insertQuery) === true) {
                $db->close();
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Bot Flow duplicated successfully.",
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

    /**
     * Post/Add tenant
     *
     * @param array $data
     * @return multitype:string
     */
    public function createBotFlow($data, $loginData)
    {
        // print_r($data);exit;
        $resultArray = array();
        try {
            $db = $this->dbConnect();


            // Validate required input
            $validationData = array(
                "Name" => $data['name'],
                // "Description" => $data['description'],

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
            $sql = "SELECT id FROM cmp_bot_trigger_type WHERE name = '" . $data['name'] . "' AND vendor_id = " . $vendor_id;
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
                throw new Exception("Bot Flow already exists");
            }


            $dateNow = date("Y-m-d H:i:s");
            $insertStoreQuery = "INSERT INTO cmp_bot_trigger_type 
                (vendor_id, name, description, created_by,created_date)
                VALUES 
                ('$vendor_id', '" . $data['name'] . "', '" . $data['description'] . "', '$user_id','$dateNow')";

            if ($db->query($insertStoreQuery) === true) {
                $db->close();
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Bot flow successfully created.",
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

    public function updateBotFlow($data, $loginData)
    {
        $resultArray = array();
        try {
            $db = $this->dbConnect();

            // Check if the Bot Flow ID exists and is active
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_bot_trigger_type WHERE id = '{$data['id']}' AND status = 1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            if ($rowCount == 0) {
                throw new Exception("Bot Flow does not exist.");
            }

            // Check if bot name already exists for another entry
            $checkNameQuery = "SELECT COUNT(*) AS count FROM cmp_bot_trigger_type WHERE name = '{$data['name']}' AND id != '{$data['id']}' AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '{$loginData['user_id']}') AND status = 1";
            // print_r($checkNameQuery);exit;
            $result = $db->query($checkNameQuery);
            $count = $result->fetch_assoc()['count'];
            if ($count > 0) {
                throw new Exception("Bot Flow Name already exists.");
            }

            // Validate required input
            $validationData = array(
                "Name" => $data['name'],
                // "Description" => $data['description'],

            );
            $this->validateInputDetails($validationData);
            // Final data preparation
            $user_id = $loginData['user_id'];
            $dateNow = date("Y-m-d H:i:s");
            //    print_r($dateNow);exit;
            $updateQuery = "UPDATE cmp_bot_trigger_type SET
            name = '$data[name]',
            description = '$data[description]',
            updated_by = '$user_id',
            updated_date = '$dateNow'";

            if (isset($data['activeStatus'])) {
                $updateQuery .= ", active_status = '{$data['activeStatus']}'";
            }

            $updateQuery .= " WHERE id = '{$data['id']}' AND status = 1
            AND vendor_id = (SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '$user_id')";
            // print_r($updateQuery);exit;
            if ($db->query($updateQuery) === true) {
                $db->close();
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Bot Flow successfully updated.",
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

    private function deleteBotFlow($data)
    {
        try {

            $id = $data[2];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_bot_trigger_type WHERE id = $id AND status=1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Bot Flow does not exist",
                    ),
                );
            }
            //update delete query

            $deleteQuery = "UPDATE cmp_bot_trigger_type
            SET status = 0 
            WHERE id = " . $id . "";

            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Bot Flow details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete Bot Flow details, please try again later";
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

    public function BotFlowActive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_bot_trigger_type WHERE id = $id AND status=1";

            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Bot Flow does not exist",
                    ),
                );
            }
            $ActiveQuery = "UPDATE cmp_bot_trigger_type SET active_status = 1 WHERE status = 1 AND id = $id";

            if ($db->query($ActiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Bot Flow activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate Bot Flow, please try again later.";
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

    public function BotFlowDeactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }
            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_bot_trigger_type WHERE id = $id AND active_status=1 AND status=1";
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
            $deactiveQuery = "UPDATE cmp_bot_trigger_type SET active_status = 0 WHERE status = 1 AND id = $id";

            if ($db->query($deactiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Bot Flow Deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to Deactivate Bot Flow, please try again later.";
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
