
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

	include("connection1.php");
	function emptyInputSignUp($license, $nabh_id, $hname, $email, $city, $phone, $password, $con_password ,$address){

		$result;

		if(empty($license) || empty($nabh_id) || empty($hname) || empty($email) || empty($phone) || empty($password) || empty($con_password) || empty($city) || empty($address)){
			$result = true;
		}

		else{
			$result = false;
		}

		return $result;
	}


/*	function invalidName( $name ){

		$result;

		if( !preg_match("/^[a-zA-Z]*$/", $name)){
			$result = true;
		}

		else{
			$result = false;
		}

		return $result;
	}
*/
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

	function idExists( $con, $nabh_id ){

		$sql = "SELECT * FROM hospital_details WHERE hospital_nabh_id = ?;";
		$stmt = mysqli_stmt_init($con);

		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: signup1.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt, "i", $nabh_id);
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

	function createUser($con, $license, $nabh_id, $hname, $email, $city, $phone, $password, $address){

		$sql = "INSERT INTO hospital_details VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = mysqli_stmt_init($con);

		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: signup1.php?error=stmtfailed");
			exit();
		}

		$hashedPwd = password_hash($password, PASSWORD_DEFAULT);

		mysqli_stmt_bind_param($stmt, "isssisss", $nabh_id, $hname, $hashedPwd, $email, $phone, $license, $city, $address);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		createStock($con, $nabh_id); //creating stock in the stock table.

		header("Location: login1.php");
		exit();
	}

	function createStock($con, $nabh_id){

		$sql = "INSERT INTO hospital_stock ( hospital_nabh_id, curr_covisheild, curr_covaccine, new_covisheild, new_covaccine, VaccinesPerDay) VALUES (?, ?, ?, ?, ?, ?);";
		$stmt = mysqli_stmt_init($con);

		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: signup1.php?error=stmtfailed");
			exit();
		}
		$zero = 0;
		mysqli_stmt_bind_param($stmt, "iiiiii", $nabh_id, $zero, $zero, $zero, $zero, $zero);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}


	function emptyInputLogin($nabh_id, $password){

		$result;

		if(empty($nabh_id) || empty($password)){
			$result = true;
		}

		else{
			$result = false;
		}

		return $result;
	}

	function loginUser($con, $nabh_id, $password){

		$idExists = idExists($con, $nabh_id);

		if($idExists === false){
			header("Location: login1.php?error=wrongnabhid");
			exit();
		}

		$pwdHashed = $idExists['password'];
		$checkPassword = password_verify( $password, $pwdHashed );

		if($checkPassword === false){

			header("Location: login1.php?error=wrongpassword");
			exit();
		}

		else if($checkPassword === true){
			session_start();

			$_SESSION["user_id"] = $nabh_id;
			$_SESSION["name"] = $idExists["hospital_name"];
			$_SESSION["phone"] = $idExists["phone"];
			header("Location: index1.php");
			exit();
		}
	}
