<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";
require_once "model/sms.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../../vendor/autoload.php';
class SMSCampaignMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getsmscampaign($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "active") {
                    $result = $this->smscampaignactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactive") {
                    $result = $this->Smscampaigndeactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'exporttoexcel') {
                    if ($urlParam[2] === 'queue') {
                        $result = $this->exportsmsQueuetoexcel($data, $loginData, $urlParam);
                        return $result;
                    } else if ($urlParam[2] === 'executed') {
                        $result = $this->exportsmsQueuetoexcel($data, $loginData, $urlParam);
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
                    $result = $this->createSmsCampaign($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "report") {
                    $result = $this->reportSmscampaign($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "exceldownload") {
                    $result = $this->exportSmsCampaignToExcel($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    $paramLength = count($urlParam);
                    if ($paramLength == 3) {
                        if ($urlParam[2] === 'dashboard') {
                            $result = $this->getSmsDashboardDetails($data, $loginData);
                            return $result;
                        } else {
                            throw new Exception("Unable to proceed your request!");
                        }
                    } elseif ($paramLength == 4) {
                        if ($urlParam[3] === 'queue') {
                            $result = $this->getsmsCampaignQueueDetails($data, $loginData, $urlParam);
                            return $result;
                        } else if ($urlParam[3] === 'executed') {
                            $result = $this->getsmsCampaignQueueDetails($data, $loginData, $urlParam);
                            return $result;
                        } else {
                            throw new Exception("Unable to proceed your request!");
                        }
                    }
                    $result = $this->getSmsCampaignDetails($data, $loginData);
                    return $result;
                } else if ($urlParam[1] === 'archive') {
                    if ($urlParam[2] === 'list') {
                        $result = $this->getCampaignArchiveDetails($data, $loginData);
                        return $result;
                    }
                    // } elseif ($urlParam[1] == "activeachieve") {
                    //     $result = $this->campaignactiveachieve($data, $loginData);
                    //     return $result;
                    // } elseif ($urlParam[1] == "deactiveachieve") {
                    //     $result = $this->campaigndeactiveachieve($data, $loginData);
                    //     return $result;
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
                    $result = $this->deleteSmscampaign($data);
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

    public function getSmsCampaignDetails($data, $loginData)
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
                 FROM cmp_sms_campaign AS c 
                 JOIN cmp_sms_templates AS wt ON wt.id = c.template_id
        WHERE c.status = 1  
        AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData);
            // print_r($countQuery);exit;
            $countResult = $db->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $recordCount = $countRow['totalCount']; // Total record count

            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT c.id,c.title,c.template_id,c.created_date,c.active_status,c.schedule_at,c.send_status,c.status AS campaignStatus,wt.template_name,wt.language
                 FROM cmp_sms_campaign AS c 
                 LEFT JOIN cmp_sms_templates AS wt ON wt.id = c.template_id
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
                        "message" => " Sms Campaign details fetched successfully",
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
    public function getsmscampaign($data, $loginData)
    {
        try {
            $id = $data[2];
            if (empty($id)) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $sql = "SELECT c.id,c.title,c.template_id,c.created_date,c.active_status,c.schedule_at,c.send_status,c.status AS campaignStatus,wt.template_name,wt.language
                 FROM cmp_sms_campaign AS c 
                 JOIN cmp_sms_templates AS wt ON wt.id = c.template_id
                      WHERE c.id = $id AND c.status = 1 AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData) . " 
                      ";

            // print_r($sql);exit;
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
                        "message" => "Sms Campaign detail fetched successfully",
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

    private function reportSmscampaign($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            // Validate pageIndex and dataLength
            // Check if pageIndex and dataLength are not empty
            // if ($data['pageIndex'] === "") {
            //     throw new Exception("PageIndex should not be empty!");
            // }
            // if ($data['dataLength'] == "") {
            //     throw new Exception("dataLength should not be empty!");
            // }

            $start_index = $data['pageIndex'] * $data['dataLength'];
            $end_index = $data['dataLength'];

            // Validate input dates
            $fromDate = !empty($data['fromDate']) ? $db->real_escape_string($data['fromDate']) : null;
            $toDate = !empty($data['toDate']) ? $db->real_escape_string($data['toDate']) : null;

            // if (!$fromDate || !$toDate) {
            //     throw new Exception("Please provide both fromDate and toDate.");
            // }

            $vendorId = $this->getVendorIdByUserId($loginData);

            // Summary of message status counts by date
            $statusQuery = " SELECT 
                DATE(created_date) AS date,
                COUNT(*) AS submitted, 
                SUM(message_status = 'delivered') AS delivered,
                SUM(message_status = 'failed') AS failed,
                SUM(message_status = 'queued') AS awaited
            FROM cmp_sms_messages
            WHERE vendor_id = '$vendorId'
            
        ";

            if ($data['campaignId'] != "") {
                $statusQuery .= " AND campaign_id = '" . $data['campaignId'] . "'";
            }
            if ($fromDate != "" && $toDate != "") {
                $statusQuery .= " AND DATE(created_date) BETWEEN '$fromDate' AND '$toDate' 
                GROUP BY DATE(created_date)
                ORDER BY DATE(created_date) ASC";
            }
            if ($start_index && $end_index) {
                $statusQuery .= " LIMIT $start_index, $end_index";
            }

            // print_r($statusQuery);exit;
            $statusResult = $db->query($statusQuery);
            $rowCount = $statusResult->num_rows;
            $smsCampaignDetails = [];
            $totalSubmitted = $totalDelivered = $totalFailed = $totalAwaited = 0;

            while ($row = $statusResult->fetch_assoc()) {
                $submittedCount = (int)$row['submitted'];
                $deliveredCount = (int)$row['delivered'];
                $failedCount = (int)$row['failed'];
                $awaitedCount = (int)$row['awaited'];

                // Calculate submited as the total count of awaited, delivered, and failed
                $submitedCount = $awaitedCount + $deliveredCount + $failedCount;

                $smsCampaignDetails[] = [
                    "date" => date("d-m-Y", strtotime($row['date'])),
                    "submited" => $submitedCount,  // Updated here
                    "delivered" => $deliveredCount,
                    "failed" => $failedCount,
                    "awaited" => $awaitedCount
                ];

                $totalSubmitted += $submitedCount;  // Add to total
                $totalDelivered += $deliveredCount;
                $totalFailed += $failedCount;
                $totalAwaited += $awaitedCount;
            }
            $responseArray = array(
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $rowCount,
                "totalSubmitedCount" => $totalSubmitted,
                "totalDeliveredCount" => $totalDelivered,
                "totalFailedCount" => $totalFailed,
                "totalAwaitedCount" => $totalAwaited,
            );
            if (empty($data['campaignId'])) {
                $responseArray['smsCampaignDetails'] = $smsCampaignDetails;
            }
            return [
                "apiStatus" => [
                    "code" => "200",
                    "message" => "Sms Campaign Report details fetched successfully"
                ],
                "result" => $responseArray
            ];
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }

    public function exportSmsCampaignToExcel($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            // Validate input dates
            $fromDate = !empty($data['fromDate']) ? $db->real_escape_string($data['fromDate']) : null;
            $toDate = !empty($data['toDate']) ? $db->real_escape_string($data['toDate']) : null;

            if (!$fromDate || !$toDate) {
                throw new Exception("Please provide both fromDate and toDate.");
            }

            $vendorId = $this->getVendorIdByUserId($loginData);

            // Fetch SMS campaign summary
            $statusQuery = "
                SELECT 
                    DATE(created_date) AS date,
                    COUNT(*) AS submitted, 
                    SUM(message_status = 'delivered') AS delivered,
                    SUM(message_status = 'failed') AS failed,
                    SUM(message_status = 'queued') AS awaited
                FROM cmp_sms_messages
                WHERE vendor_id = '$vendorId'
                AND DATE(created_date) BETWEEN '$fromDate' AND '$toDate'
                GROUP BY DATE(created_date)
                ORDER BY DATE(created_date) ASC
            ";
            // print_r($statusQuery);exit;
            $result = $db->query($statusQuery);

            if ($result->num_rows === 0) {
                throw new Exception("No SMS campaign data found for the given date range.");
            }

            // Create Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set column headers
            $headers = ['S.No', 'Date', 'Submitted', 'Delivered', 'Failed', 'Awaited'];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true);
                $sheet->getStyle($column . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $column++;
            }

            // Fill data
            $rowIndex = 2;
            $sno = 1;
            while ($row = $result->fetch_assoc()) {
                $submitted = (int)$row['submitted'];
                $delivered = (int)$row['delivered'];
                $failed = (int)$row['failed'];
                $awaited = (int)$row['awaited'];
                $submitedCount = $delivered + $failed + $awaited;

                $sheet->setCellValue('A' . $rowIndex, $sno++);
                $sheet->setCellValue('B' . $rowIndex, date("d-m-Y", strtotime($row['date'])));
                $sheet->setCellValue('C' . $rowIndex, $submitedCount);
                $sheet->setCellValue('D' . $rowIndex, $delivered);
                $sheet->setCellValue('E' . $rowIndex, $failed);
                $sheet->setCellValue('F' . $rowIndex, $awaited);
                $rowIndex++;
            }

            // Clean output buffer if any
            if (ob_get_contents()) ob_end_clean();

            // Download headers
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="SMS_Campaign_Report_' . date('Ymd_His') . '.xlsx"');
            header('Cache-Control: max-age=0');

            // Output Excel file
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
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


    /**
     * Post/Add tenant
     *
     * @param array $data
     * @return multitype:string
     */

    public function createSmsCampaign($data, $loginData)
    {
        // print_r($data);exit;
        $resultArray = array();
        try {
            $db = $this->dbConnect();

            // Validate input details
            $validationData = array(
                "templateId"    => $data['templateId'],
                "groupId"       => $data['groupDetails']['groupId'],
                "campaignTitle" => $data['title'],
                "SenderId" => $data['senderId'],
            );
            $this->validateInputDetails($validationData);
            $vendor_id = $this->getVendorIdByUserId($loginData);

            // Fetch Template ID
            $templateId = mysqli_real_escape_string($db, $data['templateId']);
            $sql = "SELECT id,template_id FROM cmp_sms_templates WHERE template_id = '$templateId' AND status = 1 AND vendor_id = '$vendor_id' AND active_status = 1 AND sender_id = '" . $data['senderId'] . "'";

            $result = mysqli_query($db, $sql);
            if (!$result || mysqli_num_rows($result) == 0) {
                throw new Exception("Template ID not found");
            }
            $row = mysqli_fetch_assoc($result);
            $template_id = $row['id'];

            // Fetch Group Details
            $groupId =  $data['groupDetails']['groupId'];
            $groupName =  $data['groupDetails']['groupName'];

            $sql = "SELECT COUNT(*) as count FROM cmp_group_contact WHERE id = '$groupId' AND group_name = '$groupName' AND status = 1 AND vendor_id = '$vendor_id' AND active_status = 1";
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
            // //check the campaign title is already exist or not
            $title = mysqli_real_escape_string($db, $data['title']);
            $sql = "SELECT COUNT(*) as count FROM cmp_sms_campaign WHERE title = '$title' AND status = 1 ";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_assoc($result);
            if ($row['count'] > 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code"    => "400",
                        "message" => "Campaign title already exists",
                    ],
                ];
            }

            // Fetch Timezone details
            if (!empty($data['timezone']) && !empty($data['timezone'])) {
                $timezone_zoneName = mysqli_real_escape_string($db, $data['timezone']['zoneName']);
                $timezone_id = mysqli_real_escape_string($db, $data['timezone']['id']);

                $checkTimezoneQuery = "SELECT COUNT(*) as count FROM cmp_mst_timezone WHERE id = '$timezone_id' AND timezone_name = '$timezone_zoneName'";
                $result = $db->query($checkTimezoneQuery);
                $row = $result->fetch_assoc();

                if ($row['count'] == 0) {
                    // Optionally throw exception
                }
            }
            // print_r($timezone_zoneName); 
            // echo'<br>';
            // echo"rgrg";exit;
            // Validate Variables only if isVariable is true
            if (!empty($data['isVariable']) && !empty($data['variableIds'])) {
                foreach ($data['variableIds'] as $variable) {
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
            }

            // Schedule Time
            $scheduleAt = !empty($data['scheduleStatus']) && $data['scheduleStatus'] === true
                ? mysqli_real_escape_string($db, $data['scheduledAt'])
                : date('Y-m-d H:i:s');
            // print_r($scheduleAt);exit;
            // Insert Campaign
            $title = mysqli_real_escape_string($db, $data['title']);
            // $mediaId = mysqli_real_escape_string($db, $data['mediaId']?? '');
            // $mediaurl = mysqli_real_escape_string($db, $data['mediaurl']?? '');
            // $restrictLangCode = mysqli_real_escape_string($db, $data['restrictLangCode'] ?? '');
            $sendNum = mysqli_real_escape_string($db, $data['SendNum']);
            $createdBy = mysqli_real_escape_string($db, $loginData['user_id']);

            $sql = "INSERT INTO cmp_sms_campaign (group_id, template_id, title,timezone, schedule_at, send_status, send_num, created_by) 
                VALUES ('$groupId', '$template_id', '$title',  '$timezone_zoneName', '$scheduleAt', 'Scheduled', '$sendNum',  '$createdBy')";
            // print_r($sql);
            // exit;
            if (!mysqli_query($db, $sql)) {
                throw new Exception("Error inserting campaign: " . mysqli_error($db));
            }
            $campaign_id = mysqli_insert_id($db);

            // Write to file.txt after successful insert
            if (!empty($data['scheduleStatus']) && $data['scheduleStatus'] === true) {
                // Proceed to send immediately
                $fetchResponse = $this->getUsingCampCredentials($data, $loginData);
                $testContact = $this->getTestContact($vendor_id);

                if ($fetchResponse['apiStatus']['code'] != "200") {
                    return $fetchResponse;
                }

                $contacts = $fetchResponse['result']['contacts'];
                $contacts[]['mobile'] = $testContact;


                //    print_r($contacts);exit;
                // Insert into cmp_sms_campaign_contact_mapping
                // foreach ($contacts as $contact) {
                //     $insertContact = "INSERT INTO cmp_sms_campaign_contact_mapping (campaign_id, contact_id, created_by) 
                //     VALUES ('$campaign_id', '" . $contact['contactId'] . "', '$createdBy')";
                //     if (!mysqli_query($db, $insertContact)) {
                //         throw new Exception("Error inserting contact mapping: " . mysqli_error($db));
                //     }
                // }

                // Insert into cmp_sms_messages

                foreach ($contacts as $contact) {
                    //insert the queue for the sms camapaign queue 
                    $insertqueue = "INSERT INTO cmp_sms_messages (vendor_id,campaign_id, campaign_schedule,template_id,sender_id,mobile, template_name, message_status,created_by, created_date)
                 VALUES ('$vendor_id','$campaign_id','1','$template_id', '" . $data['senderId'] . "','" . $contact['mobile'] . "', '" . $data['templateName'] . "', 'queued','" . $loginData['user_id'] . "', NOW())";
                    if (!mysqli_query($db, $insertqueue)) {
                        throw new Exception("Error inserting into queue: " . mysqli_error($db));
                    }
                }
                // Write to file.txt
                $fileData = "SmsCampaignID: $campaign_id | vendorId: $vendor_id | Timezone: $timezone_zoneName | ScheduleAt: $scheduleAt |createdBy: {$loginData['user_id']}| Status: Scheduled" . PHP_EOL;
                file_put_contents("Smsfile.txt", $fileData, FILE_APPEND);
            }

            // If not scheduled, update the send_status to 'Executed'
            if (empty($data['scheduleStatus']) || $data['scheduleStatus'] === false) {

                $fetchResponse = $this->getUsingCampCredentials($data, $loginData);
                // $testContact = $this->getTestContact($vendor_id);

                if ($fetchResponse['apiStatus']['code'] != "200") {
                    return $fetchResponse;
                }

                $contacts = $fetchResponse['result']['contacts'];
                // $contacts[]['mobile'] = $testContact;

                // print_r($contacts);exit;
                // Send WhatsApp Message
                $call = new SMSMODEL();
                $resulthhtp = $call->sendMessagee($contacts, $loginData, $campaign_id, $data['bodycontent'], $templateId);
                $updateSql = "UPDATE cmp_sms_campaign SET send_status = 'Executed' WHERE id = '$campaign_id'";
                if (!mysqli_query($db, $updateSql)) {
                    throw new Exception("Error updating send status: " . mysqli_error($db));
                }
            }

            // Insert variable mappings if isVariable is true
            if (!empty($data['isVariable']) && !empty($data['variableIds'])) {
                foreach ($data['variableIds'] as $variable) {
                    if (isset($variable['type'])) {
                        $type = mysqli_real_escape_string($db, $variable['type']);

                        foreach ($variable['variables'] as $var) {
                            $vartypeId = mysqli_real_escape_string($db, $var['varName']);
                            $varValueId = mysqli_real_escape_string($db, $var['varValue']['varTypeId']);
                            $varValueName = mysqli_real_escape_string($db, $var['varValue']['varTypeName']);

                            $sqlW = "INSERT INTO cmp_sms_campaign_variable_mapping (campaign_id, template_id, type, variable_type_id, variable_value, group_id, created_by) 
                                 VALUES ('$campaign_id', '$template_id', '$type', '$vartypeId', '$varValueId', '$groupId', '$createdBy')";

                            if (!mysqli_query($db, $sqlW)) {
                                throw new Exception("Error inserting variable mapping: " . mysqli_error($db));
                            }
                        }
                    }
                }
            }

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


    private function getUsingCampCredentials($data, $loginData)
    {
        try {
            $groupID = $data['groupDetails']['groupId'];
            $db = $this->dbConnect();
            // Fetch group details with user-provided group name
            $vendor_id = $this->getVendorIdByUserId($loginData);
            // Fetch group details with user-provided group name
            $queryService = "SELECT
                            gc.id AS groupId,
                            gc.group_name AS groupName,
                            gc.active_status AS activeStatus,
                            c.id AS contactId,
                            c.first_name AS firstName,
                            c.last_name AS lastName,
                            c.mobile,
                            c.email,
                            c.country,
                            c.language_code,
                            0 AS is_test_contact
                        FROM cmp_group_contact_mapping AS gcm
                        LEFT JOIN cmp_group_contact AS gc ON gc.id = gcm.group_id
                        LEFT JOIN cmp_contact AS c ON c.id = gcm.contact_id
                        WHERE gc.status = 1
                            AND gcm.status = 1
                            AND c.status = 1
                            AND gc.active_status = 1  
                            AND gc.vendor_id = $vendor_id
                            AND gc.id = $groupID
                        GROUP BY c.mobile
                        
                        UNION
                        
                        SELECT
                            $groupID AS groupId,
                            (SELECT group_name FROM cmp_group_contact WHERE id = $groupID) AS groupName,
                            (SELECT active_status FROM cmp_group_contact WHERE id = $groupID) AS activeStatus,
                            c.id AS contactId,
                            c.first_name AS firstName,
                            c.last_name AS lastName,
                            c.mobile,
                            c.email,
                            c.country,
                            c.language_code,
                            1 AS is_test_contact
                        FROM cmp_contact c
                        WHERE RIGHT(c.mobile, 10) IN (
                            SELECT RIGHT(test_contact, 10) FROM cmp_vendor_sms_credentials WHERE vendor_id = $vendor_id
                        )
                        AND c.status = 1 AND vendor_id = $vendor_id
                        AND NOT EXISTS (
                            SELECT 1 FROM cmp_group_contact_mapping gcm WHERE gcm.contact_id = c.id AND gcm.group_id = $groupID
                        )
                        GROUP BY c.mobile
                        ORDER BY groupId DESC, contactId ASC";
            // print_r($queryService);exit;
            $result = $db->query($queryService);
            $rowCount = mysqli_num_rows($result);

            if ($rowCount > 0) {
                $contacts = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $contacts[] = $row;
                }


                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Contacts and template fetched successfully",
                    ],
                    "result" => [
                        "contacts" => $contacts,

                    ]
                ];
            } else {
                throw new Exception("No contacts found for the provided group.");
            }
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage(),
                ],
            ];
        }
    }






    private function getSmsDashboardDetails($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            if (empty($data['id'])) {
                throw new Exception("Invalid. Please enter your ID.");
            }

            // Get the total record count
            // Get the total contact count
            $contactCountQuery = "SELECT COUNT(DISTINCT con.id) AS contactCount FROM cmp_sms_campaign AS c 
   left JOIN cmp_group_contact_mapping AS gcm ON gcm.group_id = c.group_id
    left JOIN cmp_contact AS con ON con.id = gcm.contact_id
   WHERE c.id='" . $data['id'] . "'";

            $contactCountResult = $db->query($contactCountQuery);
            $contactCountRow = $contactCountResult->fetch_assoc();
            $contactCount = $contactCountRow['contactCount'];

            // Fetch campaign details with contacts
            $queryService = "SELECT DISTINCT 
            c.id, c.title, c.template_id, c.created_date, c.send_status,
            c.active_status, c.schedule_at, c.status AS campaignStatus, 
            wt.template_name, wt.language, 
            con.first_name, con.last_name, con.mobile, con.email,
            con.status AS contactStatus, con.created_date AS contactCreatedDate 
            FROM cmp_sms_campaign AS c 
           LEFT JOIN cmp_sms_templates AS wt ON wt.id = c.template_id
            LEFT JOIN cmp_group_contact_mapping AS gcm ON gcm.group_id = c.group_id
            LEFT JOIN cmp_contact AS con ON con.id = gcm.contact_id
            LEFT JOIN cmp_campaign_contact AS cc ON cc.contact_id = con.id AND cc.campaign_id = c.id
            WHERE c.id='" . $data['id'] . "' 
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
                    "status" => $row['campaignStatus'],
                    "createdDate" => $row['created_date'],
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



    private function deleteSmscampaign($data)
    {
        try {
            // print_r($data);exit;
            $id = $data[2];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_sms_campaign WHERE id = $id AND status=1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => " Sms Campaign does not exist",
                    ),
                );
            }

            //update delete query

            $deleteQuery = "UPDATE cmp_sms_campaign
            SET status = 0 
            WHERE id = " . $id . "";

            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                // Update file.txt: remove line with the given CampaignID
                $filePath = "Smsfile.txt";
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
                $statusMessage = " Sms Campaign details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete Sms Campaign details, please try again later";
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

    public function getsmsCampaignQueueDetails($data, $loginData, $urlParam)
    {
        try {
            // echo"122";exit;
            $db = $this->dbConnect();
            // Validate pageIndex and dataLength
            if (isset($data['pageIndex']) && isset($data['pageIndex'])) {
                $start_index = $data['pageIndex'] * $data['dataLength'];
                $end_index = $data['dataLength'];
            }

            $campaign_id = mysqli_real_escape_string($db, $data['campaignId']);
            // Fetch queue data for the given campaign
            $query = "SELECT wmq.id, c.first_name, c.last_name, wmq.mobile, wmq.template_name, wmq.message_status, wmq.updated_date, wmq.reason
                        FROM cmp_sms_messages AS wmq
                        LEFT JOIN cmp_contact AS c ON c.mobile = wmq.mobile AND c.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
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
                $query .= " AND (wmq.mobile LIKE '%" . mysqli_real_escape_string($db, $data['filterBy']) . "%' or c.first_name LIKE '%" . mysqli_real_escape_string($db, $data['filterBy']) . "%' 
                            or wmq.message_status LIKE '%" . mysqli_real_escape_string($db, $data['filterBy']) . "%' 
                            or c.last_name LIKE '%" . mysqli_real_escape_string($db, $data['filterBy']) . "%')
                            ";
            }
            $query .= " ORDER BY wmq.created_date DESC";
            // print_r($query);exit;
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
            FROM cmp_sms_messages
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

    public function getTestContact($vendorId)
    {
        $db = $this->dbConnect();
        $queryTestContact = "SELECT test_contact
                     FROM cmp_vendor_fb_credentials
                     WHERE vendor_id = '$vendorId'
                     LIMIT 1";

        $resultTestContact = $db->query($queryTestContact);
        $testContact = null;

        if ($resultTestContact && $row = $resultTestContact->fetch_assoc()) {
            $testContact = $row['test_contact'];
            return $testContact;
        }
    }

    public function smscampaignactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_sms_campaign WHERE id = $id AND status=1";

            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Sms Campaign ID does not exist",
                    ),
                );
            }
            $ActiveQuery = "UPDATE cmp_sms_campaign SET active_status = 1 WHERE status = 1 AND id = $id";

            if ($db->query($ActiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = " Sms Campaign archive successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate Sms Campaign, please try again later.";
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
    // public function campaignactiveachieve($data, $loginData)
    // {
    //     try {
    //         // Validate 'id' key
    //         if (!isset($data['id']) || empty($data['id'])) {
    //             throw new Exception("Bad request. 'id' field is required.");
    //         }

    //         // Normalize to array
    //         $ids = is_array($data['id']) ? $data['id'] : [$data['id']];
    //         $ids = array_map('intval', $ids); // Sanitize IDs

    //         $db = $this->dbConnect();
    //         $idList = implode(',', $ids);

    //         // Step 1: Check if all campaign IDs exist (status = 1)
    //         $checkQuery = "SELECT id FROM cmp_campaign WHERE id IN ($idList) AND status = 1";
    //         $result = $db->query($checkQuery);

    //         $foundIds = [];
    //         while ($row = $result->fetch_assoc()) {
    //             $foundIds[] = $row['id'];
    //         }

    //         $missingIds = array_diff($ids, $foundIds);

    //         if (!empty($missingIds)) {
    //             $db->close();
    //             return array(
    //                 "apiStatus" => array(
    //                     "code" => "400",
    //                     "message" => "These campaign IDs do not exist or are inactive: " . implode(', ', $missingIds),
    //                 ),
    //             );
    //         }

    //         // Step 2: Activate all valid campaigns
    //         $activateQuery = "UPDATE cmp_campaign SET active_status = 1 WHERE id IN ($idList) AND status = 1";
    //         if ($db->query($activateQuery) === true) {
    //             $statusCode = "200";
    //             $statusMessage = "Campaign(s) activated successfully.";
    //         } else {
    //             $statusCode = "500";
    //             $statusMessage = "Unable to activate campaign(s), please try again.";
    //         }

    //         $db->close();

    //         return array(
    //             "apiStatus" => array(
    //                 "code" => $statusCode,
    //                 "message" => $statusMessage,
    //             ),
    //         );
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }
    public function Smscampaigndeactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }
            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_sms_campaign WHERE id = $id AND active_status=1 AND status=1";
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
            $deactiveQuery = "UPDATE cmp_sms_campaign SET active_status = 0 WHERE status = 1 AND id = $id";

            if ($db->query($deactiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Sms Campaign Unarchived successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to Unarchived sms Campaign, please try again later.";
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

    public function exportsmsQueuetoexcel($data, $loginData, $urlParam)
    {
        try {
            // print_r($data);exit;
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
            $query = "SELECT wmq.id, c.first_name, c.last_name, wmq.mobile, wmq.template_name, wmq.message_status, wmq.updated_date, wmq.reason
                        FROM cmp_sms_messages AS wmq
                        LEFT JOIN cmp_contact AS c ON c.mobile = wmq.mobile AND c.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
                        WHERE wmq.campaign_id = '$campaign_id' AND wmq.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
                        ";
            if ($urlParam[2] === 'queue') {
                $query .= " AND wmq.message_status = 'queued'";
                $message = "queue";
            } else if ($urlParam[2] === 'executed') {
                $query .= " AND wmq.message_status != 'queued'";
                $message = "executed";
            }

            $query .= " ORDER BY wmq.created_date ASC";
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

            $rowIndex = 2;
            $sno = 1;
            while ($row = $result->fetch_assoc()) {
                // print_r($row);
                if ($row['message_status'] == 'queued') {
                    $messageStatus = 'Queued';
                } else if ($row['message_status'] == 'sent') {
                    $messageStatus = 'Sent';
                } else if ($row['message_status'] == 'failed') {
                    $messageStatus = 'Failed';
                } else if ($row['message_status'] == 'delivered') {
                    $messageStatus = 'Delivered';
                } else if ($row['message_status'] == 'undelivered') {
                    $messageStatus = 'Failed';
                } else if ($row['message_status'] == 'expired') {
                    $messageStatus = 'Failed';
                }

                $sheet->setCellValue('A' . $rowIndex, $sno);
                $sheet->setCellValue('B' . $rowIndex, $row['first_name']);
                $sheet->setCellValue('C' . $rowIndex, $row['last_name']);
                // $sheet->setCellValue('D' . $rowIndex, $row['mobile']);
                $sheet->setCellValueExplicit('D' . $rowIndex, $row['mobile'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('E' . $rowIndex, $row['template_name']);
                $sheet->setCellValue('F' . $rowIndex, $messageStatus);
                $sheet->setCellValue('G' . $rowIndex, $row['updated_date']);
                $sheet->setCellValue('H' . $rowIndex, $row['reason']);
                $rowIndex++;
                $sno++;
            }

            // **Output the file directly for download**
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="SMS_' . $message . '_data_' . date('Ymd_His') . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output'); // Send file to browser directly

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

    // public function campaigndeactiveachieve($data, $loginData)
    // {
    //     try {
    //         // Validate input
    //         if (!isset($data['id']) || empty($data['id'])) {
    //             throw new Exception("Bad request. 'id' field is required.");
    //         }

    //         $ids = is_array($data['id']) ? $data['id'] : [$data['id']];
    //         $ids = array_map('intval', $ids); // Sanitize all IDs

    //         $db = $this->dbConnect();

    //         // Convert array to comma-separated string for SQL IN clause
    //         $idList = implode(',', $ids);

    //         // Step 1: Check all IDs exist and are active
    //         $checkQuery = "SELECT id FROM cmp_campaign WHERE id IN ($idList) AND active_status = 1 AND status = 1";
    //         $result = $db->query($checkQuery);

    //         $foundIds = [];
    //         while ($row = $result->fetch_assoc()) {
    //             $foundIds[] = $row['id'];
    //         }

    //         // Find missing or inactive IDs
    //         $missingIds = array_diff($ids, $foundIds);

    //         if (!empty($missingIds)) {
    //             $db->close();
    //             return array(
    //                 "apiStatus" => array(
    //                     "code" => "400",
    //                     "message" => "These campaign IDs are invalid or already deactivated: " . implode(', ', $missingIds),
    //                 ),
    //             );
    //         }

    //         // Step 2: All IDs are valid — proceed to deactivate
    //         $deactiveQuery = "UPDATE cmp_campaign SET active_status = 0 WHERE id IN ($idList) AND status = 1";
    //         if ($db->query($deactiveQuery) === true) {
    //             $statusCode = "200";
    //             $statusMessage = "Campaign(s) deactivated successfully.";
    //         } else {
    //             $statusCode = "500";
    //             $statusMessage = "Failed to deactivate campaign(s).";
    //         }

    //         $db->close();

    //         return array(
    //             "apiStatus" => array(
    //                 "code" => $statusCode,
    //                 "message" => $statusMessage,
    //             ),
    //         );
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }



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
     FROM cmp_sms_campaign AS c
     JOIN cmp_sms_templates AS wt ON wt.id = c.template_id
     WHERE c.status = 1  AND wt.status = 1 AND c.active_status=0
     AND wt.vendor_id = " . $this->getVendorIdByUserId($loginData);
            // print_r($countQuery);exit;
            $countResult = $db->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $recordCount = $countRow['totalCount']; // Total record count

            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT c.id,c.title,c.template_id,c.created_date,c.active_status,c.schedule_at,c.send_status,c.status AS campaignStatus,wt.template_name,wt.language
              FROM cmp_sms_campaign AS c 
              JOIN cmp_sms_templates AS wt ON wt.id = c.template_id
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
        $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '" . $loginData['user_id'] . "' AND status = 1";
        // print_r($sql);exit;
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

    // private function getTotalCount($loginData)
    // {

    //     try {
    //         $db = $this->dbConnect();
    //         // get the vendor id from the login data
    //         $vendor_id = $this->getVendorIdByUserId($loginData);
    //         $sql = "SELECT COUNT(cs.id) as totalgroup
    //     FROM cmp_group_contact cs
    //            WHERE cs.status = 1 AND vendor_id = $vendor_id";

    //         // print_r($sql);exit; 
    //         $result = $db->query($sql);
    //         $row = $result->fetch_assoc();

    //         return $row['totalgroup'];
    //     } catch (Exception $e) {
    //         return array(
    //             "result" => "401",
    //             "message" => $e->getMessage(),
    //         );
    //     }
    // }

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
