<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php';
    $ResultCode = '';
    $EncodeData = file_get_contents('php://input');
    $submitedData = json_decode($EncodeData, true);
    $fullnames  = mysqli_escape_string($db, $submitedData['filenames']);
    $phonenumber = mysqli_escape_string($db, $submitedData['linenumber']);
    $nationality = mysqli_escape_string($db, $submitedData['nationality']);
    $nationalId = mysqli_escape_string($db, $submitedData['nationalId']);
    $bank = mysqli_escape_string($db, $submitedData['bank']);
    $bankAccount = mysqli_escape_string($db, $submitedData['bankAccount']);
    $passwordsent = mysqli_escape_string($db, $submitedData['password']);



    $confirmPassword = mysqli_escape_string($db, $submitedData['conformists']);
    if (strlen($phonenumber)  == 10) {
        $check_phone = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM aminia_users WHERE phone='$phonenumber'"));
        if (isset($check_phone['phone'])) {
            $ResultCode = "Error";
            $massage = "The  phone number enterd is already registered";
            $response = array(
                'ResultCode' => $ResultCode,
                'errorMessage' => $massage
            );
        } else {
            $password1 = md5($passwordsent);
            $password = '$%&£~f052gf#9*\,bchj' . $password1 . 'hjklzxcvbnm!*%&^%$£@';
            $registration = mysqli_query($db, "INSERT INTO aminia_users(fullnames,phone,nationality,nationalId,bank,bankAccount,password) VALUE('$fullnames','$phonenumber','$nationality','$nationalId','$bank','$bankAccount','$password')");
            $messagesent = "Welcome  $fullnames  to Aminia Farm Loan Application";
            $phone =  '254' . (int)$phonenumber;
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

            $response = curl_exec($curl);
            $ResultCode = "Success";
            $massage = "Registration successfully";
            $response = array(
                'ResultCode' => $ResultCode,
                'message' => $massage
            );
        }
    } else {
        $ResultCode = "Error";
        $massage = "Invalid phone number format";
        $response = array(
            'ResultCode' => $ResultCode,
            'errorMessage' => $massage
        );
    }
} else {
    $ResultCode = "Error";
    $massage = "Invalid Request method";

    $response = array(
        'ResultCode' => $ResultCode,
        'errorMessage' => $massage
    );
}

if (!$ResultCode == '') {
    echo json_encode($response);
}