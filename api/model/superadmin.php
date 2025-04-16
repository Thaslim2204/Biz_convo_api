<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";


class SUPERADMINLOGINMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {
        $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);

                if ($urlParam[1] === 'login') {
                    $result = $this->loginCheck($data);
                } elseif ($urlParam[1] === 'vendorlogin') {
                    $result = $this->vendorloginCheck($data, $loginData);
                } elseif ($urlParam[1] === 'vendor') {
                    if (isset($urlParam[2]) && $urlParam[2] === 'create') {
                        $result = $this->createvendormanage($data, $loginData);
                    } elseif (isset($urlParam[2]) && $urlParam[2] === 'list') {
                        $result = $this->getvendormanagaedetails($data, $loginData);
                    } else {
                        throw new Exception("Unable to proceed with your request!");
                    }
                } else {
                    throw new Exception("Invalid request!");
                }
                return $result;

            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] === 'logout') {
                    $result = $this->logout($data, $loginData);
                } elseif ($urlParam[1] === 'vendor' && isset($urlParam[2]) && $urlParam[2] === 'get') {
                    $result = $this->getvendormanage($data, $loginData);
                } elseif ($urlParam[2] === "useractive") {
                    $result = $this->useractive($loginData, $data);
                    return $result;
                } elseif ($urlParam[2] === "userdeactive") {
                    $result = $this->userdeactive($loginData, $data);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed with your request!");
                }
                return $result;

            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);

                if ($urlParam[1] === 'vendor' && isset($urlParam[2]) && $urlParam[2] === 'update') {
                    $result = $this->updatevendormanage($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed with your request!");
                }
                return $result;

            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);

                if ($urlParam[1] === 'vendor' && isset($urlParam[2]) && $urlParam[2] === 'delete') {
                    $result = $this->deletevendormanage($data);
                } else {
                    throw new Exception("Unable to proceed with your request!");
                }
                return $result;

            default:
                // return $this->handle_error();
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
     * Get Login Authendication
     *
     * @return multitype:
     */
    private function loginCheck($request)
    {

        try {

            if (empty($request['email_id'])) {
                throw new Exception("Please give the User Name");
            } else if (empty($request['password'])) {
                throw new Exception("Please give the Password");
            }

            $db = $this->dbConnect();

            if (true) {
                $query = "SELECT u.first_name,u.last_name,u.username, u.password, u.id,urm.role_id,rl.role_name FROM cmp_users u JOIN cmp_user_role_mapping urm ON urm.user_id = u.id JOIN cmp_mst_role rl ON urm.id = rl.role_id   WHERE
                 u.email = '" . $request['email_id'] . "' AND rl.role_name= 'super_admin'
                AND u.status = 1";
                // print_r($query);exit;

            } else {
                throw new Exception("Your Login has not actiated.");
            }
            $result = $db->query($query);

            if ($result) {

                $row_cnt = mysqli_num_rows($result);
                if ($row_cnt > 0) {
                    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $hash = hash('sha256', hash('sha256', $request['password']));
                    // print_r($hash);exit;    
                    if ($hash != $data['password']) {
                        throw new Exception("Invalid password");
                    }
                } else {
                    throw new Exception("Invalid Username Or password");
                }
            } else {
                throw new Exception("Invalid Useraaname Or password");
            }

            // Create Token collection for authendication
            $token = $this->generateJWTToken($data['id'], $data['email_id']);

            $roles = $this->getAdminUserRole($data['id']);

            $userDetails = array(
                'loginid' => $data['id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username' => $data['username'],
                'roles' => $roles,
            );
            $result = array(
                "token" => $token,
                "userDetail" => $userDetails,
            );

            if (empty($newuser)) {

                $new = array(
                    "firstTime" => "true",
                );
                $result = array_merge($result, $new);
            }
            $userId = $data['id'];

            $timeNow = date("Y-m-d H:i:s");
            $sqlInsert = "INSERT INTO cmp_user_login_log (user_id, token, login_time, last_active_time) VALUES ('$userId', '$token', '$timeNow', '$timeNow')";

            if ($db->query($sqlInsert) === true) {
                $db->close();

                $logger = $this->loginLogCreate("logged into the application", $request['name'], getcwd());
            }
            $resultArray = array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Login Successfully",
                ),
                "result" => $result,
            );

            return $resultArray;
        } catch (Exception $e) {
            $this->loginLogCreate($e->getMessage(), "", getcwd());
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }

    private function vendorloginCheck($data, $loginData)
{
    try {
        if (empty($data['vendorId'])) {
            throw new Exception("Please provide the Vendor ID");
        }

        $db = $this->dbConnect();

        // Fetch Vendor Details
        $vendorQuery = "SELECT * FROM cmp_vendor WHERE id = '" . $data['vendorId'] . "' AND status = 1";
        $vendorResult = $db->query($vendorQuery);

        if ($vendorResult->num_rows == 0) {
            throw new Exception("Vendor not found.");
        }

        $vendorDetails = $vendorResult->fetch_assoc();

        // Fetch all user_ids mapped to the vendor
        $vendorAdminQuery = "SELECT user_id FROM cmp_vendor_user_mapping WHERE vendor_id = '" . $data['vendorId'] . "' AND status = 1";
        $vendorAdminResult = $db->query($vendorAdminQuery);

        if ($vendorAdminResult->num_rows == 0) {
            throw new Exception("No users mapped to this vendor.");
        }

        //Loop through and find user with 'Vendor Admin' role
        $vendorAdminUserId = null;
        while ($row = $vendorAdminResult->fetch_assoc()) {
            $userId = $row['user_id'];

            // Fetch role ids for user
            $roleMapQuery = "SELECT role_id FROM cmp_user_role_mapping WHERE user_id = '$userId'";
            $roleMapResult = $db->query($roleMapQuery);
            if ($roleMapResult->num_rows == 0) {
                continue; // no role assigned
            }

            while ($roleRow = $roleMapResult->fetch_assoc()) {
                $roleId = $roleRow['role_id'];

                // Fetch role name from master table
                $roleNameQuery = "SELECT role_name FROM cmp_mst_role WHERE role_id = '$roleId'";
                $roleNameResult = $db->query($roleNameQuery);
                if ($roleNameResult->num_rows > 0) {
                    $role = $roleNameResult->fetch_assoc();
                    if (strtolower($role['role_name']) === 'vendor_super_admin') {
                        $vendorAdminUserId = $userId; // found vendor admin
                        break 2; // break both loops
                    }
                }
            }
        }

        if (!$vendorAdminUserId) {
            throw new Exception("Vendor Admin not found for this vendor.");
        }

        //Fetch vendor admin user details
        $userQuery = "SELECT * FROM cmp_users WHERE id = '" . $vendorAdminUserId . "' AND status=1 AND active_status=1";
        $userResult = $db->query($userQuery);

        if ($userResult->num_rows == 0) {
            throw new Exception("Vendor Admin user not found.");
        }

        $userDetails = $userResult->fetch_assoc();
        $userId = $userDetails['id'];

        //Check for existing active token
        $tokenCheckQuery = "SELECT token FROM cmp_user_login_log WHERE user_id = '$userId' AND login_status = 1 AND status = 1 ORDER BY login_time DESC LIMIT 1";
        $tokenResult = $db->query($tokenCheckQuery);

        if ($tokenResult->num_rows > 0) {
            $row   = $tokenResult->fetch_assoc();
            $token = $row['token'];
        } else {
            // Generate a new token
            $token = $this->generateJWTToken($userDetails['id'], $userDetails['username']);
            $timeNow = date("Y-m-d H:i:s");
            $sqlInsertUserLog = "INSERT INTO cmp_user_login_log (user_id, token, login_time, last_active_time) 
                                 VALUES ('$userId', '$token', '$timeNow', '$timeNow')";
            $db->query($sqlInsertUserLog);
        }

        //Log into superadmin-vendor login log
        $vendorId = $data['vendorId'];
        $timeNow  = date("Y-m-d H:i:s", strtotime("+4 hours 30 minutes"));
        $sqlInsertSuperAdminLog = "INSERT INTO cmp_superadmin_vendor_login_log 
                                   (user_id, vendor_id, token, login_time, last_active_time, created_date) 
                                   VALUES ('$userId', '$vendorId', '$token', '$timeNow', '$timeNow', NOW())";
        $db->query($sqlInsertSuperAdminLog);

        //Prepare response
        $userDetails = array(
            "loginid" => $userDetails['id'],
            "first_name" => $userDetails['first_name'],
            "last_name" => $userDetails['last_name'],
            "username" => $userDetails['username'],
            "logInAs" => "super_admin",
        );

        $result = array(
            "token" => $token,
            "userDetail" => $userDetails,
        );

        $resultArray = array(
            "apiStatus" => array(
                "code" => "200",
                "message" => "Login Successfully",
            ),
            "result" => $result,
        );

        return $resultArray;
    } catch (Exception $e) {
        $this->loginLogCreate($e->getMessage(), "", getcwd());
        return array(
            "apiStatus" => array(
                "code" => "401",
                "message" => $e->getMessage(),
            ),
        );
    }
}



    /**
     * Log create For Login
     *
     * @param string $message
     * @param string $userName
     * @throws Exception
     */
    public function loginLogCreate($message, $name, $dir)
    {

        try {
            $fp = fopen(LOG_LOGIN, "a");
            $file = $dir;
            fwrite($fp, "" . "\t" . Date("r") . "\t$file\t$name\t$message\r\n");
        } catch (Exception $e) {
            $this->loginLogCreate($e->getMessage(), "", getcwd());
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }

    /**
     * Function is to generate the JWT Token
     *
     * @param int $userId
     * @param string $name
     * @return string
     */
    public function getAdminUserRole($userId)
    {
        try {
            $db = $this->dbConnect();
            $querySdfd = "SELECT rl.role_name, rl.role_id
            FROM cmp_user_role_mapping AS urm
            JOIN cmp_mst_role AS rl ON urm.role_id = rl.role_id
            WHERE urm.user_id = '$userId'
            ";
            $result = $db->query($querySdfd);
            // print_r($querySdfd);exit;

            $row_cnt = mysqli_num_rows($result);

            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $role = array('roleName' => $data['role_name'], 'role_id' => $data['role_id']);
            // print_r($role);exit;
            return $role;
        } catch (Exception $e) {
            return array(
                "code" => "401",
                "message" => $e->getMessage(),
            );
        }
    }
    public function generateJWTToken($userId, $name)
    {
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256',
        ]);

        // Create the token payload
        $payload = json_encode([
            'user_id' => $userId,
            'name' => $name,
            'exp' => 3600,
        ]);
        // print_r($payload);exit;
        // Encode Header
        $base64UrlHeader = str_replace(['+', '/', '='], ['', '', ''], base64_encode($header));

        // Encode Payload
        $base64UrlPayload = str_replace(['+', '/', '='], ['', '', ''], base64_encode($payload));

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, "secret", true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['', '', ''], base64_encode($signature));

        // Create JWT
        $token = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $token;
    }


    /**
     * Function is to check the Login Authendication By token
     *
     * @param array $request
     * @throws Exception
     * @return multitype:
     */
    public function tokenCheck($token = "")
    {
        // print_r($token);exit;
        try {
            if (empty($token)) {
                throw new Exception("Please give the Token");
            }
            $db = $this->dbConnect();
            // echo"1234";exit;

            $query = "SELECT a.id, u.email, a.user_id, c.role_id, b.role_name
            FROM cmp_user_login_log a
            LEFT JOIN cmp_users u ON a.user_id = u.id
            LEFT JOIN cmp_user_role_mapping c ON a.user_id = c.user_id
            LEFT JOIN cmp_mst_role b ON c.role_id = b.role_id
            WHERE a.token = '$token' AND a.login_status = 1 AND a.status = 1";
            // print_r($query);exit;
            $result = $db->query($query);

            $row_cnt = mysqli_num_rows($result);
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($row_cnt < 1) {
                throw new Exception("Unauthorized Login");
            }
            return $data;
        } catch (Exception $e) {
            $this->loginLogCreate($e->getMessage(), "", getcwd());
            throw new Exception($e->getMessage());
        }
    }

    public function logout($data, $loginData)
    {
       
        try {
           
            $user_id = $loginData['user_id'];
            $db = $this->dbConnect();
            // Fetch token associated with the user_id
            $query = "SELECT token FROM cmp_user_login_log WHERE user_id = '$user_id' AND login_status = 1";
            $result = $db->query($query);

            if (!$result || mysqli_num_rows($result) == 0) {
                throw new Exception("No active session found for this user");
            }

            $userData = mysqli_fetch_assoc($result);
            $token = $userData['token'];
            $query = "SELECT id, user_id FROM cmp_user_login_log WHERE token = '$token' and Login_status='1'";
            
            $result = $db->query($query);
            $row_cnt = mysqli_num_rows($result);
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($row_cnt > 0) {
                $sqlUpdate = "UPDATE cmp_user_login_log cul
                            JOIN cmp_superadmin_vendor_login_log svl ON cul.user_id = svl.user_id
                            SET cul.login_status = 0, svl.login_status = 0
                            WHERE cul.user_id = '" . $data['user_id'] . "';
                            ";
                // print_r($sqlUpdate);exit();
                if ($db->query($sqlUpdate) === true) {
                    $db->close();
                }
                $this->loginLogCreate("logout from the application USER ID: ", $data['user_id'], getcwd());
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Logout Successfully",
                    ),
                );
            } else {
                $this->loginLogCreate("There is no active user found with this USER ID: ", $data['user_id'], getcwd());
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "There is no active user found with this details",
                    ),
                );
            }

            return $resultArray;
        } catch (Exception $e) {
            $this->loginLogCreate($e->getMessage(), "", getcwd());
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
    }


    //vendor //


    public function getvendormanagaedetails($data, $loginData)
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

            // Query to fetch vendors and their contact persons
            $queryService = "SELECT 
                    v.id AS vendor_id, 
                    v.name, 
                    v.type, 
                    v.email, 
                    v.phone, 
                    v.address, 
                    v.active_status, 

                    vsm.id AS vum_id, 
                    vsm.user_id, 
                    vsm.vendor_id, 
                    vsm.mapping_status, 

                    u.id AS user_id,
                    u.first_name,
                    u.last_name,
                    u.username,
                    u.email AS user_email,
                    u.mobile AS user_phone,
                    u.status AS user_status,
                    u.created_by,

                    r.role_id AS role_id,
                    r.role_name AS role_name,

                    urm.id AS rum_id, -- Fixed alias issue
                    urm.user_id AS rum_user_id, 
                                urm.role_id AS rum_role_id, 
                    urm.status AS rum_status

                FROM cmp_vendor AS v 
                LEFT JOIN cmp_vendor_user_mapping AS vsm 
                   ON v.id = vsm.vendor_id AND vsm.status = 1
                LEFT JOIN cmp_users AS u 
                    ON vsm.user_id = u.id
                LEFT JOIN cmp_user_role_mapping AS urm  
                    ON u.id = urm.user_id
                LEFT JOIN cmp_mst_role AS r 
                   ON urm.role_id = r.role_id
                       WHERE v.status = 1 
                        --    AND v.created_by = '" . $loginData['user_id'] . "'
                          AND r.role_name = 'vendor_super_admin'
                       ORDER BY v.id DESC 
                       LIMIT 
                          $start_index, $end_index";
            //   print_r($queryService);exit;
            $result = $db->query($queryService);
            $row_cnt = mysqli_num_rows($result);

            $vendor = array(); // Initialize array to hold vendor data
            if ($row_cnt > 0) {
                while ($row = $result->fetch_assoc()) {
                    $vendor[] = array(
                        "VendorId" => $row['vendor_id'],
                        "VendorName" => $row['name'],
                        "VendorType" => $row['type'],
                        "VendorEmail" => $row['email'],
                        "VendorPhone" => $row['phone'],
                        "VendorAddress" => $row['address'],
                        "VendorStatus" => $row['active_status'],
                        "UserId" => $row['user_id'],
                        "UserFirstName" => $row['first_name'],
                        "UserLastName" => $row['last_name'],
                        "UserUserName" => $row['username'],
                        "UserEmail" => $row['user_email'],
                        "UserPhone" => $row['user_phone'],
                        "UserStatus" => $row['user_status'],
                        "RoleId" => $row['role_id'],
                        "RoleName" => $row['role_name'],

                    );
                }
            }

            // Construct the final response array
            $responseArray = array(
                "pageIndex" => $data['pageIndex'],
                "dataLength" => $data['dataLength'],
                "totalRecordCount" => $recordCount,
                'VendorData' => array_values($vendor), // Reset array keys
            );

            // Check if vendor data exists
            if (!empty($vendor)) {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Vendor details with super admin persons fetched successfully",
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
    public function getvendormanage($data, $loginData)
    {
        try {
            $id = $data[3];
            if (empty($id)) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $sql = "SELECT 
                    v.id AS vendor_id, 
                    v.name, 
                    v.type, 
                    v.email, 
                    v.phone, 
                    v.address, 
                    v.active_status, 

                    vsm.id AS vum_id, 
                    vsm.user_id, 
                    vsm.vendor_id, 
                    vsm.mapping_status, 

                    u.id AS user_id,
                    u.first_name,
                    u.last_name,
                    u.username,
                    u.email AS user_email,
                    u.mobile AS user_phone,
                    u.status AS user_status,
                    u.created_by,

                    r.role_id AS role_id,
                    r.role_name AS role_name,

                    urm.id AS rum_id, -- Fixed alias issue
                    urm.user_id AS rum_user_id, 
                                urm.role_id AS rum_role_id, 
                    urm.status AS rum_status

                FROM cmp_vendor AS v 
                LEFT JOIN cmp_vendor_user_mapping AS vsm 
                   ON v.id = vsm.vendor_id AND vsm.status = 1
                LEFT JOIN cmp_users AS u 
                    ON vsm.user_id = u.id
                LEFT JOIN cmp_user_role_mapping AS urm  
                    ON u.id = urm.user_id
                LEFT JOIN cmp_mst_role AS r 
                   ON urm.role_id = r.role_id
                    WHERE  v.status = 1 
                        AND r.role_name = 'vendor_super_admin'
                       AND v.id = $id";

            $result = $db->query($sql);

            // Check if vendor exists
            if ($result->num_rows > 0) {
                $vendor = array();
                $contactPersons = array();

                while ($row = $result->fetch_assoc()) {
                    $vendor = array(
                        "VendorId" => $row['vendor_id'],
                        "VendorName" => $row['name'],
                        "VendorType" => $row['type'],
                        "VendorEmail" => $row['email'],
                        "VendorPhone" => $row['phone'],
                        "VendorAddress" => $row['address'],
                        "VendorStatus" => $row['active_status'],
                        "UserId" => $row['user_id'],
                        "UserFirstName" => $row['first_name'],
                        "UserLastName" => $row['last_name'],
                        "UserUserName" => $row['username'],
                        "UserEmail" => $row['user_email'],
                        "UserPhone" => $row['user_phone'],
                        "UserStatus" => $row['user_status'],
                        "RoleId" => $row['role_id'],
                        "RoleName" => $row['role_name'],
                    );
                }
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Vendor details with super admin persons fetched successfully",
                    ),
                    "result" => $vendor
                );
            } else {
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "404",
                        "message" => "Vendor not found",
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
    public function createvendormanage($data, $loginData)
    {
        // print_r($loginData);exit;
        // Initialize result array
        $resultArray = array();
        try {
            $db = $this->dbConnect();
            // Check if the logged-in user is an Admin or Super Admin
            if (!in_array($loginData['role_name'], ['super_admin'])) {
                throw new Exception("Permission denied. Only Super Admin can create a user.");
            }

            // Validate input data
            $validationData = array(
                "Company Name" => $data['company_name'],
                "Type"         => $data['type'],
                "Address"      => $data['address'],
                "Phone"        => $data['phone'],
                "First Name"   => $data['userData']['first_name'],
                "Last Name"    => $data['userData']['last_name'],
                "User Name"    => $data['userData']['username'],
                "Email ID"     => $data['userData']['email_id'],
                "Password"     => $data['userData']['password']
            );

            $this->validateInputDetails($validationData);

            $userData = $data['userData'];
            $password = $userData['password'];

            if ($userData['password'] != $userData['confirmPassword']) {
                throw new Exception("Password & Confirm Password are not correct!");
            }
            // Check if the user already exists
            $sql = "SELECT id FROM cmp_users WHERE email = '" . $userData['email_id'] . "' AND status = 1";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
                throw new Exception("User already exist");
            }
            // Hash the password and generate a unique uid
            $hashed_password = hash('sha256', hash('sha256', $password));
            $uid = bin2hex(random_bytes(8));

            // Get role ID dynamically
            $roleName = $userData['role_name']; // Change this if needed
            $sql = "SELECT role_id FROM cmp_mst_role WHERE role_name = '$roleName'";
            $roleResult = mysqli_query($db, $sql);
            if ($roleRow = mysqli_fetch_assoc($roleResult)) {
                $roleId = $roleRow['role_id'];
            } else {
                throw new Exception("Role '$roleName' not found.");
            }

            // Insert into cmp_users table
            $insertUserQuery = "INSERT INTO cmp_users (uid, first_name, last_name, username, email, password, mobile,created_by)
                            VALUES ('" . $uid . "', '" . $userData['first_name'] . "', '" . $userData['last_name'] . "', '" . $userData['username'] . "', '" . $userData['email_id'] . "', '" . $hashed_password . "', '" . $userData['phone'] . "','" . $loginData['user_id'] . "')";
            if ($db->query($insertUserQuery) === true) {
                $lastInsertedId = mysqli_insert_id($db);

                // Insert into cmp_user_role_mapping
                $sql = "INSERT INTO cmp_user_role_mapping (user_id, role_id, created_by) 
                VALUES ('$lastInsertedId', '$roleId', '{$loginData['user_id']}')";
                if (!mysqli_query($db, $sql)) {
                    throw new Exception("Failed to insert user-role mapping: " . mysqli_error($db));
                }
                // Check if vendor exists
                $sql = "SELECT id FROM cmp_vendor WHERE  status = 1 AND name = '" . $data['company_name'] . "' AND email = '" . $data['email'] . "' ";
                // print_r($sql);exit;
                $vendorResult = mysqli_query($db, $sql);

                if ($vendorRow = mysqli_fetch_assoc($vendorResult)) {
                    $vendorId = $vendorRow['id'];
                } else {
                    // Insert new vendor
                    $vendorUid   = bin2hex(random_bytes(8));
                    $createdDate = date('Y-m-d H:i:s');
                    $insertVendorQuery = "INSERT INTO cmp_vendor (uid, name, type, address, phone, email, created_by, created_date)
                                     VALUES ('" . $vendorUid . "', '" . $data['company_name'] . "', '" . $data['type'] . "', '" . $data['address'] . "', '" . $data['phone'] . "', '" . $data['email'] . "', '" . $loginData['user_id'] . "', '" . $createdDate . "')";
                    if ($db->query($insertVendorQuery) === true) {
                        $vendorId = mysqli_insert_id($db);
                    } else {
                        throw new Exception("Failed to insert vendor: " . $db->error);
                    }
                }

                // Insert into cmp_vendor_user_mapping
                $insertMappingQuery = "INSERT INTO cmp_vendor_user_mapping (vendor_id, user_id, created_by)
                                  VALUES ('" . $vendorId . "', '" . $lastInsertedId . "','" . $loginData['user_id'] . "')";
                if (!$db->query($insertMappingQuery)) {
                    throw new Exception("Failed to insert vendor mapping: " . $db->error);
                }

                $db->close();

                // Populate result array
                $resultArray = array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Vendor details successfully created.",
                    ),
                );
            } else {
                throw new Exception("Error occurred while inserting vendor: " . $db->error);
            }
        } catch (Exception $e) {
            $resultArray = array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage(),
                ),
            );
        }
        return $resultArray;
    }


    private function updatevendormanage($data, $loginData)
    {
        try {
            $db = $this->dbConnect();
            if (!in_array($loginData['role_name'], ['super_admin', 'vendor_super_admin'])) {
                throw new Exception("Permission denied. Only Admin and Super Admin can Update Details.");
            }



            $dateNow = date("Y-m-d H:i:s", strtotime('+3 hours 30 minutes'));
            $vendorUpdated = false;
            $userUpdated = false;

            // If vendor details are provided, validate and update them
            if (isset($data['company_name']) || isset($data['type']) || isset($data['address']) || isset($data['phone'])) {
                $validationData = array(
                    "id" => $data['id'],
                    "CompanyName" => $data['company_name'],
                    "Type" => $data['type'],
                    "Address" => $data['address'],
                    "Phone" => $data['phone']
                );
                $this->validateInputDetails($validationData);
                // Check if the vendor ID exists and is active
                $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_vendor WHERE id = '{$data['id']}' AND status = 1";
                $result = $db->query($checkIdQuery);
                $rowCount = $result->fetch_assoc()['count'];

                if ($rowCount == 0) {
                    $db->close();
                    return [
                        "apiStatus" => [
                            "code" => "400",
                            "message" => "Vendor does not exist",
                        ],
                    ];
                }

                $updateVendorQuery = "UPDATE cmp_vendor SET 
                                      name = '{$data['company_name']}',
                                      type = '{$data['type']}',
                                      address = '{$data['address']}',
                                      phone = '{$data['phone']}',
                                      email = '{$data['email']}',
                                      updated_by = '{$loginData['user_id']}',
                                      updated_date = '{$dateNow}'
                                      WHERE id = '{$data['id']}' AND status = 1";

                if ($db->query($updateVendorQuery) === false) {
                    $db->close();
                    return [
                        "apiStatus" => [
                            "code" => "500",
                            "message" => "Unable to update vendor details, please try again later",
                        ],
                    ];
                }
                $vendorUpdated = true;
            }

            // Update vendor user details if provided
            if (isset($data['userData'])) {
                $userData = $data['userData'];

                // Validate required user fields
                $validationUserData = array(
                    "username" => $userData['username'],
                    "first_name" => $userData['first_name'],
                    "last_name" => $userData['last_name'],
                    "email_id" => $userData['email_id']
                );
                $this->validateInputDetails($validationUserData);

                // Check if username or email already exists
                $checkUserQuery = "SELECT COUNT(*) AS count FROM cmp_users WHERE (username = '{$userData['username']}' OR email = '{$userData['email_id']}') AND id != '{$userData['user_id']}'";
                //   print_r($checkUserQuery);exit;
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

                // Check if password needs to be updated
                $passwordUpdate = "";
                if (!empty($userData['password'])) {
                    $hashed_password = hash('sha256', hash('sha256', $userData['password']));
                    $passwordUpdate = ", password = '{$hashed_password}'";
                }

                $updateUserQuery = "UPDATE cmp_users SET 
                                    first_name = '{$userData['first_name']}',
                                    last_name = '{$userData['last_name']}',
                                    username = '{$userData['username']}',
                                    email = '{$userData['email_id']}',
                                    mobile = '{$userData['phone']}',
                                    updated_by = '{$loginData['user_id']}',
                                    updated_date = '{$dateNow}'
                                    {$passwordUpdate}
                                    WHERE id = '{$userData['user_id']}' AND status = 1";

                if ($db->query($updateUserQuery) === false) {
                    $db->close();
                    return [
                        "apiStatus" => [
                            "code" => "500",
                            "message" => "Unable to update vendor user details, please try again later",
                        ],
                    ];
                }
                $userUpdated = true;
            }

            $db->close();

            $message = "No updates were made.";
            if ($vendorUpdated && $userUpdated) {
                $message = "Vendor details and user details updated successfully.";
            } elseif ($vendorUpdated) {
                $message = "Vendor details updated successfully.";
            } elseif ($userUpdated) {
                $message = "Vendor user details updated successfully.";
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




    private function deletevendormanage($data)
    {
        try {

            $id = $data[3];
            $db = $this->dbConnect();
            // Check if the ID is provided and valid
            if (empty($data[2])) {
                throw new Exception("Invalid. Please enter your ID.");
            }
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_vendor WHERE id = $id AND status=1";
            // print_r($checkIdQuery);exit;
            $result = $db->query($checkIdQuery);
            $rowCount = $result->fetch_assoc()['count'];

            // If ID doesn't exist, return error
            if ($rowCount == 0) {
                $db->close();
                return array(
                    "apiStatus" => array(
                        "code" => "400",
                        "message" => "vendor does not exist",
                    ),
                );
            }

            //update delete query

            // $deleteQuery = "UPDATE cmp_vendor_user_mapping
            // INNER JOIN cmp_vendor ON cmp_vendor_user_mapping.vendor_id = cmp_vendor.id
            // INNER JOIN cmp_users on cmp_vendor_user_mapping.user_id = cmp_users.id
            // INNER JOIN cmp_user_role_mapping on cmp_users.id = cmp_user_role_mapping.user_id
            // SET cmp_vendor_user_mapping.status = 0 ,cmp_vendor.status =0 ,cmp_users.status = 0,cmp_user_role_mapping.status = 0
            // WHERE cmp_vendor.id = " . $id . "";
            $deleteQuery = "UPDATE cmp_vendor SET status = 0 WHERE id = " . $id . "";
            // print_r($deleteQuery);exit;

            if ($db->query($deleteQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "vendor details deleted successfully";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to delete vendor details, please try again later";
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

    //vendor activative and deactive

    public function useractive($loginData, $data)
    {
        try {
            $id = $data[3];
            $db = $this->dbConnect();
            if (empty($data[3])) {
                throw new Exception("Bad request");
            }

            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_vendor WHERE id = $id AND status=1";

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
            $ActiveQuery = "UPDATE cmp_users cu
JOIN cmp_vendor_user_mapping vum ON cu.id = vum.user_id
JOIN cmp_vendor cv ON vum.vendor_id = cv.id
SET cu.active_status = 1 , cv.active_status = 1
WHERE cu.status = 1 AND cv.id = $id";

            if ($db->query($ActiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "User activated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to activate user, please try again later.";
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
    public function userdeactive($loginData, $data)
    {
        try {
            $id = $data[3];
            $db = $this->dbConnect();
            if (empty($data[3])) {
                throw new Exception("Bad request");
            }
            $db = $this->dbConnect();
            $checkIdQuery = "SELECT COUNT(*) AS count FROM cmp_vendor WHERE id = $id AND active_status=1 AND status=1";
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
            $deactiveQuery = "UPDATE cmp_users cu
JOIN cmp_vendor_user_mapping vum ON cu.id = vum.user_id
JOIN cmp_vendor cv ON vum.vendor_id = cv.id
SET cu.active_status = 0 , cv.active_status = 0
WHERE cu.status = 1 AND cv.id = $id";

            if ($db->query($deactiveQuery) === true) {
                $db->close();
                $statusCode = "200";
                $statusMessage = "User Deactivated successfully.";
            } else {
                $statusCode = "500";
                $statusMessage = "Unable to Deactivate user, please try again later.";
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

    private function getTotalCount($loginData)
    {
        try {
            $db = $this->dbConnect();
            $sql = "SELECT * FROM cmp_vendor WHERE status = 1 ";
            // -- AND created_by = " . $loginData['user_id'] . "";
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
