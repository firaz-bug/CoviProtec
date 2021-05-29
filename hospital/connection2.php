<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "peoplelogin";

//Create new connection
if(!$conn = mysqli_connect($servername, $username, $password, $dbname)){
    die("Connection failed: " . $conn->connect_error);
}

#echo "Connected successfully";
?>
