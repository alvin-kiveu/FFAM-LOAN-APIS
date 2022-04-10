<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php';
    $ResultCode = '';
    $EncodeData = file_get_contents('php://input');
    $submitedData = json_decode($EncodeData, true);
    $PhoneNumber = mysqli_escape_string($db, $submitedData['linenumber']);
    $password = mysqli_escape_string($db, $submitedData['password']);

    $password1 = md5($password);
    $passwordcheck = '$%&£~f052gf#9*\,bchj' . $password1 . 'hjklzxcvbnm!*%&^%$£@';
    $get_user_id = "SELECT *  FROM aminia_users  WHERE phone='$PhoneNumber';";
    $result_get_user_id = mysqli_query($db, $get_user_id);
    if (mysqli_num_rows($result_get_user_id) > 0) {
        $row = mysqli_fetch_array($result_get_user_id);
        $query = "SELECT * FROM  aminia_users  WHERE phone='$PhoneNumber' AND password='$passwordcheck ';";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) == 1) {
            $ResultCode = "Success";
            $massage = "Loged in successfully.";
            $response = array(
                'ResultCode' => $ResultCode,
                'massage' => $massage,
            );
        } else {
            $ResultCode = "Error";
            $massage = "Incorrect logIn cridentials !!!'";
            $response = array(
                'ResultCode' => $ResultCode,
                'errorMessage' => $massage
            );
        }
    } else {
        $ResultCode = "Error";
        $massage = "the phone number entered does not exits";
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