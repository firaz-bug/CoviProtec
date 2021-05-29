<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "peoplelogin";

//Create new connection
if(!$con = mysqli_connect($servername, $username, $password, $dbname)){
    die("Connection failed: " . $con->connect_error);
}

#echo "Connected successfully";
?>