<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

class WABAMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {
        $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
        $urlPath = $_GET['url'];
        $urlParam = explode('/', $urlPath);

        switch (REQUESTMETHOD) {
            case 'GET':
                if ($urlParam[1] == "get") {
                    if ($urlParam[2] == "phoneNumbers") {
                        $result = $this->getPhoneNumbersInfo($data, $loginData);
                        return $result;
                    } else if ($urlParam[2] == "healthStatus") {
                        $result = $this->gethealthStatusInfo($data, $loginData);
                        return $result;
                    } else if ($urlParam[2] == "configStatus") {
                        $result = $this->getConfigStatus($data, $loginData);
                        return $result;
                    } else if ($urlParam[2] == "tokenInfo") {
                        $result = $this->getTokenInfo($data, $loginData);
                        return $result;
                    } else if ($urlParam[2] == "businessProfile") {
                        $result = $this->getBusinessProfile($data, $loginData);
                        return $result;
                    } else if ($urlParam[2] == "industryList") {
                        $result = $this->getIndustryList($data, $loginData);
                        return $result;
                    }
                }
                break;

            case 'POST':
                if ($urlParam[1] == "webhook") {
                    if ($urlParam[2] == "subscribe") {
                        $result = $this->webhookSubscribe($data, $loginData);
                    }
                } else if ($urlParam[1] == "businessInfo") {
                    if ($urlParam[2] == "phoneNumbers") {
                        $result = $this->phoneNumbersInfo($data, $loginData);
                        return $result;
                    } elseif ($urlParam[2] == "healthStatus") {
                        $result = $this->healthStatusInfo($data, $loginData);
                        return $result;
                    }
                } else if ($urlParam[1] == "update") {
                    if ($urlParam[2] == "businessProfile") {
                        $result = $this->updateBusinessProfile($data, $loginData);
                    }
                    return $result;
                }
                return $result;

                break;

            case 'DELETE':
                if ($urlParam[1] == "webhook") {
                    if ($urlParam[2] == "unsubscribe") {
                        $result = $this->webhookUnsubscribe($data, $loginData);
                    }
                }
                return $result;
                break;
            default:
                $result = $this->handle_error($request);
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

    private $facebook_base_url;
    private $facebook_base_version;
    private $whatsapp_business_id;
    private $phone_no_id;
    private $fb_auth_token;
    private $facebook_app_id;
    private $app_secret;

    // Initiate FB Credentials
    private function fbCredentials($loginData)
    {
        // print_r($loginData);
        $db = $this->dbConnect();

        // Get the Contact id from the login data
        $user_id = $loginData['user_id'];
        $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '$user_id'";
        $result = $db->query($sql);
        // print
        if ($result) {
            $row = $result->fetch_assoc();
            //  print_r($row);exit;
            if (!$row || !isset($row['vendor_id'])) {
                throw new Exception("Vendor ID not found for user ID: $user_id");
            }
            $vendor_id = $row['vendor_id'];
            // echo $vendor_id;
        } else {
            throw new Exception("Database query failed: " . $db->error);
        }

        $this->facebook_base_url = "https://graph.facebook.com";
        $this->facebook_base_version = "v22.0";

        //get private tokens from DB
        $sql = "SELECT whatsapp_business_acc_id, phone_no_id, access_token , app_id, app_secret from cmp_vendor_fb_credentials where vendor_id = $vendor_id and status = 1";
        $result = $db->query($sql);
        $fbData = mysqli_fetch_assoc($result);
        if ($fbData) {
            $this->whatsapp_business_id = $fbData['whatsapp_business_acc_id'];
            $this->phone_no_id = $fbData['phone_no_id'];
            $this->fb_auth_token = $fbData['access_token'];
            // print_r($this->fb_auth_token);exit;
            $this->facebook_app_id = $fbData['app_id'];
            $this->app_secret = $fbData['app_secret'];
        } else {
            throw new Exception("Failed to fetch Facebook credentials from the database.");
        }
    }

    private function phoneNumbersInfo($data, $loginData)
    {
        try {
            //get Vendor id using logindata
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

            $whatsappBusinessId = $data['wa_business_acc_id'];
            $accessToken = $data['access_token'];

            if (empty($whatsappBusinessId)) {
                throw new Exception("Whatsapp business account id is required");
            }

            if (empty($accessToken)) {
                throw new Exception("Access token is required");
            }

            $url = "https://graph.facebook.com/v22.0/$whatsappBusinessId/phone_numbers?access_token=$accessToken";
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
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if (curl_errno($curl)) {
                throw new Exception('Curl error: ' . curl_error($curl));
            }
            if ($httpCode == "200") {
                $responseData = json_decode($response, true);
                $phoneNoId = $responseData['data'][0]['id'];
                if ($vendor_id) {
                    //check vendors has credentials
                    // echo $vendor_id;
                    $date = date("y:m:d h:m:i");
                    $uid = bin2hex(random_bytes(8));
                    $checkFbQuery = "SELECT * from cmp_vendor_fb_credentials where vendor_id = '$vendor_id'";
                    $checkFbResult = $db->query($checkFbQuery);
                    $checkFbQueryRowCnt = mysqli_num_rows($checkFbResult);
                    if ($checkFbQueryRowCnt > 0) {
                        //update if have count
                        $updateFbQuery = "UPDATE cmp_vendor_fb_credentials set phone_no_id = '$phoneNoId',whatsapp_business_acc_id = '$whatsappBusinessId', access_token = '$accessToken', whatsapp_configured = '1', updated_by = '" . $loginData['user_id'] . "' ,updated_date = '$date' where vendor_id = '$vendor_id'";
                    } else {
                        ////insert credentials against vendors if not count 
                        $updateFbQuery = "INSERT into cmp_vendor_fb_credentials (uid,vendor_id, phone_no_id,whatsapp_business_acc_id, access_token, whatsapp_configured) values ('$uid','$vendor_id','$phoneNoId','$whatsappBusinessId', '$accessToken', '1')";
                    }

                    if ($db->query($updateFbQuery) != true) {
                        throw new Exception("Unable to update records!");
                    }
                }

                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Phone number info listed successfully.",
                    ],
                    "result" => $responseData['data']
                ];
            }
            curl_close($curl);
            echo $response;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }

    private function getPhoneNumbersInfo($data, $loginData)
    {
        try {
            $this->fbCredentials($loginData);

            $whatsappBusinessId = $this->whatsapp_business_id;
            $accessToken = $this->fb_auth_token;

            if (empty($whatsappBusinessId) && empty($accessToken)) {
                throw new Exception("Whatsapp not configured");
            }
            if (empty($whatsappBusinessId)) {
                throw new Exception("Whatsapp business account id is required");
            }

            if (empty($accessToken)) {
                throw new Exception("Access token is required");
            }

            $url = "https://graph.facebook.com/v22.0/$whatsappBusinessId/phone_numbers?access_token=$accessToken";
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
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if (curl_errno($curl)) {
                throw new Exception('Curl error: ' . curl_error($curl));
            }
            if ($httpCode == "200") {
                $responseData = json_decode($response, true);
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Phone number info listed successfully.",
                    ],
                    "result" => $responseData['data']
                ];
            }
            curl_close($curl);
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }

    private function healthStatusInfo($data, $loginData)
    {
        try {
            //get Vendor id using logindata
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

            $whatsappBusinessId = $data['wa_business_acc_id'];
            $accessToken = $data['access_token'];

            if (empty($whatsappBusinessId)) {
                throw new Exception("Whatsapp business account id is required");
            }

            if (empty($accessToken)) {
                throw new Exception("Access token is required");
            }

            $url = "https://graph.facebook.com/v22.0/$whatsappBusinessId?fields=health_status";

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
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $accessToken,
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $responseData = json_decode($response, true);

            if ($responseData['error']) {
                throw new Exception($responseData['error']['message']);
            }

            if (curl_errno($curl)) {
                throw new Exception('Curl error: ' . curl_error($curl));
            }
            if ($httpCode == "200") {

                //update health status checked date into db
                $db = $this->dbConnect();
                $dateNow = date("Y-m-d H:i:s");
                $update = "UPDATE cmp_vendor_fb_credentials set health_status_date = '".$dateNow."' where vendor_id = '$vendor_id'";
                $db->query($update);

                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Health status info listed successfully.",
                    ],
                    "result" => $responseData
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

    private function getHealthStatusInfo($data, $loginData)
    {
        try {
            //get Vendor id using logindata
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
            
            $this->fbCredentials($loginData);

            $whatsappBusinessId = $this->whatsapp_business_id;
            $accessToken = $this->fb_auth_token;

            if (empty($whatsappBusinessId) && empty($accessToken)) {
                throw new Exception("Whatsapp not configured");
            }

            if (empty($whatsappBusinessId)) {
                throw new Exception("Whatsapp business account id is required");
            }

            if (empty($accessToken)) {
                throw new Exception("Access token is required");
            }

            $url = "https://graph.facebook.com/v22.0/$whatsappBusinessId?fields=health_status";

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
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $accessToken,
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $responseData = json_decode($response, true);

            if ($responseData['error']) {
                throw new Exception($responseData['error']['message']);
            }

            if (curl_errno($curl)) {
                throw new Exception('Curl error: ' . curl_error($curl));
            }
            if ($httpCode == "200") {

                 //update health status checked date into db
                $db = $this->dbConnect();
                $dateNow = date("Y-m-d H:i:s");
                $update = "UPDATE cmp_vendor_fb_credentials set health_status_date = '".$dateNow."' where vendor_id = '$vendor_id'";
                $db->query($update);

                //get healt status date
                $get = "SELECT health_status_date from cmp_vendor_fb_credentials where vendor_id = '$vendor_id' and status = 1";
                $result = $db->query($get);
                $getData = mysqli_fetch_assoc($result);
                $getDate = $getData['health_status_date'];
                $responseData['status_checked_at'] = $getDate;
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Health status info listed successfully.",
                    ],
                    "result" => $responseData,
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

    private function getConfigStatus($data, $loginData)
    {
        try {

            //get Vendor id using logindata
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

            $configQuery = "SELECT webhook_configured, whatsapp_configured from cmp_vendor_fb_credentials where vendor_id = '$vendor_id'";
            $configResult = $db->query($configQuery);
            $configQueryRowCnt = mysqli_num_rows($configResult);
            if ($configQueryRowCnt > 0) {
                $configData = mysqli_fetch_assoc($configResult);
                if ($configData['webhook_configured'] == 1) {
                    $webhookConfig = true;
                } else {
                    $webhookConfig = false;
                }
                if ($configData['whatsapp_configured'] == 1) {
                    $whatsappConfig = true;
                } else {
                    $whatsappConfig = false;
                }
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Whatsapp configuration info listed successfully.",
                    ],
                    "result" => [
                        "webhook_configured" => $webhookConfig,
                        "whatsapp_configured" => $whatsappConfig
                    ]
                ];
            } else {
                throw new Exception("Whatsapp not configured!");
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

    private function getTokenInfo($data, $loginData)
    {
        try {
            $this->fbCredentials($loginData);

            $accessToken = $this->fb_auth_token;

            if (empty($accessToken)) {
                throw new Exception("Access token is required");
            }

            $url = "https://graph.facebook.com/debug_token?input_token=$accessToken&access_token=$accessToken";

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
            curl_close($curl);

            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $responseData = json_decode($response, true);

            if ($responseData['error']) {
                throw new Exception($responseData['error']['message']);
            }

            if (curl_errno($curl)) {
                throw new Exception('Curl error: ' . curl_error($curl));
            }
            if ($httpCode == "200") {
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Access token info listed successfully.",
                    ],
                    "result" => $responseData['data']
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

    private function getBusinessProfile($data, $loginData)
    {
        try {
            $this->fbCredentials($loginData);

            $phoneNoId = $this->phone_no_id;
            $accessToken = $this->fb_auth_token;

            if (empty($accessToken) && empty($phoneNoId)) {
                throw new Exception("Whatsapp not configured!");
            }

            $fields = "about,address,description,email,profile_picture_url,websites,vertical";
            $url = "https://graph.facebook.com/v22.0/$phoneNoId/whatsapp_business_profile?fields=$fields";

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
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $accessToken,
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $responseData = json_decode($response, true);

            if ($responseData['error']) {
                throw new Exception($responseData['error']['message']);
            }

            if (curl_errno($curl)) {
                throw new Exception('Curl error: ' . curl_error($curl));
            }
            if ($httpCode == "200") {
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Business profile info listed successfully.",
                    ],
                    "result" => $responseData['data']
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

    private function webhookSubscribe($data, $loginData)
    {
        try {
            //get Vendor id using logindata
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

            //process webhook subscribe
            $appId = $data['appId'];
            $appSecret = $data['appSecret'];

            if (empty($appId)) {
                throw new Exception("App id is required");
            }

            if (empty($appSecret)) {
                throw new Exception("App secret is required");
            }

            //Generate access token
            $clientAccessToken = $this->generateClientAccessToken($appId, $appSecret);
            $tokenData = json_decode($clientAccessToken, true);

            if (isset($tokenData['error'])) {
                throw new Exception($tokenData['error']['message']);
            }

            $token = $tokenData['access_token'];

            //Prepare webhook subscription data
            $callbackUrl = "https://crm.bizconvo.in/be/api/webhook";
            $verifyToken = "Happy";
            $fields = "account_alerts,account_update,business_status_update,message_template_components_update,message_template_status_update,message_template_quality_update,messages,phone_number_name_update,phone_number_quality_update,security";

            $curl = curl_init();
            $url = "https://graph.facebook.com/v22.0/$appId/subscriptions";

            $postFields = http_build_query([
                'object' => 'whatsapp_business_account',
                'callback_url' => $callbackUrl,
                'verify_token' => $verifyToken,
                'fields' => $fields,
                'access_token' => $token,
            ]);

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                ),
            ));

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                throw new Exception('Curl error: ' . curl_error($curl));
            }
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpCode == "200") {

                if ($vendor_id) {
                    //check vendors has credentials
                    // echo $vendor_id;
                    $date = date("y:m:d h:m:i");
                    $uid = bin2hex(random_bytes(8));
                    $checkFbQuery = "SELECT * from cmp_vendor_fb_credentials where vendor_id = '$vendor_id'";
                    $checkFbResult = $db->query($checkFbQuery);
                    $checkFbQueryRowCnt = mysqli_num_rows($checkFbResult);
                    if ($checkFbQueryRowCnt > 0) {
                        //update if have count
                        $updateFbQuery = "UPDATE cmp_vendor_fb_credentials set app_id = '$appId', app_secret = '$appSecret',webhook_configured = '1', updated_by = '" . $loginData['user_id'] . "' ,updated_date = '$date' where vendor_id = '$vendor_id'";
                    } else {
                        ////insert credentials against vendors if not count 
                        $updateFbQuery = "INSERT into cmp_vendor_fb_credentials (uid,vendor_id, app_id, app_secret, webhook_configured, created_by) values ('$uid','$vendor_id','$appId', '$appSecret', '1', '" . $loginData['user_id'] . "')";
                    }

                    if ($db->query($updateFbQuery) != true) {
                        throw new Exception("Unable to update records!");
                    }
                }

                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "webhook subscribed successfully.",
                    ],
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

    private function webhookUnsubscribe($data, $loginData)
    {
        try {
            //get Vendor id using logindata
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

            $this->fbCredentials($loginData);

            $appId = $this->facebook_app_id;
            $appSecret = $this->app_secret;

            if (empty($appId) && empty($appSecret)) {
                throw new Exception("Webhook not configured!");
            }
            if (empty($appId)) {
                throw new Exception("App id is required");
            }

            if (empty($appSecret)) {
                throw new Exception("App secret is required");
            }

            //Generate access token
            $clientAccessToken = $this->generateClientAccessToken($appId, $appSecret);
            $tokenData = json_decode($clientAccessToken, true);

            if (isset($tokenData['error'])) {
                throw new Exception($tokenData['error']['message']);
            }

            $token = $tokenData['access_token'];

            $url = "https://graph.facebook.com/v22.0/$appId/subscriptions";

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'DELETE',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $token,
                ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);
            // echo $response;

            if ($httpCode == "200") {

                if ($vendor_id) {
                    $date = date("y:m:d h:m:i");
                    $updateFbQuery = "UPDATE cmp_vendor_fb_credentials set webhook_configured = '0', updated_by = '" . $loginData['user_id'] . "' ,updated_date = '$date' where vendor_id = '$vendor_id'";

                    if ($db->query($updateFbQuery) != true) {
                        throw new Exception("Unable to update records!");
                    }
                }

                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Webhook unsubscribed successfully.",
                    ],
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

    private function updateBusinessProfile($data, $loginData)
    {
        try {
            $this->fbCredentials($loginData);

            $phoneNoId = $this->phone_no_id;
            $accessToken = $this->fb_auth_token;

            if (empty($apphoneNoId) && empty($accessToken)) {
                throw new Exception("Whatsapp not configured!");
            }

            $body = array(
                "messaging_product" => "whatsapp",
                "about" => $data['about'],
                "address" => $data['address'],
                "description" => $data['description'],
                "vertical" => $data['vertical'],
                "email" => $data['email'],
                "websites" => $data['websites'],
                "profile_picture_handle" => $data['profile_picture_handle']
            );

            $url = "https://graph.facebook.com/v22.0/$phoneNoId/whatsapp_business_profile";
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
                CURLOPT_POSTFIELDS => json_encode($body),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $accessToken,
                ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $responseData = json_decode($response, true);

            if ($responseData['error']) {
                throw new Exception($responseData['error']['message']);
            }

            if ($httpCode == "200") {
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Business profile updated successfully.",
                    ],
                ];
            }
            curl_close($curl);
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }

    private function getIndustryList($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $sql = "SELECT id, industry_type from cmp_wa_industry_type where status = 1";
            $result = $db->query($sql);
            $rowCount = mysqli_num_rows($result);
            if($rowCount > 0){
                while ($row = mysqli_fetch_assoc($result)) {
                    $industryData[] = $row;
                }
                $response = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Industry types listed successfully"
                    ),
                    "result" => $industryData
                );
                return $response;
            }else{
                throw new Exception("No data found!");
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

    public function generateClientAccessToken($appId, $appSecret)
    {
        $curl = curl_init();
        $url = "https://graph.facebook.com/oauth/access_token?client_id=$appId&client_secret=$appSecret&grant_type=client_credentials";

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

        if (curl_errno($curl)) {
            throw new Exception('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);
        return $response;
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
                    "message" => $e->getMessage(),
                ),
            );
        }
    }
    private function handle_error($request) {}
}
