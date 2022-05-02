<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php';
    $ResultCode = '';
    $EncodeData = file_get_contents('php://input');
    $submitedData = json_decode($EncodeData, true);
    $linenumber  = mysqli_escape_string($db, $submitedData['linenumber']);


    //Check if there is an exixting loan
    $checkLoan = mysqli_query($db, "SELECT * FROM aminia_loan WHERE phonenumber='$linenumber' AND loanStatus='Active'");
    if (mysqli_num_rows($checkLoan) > 0) {
        $loan = mysqli_fetch_array($checkLoan);
        $typeOfFarming = $loan['typeOfFarming'];
        $periodOfFarming = $loan['periodOfFarming'];
        $scaleOfFarming = $loan['scaleOfFarming'];
        $product = $loan['product'];
        $amount = $loan['amount'];
        $paymentMethod = $loan['paymentMethod'];
        $paymentDuration = $loan['paymentDuration'];
        $paymentMethod = $loan['paymentMethod'];
        $repaid = $loan['repaid'];
        $remainBalance = $loan['amount'] - $repaid;


        $ResultCode = "Active";
        $massage = "You have an active loan";
        $response = array(
            'ResultCode' => $ResultCode,
            'massage' => $massage,
            'typeOfFarming' =>  $typeOfFarming,
            'periodOfFarming' => $periodOfFarming,
            'scaleOfFarming' => $scaleOfFarming,
            'product' => $product,
            'amount' => $amount,
            'paymentMethod' => $paymentMethod,
            'repaid' => $repaid,
            'remainBalance ' => $remainBalance
        );
    } else {
        $ResultCode = "NotActive";
        $massage = "You dont have an active loan";
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