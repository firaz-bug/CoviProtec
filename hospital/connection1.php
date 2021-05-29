<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "hospital_appointment";

$con = mysqli_connect($host,$user,$pass,$db);
if(!$con)
{
	mysqli_error($con);
	die("failed to connect!");
}
