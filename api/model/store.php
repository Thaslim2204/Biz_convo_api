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

class STOREMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getStore($data, $loginData);
                } elseif ($urlParam[1] === 'storedropdown') {
                    $result = $this->getStoredropdown($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'exportstoretoexcel') {
                    $result = $this->exportStoreToExcel($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'exportisheader') {
                    $result = $this->isheaderonly($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "active") {
                    $result = $this->useractive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "deactive") {
                    $result = $this->userdeactive($data, $loginData);
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
                    $result = $this->createstore($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    $result = $this->getStoredetails($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'importstorefromexcel') {
                    $result = $this->importStoreFromExcel($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    $result = $this->updatestore($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    $result = $this->deleteStore($data);
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

    public function getStoredetails($data, $loginData)
    {
        try {
            $responseArray = ''; // Initializing response variable
            $db = $this->dbConnect();
            $recordCount = $this->getTotalCount($loginData);

            // Check if pageIndex and dataLength are not empty
            if ($data['pageIndex'] === "") {
                throw new Exception("PageIndex should not be empty!");
            }
            if ($data['dataLength'] == "") {
                throw new Exception("dataLength should not be empty!");
            }

            $start_index = $data['pageIndex'] * $data['dataLength'];
            $end_index = $data['dataLength'];

            // Query to fetch vendors and their contact persons0
            $queryService = "SELECT cs.id, cs.uid, cs.store_name, cs.address_line1, cs.address_line2, cs.dist, cs.state, cs.pincode, cs.phone, cs.email, 
                        cs.active_status, cs.created_by, cs.created_date, cs.updated_by, cs.updated_date 
                 FROM cmp_store cs
                 INNER JOIN cmp_vendor_store_mapping cvsm ON cs.id = cvsm.store_id
                 INNER JOIN cmp_vendor_user_mapping cvum ON cvsm.vendor_id = cvum.vendor_id
                 WHERE cs.status = 1 AND cvum.user_id = " . $loginData['user_id'] . "  
                 ORDER BY cs.id DESC 
                 LIMIT $start_index, $end_index";

            // print_r($queryService);
            // exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);

            $Store = array(); // Initialize array to hold Store data
            if ($row_cnt > 0) {
                while ($row = $result->fetch_assoc()) {
                    $Store[] = array(
                        "storeId" => $row['id'],
                        "storeUid" => $row['uid'],
                        "storeName" => $row['store_name'],
                        "storeAddressLine1" => $row['address_line1'],
                        "storeAddressLine2" => $row['address_line2'],
                        "storeDist" => $row['dist'],
                        "storeState" => $row['state'],
                        "storePincode" => $row['pincode'],
                        "storePhone" => $row['phone'],
                        "storeEmail" => $row['email'],
                        "storeStatus" => $row['active_status'],
                        "createdBy" => $row['created_by'],
                        "createdDate" => $row['created_date'],
                        "updatedBy" => $row['updated_by'],
                        "updatedDate" => $row['updated_date'],


                    );
                }
            }

            // Construct the final response array
            $responseArray = array(
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $recordCount,
                'StoreData' => array_values($Store), // Reset array keys
            );

            // Check if Store data exists
            if (!empty($Store)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Store details fetched successfully",
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
    public function getStore($data, $loginData)
    {
        try {
            $id = $data[2];
            if (empty($id)) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $sql = "SELECT cs.id, cs.uid, cs.store_name, cs.address_line1, cs.address_line2, cs.dist, cs.state, cs.pincode, cs.phone, cs.email, 
                        cs.active_status, cs.created_by, cs.created_date, cs.updated_by, cs.updated_date 
                 FROM cmp_store cs
                 INNER JOIN cmp_vendor_store_mapping cvsm ON cs.id = cvsm.store_id
                 INNER JOIN cmp_vendor_user_mapping cvum ON cvsm.vendor_id = cvum.vendor_id  
                    WHERE cs.id = $id AND cs.status = 1 AND cvum.user_id = " . $loginData['user_id'] . " 
                      ";

            $result = $db->query($sql);

            // Check if Store exists
            if ($result->num_rows > 0) {
                $Store = array();
                $contactPersons = array();

                while ($row = $result->fetch_assoc()) {
                    $Store = array(
                        "storeId" => $row['id'],
                        "storeUid" => $row['uid'],
                        "storeName" => $row['store_name'],
                        "storeAddressLine1" => $row['address_line1'],
                        "storeAddressLine2" => $row['address_line2'],
                        "storeDist" => $row['dist'],
                        "storeState" => $row['state'],
                        "storePincode" => $row['pincode'],
                        "storePhone" => $row['phone'],
                        "storeEmail" => $row['email'],
                        "storeStatus" => $row['active_status'],
                        "createdBy" => $row['created_by'],
                        "createdDate" => $row['created_date'],
                        "updatedBy" => $row['updated_by'],
                        "updatedDate" => $row['updated_date'],
                    );
                }
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Store detail fetched successfully",
                    ),
                    "result" => $Store
                );
            } else {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "Store not found",
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
    public function createstore($data, $loginData)
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
                "StoreName" => $data['storeName'],
                "Address"      => $data['addressLine1'],
                "Phone"        => $data['phone'],
                "Email ID"     => $data['email']

            );

            $this->validateInputDetails($validationData);
            //Get the Store id from the login data
            $user_id = $loginData['user_id'];
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id";
            $result = $db->query($sql);
            $vendor_id = $result->fetch_assoc()['vendor_id'];
            // print_r($vendor_id);exit;

            // Check if the user already exists
            $sql = "SELECT id FROM cmp_store WHERE store_name = '" . $data['storeName'] . "' AND status = 1 AND created_by = " . $loginData['user_id'] . "";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
                throw new Exception("Store already exist");
            }

            $uid = bin2hex(random_bytes(8));

            // Insert into cmp_store
            $insertStoreQuery = "INSERT INTO cmp_store (uid, store_name, address_line1,address_line2,dist,state,pincode, phone, email, created_by)
                  VALUES ('" . $uid . "', '" . $data['storeName'] . "', '" . $data['addressLine1'] . "', '" . $data['addressLine2'] . "', '" . $data['district'] . "', '" . $data['state'] . "', '" . $data['pincode'] . "', '" . $data['phone'] . "', '" . $data['email'] . "', '" . $loginData['user_id'] . "')";
            // print_r($insertStoreQuery);exit;
            if ($db->query($insertStoreQuery) === true) {
                $store_id = $db->insert_id;

                // Insert into cmp_vendor_store
                $insertVendorStoreQuery = "INSERT INTO cmp_vendor_store_mapping (vendor_id, store_id, created_by)
       VALUES ('$vendor_id', '$store_id', '$user_id')";

                if ($db->query($insertVendorStoreQuery) !== true) {
                    throw new Exception("Failed to insert into cmp_vendor_store: " . $db->error);
                }

                $db->close();

                $resultArray = array(
                    "apiStatus" => array(
                        "code"    => "200",
                        "message" => "Store details successfully created.",
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


    private function updatestore($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            // if (!in_array($loginData['role_name'], ['super_admin', 'vendor_super_admin'])) {
            //     throw new Exception("Permission denied. Only Admin and Super Admin can Update Details.");
            // }

            // Check if the Store ID exists and is active
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_store WHERE id = '{$data['id']}' AND status = 1";
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            if ($rowCount == 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "Store does not exist",
                    ],
                ];
            }
            // Check if username or email already exists
            $checkUserQuery = "SELECT COUNT(*) AS count FROM cmp_store WHERE (store_name = '{$data['storeName']}' OR email = '{$data['email']}') AND id != '{$data['id']}'";
            //  print_r($checkUserQuery);exit;
            $userResult = $db->query($checkUserQuery);
            $userCount = $userResult->fetch_assoc()['count'];

            if ($userCount > 0) {
                $db->close();
                return [
                    "apiStatus" => [
                        "code" => "400",
                        "message" => "Username or email already exists",
                    ],
                ];
            }


            $dateNow = date("Y-m-d H:i:s");
            $vendorUpdated = false;
            $userUpdated = false;

            // If Store details are provided, validate and update them
            if (isset($data['storeName']) || isset($data['email']) || isset($data['addressLine1']) || isset($data['phone'])) {
                $validationData = array(
                    "id" => $data['id'],
                    "Store Name" => $data['storeName'],
                    // "Email" => $data['email'],
                    "Address" => $data['addressLine1'],
                    "Phone" => $data['phone']
                );
                $this->validateInputDetails($validationData);

                $updateVendorQuery = "UPDATE cmp_store SET 
                        store_name = '{$data['storeName']}',
                        address_line1 = '{$data['addressLine1']}',
                        address_line2 = '{$data['addressLine2']}',
                        dist = '{$data['district']}',
                        state = '{$data['state']}',
                        pincode = '{$data['pincode']}',
                        phone = '{$data['phone']}',
                        email = '{$data['email']}',";

                if (isset($data['activeStatus'])) {
                    $updateVendorQuery .= " active_status = '{$data['activeStatus']}',";
                }

                $updateVendorQuery .= " updated_by = '{$loginData['user_id']}',
                        updated_date = '{$dateNow}'
                        WHERE id = '{$data['id']}' AND status = 1";
                // print_r($updateVendorQuery);exit;

                if ($db->query($updateVendorQuery) === false) {
                    $db->close();
                    return [
                        "apiStatus" => [
                            "code" => "500",
                            "message" => "Unable to update Store details, please try again later",
                        ],
                    ];
                }
                $vendorUpdated = true;
            }


            $db->close();

            // Construct the response message
            $message = "Store details updated successfully";
            if ($vendorUpdated === false) {
                $message = "No changes made to Store details";
            }



            return [
                "apiStatus" => [
                    "code" => "200",
                    "message" => $message,
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








    private function deleteStore($data)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();

            // Check if the ID is provided and valid
            if (empty($id)) {
                throw new Exception("Invalid. Please enter your ID.");
            }

            // Check if store exists and is active
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_store WHERE id = $id AND status = 1";
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];
// print_r($checkIdQuery);exit;
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Store does not exist",
                    ),
                );
            }

            // Begin Transaction
            $db->begin_transaction();

            //  Mark store as deleted
            $deleteStoreQuery = "UPDATE cmp_store SET status = 0 WHERE id = $id";
            if (!$db->query($deleteStoreQuery)) {
                throw new Exception("Failed to update store status.");
            }

            // Update cmp_vendor_store_staff_mapping status = 0 for this store
            $updateStaffMappingQuery = "UPDATE cmp_vendor_store_staff_mapping SET status = 0 WHERE store_id = $id";
            if (!$db->query($updateStaffMappingQuery)) {
                throw new Exception("Failed to update vendor-store-staff mapping.");
            }

            //  Update cmp_users status = 0 for all users linked to this store
            // assuming cmp_vendor_store_staff_mapping has a user_id field
            $updateUserStatusQuery = "
            UPDATE cmp_users 
            SET status = 0 
            WHERE id IN (
                SELECT staff_id 
                FROM cmp_vendor_store_staff_mapping 
                WHERE store_id = $id
            )
        ";
            if (!$db->query($updateUserStatusQuery)) {
                throw new Exception("Failed to update user status.");
            }

            // Commit transaction
            $db->commit();
            $db->close();

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Store  data deleted successfully",
                ),
            );
        } catch (Exception $e) {
            // Rollback on error
            if ($db && $db->connect_errno == 0) {
                $db->rollback();
                $db->close();
            }
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }


    public function useractive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_store WHERE id = $id AND status=1";

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
            $ActiveQuery = "UPDATE cmp_store cs
                        JOIN cmp_vendor_store_staff_mapping cvsm ON cs.id = cvsm.store_id
                        JOIN cmp_users cu ON cvsm.staff_id = cu.id
                        SET 
                            cs.active_status = 1,
                            cvsm.mapping_status = 1,
                            cu.active_status = 1
                        WHERE 
                            cs.status = 1 
                            AND cvsm.status = 1
                            AND cs.id = $id;
                    ";
            // print_r(    $ActiveQuery);exit;
            if ($db->query($ActiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Store activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate Store, please try again later.";
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

    public function userdeactive($data, $loginData)
    {
        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }
            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_store WHERE id = $id AND active_status=1 AND status=1";
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
            $deactiveQuery = "UPDATE cmp_store cs
                        JOIN cmp_vendor_store_staff_mapping cvsm ON cs.id = cvsm.store_id
                        JOIN cmp_users cu ON cvsm.staff_id = cu.id
                        SET 
                            cs.active_status = 0,
                            cvsm.mapping_status = 0,
                            cu.active_status = 0
                        WHERE 
                            cs.status = 1 
                            AND cvsm.status = 1
                            AND cs.id = $id
                    ";

            if ($db->query($deactiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Store Deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to Deactivate Store, please try again later.";
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


    public function importStoreFromExcel($data, $loginData)
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
            $fileType = $file['type'];
            $fileSize = $file['size'];

            // Allowed file types
            $allowedTypes = [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // XLSX
                'application/vnd.ms-excel' // XLS
            ];

            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Invalid file type. Please upload an Excel file.");
            }

            // Check file size (Max: 5MB)
            if ($fileSize > 5 * 1024 * 1024) {
                throw new Exception("File size exceeds 5MB limit.");
            }

            // Read Excel file
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (empty($rows) || count($rows) < 2) {
                throw new Exception("Excel file is empty or invalid format.");
            }

            unset($rows[1]); // Remove header row

            // Get the Vendor ID from the login data
            $user_id = $loginData['user_id'];
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = '$user_id'";
            $result = $db->query($sql);

            if (!$result || $result->num_rows === 0) {
                throw new Exception("Vendor ID not found for user ID: $user_id");
            }

            $vendorRow = $result->fetch_assoc();
            $vendor_id = $vendorRow['vendor_id'];

            // Process Excel Rows
            foreach ($rows as $row) {
                $storeName = trim($row['B']);
                $address1   = trim($row['C']);
                $address2   = trim($row['D']);
                $dist   = trim($row['E']);
                $state   = trim($row['F']);
                $pincode   = trim($row['G']);
                $phone     = trim($row['H']);
                $email     = trim($row['I']);
                $uid = bin2hex(random_bytes(8));

                if (empty($storeName)) {
                    throw new Exception("Invalid data found in row: Store Name is missing.");
                }

                if (empty($address1)) {
                    throw new Exception("Invalid data found in row: Address is missing.");
                }

                if (empty($phone)) {
                    throw new Exception("Invalid data found in row: Mobile number is missing.");
                }

                if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                    throw new Exception("Invalid data found in row: Mobile number must contain only digits and be 10 to 15 digits long.");
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Invalid data found in row: Email address is invalid.");
                }


                // Check if Store Exists
                $storeQuery = "SELECT id FROM cmp_store WHERE store_name = '$storeName' LIMIT 1";
                $storeResult = $db->query($storeQuery);
                // print_r($storeQuery);exit;

                if ($storeResult->num_rows > 0) {
                    $storeRow = $storeResult->fetch_assoc();
                    $store_id = $storeRow['id'];

                    // UPDATE existing store with new values
                    $updateStoreQuery = "UPDATE cmp_store SET 
                            address_line1 = '$address1',
                            address_line2 = '$address2',
                            dist = '$dist',
                            state = '$state',
                            pincode = '$pincode',
                            phone = '$phone',
                            email = '$email',
                            updated_by = '$user_id',
                            updated_date = NOW()
                         WHERE id = '$store_id'";
                    if (!$db->query($updateStoreQuery)) {
                        throw new Exception("Error updating store: " . $db->error);
                    }
                } else {
                    // INSERT new store
                    $insertStoreQuery = "INSERT INTO cmp_store (uid,store_name, address_line1,address_line2,dist,state,pincode, phone, email, created_by, status) 
                         VALUES ('$uid','$storeName', '$address1', '$address2', '$dist', '$state', '$pincode', '$phone', '$email', '$user_id', 1)";
                    if (!$db->query($insertStoreQuery)) {
                        throw new Exception("Error inserting store: " . $db->error);
                    }
                    $store_id = $db->insert_id;
                }


                // Check if Store is Already Mapped to Vendor
                $mappingQuery = "SELECT id FROM cmp_vendor_store_mapping WHERE vendor_id = '$vendor_id' AND store_id = '$store_id' LIMIT 1";
                $mappingResult = $db->query($mappingQuery);

                if ($mappingResult->num_rows === 0) {
                    // Insert Mapping into cmp_vendor_store_mapping
                    $insertMappingQuery = "INSERT INTO cmp_vendor_store_mapping (vendor_id, store_id) VALUES ('$vendor_id', '$store_id')";
                    if (!$db->query($insertMappingQuery)) {
                        throw new Exception("Error mapping store to vendor: " . $db->error);
                    }
                }
            }

            return ["apiStatus" => ["code" => "200", "message" => "Excel file uploaded and processed successfully."]];
        } catch (Exception $e) {
            return ["apiStatus" => ["code" => "401", "message" => $e->getMessage()]];
        }
    }





    //export the data to excel 

    public function exportStoreToExcel($data, $loginData)
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
            $headers = ['S.No',  'Store Name', 'Address Line 1', 'Address Line 2', 'District', 'State', 'Pincode',  'Phone', 'Email'];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true); // Make text bold
                $sheet->getStyle($column . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Center align
                $sheet->getColumnDimension($column)->setAutoSize(true); // Auto-adjust column width
                $column++;
            }
            // Fetch staff details
            $query = "SELECT*
             FROM cmp_store c 
                  WHERE status = 1  AND id IN (SELECT store_id FROM cmp_vendor_store_mapping WHERE vendor_id = $vendor_id)";
            //  print_r($query);exit;
            $result = $db->query($query);

            if ($result->num_rows === 0) {
                throw new Exception("No Contact data found.");
            }

            $rowIndex = 2; // Start from row 2 (row 1 is headers)
            $sno = 1; // Initialize serial number
            while ($row = $result->fetch_assoc()) {
                $column = 'A';
                $sheet->setCellValue($column++ . $rowIndex, $sno++);
                $sheet->setCellValue($column++ . $rowIndex, $row['store_name']);
                $sheet->setCellValue($column++ . $rowIndex, $row['address_line1']);
                $sheet->setCellValue($column++ . $rowIndex, $row['address_line2']);
                $sheet->setCellValue($column++ . $rowIndex, $row['dist']);
                $sheet->setCellValue($column++ . $rowIndex, $row['state']);
                $sheet->setCellValue($column++ . $rowIndex, $row['pincode']);
                $sheet->setCellValue($column++ . $rowIndex, $row['phone']);
                $sheet->setCellValue($column++ . $rowIndex, $row['email']);
                $sheet->getStyle('A' . $rowIndex . ':' . $column . $rowIndex)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Center align
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

            // Set column headers
            $headers = ['S.No', 'Store Name', 'Address Line 1', 'Address Line 2', 'District', 'State', 'Pincode', 'Phone', 'Email'];
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
            header('Content-Disposition: attachment; filename="Store_Data_' . date('Ymd_His') . '.xlsx"');
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

    private function getTotalCount($loginData)
    {
        try {
            $db = $this->dbConnect();
            $sql = "SELECT COUNT(cs.id) as totalStores
        FROM cmp_store cs
        INNER JOIN cmp_vendor_store_mapping cvsm ON cs.id = cvsm.store_id
        INNER JOIN cmp_vendor_user_mapping cvum ON cvsm.vendor_id = cvum.vendor_id
        WHERE cs.status = 1 AND cvum.user_id = " . $loginData['user_id'];


            $result = $db->query($sql);
            $row = $result->fetch_assoc();

            return $row['totalStores'];
        } catch (Exception $e) {
            return array(
                "result" => "401",
                "message" => $e->getMessage(),
            );
        }
    }

    public function getStoredropdown($data, $loginData)
    {
        try {
            $StoreDrop = array();
            $db = $this->dbConnect();

            // Get user_id from loginData
            $userId = $loginData['user_id'];

            // Query to get store details based on user_id -> vendor_id -> store_id
            $queryService = "SELECT cs.id, cs.store_name, cs.active_status
                         FROM cmp_store cs
                         INNER JOIN cmp_vendor_store_mapping cvsm ON cs.id = cvsm.store_id
                         INNER JOIN cmp_vendor_user_mapping cvum ON cvsm.vendor_id = cvum.vendor_id
                         WHERE cs.status = 1 
                         AND cs.active_status = 1
                         AND cvum.user_id = $userId"; // Directly using user_id

            $result = $db->query($queryService);

            if (!$result) {
                throw new Exception("Database Query Failed: " . $db->error);
            }

            $row_cnt = mysqli_num_rows($result);

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $StoreDrop[] = $row;
            }


            $responseArray = array(
                "totalRecordCount" => $row_cnt,
                "StoreDataDropDown" => $StoreDrop,
            );

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Store Dropdown details fetched successfully",
                ),
                "result" => $responseArray,
            );
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => "Error: " . $e->getMessage(),
                ),
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
