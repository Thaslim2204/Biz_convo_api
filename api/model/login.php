<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";


class LOGINMODEL extends APIRESPONSE
{
    private function processMethod($data)
    {
        $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

        switch (REQUESTMETHOD) {
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);

                $result = $this->loginCheck($data);
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

    /**
     * Get Login Authendication
     *
     * @return multitype:
     */
    private function loginCheck($request)
    {

        try {
            if (empty($request['loginType'])) {
                throw new Exception("Please select login Type");
            } else if (empty($request['email_id'])) {
                throw new Exception("Please give the User Name");
            } else if (empty($request['password'])) {
                throw new Exception("Please give the Password");
            }

            $db = $this->dbConnect();
            if ($request['loginType'] === "vendor") {
                $email = $request['email_id'];

                // Get user ID
                $checkUserQuery = "SELECT id FROM cmp_users WHERE email = '$email' AND status = 1 AND active_status = 1";
                // print_r($checkUserQuery);exit;
                $result = $db->query($checkUserQuery);
                $user = mysqli_fetch_assoc($result);

                if (!$user) {
                    throw new Exception("User not found or inactive.");
                }

                $userId = $user['id'];

                // vendor id for user id

                $sql = "SELECT vendor_id FROM cmp_vendor_user_mapping WHERE user_id = $userId";
                $result = $db->query($sql);
                $vendor_id = $result->fetch_assoc()['vendor_id'];
                if (empty($vendor_id)) {
                    throw new Exception("Vendor ID not found for user ID: $userId");
                }
                //check if vendor is active
                $checkVendorQuery = "SELECT status FROM cmp_vendor WHERE id = '$vendor_id' AND status = 1 AND active_status = 1";
                $vendorResult = $db->query($checkVendorQuery);
                if ($vendorResult && mysqli_num_rows($vendorResult) > 0) {
                    $vendorData = mysqli_fetch_assoc($vendorResult);
                    if ($vendorData['status'] != 1) {
                        throw new Exception("Vendor is inactive.");
                    }
                } else {
                    throw new Exception("Vendor not found or inactive.");
                }

                // Check user role
                $checkRoleQuery = "SELECT role_id FROM cmp_user_role_mapping WHERE user_id = '$userId' AND status = 1";
                $roleResult = $db->query($checkRoleQuery);

                if ($roleResult && mysqli_num_rows($roleResult) > 0) {
                    $roleData = mysqli_fetch_assoc($roleResult);

                    if ($roleData['role_id'] == 1) { // Super admin not allowed for vendor login
                        throw new Exception("Super Admin is not allowed to login as Vendor.");
                    }
                }

                //Get vendor details
                $query = "SELECT 
                            u.first_name, u.last_name, u.username, u.email, u.password, u.id, 
                            urm.role_id, cmr.role_name 
                          FROM cmp_users u 
                          JOIN cmp_user_role_mapping urm ON urm.user_id = u.id 
                          JOIN cmp_mst_role cmr ON cmr.role_id = urm.role_id 
                          WHERE u.email = '$email' AND u.status = 1 AND u.active_status = 1";
                // print_r($query);exit;
                //  }
                //   elseif ($request['loginType'] === "super_admin") {
                //     $query = "SELECT u.first_name,u.last_name,u.username, u.password, u.id,urm.role_id,rl.role_name FROM cmp_users u JOIN cmp_user_role_mapping urm ON urm.user_id = u.id JOIN cmp_mst_role rl ON urm.id = rl.role_id   WHERE
                //      u.email = '" . $request['email_id'] . "' AND rl.role_name='" . $request['loginType'] . "'
                //     AND u.status = 1";
                // print_r($query);exit;


            } else {
                throw new Exception("Your Login has not actiated.");
            }
            $result = $db->query($query);
            // print_r($query);exit;
            if ($result) {

                $row_cnt = mysqli_num_rows($result);
                if ($row_cnt > 0) {
                    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $hash = hash('sha256', hash('sha256', $request['password']));
                    // print_r($hash);
                    // print_r($data['password']);exit;
                    if ($hash != $data['password']) {
                        throw new Exception("Invalid password");
                    }
                } else {
                    throw new Exception("Invalid Username Or password");
                }
            } else {
                throw new Exception("Invalid Username Or password");
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
            // print_r($data);exit;
            if ($row_cnt < 1) {
                throw new Exception("Unauthorized Login");
            }
            // print_r($data);exit;
            return $data;
        } catch (Exception $e) {
            $this->loginLogCreate($e->getMessage(), "", getcwd());
            throw new Exception($e->getMessage());
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
