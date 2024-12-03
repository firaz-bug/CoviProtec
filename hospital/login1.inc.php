<?php

	include("connection1.php");
	include("functions1.php");

	if(isset($_POST["submit"])){

		$nabh_id = $_POST["nabh_id"];
		$password = $_POST["password"];

		if(emptyInputLogin($nabh_id, $password) !== false){
			header("Location: login1.php?error=emptyinput");
			exit();
		}

		loginUser($con, $nabh_id, $password);
	}
	else{
			header("Location: login1.php?error=emptyinput");
			exit();
	}
