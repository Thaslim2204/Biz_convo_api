<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";
// require_once "model/register.php";

require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CONTACTMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getContact($data, $loginData);
                    // } elseif ($urlParam[1] == "active") {
                    //     $result = $this->useractive($data, $loginData);
                    //     return $result;
                    // } elseif ($urlParam[1] == "deactive") {
                    //     $result = $this->userdeactive($data, $loginData);
                } elseif ($urlParam[1] === 'exportcontacttoexcel') {
                    $result = $this->exportContactToExcel($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'exportisheader') {
                    $result = $this->isheaderonly($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'alldelete') {
                    $result = $this->AllContactDelete($data, $loginData);
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
                    $result = $this->createContact($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    $result = $this->getContactdetails($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'importContactfromexcel') {
                    $result = $this->importContactFromExcel($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'contactselectdelete') {
                    $result = $this->selecteddatacontactDelete($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'contactassigngroup') {
                    $result = $this->contactassigngroup($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    $result = $this->updateContactDeatils($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    $result = $this->deleteContact($data);
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

    public function getContactdetails($data, $loginData)
    {
        try {
            // print_r($loginData);
            $responseArray = ''; // Initializing response variable
            $db = $this->dbConnect();

            //filter using verndor id
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

            $recordCount = $this->getTotalCount($loginData, $vendor_id);


            // Validate pageIndex and dataLength
            if (!isset($data['pageIndex']) || $data['pageIndex'] === "") {
                throw new Exception("PageIndex should not be empty!");
            }
            if (!isset($data['dataLength']) || $data['dataLength'] === "") {
                throw new Exception("dataLength should not be empty!");
            }
            $start_index = $data['pageIndex'] * $data['dataLength'];
            $end_index = $data['dataLength'];

            // Query to fetch  contact person details
            // Query to fetch contact person details with store name and cmp_status
            $queryService = "SELECT 
            c.id AS ContactId, 
            c.vendor_id, 
            c.store_id, 
            c.first_name, 
            c.last_name,
            c.gender, 
            c.mobile, 
            c.email, 
            c.date_of_birth, 
            c.anniversary, 
            c.address, 
            c.loyality, 
            c.country, 
            c.sales_amount, 
            c.language_code, 
            c.created_by, 
            c.created_date, 
            c.updated_by, 
            c.updated_date, 
            c.status, 
            s.id AS store_id, 
            s.store_name
        
            FROM cmp_contact c
            JOIN cmp_store s ON c.store_id = s.id

                    WHERE c.status = 1  
                    -- AND c.created_by = " . $loginData['user_id'] . " 
                    AND c.vendor_id = $vendor_id
                    GROUP BY c.id
                    ORDER BY c.id DESC 
                    LIMIT $start_index, $end_index";
            //   print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);

            $Contact = []; // Store multiple contact records

            $contactMap = []; // Store contacts mapped by ID for grouping groups

            while ($row = $result->fetch_assoc()) {
                $contactId = $row['ContactId'];

                // If contact is not in array, add it
                if (!isset($contactMap[$contactId])) {
                    $contactMap[$contactId] = [
                        "id" => $row['ContactId'],
                        "storeId" => $row['store_id'],
                        "storeName" => $row['store_name'],
                        "firstName" => $row['first_name'],
                        "lastName" => $row['last_name'],
                        "gender" => $row['gender'],
                        "mobile" => $row['mobile'],
                        "email" => $row['email'],
                        "country" => $row['country'],
                        "language" => $row['language_code'],
                        "status" => $row['status'],
                        "createdBy" => $row['created_by'],
                        "createdDate" => $row['created_date'],
                        "otherInformation" => [
                            "DOB" => $row['date_of_birth'],
                            "anniversary" => $row['anniversary'],
                            "loyality" => $row['loyality'],
                            "salesAmount" => $row['sales_amount'],
                            "address" => $row['address']
                        ],
                        "groupDetails" => [] // Initialize group details as an array
                    ];
                }

                // Convert group data from comma-separated values to array
                $groupNames = explode(",", $row['group_names']);
                $groupIds = explode(",", $row['group_ids']);

                // Map group IDs to group names
                foreach ($groupIds as $index => $groupId) {
                    $contactMap[$contactId]["groupDetails"][] = [
                        "groupId" => $groupId,
                        "groupName" => $groupNames[$index]
                    ];
                }
            }

            // Convert map to indexed array
            $Contact = array_values($contactMap);

            // Construct the final response array
            $responseArray = [
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $recordCount,
                "ContactData" => $Contact,
            ];
            // Check if Contact data exists
            if (!empty($Contact)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Contact details fetched successfully",
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
    public function getContact($data, $loginData)
    {
        try {
            $db = $this->dbConnect();

            $id = isset($data[2]) ? intval($data[2]) : 0;
            $userId = intval($loginData['user_id']); // Ensure it's an integer

            if ($id <= 0) {
                throw new Exception("Bad request");
            }

            // Escape variables to prevent SQL injection
            $id = mysqli_real_escape_string($db, $id);
            $userId = mysqli_real_escape_string($db, $userId);

            $sql = "SELECT 
            c.id AS ContactId, 
            c.vendor_id, 
            c.store_id, 
            c.first_name, 
            c.last_name, 
            c.gender,
            c.mobile, 
            c.email, 
            c.date_of_birth, 
            c.anniversary, 
            c.address, 
            c.loyality, 
            c.country, 
            c.sales_amount, 
            c.language_code, 
            c.created_by, 
            c.created_date, 
            c.updated_by, 
            c.updated_date, 
            c.status, 
            s.id AS store_id, 
            s.store_name, 
            GROUP_CONCAT(DISTINCT gc.group_name) AS group_names,
            GROUP_CONCAT(DISTINCT gcm.group_id) AS group_ids
        FROM cmp_contact c
        JOIN cmp_store s ON c.store_id = s.id
        LEFT JOIN cmp_group_contact_mapping gcm ON c.id = gcm.contact_id AND gcm.status = 1
        LEFT JOIN cmp_group_contact gc ON gcm.group_id = gc.id
        WHERE c.status = 1 
            AND c.created_by = $userId 
            AND c.id = $id
        GROUP BY c.id";


            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                $contactData = null;

                while ($row = $result->fetch_assoc()) {
                    if ($contactData === null) {
                        $contactData = [
                            "contactId" => $row['ContactId'],
                            "storeId" => $row['store_id'],
                            "storeName" => $row['store_name'],
                            "firstName" => $row['first_name'],
                            "lastName" => $row['last_name'],
                            "gender" => $row['gender'],
                            "mobile" => $row['mobile'],
                            "email" => $row['email'],
                            "country" => $row['country'],
                            "language" => $row['language_code'],
                            "otherInformation" => [
                                "DOB" => $row['date_of_birth'],
                                "anniversary" => $row['anniversary'],
                                "loyality" => $row['loyality'],
                                "salesAmount" => $row['sales_amount'],
                                "address" => $row['address']
                            ],
                            "groupDetails" => [] // Initialize as an empty array
                        ];
                    }

                    // Process group details
                    if (!empty($row['group_names']) && !empty($row['group_ids'])) {
                        $groupNames = explode(",", $row['group_names']);
                        $groupIds = explode(",", $row['group_ids']);

                        foreach ($groupIds as $index => $groupId) {
                            $contactData["groupDetails"][] = [
                                "groupId" => $groupId,
                                "groupName" => $groupNames[$index]
                            ];
                        }
                    }
                }

                $resultArray = [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Contact details fetched successfully",
                    ],
                    "result" => $contactData
                ];
            } else {
                $resultArray = [
                    "apiStatus" => [
                        "code" => "404",
                        "message" => "Contact details not found",
                    ]
                ];
            }

            // Close database connection
            $db->close();

            return $resultArray;
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "500",
                    "message" => $e->getMessage(),
                ]
            ];
        }
    }




    /**
     * Post/Add tenant
     *
     * @param array $data
     * @return multitype:string
     */
    public function createContact($data, $loginData)
    {
        // Initialize result array
        $resultArray = array();
        try {
            // print_r("1233");exit;
            // print_r($data);exit;
            $db = $this->dbConnect();

            // Validate input data
            $validationData = array(
                // "storeId" => $data['storeId'],
                "FirstName" => $data['firstName'],
                "Mobile"        => $data['mobile'],
                // "EmailID"     => $data['email'],
                // "Country"     => $data['country'],
                // "Language"       => $data['language'],

            );

            $this->validateInputDetails($validationData);
            //Get the Contact id from the login data
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

            // print_r($vendor_id);exit;

            // Check if the user already exists
            $sql = "SELECT id FROM cmp_contact WHERE mobile = '" . $data['mobile'] . "'
             AND status = 1  AND  vendor_id = " . $vendor_id;
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
                throw new Exception("Contact already exist");
            }

            // Check if Contact ID exists
            // $sql = "SELECT id FROM cmp_store WHERE id = '" . $data['storeId'] . "' AND status = 1 AND active_status = 1 AND created_by = " . $loginData['user_id'];
            // $result = mysqli_query($db, $sql);

            // if (!$result || mysqli_num_rows($result) === 0) {
            //     throw new Exception('Invalid Store ID');
            // }
            // Validate group name and ID
            foreach ($data['groupdetails'] as $group) {
                $groupId = $group['groupId'];
                $groupName = $group['groupName'];

                $sql = "SELECT id FROM cmp_group_contact WHERE id = '$groupId' AND group_name = '$groupName'";
                $result = $db->query($sql);
                if (!$result || mysqli_num_rows($result) === 0) {
                    throw new Exception("Invalid Group ID or Group Name mismatch: $groupId");
                }
            }

            // Convert dates to MySQL format (YYYY-MM-DD)
            $dob = !empty($data['otherInformation']['DOB']) ? DateTime::createFromFormat('m/d/Y', $data['otherInformation']['DOB'])->format('Y-m-d') : null;
            $anniversary = !empty($data['otherInformation']['anniversary']) ? DateTime::createFromFormat('m/d/Y', $data['otherInformation']['anniversary'])->format('Y-m-d') : null;

            $sql = "INSERT INTO cmp_contact 
        (vendor_id, store_id, first_name, last_name, mobile, gender,email, date_of_birth, anniversary, address, loyality, country,sales_amount, language_code, created_by, created_date) 
        VALUES 
        ('$vendor_id', 
         '" . $data['storeId'] . "', 
         '" . $data['firstName'] . "', 
         '" . $data['lastName'] . "', 
         '" . $data['mobile'] . "', 
         '" . $data['gender'] . "', 
         '" . $data['email'] . "', 
         '" . $dob . "', 
         '" . $anniversary . "', 
         '" . $data['otherInformation']['address'] . "', 
         '" . $data['otherInformation']['loyality'] . "', 
         '" . $data['country'] . "', 
          '" . $data['otherInformation']['saleAmount'] . "',
         '" . $data['language'] . "', 
         '" . $loginData['user_id'] . "', 
         NOW())";

            //  print_r($sql);exit;    
            if ($db->query($sql) === true) {
                $contactId = $db->insert_id;

                // Check and Insert Group Details
                foreach ($data['groupdetails'] as $group) {
                    $groupId = $group['groupId'];
                    $sql = "SELECT id FROM cmp_group_contact WHERE id = '$groupId'";
                    $result = $db->query($sql);
                    if (!$result || mysqli_num_rows($result) === 0) {
                        throw new Exception("Invalid Group ID: $groupId");
                    }

                    // Insert into cmp_group_contact_mapping
                    $sql = "INSERT INTO cmp_group_contact_mapping (group_id, contact_id, created_by, created_date) VALUES ('$groupId', '$contactId', '$user_id', NOW())";
                    if (!$db->query($sql)) {
                        throw new Exception("Error inserting into cmp_group_contact_mapping: " . $db->error);
                    }
                }


                $db->close();

                $resultArray = array(
                    "apiStatus" => array(
                        "code"    => "200",
                        "message" => "Contact successfully created.",
                    ),
                );
            } else {
                throw new Exception("Error occurred while inserting Contact: " . $db->error);
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


    private function updateContactDeatils($data, $loginData)

    {

        // Initialize result array
        $resultArray = array();
        try {
            $db = $this->dbConnect();

            // Validate input data
            $validationData = array(
                "ContactId" => $data['contactId'],
                // "storeId" => $data['storeId'],
                "FirstName" => $data['firstName'],
                "Mobile" => $data['mobile'],
                // "EmailID" => $data['email'],
                // "Country" => $data['country'],
                // "Language" => $data['language'],
            );

            $this->validateInputDetails($validationData);

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
            // Check if the contact exists
            $sql = "SELECT id FROM cmp_contact WHERE id = '" . $data['contactId'] . "' AND status = 1  AND vendor_id = '" . $vendor_id . "'";
            // print_r($sql);exit;
            $result = mysqli_query($db, $sql);
            if (!$result || mysqli_num_rows($result) === 0) {
                throw new Exception("Contact not found");
            }

        //     // Check if Contact ID exists
        //     $sql = "SELECT id FROM cmp_store WHERE id = '" . $data['storeId'] . "' AND status = 1 AND active_status = 1 ";
        // //    print_r($sql);exit;
        //     $result = mysqli_query($db, $sql);
        //     if (!$result || mysqli_num_rows($result) === 0) {
        //         throw new Exception('Invalid Store ID');
        //     }

            // Check if the mobile number already exists for another contact
            $sql = "SELECT id FROM cmp_contact WHERE mobile = '" . $data['mobile'] . "' AND id != '" . $data['contactId'] . "' AND status = 1 AND vendor_id = '" . $vendor_id . "'";
            // print_r($sql);exit;
            $result = mysqli_query($db, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                throw new Exception("Mobile number already exists for another contact");
            }
            // Validate group name and ID
            foreach ($data['groupdetails'] as $group) {
                $groupId = $group['groupId'];
                $groupName = $group['groupName'];

                $sql = "SELECT id FROM cmp_group_contact WHERE id = '$groupId' AND group_name = '$groupName'";
                $result = $db->query($sql);
                if (!$result || mysqli_num_rows($result) === 0) {
                    throw new Exception("Invalid Group ID or Group Name mismatch: $groupId");
                }
            }


            // Convert dates to MySQL format (YYYY-MM-DD)
            $dob = !empty($data['otherInformation']['DOB']) ? DateTime::createFromFormat('m/d/Y', $data['otherInformation']['DOB'])->format('Y-m-d') : null;
            $anniversary = !empty($data['otherInformation']['anniversary']) ? DateTime::createFromFormat('m/d/Y', $data['otherInformation']['anniversary'])->format('Y-m-d') : null;

            // Update contact information
            $sql = "UPDATE cmp_contact SET 
                    store_id = '" . $data['storeId'] . "',
                    first_name = '" . $data['firstName'] . "',
                    last_name = '" . $data['lastName'] . "',
                    gender = '" . $data['gender'] . "',
                    mobile = '" . $data['mobile'] . "',
                    email = '" . $data['email'] . "',
                    date_of_birth = '" . $dob . "',
                    anniversary = '" . $anniversary . "',
                    address = '" . $data['otherInformation']['address'] . "',
                    loyality = '" . $data['otherInformation']['loyality'] . "',
                    country = '" . $data['country'] . "',
                    sales_amount = '" .  $data['otherInformation']['saleAmount'] . "',
                    language_code = '" . $data['language'] . "',
                    updated_by = '" . $loginData['user_id'] . "',
                    updated_date = NOW()
                WHERE id = '" . $data['contactId'] . "'";
            // print_r($sql);exit;
            if ($db->query($sql) === true) {
                // Fetch existing group mappings
                $existingGroups = [];
                $sql = "SELECT group_id FROM cmp_group_contact_mapping WHERE contact_id = '" . $data['contactId'] . "'";
                $result = $db->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $existingGroups[$row['group_id']] = true;
                }

                // Update Group Details
                foreach ($data['groupdetails'] as $group) {
                    $groupId = $group['groupId'];

                    if (isset($existingGroups[$groupId])) {
                        // Update existing mapping status to active
                        $sql = "UPDATE cmp_group_contact_mapping SET status = 1 WHERE group_id = '$groupId' AND contact_id = '" . $data['contactId'] . "'";
                        $db->query($sql);
                        unset($existingGroups[$groupId]);
                    } else {
                        // Insert new group mapping
                        $sql = "INSERT INTO cmp_group_contact_mapping (group_id, contact_id, created_by, created_date, status) VALUES ('$groupId', '" . $data['contactId'] . "', '" . $loginData['user_id'] . "', NOW(), 1)";
                        if (!$db->query($sql)) {
                            throw new Exception("Error inserting into cmp_group_contact_mapping: " . $db->error);
                        }
                    }
                }

                // Set status to 0 for removed groups
                foreach ($existingGroups as $oldGroupId => $value) {
                    $sql = "UPDATE cmp_group_contact_mapping SET status = 0 WHERE group_id = '$oldGroupId' AND contact_id = '" . $data['contactId'] . "'";
                    $db->query($sql);
                }

                $db->close();
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Contact successfully updated with groups.",
                    ),
                );
            } else {
                throw new Exception("Error occurred while updating contact: " . $db->error);
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






    private function deleteContact($data)
    {
        try {

            $id = $data[2];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_contact WHERE id = $id AND status=1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Contact does not exist",
                    ),
                );
            }

            //update delete query

            $deleteQuery = "UPDATE cmp_contact
            SET status = 0 
            WHERE id = " . $id . "";

            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Contact details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete Contact details, please try again later";
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
    // //Contact ative and deactive
    // public function useractive($data, $loginData)
    // {
    //     try {
    //         $id = $data[2];
    //         $db = $this->dbConnect();
    //         if (empty($data[2])) {
    //             throw new Exception("Bad request");
    //         }

    //         $db = $this->dbConnect();
    //         $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_store WHERE id = $id AND status=1";

    //         $result = $db->query($checkIdQuery);
    //         $rowCount = $result->fetch_assoc()['count'];

    //         // If ID doesn't exist, return error
    //         if ($rowCount == 0) {
    //             $db->close();
    //             return array(
    //                 "apiStatus" => array(
    //                     "code" => "400",
    //                     "message" => "User ID does not exist",
    //                 ),
    //             );
    //         }
    //         $ActiveQuery = "UPDATE cmp_store SET active_status = 1 WHERE status = 1 AND id = $id";

    //         if ($db->query($ActiveQuery) === true) {
    //             $db->close();
    //             $statusCode = "200";
    //             $statusMessage = "Contact activated successfully.";
    //         } else {
    //             $statusCode = "500";
    //             $statusMessage = "Unable to activate Contact, please try again later.";
    //         }
    //         $resultArray = array(
    //             "apiStatus" => array(
    //                 "code" => $statusCode,
    //                 "message" => $statusMessage,
    //             ),
    //         );
    //         return $resultArray;
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }
    // public function userdeactive($data, $loginData)
    // {
    //     try {
    //         $id = $data[2];
    //         $db = $this->dbConnect();
    //         if (empty($data[2])) {
    //             throw new Exception("Bad request");
    //         }
    //         $db = $this->dbConnect();
    //         $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_store WHERE id = $id AND active_status=1 AND status=1";
    //         // print_r($checkIdQuery);exit;

    //         $result = $db->query($checkIdQuery);
    //         $rowCount = $result->fetch_assoc()['count'];

    //         // If ID doesn't exist, return error
    //         if ($rowCount == 0) {
    //             $db->close();
    //             $statusCode = "400";
    //             $statusMessage = "User ID does not exist.";
    //             return array(
    //                 "apiStatus" => array(
    //                     "code" => $statusCode,
    //                     "message" => $statusMessage,
    //                 ),
    //             );
    //         }
    //         $deactiveQuery = "UPDATE cmp_store SET active_status = 0 WHERE status = 1 AND id = $id";

    //         if ($db->query($deactiveQuery) === true) {
    //             $db->close();
    //             $statusCode = "200";
    //             $statusMessage = "Contact Deactivated successfully.";
    //         } else {
    //             $statusCode = "500";
    //             $statusMessage = "Unable to Deactivate Contact, please try again later.";
    //         }
    //         $resultArray = array(
    //             "apiStatus" => array(
    //                 "code" => $statusCode,
    //                 "message" => $statusMessage,
    //             ),
    //         );
    //         return $resultArray;
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }
    public function importContactFromExcel($data, $loginData)
    {
        $resultArray = [];
        try {
            $db = $this->dbConnect();

            // Validate file upload
            if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
                throw new Exception("No valid file uploaded.");
            }

            $file = $_FILES['file'];
            $filePath = $file['tmp_name'];
            $fileName = $file['name'];
            $fileType = $file['type'];
            $fileSize = $file['size'];


            // Allowed file types
            $allowedTypes = [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel'
            ];

            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Invalid file type. Please upload an Excel file.");
            }

            if ($fileSize > 5 * 1024 * 1024) {
                throw new Exception("File size exceeds 5MB limit.");
            }

            if (!file_exists($filePath) || empty($filePath)) {
                throw new Exception("Uploaded file not found.");
            }

            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (empty($rows) || count($rows) < 2) {
                throw new Exception("Excel file is empty or invalid format.");
            }

            unset($rows[1]); // Skip header row

            // Get Vendor ID
            $user_id = $loginData['user_id'];
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '$user_id'";
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

            // Begin transaction
            $db->begin_transaction();

            foreach ($rows as $row) {
                $firstName   = $db->real_escape_string(trim($row['B']));
                $lastName    = $db->real_escape_string(trim($row['C']));
                $gender      = $db->real_escape_string(trim($row['D']));
                $email       = $db->real_escape_string(trim($row['E']));
                $mobile      = $db->real_escape_string(trim($row['F']));
                $language    = $db->real_escape_string(trim($row['G']));
                $address     = $db->real_escape_string(trim($row['I']));
                $loyalty     = $db->real_escape_string(trim($row['J']));
                $country     = $db->real_escape_string(trim($row['L']));
                $groupName   = $db->real_escape_string(trim($row['M']));
                $storeName   = $db->real_escape_string(trim($row['N']));
                $saleAmount  = $db->real_escape_string(trim($row['O']));
                $rawDOB = trim($row['H']);
                $rawAnniversary = trim($row['K']);

                error_log("Processing row: " . json_encode($row));
// print_r($rows);exit;
                // Validate required fields
                // if (empty($firstName) || empty($groupName) || empty($mobile)) {
                //     throw new Exception("Invalid or missing required fields in row: " . json_encode($row));
                // }
                if (empty($firstName)) {
                    throw new Exception("Invalid data found in row: Address is missing.");
                }
                if (empty($mobile)) {
                    throw new Exception("Invalid data found in row: Mobile number is missing.");
                }
                if (empty($groupName)) {
                    throw new Exception("Invalid data found in row: Group name is missing.");
                }

                // Store lookup
                $storeQuery = "SELECT id FROM cmp_store WHERE store_name = '$storeName' LIMIT 1";
                $storeResult = $db->query($storeQuery);
                if (!$storeResult || $storeResult->num_rows == 0) {
                    throw new Exception("Store '$storeName' not found.");
                }
                $storeRow = $storeResult->fetch_assoc();
                $store_id = $storeRow['id'];

                // Handle multiple group names
                $groupNames = array_map('trim', explode(',', $groupName));
                $groupIds = [];
                foreach ($groupNames as $gName) {
                    $escapedGroupName = $db->real_escape_string($gName);
                    $groupQuery = "SELECT id FROM cmp_group_contact WHERE status=1 AND group_name = '$escapedGroupName' LIMIT 1 ";
                    $groupResult = $db->query($groupQuery);
                    if (!$groupResult || $groupResult->num_rows == 0) {
                        throw new Exception("Group '$gName' not found.");
                    }
                    $groupRow = $groupResult->fetch_assoc();
                    $groupIds[] = $groupRow['id'];
                }

                // Check duplicate
                $checkQuery = "SELECT id FROM cmp_contact WHERE mobile = '$mobile' AND created_by = '$user_id' AND status = 1 LIMIT 1";
                $checkResult = $db->query($checkQuery);
                if ($checkResult->num_rows > 0) {
                    $existingRow = $checkResult->fetch_assoc();
                    $contactId = $existingRow['id'];

                    foreach ($groupIds as $group_id) {
                        $mapCheckQuery = "SELECT id FROM cmp_group_contact_mapping WHERE group_id = '$group_id' AND contact_id = '$contactId'";
                        $mapCheckResult = $db->query($mapCheckQuery);
                        if ($mapCheckResult->num_rows == 0) {
                            $insertGroupQuery = "INSERT INTO cmp_group_contact_mapping (group_id, contact_id, created_by, created_date) 
                                                 VALUES ('$group_id', '$contactId', '$user_id', NOW())";
                            if (!$db->query($insertGroupQuery)) {
                                throw new Exception("Error inserting into cmp_group_contact_mapping: " . $db->error);
                            }
                        }
                    }
                    continue;
                }

                // Format dates
                $dob = !empty($rawDOB) ? DateTime::createFromFormat('d/m/Y', $rawDOB)->format('Y-m-d') : null;
                $anniversary = !empty($rawAnniversary) ? DateTime::createFromFormat('d/m/Y', $rawAnniversary)->format('Y-m-d') : null;
                $dob = $dob ? $db->real_escape_string($dob) : null;
                $anniversary = $anniversary ? $db->real_escape_string($anniversary) : null;

                // Insert contact
                $insertQuery = "INSERT INTO cmp_contact 
                                (first_name, last_name, gender, email, mobile, language_code, date_of_birth, address, loyality, anniversary, country,sales_amount, store_id, vendor_id, created_by) 
                                VALUES 
                                ('$firstName', '$lastName', '$gender', '$email', '$mobile', '$language', '$dob', '$address', '$loyalty', '$anniversary', '$country','$saleAmount', '$store_id', '$vendor_id', '$user_id')";
                // print_r($insertQuery);
                if ($db->query($insertQuery) === true) {
                    $contactId = $db->insert_id;

                    // Insert group mappings
                    foreach ($groupIds as $group_id) {
                        $insertGroupQuery = "INSERT INTO cmp_group_contact_mapping (group_id, contact_id, created_by, created_date) 
                                             VALUES ('$group_id', '$contactId', '$user_id', NOW())";
                    //    print_r($insertGroupQuery);
                       if (!$db->query($insertGroupQuery)) {
                            throw new Exception("Error inserting into cmp_group_contact_mapping: " . $db->error);
                        }
                    }
                } else {
                    throw new Exception("Error inserting data: " . $db->error);
                }
            }

            // Commit transaction
            $db->commit();

            $resultArray = [
                "apiStatus" => [
                    "code"    => "200",
                    "message" => "Excel file uploaded and processed successfully."
                ]
            ];
        } catch (Exception $e) {
            // Rollback in case of any error
            if ($db && $db->errno === 0) {
                $db->rollback();
            }
            error_log("Import Error: " . $e->getMessage());
            $resultArray = [
                "apiStatus" => [
                    "code"    => "401",
                    "message" => $e->getMessage()
                ]
            ];
        }
        return $resultArray;
    }








    //export the data to excel 

    public function exportContactToExcel($data, $loginData)
    {

        try {
            $db = $this->dbConnect();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //Get the Contact id from the login data
            $user_id = $loginData['user_id'];
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '$user_id'";
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
            // Set column headers
            $headers = ['S.No',  'First Name', 'Last Name',  'Gender', 'Email', 'Mobile', 'language', 'Date of Birth', 'Address', 'loyality', 'Anniversary Date', 'Country', 'Store Name', 'Sale Amount'];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true); // Make text bold
                $sheet->getStyle($column . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Center align
                $sheet->getColumnDimension($column)->setAutoSize(true); // Auto-adjust column width
                $column++;
            }
            // Fetch staff details
            $query = "SELECT
                    c.first_name,
                    c.last_name,
                    c.gender,
                    c.mobile,
                    c.email,
                    c.date_of_birth,
                    c.language_code,
                    c.anniversary,
                    c.address,
                    c.loyality,
                    c.country,
                    c.sales_amount,

                    s.store_name


             FROM cmp_contact c 
                  JOIN cmp_store s ON s.id = c.store_id                  
                  WHERE c.status = 1 AND vendor_id= $vendor_id AND c.created_by='" . $loginData['user_id'] . "'";
            // print_r($query);exit;
            $result = $db->query($query);

            if ($result->num_rows === 0) {
                throw new Exception("No Contact data found.");
            }

            $rowIndex = 2; // Start from row 2 (row 1 is headers)
            $sno = 1; // Initialize serial number
            while ($row = $result->fetch_assoc()) {
                $column = 'A';
                $sheet->setCellValue($column++ . $rowIndex, $sno++);
                $sheet->setCellValue($column++ . $rowIndex, $row['first_name']);
                $sheet->setCellValue($column++ . $rowIndex, $row['last_name']);
                $sheet->setCellValue($column++ . $rowIndex, $row['gender']);
                $sheet->setCellValue($column++ . $rowIndex, $row['email']);
                $sheet->setCellValue($column++ . $rowIndex, $row['mobile']);
                $sheet->setCellValue($column++ . $rowIndex, $row['language_code']);
                $sheet->setCellValue($column++ . $rowIndex, $row['date_of_birth']);
                $sheet->setCellValue($column++ . $rowIndex, $row['address']);
                $sheet->setCellValue($column++ . $rowIndex, $row['loyality']);
                $sheet->setCellValue($column++ . $rowIndex, $row['anniversary']);
                $sheet->setCellValue($column++ . $rowIndex, $row['country']);
                $sheet->setCellValue($column++ . $rowIndex, $row['store_name']);
                $sheet->setCellValue($column++ . $rowIndex, $row['sale_amount']);
                $rowIndex++;
            }

            // **Output the file directly for download**
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Staff_Data_' . date('Ymd_His') . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output'); // Send file to browser directly

            exit;
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }
    public function isheaderonly($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();



            // Set column headers
            $headers = ['S.No', 'First Name', 'Last Name', 'Gender', 'Email', 'Mobile', 'Language', 'Date of Birth', 'Address', 'Loyality', 'Anniversary Date', 'Country', 'Group Name', 'Store Name', 'Sale Amount'];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true);
                $sheet->getStyle($column . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $column++;
            }

            // Output the file directly for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Contact_Data_' . date('Ymd_His') . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }


    //multiple delete for contact 

    private function selecteddatacontactDelete($data, $loginData)
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
                $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_contact WHERE id = $id AND status=1 AND created_by ='" . $loginData['user_id'] . "'";
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
                $deleteQuery = "UPDATE cmp_contact SET status = 0  WHERE id = $id ";
                if ($db->query($deleteQuery) === true) {
                    $deleted[] = [
                        'id' => $id,
                        'status' => "200",
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
                    'code' => count($failed) > 0 ? "400" : "200",
                    'message' => count($failed) > 0 ? 'Some deletions failed' : 'Records deleted successfully'
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
    //Assigned group to contact
    private function contactassigngroup($data, $loginData)
    {
        try {
            $ids = $data['id'];
            $group_ids = $data['group_id'];

            if (empty($ids) || !is_array($ids)) {
                throw new Exception("Please provide the contact IDs.");
            }

            if (empty($group_ids) || !is_array($group_ids)) {
                throw new Exception("Please provide the group IDs.");
            }

            $db = $this->dbConnect();

            // Validate all group IDs
            foreach ($group_ids as $group_id) {
                if (!is_numeric($group_id)) {
                    throw new Exception("Invalid group ID: $group_id");
                }

                $groupCheckQuery = "SELECT COUNT(*) AS count FROM cmp_group_contact 
                                WHERE id = $group_id AND status = 1 AND created_by = '" . $loginData['user_id'] . "'";
                $groupResult = $db->query($groupCheckQuery);
                $groupExists = $groupResult->fetch_assoc()['count'];

                if ($groupExists == 0) {
                    throw new Exception("Group ID $group_id not found or inactive.");
                }
            }

            // Assign each contact to each group
            foreach ($ids as $id) {
                if (!is_numeric($id)) {
                    throw new Exception("Invalid contact ID: $id");
                }

                // Check contact exists
                $checkContactQuery = "SELECT COUNT(*) AS count FROM cmp_contact 
                                  WHERE id = $id AND status = 1 AND created_by = '" . $loginData['user_id'] . "'";
                $contactResult = $db->query($checkContactQuery);
                $contactExists = $contactResult->fetch_assoc()['count'];

                if ($contactExists == 0) {
                    throw new Exception("Contact ID $id does not exist or is inactive.");
                }

                foreach ($group_ids as $group_id) {
                    // Check already mapped
                    $checkMappingQuery = "SELECT COUNT(*) AS count FROM cmp_group_contact_mapping 
                          WHERE contact_id = $id AND group_id = $group_id";
                    $mappingResult = $db->query($checkMappingQuery);
                    $alreadyMapped = $mappingResult->fetch_assoc()['count'];

                    if ($alreadyMapped > 0) {
                        // Skip this group, don't throw error
                        continue;
                    }

                    // Insert mapping
                    $insertQuery = "INSERT INTO cmp_group_contact_mapping (group_id, contact_id, created_by) 
                    VALUES ($group_id, $id, '" . $loginData['user_id'] . "')";
                    if (!$db->query($insertQuery)) {
                        throw new Exception("Failed to assign Contact ID $id to Group ID $group_id.");
                    }
                }
            }

            $db->close();

            return [
                'apiStatus' => [
                    'code' => "200",
                    'status' => "OK",
                    'message' => "Contacts assigned to groups successfully."
                ]
            ];
        } catch (Exception $e) {
            return [
                'apiStatus' => [
                    'code' => "500",
                    'status' => "ERROR",
                    'message' => $e->getMessage()
                ]
            ];
        }
    }

    private function AllContactDelete($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $userId = $loginData['user_id'];

            $db->query("UPDATE cmp_contact SET status = 0 WHERE created_by = $userId");

            return [
                'apiStatus' => [
                    'code' => "200",
                    'message' => 'All contacts deleted successfully.'
                ]
            ];
        } catch (Exception $e) {
            return [
                'apiStatus' => [
                    'code' => "500",
                    'message' => $e->getMessage()
                ]
            ];
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

    private function getTotalCount($loginData, $vendor_id)
    {
        try {
            $db = $this->dbConnect();
            $sql = "SELECT * FROM cmp_contact WHERE  vendor_id = " . $vendor_id . " AND status = 1";
            // print_r($sql);exit;
            $result = $db->query($sql);
            $row_cnt = mysqli_num_rows($result);
            return $row_cnt;
        } catch (Exception $e) {
            return array(
                "result" => "401",
                "message" => $e->getMessage(),
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
