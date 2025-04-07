<?php
// Include Deals Model
require_once "model/superadmin.php";

class SUPERLOGIN extends SUPERADMINLOGINMODEL
{
    public function SuperadminloginCtrl($data, $tokenParms)
    {
        try {
            // Extracting action type from request data
            $endpoint = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

            if (strpos($endpoint, 'superadmin/login') !== false) {
                // Allow login without token
                $response = $this->processList($data, null);
                echo $this->json($response);
                exit();
            }
            
            // Token is required for any other action
            $token = $this->tokenCheck($tokenParms);
            if (empty($token)) {
                throw new Exception("Unauthorized Access: Token Required");
            }
            
            $response = $this->processList($data, $token);
            echo $this->json($response);
            exit();
        } catch (Exception $e) {
            echo $this->json([
                "result" => "401",
                "message" => $e->getMessage(),
            ]);
            exit();
        }
    }
}

// Initiate controller & Response method
$classActivate = new SUPERLOGIN();

// Response for the request
$classActivate->SuperadminloginCtrl($data, $token);
