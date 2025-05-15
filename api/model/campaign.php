<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";
require_once "model/whatsapp_template.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../../vendor/autoload.php';

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
                } elseif ($urlParam[1] === 'dashboard') {
                    $result = $this->campaignactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "active") {
                    $result = $this->campaignactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactive") {
                    $result = $this->campaigndeactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'exporttoexcel') {
                    if ($urlParam[2] === 'queue') {
                        $result = $this->exportwhatsappQueuetoexcel($data, $loginData, $urlParam);
                        return $result;
                    } else if ($urlParam[2] === 'executed') {
                        $result = $this->exportwhatsappQueuetoexcel($data, $loginData, $urlParam);
                        return $result;
                    } else {
                        throw new Exception("Unable to proceed your request!");
                    }
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
                    $paramLength = count($urlParam);
                    if ($paramLength == 3) {
                        if ($urlParam[2] === 'dashboard') {
                            $result = $this->getDashboardDetails($data, $loginData);
                            return $result;
                        } else {
                            throw new Exception("Unable to proceed your request!");
                        }
                    } elseif ($paramLength == 4) {
                        if ($urlParam[3] === 'queue') {
                            $result = $this->getCampaignQueueDetails($data, $loginData, $urlParam);
                            return $result;
                        } else if ($urlParam[3] === 'executed') {
                            $result = $this->getCampaignQueueDetails($data, $loginData, $urlParam);
                            return $result;
                        } else {
                            throw new Exception("Unable to proceed your request!");
                        }
                    }
                    $result = $this->getCampaignDetails($data, $loginData);
                    return $result;
                } else if ($urlParam[1] === 'archive') {
                    if ($urlParam[2] === 'list') {
                        $result = $this->getCampaignArchiveDetails($data, $loginData);
                        return $result;
                    }
                } elseif ($urlParam[1] == "activeachieve") {
                    $result = $this->campaignactiveachieve($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactiveachieve") {
                    $result = $this->campaigndeactiveachieve($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);

                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    $result = $this->deletecampaign($data);
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
            $queryService = "SELECT c.id,c.title,c.template_id,c.created_date,c.active_status,c.schedule_at,c.send_status,c.status AS campaignStatus,wt.template_name,wt.language
                 FROM cmp_campaign AS c 
                 JOIN cmp_whatsapp_templates AS wt ON wt.id = c.template_id
                 WHERE c.status = 1 AND wt.status=1 AND c.active_status=1 AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
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
                        "sendStatus" => $row['send_status'],
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
            $sql = "SELECT c.id,c.title,c.template_id,c.created_date,c.active_status,c.schedule_at,c.send_status,c.status AS campaignStatus,wt.template_name,wt.language
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
                        "sendStatus" => $row['send_status'],
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
        // print_r($data);exit;
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
            $vendor_id = $this->getVendorIdByUserId($loginData);
            // Fetch Template ID
            $templateId = mysqli_real_escape_string($db, $data['templateId']);
            $sql = "SELECT id FROM cmp_whatsapp_templates WHERE template_id = '$templateId' AND status = 1";
            $result = mysqli_query($db, $sql);

            if (!$result || mysqli_num_rows($result) == 0) {
                throw new Exception("Template ID not found");
            }
            $row = mysqli_fetch_assoc($result);
            $template_id = $row['id'];

            // Fetch Group Details
            $groupId =  $data['group']['groupId'];
            $groupName =  $data['group']['groupName'];

            $sql = "SELECT COUNT(*) as count FROM cmp_group_contact WHERE id = '$groupId' AND group_name = '$groupName' AND status = 1";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_assoc($result);

            if ($row['count'] == 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code"    => "400",
                        "message" => "Group does not exist",
                    ],
                ];
            }

            // Fetch Timezone details
            $timezone_zoneName = mysqli_real_escape_string($db, $data['timezone']['zoneName']);
            $timezone_id = mysqli_real_escape_string($db, $data['timezone']['id']);
            // Check if timezone exists in cmp_mst_timezone
            $checkTimezoneQuery = "SELECT COUNT(*)
            as count FROM cmp_mst_timezone WHERE id = '$timezone_id' AND timezone_name = '$timezone_zoneName'";
            $result = $db->query($checkTimezoneQuery);
            $row = $result->fetch_assoc();

            if ($row['count'] == 0) {
                // throw new Exception("Invalid timezone ID or zone name.");
            }

            // Validate Variables
            foreach ($data['variableIds'] as $variable) {
                // Validate Header Variables
                if ($variable['type'] === 'header') {
                    foreach ($variable['variables'] as $var) {
                        $varValueId = mysqli_real_escape_string($db, $var['varValue']['varTypeId']);
                        $varValueName = mysqli_real_escape_string($db, $var['varValue']['varTypeName']);

                        $sql = "SELECT COUNT(*) as count FROM cmp_mst_variable WHERE id = '$varValueId' AND variable_name = '$varValueName'";
                        $result = mysqli_query($db, $sql);
                        if (!$result || mysqli_fetch_assoc($result)['count'] == 0) {
                            throw new Exception("Invalid header variable ID or variable name.");
                        }
                    }
                }

                // Validate Body Variables
                if ($variable['type'] === 'body') {
                    foreach ($variable['variables'] as $var) {
                        $varValueId = mysqli_real_escape_string($db, $var['varValue']['varTypeId']);
                        $varValueName = mysqli_real_escape_string($db, $var['varValue']['varTypeName']);

                        $sql = "SELECT COUNT(*) as count FROM cmp_mst_variable WHERE id = '$varValueId' AND variable_name = '$varValueName'";
                        $result = mysqli_query($db, $sql);
                        if (!$result || mysqli_fetch_assoc($result)['count'] == 0) {
                            throw new Exception("Invalid body variable ID or variable name.");
                        }
                    }
                }
            }


            // Schedule Time
            $scheduleAt = !empty($data['scheduleStatus']) && $data['scheduleStatus'] === true
                ? mysqli_real_escape_string($db, $data['scheduledAt'])
                : date("Y-m-d H:i:s");

            // Insert Campaign
            $title = mysqli_real_escape_string($db, $data['title']);
            $mediaId = mysqli_real_escape_string($db, $data['mediaId']);
            $restrictLangCode = mysqli_real_escape_string($db, $data['restrictLangCode']);
            $sendNum = mysqli_real_escape_string($db, $data['SendNum']);
            $createdBy = mysqli_real_escape_string($db, $loginData['user_id']);
            $date = date("Y-m-d H:i:s");
            $sql = "INSERT INTO cmp_campaign (group_id, template_id, title, restrictLangCode, timezone, schedule_at, send_status,send_num,media_id, created_by,created_date) 
        VALUES ('$groupId', '$template_id', '$title', '$restrictLangCode', '$timezone_zoneName', '$scheduleAt','Scheduled' ,'$sendNum','$mediaId', '$createdBy','$date')";
// print_r($sql);exit;
            if (!mysqli_query($db, $sql)) {
                throw new Exception("Error inserting campaign: " . mysqli_error($db));
            }
            $campaign_id = mysqli_insert_id($db);


            // Write to file.txt after successful insert
            if ($data['scheduleStatus'] === true) {
                $fileData = "CampaignID: $campaign_id | vendorId: $vendor_id | Timezone: $timezone_zoneName | ScheduleAt: $scheduleAt |createdBy: {$loginData['user_id']}| Status: Scheduled" . PHP_EOL;
                file_put_contents("file.txt", $fileData, FILE_APPEND); // Appends to the file
            }


            // Send WhatsApp Message
            $call = new WHATSAPPTEMPLATEMODEL();
            $resulthhtp = $call->processQueue($data, $loginData, $campaign_id, "campaign");
            // print_r($resulthhtp['apiStatus']['code']);exit;

            // if ($resulthhtp['apiStatus']['code'] !== "200") {
            //     throw new Exception("Message sending failed: " . $resulthhtp['apiStatus']['message']);
            // }

            // If not scheduled, update the send_status to 'sent'
            if (empty($data['scheduleStatus']) || $data['scheduleStatus'] === false) {
                $updateSql = "UPDATE cmp_campaign SET send_status = 'Executed' WHERE id = '$campaign_id'";
                if (!mysqli_query($db, $updateSql)) {
                    throw new Exception("Error updating send status: " . mysqli_error($db));
                }
            }

            foreach ($data['variableIds'] as $variable) {
                if (isset($variable['type'])) {
                    $type = mysqli_real_escape_string($db, $variable['type']); // Prevent SQL injection

                    foreach ($variable['variables'] as $var) {
                        $vartypeId = mysqli_real_escape_string($db, $var['varName']);
                        $varValueId = mysqli_real_escape_string($db, $var['varValue']['varTypeId']);
                        $varValueName = mysqli_real_escape_string($db, $var['varValue']['varTypeName']);

                        $sqlW = "INSERT INTO cmp_campaign_variable_mapping (campaign_id, template_id, type, variable_type_id, variable_value, group_id, created_by) 
                             VALUES ('$campaign_id', '$template_id', '$type', '$vartypeId', '$varValueId', '$groupId', '$createdBy')";

                        if (!mysqli_query($db, $sqlW)) {
                            die("Error inserting variable mapping: " . mysqli_error($db));
                        }
                    }
                }
            }


            // print_r($sqlW);exit;

            $db->close();
            return [
                "apiStatus" => [
                    "code"    => "200",
                    "message" => "Campaign successfully created and message sent.",
                ],
            ];
        } catch (Exception $e) {
            if (isset($db)) {
                $db->close();
            }
            return [
                "apiStatus" => [
                    "code"    => "401",
                    "message" => $e->getMessage(),
                ],
            ];
        }
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
   left JOIN cmp_group_contact_mapping AS gcm ON gcm.group_id = c.group_id
    left JOIN cmp_contact AS con ON con.id = gcm.contact_id
   WHERE c.id='" . $data['templateId'] . "'";

            $contactCountResult = $db->query($contactCountQuery);
            $contactCountRow = $contactCountResult->fetch_assoc();
            $contactCount = $contactCountRow['contactCount'];

            // Fetch campaign details with contacts
            $queryService = "SELECT DISTINCT 
            c.id, c.title, c.template_id, c.created_date, c.send_status,
            c.active_status, c.schedule_at, c.created_by,c.status AS campaignStatus, 
            wt.template_name, wt.language, 
            con.first_name, con.last_name, con.mobile, con.email,
            con.status AS contactStatus, con.created_date AS contactCreatedDate 
            FROM cmp_campaign AS c 
           LEFT JOIN cmp_whatsapp_templates AS wt ON wt.id = c.template_id
            LEFT JOIN cmp_group_contact_mapping AS gcm ON gcm.group_id = c.group_id
            LEFT JOIN cmp_contact AS con ON con.id = gcm.contact_id
            LEFT JOIN cmp_campaign_contact AS cc ON cc.contact_id = con.id AND cc.campaign_id = c.id
            WHERE c.id='" . $data['templateId'] . "' 
            AND c.status = 1 
            AND wt.status = 1 
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
                    "sendStatus" => $row['send_status'],
                    "createdAt" => $row['created_date'],
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
                    // "campaignContactList" => $campaignContactList
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



    private function deletecampaign($data)
    {
        try {
            // print_r($data);exit;
            $id = $data[2];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_campaign WHERE id = $id AND status=1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Campaign does not exist",
                    ),
                );
            }

            //update delete query

            $deleteQuery = "UPDATE cmp_campaign
            SET status = 0 
            WHERE id = " . $id . "";

            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                // Update file.txt: remove line with the given CampaignID
                $filePath = "file.txt";
                if (file_exists($filePath)) {
                    $lines = file($filePath, FILE_IGNORE_NEW_LINES);
                    $newLines = [];

                    foreach ($lines as $line) {
                        if (strpos($line, "CampaignID: $id ") === false) {
                            $newLines[] = $line;
                        }
                    }

                    file_put_contents($filePath, implode(PHP_EOL, $newLines) . PHP_EOL);
                }
                $db->close();
                $statusCode = "200";
                $statusMessage = "Campaign details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete Campaign details, please try again later";
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

    public function getCampaignQueueDetails($data, $loginData, $urlParam)
    {
        try {
            $db = $this->dbConnect();
            // Validate pageIndex and dataLength
            if (isset($data['pageIndex']) && isset($data['pageIndex'])) {
                $start_index = $data['pageIndex'] * $data['dataLength'];
                $end_index = $data['dataLength'];
            }

            $campaign_id = mysqli_real_escape_string($db, $data['campaignId']);
            // Fetch queue data for the given campaign
            $query = "SELECT wmq.id, c.first_name, c.last_name, wmq.phone_number, wmq.template_name, wmq.message_status, wmq.updated_date, wmq.error_message
                        FROM cmp_whatsapp_message_queue AS wmq
                        LEFT JOIN cmp_contact AS c ON c.mobile = wmq.phone_number AND c.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
                        WHERE wmq.campaign_id = '$campaign_id' AND wmq.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
                        ";
            if ($urlParam[3] === 'queue') {
                $query .= " AND wmq.message_status = 'queued'";
                $message = "queue";
            } else if ($urlParam[3] === 'executed') {
                $query .= " AND wmq.message_status != 'queued'";
                $message = "executed";
            }
            if ($data['filterBy']) {
                $query .= " AND (wmq.phone_number LIKE '%" . mysqli_real_escape_string($db, $data['filterBy']) . "%' or wmq.template_name LIKE '%" . mysqli_real_escape_string($db, $data['filterBy']) . "%' 
                            or wmq.message_status LIKE '%" . mysqli_real_escape_string($db, $data['filterBy']) . "%' )";
            }
            $query .= " ORDER BY wmq.created_date DESC";

            if (isset($data['pageIndex']) && isset($data['dataLength'])) {
                $query .= " LIMIT $start_index, $end_index";
            }

            $result = mysqli_query($db, $query);
            $rowCnt = mysqli_num_rows($result);
            if ($rowCnt > 0) {
                $totalCount = $this->getQueueTotalCount($campaign_id);
                $queueData = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $queueData[] = $row;
                }
                return array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Campaign $message data listed successfully"
                    ),
                    "totalRecordCount" => $totalCount,
                    "responseCount" => $rowCnt,
                    $message . 'Data' => $queueData
                );
            } else {
                throw new Exception("No data found");
            }
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }

    //Campaign add this new function
    private function getQueueTotalCount($campaign_id)
    {
        try {
            $db = $this->dbConnect();
            $sql = "SELECT COUNT(*) as count
            FROM cmp_whatsapp_message_queue
            WHERE campaign_id = '$campaign_id'";
            // print_r($sql);exit; 
            $result = $db->query($sql);
            $row = $result->fetch_assoc();
            return $row['count'];
        } catch (Exception $e) {
            return array(
                "result" => "401",
                "message" => $e->getMessage(),
            );
        }
    }

    public function campaignactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_campaign WHERE id = $id AND status=1";

            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Campaign ID does not exist",
                    ),
                );
            }
            $ActiveQuery = "UPDATE cmp_campaign SET active_status = 1 WHERE status = 1 AND id = $id";

            if ($db->query($ActiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Campaign activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate Campaign, please try again later.";
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
    //Group ative and deactive
    public function campaignactiveachieve($data, $loginData)
    {
        try {
            // Validate 'id' key
            if (!isset($data['id']) || empty($data['id'])) {
                throw new Exception("Bad request. 'id' field is required.");
            }

            // Normalize to array
            $ids = is_array($data['id']) ? $data['id'] : [$data['id']];
            $ids = array_map('intval', $ids); // Sanitize IDs

            $db = $this->dbConnect();
            $idList = implode(',', $ids);

            // Step 1: Check if all campaign IDs exist (status = 1)
            $checkQuery = "SELECT id FROM cmp_campaign WHERE id IN ($idList) AND status = 1";
            $result = $db->query($checkQuery);

            $foundIds = [];
            while ($row = $result->fetch_assoc()) {
                $foundIds[] = $row['id'];
            }

            $missingIds = array_diff($ids, $foundIds);

            if (!empty($missingIds)) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "These campaign IDs do not exist or are inactive: " . implode(', ', $missingIds),
                    ),
                );
            }

            // Step 2: Activate all valid campaigns
            $activateQuery = "UPDATE cmp_campaign SET active_status = 1 WHERE id IN ($idList) AND status = 1";
            if ($db->query($activateQuery) === true) {
                $statusCode = "200";
                $statusMessage = "Campaign(s) activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate campaign(s), please try again.";
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
    public function campaigndeactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }
            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_campaign WHERE id = $id AND active_status=1 AND status=1";
            // print_r($checkIdQuery);exit;

            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                $statusCode = "400";
                $statusMessage = "Campaign ID does not exist.";
                return array(
                    "apiStatus" => array(
                        "code" => $statusCode,
                        "message" => $statusMessage,
                    ),
                );
            }
            $deactiveQuery = "UPDATE cmp_campaign SET active_status = 0 WHERE status = 1 AND id = $id";

            if ($db->query($deactiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Campaign Deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to Deactivate Campaign, please try again later.";
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
    public function campaigndeactiveachieve($data, $loginData)
    {
        try {
            // Validate input
            if (!isset($data['id']) || empty($data['id'])) {
                throw new Exception("Bad request. 'id' field is required.");
            }

            $ids = is_array($data['id']) ? $data['id'] : [$data['id']];
            $ids = array_map('intval', $ids); // Sanitize all IDs

            $db = $this->dbConnect();

            // Convert array to comma-separated string for SQL IN clause
            $idList = implode(',', $ids);

            // Step 1: Check all IDs exist and are active
            $checkQuery = "SELECT id FROM cmp_campaign WHERE id IN ($idList) AND active_status = 1 AND status = 1";
            $result = $db->query($checkQuery);

            $foundIds = [];
            while ($row = $result->fetch_assoc()) {
                $foundIds[] = $row['id'];
            }

            // Find missing or inactive IDs
            $missingIds = array_diff($ids, $foundIds);

            if (!empty($missingIds)) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "These campaign IDs are invalid or already deactivated: " . implode(', ', $missingIds),
                    ),
                );
            }

            // Step 2: All IDs are valid — proceed to deactivate
            $deactiveQuery = "UPDATE cmp_campaign SET active_status = 0 WHERE id IN ($idList) AND status = 1";
            if ($db->query($deactiveQuery) === true) {
                $statusCode = "200";
                $statusMessage = "Campaign(s) deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Failed to deactivate campaign(s).";
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



    public function exportwhatsappQueuetoexcel($data, $loginData, $urlParam)
    {
        try {

            // print_r($urlParam);exit;
            // Check if loginData exists and contains user_id
            if (!isset($loginData['user_id'])) {
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "Missing user_id in loginData."
                    ]
                ];
            }

            $db = $this->dbConnect();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set column headers
            $headers = ['S.No', 'First Name', 'Last Name', 'Mobile Number', 'Template Name', 'Message Status', 'Updated Date', 'Reason'];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true);
                $sheet->getStyle($column . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $column++;
            }

            // Fetch staff details
            $campaign_id = $data[3];
            if (empty($campaign_id)) {
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "Campaign ID is required."
                    ]
                ];
            }
            // Fetch queue data for the given campaign
            $query =  "SELECT wmq.id, c.first_name, c.last_name, wmq.phone_number, wmq.template_name, wmq.message_status, wmq.updated_date, wmq.error_message
                        FROM cmp_whatsapp_message_queue AS wmq
                        LEFT JOIN cmp_contact AS c ON c.mobile = wmq.phone_number AND c.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
                        WHERE wmq.campaign_id = '$campaign_id' AND wmq.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
                        ";
            if ($urlParam[2] === 'queue') {
                $query .= " AND wmq.message_status = 'queued'";
                $message = "queue";
            } else if ($urlParam[2] === 'executed') {
                $query .= " AND wmq.message_status != 'queued'";
                $message = "executed";
            }

            $query .= " ORDER BY wmq.created_date DESC";


            // print_r($query);exit;
            $result = $db->query($query);

            if ($result->num_rows === 0) {
                return [
                    "apiStatus" => [
                        "code" => "204",
                        "message" => "No data found."
                    ]
                ];
            }

            // Status mapping
            $statusMap = [
                'queued' => 'Queued',
                'sent' => 'Sent',
                'failed' => 'Failed',
                'delivered' => 'Delivered',
                'undelivered' => 'Failed',
                'expired' => 'Failed'
            ];

            // Fill data
            $rowIndex = 2;
            $sno = 1;
            while ($row = $result->fetch_assoc()) {
                $messageStatus = $statusMap[$row['message_status']] ?? ucfirst($row['message_status']);

                $sheet->setCellValue('A' . $rowIndex, $sno);
                $sheet->setCellValue('B' . $rowIndex, $row['first_name']);
                $sheet->setCellValue('C' . $rowIndex, $row['last_name']);
                $sheet->setCellValue('D' . $rowIndex, $row['phone_number']);
                $sheet->setCellValue('E' . $rowIndex, $row['template_name']);
                $sheet->setCellValue('F' . $rowIndex, $messageStatus);
                $sheet->setCellValue('G' . $rowIndex, $row['updated_date']);
                $sheet->setCellValue('H' . $rowIndex, $row['error_message']);

                $rowIndex++;
                $sno++;
            }

            // Output Excel file to browser
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="WhatsApp_' . $message . '_data_' . date('Ymd_His') . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output'); // Send file to browser

            exit;
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "500",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }

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
    public function getDashboardactive($data, $loginData)
    {
        try {
            $id = $data[3];
            $db = $this->dbConnect();
            if (empty($data[3])) {
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

    public function getCampaignArchiveDetails($data, $loginData)
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
     WHERE c.status = 1  AND wt.status = 1 AND c.active_status=0
     AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData);
            // print_r($countQuery);exit;
            $countResult = $db->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $recordCount = $countRow['totalCount']; // Total record count

            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT c.id,c.title,c.template_id,c.created_date,c.active_status,c.schedule_at,c.send_status,c.status AS campaignStatus,wt.template_name,wt.language
              FROM cmp_campaign AS c 
              JOIN cmp_whatsapp_templates AS wt ON wt.id = c.template_id
              WHERE c.status = 1 AND c.active_status=0 AND wt.status=1 AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
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
                        "sendStatus" => $row['send_status'],
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
    //multiple Delete for campaign Id 

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
                $deleteQuery = "UPDATE cmp_group_contact SET status = 0 AND active_status= 0 WHERE id = $id ";
                if ($db->query($deleteQuery) === true) {
                    $deleted[] = [
                        'id' => $id,
                        'status' => "200",
                        'message' => 'Group details deleted successfully'
                    ];
                } else {
                    $failed[] = [
                        'id' => $id,
                        'status' => "500",
                        'message' => 'Unable to delete Group details, please try again later'
                    ];
                }
            }

            $db->close();

            return [
                'apiStatus' => [
                    'code' => count($failed) > 0 ? "400" : "200",
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
