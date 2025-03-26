<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";
require_once "model/whatsapp_template.php";

class CAMPAIGNMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getcampaign($data, $loginData);
                    // } elseif ($urlParam[1] === 'timezon') {
                    //     $result = $this->getTimezonedropdown($data, $loginData);
                    //     return $result;
                } elseif ($urlParam[1] == "active") {
                    $result = $this->campaignactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactive") {
                    $result = $this->campaigndeactive($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] === 'create') {
                    $result = $this->createCampaign($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    if ($urlParam[2] === 'dashboard') {
                        $result = $this->getDashboardDetails($data, $loginData);
                        return $result;
                    }
                    $result = $this->getCampaignDetails($data, $loginData);
                    return $result;

                    // } elseif ($urlParam[1] === 'payloadStructure') {
                    //     // print_r($data);exit;
                    //     $result = $this->getpayloadstructure($data, $loginData);
                    //     return $result;
                    // } elseif ($urlParam[1] === 'groupbycontact') {
                    //     $result = $this->getcampaignByContactDetails($data, $loginData);
                    //     return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                // if ($urlParam[1] == "update") {
                //     $result = $this->updateGroup($data, $loginData);
                // } else {
                //     throw new Exception("Unable to proceed your request!");
                // }
                // return $result;
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

    public function getCampaignDetails($data, $loginData)
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
            // Get the total record count before applying the LIMIT
            $countQuery = "SELECT COUNT(*) AS totalCount 
        FROM cmp_campaign AS c
        JOIN cmp_whatsapp_templates AS wt ON wt.id = c.template_id
        WHERE c.status = 1  AND wt.status = 1
        AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData);

            $countResult = $db->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $recordCount = $countRow['totalCount']; // Total record count

            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT c.id,c.title,c.template_id,c.created_date,c.active_status,c.schedule_at,c.status AS campaignStatus,wt.template_name,wt.language
                 FROM cmp_campaign AS c 
                 JOIN cmp_whatsapp_templates AS wt ON wt.id = c.template_id
                 WHERE c.status = 1 AND wt.status=1 AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
                 ORDER BY id DESC 
                 LIMIT $start_index, $end_index";

            //   print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);

            $group = array(); // Initialize array to hold Store data
            if ($row_cnt > 0) {
                while ($row = $result->fetch_assoc()) {
                    $group[] = array(
                        "id" => $row['id'],
                        "title" => $row['title'],
                        "templateName" => $row['template_name'],
                        "tempLang" => $row['language'],
                        "activeStatus" => $row['active_status'],
                        "createdDate" => $row['created_date'],
                        "scheduleAt" => $row['schedule_at'],
                        "status" => $row['campaignStatus']
                    );
                }
            }

            // Construct the final response array
            $responseArray = array(
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $recordCount,
                'CampaignDataList' => array_values($group), // Reset array keys
            );

            // Check if Store data exists
            if (!empty($group)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Campaign details fetched successfully",
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
    public function getcampaign($data, $loginData)
    {
        try {
            $id = $data[2];
            if (empty($id)) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $sql = "SELECT c.id,c.title,c.template_id,c.created_date,c.active_status,c.schedule_at,c.status AS campaignStatus,wt.template_name,wt.language
                 FROM cmp_campaign AS c 
                 JOIN cmp_whatsapp_templates AS wt ON wt.id = c.template_id
                      WHERE c.id = $id AND c.status = 1 AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData) . " 
                      ";


            $result = $db->query($sql);

            // Check if Store exists
            if ($result->num_rows > 0) {
                $group = array();
                while ($row = $result->fetch_assoc()) {
                    $group = array(
                        "id" => $row['id'],
                        "title" => $row['title'],
                        "templateName" => $row['template_name'],
                        "tempLang" => $row['language'],
                        "activeStatus" => $row['active_status'],
                        "createdDate" => $row['created_date'],
                        "scheduleAt" => $row['schedule_at'],
                        "status" => $row['campaignStatus']
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
    public function createCampaign($data, $loginData)
    {
        // Initialize result array
        $resultArray = array();
        try {
            $db = $this->dbConnect();

            // Validate input details
            $validationData = array(
                "templateId"    => $data['templateId'],
                "groupId"       => $data['group']['groupId'],
                "campaignTitle" => $data['title'],
            );
            $this->validateInputDetails($validationData);

            // Check if the template ID exists
            $sql = "SELECT id FROM cmp_whatsapp_templates WHERE template_id = '" . $data['templateId'] . "' AND status = 1";
            $result = mysqli_query($db, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                if ($row) {
                    $template_id = $row['id'];
                } else {
                    throw new Exception("Template ID not found");
                }
            } else {
                throw new Exception("Database query failed: " . mysqli_error($db));
            }

            // Extract group ID and group name
            $groupId = $data['group']['groupId'];
            $groupName = $data['group']['groupName'];

            // Validate Group ID and Group Name
            $checkIdQuery = "SELECT COUNT(*) as count FROM cmp_group_contact WHERE id = '$groupId' AND group_name='$groupName' AND status = 1";
            $result = $db->query($checkIdQuery);
            $row = $result->fetch_assoc();

            if ($row['count'] == 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code"    => "400",
                        "message" => "Group does not exist",
                    ],
                ];
            }

            // Extract timezone details
            $timezone_id = $data['timezone']['id'];
            $timezone_zoneName = $data['timezone']['zoneName'];

            // Validate all variables before inserting anything
            foreach ($data['variableIds'] as $variable) {
                $varTypeId = $variable['varName'];
                $varValueid = $variable['varValue']['varTypeId'];
                $varValuename = $variable['varValue']['varTypeName'];

                // Validate variable ID and variable name in cmp_mst_variable
                $checkVariableQuery = "SELECT COUNT(*) as count FROM cmp_mst_variable WHERE id = '$varValueid' AND variable_name = '$varValuename'";
                $result = $db->query($checkVariableQuery);
                $row = $result->fetch_assoc();

                if ($row['count'] == 0) {
                    throw new Exception("Invalid variable ID or variable name.");
                }
            }

            // Handle schedule time
            if (!empty($data['scheduleStatus']) && $data['scheduleStatus'] == true) {
                if (!empty($data['scheduledAt']) && !empty($timezone_zoneName)) {
                    $scheduleAt = $data['scheduledAt'];
                } else {
                    throw new Exception("Invalid scheduled time or timezone.");
                }
            } else {
                $scheduleAt = date("Y-m-d H:i:s", strtotime("+4 hours 30 minutes")); // Use current datetime
            }

            // Insert into cmp_campaign
            $insertGroupQuery = "INSERT INTO cmp_campaign (group_id, template_id, title, restrictLangCode, timezone, schedule_at, send_num, created_by)
    VALUES ('$groupId', '$template_id', '" . $data['title'] . "', '" . $data['restrictLangCode'] . "', '$timezone_zoneName', '$scheduleAt', '" . $data['SendNum'] . "', '" . $loginData['user_id'] . "')";

            if ($db->query($insertGroupQuery) === true) {
                // Get the last inserted campaign_id
                $campaign_id = mysqli_insert_id($db);

                $call=New WHATSAPPTEMPLATEMODEL();
                $call->sendMessage($data, $loginData,$campaign_id);
                // Insert into cmp_campaign_variable_mapping
                foreach ($data['variableIds'] as $variable) {
                    $varTypeId = $variable['varName'];
                    $varValueid = $variable['varValue']['varTypeId'];
                    $varValuename = $variable['varValue']['varTypeName'];

                    $insertVariableMappingQuery = "INSERT INTO cmp_campaign_variable_mapping (campaign_id, template_id, variable_type_id, variable_value, group_id, created_by) 
VALUES ('$campaign_id', '$template_id', '$varTypeId', '$varValueid', '$groupId', '" . $loginData['user_id'] . "')";

                    if (!$db->query($insertVariableMappingQuery)) {
                        throw new Exception("Error inserting campaign variable mapping: " . $db->error);
                    }
                }
                $db->close();
                $resultArray = array(
                    "apiStatus" => array(
                        "code"    => "200",
                        "message" => "Campaign successfully created.",
                    ),
                );
            } else {
                throw new Exception("Error occurred while inserting campaign: " . $db->error);
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





    private function getDashboardDetails($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            if (empty($data['templateId'])) {
                throw new Exception("Invalid. Please enter your ID.");
            }

            // Get the total record count
            // Get the total contact count
            $contactCountQuery = "SELECT COUNT(DISTINCT con.id) AS contactCount FROM cmp_campaign AS c 
   JOIN cmp_group_contact_mapping AS gcm ON gcm.group_id = c.group_id
   JOIN cmp_contact AS con ON con.id = gcm.contact_id
   WHERE c.id='" . $data['templateId'] . "'";

            $contactCountResult = $db->query($contactCountQuery);
            $contactCountRow = $contactCountResult->fetch_assoc();
            $contactCount = $contactCountRow['contactCount'];

            // Fetch campaign details with contacts
            $queryService = "SELECT DISTINCT 
            c.id, c.title, c.template_id, c.created_date, 
            c.active_status, c.schedule_at, c.status AS campaignStatus, 
            wt.template_name, wt.language, 
            con.first_name, con.last_name, con.mobile, con.email,
            con.status AS contactStatus, con.created_date AS contactCreatedDate 
            FROM cmp_campaign AS c 
            JOIN cmp_whatsapp_templates AS wt ON wt.id = c.template_id
            JOIN cmp_group_contact_mapping AS gcm ON gcm.group_id = c.group_id
            JOIN cmp_contact AS con ON con.id = gcm.contact_id
            LEFT JOIN cmp_campaign_contact AS cc ON cc.contact_id = con.id AND cc.campaign_id = c.id
            WHERE c.id='" . $data['templateId'] . "' 
            AND c.status = 1 
            AND wt.status = 1 
            AND c.active_status = 1 
            AND c.created_by = '" . $loginData['user_id'] . "' 
            AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
            ORDER BY c.id DESC";
// print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);

            if ($row_cnt > 0) {
                $row = $result->fetch_assoc();

                $campaignDetails = [
                    "templateName" => $row['template_name'],
                    "campaignName" => $row['title'],
                    "tempalte_language" => $row['language'],
                    "scheduleAt" => $row['schedule_at'],
                    "status" => $row['campaignStatus']
                ];

                $campaignContactList = [];
                do {
                    $campaignContactList[] = [
                        "contactName" => trim($row['first_name'] . " " . $row['last_name']),
                        "mobile" => $row['mobile'],
                        "status" => $row['contactStatus'],
                        "createdStatus" => $row['contactCreatedDate']
                    ];
                } while ($row = $result->fetch_assoc());

                $responseArray = [
                    "totalContactCount" => $contactCount,
                    "campaignDetails" => $campaignDetails,
                    "campaignContactList" => $campaignContactList
                ];

                $resultArray = [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Campaign Dashboard details fetched successfully"
                    ],
                    "result" => $responseArray
                ];
            } else {
                $resultArray = [
                    "apiStatus" => [
                        "code" => "404",
                        "message" => "No data found..."
                    ]
                ];
            }

            return $resultArray;
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }



    private function deleteGroup($data)
    {
        // try {

        //     $id = $data[2];
        //     $db = $this->dbConnect();
        //     // Check if the ID is provided and valid
        //     if (empty($data[2])) {
        //         throw new Exception("Invalid. Please enter your ID.");
        //     }
        //     $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_group_contact WHERE id = $id AND status=1";
        //     // print_r($checkIdQuery);exit;
        //     $result = $db->query($checkIdQuery);
        //     $rowCount = $result->fetch_assoc()['count'];

        //     // If ID doesn't exist, return error
        //     if ($rowCount == 0) {
        //         $db->close();
        //         return array(
        //             "apiStatus" => array(
        //                 "code" => "400",
        //                 "message" => "Group does not exist",
        //             ),
        //         );
        //     }

        //     //update delete query

        //     $deleteQuery = "UPDATE cmp_group_contact
        //     SET status = 0 
        //     WHERE id = " . $id . "";

        //     // print_r($deleteQuery);exit;

        //     if ($db->query($deleteQuery) === true) {
        //         $db->close();
        //         $statusCode = "200";
        //         $statusMessage = "Group details deleted successfully";
        //     } else {
        //         $statusCode = "500";
        //         $statusMessage = "Unable to delete Group details, please try again later";
        //     }
        //     $resultArray = array(
        //         "apiStatus" => array(
        //             "code" => $statusCode,
        //             "message" => $statusMessage,
        //         ),
        //     );
        //     return $resultArray;
        // } catch (Exception $e) {
        //     throw new Exception($e->getMessage());
        // }
    }
    //Group ative and deactive
    public function campaignactive($data, $loginData)
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
    public function campaigndeactive($data, $loginData)
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

    // public function importStoreFromExcel($data, $loginData)
    // {
    //     $resultArray = [];
    //     try {
    //         $db = $this->dbConnect();

    //         // Validate file upload
    //         if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
    //             throw new Exception("No valid file uploaded.");
    //         }

    //         $file = $_FILES['file'];
    //         $filePath = $file['tmp_name'];
    //         $fileType = $file['type'];
    //         $fileSize = $file['size'];

    //         // Allowed file types
    //         $allowedTypes = [
    //             'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // XLSX
    //             'application/vnd.ms-excel' // XLS
    //         ];

    //         if (!in_array($fileType, $allowedTypes)) {
    //             throw new Exception("Invalid file type. Please upload an Excel file.");
    //         }

    //         // Check file size (Max: 5MB)
    //         if ($fileSize > 5 * 1024 * 1024) {
    //             throw new Exception("File size exceeds 5MB limit.");
    //         }

    //         // Read Excel file
    //         $spreadsheet = IOFactory::load($filePath);
    //         $sheet = $spreadsheet->getActiveSheet();
    //         $rows = $sheet->toArray(null, true, true, true);

    //         if (empty($rows) || count($rows) < 2) {
    //             throw new Exception("Excel file is empty or invalid format.");
    //         }

    //         unset($rows[1]); // Remove header row

    //         // Get the Vendor ID from the login data
    //         $user_id = $loginData['user_id'];
    //         $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '$user_id'";
    //         $result = $db->query($sql);

    //         if (!$result || $result->num_rows === 0) {
    //             throw new Exception("Vendor ID not found for user ID: $user_id");
    //         }

    //         $vendorRow = $result->fetch_assoc();
    //         $vendor_id = $vendorRow['vendor_id'];
    //         $uid = bin2hex(random_bytes(8));
    //         // Process Excel Rows
    //         foreach ($rows as $row) {
    //             $storeName = trim($row['B']);
    //             $address   = trim($row['C']);
    //             $phone     = trim($row['D']);
    //             $email     = trim($row['E']);

    //             if (empty($storeName) || empty($address) || empty($phone) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //                 throw new Exception("Invalid data found in row: " . json_encode($row));
    //             }

    //             // Check if Store Exists
    //             $storeQuery = "SELECT id FROM cmp_store WHERE store_name = '$storeName' LIMIT 1";
    //             $storeResult = $db->query($storeQuery);

    //             if ($storeResult->num_rows > 0) {
    //                 $storeRow = $storeResult->fetch_assoc();
    //                 $store_id = $storeRow['id'];
    //             } else {

    //                 // Insert Store into cmp_store
    //                 $insertStoreQuery = "INSERT INTO cmp_store (uid,store_name, address, phone, email, created_by, status) 
    //                                      VALUES (' $uid ','$storeName', '$address', '$phone', '$email', '$user_id', 1)";
    //                 if (!$db->query($insertStoreQuery)) {
    //                     throw new Exception("Error inserting Group: " . $db->error);
    //                 }
    //                 $store_id = $db->insert_id;
    //             }

    //             // Check if Store is Already Mapped to Vendor
    //             $mappingQuery = "SELECT id FROM cmp_vendor_store_mapping WHERE vendor_id = '$vendor_id' AND store_id = '$store_id' LIMIT 1";
    //             $mappingResult = $db->query($mappingQuery);

    //             if ($mappingResult->num_rows === 0) {
    //                 // Insert Mapping into cmp_vendor_store_mapping
    //                 $insertMappingQuery = "INSERT INTO cmp_vendor_store_mapping (vendor_id, store_id) VALUES ('$vendor_id', '$store_id')";
    //                 if (!$db->query($insertMappingQuery)) {
    //                     throw new Exception("Error mapping Group to vendor: " . $db->error);
    //                 }
    //             }
    //         }

    //         return ["apiStatus" => ["code" => "200", "message" => "Excel file uploaded and processed successfully."]];
    //     } catch (Exception $e) {
    //         return ["apiStatus" => ["code" => "401", "message" => $e->getMessage()]];
    //     }
    // }

    //export the data to excel 
    // public function exportStoreToExcel($data, $loginData)
    // {

    //     try {
    //         $db = $this->dbConnect();
    //         $spreadsheet = new Spreadsheet();
    //         $sheet = $spreadsheet->getActiveSheet();

    //         //Get the Contact id from the login data
    //         $user_id = $loginData['user_id'];
    //         $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '$user_id'";
    //         $result = $db->query($sql);

    //         if ($result) {
    //             $row = $result->fetch_assoc();
    //             if (!$row || !isset($row['vendor_id'])) {
    //                 throw new Exception("Vendor ID not found for user ID: $user_id");
    //             }
    //             $vendor_id = $row['vendor_id'];
    //         } else {
    //             throw new Exception("Database query failed: " . $db->error);
    //         }
    //         // Set column headers
    //         $headers = ['S.No',  'Store Name', 'Address',  'Phone', 'Email'];
    //         $column = 'A';
    //         foreach ($headers as $header) {
    //             $sheet->setCellValue($column . '1', $header);
    //             $sheet->getStyle($column . '1')->getFont()->setBold(true); // Make text bold
    //             $sheet->getStyle($column . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Center align
    //             $sheet->getColumnDimension($column)->setAutoSize(true); // Auto-adjust column width
    //             $column++;
    //         }
    //         // Fetch staff details
    //         $query = "SELECT*
    //          FROM cmp_store c 
    //               WHERE status = 1  AND created_by='" . $loginData['user_id'] . "'";
    //         // print_r($query);exit;
    //         $result = $db->query($query);

    //         if ($result->num_rows === 0) {
    //             throw new Exception("No Contact data found.");
    //         }

    //         $rowIndex = 2; // Start from row 2 (row 1 is headers)
    //         $sno = 1; // Initialize serial number
    //         while ($row = $result->fetch_assoc()) {
    //             $column = 'A';
    //             $sheet->setCellValue($column++ . $rowIndex, $sno++);
    //             $sheet->setCellValue($column++ . $rowIndex, $row['store_name']);
    //             $sheet->setCellValue($column++ . $rowIndex, $row['address']);
    //             $sheet->setCellValue($column++ . $rowIndex, $row['phone']);
    //             $sheet->setCellValue($column++ . $rowIndex, $row['email']);
    //             $sheet->getStyle('A' . $rowIndex . ':' . $column . $rowIndex)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Center align
    //             $rowIndex++;
    //         }

    //          // **Output the file directly for download**
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="Staff_Data_' . date('Ymd_His') . '.xlsx"');
    //     header('Cache-Control: max-age=0');

    //     $writer = new Xlsx($spreadsheet);
    //     $writer->save('php://output'); // Send file to browser directly

    //     exit; 

    //     } catch (Exception $e) {
    //         return [
    //             "apiStatus" => [
    //                 "code" => "401",
    //                 "message" => $e->getMessage()
    //             ]
    //         ];
    //     }
    // }



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
               WHERE cs.status = 1 AND vendor_id = $vendor_id";

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

    // public function getcampaigndropdown($data, $loginData)
    // {
    //     try {
    //         $GroupData = array();
    //         $db = $this->dbConnect();

    //         // Get user_id from loginData
    //         $userId = $loginData['user_id'];

    //         // Query to get Group details based on user_id -> vendor_id -> store_id
    //         $queryService = "SELECT id, group_name FROM cmp_group_contact WHERE status = 1 AND vendor_id = " . $this->getVendorIdByUserId($loginData) . " ORDER BY id DESC";

    //         $result = $db->query($queryService);

    //         if (!$result) {
    //             throw new Exception("Database Query Failed: " . $db->error);
    //         }

    //         $row_cnt = mysqli_num_rows($result);

    //         while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    //             $GroupData[] = $row;
    //         }


    //         $responseArray = array(
    //             "totalRecordCount" => $row_cnt,
    //             "GroupDataDropDown" => $GroupData,
    //         );

    //         return array(
    //             "apiStatus" => array(
    //                 "code" => "200",
    //                 "message" => "Group Dropdown details fetched successfully",
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
    // public function getpayloadstructure($data, $loginData)
    // {
    //     // Initialize result array
    //     $resultArray = array();
    //     try {
    //         $db = $this->dbConnect();




    //         // Check if the user already exists
    //         $sql = "SELECT payload FROM cmp_mst_wa_temp_payload_strc WHERE type = '" . $data['type'] . "' AND status = 1 ";
    //         // print_r(   $sql);exit;
    //         $result = mysqli_query($db, $sql);
    //         if (mysqli_num_rows($result) > 0) {
    //             $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    //             // print_r($data);exit;   
    //             $string = trim(preg_replace('/\s\s+/', ' ', $data['payload']));
    //             $resultArray = array(
    //                 "apiStatus" => array(
    //                     "code" => "200",
    //                     "message" => "Payload details fetched successfully",
    //                     "payload" => json_decode($string)
    //                 ),
    //             );
    //         } else {
    //             $resultArray = array(
    //                 "apiStatus" => array(
    //                     "code" => "404",
    //                     "message" => "No data found.",
    //                 )
    //             );
    //         }
    //         // $resultArray = array(
    //         //         "apiStatus" => array(
    //         //             "code"    => "200",
    //         //             "message" => "Group details successfully created.",
    //         //             "payload"=>$data['payload']
    //         //         ),
    //         //     );
    //         // }


    //         // if ($db->query($sql) === true) {

    //         //     $db->close();

    //         //     $resultArray = array(
    //         //         "apiStatus" => array(
    //         //             "code"    => "200",
    //         //             "message" => "Group details successfully created.",
    //         //             "payload"=>$result
    //         //         ),
    //         //     );
    //         // } 
    //         // else {
    //         //     throw new Exception("Error occurred while inserting Group: " . $db->error);
    //         // }
    //     } catch (Exception $e) {
    //         if (isset($db)) {
    //             $db->close();
    //         }
    //         $resultArray = array(
    //             "apiStatus" => array(
    //                 "code"    => "401",
    //                 "message" => $e->getMessage(),
    //             ),
    //         );
    //     }
    //     return $resultArray;
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
