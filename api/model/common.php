<?php
// error_reporting(E_ALL);

require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";


class COMMONMODEL extends APIRESPONSE
{
    private function processMethod($data, $loginData)
    {

        switch (REQUESTMETHOD) {
            case 'GET':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "get") {
                    // $result = $this->getGroup($data, $loginData);
                } elseif ($urlParam[1] === 'variabledropdown') {
                    $result = $this->getVariabledropdown($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "countrydropdown") {
                    $result = $this->getCountrydropdown($data, $loginData);
                    return $result;
                } elseif ($urlParam[1] == "timezonedropdown") {
                    $result = $this->timeZonedropdown($data, $loginData);
                    return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                // return $result;
                break;
            case 'POST':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] === 'create') {
                    // $result = $this->createGroup($data, $loginData);
                    // return $result;
                } elseif ($urlParam[1] === 'list') {
                    // $result = $this->getGroupDetails($data, $loginData);
                    // return $result;
                } elseif ($urlParam[1] === 'payloadStructure') {
                    // // print_r($data);exit;
                    // $result = $this->getpayloadstructure($data, $loginData);
                    // return $result;
                } elseif ($urlParam[1] === 'groupbycontact') {
                    // $result = $this->getGroupByContactDetails($data, $loginData);
                    // return $result;
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                break;
            case 'PUT':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "update") {
                    // $result = $this->updateGroup($data, $loginData);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                // return $result;
                break;
            case 'DELETE':
                $urlPath = $_GET['url'];
                $urlParam = explode('/', $urlPath);
                if ($urlParam[1] == "delete") {
                    // $result = $this->deleteGroup($data);
                } else {
                    throw new Exception("Unable to proceed your request!");
                }
                // return $result;
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

    public function getCountrydropdown($data, $loginData)
    {
        try {
            $GroupData = array();
            $db = $this->dbConnect();

            // Get user_id from loginData
            $userId = $loginData['user_id'];

            $queryService = "SELECT id, name,iso_code,name_capitalized,iso3_code,iso_num_code,phone_code FROM cmp_mst_country  ";

            $result = $db->query($queryService);

            if (!$result) {
                throw new Exception("Database Query Failed: " . $db->error);
            }

            $row_cnt = mysqli_num_rows($result);

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $GroupData[] = $row;
            }  $responseArray = array(
                "totalRecordCount" => $row_cnt,
                "CountryData" => $GroupData,
            );

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Country Dropdown details fetched successfully",
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
    public function timeZonedropdown($data, $loginData)
    {
        try {
            $GroupData = array();
            $db = $this->dbConnect();

            // Get user_id from loginData
            $userId = $loginData['user_id'];

            $queryService = "SELECT id, timezone_name,utc_offset,location_name FROM cmp_mst_timezone  ";

            $result = $db->query($queryService);

            if (!$result) {
                throw new Exception("Database Query Failed: " . $db->error);
            }

            $row_cnt = mysqli_num_rows($result);

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $GroupData[] = $row;
            }  $responseArray = array(
                "totalRecordCount" => $row_cnt,
                "CountryData" => $GroupData,
            );

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Timezone Dropdown details fetched successfully",
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


    public function getVariabledropdown($data, $loginData)
    {
        try {
            $GroupData = array();
            $db = $this->dbConnect();

            // Get user_id from loginData
            $userId = $loginData['user_id'];

            // Query to get Group details based on user_id -> vendor_id -> store_id
            $queryService = "SELECT id, variable_name FROM cmp_mst_variable WHERE status = 1 ";

            $result = $db->query($queryService);

            if (!$result) {
                throw new Exception("Database Query Failed: " . $db->error);
            }

            $row_cnt = mysqli_num_rows($result);

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $GroupData[] = $row;
            }


            $responseArray = array(
                "totalRecordCount" => $row_cnt,
                "VariableDataDropDown" => $GroupData,
            );

            return array(
                "apiStatus" => array(
                    "code" => "200",
                    "message" => "Variable Dropdown details fetched successfully",
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
