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

            case 'POST':
                if ($urlParam[1] == 'send') {
                    $result = $this->sendMsg($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == 'summaryReport') {
                    $result = $this->summaryReport($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == 'sendMessage1') { //Send message function with json body: Note: Response of invalid mobile numbers are not proper
                    $result = $this->sendMessage1($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == 'sendMessage') {
                    $result = $this->sendMessage($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == 'report') {
                    $result = $this->singleReport($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to procede your request");
                }
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

    //Send message function with json body: Note: Response of invalid mobile numbers are not proper
    protected function sendMessage1($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $this->smsCredentials($loginData);
            $url = "$this->sms_base_url/jsms";
            $msgContent = "Cheers To Celebrate Diwali @DENCH UNION! Get Rs.500 OFF on Rs.3999/- & Rs.1000 OFF on Rs.6999/-. Shop for Luxury European Brands!! Visit Now!! Call: 9677917733";
            $body = [
                "key" => $this->key,
                "from" => $this->from,
                "to" => [
                    "8248415806"
                ],
                "body" => $msgContent,
                "templateid" => $this->template_id,
                "entityid" => $this->entity_id,
                "custref1" => "xx",
                "custref2" => "xx"
            ];

            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => $url,
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_POSTFIELDS => json_encode($body),
            //     CURLOPT_HTTPHEADER => array(
            //         'Content-Type: application/json'
            //     ),
            // ));

            // $response = curl_exec($curl);

            // curl_close($curl);
            $response = '[
                        {
                            "Duplicate Numbers": 0
                        },
                        {
                            "status": 100,
                            "description": "Message submitted with tracking id ( UID )",
                            "messageid": "2wlkXXHYFsnorncFEqlj7jZCflG"
                        },
                        {
                            "status": 100,
                            "description": "Message submitted with tracking id ( UID )",
                            "messageid": "2wlkXTeOaOXA2lvr89Q9P4lA0o6"
                        }
                    ]';
            // $response = '
            //     [
            //         {
            //             "Duplicate Numbers": 0
            //         },
            //         {
            //             "status": 700,
            //             "description": "NO VALID RECIPIENTS FOUND!"
            //         }
            //     ]
            // ';

            $decodedResponse = json_decode($response, true);
            // exit;
            if ($decodedResponse[1]['status'] == 100) {
                $this->storeMsgId($decodedResponse, $loginData, $this->template_id, $this->from, $db);
                exit;
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Message sent successfully",
                    ],
                    "result" => $decodedResponse
                ];
            } else {
                throw new Exception($decodedResponse[1]['description']);
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

    protected function sendMessage($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $this->smsCredentials($loginData);
            $url = "$this->sms_base_url/jsmslist3";
            $msgContent = "Cheers To Celebrate Diwali @DENCH UNION! Get Rs.500 OFF on Rs.3999/- & Rs.1000 OFF on Rs.6999/-. Shop for Luxury European Brands!! Visit Now!! Call: 9677917733";
            $contacts = ["824841580678", "824841580678"];
            $templateId = "1707172857624499901";
            $listsms = [];
            foreach ($contacts as $contact) {
                $listsms[] = [
                    "from" => $this->from,
                    "to" => $contact,
                    "body" => $msgContent,
                    "templateid" => $templateId,
                    "entityid" => $this->entity_id,
                    "clientsmsid" => "xx"  //variables parameter
                ];
            }
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
                $this->storeMsgId($decodedResponse['smslist']['sms'], $loginData, $templateId, $this->from, $db);
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

    public function storeMsgId($response, $loginData, $templateId, $senderId, $db)
    {

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
                    $sql = "INSERT into cmp_sms_messages (template_id,sender_id,mobile,vendor_id,msg_id, reason, message_status, created_by) values ('$templateId','$senderId','" . $res['mobileno'] . "','$vendor_id','" . $messageId . "','" . $res['reason'] . "','" . $res['status'] . "','" . $loginData['user_id'] . "')";
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
