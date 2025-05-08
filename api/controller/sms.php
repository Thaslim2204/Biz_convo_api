<?php
require_once "model/sms.php";
require_once "model/login.php";

class SMS extends SMSMODEL
{

    public function smsCtrl($request, $tokenParms)
    {
        // print_r($data);exit;
        try {

            $loginAuthendicate = new LOGINMODEL();
            $token = $loginAuthendicate->tokenCheck($tokenParms);

            if (!empty($token)) {
                $response = $this->processList($request, $token);
                echo $this->json($response);
                exit();
            } else {
                throw new Exception("Unauthorized login");
            }
        } catch (Exception $e) {
            echo $this->json(array(
                "result" => "401",
                "message" => $e->getMessage(),
            ));
            exit();
        }
    }
}

$classActivate = new SMS();
$classActivate->smsCtrl($data, $token);
