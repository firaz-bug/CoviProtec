<?php
/*
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "peoplelogin";

//Create new connection
if(!$con = mysqli_connect($servername, $username, $password, $dbname)){
    die("Connection failed: " . $con->connect_error);
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
$con = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

?>