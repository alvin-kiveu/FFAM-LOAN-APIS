<?php
include 'config.php';
$linenumber = "0768168060";
$messagesent = "AMINIA FARM LOAN APPLICATION You have repaid  Ksh $amountOfAmount of your loan your loan balnce is $newBalance";
$phone  = "254768168060";
//send message
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.umeskiasoftwares.com/api/v1/sms',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
        "api_key":"SFhERkJLN0o6NzU0bTYxNGU=",
        "email":"alvo967@gmail.com",
        "Sender_Id": "23107",
        "message": "' .  $messagesent . '",
        "phone":"' . $phone . '"
      }',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
));