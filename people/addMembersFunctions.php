<?php
	
	include("connection.php");
	function emptyInputSignUp($aadhar, $aadhar_ID, $name, $gender, $age){
		
		$result;
		
		if(empty($aadhar) || empty($aadhar_ID) || empty($name) || empty($age)){
			$result = true;
		}
		
		else{
			$result = false;
		}
		
		return $result;
	}
	
	function invalidName( $name ){
		
		$result;
		
		if( !preg_match("/^[a-zA-Z ]*$/", $name)){
			$result = true;
		}
		
		else{
			$result = false;
		}
		
		return $result;
	}
	
	function idExists( $con, $aadhar_ID ){
		
		$sql = "SELECT * FROM people WHERE aadhar_ID = ?;";
		$stmt = mysqli_stmt_init($con);
		
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: index.php?error=stmtfailed");
			exit();
		}
		
		mysqli_stmt_bind_param($stmt, "i", $aadhar_ID);
		mysqli_stmt_execute($stmt);
		
		$resultData = mysqli_stmt_get_result($stmt);
		
		if($row = mysqli_fetch_assoc($resultData)){
			return $row;
		}
		
		else{
			$result = false;
			return $result;
		}
		
		mysqli_stmt_close($stmt);
	}
	
	function createUser($con, $aadhar, $aadhar_ID, $name, $gender, $age, $phone){

		$sql = "INSERT INTO people (aadhar, aadhar_ID, name, gender, age, phone) VALUES (?, ?, ?, ?, ?, ?);";
		$stmt = mysqli_stmt_init($con);
		
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: index.php?error=stmtfailed");
			exit();
		}
		
		mysqli_stmt_bind_param($stmt, "sissii", $aadhar, $aadhar_ID, $name, $gender, $age, $phone);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		header("Location: index.php");
		exit();
	}