<?php
// Include Deals Model
require_once "model/login.php";
require_once "model/webhook.php";

class WEBHOOK extends WEBHOOKMODEL
{
    public function loginCtrl($data, $token)
    {
        try {
            // $response = $this->processList($data, $token);
            // echo $this->json($response);
            $method = $_SERVER["REQUEST_METHOD"];
            //  Handle GET request for Facebook webhook verification
            if ($method === "GET") {
                // Log the request
                file_put_contents("webhook_log.txt", "Verification Request in controller: " .date("Y-m-d H:i:s") . print_r($_GET, true) . "\n", FILE_APPEND);
                $this->verifyWebhook();                
            }

            //  Handle POST request for webhook events
            if ($method === "POST") {
                // echo "calling";
                file_put_contents("webhook_log.txt", date("Y-m-d H:i:s") . " - " . json_encode($data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);
                $this->processWebhookData();
            }


            // Return 404 for unsupported requests
            http_response_code(404);
            echo "Invalid request";
            exit;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
// Initiate controller & Response method
$classActivate = new WEBHOOK();

// Reponse for the request
$classActivate->loginCtrl($data, $token);
