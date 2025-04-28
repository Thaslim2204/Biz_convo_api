<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";

class WHATSAPPTEMPLATEMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {
        $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
        $urlPath = $_GET['url'];
        $urlParam = explode('/', $urlPath);

        switch (REQUESTMETHOD) {
            case 'GET':
                if ($urlParam[1] == "get") {
                    $result = $this->templateByID($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'templatedropdown') {
                    // $result = $this->getTemplatedropdown($data, $loginData);
                    $result = $this->getTemplatedropdownWapp($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == "getLanguageCodes") {
                    $result = $this->getLanguageCodes($data, $loginData); //this gets from database
                    return $result;
                }
                break;

            case 'POST':
                if ($urlParam[1] == "create") {
                    $result = $this->createOrUpdateTemplate($data, $loginData);
                } else if ($urlParam[1] == "list") {
                    $result = $this->templateList($data, $loginData);
                    return $result;
                } else if ($urlParam[1] == "sendMessage") {
                    $result = $this->sendMessage($data, $loginData, "", "");
                } else if ($urlParam[1] == "uploadMedia") {
                    $result = $this->uploadMedia($data, $loginData);
                } elseif ($urlParam[1] === 'testing') {
                    $result = $this->getUsingCampCredentials($data, $loginData);
                    return $result;
                }
                // }
                return $result;

                break;

            case 'DELETE':
                $result = $this->deleteTemplate($data, $loginData);
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

    // Initiate FB Credentials
    private function fbCredentials($loginData)
    {
        // print_r($loginData);
        $db = $this->dbConnect();
// print_r($loginData);exit;
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
         } else {
             throw new Exception("Database query failed: " . $db->error);
         }

        $this->facebook_base_url = "https://graph.facebook.com";
        $this->facebook_base_version = "v22.0";

        //get private tokens from DB
        $sql = "SELECT whatsapp_business_acc_id, phone_no_id, access_token , app_id from cmp_vendor_fb_credentials where vendor_id = $vendor_id and status = 1";
        $result = $db->query($sql);
        $fbData = mysqli_fetch_assoc($result);
        if ($fbData) {
            $this->whatsapp_business_id = $fbData['whatsapp_business_acc_id'];
            $this->phone_no_id = $fbData['phone_no_id'];
            $this->fb_auth_token = $fbData['access_token'];
            // print_r($this->fb_auth_token);exit;
            $this->facebook_app_id = $fbData['app_id'];
            
        } else {
            throw new Exception("Failed to fetch Facebook credentials from the database.");
        }
    }

    public function createOrUpdateTemplate($request, $loginData)
    {
        try {
            $db = $this->dbConnect();
            $this->fbCredentials($loginData);

            $mediaId = $request['mediaId'] ? $request['mediaId'] : "";

            $url =  $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->whatsapp_business_id . '/' . "message_templates";
            // print_r($url);exit;
            // template update
            if ($request["template_id"]) {
                // echo "comes in id part";
                $message = "updated";
                $url = $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $request['template_id'];
            } else {
                // create new template
                $message = "created";
                // echo "comes in else part";
                $url = $url;
            }
            // exit;
            $body = array(
                "name" => $request["name"],
                "language" => $request["language"],
                "category" => $request["category"],
                "components" => $request["components"],
                "allow_category_change" => false,
            );
            // print_r(json_encode($body));exit;
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
                    'Authorization: Bearer ' . $this->fb_auth_token
                ),
            ));

            $response = curl_exec($curl);
            $decodedResponse = json_decode($response, true);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $template_id = isset($decodedResponse['id']) ? $decodedResponse['id'] : null;
            $template_status = isset($decodedResponse['status']) ? $decodedResponse['status'] : null;
            curl_close($curl);
            // echo $response;exit;
            if ($template_id) {
                //Get the Store id from the login data
                $user_id = $loginData['user_id'];
                $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id";
                $result = $db->query($sql);
                $vendor_id = $result->fetch_assoc()['vendor_id'];

                // Generate unique user ID
                $uid = bin2hex(random_bytes(8));

                if ($request["template_id"]) {
                    //Update query
                    $sql = "UPDATE cmp_whatsapp_templates 
                    SET 
                        vendor_id = '" . $vendor_id . "',
                        template_name = '" . mysqli_real_escape_string($db, $request["name"]) . "',
                        category = '" . mysqli_real_escape_string($db, $request["category"]) . "',
                        body_data = '" . mysqli_real_escape_string($db, json_encode($body)) . "',
                        media_id = '" . $mediaId . "',
                        updated_by = '" . $loginData['user_id'] . "',
                        updated_date = NOW()
                        WHERE template_id = '" . $template_id . "'";

                    $db->query($sql);
                } else {
                    //Insertion query
                    $sql = "INSERT INTO cmp_whatsapp_templates 
                        (uid, vendor_id, template_id, template_name, category, language, body_data, media_id, template_status, created_by) 
                        VALUES 
                        ('" . $uid . "','" . $vendor_id . "', '" . $template_id . "','" . mysqli_real_escape_string($db, $request["name"]) . "', '" . mysqli_real_escape_string($db, $request["category"]) . "', 
                        '" . mysqli_real_escape_string($db, $request["language"]) . "', '" . mysqli_real_escape_string($db, json_encode($body)) . "', '" . $mediaId . "' , '" . $template_status . "', '" . $loginData['user_id'] . "'
                        )";

                    $db->query($sql);
                }
                $db->close();

                if ($httpCode == "200") {
                    return array(
                        "apiStatus" => array(
                            "code" => "200",
                            "message" => "Whatsapp template $message successfully",
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
            } else {
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Error occured in META API service!",
                    ),
                );
            }
            // if($createTemplateRequest['status'] == 'REJECTED') {
            //     $this->processSyncTemplates();
            //     $rejectedReason = $this->whatsAppApiService->getTemplateRejectionReason($createTemplateRequest['id']);
            //     return $this->engineFailedResponse([], __tr('Template has been rejected due to __rejectedReason__', [
            //         '__rejectedReason__' => $rejectedReason['rejected_reason']
            //     ]));
            // } elseif($createTemplateRequest['status'] == 'APPROVED') {
            //     $this->processSyncTemplates();
            //     return $this->engineSuccessResponse([], __tr('Your template has been created and approved'));
            // }
            // $this->processSyncTemplates();
            // return $this->engineSuccessResponse([], __tr('Your template has submitted for review and it is now __templateStatus__', [
            //     '__templateStatus__' => $createTemplateRequest['status']
            // ]));
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }

    protected function templateList($data, $loginData)
    {
        try {

            $this->fbCredentials($loginData);

            $url = $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->whatsapp_business_id . '/' . "message_templates";
            if ($data['limit']) {
                $url .= "?limit=" . $data['limit'] . "";
            }
            if ($data['before']) {
                $url .= "&before=" . $data['before'] . "";
            }
            if ($data['after']) {
                $url .= "&after=" . $data['after'] . "";
            }
            // Check if pageIndex and dataLength are not empty
            if ($data['pageIndex'] === "") {
                throw new Exception("PageIndex should not be empty!");
            }
            if ($data['dataLength'] == "") {
                throw new Exception("dataLength should not be empty!");
            }

            $start_index = $data['pageIndex'] * $data['dataLength'];
            $end_index = $data['dataLength'];
            // echo $url;exit;
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
                    'Authorization: Bearer ' . $this->fb_auth_token . ''
                ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            $responseData = json_decode($response, true);
            $template_id = isset($responseData['id']) ? $responseData['id'] : null;
            $templates = $responseData["data"] ?? [];
            $paging = $responseData["paging"] ?? [];
            $count = count($templates);
            // echo $count;
            // exit;
            curl_close($curl);
            $db = $this->dbConnect();
            //Get the Store id from the login data
            $user_id = $loginData['user_id'];
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id";
            $result = $db->query($sql);
            $vendor_id = $result->fetch_assoc()['vendor_id'];
// print_r($vendor_id);exit;
            // Generate unique user ID
            $uid = bin2hex(random_bytes(8));
            $sql = "SELECT id, template_id, created_date, updated_date FROM cmp_whatsapp_templates WHERE status = 1 AND vendor_id = '" . $vendor_id . "' ";
            // print_r($sql);exit;
            $result = $db->query($sql);


            // print_r($totalCount);exit;

            $dbTemplateDates = [];
            while ($row = $result->fetch_assoc()) {
                $dbTemplateDates[$row['template_id']] = [
                    "created_date" => $row['created_date'],
                    "updated_date" => $row['updated_date']
                ];
            }

            // Merge created_date & updated_date and update/insert into DB
            foreach ($templates as $template) {
                $template_id = $template['id'] ?? null;
                $templateName = $template['name'] ?? null;
                $templateCategory = $template['category'] ?? null;
                $templatelanguage = $template['language'] ?? null;
                $status = $template['status'] ?? null;
                $templatedData = json_encode($template);
                if ($template_id && isset($dbTemplateDates[$template_id])) {
                    // Exists: update status and updated_date
                    $template['created_date'] = $dbTemplateDates[$template_id]['created_date'];
                    $template['updated_date'] = $dbTemplateDates[$template_id]['updated_date'];

                    $updateSql = "UPDATE cmp_whatsapp_templates 
                                  SET status = 1, updated_date = NOW() 
                                  WHERE
                                     template_status = '" . $status . "' AND
                                   template_id = '$template_id'";
                    $db->query($updateSql);
                } else if ($template_id) {
                    // Not exists: insert new record
                    $created_date = date('Y-m-d H:i:s');
                    $insertSql = "INSERT INTO cmp_whatsapp_templates 
                        (uid, vendor_id, template_id, template_name, category, language, body_data,  template_status, created_by) 
                        VALUES ('$uid','$vendor_id','$template_id','$templateName' ,'$templateCategory','$templatelanguage','" . mysqli_real_escape_string($db, $templatedData) . "', '$status', '" . $loginData['user_id'] . "')";
                    // print_r($insertSql);exit;
                    $db->query($insertSql);

                    $template['created_date'] = null;
                    $template['updated_date'] = null;
                }
            }
            $db->commit();
            // Get total count of templates from db
            $totalCount = "SELECT * FROM cmp_whatsapp_templates WHERE status = 1 AND vendor_id = '" . $vendor_id . "' ";

            $countResult = $db->query($totalCount);
            $row_cnt = mysqli_num_rows($countResult);
            $query = "SELECT * FROM cmp_whatsapp_templates WHERE status = 1 AND vendor_id = '" . $vendor_id . "' 
                ORDER BY id DESC 
                 LIMIT $start_index, $end_index";
                 
            $result = $db->query($query);
            $templates = [];
// created_by = '" . $loginData['user_id'] . "'
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $templates[] = array(
                        "id" => $row['template_id'],
                        "name" => $row['template_name'],
                        "category" => $row['category'],
                        "language" => $row['language'],
                        "status" => $row['template_status'],
                        "created_date" => $row['created_date'],
                        "updated_date" => $row['updated_date']
                    );
                }

                //// Construct the final response array
                $responseArray = array(
                    "pageIndex" => $data['pageIndex'],
                    "dataLength" => $data['dataLength'],
                    "totalRecordCount" => $row_cnt,
                    'TemplateData' => array_values($templates), // Reset array keys
                );
                if (!empty($templates)) {
                    $resultArray = array(
                        "apiStatus" => array(
                            "code" => "200",
                            "message" => "Template details fetched successfully",
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
            }
            return $resultArray;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }


    public function templateByID($data, $loginData)
    {
        try {

            // print_r($data);exit;
            $template_id = $data[2];
            if (empty($template_id)) {
                throw new Exception("Give template id");
            }

            $this->fbCredentials($loginData);

            $url =  $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $template_id . '?' . "access_token=$this->fb_auth_token";
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
                    'Authorization: Bearer ' . $this->fb_auth_token . ''
                ),
            ));

            $response = curl_exec($curl);
            $responseData = json_decode($response, true);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // echo $httpCode;
            // exit;
            curl_close($curl);
            // echo $response;exit;

            $db = $this->dbConnect();
            $sql = "SELECT id, template_id, media_id, created_date, updated_date from cmp_whatsapp_templates where status = 1 and template_id = '" . $template_id . "' AND created_by = '" . $loginData['user_id'] . "'";
            $result = $db->query($sql);
            $dbData = $result->fetch_assoc();
            // print_r($sql);exit;
            $responseData['created_date'] = $dbData['created_date'] ?? null;
            $responseData['updated_date'] = $dbData['updated_date'] ?? null;
            $responseData['media_id'] = $dbData['media_id'] ?? null;

            if ($httpCode == "200") {
                return array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Whatsapp template successfully listed",
                    ),
                    "result" => $responseData
                );
            } else {
                // echo $response;
                return array(
                    "apiStatus" => array(
                        "code" => $httpCode,
                        "message" => "Error occured!",
                    ),
                    "result" => json_decode($response)
                );
            }
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => $e->getMessage(),
                )
            );
        }
    }


    protected function getTemplatedropdownWapp($data, $loginData)
    {
        try {
            $this->fbCredentials($loginData);
            $url = $this->facebook_base_url . "/" . $this->facebook_base_version . "/" . $this->whatsapp_business_id . "/" . "message_templates?fields=name,status&status=APPROVED";
            // echo $url;exit;
            $queryParams = [];
            if (!empty($data['limit'])) {
                $queryParams[] = "limit=" . $data['limit'];
            }
            if (!empty($data['before'])) {
                $queryParams[] = "before=" . $data['before'];
            }
            if (!empty($data['after'])) {
                $queryParams[] = "after=" . $data['after'];
            }
            if (!empty($queryParams)) {
                $url .= '?' . implode('&', $queryParams);
            }

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->fb_auth_token
                ],
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            $responseData = json_decode($response, true);
// print_r($responseData);exit;

            $approvedTemplates = [];

            if (isset($responseData["data"]) && is_array($responseData["data"])) {
                foreach ($responseData["data"] as $template) {
                    // Only include templates with "APPROVED" status
                    // if (isset($template["status"]) && strtoupper($template["status"]) === "APPROVED") {
                    $approvedTemplates[] = [
                        "id" => $template["id"] ?? null,
                        "template_name" => $template["name"] ?? null
                    ];
                    // }
                }
            }

            $count = count($approvedTemplates);

            if ($httpCode == 200) {
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Approved WhatsApp templates successfully listed",
                        "total_count" => $count,
                    ],
                    "result" => [
                        "total_count" => $count,
                        "templateList" => $responseData
                    ]
                ];
            } else {
                return [
                    "apiStatus" => [
                        "code" => $httpCode,
                        "message" => "Error occurred!",
                    ],
                    "result" => json_decode($response, true)
                ];
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

    public function getLanguageCodes($data, $loginData)
    {
        try {

            $db = $this->dbConnect();
            $sql = "SELECT id, language_name, language_code from cmp_whatsapp_template_languages";
            $result = $db->query($sql);

            $languageList = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $languageList[] = [
                        "id" => $row['id'],
                        "language_name" => $row['language_name'],
                        "language_code" => $row['language_code']
                    ];
                }

                return array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Language codes successfully listed",
                    ),
                    "result" => $languageList
                );
            } else {
                // echo $response;
                return array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "No data found",
                    ),
                );
            }
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => $e->getMessage(),
                )
            );
        }
    }

    public function deleteTemplate($data, $loginData)
    {
        try {
            //code...
            // print_r($data);
            $template_id = $data[2];
            $template_name = $data[3];

            $this->fbCredentials($loginData);

            if (empty($template_id)) {
                throw new Exception("Give template id");
            }

            if (empty($template_name)) {
                throw new Exception("Give template name");
            }

            $url =  $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->whatsapp_business_id . '/' . "message_templates" . "?hsm_id=$template_id&name=$template_name";

            // $url =  "https://graph.facebook.com/v22.0/4077810655839281/message_templates?hsm_id=$template_id&name=$template_name";
            // echo $url;exit;
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
                    'Authorization: Bearer ' . $this->fb_auth_token
                ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            // echo $response;
            // echo $httpCode;exit;
            $db = $this->dbConnect();
            $sql = "UPDATE cmp_whatsapp_templates SET status = 0 where template_id = '" . $template_id . "'";
            $db->query($sql);

            if ($httpCode == "200") {
                return array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Whatsapp template deleted successfully",
                    ),
                    "result" => json_decode($response)
                );
            } else {
                // echo $response;
                return array(
                    "apiStatus" => array(
                        "code" => $httpCode,
                        "message" => "Error occured!",
                    ),
                    "result" => json_decode($response)
                );
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


    public function uploadMedia($data, $loginData)
    {
        try {
            // Check if file is uploaded
            if (!isset($_FILES['media_file'])) {
                throw new Exception("No file uploaded");
            }

            // print_r($_FILES);exit;
            // Get file details
            $file = $_FILES['media_file'];
            $fileName = $file['name'];
            $fileTmpPath = $file['tmp_name'];
            $fileType = $file['type'];
            $fileSize = $file['size'];

            // echo $fileSize;

            $this->fbCredentials($loginData);

            //for upload media session
            $sessionUrl =  $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->facebook_app_id . '/' . "uploads?file_name=$fileName&file_length=$fileSize&file_type=$fileType&access_token=$this->fb_auth_token";
            //  print_r($sessionUrl) ;
            //  exit;

            //Call Upload session url
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $sessionUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
            ));

            $response = curl_exec($curl);
            //  echo $response;exit;
            $structured = json_decode($response, true);
            $settionId = $structured['id'];
            curl_close($curl);
            if ($settionId) {
                // Read binary data from the file
                $binaryData = file_get_contents($fileTmpPath);
                //  print_r($binaryData);

                $uploadUrl = $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $settionId;
                //  echo $uploadUrl;exit;

                //Curl for upload media
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $uploadUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $binaryData,
                    CURLOPT_HTTPHEADER => array(
                        'file_offset: 0',
                        'Authorization: OAuth ' . $this->fb_auth_token,
                        'Content-Type: ' . $fileType
                    ),
                ));

                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                $firstResponse = json_decode($response, true);

                // After uploading, get the media ID from the upload media
                $mediaData = $this->uploadMediaGetID($loginData, $file);
                // print_r(json_encode($mediaData));exit;
                if (isset($mediaData)) {
                    $mergedResponse = array_merge($firstResponse, $mediaData);
                    // print_r(json_encode($mergedResponse));exit;
                    if ($mediaData['id']) {
                        return array(
                            "apiStatus" => array(
                                "code" => "200",
                                "message" => "Media uploaded successfully",
                            ),
                            "result" => ($mergedResponse)
                        );
                    } else {
                        // echo "just ecjo";
                        return array(
                            "apiStatus" => array(
                                "code" => "401",
                                "message" => "Media upload error, Try again!",
                            ),
                            "result" => ($mergedResponse)
                        );
                    }
                    // echo json_encode($mergedResponse, JSON_PRETTY_PRINT);
                }
                exit;
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

    public function uploadMediaGetID($loginData, $file)
    {
        try {
            // print_r($file);exit;
            $this->fbCredentials($loginData);

            $uploadUrl = $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->phone_no_id . '/media';
            // print_r($uploadUrl);exit;
            // Now, pass the uploaded file dynamically using $_FILES data
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $uploadUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'messaging_product' => 'whatsapp',
                    'file' => new CURLFILE($file['tmp_name'], $file['type'], $file['name'])
                ),
                CURLOPT_HTTPHEADER => array(
                    'file_offset: 0',
                    'Authorization: OAuth ' . $this->fb_auth_token,
                ),
            ));
            // echo $this->fb_auth_token;exit;
            $response = curl_exec($curl);
            curl_close($curl);

            $responseDecoded = json_decode($response, true);
            // print_r($responseDecoded);exit;
            // Return the response from media upload to extract the media ID
            return $responseDecoded;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }

    private function getUsingCampCredentials($data, $loginData)
    {
        try {
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
            AND gc.vendor_id = " . $this->getVendorIdByUserId($loginData) . "
        ORDER BY gc.id DESC";

            $result = $db->query($queryService);
            $rowCount = mysqli_num_rows($result);

            if ($rowCount > 0) {
                $contacts = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $contacts[] = $row;
                }

                // Fetch the template dynamically
                $template_id = $data['templateId'];
                $templateResponse = $this->templateByID(["", "", $template_id], $loginData);
                $templateData = $templateResponse['result'];
                // print_r(json_encode($templateResponse));exit;
                // Return contacts along with template
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Contacts and template fetched successfully",
                    ],
                    "result" => [
                        "contacts" => $contacts,
                        "template" => $templateData,
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

    public function sendMessage($data, $loginData, $campaign_id, $iscampaign)
    {
        try {


            $this->fbCredentials($loginData);
            if (isset($data['scheduleStatus']) && $data['scheduleStatus'] === true) {
                // It's a scheduled message, don't send immediately
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Message has been scheduled to be sent at " . $data['scheduledAt'],
                    ],
                    "result" => [
                        "scheduled" => true,
                        "scheduledAt" => $data['scheduledAt'],
                        "timezone" => $data['timezone']['zoneName'] ?? 'Not Provided',
                    ],
                ];
            }

            // Proceed to send immediately
            $fetchResponse = $this->getUsingCampCredentials($data, $loginData);

            if ($fetchResponse['apiStatus']['code'] != "200") {
                return $fetchResponse;
            }

            $contacts = $fetchResponse['result']['contacts'];
            $template = $fetchResponse['result']['template'];

            $successRecipient = [];
            $failureRecipient = [];
            $responseArray = [];

            foreach ($contacts as $contact) {
                $dynamicComponents = $this->prepareDynamicComponents($template['components'], $contact, $data['variableIds'], $template['media_id'], $iscampaign,$campaign_id);

                $body = [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $contact['mobile'],
                    'type' => 'template',
                    'template' => [
                        'name' => $template['name'],
                        'language' => [
                            'code' => $template['language'],
                        ],
                        'components' => $dynamicComponents,
                    ],
                ];
// print_r(json_encode($body));exit;
                $insertcampaign = "INSERT INTO cmp_campaign_contact(campaign_id, contact_id, created_by, created_date) VALUES('" . $campaign_id . "','" . $contact['contactId'] . "','" . $loginData['user_id'] . "', NOW())";
                $db = $this->dbConnect();
                $db->query($insertcampaign);

                $curl = curl_init();
                $url = $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->phone_no_id . '/messages';

                curl_setopt_array($curl, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($body),
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $this->fb_auth_token,
                    ],
                ]);

                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                $responseArray[] = json_decode($response, true);

                if ($httpCode == 200) {
                    $successRecipient[] = $contact['mobile'];
                } else {
                    $failureRecipient[] = $contact['mobile'];
                }
            }

            return [
                "apiStatus" => [
                    "code" => "200",
                    "message" => "WhatsApp template message executed successfully",
                ],
                "result" => [
                    "successRecipients" => $successRecipient,
                    "failureRecipients" => $failureRecipient,
                    "apiResponse" => $responseArray,
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


    /**
     * Prepare dynamic components based on the template and contact data
     */
    private function prepareDynamicComponents($templateComponents, $contact, $variableIds, $mediaId, $iscampaign,$campaign_id)
    {
        // print_r((json_encode($mediaId)));
        // exit;

        // print_r($variableIds['body']);exit;
        $dynamicComponents = [];
        $headerHasVariable = false;

        // Loop through each component to adjust dynamically based on the type and contact data
        foreach ($templateComponents as $component) {
            // Debugging: Check the component structure
            // print_r(json_encode($component));  // This will print the current component before processing

            switch ($component['type']) {
                case 'HEADER':
                    // If header contains text (replace placeholders like {{1}}, {{2}} etc.)
                    if (isset($component['format']) == 'TEXT' && isset($component['example']['header_text'])) {
                        // Replace placeholders in header text dynamically
                        // echo "csdkf";exit;

                        // print_r(json_encode($variableIds));
                        $headerHasVariable = true;
                        $dynamicComponents[] = $this->prepareHeaderTextComponent($component, $contact, $variableIds);
                    } else if ($component['format'] != 'TEXT') {
                        // echo "csdkf";exit;

                        $dynamicComponents[] = $this->prepareHeaderMediaComponent($component, $contact, $mediaId, $iscampaign,$campaign_id);
                    }
                    break;

                case 'BODY':

                    // print_r(json_encode($component));exit;
                    // Only add the body component if there is dynamic text to replace
                    if (isset($component['text']) && isset($component['example']['body_text'])) {
                        $dynamicComponents[] = $this->prepareBodyComponent($component, $contact, $variableIds, $headerHasVariable);
                    }
                    break;

                case 'FOOTER':
                    // Process the footer text if it exists
                    if (isset($component['text']) && !empty($component['text'])) {
                        $dynamicComponents[] = $this->prepareFooterComponent($component, $contact);
                    }
                    break;

                    // case 'BUTTONS':
                    //     // Process buttons if they exist
                    //     if (isset($component['buttons']) && count($component['buttons']) > 0) {
                    //         $dynamicComponents[] = $this->prepareButtonComponent($component, $contact);
                    //     }
                    //     break;

                    // Add cases for any other custom component types here if needed
            }
        }

        // Debugging: Print final dynamic components before returning
        // print_r(json_encode($dynamicComponents));  // This will print the components array after processing
        return $dynamicComponents;
    }


    private function prepareHeaderTextComponent($component, $contact, $variableIds)
    {
        // exit;
        // print_r($variableIds);
        // exit;
        $dynamicParameters = [];
        preg_match_all('/\{\{(\d+)\}\}/', $component['text'], $matches); // Find placeholders

        // Check if header has a variable (header can only have 1 variable)
        if (count($matches[1]) == 1) {
            $varName = (int) $matches[1][0];  // Get the placeholder number

            // Find the corresponding variable in the header
            $variable = $this->findVariableByName($varName, $variableIds, true);  // Pass 'true' for header

            if ($variable) {
                $dynamicValue = $this->getDynamicValueForVariable($variable, $contact);

                // Add the dynamic value to the parameters
                $dynamicParameters[] = [
                    'type' => 'text',
                    'text' => $dynamicValue  // Replace {{1}} with the dynamic value
                ];
            }
        }

        return [
            'type' => 'HEADER',
            'parameters' => $dynamicParameters
        ];
    }


    private function prepareHeaderMediaComponent($component, $contact, $mediaId, $iscampaign,$campaign_id)
    {
        $db = $this->dbConnect();
        if ($iscampaign === "campaign") {
            $sql = "SELECT media_id,media_url FROM cmp_campaign WHERE id='" . $campaign_id . "' AND status=1";
            $result = $db->query($sql);
            $row = $result->fetch_assoc();
            $dbmediaId = $row['media_id'];
        }
        // print_r($dbmediaId);
        // Assuming the header media is an image (you can extend this for other media types like video)
        if (isset($component['format'])) {
            return [
                'type' => 'HEADER',
                'parameters' => [
                    [
                        'type' => $component['format'],
                        strtolower($component['format']) => [
                            // 'link' => $component['example']['header_handle'][0]
                            "id" => $dbmediaId ? $dbmediaId : $mediaId
                            // "id" => $mediaId
                        ]
                    ]
                ]
            ];
        }
        // You can extend this for other media types like videos
        // if (isset($component['parameters'][0]['video']['id'])) {
        //     return [
        //         'type' => 'HEADER',
        //         'parameters' => [
        //             [
        //                 'type' => 'video',
        //                 'video' => [
        //                     'id' => $component['parameters'][0]['video']['id']
        //                 ]
        //             ]
        //         ]
        //     ];
        // }

        return []; // Return an empty array if no valid media is found
    }


    private function prepareBodyComponent($component, $contact, $variableIds, $headerHasVariable)
    {
        $dynamicParameters = [];
        preg_match_all('/\{\{(\d+)\}\}/', $component['text'], $matches); // Find placeholders like {{1}}, {{2}}, etc.

        // Loop through all placeholders found in the body text
        foreach ($matches[1] as $index) {
            // Determine the variable name (this is the number from the placeholder, like 1, 2, etc.)
            $varName = (int) $index;

            // Find the corresponding variable in the body (multiple variables possible)
            $variable = $this->findVariableByName($varName, $variableIds, false);  // Pass 'false' for body

            if ($variable) {
                $dynamicValue = $this->getDynamicValueForVariable($variable, $contact);

                // Add the dynamic value to the parameters
                $dynamicParameters[] = [
                    'type' => 'text',
                    'text' => $dynamicValue  // Replace {{1}} with the dynamic value
                ];
            }
        }

        return [
            'type' => 'BODY',
            'parameters' => $dynamicParameters
        ];
    }




    /**
     * Find the variable from variableIds by its varName (like {{1}}, {{2}}, etc.)
     */
    private function findVariableByName($varName, $variableIds, $isHeader = false)
    {
        // Loop through the variableIds array
        // print_r(json_encode($variableIds));exit;
        foreach ($variableIds as $group) {
            // print_r(json_encode($group));exit;
            // Check if the group type is 'header' or 'body'
            if ($isHeader && $group['type'] == 'header') {
                // Find the variable in the header group
                foreach ($group['variables'] as $variable) {
                    if ($variable['varName'] == (string)$varName) {
                        return $variable;  // Return the variable from the header
                    }
                }
            } elseif (!$isHeader && $group['type'] == 'body') {
                // Find the variable in the body group
                foreach ($group['variables'] as $variable) {
                    if ($variable['varName'] == (string)$varName) {
                        return $variable;  // Return the variable from the body
                    }
                }
            }
        }

        return null;  // Return null if no variable found
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


    /**
     * Prepare the BODY component with dynamic content (e.g., variables like {first_name}, etc.)
     */
    private function prepareFooterComponent($component, $contact)
    {
        $text = $this->replacePlaceholders($component['text'], $contact);  // Replace placeholders if needed

        return [
            'type' => 'FOOTER',
            'parameters' => [
                [
                    'type' => 'text',
                    'text' => $text,
                ]
            ]
        ];
    }

    /**
     * Prepare BUTTONS component with dynamic content
     */
    private function prepareButtonComponent($component, $contact)
    {
        $buttons = [];

        // Loop through the buttons and replace placeholders if necessary
        foreach ($component['buttons'] as $button) {
            if (isset($button['text'])) {
                // Replace placeholders in button text
                $button['text'] = $this->replacePlaceholders($button['text'], $contact);
            }

            // Add the button to the buttons array
            $buttons[] = [
                'type' => $button['type'],
                'text' => $button['text']
            ];
        }

        return [
            'type' => 'BUTTON',
            'parameters' => $buttons
        ];
    }


    /**
     * Replace placeholders in the template text with actual contact information
     */
    private function replacePlaceholders($text, $contact)
    {
        // Replace placeholders (e.g., {{1}}, {{2}}, etc.) with dynamic values from the contact
        $placeholders = [
            '{{1}}' => isset($contact['firstName']) ? $contact['firstName'] : '',  // Replace {{1}} with first name
            '{{2}}' => isset($contact['lastName']) ? $contact['lastName'] : '',   // Replace {{2}} with last name
            '{{3}}' => isset($contact['mobile']) ? $contact['mobile'] : '',       // Replace {{3}} with mobile number
            '{{4}}' => isset($contact['email']) ? $contact['email'] : '',         // Replace {{4}} with email
            // Add more placeholders as needed
        ];

        // Loop through all placeholders and replace them in the text
        foreach ($placeholders as $placeholder => $replacement) {
            $text = str_replace($placeholder, $replacement, $text);
        }

        return $text;
    }







    // public function sendMessage($data, $loginData)
    // {
    //     try {
    //         //code...
    //         // print_r(json_encode($data['template']['components']));exit;
    //         $this->fbCredentials($loginData);

    //         $successRecipient = [];
    //         $failureRecipient = [];

    //         $url =  $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->phone_no_id . '/' . "messages";
    //         foreach ($data['to'] as $recipient) {
    //             // echo $recipient;
    //             $body = [
    //                 'messaging_product' => 'whatsapp',
    //                 'recipient_type' => 'individual',
    //                 'to' => $recipient,
    //                 'type' => 'template',
    //                 'template' => [
    //                     'name' => $data['template']['name'],
    //                     'language' => [
    //                         'code' => $data['template']['language']['code'],
    //                     ],
    //                     'components' => $data['template']['components'],
    //                 ],
    //             ];
    //             // exit;
    //             $curl = curl_init();

    //             curl_setopt_array($curl, array(
    //                 CURLOPT_URL => $url,
    //                 CURLOPT_RETURNTRANSFER => true,
    //                 CURLOPT_ENCODING => '',
    //                 CURLOPT_MAXREDIRS => 10,
    //                 CURLOPT_TIMEOUT => 0,
    //                 CURLOPT_FOLLOWLOCATION => true,
    //                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //                 CURLOPT_CUSTOMREQUEST => 'POST',
    //                 CURLOPT_POSTFIELDS => json_encode($body),
    //                 CURLOPT_HTTPHEADER => array(
    //                     'Content-Type: application/json',
    //                     'Authorization: Bearer ' . $this->fb_auth_token
    //                 ),
    //             ));

    //             $response = curl_exec($curl);
    //             $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //             curl_close($curl);


    //             // sleep(1); 
    //             if ($httpCode == 200) {
    //                 $successRecipient[] = $recipient;
    //             } else {
    //                 $failureRecipient[] = $recipient;
    //             }
    //         }

    //         if (count($data['to']) > 1) {
    //             $responses = [
    //                 "apiStatus" => [
    //                     "code" =>  "200",
    //                     "message" => "WhatsApp bulk template message executed successfully",
    //                 ],
    //                 "result" => [
    //                     "successRecipients" => $successRecipient,
    //                     "failureRecipients" => $failureRecipient,
    //                     "apiResponse" => json_decode($response)
    //                 ]
    //             ];
    //         } else {
    //             $responses = [
    //                 "apiStatus" => [
    //                     "code" => $httpCode == 200 ? "200" : "400",
    //                     "message" => $httpCode == 200 ? "WhatsApp template message sent successfully" : "Error occurred!",
    //                 ],
    //                 "result" => [
    //                     "apiResponse" => json_decode($response)
    //                 ]
    //             ];
    //         }

    //         return $responses;
    //     } catch (Exception $e) {
    //         return array(
    //             "apiStatus" => array(
    //                 "code" => "401",
    //                 "message" => $e->getMessage(),
    //             ),
    //         );
    //     }
    // }

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
    /**
     * Manual API requests
     *
     * @return array
     */
    // protected function apiGetRequest(string $requestSubject, array $parameters = [])
    // {
    //     return $this->baseApiRequest()->get("{$this->baseApiRequestEndpoint}{$requestSubject}", $parameters)->json();
    // }


    /**
     * Base API requests
     *
     * @return Http query request
     */
    // protected function baseApiRequest($requestBaseObject = null)
    // {
    //     if ($requestBaseObject) {
    //         $baseRequest = $requestBaseObject->withToken($this->getServiceConfiguration('whatsapp_access_token'));
    //     } else {
    //         $baseRequest = Http::withToken($this->getServiceConfiguration('whatsapp_access_token'));
    //     }
    //     return $baseRequest->throw(function ($response, $e) {
    //         $getContents = $response->getBody()->getContents();
    //         $getContentsDecoded = json_decode($getContents, true);
    //         $userMessage = Arr::get($getContentsDecoded, 'error.error_user_title', '') . ' '
    //         . Arr::get($getContentsDecoded, 'error.message', '') . ' '
    //         . Arr::get($getContentsDecoded, 'error.error_user_msg', '') . ' '
    //         . Arr::get($getContentsDecoded, 'error.error_data.details');
    //         if (!$userMessage) {
    //             $userMessage = $e->getMessage();
    //         }
    //         // __logDebug($userMessage);
    //         // set notification as your key is token expired
    //         if (Str::contains($e->getMessage(), 'Session has expired') and !getVendorSettings(
    //             'whatsapp_access_token_expired',
    //             null,
    //             null,
    //             $this->vendorId ?? getVendorId()
    //         )
    //         ) {
    //             setVendorSettings(
    //                 'internals',
    //                 [
    //                     'whatsapp_access_token_expired' => true
    //                 ],
    //                 $this->vendorId ?? getVendorId()
    //             );
    //         }
    //         // stop and response back for error if any
    //         if (!ignoreFacebookApiError()) {
    //             // stop and response back for error if any
    //             abortIf(
    //                 true,
    //                 $response->status(),
    //                 $userMessage
    //             );
    //         }
    //     });
    // }

    // Unautherized api request
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
