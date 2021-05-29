<?php

	include("connection.php");
	include("functions.php");
	
	if(isset($_POST["submit"])){
		
		$aadhar_ID = $_POST["aadhar_ID"];
		$password = $_POST["password"];
		
		if(emptyInputLogin($aadhar_ID, $password) !== false){
			header("Location: login.php?error=emptyinput");
			exit();
		}
		
		loginUser($con, $aadhar_ID, $password);
	}
	else{
			header("Location: login.php?error=emptyinput");
			exit();
	}