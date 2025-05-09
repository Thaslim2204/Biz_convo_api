<?php
// cronjob.php

include 'include/dbConnection.php';
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

class SmsCampaignScheduler
{
    // print_r($loginData);exit;    

    public $db;
    private $filePath = "Smsfile.txt";
    private $wt;


    private function dbConnect()
    {
        $conn = new DBCONNECTION();
        $db = $conn->connect();
        return $db;
    }
    private $sms_base_url;
    private $from;
    private $body;
    private $template_id;
    private $key;
    private $entity_id;
    private $to;

    private function smsCredentials($vid)
    {
        $db = $this->dbConnect();
        $vendor_id = $vid;

        //get private tokens from DB
        $sql = "SELECT id, sender_id, api_key, template_id, entity_id from cmp_vendor_sms_credentials where vendor_id = $vendor_id and status = 1";
        $result = $db->query($sql);
        $fbData = mysqli_fetch_assoc($result);
        if ($fbData) {
            $this->sms_base_url = "https://api.smsc.ai/api";
            $this->from = $fbData['sender_id'];
            $this->key = $fbData['api_key'];
            $this->template_id = $fbData['template_id'];
            $this->entity_id = $fbData['entity_id'];
            // $this->body = $fbData['body'];
        } else {
            throw new Exception("Failed to fetch sms credentials from the database.");
        }
    }

    public function run()
    {
        $this->executeUpdateMsgStatus();

        if (!file_exists($this->filePath)) {
            echo "📂 Smsfile.txt not found. Nothing to send.\n";
            return;
        }
        // $this->checkAndSend();
    }

    private function checkAndSend()
    {
        // echo"123654789";exit;
        $db = $this->dbConnect();
        $data = file_get_contents($this->filePath);
        // print_r(    $data);exit;
        // Extract parts using regex
        preg_match('/vendorId:\s*(\d+)/', $data, $matchvid);
        preg_match('/SmsCampaignID:\s*(\d+)/', $data, $matchCampaign);
        preg_match('/Timezone:\s*([^\|]+)/', $data, $matchTimezone);
        preg_match('/ScheduleAt:\s*([^\|]+)/', $data, $matchSchedule);
        preg_match('/createdBy:\s*([^\|]+)/', $data, $matchcreatedBy);
        preg_match('/Status:\s*(\w+)/', $data, $matchStatus);
        // print_r($matchvid);exit;
        $vid = isset($matchvid[1]) ? trim($matchvid[1]) : null;
        $campaignId = isset($matchCampaign[1]) ? trim($matchCampaign[1]) : null;
        $zoneName = isset($matchTimezone[1]) ? trim($matchTimezone[1]) : "Asia/Kolkata";
        $scheduleAt = isset($matchSchedule[1]) ? trim($matchSchedule[1]) : null;
        $createdBy = isset($matchcreatedBy[1]) ? trim($matchcreatedBy[1]) : null;
        $status = isset($matchStatus[1]) ? trim($matchStatus[1]) : null;
        // print_r($createdBy);exit;
        if (!$campaignId || !$zoneName || !$scheduleAt) {
            echo "❌ Invalid data format in file.txt\n";
            return;
        }

        // ✅ Dynamically set the timezone
        if (in_array($zoneName, timezone_identifiers_list())) {
            date_default_timezone_set($zoneName);
        } else {
            echo "⚠️ Invalid timezone '$zoneName'. Falling back to Asia/Kolkata\n";
            date_default_timezone_set("Asia/Kolkata");
        }

        // 🔍 Get timezone ID from DB
        $timeZoneId = null;
        $zoneQuery = "SELECT id FROM cmp_mst_timezone WHERE timezone_name = '$zoneName'";
        $zoneResult = $db->query($zoneQuery);
        if ($zoneResult && $zoneResult->num_rows > 0) {
            $zoneRow = $zoneResult->fetch_assoc();
            $timeZoneId = $zoneRow['id'];
        } else {
            echo "Zone '$zoneName' not found in cmp_mst_timezone. Using ID = 0\n";
            $timeZoneId = 0;
        }

        // 🕒 Time check
        $scheduleTime = strtotime($scheduleAt);
        $currentTime = time();
        $diff = $scheduleTime - $currentTime;
        // print_r($diff);exit;
        if ($diff <= 240 && $diff >= 0) {
            // ✉️ Send Message Logic
            $query = "
            SELECT t.template_id AS templateId,
                   c.title,
                   c.group_id, c.restrictLangCode,
                   c.schedule_at AS scheduledAt,
                   t.template_content,
                   gc.group_name
              FROM cmp_campaign c
        JOIN cmp_sms_templates t ON c.template_id = t.id       
        JOIN cmp_group_contact gc ON c.group_id=gc.id
        LEFT JOIN cmp_campaign_variable_mapping AS cvm ON c.id = cvm.campaign_id
        WHERE c.id = $campaignId
        ";
            // print_r($query);exit;
            $result = $db->query($query);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $templateId = $row['templateId'];
                $bodyData = $row['template_content'];
                $groupId = $row['group_id'];
                $title = $row['title'];
                $scheduledAt = $row['scheduledAt'];
                $restrictLangCode = $row['restrictLangCode'];
                $groupName = $row['group_name'];
                $isRestricted = ($restrictLangCode == 1);

                // 🔍 Variable Mapping
                $varQuery = "
                SELECT 
                    cvm.type, 
                    cvm.variable_type_id AS varName, 
                    cvm.variable_value AS varTypeId,
                    mv.variable_name AS varTypeName 
                FROM cmp_sms_campaign_variable_mapping cvm
                LEFT JOIN cmp_mst_variable mv ON cvm.variable_value = mv.id
                WHERE cvm.campaign_id = $campaignId 
                    AND cvm.template_id = $templateId 
                    AND cvm.group_id = $groupId
            ";
                $varResult = $db->query($varQuery);
                $variableMap = [];
                if ($varResult && $varResult->num_rows > 0) {
                    while ($varRow = $varResult->fetch_assoc()) {
                        $type = $varRow['type'];
                        $varName = $varRow['varName'];
                        $varTypeId = $varRow['varTypeId'];
                        $varTypeName = $varRow['varTypeName'];

                        if (!isset($variableMap[$type])) {
                            $variableMap[$type] = [];
                        }
                        $variableMap[$type][] = [
                            "varName" => $varName,
                            "varValue" => [
                                "varTypeName" => $varTypeName,
                                "varTypeId" => $varTypeId
                            ]
                        ];
                    }
                }

                $variableIds = [];
                foreach ($variableMap as $type => $variables) {
                    $variableIds[] = [
                        "type" => $type,
                        "variables" => $variables
                    ];
                }

                // 🧱 Build data
                $dataToSend = [
                    "templateId" => $templateId,
                    "group" => [
                        "groupId" => $groupId,
                        "groupName" => $groupName
                    ],
                    "title" => $title,
                    "body" => $bodyData,
                    "restrictLangCode" => $isRestricted,
                    "scheduleStatus" => true,
                    "timezone" => [
                        "id" => $timeZoneId,
                        "zoneName" => $zoneName
                    ],
                    "scheduledAt" => $scheduledAt,
                    "SendNum" => "",
                    "variableIds" => $variableIds
                ];
                $fetchResponse = $this->getUsingCampCredentials($dataToSend, $vid);
                
                if ($fetchResponse['apiStatus']['code'] != "200") {
                    return $fetchResponse;
                }
    
                $contacts = $fetchResponse['result']['contacts'];
                // ✅ Send the message
                $sendStatus = $this->sendMessagee($dataToSend, $vid, $campaignId, $bodyData, $templateId,$contacts);
                // print_r($sendStatus);exit;
                if ($sendStatus['apiStatus']['code'] === '200') {
                    // ✅ Update send_status to 'sent' in cmp_campaign
                    $updateStatusSql = "UPDATE cmp_campaign SET send_status = 'Executed' WHERE id = '$campaignId'";
                    if (!$db->query($updateStatusSql)) {
                        echo "⚠️ Failed to update send_status in cmp_campaign: " . $db->error . "\n";
                    } else {
                        echo "📌 send_status updated to 'sent' for campaign ID $campaignId\n";
                    }
                    // print_r($updateStatusSql);exit;
                    // Remove current block from file.txt
                    $data = file_get_contents($this->filePath);
                    $pattern = "/SmsCampaignID:\s*{$campaignId}\s*\|.*?Status:\s*\w+\s*/s";
                    $updatedData = preg_replace($pattern, '', $data);
                    file_put_contents($this->filePath, trim($updatedData));

                    echo "✅ Message sent successfully. CampaignID $campaignId removed from file.txt\n";
                } else {
                    echo "❌ Failed to send message for campaign ID $campaignId.\n";
                }
            } else {
                echo "❌ No campaign/template data found for ID $campaignId.\n";
            }
            } elseif ($diff < -240) {
                // ⌛ Schedule expired
                echo "⚠️ Scheduled time passed for CampaignID $campaignId. Removing from file.\n";
                $data = file_get_contents($this->filePath);
                $pattern = "/SmsCampaignID:\s*{$campaignId}\s*\|.*?Status:\s*\w+\s*/s";
                $updatedData = preg_replace($pattern, '', $data);
                file_put_contents($this->filePath, trim($updatedData));
        } else {
            echo "🕒 Not time yet. ($diff seconds left)\n";
        }
    }


    private function getUsingCampCredentials($data, $vid)
    {
        try {
            // print_r($data);exit;
            $groupID = $data['group']['groupId'];
            $db = $this->dbConnect();
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
            c.language_code
        FROM cmp_group_contact_mapping AS gcm
        LEFT JOIN cmp_group_contact AS gc ON gc.id = gcm.group_id
        LEFT JOIN cmp_contact AS c ON c.id = gcm.contact_id
        WHERE gc.status = 1 
            AND gcm.status = 1
            AND c.status = 1
            AND gc.active_status = 1  
            AND gc.id = $groupID 
        ORDER BY gc.id DESC";
            $result = $db->query($queryService);
            $rowCount = mysqli_num_rows($result);

            if ($rowCount > 0) {
                $contacts = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $contacts[] = $row;
                }
// print_r($contacts);exit;
                // // Fetch the template dynamically
                // $template_id = $data['templateId'];
                // // $templateResponse = $this->templateByID(["", "", $template_id], $vid);
                // $templateData = $templateResponse['result'];
                // print_r(($templateResponse));exit;
                // Return contacts along with template
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Contacts and template fetched successfully",
                    ],
                    "result" => [
                        "contacts" => $contacts,
                        // "template" => $templateData,
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



    //new fast executequeue
    private function executeUpdateMsgStatus()
    {
        $lock_file = 'sms_cron.lock';
 
        // Step 1: Check if lock file exists and is recent (last 5 minutes)
        if (file_exists($lock_file)) {
            echo "Another execution is already running. Exiting...\n";
            return;
        }
 
        // Step 2: Create/refresh the lock file
        touch($lock_file);
 
        try {
            $db = $this->dbConnect();
 
            // ⚡ 1. Directly get top 80 queued messages PER vendor
            $messagesQuery = "SELECT * FROM (
                                SELECT *,
                                    ROW_NUMBER() OVER (PARTITION BY vendor_id ORDER BY created_date ASC) as row_num
                                FROM cmp_sms_messages
                                WHERE message_status = 'sent'
                            ) AS subquery
                            WHERE row_num <= 80
                            ORDER BY vendor_id, created_date ASC
                        ";
            // print_r($messagesQuery);exit;
            $resultMessages = $db->query($messagesQuery);
 
            $messagesGroupedByVendor = [];
 
            while ($row = mysqli_fetch_assoc($resultMessages)) {
                $messagesGroupedByVendor[$row['vendor_id']][] = $row;
            }
            // print_r($messagesGroupedByVendor[$row['vendor_id']][]);exit;
 
            // 🔥 2. Now process messages group by vendor
            foreach ($messagesGroupedByVendor as $vendorId => $allMessages) {
 
                echo "Processing Vendor: " . $vendorId . " at " . date("Y-m-d H:i:s") . "\n";
 
                // Set Facebook credentials
                $this->smsCredentials($vendorId);
 
                foreach ($allMessages as $message) {
                    $messageId = $message['msg_id'];
                    $vendorID = $message['vendor_id'];
                    $this->singleReport($messageId, $vendorID);
                }
            }
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage(),
                ],
            ];
        } finally {
            if (file_exists($lock_file)) {
                unlink($lock_file);
                echo "file deleted\n";
            }
        }
    }


    public function sendMessagee($data, $vid, $campaignid, $msgContent, $templateId,$contacts)
    {
        // echo"12366555";
        // print_r(json_encode($contacts));exit;
        // exit;
        try {
            $db = $this->dbConnect();
            $this->smsCredentials($vid);
            $url = "$this->sms_base_url/jsmslist3";
            // $msgContent = "Cheers To Celebrate Diwali @DENCH UNION! Get Rs.500 OFF on Rs.3999/- & Rs.1000 OFF on Rs.6999/-. Shop for Luxury European Brands!! Visit Now!! Call: 9677917733";
            // $contacts = ["824841580678", "824841580678"];
            // $templateId = "1707172857624499901";
            $listsms = [];
            foreach ($contacts as $contact) {
                $listsms[] = [
                    "from" => $this->from,
                    "to" => $contact['mobile'],
                    "body" => $data['body'],
                    "templateid" => $templateId,
                    "entityid" => $this->entity_id,
                    "clientsmsid" => "xx"  //variables parameter
                ];
            }
            // print_r($listsms);exit;
            $body = json_encode([
                "key" => $this->key,
                "listsms" => $listsms
            ]);

            // print_r($body);exit;
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;exit;

            // $response = '{
            //     "smslist": {
            //         "sms": [
            //             {
            //                 "reason": "invalid mobileno",
            //                 "code": 700,
            //                 "mobileno": "934216235777",
            //                 "status": "fail"
            //             },
            //             {
            //                 "reason": "success",
            //                 "code": 0,
            //                 "messageid": "2wnhoa79Fa5NQwDrogku8cUPDzy",
            //                 "messagepartids": [],
            //                 "mobileno": "8248415806",
            //                 "status": "success"
            //             }
            //         ]
            //     }
            // }';

            $decodedResponse = json_decode($response, true);
            // print_r($response);exit;
            // print_r($decodedResponse['smslist']);exit;
            if ($decodedResponse['smslist']) {
                $this->storeMsgId($decodedResponse['smslist']['sms'], $vid, $templateId, $this->from, $db, $campaignid);
                // exit;
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Message sent successfully",
                    ],
                    "result" => $decodedResponse['smslist']['sms']
                ];
            } else {
                throw new Exception($decodedResponse['description']);
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


    public function storeMsgId($response, $vid, $templateId, $senderId, $db, $campaignid )
    {

        try {
            //get vendor id using logindata
            $db = $this->dbConnect();
            // Get the Contact id from the login data
            
                $vendor_id = $vid;
           

            foreach ($response as $res) {
                // print_r((json_encode($res)));

                if (isset($res['mobileno'])) {
                    $messageId = isset($res['messageid']) ? $res['messageid'] : null;
                    if($res['status'] == "success"){
                        $msgStatus = "sent";
                    }else if($res['status'] == "fail"){
                        $msgStatus = "failed";
                    }
                    // $sql = "INSERT into cmp_sms_messages (campaign_id,template_id,sender_id,mobile,vendor_id,msg_id, reason, message_status, created_by) 
                    // values ('$campaignid','$templateId','$senderId','" . $res['mobileno'] . "','$vendor_id','" . $messageId . "','" . $res['reason'] . "','" . $res['status'] . "','" . $loginData['user_id'] . "')";
                    $updatedDate = date('Y-m-d H:i:s');
                    $updatedSql="UPDATE cmp_sms_messages set message_status = '" . $msgStatus . "', updated_date = '" . $updatedDate . "',msg_id = '" . $messageId . "'  where  mobile = '" . $res['mobileno'] . "' and vendor_id = '$vendor_id' AND campaign_id = '$campaignid'";
                    $result = $db->query($updatedSql);
                    // $report = $this->singleReport($messageId, $vid);
                    if (!$result) {
                        throw new Exception("Unable to store sms informations!");
                    }
                    
                }
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

    protected function singleReport($messageId, $vid)
    {
        try {
            // $messageId = "2wnhoa79Fa5NQwDrogku8cUPDzy";
            // print_r($messageId);exit;
            $this->smsCredentials($vid);
            $url = "$this->sms_base_url/report?key=$this->key&uid=$messageId";
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);
            // print_r($response);exit;
            $decodedResponse = json_decode($response, true);
            curl_close($curl);

            if ($decodedResponse['response']) {
                // print_r($response);exit;
                $this->updateMsgStatus($messageId, $decodedResponse['response']);
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Message report listed successfully",
                    ],
                    "result" => $decodedResponse['response']
                ];
            } else {
                throw new Exception($decodedResponse['description']);
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

    public function updateMsgStatus($messageId, $response)
    {
        try {
            $db = $this->dbConnect();
            // echo "from update msg status";
            // print_r($response[0]['description']);
            // exit;
            $msgDesc = explode('#', $response[0]['description']);
            $msgStatus = $msgDesc[0];
            $updatedDate = $msgDesc[1];
            // print_r($updatedDate);
            // exit;
            if ($msgStatus == 'DELIVRD') {
                $msgStatus = "Delivered";
            } else if ($msgStatus == 'REJECTD') {
                $msgStatus = "Rejected";
            }
            // echo $msgStatus;exit;
            //update message status using message id
            $update = "UPDATE cmp_sms_messages set message_status = '" . $msgStatus . "', updated_date = '" . $updatedDate . "' where msg_id = '" . $messageId . "'";
            $result = $db->query($update);
            if (!$result) {
                throw new Exception("Unable to update message status!");
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



    /**
     * Get the dynamic value for a variable from the contact data
     */
    private function getDynamicValueForVariable($variable, $contact)
    {
        // print_r(json_encode($contact['lastName']));exit;
        // Fetch the dynamic value based on the variable type
        switch ($variable['varValue']['varTypeName']) {
            case 'Contact Full Name':
                return $contact['firstName'] . ' ' . $contact['lastName'];
            case 'Contact Last Name':
                return $contact['lastName'];
            case 'Contact First Name':
                return $contact['firstName'];
            case 'Contact Email':
                return $contact['email'];
            case 'Contact Phone':
                return $contact['mobile'];
            case 'Contact Country':
                return $contact['country'];
            case 'Contact Language':
                return $contact['language_code'];
            default:
                return '';
        }
    }


   
}

// Run the scheduler
$scheduler = new SmsCampaignScheduler();
$scheduler->run();
