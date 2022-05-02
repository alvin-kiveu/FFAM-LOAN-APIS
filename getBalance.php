<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php';
    $ResultCode = '';
    $EncodeData = file_get_contents('php://input');
    $submitedData = json_decode($EncodeData, true);
    $PhoneNumber = mysqli_escape_string($db, $submitedData['linenumber']);
    $user = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM aminia_users WHERE phone='$PhoneNumber'"));
    $balance = 'Ksh ' . number_format($user['balance']);

    $ResultCode = "Success";
    $massage = "user balance fetched sucessfuly successfully.";
    $response = array(
        'ResultCode' => $ResultCode,
        'massage' => $massage,
        'userBalance' => $balance,
    );
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