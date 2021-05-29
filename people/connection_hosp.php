<?php

$servername_hosp = "localhost";
$username_hosp = "root";
$password_hosp = "";
$dbname_hosp = "hospital_appointment";

//Create new connection
if(!$con_hosp = mysqli_connect($servername_hosp, $username_hosp, $password_hosp, $dbname_hosp)){
    die("Connection failed: " . $con_hosp->connect_error);
}

#echo "Connected successfully";
?>
