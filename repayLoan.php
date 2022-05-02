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
            if ($newBalance == 0) {
                mysqli_query($db, "UPDATE aminia_loan SET loanStatus='complete' WHERE phonenumber='$linenumber'");
            }
            $getLoan =  mysqli_query($db, "SELECT * FROM aminia_loan WHERE phonenumber='$linenumber' AND loanStatus='Active'");
            $loan = mysqli_fetch_array($getLoan);
            $loanRepaid = $loan['repaid'];
            $newAmountOfAmount =  $loanRepaid  + $amountOfAmount;
            mysqli_query($db, "UPDATE aminia_loan SET repaid='$newAmountOfAmount' WHERE phonenumber='$linenumber' AND loanStatus='Active'");
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