<?php
	echo "hospdash called";
	session_start();
	include("connection1.php");
	include("hospDashFunctions.php");
	if(isset($_POST['submit'])){
		echo "hosp dash 2";
		$new_covaccine = $_POST['new_covaccine'];
		$new_covisheild = $_POST['new_covisheild'];
		$vaccines_perDay = $_POST['vaccines_perDay'];


		if(emptyInputSignUp($new_covaccine, $new_covisheild, $vaccines_perDay) !== false){
			header("Location: index1.php?error=emptyinput");
			exit();
		}

		if($vaccines_perDay >= 100){
			header("Location: index1.php?error=vaccinesPD");
			exit();
		}
		$Currentvaccine_det = check_login($con);
		echo $Currentvaccine_det['curr_covaccine'];
		UpdateUser($con, $new_covaccine, $new_covisheild, $vaccines_perDay);

	}

	else{
		header("Location: index.php");
		exit();
	}
