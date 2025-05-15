<?php

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

class SMSMODEL extends APIRESPONSE
{

    private function processMethod($data, $loginData)
    {

        $urlPath = $_GET['url'];
        $urlParam = explode('/', $urlPath);
        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    if ($urlParam[2] == "configsms") {
                        $result = $this->getSMSConfigStatus($data, $loginData);
                        return $result;
                    } else {
                        throw new Exception("Unable to proceed your request");
                    }
                    break;
                } else {
                    throw new Exception("Unable to proceed your request");
                }
            case 'POST':
                if ($urlParam[1] == 'send') {
                    $result = $this->sendMsg($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == 'summaryReport') {
                    $result = $this->summaryReport($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == 'sendMessage1') { //Send message function with json body: Note: Response of invalid mobile numbers are not proper
                    // $result = $this->sendMessage1($data, $loginData);
                    // return $result;
                } else if ($urlParam[1] == 'sendMessage') {
                    $result = $this->sendMessagee($data, $loginData, '', '', '');
                    return $result;
                } else if ($urlParam[1] == 'report') {
                    $result = $this->singleReport($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == 'setupsms') {
                    $result = $this->setupsms($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == "add") {
                    if ($urlParam[2] == "testContact") {
                        $result = $this->addTestContact($data, $loginData);
                    }
                    return $result;
                } else {
                    throw new Exception("Unable to procede your request");
                }
                // return $result;
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

    private $sms_base_url;
    private $from;
    private $body;
    private $template_id;
    private $key;
    private $entity_id;
    private $to;

    private function smsCredentials($loginData)
    {
        $db = $this->dbConnect();
        // Get the Contact id from the login data
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
            $this->body = $fbData['body'];
        } else {
            throw new Exception("Failed to fetch sms credentials from the database.");
        }
    }

    public function sendMsg($request, $loginData)
    {

        try {

            // print_r($request);exit;
            $user_id = $loginData['user_id'];
            $db = $this->dbConnect();
            $this->smsCredentials($loginData);

            $check = "SELECT api_key,sender_id,template_id,entity_id,vendor_id FROM cmp_vendor_sms_credentials WHERE  vendor_id = '$user_id'";
            // print_r($check);exit;
            $result = $db->query($check);
            // print_r($result);exit;
            $rowCount = mysqli_num_rows($result);
            // print_r($rowCount);exit;
            $message = "created";

            if ($rowCount > 0) {

                $curl = curl_init();
                $url = $this->sms_base_url . '/' . $this->from . '/' . $this->body . '/' . $this->template_id . '/' . $this->key . '/' . $this->to . '/' . $this->entity_id . '/';
                // print_r($url);
                // exit;

                $response = curl_exec($curl);
                $decodedResponse = json_decode($response, true);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

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
                curl_close($curl);
                echo $response;
            }
            if ($httpCode == "200") {
                return array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "SMS $message successfully",
                    ),
                    "result" => json_decode($response)
                );
            } else {
                return array(
                    "apiStatus" => array(
                        "code" => $httpCode,
                        "message" => "Error occured!",
                    ),
                    "result" => json_decode($response)
                );
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

    // //Send message function with json body: Note: Response of invalid mobile numbers are not proper
    // protected function sendMessage1($data, $loginData)
    // {
    //     try {
    //         $db = $this->dbConnect();
    //         $this->smsCredentials($loginData);
    //         $url = "$this->sms_base_url/jsms";
    //         $msgContent = "Cheers To Celebrate Diwali @DENCH UNION! Get Rs.500 OFF on Rs.3999/- & Rs.1000 OFF on Rs.6999/-. Shop for Luxury European Brands!! Visit Now!! Call: 9677917733";
    //         $body = [
    //             "key" => $this->key,
    //             "from" => $this->from,
    //             "to" => [
    //                 "8248415806"
    //             ],
    //             "body" => $msgContent,
    //             "templateid" => $this->template_id,
    //             "entityid" => $this->entity_id,
    //             "custref1" => "xx",
    //             "custref2" => "xx"
    //         ];

    //         // $curl = curl_init();
    //         // curl_setopt_array($curl, array(
    //         //     CURLOPT_URL => $url,
    //         //     CURLOPT_RETURNTRANSFER => true,
    //         //     CURLOPT_ENCODING => '',
    //         //     CURLOPT_MAXREDIRS => 10,
    //         //     CURLOPT_TIMEOUT => 0,
    //         //     CURLOPT_FOLLOWLOCATION => true,
    //         //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         //     CURLOPT_CUSTOMREQUEST => 'POST',
    //         //     CURLOPT_POSTFIELDS => json_encode($body),
    //         //     CURLOPT_HTTPHEADER => array(
    //         //         'Content-Type: application/json'
    //         //     ),
    //         // ));

    //         // $response = curl_exec($curl);

    //         // curl_close($curl);
    //         $response = '[
    //                     {
    //                         "Duplicate Numbers": 0
    //                     },
    //                     {
    //                         "status": 100,
    //                         "description": "Message submitted with tracking id ( UID )",
    //                         "messageid": "2wlkXXHYFsnorncFEqlj7jZCflG"
    //                     },
    //                     {
    //                         "status": 100,
    //                         "description": "Message submitted with tracking id ( UID )",
    //                         "messageid": "2wlkXTeOaOXA2lvr89Q9P4lA0o6"
    //                     }
    //                 ]';
    //         // $response = '
    //         //     [
    //         //         {
    //         //             "Duplicate Numbers": 0
    //         //         },
    //         //         {
    //         //             "status": 700,
    //         //             "description": "NO VALID RECIPIENTS FOUND!"
    //         //         }
    //         //     ]
    //         // ';

    //         $decodedResponse = json_decode($response, true);
    //         // exit;
    //         if ($decodedResponse[1]['status'] == 100) {
    //             $this->storeMsgId($decodedResponse, $loginData, $this->template_id, $this->from, $db, '');
    //             exit;
    //             return [
    //                 "apiStatus" => [
    //                     "code" => "200",
    //                     "message" => "Message sent successfully",
    //                 ],
    //                 "result" => $decodedResponse
    //             ];
    //         } else {
    //             throw new Exception($decodedResponse[1]['description']);
    //         }
    //     } catch (Exception $e) {
    //         return [
    //             "apiStatus" => [
    //                 "code" => "401",
    //                 "message" => $e->getMessage(),
    //             ],
    //         ];
    //     }
    // }

    public function sendMessagee($data, $loginData, $campaignid, $msgContent, $templateId)
    {
        // echo"12366555";exit;
        // print_r(json_encode($data));
        // print_r($data);
        // print_r($loginData);
        // print_r($campaignid);
        // print_r($msgContent);
        // print_r($templateId);exit;
        // exit;
        try {
            $db = $this->dbConnect();
            $this->smsCredentials($loginData);
            $url = "$this->sms_base_url/jsmslist";
            // $msgContent = "Cheers To Celebrate Diwali @DENCH UNION! Get Rs.500 OFF on Rs.3999/- & Rs.1000 OFF on Rs.6999/-. Shop for Luxury European Brands!! Visit Now!! Call: 9677917733";
            // $contacts = ["824841580678", "824841580678"];
            // $templateId = "1707172857624499901";
            $listsms = [];
            foreach ($data as $contact) {
                $listsms[] = [
                    "from" => $this->from,
                    "to" => $contact['mobile'],
                    "body" => $msgContent,
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

            $decodedResponse = json_decode($response, true);
            // print_r($response);exit;
            // print_r($decodedResponse['smslist']);exit;
            if ($decodedResponse['smslist']) {
                $this->storeMsgId($decodedResponse['smslist']['sms'], $loginData, $templateId, $this->from, $db, $campaignid);
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

    public function storeMsgId($response, $loginData, $templateId, $senderId, $db, $campaignid)
    {
        //         echo"1212   ";
        // print_r($templateId);exit;
        try {
            //get vendor id using logindata
            $db = $this->dbConnect();
            // Get the Contact id from the login data
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

            foreach ($response as $res) {
                // print_r((json_encode($res)));


                if (isset($res['mobileno'])) {
                    $messageId = isset($res['messageid']) ? $res['messageid'] : null;
                    if ($res['status'] == "success") {
                        $msgStatus = "sent";
                    } else if ($res['status'] == "fail") {
                        $msgStatus = "failed";
                    }
                    $sql = "INSERT into cmp_sms_messages (campaign_id,template_id,sender_id,mobile,vendor_id,msg_id, reason, message_status, created_by) 
                    values ('$campaignid','$templateId','$senderId','" . $res['mobileno'] . "','$vendor_id','" . $messageId . "','" . $res['reason'] . "','" . $msgStatus . "','" . $loginData['user_id'] . "')";
                    //    print_r($sql);exit;


                    $result = $db->query($sql);
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

    protected function singleReport($data, $loginData)
    {
        try {
            $messageId = "2wnhoa79Fa5NQwDrogku8cUPDzy";
            $this->smsCredentials($loginData);
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
                $msgStatus = "delivered";
            } else if ($msgStatus == 'REJECTD') {
                $msgStatus = "rejected";
            } else if ($msgStatus == 'EXPIRED') {
                $msgStatus = "expired";
            } else if ($msgStatus == 'UNDELIVRD') {
                $msgStatus = "undelivered";
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

    protected function summaryReport($data, $loginData)
    {
        try {
            // echo "comes here";
            $fromDate = $data['fromDate'];
            $toDate = $data['toDate'];

            if (empty($fromDate)) {
                throw new Exception("From date is required");
            }

            if (empty($toDate)) {
                throw new Exception("To date is required");
            }

            $this->smsCredentials($loginData);

            $curl = curl_init();
            $url = "$this->sms_base_url/summary?key=$this->key&todate=$fromDate&fromdate=$toDate";
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

            curl_close($curl);
            $decodedResponse = json_decode($response, true);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($decodedResponse['status']) {
                throw new Exception($decodedResponse['description']);
            } else if ($httpCode == "200") {
                return array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Message summary reports listed successfully",
                    ),
                    "result" => $decodedResponse
                );
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

    public function setupsms($data, $loginData)
    {
        $resultArray = array();
        try {
            $db = $this->dbConnect();
            $user_id = $loginData['user_id'];

            // 1. Get vendor_id from cmp_vendor_user_mapping
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id";
            $result = $db->query($sql);
            $vendorRow = $result->fetch_assoc();
            $vendor_id = $vendorRow['vendor_id'] ?? null;

            if (empty($vendor_id)) {
                throw new Exception("Vendor ID not found for user ID: $user_id");
            }

            // 2. Validate required inputs
            $validationData = array(
                "Sender ID" => $data['senderId'],
                "Entity ID" => $data['entityId'],
                "API Key"   => $data['apiKey'],
            );
            $this->validateInputDetails($validationData);

            // 3. Check if record exists
            $checkSql = "SELECT id FROM cmp_vendor_sms_credentials WHERE vendor_id = $vendor_id";
            $checkResult = $db->query($checkSql);

            if ($checkResult->num_rows > 0) {
                // Record exists: perform update
                $updateSql = "UPDATE cmp_vendor_sms_credentials 
                          SET sender_id = '" . $data['senderId'] . "',
                              entity_id = '" . $data['entityId'] . "',
                              api_key = '" . $data['apiKey'] . "',
                              updated_by = '$user_id',
                              updated_date = NOW()
                          WHERE vendor_id = $vendor_id";

                if ($db->query($updateSql) === true) {
                    $db->close();
                    return array(
                        "apiStatus" => array(
                            "code" => "200",
                            "message" => "SMS credentials updated successfully.",
                        ),
                    );
                } else {
                    throw new Exception("Error updating SMS credentials: " . $db->error);
                }
            } else {
                // Record does not exist: perform insert
                $insertSql = "INSERT INTO cmp_vendor_sms_credentials 
                          (vendor_id, sender_id, entity_id, api_key, created_by, created_date, status)
                          VALUES 
                          ('$vendor_id', '" . $data['sender_id'] . "', '" . $data['entity_id'] . "', '" . $data['api_key'] . "', '$user_id', NOW(), 1)";

                if ($db->query($insertSql) === true) {
                    $db->close();
                    return array(
                        "apiStatus" => array(
                            "code" => "200",
                            "message" => "SMS credentials added successfully.",
                        ),
                    );
                } else {
                    throw new Exception("Error inserting SMS credentials: " . $db->error);
                }
            }
        } catch (Exception $e) {
            if (isset($db)) {
                $db->close();
            }
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }


    private function getSMSConfigStatus($data, $loginData)
    {
        try {
            // Get vendor ID using loginData
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

            // Check if SMS configuration exists
            $smsQuery = "SELECT sender_id, entity_id, api_key, test_contact FROM cmp_vendor_sms_credentials WHERE vendor_id = '$vendor_id'";
            $smsResult = $db->query($smsQuery);

            if ($smsResult && $smsResult->num_rows > 0) {
                $smsData = $smsResult->fetch_assoc();

                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "SMS configuration info listed successfully.",
                    ],
                    "result" => [
                        "smsConfigured" => true,
                        "senderId" => $smsData['sender_id'],
                        "entityId" => $smsData['entity_id'],
                        "apiKey" => $smsData['api_key'],
                        "testContact" => $smsData['test_contact']
                    ]
                ];
            } else {
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "SMS configuration not found.",
                    ],
                    "result" => [
                        "sms_configured" => false
                    ]
                ];
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

    public function addTestContact($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
 
            $user_id = $loginData['user_id'];
            $test_contact = $data['test_contact'];
 
            // 1. Get vendor_id from cmp_vendor_user_mapping
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id";
            $result = $db->query($sql);
            $vendor_id = $result->fetch_assoc()['vendor_id'];
 
            if (empty($vendor_id)) {
                throw new Exception("Vendor ID not found for user ID: $user_id");
            }
 
            if (empty($test_contact)) {
                throw new Exception("Test contact is required");
            }
 
            // 3. Check if record already exists in cmp_vendor_sms_credentials
            $checkSql = "SELECT id FROM cmp_vendor_sms_credentials WHERE vendor_id = $vendor_id and status = 1";
            $checkResult = $db->query($checkSql);
 
            if ($checkResult->num_rows > 0) {
 
                $date = date("Y-m-d H:i:s");
                $insertSql = "UPDATE cmp_vendor_sms_credentials SET
                                test_contact = '" . $test_contact . "',
                                updated_by = '$user_id',
                                updated_date = '" . $date . "'
                                where vendor_id = '" . $vendor_id . "'";
            } else {
                // 4. Insert new SMS credentials
                $insertSql = "INSERT INTO cmp_vendor_sms_credentials
                (vendor_id, test_contact, created_by, created_date, status)
                VALUES
                ('$vendor_id', '" . $test_contact . "', '$user_id', NOW(), 1)";
            }
 
            if ($db->query($insertSql) === true) {
                $db->close();
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Test contact configured successfully.",
                    ),
                );
            } else {
                throw new Exception("Error inserting Whatsapp test contact credentials: " . $db->error);
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

    public function validateInputDetails($validationData)
    {
        foreach ($validationData as $key => $value) {
            if (empty($value) || trim($value) == "") {
                throw new Exception($key . " should not be empty!");
            }
        }
    }

    public function processList($request, $token)
    {
        try {
            $responseData = $this->processMethod($request, $token);
            $result = $this->response($responseData);
            return $result;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage()
                ),
            );
        }
    }
}
