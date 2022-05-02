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

    //Check if there is an exixting loan
    $checkLoan = mysqli_query($db, "SELECT * FROM aminia_loan WHERE phonenumber='$linenumber' AND loanStatus='Active'");
    if (mysqli_num_rows($checkLoan) > 0) {
        $ResultCode = "Error";
        $massage = "You have an exiting loan, please repay!!!";
        $response = array(
            'ResultCode' => $ResultCode,
            'errorMessage' => $massage
        );
    } else {
        mysqli_query($db, "INSERT INTO aminia_loan(phonenumber,typeOfFarming,periodOfFarming,scaleOfFarming,product,amount,paymentMethod,paymentDuration,actionTaken,loanStatus) VALUES('$linenumber','$typeOfFarming','$periodOfFarming','$scaleOfFarming','$product','$amount','$paymentMethod','$paymentDuration','$actionTaken','Active')");
        mysqli_query($db, "UPDATE aminia_users SET balance='$amount' WHERE phone='$linenumber'");
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