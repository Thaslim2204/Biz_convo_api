<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";


class GROUPMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getGroup($data, $loginData);
                } elseif ($urlParam[1] === 'groupdropdown') {
                    $result = $this->getGroupdropdown($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "active") {
                    $result = $this->Groupactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactive") {
                    $result = $this->Groupdeactive($data, $loginData);
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
                    $result = $this->createGroup($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    if ($urlParam[2] === 'archive') {
                        $result = $this->getArchiveGroupDetails($data, $loginData);
                        return $result;
                    }
                    $result = $this->getGroupDetails($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'payloadStructure') {
                    // print_r($data);exit;
                    $result = $this->getpayloadstructure($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'groupbycontact') {
                    $result = $this->getGroupByContactDetails($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'selectdelete') {
                    $result = $this->selecteddataDelete($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'alldelete') {
                    $result = $this->AllDelete($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'groupselectdelete') {
                    $result = $this->selecteddatagroupDelete($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "activeachieve") {
                    $result = $this->Groupactiveachieve($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactiveachieve") {
                    $result = $this->Groupdeactiveachieve($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    $result = $this->updateGroup($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    $result = $this->deleteGroup($data);
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

    public function getGroupDetails($data, $loginData)
    {
        try {
            $responseArray = ''; // Initializing response variable
            $db = $this->dbConnect();
            $recordCount = $this->getTotalCount($loginData);

            // Check if pageIndex and dataLength are not empty
            if ($data['pageIndex'] === "") {
                throw new Exception("PageIndex should not be empty!");
            }
            if ($data['dataLength'] == "") {
                throw new Exception("dataLength should not be empty!");
            }

            $start_index = $data['pageIndex'] * $data['dataLength'];
            $end_index = $data['dataLength'];

            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT id,uid,vendor_id,group_name,description,active_status,status,created_by,created_date,updated_by,updated_date
                 FROM cmp_group_contact 
                 WHERE status = 1  AND active_status = 1 AND vendor_id = " . $this->getVendorIdByUserId($loginData) . "
                 ORDER BY id DESC 
                 LIMIT $start_index, $end_index";

            //   print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);

            $group = array(); // Initialize array to hold Store data
            if ($row_cnt > 0) {
                while ($row = $result->fetch_assoc()) {
                    $group[] = array(
                        "groupId" => $row['id'],
                        "groupUid" => $row['uid'],
                        "groupName" => $row['group_name'],
                        "description" => $row['description'],
                        "activeStatus" => $row['active_status'],
                        "createdBy" => $row['created_by'],
                        "createdDate" => $row['created_date'],
                        "updatedBy" => $row['updated_by'],
                        "updatedDate" => $row['updated_date'],
                        "status" => $row['status']
                    );
                }
            }

            // Construct the final response array
            $responseArray = array(
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $recordCount,
                'GroupData' => array_values($group), // Reset array keys
            );

            // Check if Store data exists
            if (!empty($group)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Group details fetched successfully",
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
    public function getGroupByContactDetails($data, $loginData)
    {
        try {
            $responseArray = []; // Initialize response variable
            $db = $this->dbConnect();

            // if ($data['pageIndex'] === "") {
            //     throw new Exception("PageIndex should not be empty!");
            // }
            // if ($data['dataLength'] == "") {
            //     throw new Exception("dataLength should not be empty!");
            // }
            if (empty($data['groupName'])) {
                throw new Exception("groupName should not be empty!");
            }

            $groupName = $db->real_escape_string($data['groupName']);
            // $start_index = $data['pageIndex'] * $data['dataLength'];
            // $end_index = $data['dataLength'];
            // Get total record count
            $countQuery = "SELECT COUNT(*) AS totalCount FROM cmp_group_contact_mapping AS gcm
        LEFT JOIN cmp_group_contact AS gc ON gc.id = gcm.group_id
        LEFT JOIN cmp_contact AS c ON c.id = gcm.contact_id
        WHERE gc.status = 1 
        AND gcm.status = 1
        AND c.status = 1
        AND gc.active_status = 1  
        AND gc.group_name = '$groupName' 
        AND gc.vendor_id = " . $this->getVendorIdByUserId($loginData);

            $countResult = $db->query($countQuery);
            $recordCount = ($countResult && $countResult->num_rows > 0) ? $countResult->fetch_assoc()['totalCount'] : 0;

            // Fetch group details with user-provided group name
            $queryService = "SELECT 
            gc.id AS groupId,
            gc.group_name AS groupName,
            gc.active_status AS activeStatus,
            c.id AS contactId,
            c.store_id AS storeId,
            s.store_name AS storeName,
            c.first_name AS firstName,
            c.last_name AS lastName,
            c.mobile,
            c.email,
            c.country,
            c.language_code,
            c.status,
            c.created_by AS createdBy,
            c.created_date AS createdDate,
            c.date_of_birth AS DOB,
            c.anniversary,
            c.loyality,
            c.address
        FROM cmp_group_contact_mapping AS gcm
        LEFT JOIN cmp_group_contact AS gc ON gc.id = gcm.group_id
        LEFT JOIN cmp_contact AS c ON c.id = gcm.contact_id
        LEFT JOIN cmp_store AS s ON s.id = c.store_id
        
        WHERE gc.status = 1 
            AND gcm.status = 1
            AND c.status = 1
            AND gc.active_status = 1  
            AND gc.group_name = '$groupName' 
            AND gc.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
        ORDER BY gc.id DESC 
   
        ";
            // print_r($queryService);exit;
            //      LIMIT $start_index, $end_index

            $result = $db->query($queryService);
            $groupData = [];

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $groupId = $row['groupId'];

                    // If the group is not in the array, initialize it
                    if (!isset($groupData[$groupId])) {
                        $groupData[$groupId] = [
                            "groupId" => $row['groupId'],
                            "groupName" => $row['groupName'],
                            "activeStatus" => $row['activeStatus'],
                            "contacts" => []
                        ];
                    }

                    // Add contact details
                    $groupData[$groupId]['contacts'][] = [
                        "id" => $row['contactId'],
                        "storeId" => $row['storeId'],
                        "storeName" => $row['storeName'],
                        "firstName" => $row['firstName'],
                        "lastName" => $row['lastName'],
                        "mobile" => $row['mobile'],
                        "email" => $row['email'],
                        "country" => $row['country'],
                        "language" => $row['language_code'],
                        "status" => $row['status'],
                        // "createdBy" => $row['createdBy'],
                        // "createdDate" => $row['createdDate'],
                        "otherInformation" => [
                            "DOB" => $row['DOB'] ?? "0000-00-00",
                            "anniversary" => $row['anniversary'] ?? "0000-00-00",
                            "loyality" => $row['loyality'] ?? "",
                            "address" => $row['address'] ?? ""
                        ]
                    ];
                }
            }

            // Construct the final response
            if (!empty($groupData)) {
                $responseArray = [
                    // "pageIndex" => $data['pageIndex'],
                    // "dataLength" => $data['dataLength'],
                    "totalRecordCount" => $recordCount,
                    "GroupData" => reset($groupData) // Assuming only one group per request
                ];

                $resultArray = [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Group details fetched successfully",
                    ],
                    "result" => $responseArray,
                ];
            } else {
                $resultArray = [
                    "apiStatus" => [
                        "code" => "404",
                        "message" => "No data found...",
                    ],
                ];
            }

            return $resultArray;
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "500",
                    "message" => $e->getMessage(),
                ],
            ];
        }
    }

    public function getArchiveGroupDetails($data, $loginData)
    {
        try {
            $responseArray = ''; // Initializing response variable
            $db = $this->dbConnect();
            $recordCount = $this->getArchiveTotalCount($loginData);

            // Check if pageIndex and dataLength are not empty
            if ($data['pageIndex'] === "") {
                throw new Exception("PageIndex should not be empty!");
            }
            if ($data['dataLength'] == "") {
                throw new Exception("dataLength should not be empty!");
            }

            $start_index = $data['pageIndex'] * $data['dataLength'];
            $end_index = $data['dataLength'];

            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT id,uid,vendor_id,group_name,description,active_status,status,created_by,created_date,updated_by,updated_date
                 FROM cmp_group_contact 
                 WHERE status = 1 AND active_status=0 AND vendor_id = " . $this->getVendorIdByUserId($loginData) . "
                 ORDER BY id DESC 
                 LIMIT $start_index, $end_index";

            //   print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);

            $group = array(); // Initialize array to hold Store data
            if ($row_cnt > 0) {
                while ($row = $result->fetch_assoc()) {
                    $group[] = array(
                        "groupId" => $row['id'],
                        "groupUid" => $row['uid'],
                        "groupName" => $row['group_name'],
                        "description" => $row['description'],
                        "activeStatus" => $row['active_status'],
                        "createdBy" => $row['created_by'],
                        "createdDate" => $row['created_date'],
                        "updatedBy" => $row['updated_by'],
                        "updatedDate" => $row['updated_date'],
                        "status" => $row['status']
                    );
                }
            }

            // Construct the final response array
            $responseArray = array(
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $recordCount,
                'GroupData' => array_values($group), // Reset array keys
            );

            // Check if Store data exists
            if (!empty($group)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Group details fetched successfully",
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
    public function getGroup($data, $loginData)
    {
        try {
            $id = $data[2];
            if (empty($id)) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $sql = "SELECT id,uid,group_name,description,active_status,status,created_by,created_date,updated_by,updated_date
                      FROM cmp_group_contact cs
                      WHERE cs.id = $id AND cs.status = 1 AND vendor_id = " . $this->getVendorIdByUserId($loginData) . " 
                      ";


            $result = $db->query($sql);

            // Check if Store exists
            if ($result->num_rows > 0) {
                $group = array();
                while ($row = $result->fetch_assoc()) {
                    $group = array(
                        "groupId" => $row['id'],
                        "groupUid" => $row['uid'],
                        "groupName" => $row['group_name'],
                        "description" => $row['description'],
                        "activeStatus" => $row['active_status'],
                        "createdBy" => $row['created_by'],
                        "createdDate" => $row['created_date'],
                        "updatedBy" => $row['updated_by'],
                        "updatedDate" => $row['updated_date'],
                        "status" => $row['status']

                    );
                }
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Group detail fetched successfully",
                    ),
                    "result" => $group
                );
            } else {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "No data found.",
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



    /**
     * Post/Add tenant
     *
     * @param array $data
     * @return multitype:string
     */
    public function createGroup($data, $loginData)
    {
        // Initialize result array
        $resultArray = array();
        try {
            $db = $this->dbConnect();


            // Validate input data
            $validationData = array(
                "GroupName" => $data['groupName'],
                // "Description"      => $data['description'],
            );
            $this->validateInputDetails($validationData);

            //Get the Store id from the login data
            $vendor_id = $this->getVendorIdByUserId($loginData);

            // Check if the user already exists
            $sql = "SELECT id FROM cmp_group_contact WHERE group_name = '" . $data['groupName'] . "' AND status = 1 AND created_by = " . $loginData['user_id'] . "";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
                throw new Exception("Group already exist");
            }

            $uid = bin2hex(random_bytes(8));

            // Insert into cmp_store
            $insertGroupQuery = "INSERT INTO cmp_group_contact (uid, group_name, description, vendor_id, created_by)
            VALUES ('$uid', '" . $data['groupName'] . "', '" . $data['description'] . "', '$vendor_id', '" . $loginData['user_id'] . "')";
            // print_r($insertGroupQuery);exit;
            if ($db->query($insertGroupQuery) === true) {

                $db->close();

                $resultArray = array(
                    "apiStatus" => array(
                        "code"    => "200",
                        "message" => "Group details successfully created.",
                    ),
                );
            } else {
                throw new Exception("Error occurred while inserting Group: " . $db->error);
            }
        } catch (Exception $e) {
            if (isset($db)) {
                $db->close();
            }
            $resultArray = array(
                "apiStatus" => array(
                    "code"    => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
        return $resultArray;
    }


    private function updateGroup($data, $loginData)
    {
        try {
            $db = $this->dbConnect();

            // Sanitize input data
            $groupId = $data['id'];
            $groupName = $data['groupName'];
            $description = $data['description'];
            $userId = $loginData['user_id'];
            $vendorId = $this->getVendorIdByUserId($loginData);
            $activeStatus = isset($data['activeStatus']) ? (int) $data['activeStatus'] : null;

            // Check if the Group ID exists and is active
            $checkIdQuery = "SELECT group_name, description, active_status FROM cmp_group_contact WHERE id = '$groupId' AND status = 1";
            $result = $db->query($checkIdQuery);
            $existingData = $result->fetch_assoc();

            if (!$existingData) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "Group does not exist",
                    ],
                ];
            }

            // Check if group name already exists
            $checkUserQuery = "SELECT COUNT(*) AS count FROM cmp_group_contact WHERE group_name = '$groupName' AND id != '$groupId'";
            $userResult = $db->query($checkUserQuery);
            $userCount = $userResult->fetch_assoc()['count'];

            if ($userCount > 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "Group already exists",
                    ],
                ];
            }

            // Get current time in Asia/Kolkata timezone
            $dateNow = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
            $dateNowFormatted = $dateNow->format('Y-m-d H:i:s');

            // Check if there are actual changes
            $vendorUpdated = false;
            if (
                $existingData['group_name'] !== $groupName ||
                $existingData['description'] !== $description ||
                ($activeStatus !== null && $existingData['active_status'] != $activeStatus)
            ) {
                // Validate input
                $validationData = [
                    "id" => $groupId,
                    "Group Name" => $groupName,
                    "description" => $description
                ];
                $this->validateInputDetails($validationData);

                // Update group details in the database
                $updateVendorQuery = "UPDATE cmp_group_contact SET 
                    group_name = '$groupName',
                    description = '$description',
                    updated_by = '$userId',
                    updated_date = '$dateNowFormatted'";

                if ($activeStatus !== null) {
                    $updateVendorQuery .= ", active_status = '$activeStatus'";
                }

                $updateVendorQuery .= " WHERE id = '$groupId' AND vendor_id = '$vendorId' AND status = 1";

                // Execute the query
                if ($db->query($updateVendorQuery) === false) {
                    $db->close();
                    return [
                        "apiStatus" => [
                            "code" => "500",
                            "message" => "Unable to update Group details, please try again later",
                        ],
                    ];
                }

                // Check if any rows were actually updated
                if ($db->affected_rows > 0) {
                    $vendorUpdated = true;
                }
            }

            // Close the database connection
            $db->close();

            // Construct the response message
            $message = $vendorUpdated ? "Group details updated successfully" : "No changes made to Group details";

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



    private function deleteGroup($data)
    {
        try {

            $id = $data[2];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_group_contact WHERE id = $id AND status=1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Group does not exist",
                    ),
                );
            }

            //update delete query

            $deleteQuery = "UPDATE cmp_group_contact
            SET status = 0 
            WHERE id = " . $id . "";

            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Group details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete Group details, please try again later";
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


    private function selecteddataDelete($data, $loginData)
    {
        try {
            $ids = $data['deleteId'];

            if (empty($ids) || !is_array($ids)) {
                throw new Exception("Invalid input. Please provide an array of IDs.");
            }

            $db = $this->dbConnect();
            $deleted = [];
            $failed = [];

            foreach ($ids as $id) {
                if (!is_numeric($id)) {
                    $failed[] = [
                        'id' => $id,
                        'status' => 400,
                        'message' => 'Invalid ID format'
                    ];
                    continue;
                }

                // Check if group exists and is active
                $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_group_contact WHERE id = $id AND status=1 AND created_by ='" . $loginData['user_id'] . "'";
                $result = $db->query($checkIdQuery);
                $rowCount = $result->fetch_assoc()['count'];

                if ($rowCount == 0) {
                    $failed[] = [
                        'id' => $id,
                        'status' => 400,
                        'message' => 'Group does not exist or is already deleted'
                    ];
                    continue;
                }

                // 🔹 Step 1: Fetch contact IDs from mapping table
                $contactIds = [];
                $mapQuery = "SELECT contact_id FROM cmp_group_contact_mapping WHERE group_id = $id";
                $mapResult = $db->query($mapQuery);
                while ($row = $mapResult->fetch_assoc()) {
                    $contactIds[] = $row['contact_id'];
                }

                // 🔹 Step 2: Update contact status in cmp_contact
                if (!empty($contactIds)) {
                    $contactIdStr = implode(',', array_map('intval', $contactIds)); // sanitize
                    $updateContactQuery = "UPDATE cmp_contact SET status = 0 WHERE id IN ($contactIdStr)";
                    $db->query($updateContactQuery);
                }
                if (!empty($contactIds)) {
                    $contactIdStr = implode(',', array_map('intval', $contactIds)); // sanitize
                    $updateContactmappingQuery = "UPDATE cmp_group_contact_mapping SET status = 0 WHERE contact_id IN ($contactIdStr)";
                    $db->query($updateContactmappingQuery);
                }

                // 🔹 Step 3: Update group status
                $deleteQuery = "UPDATE cmp_group_contact SET status = 0 , active_status= 0 WHERE id = $id ";
                if ($db->query($deleteQuery) === true) {
                    $deleted[] = [
                        'id' => $id,
                        'status' => 200,
                        'message' => 'Group and related contacts deleted successfully'
                    ];
                } else {
                    $failed[] = [
                        'id' => $id,
                        'status' => 500,
                        'message' => 'Unable to delete Group details, please try again later'
                    ];
                }
            }

            $db->close();

            return [
                'apiStatus' => [
                    'code' => count($failed) > 0 ? 400 : 200,
                    'message' => count($failed) > 0 ? 'Some deletions failed' : 'All deletions successful'
                ],
                'deleted' => $deleted,
                'failed' => $failed
            ];
        } catch (Exception $e) {
            return [
                'apiStatus' => [
                    'code' => 500,
                    'message' => $e->getMessage()
                ]
            ];
        }
    }


    private function AllDelete($data, $loginData) 
    {
        try {
            $db = $this->dbConnect();
            $userId = $loginData['user_id'];
    
            $db->query("UPDATE cmp_contact SET status = 0 WHERE created_by = $userId");
    
            return [
                'apiStatus' => [
                    'code' => 200,
                    'message' => 'All contacts marked as deleted successfully.'
                ]
            ];
        } catch (Exception $e) {
            return [
                'apiStatus' => [
                    'code' => 500,
                    'message' => $e->getMessage()
                ]
            ];
        }
    }
    

    //Group ative and deactive
    public function Groupactiveachieve($data, $loginData)
    {
        try {
            // Step 1: Validate input
            if (!isset($data['id']) || empty($data['id'])) {
                throw new Exception("Bad request. 'id' field is required.");
            }

            // Normalize to array and sanitize IDs
            $ids = is_array($data['id']) ? $data['id'] : [$data['id']];
            $ids = array_map('intval', $ids);

            $db = $this->dbConnect();
            $idList = implode(',', $ids);

            // Step 2: Check if group contact IDs exist and are active (status = 1)
            $checkQuery = "SELECT id FROM cmp_group_contact WHERE id IN ($idList) AND status = 1";
            $result = $db->query($checkQuery);

            $foundIds = [];
            while ($row = $result->fetch_assoc()) {
                $foundIds[] = $row['id'];
            }

            $missingIds = array_diff($ids, $foundIds);

            // Step 3: If some IDs are invalid, return error
            if (!empty($missingIds)) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "These group contact IDs do not exist or are inactive: " . implode(', ', $missingIds),
                    ),
                );
            }

            // Step 4: Activate valid group contacts
            $activateQuery = "UPDATE cmp_group_contact SET active_status = 1 WHERE id IN ($idList) AND status = 1";
            if ($db->query($activateQuery) === true) {
                $statusCode = "200";
                $statusMessage = "Group contact(s) activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate group contact(s), please try again later.";
            }

            $db->close();

            return array(
                "apiStatus" => array(
                    "code" => $statusCode,
                    "message" => $statusMessage,
                ),
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function Groupdeactiveachieve($data, $loginData)
    {
        try {
            // Step 1: Validate input
            if (!isset($data['id']) || empty($data['id'])) {
                throw new Exception("Bad request. 'id' field is required.");
            }

            // Normalize to array and sanitize IDs
            $ids = is_array($data['id']) ? $data['id'] : [$data['id']];
            $ids = array_map('intval', $ids);

            $db = $this->dbConnect();
            $idList = implode(',', $ids);

            // Step 2: Check if group contact IDs exist AND are active
            $checkQuery = "SELECT id FROM cmp_group_contact WHERE id IN ($idList) AND active_status = 1 AND status = 1";
            $result = $db->query($checkQuery);

            $foundIds = [];
            while ($row = $result->fetch_assoc()) {
                $foundIds[] = $row['id'];
            }

            // Step 3: If any ID is missing or inactive, return error
            $missingIds = array_diff($ids, $foundIds);
            if (!empty($missingIds)) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "These group contact IDs do not exist or are already deactivated: " . implode(', ', $missingIds),
                    ),
                );
            }

            // Step 4: Deactivate valid group contacts
            $deactivateQuery = "UPDATE cmp_group_contact SET active_status = 0 WHERE id IN ($idList) AND status = 1";
            if ($db->query($deactivateQuery) === true) {
                $statusCode = "200";
                $statusMessage = "Group contact(s) deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to deactivate group contact(s), please try again later.";
            }

            $db->close();

            return array(
                "apiStatus" => array(
                    "code" => $statusCode,
                    "message" => $statusMessage,
                ),
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    //Group active and deactive
    public function Groupactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_group_contact WHERE id = $id AND status=1";

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
            $ActiveQuery = "UPDATE cmp_group_contact SET active_status = 1 WHERE status = 1 AND id = $id";

            if ($db->query($ActiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Group activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate Group, please try again later.";
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

    public function Groupdeactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }
            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_group_contact WHERE id = $id AND active_status=1 AND status=1";
            // print_r($checkIdQuery);exit;

            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                $statusCode = "400";
                $statusMessage = "User ID does not exist.";
                return array(
                    "apiStatus" => array(
                        "code" => $statusCode,
                        "message" => $statusMessage,
                    ),
                );
            }
            $deactiveQuery = "UPDATE cmp_group_contact SET active_status = 0 WHERE status = 1 AND id = $id";

            if ($db->query($deactiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Group Deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to Deactivate Group, please try again later.";
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

    private function getTotalCount($loginData)
    {

        try {
            $db = $this->dbConnect();
            // get the vendor id from the login data
            $vendor_id = $this->getVendorIdByUserId($loginData);
            $sql = "SELECT COUNT(cs.id) as totalgroup
        FROM cmp_group_contact cs
               WHERE cs.status = 1 AND active_status=1 AND vendor_id = $vendor_id";

            // print_r($sql);exit; 
            $result = $db->query($sql);
            $row = $result->fetch_assoc();

            return $row['totalgroup'];
        } catch (Exception $e) {
            return array(
                "result" => "401",
                "message" => $e->getMessage(),
            );
        }
    }
    private function getArchiveTotalCount($loginData)
    {

        try {
            $db = $this->dbConnect();
            // get the vendor id from the login data
            $vendor_id = $this->getVendorIdByUserId($loginData);
            $sql = "SELECT COUNT(cs.id) as totalgroup
        FROM cmp_group_contact cs
               WHERE cs.status = 1 AND active_status=0 AND vendor_id = $vendor_id";

            // print_r($sql);exit; 
            $result = $db->query($sql);
            $row = $result->fetch_assoc();

            return $row['totalgroup'];
        } catch (Exception $e) {
            return array(
                "result" => "401",
                "message" => $e->getMessage(),
            );
        }
    }

    public function getGroupdropdown($data, $loginData)
    {
        try {
            $GroupData = array();
            $db = $this->dbConnect();

            // Get user_id from loginData
            $userId = $loginData['user_id'];

            // Query to get Group details based on user_id -> vendor_id -> store_id
            $queryService = "SELECT id, group_name FROM cmp_group_contact WHERE status = 1 AND vendor_id = " . $this->getVendorIdByUserId($loginData) . " ORDER BY id DESC";

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
                "GroupDataDropDown" => $GroupData,
            );

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Group Dropdown details fetched successfully",
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

    //group delete in multiple

    private function selecteddatagroupDelete($data, $loginData)
    {
        try {
            $ids = $data['deleteId'];

            // print_r($data);exit;
            // Check if IDs are provided and valid
            if (empty($ids) || !is_array($ids)) {
                throw new Exception("Invalid input. Please provide an array of IDs.");
            }

            $db = $this->dbConnect();
            $deleted = [];
            $failed = [];

            foreach ($ids as $id) {
                // Validate ID
                if (!is_numeric($id)) {
                    $failed[] = [
                        'id' => $id,
                        'status' => 400,
                        'message' => 'Invalid ID format'
                    ];
                    continue;
                }

                // Check if the ID exists and is active
                $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_group_contact WHERE id = $id AND status=1 AND created_by ='" . $loginData['user_id'] . "'";
                $result = $db->query($checkIdQuery);
                $rowCount = $result->fetch_assoc()['count'];

                if ($rowCount == 0) {
                    $failed[] = [
                        'id' => $id,
                        'status' => 400,
                        'message' => 'Group does not exist or is already deleted'
                    ];
                    continue;
                }

                // Update delete query to set status to 0
                $deleteQuery = "UPDATE cmp_group_contact SET status = 0 , active_status= 0 WHERE id = $id ";
                if ($db->query($deleteQuery) === true) {
                    $deleted[] = [
                        'id' => $id,
                        'status' => 200,
                        'message' => 'Group details deleted successfully'
                    ];
                } else {
                    $failed[] = [
                        'id' => $id,
                        'status' => 500,
                        'message' => 'Unable to delete Group details, please try again later'
                    ];
                }
            }

            $db->close();

            return [
                'apiStatus' => [
                    'code' => count($failed) > 0 ? 400 : 200,
                    'message' => count($failed) > 0 ? 'Some deletions failed' : 'All deletions successful'
                ],
                'deleted' => $deleted,
                'failed' => $failed
            ];
        } catch (Exception $e) {
            return [
                'apiStatus' => [
                    'code' => 500,
                    'message' => $e->getMessage()
                ]
            ];
        }
    }



    public function getpayloadstructure($data, $loginData)
    {
        // Initialize result array
        $resultArray = array();
        try {
            $db = $this->dbConnect();




            // Check if the user already exists
            $sql = "SELECT payload FROM cmp_mst_wa_temp_payload_strc WHERE type = '" . $data['type'] . "' AND status = 1 ";
            // print_r(   $sql);exit;
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
                // print_r($data);exit;   
                $string = trim(preg_replace('/\s\s+/', ' ', $data['payload']));
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Payload details fetched successfully",
                        "payload" => json_decode($string)
                    ),
                );
            } else {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "No data found.",
                    )
                );
            }
            // $resultArray = array(
            //         "apiStatus" => array(
            //             "code"    => "200",
            //             "message" => "Group details successfully created.",
            //             "payload"=>$data['payload']
            //         ),
            //     );
            // }


            // if ($db->query($sql) === true) {

            //     $db->close();

            //     $resultArray = array(
            //         "apiStatus" => array(
            //             "code"    => "200",
            //             "message" => "Group details successfully created.",
            //             "payload"=>$result
            //         ),
            //     );
            // } 
            // else {
            //     throw new Exception("Error occurred while inserting Group: " . $db->error);
            // }
        } catch (Exception $e) {
            if (isset($db)) {
                $db->close();
            }
            $resultArray = array(
                "apiStatus" => array(
                    "code"    => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
        return $resultArray;
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
