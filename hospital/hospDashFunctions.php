<?php

	include("connection1.php");
	function emptyInputSignUp($new_covaccine, $new_covisheild, $vaccines_perDay){

		$result;

		if(empty($new_covaccine) || empty($new_covisheild) || empty($vaccines_perDay)){
			$result = true;
		}

		else{
			$result = false;
		}

		return $result;
	}



	function currentVaccine_details( $con, $nabh_id ){

		$sql = "SELECT * FROM hospital_stock WHERE hospital_nabh_id = ?;";
		$stmt = mysqli_stmt_init($con);

		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: index1.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt, "i", $nabh_id);
		mysqli_stmt_execute($stmt);

		$resultData = mysqli_stmt_get_result($stmt);
		if($resultData && mysqli_num_rows($resultData) > 0)
		{
			$row = mysqli_fetch_assoc($resultData);
			return $row;
		}
		else {
			return NULL;
		}

		mysqli_stmt_close($stmt);
	}


	function check_login($con)
	{
		if(isset($_SESSION["user_id"]))
		{

			$id = $_SESSION["user_id"];
			$sql = "SELECT * FROM hospital_details WHERE hospital_nabh_id = ?;";
			$stmt = mysqli_stmt_init($con);

			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("Location: index1.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);

			$resultData = mysqli_stmt_get_result($stmt);
			if($resultData && mysqli_num_rows($resultData) > 0)
			{

				$user_data = currentVaccine_details( $con, $id );
				return $user_data;
			}
			mysqli_stmt_close($stmt);
		}

		//redirect to login
		header("Location: login1.php");
		die;

	}

	function createUser($con, $vaccines_perDay){
		session_start();
		$id = $_SESSION['user_id'];
		echo $id;
		$sql = "INSERT INTO vaccine_date(hospital_nabh_ID, vaccine_per_day1, vaccine_per_day2, vaccine_per_day3, vaccine_per_day4, vaccine_per_day5, vaccine_per_day6, vaccine_per_day7, vaccine_per_day) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = mysqli_stmt_init($con);

		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: index1.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt, "iiiiiiiii", $id, $vaccines_perDay, $vaccines_perDay, $vaccines_perDay, $vaccines_perDay, $vaccines_perDay, $vaccines_perDay, $vaccines_perDay, $vaccines_perDay);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

	}

	function UpdateUser($con, $new_covaccine, $new_covisheild, $vaccines_perDay){

		session_start();
		$id = $_SESSION['user_id'];
		echo $id;
		$user_data = currentVaccine_details( $con, $id );
		$sql = "UPDATE hospital_stock SET  curr_covisheild = ? , curr_covaccine = ? , new_covisheild = ?, new_covaccine = ?, VaccinesPerDay = ? WHERE hospital_nabh_id= ? ;";
		$stmt = mysqli_stmt_init($con);

		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: index1.php?error=stmtfailed");
			exit();
		}

		$curr_sheild =  $user_data['curr_covisheild'] + $new_covisheild;
		$curr_cova =  $user_data['curr_covaccine'] + $new_covaccine;
		mysqli_stmt_bind_param($stmt, "iiiiii", $curr_sheild, $curr_cova, $new_covisheild, $new_covaccine, $vaccines_perDay, $id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		createUser($con, $vaccines_perDay);
		header("Location: index1.php");
		exit();
	}
