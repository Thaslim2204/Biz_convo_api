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

class SMSTemplateMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getSmsTemplate($data, $loginData);
                } elseif ($urlParam[1] === 'id_dropdown') { //template id dropdown list
                    $result = $this->smsTemplateIdDropdown($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'sender_id_dropdown') {
                    $result = $this->smsSenderIdDropdown($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'storedropdown') {
                    // $result = $this->getStoredropdown($data, $loginData);
                    // return $result;
                    // } elseif ($urlParam[1] === 'exportstoretoexcel') {
                    // $result = $this->exportStoreToExcel($data, $loginData);
                    // return $result;
                    // } elseif ($urlParam[1] === 'exportisheader') {
                    // $result = $this->isheaderonly($data, $loginData);
                    // return $result;
                } elseif ($urlParam[1] == "active") {
                    $result = $this->SmsTemplateactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactive") {
                    $result = $this->SmsTemplateDeactive($data, $loginData);
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
                    $result = $this->createSmsTemplate($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    $result = $this->getSmsTemplatedetails($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'importstorefromexcel') {
                    // $result = $this->importStoreFromExcel($data, $loginData);
                    // return $result;

                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    $result = $this->updateSmsTemplate($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    $result = $this->deleteSmsTemplate($data);
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

    public function getSmsTemplatedetails($data, $loginData)
    {
        try {
            $responseArray = ''; // Initializing response variable
            $db = $this->dbConnect();
            $userId = $loginData['user_id'];

            //get the vendor id from the login data
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $userId";
            $result = $db->query($sql);
            if ($result) {
                $row = $result->fetch_assoc();
                if (!$row || !isset($row['vendor_id'])) {
                    throw new Exception("Vendor ID not found for user ID: $userId");
                }
                $vendorId = $row['vendor_id'];
            } else {
                throw new Exception("Database query failed: " . $db->error);
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

            // Query to count total records
            $queryCount = "SELECT count(*) AS totalrecordcount from cmp_sms_templates WHERE status= 1 AND vendor_id = " . $vendorId . " ";
            // print_r($queryCount);exit;
            $resultCount = $db->query($queryCount);
            $rowCount = $resultCount->fetch_assoc();
            $recordCount = $rowCount['totalrecordcount'];
            // Check if record count is greater than 0
            // if ($recordCount <= 0) {
            //     throw new Exception("No data found...");
            // }
            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT id,vendor_id,sender_id,template_id,template_name,sms_type,language,template_content,created_by,created_date,active_status,test_mobile 
            FROm cmp_sms_templates 
                 WHERE status = 1 AND vendor_id = " . $vendorId . "  
                 ORDER BY id DESC 
                 LIMIT $start_index, $end_index";

            //   print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);
            $smsData = array(); // Initialize array to hold Store data
            if ($row_cnt > 0) {
                while ($row = $result->fetch_assoc()) {
                    $smsData[] = array(
                        "id" => $row['id'],
                        "vendorId" => $row['vendor_id'],
                        "senderId" => $row['sender_id'],
                        "templateId" => $row['template_id'],
                        "templateName" => $row['template_name'],
                        "smsType" => $row['sms_type'],
                        "language" => $row['language'],
                        "templateContent" => $row['template_content'],
                        "createdBy" => $row['created_by'],
                        "createdDate" => $row['created_date'],
                        "activeStatus" => $row['active_status'],
                        "testMobile" => $row['test_mobile'],
                    );
                }
            }
            // Construct the final response array
            $responseArray = array(
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $recordCount,
                'smsTemplateDataDetails' => $smsData,
            );

            // Check if Store data exists
            if (!empty($smsData)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Sms Template details fetched successfully",
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
    public function getSmsTemplate($data, $loginData)
    {
        try {
            $id = $data[2];
            if (empty($id)) {
                throw new Exception("Bad request");
            }
            $db = $this->dbConnect();
            $userId = $loginData['user_id'];
            //get the vendor id from the login data
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $userId";
            $result = $db->query($sql);
            if ($result) {
                $row = $result->fetch_assoc();
                if (!$row || !isset($row['vendor_id'])) {
                    throw new Exception("Vendor ID not found for user ID: $userId");
                }
                $vendorId = $row['vendor_id'];
            } else {
                throw new Exception("Database query failed: " . $db->error);
            }

            $db = $this->dbConnect();
            $sql = "SELECT id,vendor_id,sender_id,template_id,template_name,sms_type,language,template_content,created_by,created_date,active_status,test_mobile 
            FROm cmp_sms_templates   
                    WHERE id = $id AND status = 1 AND vendor_id = " . $vendorId . " 
                      ";

            $result = $db->query($sql);

            // Check if Store exists
            if ($result->num_rows > 0) {
                $SmsData = array();
                $contactPersons = array();

                while ($row = $result->fetch_assoc()) {
                    $SmsData = array(
                        "id" => $row['id'],
                        "vendorId" => $row['vendor_id'],
                        "senderId" => $row['sender_id'],
                        "templateId" => $row['template_id'],
                        "templateName" => $row['template_name'],
                        "smsType" => $row['sms_type'],
                        "language" => $row['language'],
                        "templateContent" => $row['template_content'],
                        "createdBy" => $row['created_by'],
                        "createdDate" => $row['created_date'],
                        "activeStatus" => $row['active_status'],
                        "testMobile" => $row['test_mobile'],
                    );
                }
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Sms Template detail fetched successfully",
                    ),
                    "result" => $SmsData
                );
            } else {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "Sms Template not found",
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


    public function smsTemplateIdDropdown($data, $loginData)
    {
        try {

            $responseArray = '';
            $db = $this->dbConnect();
            $userId = $loginData['user_id'];

            //get the vendor id from the login data
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $userId";
            $result = $db->query($sql);
            if ($result) {
                $row = $result->fetch_assoc();
                if (!$row || !isset($row['vendor_id'])) {
                    throw new Exception("Vendor ID not found for user ID: $userId");
                }
                $vendorId = $row['vendor_id'];
            } else {
                throw new Exception("Database query failed: " . $db->error);
            }

            $queryService = "SELECT id,template_id,template_name
            FROm cmp_sms_templates
                 WHERE status = 1 AND vendor_id = " . $vendorId . "  
                 ";

            //   print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);
            $smsData = array(); // Initialize array to hold Store data
            if ($row_cnt > 0) {
                while ($row = $result->fetch_assoc()) {
                    $smsData[] = array(
                        "id" => $row['id'],
                        "templateId" => $row['template_id'],
                    );
                }
            }

            // Check if Store data exists
            if (!empty($smsData)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Sms Template ID list fetched successfully",
                    ),
                    "result" => $smsData,
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
                )
            );
        }
    }

    public function smsSenderIdDropdown($data, $loginData)
    {
        try {

            $responseArray = '';
            $db = $this->dbConnect();
            $userId = $loginData['user_id'];

            //get the vendor id from the login data
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $userId";
            $result = $db->query($sql);
            if ($result) {
                $row = $result->fetch_assoc();
                if (!$row || !isset($row['vendor_id'])) {
                    throw new Exception("Vendor ID not found for user ID: $userId");
                }
                $vendorId = $row['vendor_id'];
            } else {
                throw new Exception("Database query failed: " . $db->error);
            }

            $queryService = "SELECT id, sender_id
            FROm cmp_vendor_sms_credentials
                 WHERE status = 1 AND vendor_id = " . $vendorId . "  
                 ";

            //   print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);
            $smsData = array(); // Initialize array to hold Store data
            if ($row_cnt > 0) {
                while ($row = $result->fetch_assoc()) {
                    $smsData[] = array(
                        "id" => $row['id'],
                        "senderId" => $row['sender_id'],
                    );
                }
            }

            // Check if Store data exists
            if (!empty($smsData)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Sms Template ID list fetched successfully",
                    ),
                    "result" => $smsData,
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
    public function createSmsTemplate($data, $loginData)
    {
        // Initialize result array
        $resultArray = array();
        try {
            $db = $this->dbConnect();
            // Check if the logged-in user is an Admin or Super Admin
            // if (!in_array($loginData['role_name'], [ 'Vendor_super_admin'])) {
            //     throw new Exception("Permission denied. Only Super Admin can create a user.");
            // }

            // Validate input data
            $validationData = array(
                "Sender Id" => $data['senderId'],
                "Template ID"      => $data['templateDetails']['templateId'],
                "template Name"        => $data['templateDetails']['templateName'],
                "Template Content"        => $data['templateDetails']['templateContent'],
            );

            $this->validateInputDetails($validationData);
            //Get the Store id from the login data
            $user_id = $loginData['user_id'];
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id";
            $result = $db->query($sql);
            $vendor_id = $result->fetch_assoc()['vendor_id'];
            // print_r($vendor_id);exit;

            //check the Sender Id from The Credentials table
            $checkSenderIdQuery = "SELECT sender_id FROM cmp_vendor_sms_credentials WHERE sender_id = '{$data['senderId']}' AND status = 1";
            $result = $db->query($checkSenderIdQuery);
            if ($result->num_rows == 0) {
                throw new Exception("Sender ID does not exists.");
            }
            //check the template id and template name from the cmp_sms_template table
            $checkTemplateQuery = "SELECT * FROM cmp_sms_templates WHERE template_id = '{$data['templateDetails']['templateId']}' AND template_name = '{$data['templateDetails']['templateName']}' AND status = 1";
            $result = $db->query($checkTemplateQuery);
            if ($result->num_rows > 0) {
                throw new Exception("Template ID or Template Name already exists.");
            }
            $dateNow = date("Y-m-d H:i:s");
            // Insert into cmp_sms_template
            $insertStoreQuery = "INSERT INTO cmp_sms_templates (vendor_id,sender_id,template_id,template_name,sms_type,language,template_content,test_mobile, created_by,created_date)
                  VALUES ( '" . $vendor_id . "','" . $data['senderId'] . "', '" . $data['templateDetails']['templateId'] . "', '" . $data['templateDetails']['templateName'] . "', '" . $data['smsType'] . "', '" . $data['language'] . "', '" . $data['templateDetails']['templateContent'] . "', '" . $data['testMobileNo'] . "', '" . $loginData['user_id'] . "', '" . $dateNow . "')";
            // print_r($insertStoreQuery);exit;
            if ($db->query($insertStoreQuery) === true) {

                $db->close();

                $resultArray = array(
                    "apiStatus" => array(
                        "code"    => "200",
                        "message" => "Sms Template details successfully created.",
                    ),
                );
            } else {
                throw new Exception("Error occurred while inserting store: " . $db->error);
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


    public function updateSmsTemplate($data, $loginData)
    {
        $resultArray = array();
        try {
            $db = $this->dbConnect();

            // Validate input data
            $validationData = array(
                "ID" => $data['id'],
                "Sender Id" => $data['senderId'],
                "Template ID" => $data['templateDetails']['templateId'],
                "Template Name" => $data['templateDetails']['templateName'],
                "Template Content" => $data['templateDetails']['templateContent'],
            );
            $this->validateInputDetails($validationData);

            // Check if the template ID exists and is active
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_sms_templates WHERE id = '{$data['id']}' AND status = 1";
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            if ($rowCount == 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "Template does not exist.",
                    ],
                ];
            }

            // Check for duplicate template ID or name (excluding current ID)
            $checkDuplicateQuery = "SELECT COUNT(*) AS count FROM cmp_sms_templates 
                WHERE (template_id = '{$data['templateDetails']['templateId']}' OR template_name = '{$data['templateDetails']['templateName']}') 
                AND id != '{$data['id']}' AND status = 1";
            // print_r($checkDuplicateQuery);exit;
            $result = $db->query($checkDuplicateQuery);
            $duplicateCount = $result->fetch_assoc()['count'];

            if ($duplicateCount > 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "Template ID or Template Name already exists.",
                    ],
                ];
            }

            // Check if the sender ID exists in credentials
            $checkSenderIdQuery = "SELECT sender_id FROM cmp_vendor_sms_credentials 
                WHERE sender_id = '{$data['senderId']}' AND status = 1";
            $result = $db->query($checkSenderIdQuery);
            if ($result->num_rows == 0) {
                throw new Exception("Sender ID does not exist.");
            }
            $dateNow = date("Y-m-d H:i:s");
            // Update query
            $updateQuery = "UPDATE cmp_sms_templates SET 
                sender_id = '{$data['senderId']}',
                template_id = '{$data['templateDetails']['templateId']}',
                template_name = '{$data['templateDetails']['templateName']}',
                sms_type = '{$data['smsType']}',
                language = '{$data['language']}',
                template_content = '{$data['templateDetails']['templateContent']}',
                test_mobile = '{$data['testMobileNo']}',
                updated_by = '{$loginData['user_id']}',
                updated_date = '$dateNow'
                WHERE id = '{$data['id']}' AND status = 1";

            if ($db->query($updateQuery) === false) {
                throw new Exception("Failed to update SMS Template: " . $db->error);
            }

            $db->close();

            $resultArray = [
                "apiStatus" => [
                    "code" => "200",
                    "message" => "SMS Template updated successfully.",
                ],
            ];
        } catch (Exception $e) {
            if (isset($db)) {
                $db->close();
            }
            $resultArray = [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage(),
                ],
            ];
        }

        return $resultArray;
    }








    private function deleteSmsTemplate($data)
    {
        try {

            $id = $data[2];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_sms_templates WHERE id = $id AND status=1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Sms Template does not exist",
                    ),
                );
            }

            //update delete query

            $deleteQuery = "UPDATE cmp_sms_templates
            SET status = 0 
            WHERE id = " . $id . "";

            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Sms Template details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete Sms Template details, please try again later";
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
    public function SmsTemplateactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_sms_templates WHERE id = $id AND status=1";

            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Sms Template ID does not exist",
                    ),
                );
            }
            $ActiveQuery = "UPDATE cmp_sms_templates SET active_status = 1 WHERE status = 1 AND id = $id";

            if ($db->query($ActiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Sms Template activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate Sms Template, please try again later.";
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

    public function SmsTemplateDeactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }
            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_sms_templates WHERE id = $id AND active_status=1 AND status=1";
            // print_r($checkIdQuery);exit;

            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                $statusCode = "400";
                $statusMessage = "Sms Template ID does not exist.";
                return array(
                    "apiStatus" => array(
                        "code" => $statusCode,
                        "message" => $statusMessage,
                    ),
                );
            }
            $deactiveQuery = "UPDATE cmp_sms_templates SET active_status = 0 WHERE status = 1 AND id = $id";

            if ($db->query($deactiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Sms Template Deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to Deactivate Sms Template, please try again later.";
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



    // public function getStoredropdown($data, $loginData)
    // {
    //     try {
    //         $StoreDrop = array();
    //         $db = $this->dbConnect();

    //         // Get user_id from loginData
    //         $userId = $loginData['user_id'];

    //         // Query to get store details based on user_id -> vendor_id -> store_id
    //         $queryService = "SELECT cs.id, cs.store_name, cs.active_status
    //                      FROM cmp_store cs
    //                      INNER JOIN cmp_vendor_store_mapping cvsm ON cs.id = cvsm.store_id
    //                      INNER JOIN cmp_vendor_user_mapping cvum ON cvsm.vendor_id = cvum.vendor_id
    //                      WHERE cs.status = 1 
    //                      AND cs.active_status = 1
    //                      AND cvum.user_id = $userId"; // Directly using user_id

    //         $result = $db->query($queryService);

    //         if (!$result) {
    //             throw new Exception("Database Query Failed: " . $db->error);
    //         }

    //         $row_cnt = mysqli_num_rows($result);

    //         while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    //             $StoreDrop[] = $row;
    //         }


    //         $responseArray = array(
    //             "totalRecordCount" => $row_cnt,
    //             "StoreDataDropDown" => $StoreDrop,
    //         );

    //         return array(
    //             "apiStatus" => array(
    //                 "code" => "200",
    //                 "message" => "Store Dropdown details fetched successfully",
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
