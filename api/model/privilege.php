<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";
// require_once "model/register.php";


class PRIVILEGEMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    $result = $this->getprivilege($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] === 'create') {
                    $result = $this->createprivilege($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] === 'list') {
                    $result = $this->getPrivilegedetails($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    $result = $this->updateprivilege($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    $result = $this->deletePrivilege($data, $loginData);
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

    public function getPrivilegedetails($data, $loginData)
    {
        try {
            $responseArray = []; // Initialize response variable
            $db = $this->dbConnect();
            $recordCount = $this->getTotalCount($loginData);

            // Validate input parameters
            if ($data['pageIndex'] === "") {
                throw new Exception("PageIndex should not be empty!");
            }
            if ($data['dataLength'] == "") {
                throw new Exception("dataLength should not be empty!");
            }

            $start_index = $data['pageIndex'] * $data['dataLength'];
            $end_index = $data['dataLength'];

            // Query to fetch privileges, modules, and permissions
            $queryService = "SELECT 
    cm.id AS privilege_id, 
    cm.priv_name AS privilege_name, 
    cm.description,
    cmm.name AS module_name, 
    cmm.id AS module_id, 
    GROUP_CONCAT(
        CASE WHEN cpm.pre_create = 1 THEN 'create,' ELSE '' END,
        
        CASE WHEN cpm.pre_read = 1 THEN 'read,' ELSE '' END,
        
        CASE WHEN cpm.pre_update = 1 THEN 'update,' ELSE '' END,
        
        CASE WHEN cpm.pre_delete = 1 THEN 'delete' ELSE '' END
        SEPARATOR ','
    ) AS permissions 
            FROM cmp_privilege AS cm 
JOIN cmp_privilege_module_permission_mapping AS cpm 
    ON cm.id = cpm.priv_id
JOIN cmp_mst_module AS cmm 
    ON cpm.mod_id = cmm.id
    WHERE cm.status = 1 
    AND cm.created_by = " . $loginData['user_id'] . "
GROUP BY cm.id, cmm.id
ORDER BY cm.id DESC 
            LIMIT $start_index, $end_index";
            // print_r($queryService);exit;
            $result = $db->query($queryService);
            $staffDetails = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $privilegeId = $row['privilege_id'];
                    if (!isset($staffDetails[$privilegeId])) {
                        $staffDetails[$privilegeId] = [
                            "id" => (int)$privilegeId,
                            "privilegeName" => $row['privilege_name'],
                            "description" => $row['description'],
                            "modulePermissions" => []
                        ];
                    }

                    // Process permissions
                    $permissions = !empty($row['permissions']) ? array_values(array_filter(explode(",", $row['permissions']))) : [];

                    $staffDetails[$privilegeId]['modulePermissions'][] = [
                        "moduleId" => $row['module_id'],
                        "moduleName" => $row['module_name'],
                        "permission" => $permissions
                    ];
                }
            }

            // Construct the final response array
            $responseArray = [
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $recordCount,
                'PrivilegeData' => array_values($staffDetails) // Reset array keys
            ];

            // Return API response
            return [
                "apiStatus" => [
                    "code" => "200",
                    "message" => "Privilege details fetched successfully"
                ],
                "result" => $responseArray
            ];
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
    public function getprivilege($data, $loginData)
    {
        try {
            $id = $data[2];
            if (empty($id)) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $sql = "SELECT 
            cm.id AS privilege_id, 
            IFNULL(cm.priv_name, '') AS privilege_name, 
            IFNULL(cm.description, '') AS description,
            IFNULL(cmm.name, '') AS module_name, 
            IFNULL(cmm.id, 0) AS module_id, 
            IFNULL(GROUP_CONCAT(
                CASE WHEN cpm.pre_create = 1 THEN 'create,' ELSE '' END,
                CASE WHEN cpm.pre_read = 1 THEN 'read,' ELSE '' END,
                CASE WHEN cpm.pre_update = 1 THEN 'update,' ELSE '' END,
                CASE WHEN cpm.pre_delete = 1 THEN 'delete' ELSE '' END
                SEPARATOR ','
            ), '') AS permissions 
        FROM cmp_privilege AS cm 
        LEFT JOIN cmp_privilege_module_permission_mapping AS cpm 
            ON cm.id = cpm.priv_id
        LEFT JOIN cmp_mst_module AS cmm 
            ON cpm.mod_id = cmm.id            
        WHERE cm.status = 1 
            AND cpm.status = 1  
            AND cm.created_by = " . $loginData['user_id'] . "
            AND cpm.created_by = " . $loginData['user_id'] . "
            AND cm.id = $id
        GROUP BY cm.id, cmm.id";

            // print_r($sql);exit;
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                $staffDetails = [];

                while ($row = $result->fetch_assoc()) {
                    $privilegeId = $row['privilege_id'];
                    if (!isset($staffDetails[$privilegeId])) {
                        $staffDetails[$privilegeId] = [
                            "id" => (int)$privilegeId,
                            "privilegeName" => $row['privilege_name'],
                            "description" => $row['description'],
                            "modulePermissions" => []
                        ];
                    }

                    // Process permissions
                    $permissions = !empty($row['permissions']) ? array_values(array_filter(explode(",", $row['permissions']))) : [];

                    $staffDetails[$privilegeId]['modulePermissions'][] = [
                        "moduleId" => $row['module_id'],
                        "moduleName" => $row['module_name'],
                        "permission" => $permissions
                    ];
                }
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Privilege detail fetched successfully",
                    ),
                    "result" => array_values($staffDetails) // Reset array keys
                );
            } else {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "Privilege not found",
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
    public function createprivilege($data, $loginData)
    {
        $resultArray = array();

        try {
            $db = $this->dbConnect();

            // Validate privilegeName & description
            $this->validateInputDetails([
                "privilegeName" => $data['privilegeName'],
                "description"   => $data['description']
            ]);

            // Validate modulePermissions

            $moduleNames = [];
            foreach ($data['modulePermissions'] as $item) {
                if (!isset($item['moduleName'])) {
                    throw new Exception("Module name is missing");
                }

                if (in_array($item['moduleName'], $moduleNames)) {
                    throw new Exception("Duplicate module name found: " . $item['moduleName']);
                }
                $moduleNames[] = $item['moduleName'];

                $this->validateInputDetails(["moduleName" => $item['moduleName']]);
            }
            // Check if privilege already exists
            $sql = "SELECT id FROM cmp_privilege WHERE priv_name = '{$data['privilegeName']}' AND status = 1 AND created_by = {$loginData['user_id']}";
            $result = mysqli_query($db, $sql);

            if (mysqli_num_rows($result) > 0) {
                throw new Exception("Privilege already exists");
            }

            // Get module IDs
            $module_ids = [];
            foreach ($data['modulePermissions'] as $item) {
                $sql = "SELECT id FROM cmp_mst_module WHERE name = '{$item['moduleName']}' AND status = 1";
                $result = mysqli_query($db, $sql);

                if ($row = mysqli_fetch_assoc($result)) {
                    $module_ids[$item['moduleName']] = $row['id'];
                } else {
                    throw new Exception("Module does not exist: " . $item['moduleName']);
                }
            }

            // Check and validate permissions
            foreach ($data['modulePermissions'] as $item) {
                foreach ($item['permissions'] as $permission) {
                    $sql = "SELECT 1 FROM cmp_mst_permission WHERE name = '$permission' AND status = 1 LIMIT 1";
                    $result = mysqli_query($db, $sql);

                    if (mysqli_num_rows($result) == 0) {
                        throw new Exception("Permission does not exist: " . $permission);
                    }
                }
            }
            $dateNow = date("Y-m-d H:i:s");
            // Insert into cmp_privilege table
            $insertPrivilegeQuery = "INSERT INTO cmp_privilege (priv_name, description, created_by,created_date) 
                                 VALUES ('{$data['privilegeName']}', '{$data['description']}', '{$loginData['user_id']}','$dateNow')";
            if (mysqli_query($db, $insertPrivilegeQuery)) {
                $privilege_id = mysqli_insert_id($db);

                // Insert into cmp_privilege_module_permission_mapping
                foreach ($data['modulePermissions'] as $item) {
                    $mod_id = $module_ids[$item['moduleName']];
                    $pre_create = in_array("create", $item['permissions']) ? 1 : 0;
                    $pre_read   = in_array("read", $item['permissions']) ? 1 : 0;
                    $pre_update = in_array("update", $item['permissions']) ? 1 : 0;
                    $pre_delete = in_array("delete", $item['permissions']) ? 1 : 0;

                    $insertMappingQuery = "INSERT INTO cmp_privilege_module_permission_mapping 
                                       (priv_id, mod_id, pre_create, pre_read, pre_update, pre_delete, created_by, created_date) 
                                       VALUES ($privilege_id, $mod_id, $pre_create, $pre_read, $pre_update, $pre_delete, {$loginData['user_id']}, '$dateNow')";
                    mysqli_query($db, $insertMappingQuery);
                }

                mysqli_close($db);
                return [
                    "apiStatus" => [
                        "code"    => "200",
                        "message" => "Privilege details successfully created."
                    ]
                ];
            } else {
                throw new Exception("Error occurred while inserting privilege: " . mysqli_error($db));
            }
        } catch (Exception $e) {
            if (isset($db)) {
                mysqli_close($db);
            }
            return [
                "apiStatus" => [
                    "code"    => "401",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }

    /**
     * Update privilege details
     *
     * @param array $data
     * @return multitype:string
     */

    /**
     * Updates an existing privilege with new details and module permissions.
     *
     * @param array $data       Contains privilege details, including privilegeId, privilegeName, description, and modulePermissions.
     * @param array $loginData  Contains user details like user_id.
     * @return array            API response with status code and message.
     */
    public function updatePrivilege($data, $loginData)
    {
        $resultArray = array();

        try {
            // Connect to the database
            $db = $this->dbConnect();

            // Validate privilege ID
            if (!isset($data['privilegeId'])) {
                throw new Exception("Privilege ID is required");
            }
            $privilegeId = $data['privilegeId'];

            // Validate input details
            $this->validateInputDetails([
                "privilegeName" => $data['privilegeName'],
                "description"   => $data['description']
            ]);

            // Check if privilege exists
            $checkPrivilegeQuery = "SELECT id FROM cmp_privilege WHERE id = $privilegeId  AND status=1 AND created_by = {$loginData['user_id']}";
            $result = mysqli_query($db, $checkPrivilegeQuery);
            if (mysqli_num_rows($result) == 0) {
                throw new Exception("Privilege not found");
            }
            // Check if privilege name already exists
            $checkPrivilegeNameQuery = "SELECT id FROM cmp_privilege WHERE priv_name = '{$data['privilegeName']}' AND id != $privilegeId AND status=1 AND created_by = {$loginData['user_id']}";
            $result = mysqli_query($db, $checkPrivilegeNameQuery);
            if (mysqli_num_rows($result) > 0) {
                throw new Exception("Privilege name already exists");
            }
            // Validate module names and check for duplicates
            $moduleNames = [];
            foreach ($data['modulePermissions'] as $item) {
                if (!isset($item['moduleName'])) {
                    throw new Exception("Module name is missing");
                }
                if (in_array($item['moduleName'], $moduleNames)) {
                    throw new Exception("Duplicate module name found: " . $item['moduleName']);
                }
                $moduleNames[] = $item['moduleName'];
            }

            // Fetch module IDs and validate module permissions
            $module_ids = [];
            foreach ($data['modulePermissions'] as $item) {
                $sql = "SELECT id FROM cmp_mst_module WHERE name = '{$item['moduleName']}' AND status = 1";
                $result = mysqli_query($db, $sql);
                if ($row = mysqli_fetch_assoc($result)) {
                    $module_ids[$item['moduleName']] = $row['id'];
                } else {
                    throw new Exception("Module does not exist: " . $item['moduleName']);
                }

                // Validate each permission
                foreach ($item['permissions'] as $permission) {
                    $sql = "SELECT 1 FROM cmp_mst_permission WHERE name = '$permission' AND status = 1 LIMIT 1";
                    $result = mysqli_query($db, $sql);
                    if (mysqli_num_rows($result) == 0) {
                        throw new Exception("Permission does not exist: " . $permission);
                    }
                }
            }
             $dateNow = date("Y-m-d H:i:s");
            //  $dateNow = date("Y-m-d H:i:s");
            // Update privilege details
            $updatePrivilegeQuery = "UPDATE cmp_privilege SET priv_name = '{$data['privilegeName']}', description = '{$data['description']}' ,updated_by ='{$loginData['user_id']}',updated_date ='{$dateNow}' WHERE id = $privilegeId AND Status=1   AND created_by = {$loginData['user_id']}";
            mysqli_query($db, $updatePrivilegeQuery);

            // Fetch existing module permissions
            $existingPermissions = [];
            $sql = "SELECT mod_id, pre_create, pre_read, pre_update, pre_delete FROM cmp_privilege_module_permission_mapping WHERE priv_id = $privilegeId AND status = 1 AND created_by = {$loginData['user_id']}";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $existingPermissions[$row['mod_id']] = $row;
            }

            // Update or insert module permissions
            foreach ($data['modulePermissions'] as $item) {
                $mod_id = $module_ids[$item['moduleName']];
                $pre_create = in_array("create", $item['permissions']) ? 1 : 0;
                $pre_read   = in_array("read", $item['permissions']) ? 1 : 0;
                $pre_update = in_array("update", $item['permissions']) ? 1 : 0;
                $pre_delete = in_array("delete", $item['permissions']) ? 1 : 0;

                if (isset($existingPermissions[$mod_id])) {
                    // Update existing permissions
                    $updatePermissionQuery = "UPDATE cmp_privilege_module_permission_mapping 
                                         SET pre_create = $pre_create, pre_read = $pre_read, pre_update = $pre_update, pre_delete = $pre_delete ,updated_by ='{$loginData['user_id']}',updated_date = '{$dateNow}'
                                         WHERE priv_id = $privilegeId AND mod_id = $mod_id AND status = 1 AND created_by = {$loginData['user_id']}";
                    mysqli_query($db, $updatePermissionQuery);
                    unset($existingPermissions[$mod_id]);
                } else {
                    // Insert new module permissions
                    $insertPermissionQuery = "INSERT INTO cmp_privilege_module_permission_mapping 
                                         (priv_id, mod_id, pre_create, pre_read, pre_update, pre_delete, created_by, created_date) 
                                         VALUES ($privilegeId, $mod_id, $pre_create, $pre_read, $pre_update, $pre_delete, {$loginData['user_id']}, '$dateNow')";
                    mysqli_query($db, $insertPermissionQuery);
                }
            }

            // Disable old permissions that are no longer present
            foreach ($existingPermissions as $mod_id => $perm) {
                $disableOldPermissionQuery = "UPDATE cmp_privilege_module_permission_mapping 
                                          SET status = 0 WHERE priv_id = $privilegeId AND mod_id = $mod_id AND created_by = {$loginData['user_id']}";
                mysqli_query($db, $disableOldPermissionQuery);
            }

            // Close database connection
            mysqli_close($db);
            return [
                "apiStatus" => [
                    "code"    => "200",
                    "message" => "Privilege details successfully updated."
                ]
            ];
        } catch (Exception $e) {
            if (isset($db)) {
                mysqli_close($db);
            }
            return [
                "apiStatus" => [
                    "code"    => "401",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }





    private function deletePrivilege($data, $loginData)
    {
        try {

            $id = $data[2];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_privilege WHERE id = $id AND status=1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "Privilege does not exist",
                    ),
                );
            }

            //update delete query

            $deleteQuery = "UPDATE cmp_privilege cp
                JOIN cmp_privilege_module_permission_mapping cpm 
                ON cp.id = cpm.priv_id
                SET cp.status = 0, cpm.status = 0
                WHERE cp.id = " . $id . " 
                AND cp.created_by = " . $loginData['user_id'] . "
                AND cpm.created_by = " . $loginData['user_id'] . "";


            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "Privilege details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete Privilege details, please try again later";
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
            $sql = "SELECT * FROM cmp_privilege WHERE status=1 AND created_by = " . $loginData['user_id'] . "";
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
