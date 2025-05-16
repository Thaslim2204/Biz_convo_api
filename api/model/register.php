<?php
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";
class REGISTERMODEL extends APIRESPONSE
{
    private function processMethod($data)
    {

        switch (REQUESTMETHOD) {
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                $result = $this->VendorsuperadminRegistration($data);
                return $result;
            default:
                $result = $this->handle_error($data);
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
     * Post/Register Member
     *
     * @param array $data
     * @return multitype:string
     */

    private function VendorsuperadminRegistration($data)
    {

        try {
            $db = $this->dbConnect();

            // Validate input data
            $validationData = array(
                "Company Name" => $data['company_name'],
                "Type" => $data['type'],
                "Address" => $data['address'],
                "Phone" => $data['phone'],
                "First Name" => $data['userData']['first_name'],
                "Last Name" => $data['userData']['last_name'],
                "User Name" => $data['userData']['username'],
                "Email ID" => $data['userData']['email_id'],
                "Password" => $data['userData']['password']
            );

            $this->validateInputDetails($validationData);

            $userData = $data['userData'];

            $password = $userData['password'];

            if ($userData['password'] != $userData['confirmPassword']) {
                throw new Exception("Password & Confirm Password are not correct!");
            }

            $sql = "SELECT id FROM cmp_users WHERE email = '" . $userData['email_id'] . "'  AND status = 1";
            $result = mysqli_query($db, $sql);
            $row_cnt = mysqli_num_rows($result);
            if ($row_cnt > 0) {
                throw new Exception("User already exist");
            }
            $companysql = "SELECT id FROM cmp_vendor WHERE name = '" . $data['company_name'] . "'  AND status = 1";
            $result = mysqli_query($db, $companysql);
            $row_cnt = mysqli_num_rows($result);
            if ($row_cnt > 0) {
                throw new Exception("Your compnay has been already registered");
            }
            $hashed_password = hash('sha256', hash('sha256', $password));
            //uniquer user id
            $uid = bin2hex(random_bytes(8));


            // Insert into cmp_users table
            $insertUserQuery = "INSERT INTO cmp_users (uid, first_name, last_name, username, email, password, mobile)
        VALUES ('" . $uid . "', '" . $userData['first_name'] . "', '" . $userData['last_name'] . "', '" . $userData['username'] . "', '" . $userData['email_id'] . "', '" . $hashed_password . "', '" . $userData['phone'] . "')";
            if ($db->query($insertUserQuery) === true) {
                $lastInsertedId = mysqli_insert_id($db);

                // Update user role if needed
                $this->updateUserRole($lastInsertedId);

                // Insert into cmp_vendor table
                $vendorUid   = bin2hex(random_bytes(8));
                $createdDate = date('Y-m-d H:i:s');
                $insertVendorQuery = "INSERT INTO cmp_vendor (uid, name, type, address, phone, email, created_by, created_date)
              VALUES ('" . $vendorUid . "', '" . $data['company_name'] . "', '" . $data['type'] . "', '" . $data['address'] . "', '" . $data['phone'] . "', '" . $userData['email_id'] . "', '" . $lastInsertedId . "', '" . $createdDate . "')";
                if ($db->query($insertVendorQuery) === true) {
                    $vendorId = mysqli_insert_id($db);

                    // Insert into cmp_vendor_mapping table
                    $insertMappingQuery = "INSERT INTO cmp_vendor_user_mapping (vendor_id, user_id,created_by,created_date)
                   VALUES ('" . $vendorId . "', '" . $lastInsertedId . "','" . $lastInsertedId . "', '" . $createdDate . "')";
                    if (!$db->query($insertMappingQuery)) {
                        throw new Exception("Failed to insert vendor mapping: " . $db->error);
                    }
                } else {
                    throw new Exception("Failed to insert vendor: " . $db->error);
                }

                $db->close();
            } else {
                throw new Exception("User insertion failed: " . $db->error);
            }

            $resultArray = array(
                "apiStatus" => array(
                    "code"    => "200",
                    "message" => "Your registration has been submitted successfully"
                )
            );
            return $resultArray;
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code"    => "401",
                    "message" => $e->getMessage()
                ),
            );
        }
    }
    public function updateUserRole($lastInsertedId)
    {

        try {
            $db = $this->dbConnect();

            if ($lastInsertedId) {

                $insertQuery = "INSERT INTO cmp_user_role_mapping (`user_id`, `role_id`, `created_by`) VALUES ('$lastInsertedId', '2 ', '$lastInsertedId') ";
                //    print_r($insertQuery);exit;
                if ($db->query($insertQuery) === true) {
                    $db->close();
                    return true;
                }
                return false;
            } else {
                throw new Exception("Not able to update role");
            }
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "401",
                    "message" => $e->getMessage()
                ),
            );
        }
    }

    public function validateInputDetails($validationData)
    {
        foreach ($validationData as $key => $value) {
            if (empty($value) || trim($value) == "") {
                throw new Exception($key . " should not be empty!");
            }
        }
    }

    // Unautherized api request
    private function handle_error($request) {}
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
            $result1 = $this->response($responseData);
            return $responseData;
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
