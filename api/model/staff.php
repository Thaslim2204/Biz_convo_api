<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";
// require_once "model/register.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../../vendor/autoload.php';


class STAFFMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getstaff($data, $loginData);
                } elseif ($urlParam[1] == "staffactive") {
                    $result = $this->useractive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "staffdeactive") {
                    $result = $this->userdeactive($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'exportstafftoexcel') {
                    $result = $this->exportStaffToExcel($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'exportisheader') {
                    $result = $this->isheaderonly($data, $loginData);
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
                    $result = $this->createstaff($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    $result = $this->getStaffdetails($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'importstafffromexcel') {
                    $result = $this->importStaffFromExcel($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    $result = $this->updatestaff($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    $result = $this->deleteStaff($data);
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

    public function getStaffdetails($data, $loginData)
    {
        try {
            // Validate input parameters
            if ($data['pageIndex'] === "") {
                throw new Exception("PageIndex should not be empty!");
            }
            if ($data['dataLength'] == "") {
                throw new Exception("dataLength should not be empty!");
            }

            $db = $this->dbConnect();
            $recordCount = $this->getTotalCount($loginData);

            $start_index = intval($data['pageIndex']) * intval($data['dataLength']);
            $end_index = intval($data['dataLength']);
            //  print_r($end_index);exit;

            $queryService = "SELECT 
                                 u.id, u.uid, u.first_name, u.last_name, u.username, u.email, u.mobile, 
                                 u.created_date,u.status,u.active_status, r.role_id, s.store_id 
                             FROM cmp_users u
                             JOIN cmp_user_role_mapping r ON u.id = r.user_id
                             JOIN cmp_vendor_store_staff_mapping s ON u.id = s.staff_id
                             WHERE u.created_by = " . $loginData['user_id'] . " AND r.role_id = 4 AND u.status = 1
                             ORDER BY u.id DESC 
                             LIMIT $start_index, $end_index";


            $result = $db->query($queryService);

            if ($result->num_rows > 0) {
                $staffList = [];
                while ($row = $result->fetch_assoc()) {
                    // Fetch Role Details
                    $roleSql = "SELECT role_name FROM cmp_mst_role WHERE role_id = " . intval($row['role_id']);
                    $roleResult = $db->query($roleSql);
                    $roleData = $roleResult->fetch_assoc();

                    // Fetch Store Details
                    $storeSql = "SELECT store_name, address_line1,address_line2,dist,state,pincode, phone FROM cmp_store WHERE id = " . intval($row['store_id']) . " AND status = 1 AND created_by = " . $loginData['user_id'];
                    $storeResult = $db->query($storeSql);
                    $storeData = $storeResult->fetch_assoc();

                    // Fetch Privileges and Modules
                    $privilegeSql = "SELECT 
    p.id AS previlegeID,
    p.priv_name AS previlege,
    m.id AS moduleId,
    m.name AS moduleName,
     IFNULL(GROUP_CONCAT(
                CASE WHEN pm.pre_create = 1 THEN 'create,' ELSE '' END,
                CASE WHEN pm.pre_read = 1 THEN 'read,' ELSE '' END,
                CASE WHEN pm.pre_update = 1 THEN 'update,' ELSE '' END,
                CASE WHEN pm.pre_delete = 1 THEN 'delete' ELSE '' END
                SEPARATOR ','
            ), '') AS permissions 
FROM cmp_privilege p
JOIN cmp_user_privilege_mapping up ON p.id = up.privilege_id
JOIN cmp_privilege_module_permission_mapping pm ON p.id = pm.priv_id
JOIN cmp_mst_module m ON pm.mod_id = m.id
                                      WHERE up.user_id = " . intval($row['id']) . "  AND up.status = 1 AND pm.status = 1
  AND up.active_status = 1 
  AND (pm.pre_create = 1 OR pm.pre_update = 1 OR pm.pre_read = 1 OR pm.pre_delete = 1)
GROUP BY p.id, p.priv_name, m.id, m.name";
                    // print_r($privilegeSql); exit;

                    $privilegeResult = $db->query($privilegeSql);

                    $privilegesMap = [];
                    while ($privRow = $privilegeResult->fetch_assoc()) {
                        $privilegesMap[] = [
                            "privilegeID" => $privRow['previlegeID'],
                            "privilege" => $privRow['previlege'],
                            "moduleId" => $privRow['moduleId'],
                            "moduleName" => $privRow['moduleName'],
                            "permissions" => explode(',', trim($privRow['permissions'], ','))
                        ];
                    }

                    $staffList[] = [
                        "id" => $row['id'],
                        "uid" => $row['uid'],
                        "first_name" => $row['first_name'],
                        "last_name" => $row['last_name'],
                        "user_name" => $row['username'],
                        "email" => $row['email'],
                        "activeStatus" => $row['active_status'],
                        "mobile" => $row['mobile'],
                        "created_date" => $row['created_date'],
                        "status" => $row['status'],
                        "roleDetails" => [
                            "role_id" => $row['role_id'],
                            "role_name" => $roleData['role_name'] ?? "Unknown"
                        ],
                        "storeDetails" => [
                            "store_id" => $row['store_id'],
                            "store_name" => $storeData['store_name'] ?? "Unknown",
                            "store_address" => $storeData['address'] ?? "Unknown",
                            "store_phone" => $storeData['phone'] ?? "Unknown"
                        ],
                        "privilegeDetails" => array_values($privilegesMap)
                    ];
                }

                $response = [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Staff details fetched successfully"
                    ],
                    "staffDetails" => [
                        "pageIndex" => $data['pageIndex'],
                        "dataLength" => $data['dataLength'],
                        "totalRecordCount" => $recordCount,
                        "StaffData" => $staffList
                    ]
                ];
            } else {
                $response = [
                    "apiStatus" => [
                        "code" => "404",
                        "message" => "No staff found"
                    ]
                ];
            }

            $db->close();
            return $response;
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
     * Function is to get the for particular record
     *
     * @param array $data
     * @return multitype:
     */
    public function getstaff($data, $loginData)
    {
        try {
            $id = $data[2];
            if (empty($id)) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $sql = "SELECT 
                                 u.id, u.uid, u.first_name, u.last_name, u.username, u.email, u.mobile, 
                                 u.created_date, r.role_id, s.store_id 
                             FROM cmp_users u
                             JOIN cmp_user_role_mapping r ON u.id = r.user_id
                             JOIN cmp_vendor_store_staff_mapping s ON u.id = s.staff_id
                    WHERE u.status = 1
                    AND s.created_by = " . $loginData['user_id'] . " 
                    AND r.role_id = 4
                    AND u.id = $id";

            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Fetch Role Details
                $roleSql = "SELECT role_name FROM cmp_mst_role WHERE role_id = " . $row['role_id'];
                $roleResult = $db->query($roleSql);
                $roleData = $roleResult->fetch_assoc();

                // Fetch Store Details
                $storeSql = "SELECT store_name,  address_line1,address_line2,dist,state,pincode, phone FROM cmp_store WHERE id = " . $row['store_id'];
                $storeResult = $db->query($storeSql);
                $storeData = $storeResult->fetch_assoc();

                // Fetch Privileges and Modules
                $privilegeSql = "SELECT 
    p.id AS previlegeID,
    p.priv_name AS previlege,
    m.id AS moduleId,
    m.name AS moduleName,
     IFNULL(GROUP_CONCAT(
                CASE WHEN pm.pre_create = 1 THEN 'create,' ELSE '' END,
                CASE WHEN pm.pre_read = 1 THEN 'read,' ELSE '' END,
                CASE WHEN pm.pre_update = 1 THEN 'update,' ELSE '' END,
                CASE WHEN pm.pre_delete = 1 THEN 'delete' ELSE '' END
                SEPARATOR ','
            ), '') AS permissions 
FROM cmp_privilege p
JOIN cmp_user_privilege_mapping up ON p.id = up.privilege_id
JOIN cmp_privilege_module_permission_mapping pm ON p.id = pm.priv_id
JOIN cmp_mst_module m ON pm.mod_id = m.id
                                      WHERE up.user_id = " . intval($row['id']) . "  AND up.status = 1 AND pm.status = 1
  AND up.active_status = 1 
  AND (pm.pre_create = 1 OR pm.pre_update = 1 OR pm.pre_read = 1 OR pm.pre_delete = 1)
GROUP BY p.id, p.priv_name, m.id, m.name";

                $privilegeResult = $db->query($privilegeSql);

                $privilegesMap = [];
                while ($privRow = $privilegeResult->fetch_assoc()) {
                    $privilegesMap[] = [
                        "privilegeID" => $privRow['previlegeID'],
                        "privilege" => $privRow['previlege'],
                        "moduleId" => $privRow['moduleId'],
                        "moduleName" => $privRow['moduleName'],
                        "permissions" => explode(',', trim($privRow['permissions'], ','))
                    ];
                }

                // Convert associative array to indexed array
                $privilegeDetails = array_values($privilegesMap);

                $response = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Staff detail fetched successfully"
                    ),
                    "staffDetails" => array(
                        "id" => $row['id'],
                        "uid" => $row['uid'],
                        "first_name" => $row['first_name'],
                        "last_name" => $row['last_name'],
                        "user_name" => $row['username'],
                        "email" => $row['email'],
                        "mobile" => $row['mobile'],
                        "created_date" => $row['created_date'],
                        "roleDetails" => array(
                            "role_id" => $row['role_id'],
                            "role_name" => $roleData['role_name'] ?? "Unknown"
                        ),
                        "storeDetails" => array(
                            "store_id" => $row['store_id'],
                            "store_name" => $storeData['store_name'] ?? "Unknown",
                            "store_address" => $storeData['address'] ?? "Unknown",
                            "store_phone" => $storeData['phone'] ?? "Unknown"
                        ),
                        "privilegeDetails" => $privilegeDetails
                    )
                );
            } else {
                $response = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "Staff not found"
                    )
                );
            }

            $db->close();
            return $response;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => $e->getMessage()
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
    public function createstaff($data, $loginData)
    {
        $resultArray = array();
        try {
            $db = $this->dbConnect();

            // Validate input data
            $this->validateInputDetails([
                "firstName" => $data['firstName'],
                // "lastName" => $data['lastName'],
                "username" => $data['username'],
                "email" => $data['email'],
                "password" => $data['password'],
                "confirmPassword" => $data['confirmPassword'],
                "storeId" => $data['storeId'],
                "phone" => $data['phone']
            ]);

            // Get vendor_id from loginData
            $user_id = $loginData['user_id'];
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id AND status = 1";
            $result = $db->query($sql);
            if (!$result || $result->num_rows === 0) {
                throw new Exception("Vendor ID not found for user.");
            }
            $vendor_id = $result->fetch_assoc()['vendor_id'];

            // Validate Store ID
            $storeQuery = "SELECT id, store_name FROM cmp_store WHERE id = '" . $data['storeId'] . "' AND status = 1";
            $storeResult = $db->query($storeQuery);
            if ($storeResult->num_rows === 0) {
                throw new Exception("Invalid store: " . $storeResult->fetch_assoc()['store_name']);
            }
            $store_id = $storeResult->fetch_assoc()['id'];

            // Check if user already exists
            $sql = "SELECT id FROM cmp_users WHERE (username = '" . $data['username'] . "' AND email = '" . $data['email'] . "') AND status = 1";
            $result = $db->query($sql);
            if ($result->num_rows > 0) {
                throw new Exception("User already exists.");
            }

            if ($data['password'] != $data['confirmPassword']) {
                throw new Exception("Password & Confirm Password are not correct!");
            }

            // Generate unique user ID
            $uid = bin2hex(random_bytes(8));

            // Hash password
            $hashed_password = hash('sha256', hash('sha256', $data['password']));
            $dateNow = date("Y-m-d H:i:s", strtotime('+4 hours 30 minutes'));

            // Insert into cmp_users
            $sql = "INSERT INTO cmp_users (uid, first_name, last_name, username, email, password, mobile, created_by, created_date) 
                VALUES ('$uid', '" . $data['firstName'] . "', '" . $data['lastName'] . "', '" . $data['username'] . "', '" . $data['email'] . "', '$hashed_password', '" . $data['phone'] . "', $user_id, '$dateNow')";
            if ($db->query($sql) === true) {
                $newUserId = $db->insert_id;

                // Insert into cmp_user_role_mapping
                $sql = "INSERT INTO cmp_user_role_mapping (user_id, role_id, created_by) VALUES ($newUserId, 4, $user_id)";
                $db->query($sql);

                // Insert into cmp_vendor_store_staff_mapping
                $sql = "INSERT INTO cmp_vendor_store_staff_mapping (vendor_id, store_id, staff_id, created_by) 
                    VALUES ($vendor_id, $store_id, $newUserId, $user_id)";
                $db->query($sql);

                // Insert into cmp_vendor_user_mapping
                $sql = "INSERT INTO cmp_vendor_user_mapping (user_id, vendor_id) VALUES ($newUserId, $vendor_id)";
                $db->query($sql);

                // Validate & Insert User Privileges
                foreach ($data['privilege'] as $privilege) {
                    $privilegeId = (int)$privilege['privilegeId'];
                    $privilegeName = $db->real_escape_string($privilege['privilegeName']);

                    $moduleQuery = "SELECT id, priv_name FROM cmp_privilege 
                                WHERE status = 1 
                                AND id = $privilegeId 
                                AND priv_name = '$privilegeName'";
                    $moduleResult = $db->query($moduleQuery);
                    if ($moduleResult->num_rows === 0) {
                        throw new Exception("Invalid module: " . $privilegeName);
                    }
                    $moduleData = $moduleResult->fetch_assoc();
                    $privilegeId = $moduleData['id'];

                    // Insert into cmp_user_privilege_mapping with ID
                    $sql = "INSERT INTO cmp_user_privilege_mapping (user_id, privilege_id, created_by) 
                        VALUES ($newUserId, $privilegeId, $user_id)";
                    $db->query($sql);
                }

                $resultArray = array(
                    "apiStatus" => array(
                        "code"    => "200",
                        "message" => "User successfully created.",
                    ),
                );
            } else {
                throw new Exception("Error inserting user: " . $db->error);
            }
        } catch (Exception $e) {
            $resultArray = array(
                "apiStatus" => array(
                    "code"    => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
        return $resultArray;
    }
    public function updatestaff($data, $loginData)
    {
        $resultArray = array();
        try {
            $db = $this->dbConnect();

            // Validate input data
            $this->validateInputDetails([
                "userId" => $data['userId'],
                "firstName" => $data['firstName'],
                // "lastName" => $data['lastName'],
                "username" => $data['username'],
                "email" => $data['email'],
                "storeId" => $data['storeId'],
                "phone" => $data['phone']
            ]);

            // Get vendor_id from loginData
            $user_id = $loginData['user_id'];
            $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id AND status = 1";
            $result = $db->query($sql);
            if (!$result || $result->num_rows === 0) {
                throw new Exception("Vendor ID not found for user.");
            }
            $vendor_id = $result->fetch_assoc()['vendor_id'];

            // Validate Store ID
            $storeQuery = "SELECT id FROM cmp_store WHERE id = '" . $data['storeId'] . "' AND status = 1";
            $storeResult = $db->query($storeQuery);
            if ($storeResult->num_rows === 0) {
                throw new Exception("Invalid store: " . $data['storeId']);
            }
            $store_id = $storeResult->fetch_assoc()['id'];

            // Check if user exists
            $sql = "SELECT id, password FROM cmp_users WHERE id = '" . $data['userId'] . "' AND status = 1";
            $result = $db->query($sql);
            if ($result->num_rows === 0) {
                throw new Exception("User not found.");
            }
            $userData = $result->fetch_assoc();
            $existingPassword = $userData['password'];

            // Handle password update
            if (!empty($data['password']) && !empty($data['confirmPassword'])) {
                if ($data['password'] !== $data['confirmPassword']) {
                    throw new Exception("Password & Confirm Password do not match!");
                }
                $hashed_password = hash('sha256', hash('sha256', $data['password']));
            } else {
                $hashed_password = $existingPassword; // Keep the existing password
            }

            $dateNow = date("Y-m-d H:i:s", strtotime('+4 hours 30 minutes'));

            // Update user details including password
            $updateUserQuery = "UPDATE cmp_users 
                                SET first_name = '" . $data['firstName'] . "', 
                                    last_name = '" . $data['lastName'] . "', 
                                    username = '" . $data['username'] . "', 
                                    email = '" . $data['email'] . "', 
                                    mobile = '" . $data['phone'] . "', 
                                    password = '$hashed_password',
                                    updated_by = $user_id, 
                                    updated_date = '$dateNow'
                                WHERE id = '" . $data['userId'] . "'";
            $db->query($updateUserQuery);

            // Update store mapping
            $updateStoreMappingQuery = "UPDATE cmp_vendor_store_staff_mapping 
                                        SET store_id = $store_id, 
                                            updated_by = $user_id , updated_date = '$dateNow'
                                        WHERE staff_id = '" . $data['userId'] . "'";
            $db->query($updateStoreMappingQuery);

            // Fetch existing privileges
            $existingPrivileges = [];
            $privilegeQuery = "SELECT privilege_id FROM cmp_user_privilege_mapping WHERE user_id = '" . $data['userId'] . "' AND status = 1";
            $privilegeResult = $db->query($privilegeQuery);
            while ($row = $privilegeResult->fetch_assoc()) {
                $existingPrivileges[] = $row['privilege_id'];
            }

            $newPrivileges = array_column($data['privilege'], 'privilegeId');
            $privilegesToDeactivate = array_diff($existingPrivileges, $newPrivileges);
            $privilegesToInsert = array_diff($newPrivileges, $existingPrivileges);

            // Deactivate only removed privileges
            if (!empty($privilegesToDeactivate)) {
                $deactivateQuery = "UPDATE cmp_user_privilege_mapping 
                                SET status = 0, updated_by = $user_id, updated_date = '$dateNow' 
                                WHERE user_id = '" . $data['userId'] . "' 
                                AND privilege_id IN (" . implode(",", $privilegesToDeactivate) . ")";
                $db->query($deactivateQuery);
            }

            // Insert new privileges
            foreach ($data['privilege'] as $privilege) {
                $privilegeId = (int)$privilege['privilegeId'];
                if (in_array($privilegeId, $privilegesToInsert)) {
                    $insertPrivilegeQuery = "INSERT INTO cmp_user_privilege_mapping (user_id, privilege_id, status, created_by, created_date) 
                                         VALUES ('" . $data['userId'] . "', $privilegeId, 1, $user_id, '$dateNow')";
                    $db->query($insertPrivilegeQuery);
                } else {
                    // Update privilege if it already exists
                    $updatePrivilegeQuery = "UPDATE cmp_user_privilege_mapping 
                                         SET status = 1, updated_by = $user_id, updated_date = '$dateNow' 
                                         WHERE user_id = '" . $data['userId'] . "' 
                                         AND privilege_id = $privilegeId";
                    $db->query($updatePrivilegeQuery);
                }
            }

            $resultArray = array(
                "apiStatus" => array(
                    "code"    => "200",
                    "message" => "User successfully updated.",
                ),
            );
        } catch (Exception $e) {
            $resultArray = array(
                "apiStatus" => array(
                    "code"    => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
        return $resultArray;
    }



    // public function updateStaff($data, $loginData)
    // {
    //     $resultArray = array();
    //     try {
    //         $db = $this->dbConnect();

    //         // Validate input data
    //         $this->validateInputDetails([
    //             "id" => $data['id'],
    //             "firstName" => $data['firstName'],
    //             "lastName" => $data['lastName'],
    //             "username" => $data['username'],
    //             "email" => $data['email'],
    //             "storeId" => $data['storeId'],
    //             "phone" => $data['phone']
    //         ]);

    //         $user_id = $loginData['user_id'];
    //         $staff_id = $data['id'];

    //         // Check if staff exists
    //         $sql = "SELECT id FROM cmp_users WHERE id = $staff_id AND status = 1";
    //         $result = $db->query($sql);
    //         if ($result->num_rows === 0) {
    //             throw new Exception("Staff member not found.");
    //         }

    //         // Validate Store ID
    //         $storeQuery = "SELECT id FROM cmp_store WHERE id = '" . $data['storeId'] . "' AND status = 1";
    //         $storeResult = $db->query($storeQuery);
    //         if ($storeResult->num_rows === 0) {
    //             throw new Exception("Invalid store.");
    //         }
    //         $store_id = $storeResult->fetch_assoc()['id'];

    //         if (!empty($data['password']) && $data['password'] !== $data['confirmPassword']) {
    //             throw new Exception("Password & Confirm Password do not match!");
    //         }

    //         $passwordUpdate = '';
    //         if (!empty($data['password'])) {
    //             $hashed_password = hash('sha256', hash('sha256', $data['password']));
    //             $passwordUpdate = ", password = '$hashed_password'";
    //         }

    //         // Update cmp_users table
    //         $updateQuery = "UPDATE cmp_users SET first_name = '" . $data['firstName'] . "', last_name = '" . $data['lastName'] . "', username = '" . $data['username'] . "', email = '" . $data['email'] . "', mobile = '" . $data['phone'] . "'" . $passwordUpdate . ",updated_by ='" . $loginData['used_id'] . "' WHERE id = $staff_id";
    //         $db->query($updateQuery);

    //         // Update cmp_vendor_store_staff_mapping
    //         $storeUpdateQuery = "UPDATE cmp_vendor_store_staff_mapping SET store_id = $store_id WHERE staff_id = $staff_id";
    //         $db->query($storeUpdateQuery);

    //         // Set all existing privileges to inactive before updating/inserting new ones
    //         $updatePrivileges = "UPDATE cmp_user_privilege_mapping SET status = 0, mapping_status = 0, active_status = 0 WHERE user_id = $staff_id";
    //         $db->query($updatePrivileges);

    //         // Process privileges
    //         foreach ($data['privilegesDetails'] as $privilege) {
    //             $moduleId = (int)$privilege['module']['moduleId'];
    //             $moduleName = $db->real_escape_string($privilege['module']['moduleName']);

    //             // Validate module
    //             $moduleQuery = "SELECT id FROM cmp_mst_module WHERE status = 1 AND id = $moduleId AND name = '$moduleName'";
    //             $moduleResult = $db->query($moduleQuery);
    //             if ($moduleResult->num_rows === 0) {
    //                 throw new Exception("Invalid module: " . $moduleName);
    //             }
    //             $module_id = $moduleResult->fetch_assoc()['id'];

    //             foreach ($privilege['privileges'] as $priv) {
    //                 $privilege_name = strtolower($priv);

    //                 // Validate privilege
    //                 $privilegeQuery = "SELECT id FROM cmp_mst_privilege WHERE status = 1 AND name = '$privilege_name'";
    //                 $privilegeResult = $db->query($privilegeQuery);
    //                 if ($privilegeResult->num_rows === 0) {
    //                     throw new Exception("Invalid privilege: " . $privilege_name);
    //                 }
    //                 $privilege_id = $privilegeResult->fetch_assoc()['id'];

    //                 // Check if privilege mapping already exists
    //                 $checkPrivilegeQuery = "SELECT id FROM cmp_user_privilege_mapping WHERE user_id = $staff_id AND module_id = $module_id AND privilege_id = $privilege_id";
    //                 $checkPrivilegeResult = $db->query($checkPrivilegeQuery);

    //                 if ($checkPrivilegeResult->num_rows > 0) {
    //                     // If exists, update privilege status
    //                     $updatePrivilegeQuery = "UPDATE cmp_user_privilege_mapping SET status = 1, mapping_status = 1, active_status = 1 WHERE user_id = $staff_id AND module_id = $module_id AND privilege_id = $privilege_id";
    //                     $db->query($updatePrivilegeQuery);
    //                 } else {
    //                     // If not exists, insert new privilege mapping
    //                     $insertPrivilegeQuery = "INSERT INTO cmp_user_privilege_mapping (user_id, module_id, privilege_id, created_by, status, mapping_status, active_status) VALUES ($staff_id, $module_id, $privilege_id, $user_id, 1, 1, 1)";
    //                     $db->query($insertPrivilegeQuery);
    //                 }
    //             }
    //         }

    //         $resultArray = array(
    //             "apiStatus" => array(
    //                 "code"    => "200",
    //                 "message" => "User details updated successfully.",
    //             ),
    //         );
    //     } catch (Exception $e) {
    //         $resultArray = array(
    //             "apiStatus" => array(
    //                 "code"    => "401",
    //                 "message" => $e->getMessage(),
    //             ),
    //         );
    //     }
    //     return $resultArray;
    // }
    //import the data from excel 


    // use PhpOffice\PhpSpreadsheet\IOFactory;

    public function importStaffFromExcel($data, $loginData)
    {
        $resultArray = [];
        // print_r($_FILES);exit;
        try {
            $db = $this->dbConnect();

            // Validate file upload
            if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
                throw new Exception("No valid file uploaded.");
            }

            $file = $_FILES['file'];
            $filePath = $file['tmp_name'];
            $fileType = $file['type'];

            // Allowed file types
            $allowedTypes = [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // XLSX
                'application/vnd.ms-excel' // XLS
            ];
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Invalid file type. Please upload an Excel file.");
            }

            // Read Excel file
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (empty($rows) || count($rows) < 2) {
                throw new Exception("Excel file is empty or invalid format.");
            }

            unset($rows[1]); // Remove header row

            foreach ($rows as $row) {
                $firstName = trim($row['B']);
                $lastName = trim($row['C']);
                $username = trim($row['D']);
                $email = trim($row['E']);
                $mobile = trim($row['F']);
                $storeName = trim($row['G']);
                $privileges = trim($row['H']); // Privileges are comma-separated

                // Validate required fields
                // if (empty($firstName) || empty($email) || empty($storeName)) {
                //     throw new Exception("Missing required fields in row: " . json_encode($row));
                // }
                if (empty($firstName)) {
                    throw new Exception("First name is required in row: " . "First Name is missing");
                }
                if (empty($email)) {
                    throw new Exception("Email is required in row: " . "Email is missing");
                }
                if (empty($storeName)) {
                    throw new Exception("Store name is required in row: " . "Store Name is missing");
                }
                // Check if store exists
                $storeQuery = "SELECT id FROM cmp_store WHERE store_name = '$storeName' AND status = 1 AND created_by ='" . $loginData['user_id'] . "'";
                $storeResult = $db->query($storeQuery);
                if ($storeResult->num_rows === 0) {
                    throw new Exception("Store not found: " . $storeName);
                }
                $store_id = $storeResult->fetch_assoc()['id'];

                // Fetch vendor_id using store_id
                $vendorQuery = "SELECT vendor_id FROM cmp_vendor_store_mapping WHERE store_id = '$store_id' LIMIT 1";
                $vendorResult = $db->query($vendorQuery);
                if ($vendorResult->num_rows > 0) {
                    // $vendor_id = $vendorResult->fetch_assoc()['vendor_id'];
                } else {
                    throw new Exception("Vendor ID not found for store ID: $store_id");
                }

                // Get vendor_id from loginData
                $user_id = $loginData['user_id'];
                $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $user_id AND status = 1";
                $result = $db->query($sql);
                if (!$result || $result->num_rows === 0) {
                    throw new Exception("Vendor ID not found for user.");
                }
                $vendor_id = $result->fetch_assoc()['vendor_id'];

                // Check if staff exists
                $sql = "SELECT id FROM cmp_users WHERE email = '$email' AND status = 1 AND created_by ='" . $loginData['user_id'] . "'";
                $result = $db->query($sql);

                if ($result->num_rows === 0) {
                    $uid = bin2hex(random_bytes(8));
                    $defaultPassword = hash('sha256', hash('sha256', 'pass@123'));
                    $insertUserQuery = "INSERT INTO cmp_users (uid, first_name, last_name, username, email, mobile, password, status, created_by) 
                                    VALUES ('$uid', '$firstName', '$lastName', '$username', '$email', '$mobile', '$defaultPassword', 1, '{$loginData['user_id']}')";
                    $db->query($insertUserQuery);
                    $staff_id = $db->insert_id;
                } else {
                    $staff_id = $result->fetch_assoc()['id'];
                }


                // Insert into cmp_user_role_mapping
                $roleQuery = "INSERT INTO cmp_user_role_mapping (user_id, role_id, status, created_by)
                          VALUES ('$staff_id', '4', '1', '{$loginData['user_id']}') 
                          ON DUPLICATE KEY UPDATE role_id = '4', status = '1'";
                $db->query($roleQuery);



                // Insert into cmp_vendor_store_staff_mapping
                $storeMappingQuery = "INSERT INTO cmp_vendor_store_staff_mapping (staff_id, store_id, vendor_id,created_by) 
                                  VALUES ('$staff_id', '$store_id', '$vendor_id', '{$loginData['user_id']}') 
                                  ON DUPLICATE KEY UPDATE store_id = '$store_id', vendor_id = '$vendor_id'";
                $db->query($storeMappingQuery);

                // Insert into cmp_vendor_user_mapping
                $vendorUserQuery = "INSERT INTO cmp_vendor_user_mapping (vendor_id, user_id, status ,created_by) 
                                VALUES ('$vendor_id', '$staff_id', '1', '{$loginData['user_id']}') 
                                ON DUPLICATE KEY UPDATE status = '1'";
                $db->query($vendorUserQuery);

                // Process privileges
                $privilegesList = explode(",", $privileges);
                foreach ($privilegesList as $privilege_name) {
                    $privilege_name = trim($privilege_name);

                    $privilegeQuery = "SELECT id FROM cmp_privilege WHERE priv_name = '$privilege_name' AND status = 1";
                    $privilegeResult = $db->query($privilegeQuery);
                    if ($privilegeResult->num_rows === 0) {
                        throw new Exception("Invalid privilege: " . $privilege_name);
                    }
                    $privilege_id = $privilegeResult->fetch_assoc()['id'];

                    // Insert into cmp_user_privilege_mapping
                    $privilegeMappingQuery = "INSERT INTO cmp_user_privilege_mapping (user_id, privilege_id, created_by, status, active_status) 
                                          VALUES ($staff_id, $privilege_id, '{$loginData['user_id']}', 1, 1) 
                                          ON DUPLICATE KEY UPDATE status = 1, active_status = 1";
                    $db->query($privilegeMappingQuery);
                }
            }

            $resultArray = [
                "apiStatus" => [
                    "code"    => "200",
                    "message" => "Excel file uploaded and processed successfully."
                ]
            ];
        } catch (Exception $e) {
            $resultArray = [
                "apiStatus" => [
                    "code"    => "401",
                    "message" => $e->getMessage()
                ]
            ];
        }

        return $resultArray;
    }






    //export the data to excel 

    public function exportStaffToExcel($data, $loginData)
    {
        try {
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
            $headers = ['S.No', 'First Name', 'Last Name', 'Username', 'Email', 'Mobile', 'Store Name'];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true);
                $sheet->getStyle($column . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $column++;
            }

            // Fetch staff details
            $query = "SELECT u.id, u.first_name, u.last_name, u.username, u.email, u.mobile AS staff_mobile, 
                         v.store_id, cs.store_name, cs.address_line1 AS storeAddressLine1, cs.address_line2 AS storeAddressLine2,cs.dist,cs.state,cs.pincode, cs.phone AS store_phone
                  FROM cmp_users u 
                  JOIN cmp_vendor_store_staff_mapping v ON u.id = v.staff_id
                  JOIN cmp_store cs ON cs.id = v.store_id
                  WHERE u.status = 1 AND u.created_by='" . $loginData['user_id'] . "'";
            $result = $db->query($query);

            if ($result->num_rows === 0) {
                return [
                    "apiStatus" => [
                        "code" => "204",
                        "message" => "No staff data found."
                    ]
                ];
            }

            $rowIndex = 2;
            $sno = 1;
            while ($row = $result->fetch_assoc()) {
                $sheet->setCellValue('A' . $rowIndex, $sno);
                $sheet->setCellValue('B' . $rowIndex, $row['first_name']);
                $sheet->setCellValue('C' . $rowIndex, $row['last_name']);
                $sheet->setCellValue('D' . $rowIndex, $row['username']);
                $sheet->setCellValue('E' . $rowIndex, $row['email']);
                $sheet->setCellValue('F' . $rowIndex, $row['staff_mobile']);
                $sheet->setCellValue('G' . $rowIndex, $row['store_name']);
                $rowIndex++;
                $sno++;
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
                    "code" => "500",
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

            // Set column headers
            $headers = ['S.No', 'First Name', 'Last Name', 'Username', 'Email', 'Mobile', 'Store Name', 'Privilege'];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true);
                $sheet->getStyle($column . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $column++;
            }

            if (ob_get_contents()) ob_end_clean();

            // Output the file directly for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Staff_Data_' . date('Ymd_His') . '.xlsx"');
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


    private function deleteStaff($data)
    {
        try {

            $id = $data[2];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count 
                            FROM cmp_users u 
                            JOIN cmp_user_role_mapping urm ON urm.user_id = u.id 
                            JOIN cmp_mst_role cr ON cr.role_id = urm.role_id
            WHERE u.id = $id AND u.status=1 AND urm.role_id = 4";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Staff does not exist",
                    ),
                );
            }

            //update delete query

            $deleteQuery = "
                    UPDATE cmp_users u
            LEFT JOIN cmp_user_role_mapping urm ON urm.user_id = u.id
            LEFT JOIN cmp_vendor_store_staff_mapping vssm ON vssm.staff_id = u.id
            LEFT JOIN cmp_user_privilege_mapping upm ON upm.user_id = u.id
            LEFT JOIN cmp_vendor_user_mapping vum ON vum.user_id = u.id
            SET u.status = 0, 
                urm.status = 0, 
                vssm.status = 0, 
                upm.status = 0,
                vum.status = 0
            WHERE u.id = $id AND urm.role_id = 4;
        ";
            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Staff details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete Staff details, please try again later";
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


    // //store ative and deactive
    public function useractive($data, $loginData)
    {
        // print_r($data);exit;

        try {
            $id = $data[2];
            $db = $this->dbConnect();
            if (empty($data[2])) {
                throw new Exception("Bad request");
            }
            $user_id = $loginData['user_id'];
            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_users WHERE id = $id AND status=1";

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
            $ActiveQuery = "UPDATE cmp_users SET active_status = 1 WHERE status = 1 AND id = $id AND active_status = 0";

            if ($db->query($ActiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "User activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate User, please try again later.";
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
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_users WHERE id = $id AND active_status=1 AND status=1";
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
            $deactiveQuery = "UPDATE cmp_users SET active_status = 0 WHERE status = 1 AND id = $id";

            if ($db->query($deactiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "User Deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to Deactivate User, please try again later.";
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

    private function getTotalCount($loginData)
    {
        try {
            $db = $this->dbConnect();
            $sql = "SELECT cu.* ,cr.role_name
        FROM cmp_users cu
        JOIN cmp_user_role_mapping curm ON cu.id = curm.user_id
        JOIN cmp_mst_role cr ON cr.role_id = curm.role_id
        WHERE  curm.role_id = 4 AND cu.created_by = " . intval($loginData['user_id']) . " AND cu.status = 1 
        ";

            // print_r($sql);exit;
            $result = $db->query($sql);
            $row_cnt = mysqli_num_rows($result);
            return $row_cnt;
        } catch (Exception $e) {
            return array(
                "result" => "401",
                "message" => $e->getMessage(),
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
            // print_r($request);exit;
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
