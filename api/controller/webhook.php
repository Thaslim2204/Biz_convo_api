<?php
// Include Deals Model
require_once "model/login.php";
require_once "model/webhook.php";

class WEBHOOK extends WEBHOOKMODEL
{
    public function lloginCtrl($data, $tokenParms)
    {
        // print_r($tokenParms);exit;
        try {

            // $response = $this->processList($data, $token);
            // echo $this->json($response);

            $method = $_SERVER["REQUEST_METHOD"];
            //  Handle GET request for Facebook webhook verification
            if ($method === "GET") {
                // Log the request
                file_put_contents("webhook_log.txt", "Verification Request in controller: " . date("Y-m-d H:i:s") . print_r($_GET, true) . "\n", FILE_APPEND);
                $this->verifyWebhook();
            }

            //  Handle POST request for webhook events

            // print_r("thhasasas");exit;
            if ($method === "POST") {

                // echo"Webhook request received\n";exit;
                file_put_contents("webhook_log.txt", date("Y-m-d H:i:s") . " - " . json_encode($data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);
                // print_r($token);exit;
                
                    // $this->processWebhookData($token);
                    $this->processWebhookData();
                }
                // Return 404 for unsupported requests
                http_response_code(404);
                echo "Invalid request";
                exit;
                // Object for Login Model
            //     $loginAuthendicate = new LOGINMODEL();

            //     // Token check for all the service
            //     $token = $loginAuthendicate->tokenCheck($tokenParms);
            //     //   print_r($data);exit;
            //     if (!empty($token)) {
            // } else {
            //     throw new Exception("Unauthorized Login");
            // }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
// Initiate controller & Response method
$classActivate = new WEBHOOK();

// Reponse for the request
$classActivate->lloginCtrl($data, $token);
