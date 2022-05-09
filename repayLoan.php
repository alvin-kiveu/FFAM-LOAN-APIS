<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php';
    $ResultCode = '';
    $EncodeData = file_get_contents('php://input');
    $submitedData = json_decode($EncodeData, true);
    $linenumber  = mysqli_escape_string($db, $submitedData['linenumber']);
    $amountOfAmount  = mysqli_escape_string($db, $submitedData['amountOfAmount']);

    $user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM aminia_users WHERE phone='$linenumber'"));
    $balance = $user['balance'];
    if ($balance  > 0) {
        if ($amountOfAmount  <= $balance) {
            $newBalance = $balance - $amountOfAmount;
            mysqli_query($db, "UPDATE aminia_users SET balance='$newBalance' WHERE phone='$linenumber'");

            $getLoan =  mysqli_query($db, "SELECT * FROM aminia_loan WHERE phonenumber='$linenumber' AND loanStatus='Active'");
            $loan = mysqli_fetch_array($getLoan);
            $loanRepaid = $loan['repaid'];
            $newAmountOfAmount =  $loanRepaid  + $amountOfAmount;
            mysqli_query($db, "UPDATE aminia_loan SET repaid='$newAmountOfAmount' WHERE phonenumber='$linenumber' AND loanStatus='Active'");
            if ($newBalance == 0) {
                mysqli_query($db, "UPDATE aminia_loan SET loanStatus='complete' WHERE phonenumber='$linenumber'");
            }
            $messagesent = "AMINIA FARM LOAN APPLICATION You have repaid  Ksh $amountOfAmount of your loan your loan balnce is $newBalance";
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
            $massage = "Loan has been repaid successfuly";
            $response = array(
                'ResultCode' => $ResultCode,
                'massage' => $massage,
            );
        } else {
            $ResultCode = "Error";
            $massage = "Please pay the required amount of Ksh $balance";
            $response = array(
                'ResultCode' => $ResultCode,
                'errorMessage' => $massage
            );
        }
    } else {
        $ResultCode = "Error";
        $massage = "You have no loan to repay";
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