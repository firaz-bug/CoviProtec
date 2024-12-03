<?php
/*
$servername_hosp = "localhost";
$username_hosp = "root";
$password_hosp = "";
$dbname_hosp = "hospital_appointment";

//Create new connection
if(!$con_hosp = mysqli_connect($servername_hosp, $username_hosp, $password_hosp, $dbname_hosp)){
    die("Connection failed: " . $con_hosp->connect_error);
}

#echo "Connected successfully";
*/

//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;
// Connect to DB
$con_hosp = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

?>
