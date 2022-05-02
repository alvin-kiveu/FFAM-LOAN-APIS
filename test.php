<?php
include 'config.php';
$linenumber = "0768168060";
$checkLoan = mysqli_query($db, "SELECT * FROM aminia_loan WHERE  phonenumber='0768168060' AND loanStatus='Active'");
$loan = mysqli_fetch_array($checkLoan);
echo $loan['typeOfFarming'];
if (mysqli_num_rows($checkLoan) > 0) {
    echo "It okey";
} else {
    echo "It not okey ";
}