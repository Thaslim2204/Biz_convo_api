<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";


class COMMONMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    // $result = $this->getGroup($data, $loginData);
                } elseif ($urlParam[1] === 'variabledropdown') {
                    $result = $this->getVariabledropdown($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "countrydropdown") {
                    $result = $this->getCountrydropdown($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "timezonedropdown") {
                    $result = $this->timeZonedropdown($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'getmyprofile') {
                    $result = $this->getmyprofile($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'vendordashboardcount') {
                    $result = $this->vendorDashboardCount($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'getmyprofile') {
                    $result = $this->getmyprofile($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                // return $result;
                break;
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] === 'myprofile') {
                    $result = $this->myprofileupdate($data, $loginData);
                    return $result;
                
                } elseif ($urlParam[1] === "changepassword") {
                    $result = $this->changepassword($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'groupbycontact') {
                    // $result = $this->getGroupByContactDetails($data, $loginData);
                    // return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    // $result = $this->updateGroup($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                // return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    // $result = $this->deleteGroup($data);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                // return $result;
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

    public function getCountrydropdown($data, $loginData)
    {
        try {
            $GroupData = array();
            $db = $this->dbConnect();

            // Get user_id from loginData
            $userId = $loginData['user_id'];

            $queryService = "SELECT id, name,iso_code,name_capitalized,iso3_code,iso_num_code,phone_code FROM cmp_mst_country  ";

            $result = $db->query($queryService);

            if (!$result) {
                throw new Exception("Database Query Failed: " . $db->error);
            }

            $row_cnt = mysqli_num_rows($result);

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $GroupData[] = $row;
            }
            $responseArray = array(
                "totalRecordCount" => $row_cnt,
                "CountryData" => $GroupData,
            );

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Country Dropdown details fetched successfully",
                ),
                "result" => $responseArray,
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
    public function timeZonedropdown($data, $loginData)
    {
        try {
            $GroupData = array();
            $db = $this->dbConnect();

            // Get user_id from loginData
            $userId = $loginData['user_id'];

            $queryService = "SELECT id, timezone_name,utc_offset,location_name FROM cmp_mst_timezone  ";

            $result = $db->query($queryService);

            if (!$result) {
                throw new Exception("Database Query Failed: " . $db->error);
            }

            $row_cnt = mysqli_num_rows($result);

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $GroupData[] = $row;
            }
            $responseArray = array(
                "totalRecordCount" => $row_cnt,
                "CountryData" => $GroupData,
            );

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Timezone Dropdown details fetched successfully",
                ),
                "result" => $responseArray,
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
    private function getmyprofile($data,$loginData)
{
    try {
        $db = $this->dbConnect();
// print_r($loginData);exit;
        // Get the user ID from loginData
        $userId = ($loginData['user_id']);

        // Check if the User ID exists and is active
        $checkIdQuery = "SELECT id,first_name,last_name,username,email,mobile,status FROM cmp_users WHERE id = '$userId' AND status = 1 AND active_status=1";
        // print_r($checkIdQuery);exit;
        $result = $db->query($checkIdQuery);

        if ($result === false || $result->num_rows === 0) {
            $db->close();
            return [
                "apiStatus" => [
                    "code" => "400",
                    "message" => "User not found or inactive",
                ],
            ];
        }

        // Fetch the user data
        $userData = $result->fetch_assoc();
        $db->close();

        return [
            "apiStatus" => [
                "code" => "200",
                "message" => "User profile retrieved successfully",
            ],
            "UserData" => $userData
        ];
    } catch (Exception $e) {
        return [
            "apiStatus" => [
                "code" => "401",
                "message" => $e->getMessage(),
            ],
        ];
    }
}


    private function myprofileupdate($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            // Restrict updates if the logged-in user is a super_admin
            if ($loginData['role_name'] === 'super_admin') {
                throw new Exception("Permission denied. Super Admin cannot update details.");
            }

            // Check if the User ID exists and is active
            $userId = $db->real_escape_string($loginData['user_id']);
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_users WHERE id = '$userId' AND status = 1";
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            if ($rowCount == 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "User does not exist",
                    ],
                ];
            }

            // Check if username or email already exists
            $userName = $db->real_escape_string($data['userName']);
            $email = $db->real_escape_string($data['emailId']);
            $userIdToUpdate = $db->real_escape_string($loginData['user_id']);

            $checkUserQuery = "SELECT COUNT(*) AS count FROM cmp_users 
                           WHERE (username = '$userName' OR email = '$email') 
                           AND id != '$userIdToUpdate'";
            $userResult = $db->query($checkUserQuery);
            $userCount = $userResult->fetch_assoc()['count'];

            if ($userCount > 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "Username or email already exists",
                    ],
                ];
            }

            $dateNow = date("Y-m-d H:i:s", strtotime('+4 hours 30 minutes'));

            // Initialize vendorUpdated as false
            $vendorUpdated = false;

            // Validate input fields
            $validationData = [
                "First Name" => $data['firstName'],
                "Last Name" => $data['lastName'],
                "Username" => $data['userName'],
                "Email" => $data['emailId'],
                "Phone" => $data['phone']
            ];
            $this->validateInputDetails($validationData);

            // Construct update query dynamically
            $updateFields = [];
            if (!empty($data['firstName'])) {
                $updateFields[] = "first_name = '{$db->real_escape_string($data['firstName'])}'";
            }
            if (!empty($data['lastName'])) {
                $updateFields[] = "last_name = '{$db->real_escape_string($data['lastName'])}'";
            }
            if (!empty($data['userName'])) {
                $updateFields[] = "username = '{$db->real_escape_string($data['userName'])}'";
            }
            if (!empty($data['emailId'])) {
                $updateFields[] = "email = '{$db->real_escape_string($data['emailId'])}'";
            }
            if (!empty($data['phone'])) {
                $updateFields[] = "mobile = '{$db->real_escape_string($data['phone'])}'";
            }
                // if (isset($data['activeStatus'])) {
                //     $updateFields[] = "active_status = '{$db->real_escape_string($data['activeStatus'])}'";
                // }

            // Only execute update if there are fields to update
            if (!empty($updateFields)) {
                $updateFields[] = "updated_by = '$userId'";
                $updateFields[] = "updated_date = '$dateNow'";
                $updateVendorQuery = "UPDATE cmp_users SET " . implode(", ", $updateFields) . " WHERE id = '$userId' AND status = 1";

                if ($db->query($updateVendorQuery) === false) {
                    $db->close();
                    return [
                        "apiStatus" => [
                            "code" => "500",
                            "message" => "Unable to update user details, please try again later",
                        ],
                    ];
                }
                $vendorUpdated = true;
            }

            $db->close();

            // Construct the response message
            $message = $vendorUpdated ? "User details updated successfully" : "No changes made to user details";

            return [
                "apiStatus" => [
                    "code" => "200",
                    "message" => $message,
                ],
            ];
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage(),
                ],
            ];
        }
    }

    public  function vendorDashboardCount($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $userId = $loginData['user_id'];
            $vendorId = $this->getVendorIdByUserId($loginData);

            // Query to get the dashboard count
            $query = "SELECT * FROM cmp_dashbroad_count_vendor WHERE vendor_id = '$vendorId'";
            $result = $db->query($query);

            if (!$result) {
                throw new Exception("Database query failed: " . $db->error);
            }

            $row = $result->fetch_assoc();
            $responseArray = array(
                "contactCount" => $row['contact_count'],
                "CampaignCount" => $row['campaign_count'],
                "WhatsappMessageCount" => $row['whatsapp_message_count'],
                "whatsappTemplateCount" => $row['whatsapp_template_count'],
              
            );
            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Dashboard count fetched successfully",
                ),
                "VendorDashCountData" => $responseArray,
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

    public function getVendorIdByUserId($loginData)
    {
        $db = $this->dbConnect(); // Ensure you have a working DB connection
        // print_r($loginData);exit;
        $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '" . $loginData['user_id'] . "'";
        $result = $db->query($sql);

        if (!$result) {
            throw new Exception("Database query failed: " . $db->error);
        }

        $row = $result->fetch_assoc();
        if (!$row || !isset($row['vendor_id'])) {
            throw new Exception("Vendor ID not found for user ID: '" . $loginData['user_id'] . "'");
        }

        return $row['vendor_id'];
    }


    public function getVariabledropdown($data, $loginData)
    {
        try {
            $GroupData = array();
            $db = $this->dbConnect();

            // Get user_id from loginData
            $userId = $loginData['user_id'];

            // Query to get Group details based on user_id -> vendor_id -> store_id
            $queryService = "SELECT id, variable_name FROM cmp_mst_variable WHERE status = 1 ";

            $result = $db->query($queryService);

            if (!$result) {
                throw new Exception("Database Query Failed: " . $db->error);
            }

            $row_cnt = mysqli_num_rows($result);

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $GroupData[] = $row;
            }


            $responseArray = array(
                "totalRecordCount" => $row_cnt,
                "VariableDataDropDown" => $GroupData,
            );

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Variable Dropdown details fetched successfully",
                ),
                "result" => $responseArray,
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


    private function changePassword($data, $loginData)
    {
        // echo"123";exit;
        try {
            if ($loginData['role_name'] === 'super_admin') {
                throw new Exception("Permission denied. Super Admin cannot update details.");
            }

            // Input validation
            $requiredFields = ['oldPassword', 'newPassword', 'confirmPassword'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Please enter $field.");
                }
            }
    
            if ($data['newPassword'] !== $data['confirmPassword']) {
                throw new Exception("New password and confirm password do not match");
            }
    
            // Connect to the database
            $db = $this->dbConnect();
    
            // Fetch user's current password
            $uid = $loginData['user_id'];
            $query = "SELECT password FROM cmp_users WHERE id = $uid";
            $result = $db->query($query);
    
            if (!$result || $result->num_rows == 0) {
                throw new Exception("User not found");
            }
    
            $row = $result->fetch_assoc();
            $hashedOldPassword = $row['password'];
            $oldpassword = hash('sha256', hash('sha256', $data['oldPassword']));
            
            // Verify old password
            if ($hashedOldPassword != $oldpassword) {
                throw new Exception("Incorrect old password");
            }
    
            // Hash new password
            $hashedNewPassword = hash('sha256', hash('sha256', $data['newPassword']));
            // print_r($hashedNewPassword);exit;
            // Update password
            $updateQuery = "UPDATE cmp_users SET password = '$hashedNewPassword' , updated_by='".$loginData['user_id']."' WHERE id = $uid ";
            // print_r($updateQuery);exit;
            if (!$db->query($updateQuery)) {
                throw new Exception("Failed to update password");
            }
    
            // Close database connection
            $db->close();
            $statusCode = "200";
            $statusMessage = "Password changed successfully.";
    
            // Success message
            return array(
                "apiStatus" => array(
                    "code" => $statusCode,
                    "message" => $statusMessage,
                ),
            );
    
        } catch (Exception $e) {
            // Error message
            return array(
                "apiStatus" => array(
                    "code" => 401,
                    "message" => $e->getMessage(),
                ),
            );
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
