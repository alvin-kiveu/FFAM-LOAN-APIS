<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php';
    $ResultCode = '';
    $EncodeData = file_get_contents('php://input');
    $submitedData = json_decode($EncodeData, true);
    $linenumber  = mysqli_escape_string($db, $submitedData['linenumber']);
    $typeOfFarming  = mysqli_escape_string($db, $submitedData['typeOfFarming']);
    $periodOfFarming = mysqli_escape_string($db, $submitedData['periodOfFarming']);
    $scaleOfFarming = mysqli_escape_string($db, $submitedData['scaleOfFarming']);
    $product = mysqli_escape_string($db, $submitedData['product']);
    $amount = mysqli_escape_string($db, $submitedData['amount']);
    $paymentMethod = mysqli_escape_string($db, $submitedData['paymentMethod']);
    $paymentDuration = mysqli_escape_string($db, $submitedData['paymentDuration']);
    $actionTaken = mysqli_escape_string($db, $submitedData['actionTaken']);
    $amountWithIntrest = $amount + ($amount * 0.03);

    //Check if there is an exixting loan
    $checkLoan = mysqli_query($db, "SELECT * FROM aminia_loan WHERE phonenumber='$linenumber' AND loanStatus='Active'");
    if (mysqli_num_rows($checkLoan) > 0) {
        $response = curl_exec($curl);
        $ResultCode = "Error";
        $massage = "You have an exiting loan, please repay!!!";
        $response = array(
            'ResultCode' => $ResultCode,
            'errorMessage' => $massage
        );
    } else {
        mysqli_query($db, "INSERT INTO aminia_loan(phonenumber,typeOfFarming,periodOfFarming,scaleOfFarming,product,amount,paymentMethod,paymentDuration,actionTaken,amountWithIntrest,loanStatus) VALUES('$linenumber','$typeOfFarming','$periodOfFarming','$scaleOfFarming','$product','$amount','$paymentMethod','$paymentDuration','$actionTaken','$amountWithIntrest','Active')");
        mysqli_query($db, "UPDATE aminia_users SET balance='$amountWithIntrest' WHERE phone='$linenumber'");

        function generateConsumerSecret($length1 = 8)
        {
            $characters1 = 'ABCDEFGHIJKLMNOPQRST';
            $charactersLength1 = strlen($characters1);
            $randomString1 = '';
            for ($i1 = 0; $i1 < $length1; $i1++) {
                $randomString1 .= $characters1[rand(0, $charactersLength1 - 1)];
            }
            return $randomString1;
        }
        $gen =  generateConsumerSecret();

        $trandactionId = "QE" . $gen;

        $messagesent = "$trandactionId Confirmed you have recived Ksh  $amountWithIntrest form AMINIA FARM LOAN APPLICATION";
        $phone  = '254' . (int)$linenumber;
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
        $response = curl_exec($curl);
        $ResultCode = "Success";
        $massage = "Loan application is a success";
        $response = array(
            'ResultCode' => $ResultCode,
            'massage' => $massage,
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