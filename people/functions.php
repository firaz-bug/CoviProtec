
<?php
/*
function check_login($con){
	
	if( isset($_SESSION['ID']) )
	{
		
		$id = $_SESSION['ID'];
		$query = "SELECT * FROM patient WHERE ID = '$id' limit 1";
		
		$result = mysqli_query($con, $query);
		
		if($result && mysqli_num_rows($result) > 0 ){
		
			$user_data = mysqli_fetch_assoc($result);
			
			return $user_data;
		}
		
	}
	
	//Redirect to login
	header("Location: login.php");
	
	die;
}
*/

	include("connection.php");
	function emptyInputSignUp($aadhar, $aadhar_ID, $name, $gender, $age, $phone, $password, $con_password){
		
		$result;
		
		if(empty($aadhar) || empty($aadhar_ID) || empty($name) || empty($age) || empty($phone) || empty($password) || empty($con_password)){
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
	
	function pwdMatch( $password, $con_password ){
		
		$result;
		
		if($password !== $con_password){
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
			header("Location: signup.php?error=stmtfailed");
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
	
	function createUser($con, $aadhar, $aadhar_ID, $name, $gender, $age, $phone, $password){
	
		$sql = "INSERT INTO people VALUES (?, ?, ?, ?, ?, ?, ?);";
		$stmt = mysqli_stmt_init($con);
		
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: signup.php?error=stmtfailed");
			exit();
		}
		
		$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
		
		mysqli_stmt_bind_param($stmt, "sissiis", $aadhar, $aadhar_ID, $name, $gender, $age, $phone, $hashedPwd);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		header("Location: login.php");
		exit();
	}
	
	function emptyInputLogin($aadhar_ID, $password){
		
		$result;
		
		if(empty($aadhar_ID) || empty($password)){
			$result = true;
		}
		
		else{
			$result = false;
		}
		
		return $result;
	}
	
	function loginUser($con, $aadhar_ID, $password){
		
		$idExists = idExists($con, $aadhar_ID);
		
		if($idExists === false){
			header("Location: login.php?error=wrongaadharid");
			exit();		
		}
					
		$pwdHashed = $idExists['password'];
		$checkPassword = password_verify( $password, $pwdHashed );
		
		if($checkPassword === false){

			header("Location: login.php?error=wrongpassword");
			exit();		
		}
		
		else if($checkPassword === true){
			session_start();
		
			$_SESSION["aadhar_ID"] = $aadhar_ID;
			$_SESSION["name"] = $idExists["name"];
			$_SESSION["phone"] = $idExists["phone"];
			header("Location: index.php");
			exit();	
		}
	}