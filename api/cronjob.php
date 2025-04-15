<?php
// cronjob.php

include 'include/dbConnection.php';
require_once "include/apiResponseGenerator.php";
require_once "include/dbConnection.php";
// require_once 'model/whatsapp_template.php';
class CampaignScheduler
{
    // print_r($loginData);exit;    

    public $db;
    private $filePath = "file.txt";
    private $wt;


    private function dbConnect()
    {
        $conn = new DBCONNECTION();
        $db = $conn->connect();
        return $db;
    }

    private $facebook_base_url;
    private $facebook_base_version;
    private $whatsapp_business_id;
    private $phone_no_id;
    private $fb_auth_token;
    private $facebook_app_id;

    // Initiate FB Credentials
    private function fbCredentials($loginData)
    {
        // print_r($loginData);
        $db = $this->dbConnect();

        $this->facebook_base_url = "https://graph.facebook.com";
        $this->facebook_base_version = "v22.0";

        //get private tokens from DB
        $sql = "SELECT whatsapp_business_acc_id, phone_no_id, access_token , app_id from cmp_vendor_fb_credentials where vendor_id = '1' and status = 1";
        $result = $db->query($sql);
        $fbData = mysqli_fetch_assoc($result);
        // print_r($fbData);exit;

        if ($fbData) {
            $this->whatsapp_business_id = $fbData['whatsapp_business_acc_id'];
            $this->phone_no_id = $fbData['phone_no_id'];
            $this->fb_auth_token = $fbData['access_token'];
            $this->facebook_app_id = $fbData['app_id'];
        } else {
            throw new Exception("Failed to fetch Facebook credentials from the database.");
        }
    }

    public function run()
    {
        if (!file_exists($this->filePath)) {
            echo "📂 file.txt not found. Nothing to send.\n";
            return;
        }

        $this->checkAndSend();
    }

    private function checkAndSend()
    {
        $db = $this->dbConnect();
        $data = file_get_contents($this->filePath);

        // Extract parts using regex
        preg_match('/CampaignID:\s*(\d+)/', $data, $matchCampaign);
        preg_match('/Timezone:\s*([^\|]+)/', $data, $matchTimezone);
        preg_match('/ScheduleAt:\s*([^\|]+)/', $data, $matchSchedule);
        preg_match('/Status:\s*(\w+)/', $data, $matchStatus);

        $campaignId = isset($matchCampaign[1]) ? trim($matchCampaign[1]) : null;
        $zoneName = isset($matchTimezone[1]) ? trim($matchTimezone[1]) : "Asia/Kolkata";
        $scheduleAt = isset($matchSchedule[1]) ? trim($matchSchedule[1]) : null;
        $status = isset($matchStatus[1]) ? trim($matchStatus[1]) : null;

        if (!$campaignId || !$zoneName || !$scheduleAt) {
            echo "❌ Invalid data format in file.txt\n";
            return;
        }

        // 🔍 Get timezone ID from DB
        $timeZoneId = null;
        $zoneQuery = "SELECT id FROM cmp_mst_timezone WHERE timezone_name = '$zoneName'";
        $zoneResult = $db->query($zoneQuery);
        if ($zoneResult && $zoneResult->num_rows > 0) {
            $zoneRow = $zoneResult->fetch_assoc();
            $timeZoneId = $zoneRow['id'];
        } else {
            echo "Zone '$zoneName' not found in cmp_mst_timezone. Using ID = 0\n";
            $timeZoneId = 0;
        }

        // 🕒 Time check
        $scheduleTime = strtotime($scheduleAt);
        $currentTime = time();
        $diff = $scheduleTime - $currentTime;

        if ($diff <= 240 && $diff >= 0) {
            // ✉️ Send Message Logic
            $query = "
                SELECT t.template_id AS templateId,
                       c.title,
                       c.group_id, c.restrictLangCode,
                       c.schedule_at AS scheduledAt,
                       t.body_data,
                       gc.group_name
                  FROM cmp_campaign c
            JOIN cmp_whatsapp_templates t ON c.template_id = t.id       
            JOIN cmp_group_contact gc ON c.group_id=gc.id
            LEFT JOIN cmp_campaign_variable_mapping AS cvm ON c.id = cvm.campaign_id
            WHERE c.id = $campaignId
            ";
            $result = $db->query($query);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $templateId = $row['templateId'];
                $bodyData = $row['body_data'];
                $groupId = $row['group_id'];
                $title = $row['title'];
                $scheduledAt = $row['scheduledAt'];
                $restrictLangCode = $row['restrictLangCode'];
                $groupName = $row['group_name'];
                $isRestricted = ($restrictLangCode == 1);

                // 🔍 Variable Mapping
                $varQuery = "
                    SELECT 
                        cvm.type, 
                        cvm.variable_type_id AS varName, 
                        cvm.variable_value AS varTypeId,
                        mv.variable_name AS varTypeName 
                    FROM cmp_campaign_variable_mapping cvm
                    LEFT JOIN cmp_mst_variable mv ON cvm.variable_value = mv.id
                    WHERE cvm.campaign_id = $campaignId 
                        AND cvm.template_id = $templateId 
                        AND cvm.group_id = $groupId
                ";
                $varResult = $db->query($varQuery);
                $variableMap = [];
                if ($varResult && $varResult->num_rows > 0) {
                    while ($varRow = $varResult->fetch_assoc()) {
                        $type = $varRow['type'];
                        $varName = $varRow['varName'];
                        $varTypeId = $varRow['varTypeId'];
                        $varTypeName = $varRow['varTypeName'];

                        if (!isset($variableMap[$type])) {
                            $variableMap[$type] = [];
                        }
                        $variableMap[$type][] = [
                            "varName" => $varName,
                            "varValue" => [
                                "varTypeName" => $varTypeName,
                                "varTypeId" => $varTypeId
                            ]
                        ];
                    }
                }

                $variableIds = [];
                foreach ($variableMap as $type => $variables) {
                    $variableIds[] = [
                        "type" => $type,
                        "variables" => $variables
                    ];
                }

                // 🧱 Build data
                $dataToSend = [
                    "templateId" => $templateId,
                    "group" => [
                        "groupId" => $groupId,
                        "groupName" => $groupName
                    ],
                    "title" => $title,
                    "restrictLangCode" => $isRestricted,
                    "scheduleStatus" => true,
                    "timezone" => [
                        "id" => $timeZoneId,
                        "zoneName" => $zoneName
                    ],
                    "scheduledAt" => $scheduledAt,
                    "SendNum" => "",
                    "variableIds" => $variableIds
                ];

                // ✅ Send the message
                $sendStatus = $this->sendMessage($dataToSend, $campaignId, $templateId);

                if ($sendStatus['apiStatus']['code'] === '200') {
                    // ✅ Update send_status to 'sent' in cmp_campaign
                    $updateStatusSql = "UPDATE cmp_campaign SET send_status = 'Executed' WHERE id = '$campaignId'";
                    if (!$db->query($updateStatusSql)) {
                        echo "⚠️ Failed to update send_status in cmp_campaign: " . $db->error . "\n";
                    } else {
                        echo "📌 send_status updated to 'sent' for campaign ID $campaignId\n";
                    }

                    // Remove current block from file.txt
                    $data = file_get_contents($this->filePath);
                    $pattern = "/CampaignID:\s*{$campaignId}\s*\|.*?Status:\s*\w+\s*/s";
                    $updatedData = preg_replace($pattern, '', $data);
                    file_put_contents($this->filePath, trim($updatedData));

                    echo "✅ Message sent successfully. CampaignID $campaignId removed from file.txt\n";
                } else {
                    echo "❌ Failed to send message for campaign ID $campaignId.\n";
                }
            } else {
                echo "❌ No campaign/template data found for ID $campaignId.\n";
            }
        } elseif ($diff < 0) {
            // ⌛ Schedule expired
            echo "⚠️ Scheduled time passed for CampaignID $campaignId. Removing from file.\n";
            $data = file_get_contents($this->filePath);
            $pattern = "/CampaignID:\s*{$campaignId}\s*\|.*?Status:\s*\w+\s*/s";
            $updatedData = preg_replace($pattern, '', $data);
            file_put_contents($this->filePath, trim($updatedData));
        } else {
            echo "🕒 Not time yet. ($diff seconds left)\n";
        }
    }

    private function getUsingCampCredentials($data, $loginData)
    {
        try {

            $groupID = $data['group']['groupId'];
            $db = $this->dbConnect();
            // Fetch group details with user-provided group name
            $queryService = "SELECT 
            gc.id AS groupId,
            gc.group_name AS groupName,
            gc.active_status AS activeStatus,
            c.id AS contactId,
            c.first_name AS firstName,
            c.last_name AS lastName,
            c.mobile,
            c.email,
            c.country,
            c.language_code
        FROM cmp_group_contact_mapping AS gcm
        LEFT JOIN cmp_group_contact AS gc ON gc.id = gcm.group_id
        LEFT JOIN cmp_contact AS c ON c.id = gcm.contact_id
        WHERE gc.status = 1 
            AND gcm.status = 1
            AND c.status = 1
            AND gc.active_status = 1  
            AND gc.id = $groupID 
        ORDER BY gc.id DESC";
            $result = $db->query($queryService);
            $rowCount = mysqli_num_rows($result);

            if ($rowCount > 0) {
                $contacts = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $contacts[] = $row;
                }

                // Fetch the template dynamically
                $template_id = $data['templateId'];
                $templateResponse = $this->templateByID(["", "", $template_id], $loginData);
                $templateData = $templateResponse['result'];
                // print_r(($templateResponse));exit;
                // Return contacts along with template
                return [
                    "apiStatus" => [
                        "code" => "200",
                        "message" => "Contacts and template fetched successfully",
                    ],
                    "result" => [
                        "contacts" => $contacts,
                        "template" => $templateData,
                    ]
                ];
            } else {
                throw new Exception("No contacts found for the provided group.");
            }
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage(),
                ],
            ];
        }
    }
    public function templateByID($data, $loginData)
    {
        try {
            // print_r($data);exit;
            $template_id = $data[2];
            if (empty($template_id)) {
                throw new Exception("Give template id");
            }

            $this->fbCredentials($loginData);

            $url =  $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $template_id . '?' . "access_token=$this->fb_auth_token";
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->fb_auth_token . ''
                ),
            ));

            $response = curl_exec($curl);
            $responseData = json_decode($response, true);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // echo $httpCode;
            // exit;
            curl_close($curl);
            // echo $response;exit;

            $db = $this->dbConnect();
            $sql = "SELECT id, template_id, media_id, created_date, updated_date from cmp_whatsapp_templates where status = 1 and template_id = '" . $template_id . "' ";
            $result = $db->query($sql);
            $dbData = $result->fetch_assoc();
            // print_r($sql);exit;
            $responseData['created_date'] = $dbData['created_date'] ?? null;
            $responseData['updated_date'] = $dbData['updated_date'] ?? null;
            $responseData['media_id'] = $dbData['media_id'] ?? null;

            if ($httpCode == "200") {
                return array(
                    "apiStatus" => array(
                        "code" => "200",
                        "message" => "Whatsapp template successfully listed",
                    ),
                    "result" => $responseData
                );
            } else {
                // echo $response;
                return array(
                    "apiStatus" => array(
                        "code" => $httpCode,
                        "message" => "Error occured!",
                    ),
                    "result" => json_decode($response)
                );
            }
        } catch (Exception $e) {
            return array(
                "apiStatus" => array(
                    "code" => "500",
                    "message" => $e->getMessage(),
                )
            );
        }
    }

    // private function fetchTemplateByID($template_id, $loginData)
    // {
    //     // Example implementation for fetching a template by ID
    //     $db = $this->dbConnect();
    //     $query = "SELECT * FROM cmp_whatsapp_templates WHERE id = $template_id";

    //     $result = $db->query($query);
    //     // print_r($result); exit;

    //     if ($result && $result->num_rows > 0) {
    //         return [
    //             "apiStatus" => [
    //                 "code" => "200",
    //                 "message" => "Template fetched successfully",
    //             ],
    //             "result" => $result->fetch_assoc(),
    //         ];
    //     } else {
    //         return [
    //             "apiStatus" => [
    //                 "code" => "404",
    //                 "message" => "Template not found",
    //             ],
    //         ];
    //     }
    // }

    public function sendMessage($data, $loginData, $campaign_id)
    {
        try {
            // print_r("json_encode");
            // print_r(json_decode($data));exit;
            // $db = $this->dbConnect();
            // Fetch contacts and template dynamically
            $fetchResponse = $this->getUsingCampCredentials($data, $loginData);
            // print_r($fetchResponse);exit;
            // Check if the API response was successful
            if ($fetchResponse['apiStatus']['code'] != "200") {
                return $fetchResponse; // If there's an error in fetching contacts/template, return the error response.
            }

            // Get contacts and template from the response
            $contacts = $fetchResponse['result']['contacts'];  // List of contacts
            $template = $fetchResponse['result']['template'];  // Template data

            ;
            // Initialize arrays to store success and failure recipients
            $successRecipient = [];
            $failureRecipient = [];
            $responseArray = []; // To store the response from each individual request

            // Loop through each contact and send the WhatsApp message
            foreach ($contacts as $contact) {
                // Prepare dynamic components based on the template and the contact data
                $dynamicComponents = $this->prepareDynamicComponents($template['components'], $contact, $data['variableIds'], $template['media_id']);


                // Build the message body
                $body = [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $contact['mobile'],  // The mobile number of the contact
                    'type' => 'template',
                    'template' => [
                        'name' => $template['name'],  // Template name
                        'language' => [
                            'code' => $template['language'],  // Use the contact's language code
                        ],
                        'components' => $dynamicComponents,  // Template components with dynamic data
                    ],
                ];
                // $insertcampaign = "Insert into cmp_campaign_contact(campaign_id,contact_id,created_by,created_date) values('" . $campaign_id . "','" . $contact['contactId'] . "','" . $loginData['user_id'] . "',now())";
                // $db = $this->dbConnect();
                // $db->query($insertcampaign);
                // Initialize cURL request to send the message
                $curl = curl_init();
                $url = $this->facebook_base_url . '/' . $this->facebook_base_version . '/' . $this->phone_no_id . '/' . "messages";
                // print_r( json_encode($body));exit;
                curl_setopt_array($curl, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($body),
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $this->fb_auth_token,
                    ],
                ]);

                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                // Add the response for this contact
                $responseArray[] = json_decode($response, true); // Storing each response as an associative array

                // Check if the request was successful
                if ($httpCode == 200) {
                    $successRecipient[] = $contact['mobile'];
                } else {
                    $failureRecipient[] = $contact['mobile'];
                }
            }

            // Prepare response data for success and failure
            $responses = [
                "apiStatus" => [
                    "code" => "200",
                    "message" => "WhatsApp template message executed successfully",
                ],
                "result" => [
                    "successRecipients" => $successRecipient,
                    "failureRecipients" => $failureRecipient,
                    "apiResponse" => $responseArray, // Return array of responses for all contacts
                ],
            ];

            return $responses;
        } catch (Exception $e) {
            return [
                "apiStatus" => [
                    "code" => "401",
                    "message" => $e->getMessage(),
                ],
            ];
        }
    }
    private function prepareDynamicComponents($templateComponents, $contact, $variableIds, $mediaId)
    {
        // print_r((json_encode($mediaId)));
        // exit;

        // print_r($variableIds['body']);exit;
        $dynamicComponents = [];
        $headerHasVariable = false;
        // print_r(json_encode($templateComponents));exit;
        // Loop through each component to adjust dynamically based on the type and contact data
        foreach ($templateComponents as $component) {
            // Debugging: Check the component structure
            // print_r(json_encode($component));  // This will print the current component before processing

            switch ($component['type']) {
                case 'HEADER':
                    // If header contains text (replace placeholders like {{1}}, {{2}} etc.)
                    if (isset($component['format']) == 'TEXT' && isset($component['example']['header_text'])) {
                        // Replace placeholders in header text dynamically
                        // echo "csdkf";exit;

                        // print_r(json_encode($variableIds));
                        $headerHasVariable = true;
                        $dynamicComponents[] = $this->prepareHeaderTextComponent($component, $contact, $variableIds);
                    } else if ($component['format'] != 'TEXT') {
                        // echo "csdkf";exit;

                        $dynamicComponents[] = $this->prepareHeaderMediaComponent($component, $contact, $mediaId);
                    }
                    break;

                case 'BODY':

                    // print_r(json_encode($component));exit;
                    // Only add the body component if there is dynamic text to replace
                    if (isset($component['text']) && isset($component['example']['body_text'])) {
                        $dynamicComponents[] = $this->prepareBodyComponent($component, $contact, $variableIds, $headerHasVariable);
                    }
                    break;

                case 'FOOTER':
                    // Process the footer text if it exists
                    if (isset($component['text']) && !empty($component['text'])) {
                        $dynamicComponents[] = $this->prepareFooterComponent($component, $contact);
                    }
                    break;

                    // case 'BUTTONS':
                    //     // Process buttons if they exist
                    //     if (isset($component['buttons']) && count($component['buttons']) > 0) {
                    //         $dynamicComponents[] = $this->prepareButtonComponent($component, $contact);
                    //     }
                    //     break;

                    // Add cases for any other custom component types here if needed
            }
        }

        // Debugging: Print final dynamic components before returning
        // print_r(json_encode($dynamicComponents));  // This will print the components array after processing
        return $dynamicComponents;
    }


    private function prepareHeaderTextComponent($component, $contact, $variableIds)
    {
        // exit;
        // print_r($variableIds);
        // exit;
        $dynamicParameters = [];
        preg_match_all('/\{\{(\d+)\}\}/', $component['text'], $matches); // Find placeholders

        // Check if header has a variable (header can only have 1 variable)
        if (count($matches[1]) == 1) {
            $varName = (int) $matches[1][0];  // Get the placeholder number

            // Find the corresponding variable in the header
            $variable = $this->findVariableByName($varName, $variableIds, true);  // Pass 'true' for header

            if ($variable) {
                $dynamicValue = $this->getDynamicValueForVariable($variable, $contact);

                // Add the dynamic value to the parameters
                $dynamicParameters[] = [
                    'type' => 'text',
                    'text' => $dynamicValue  // Replace {{1}} with the dynamic value
                ];
            }
        }

        return [
            'type' => 'HEADER',
            'parameters' => $dynamicParameters
        ];
    }


    private function prepareHeaderMediaComponent($component, $contact, $mediaId)
    {
        // print_r($component);exit;
        // Assuming the header media is an image (you can extend this for other media types like video)
        if (isset($component['format'])) {
            return [
                'type' => 'HEADER',
                'parameters' => [
                    [
                        'type' => $component['format'],
                        strtolower($component['format']) => [
                            // 'link' => $component['example']['header_handle'][0]
                            "id" => $mediaId
                        ]
                    ]
                ]
            ];
        }

        // You can extend this for other media types like videos
        // if (isset($component['parameters'][0]['video']['id'])) {
        //     return [
        //         'type' => 'HEADER',
        //         'parameters' => [
        //             [
        //                 'type' => 'video',
        //                 'video' => [
        //                     'id' => $component['parameters'][0]['video']['id']
        //                 ]
        //             ]
        //         ]
        //     ];
        // }

        return []; // Return an empty array if no valid media is found
    }


    private function prepareBodyComponent($component, $contact, $variableIds, $headerHasVariable)
    {
        $dynamicParameters = [];
        preg_match_all('/\{\{(\d+)\}\}/', $component['text'], $matches); // Find placeholders like {{1}}, {{2}}, etc.

        // Loop through all placeholders found in the body text
        foreach ($matches[1] as $index) {
            // Determine the variable name (this is the number from the placeholder, like 1, 2, etc.)
            $varName = (int) $index;

            // Find the corresponding variable in the body (multiple variables possible)
            $variable = $this->findVariableByName($varName, $variableIds, false);  // Pass 'false' for body

            if ($variable) {
                $dynamicValue = $this->getDynamicValueForVariable($variable, $contact);

                // Add the dynamic value to the parameters
                $dynamicParameters[] = [
                    'type' => 'text',
                    'text' => $dynamicValue  // Replace {{1}} with the dynamic value
                ];
            }
        }

        return [
            'type' => 'BODY',
            'parameters' => $dynamicParameters
        ];
    }




    /**
     * Find the variable from variableIds by its varName (like {{1}}, {{2}}, etc.)
     */
    private function findVariableByName($varName, $variableIds, $isHeader = false)
    {
        // Loop through the variableIds array
        // print_r(json_encode($variableIds));exit;
        foreach ($variableIds as $group) {
            // print_r(json_encode($group));exit;
            // Check if the group type is 'header' or 'body'
            if ($isHeader && $group['type'] == 'header') {
                // Find the variable in the header group
                foreach ($group['variables'] as $variable) {
                    if ($variable['varName'] == (string)$varName) {
                        return $variable;  // Return the variable from the header
                    }
                }
            } elseif (!$isHeader && $group['type'] == 'body') {
                // Find the variable in the body group
                foreach ($group['variables'] as $variable) {
                    if ($variable['varName'] == (string)$varName) {
                        return $variable;  // Return the variable from the body
                    }
                }
            }
        }

        return null;  // Return null if no variable found
    }




    /**
     * Get the dynamic value for a variable from the contact data
     */
    private function getDynamicValueForVariable($variable, $contact)
    {
        // print_r(json_encode($contact['lastName']));exit;
        // Fetch the dynamic value based on the variable type
        switch ($variable['varValue']['varTypeName']) {
            case 'Contact Full Name':
                return $contact['firstName'] . ' ' . $contact['lastName'];
            case 'Contact Last Name':
                return $contact['lastName'];
            case 'Contact First Name':
                return $contact['firstName'];
            case 'Contact Email':
                return $contact['email'];
            case 'Contact Phone':
                return $contact['mobile'];
            case 'Contact Country':
                return $contact['country'];
            case 'Contact Language':
                return $contact['language_code'];
            default:
                return '';
        }
    }


    /**
     * Prepare the BODY component with dynamic content (e.g., variables like {first_name}, etc.)
     */
    private function prepareFooterComponent($component, $contact)
    {
        $text = $this->replacePlaceholders($component['text'], $contact);  // Replace placeholders if needed

        return [
            'type' => 'FOOTER',
            'parameters' => [
                [
                    'type' => 'text',
                    'text' => $text,
                ]
            ]
        ];
    }

    /**
     * Prepare BUTTONS component with dynamic content
     */
    private function prepareButtonComponent($component, $contact)
    {
        $buttons = [];

        // Loop through the buttons and replace placeholders if necessary
        foreach ($component['buttons'] as $button) {
            if (isset($button['text'])) {
                // Replace placeholders in button text
                $button['text'] = $this->replacePlaceholders($button['text'], $contact);
            }

            // Add the button to the buttons array
            $buttons[] = [
                'type' => $button['type'],
                'text' => $button['text']
            ];
        }

        return [
            'type' => 'BUTTON',
            'parameters' => $buttons
        ];
    }


    /**
     * Replace placeholders in the template text with actual contact information
     */
    private function replacePlaceholders($text, $contact)
    {
        // Replace placeholders (e.g., {{1}}, {{2}}, etc.) with dynamic values from the contact
        $placeholders = [
            '{{1}}' => isset($contact['firstName']) ? $contact['firstName'] : '',  // Replace {{1}} with first name
            '{{2}}' => isset($contact['lastName']) ? $contact['lastName'] : '',   // Replace {{2}} with last name
            '{{3}}' => isset($contact['mobile']) ? $contact['mobile'] : '',       // Replace {{3}} with mobile number
            '{{4}}' => isset($contact['email']) ? $contact['email'] : '',         // Replace {{4}} with email
            // Add more placeholders as needed
        ];

        // Loop through all placeholders and replace them in the text
        foreach ($placeholders as $placeholder => $replacement) {
            $text = str_replace($placeholder, $replacement, $text);
        }

        return $text;
    }
}

// Run the scheduler
$scheduler = new CampaignScheduler();
$scheduler->run();
