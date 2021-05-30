<?php
	date_default_timezone_set("Asia/Kolkata");
	session_start();
	include("connection.php");
	include("connection_hosp.php");

	$city_function = $_SESSION['city'];
	$sql_hosp_city = "SELECT * FROM hospital_details WHERE hospital_city = ?;";
	$stmt_hosp_city = mysqli_stmt_init($con_hosp);
	
	if(!mysqli_stmt_prepare($stmt_hosp_city, $sql_hosp_city)){
		header("Location: index2.php?error=stmtfailed");
		exit();
	}
	
	mysqli_stmt_bind_param($stmt_hosp_city, "s", $city_function);
	mysqli_stmt_execute($stmt_hosp_city);
	$resultData_city = mysqli_stmt_get_result($stmt_hosp_city);
	
	while($row_city = mysqli_fetch_assoc($resultData_city)){

		$nabh_ID = $row_city['hospital_nabh_id'];
		
		$covaxin1 = $nabh_ID."covaxin1";
		$covaxin2 = $nabh_ID."covaxin2";
		$covaxin3 = $nabh_ID."covaxin3";
		$covaxin4 = $nabh_ID."covaxin4";
		$covaxin5 = $nabh_ID."covaxin5";
		$covaxin6 = $nabh_ID."covaxin6";
		$covaxin7 = $nabh_ID."covaxin7";
		
		$covishield1 = $nabh_ID."covishield1";
		$covishield2 = $nabh_ID."covishield2";
		$covishield3 = $nabh_ID."covishield3";
		$covishield4 = $nabh_ID."covishield4";
		$covishield5 = $nabh_ID."covishield5";
		$covishield6 = $nabh_ID."covishield6";
		$covishield7 = $nabh_ID."covishield7";
		
		if(isset($_POST[$covaxin1]) || isset($_POST[$covishield1])){
			
			$_SESSION['day']=1;
			$_SESSION['hospital_nabh_ID'] = $nabh_ID;
			break;
		}
		
		else if(isset($_POST[$covaxin2]) || isset($_POST[$covishield2])){
		
			$_SESSION['day']=2;
			$_SESSION['hospital_nabh_ID'] = $nabh_ID;
			break;
		}
		
		else if(isset($_POST[$covaxin3]) || isset($_POST[$covishield3])){
			
			$_SESSION['day']=3;
			$_SESSION['hospital_nabh_ID'] = $nabh_ID;
			break;
		}
		
		else if(isset($_POST[$covaxin4]) || isset($_POST[$covishield4])){
			$_SESSION['day']=4;
			$_SESSION['hospital_nabh_ID'] = $nabh_ID;
			break;
		}
		
		else if(isset($_POST[$covaxin5]) || isset($_POST[$covishield5])){
			$_SESSION['day']=5;
			$_SESSION['hospital_nabh_ID'] = $nabh_ID;
			break;
		}
		
		else if(isset($_POST[$covaxin6]) || isset($_POST[$covishield6])){
			$_SESSION['day']=6;
			$_SESSION['hospital_nabh_ID'] = $nabh_ID;
			break;
		}
		
		else if(isset($_POST[$covaxin7]) || isset($_POST[$covishield7])){
			$_SESSION['day']=7;
			$_SESSION['hospital_nabh_ID'] = $nabh_ID;
			break;
		}
}

	$covaxin= $_SESSION['hospital_nabh_ID']."covaxin".$_SESSION['day'];
	$covishield = $_SESSION['hospital_nabh_ID']."covishield".$_SESSION['day'];

	if(isset($_POST[$covaxin])){

		//Assigning Session Variables
		$hospital_nabh_ID = $_SESSION['hospital_nabh_ID'];
		$day = $_SESSION['day'];
		$dose = $_SESSION['dose'];

		//SETTING FOR USER1 OR USER2
		$user1_aadhar_ID = NULL;
		$user2_aadhar_ID = NULL;

		//SELECTING VACCINE PER DAY FROM VACCINE DATE DEPENDING ON THE HOSPITAL
		$sql_vaccine_date = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
		$stmt_vaccine_date = mysqli_stmt_init($con_hosp);

		if(!mysqli_stmt_prepare($stmt_vaccine_date, $sql_vaccine_date)){
			header("Location: index2.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt_vaccine_date, "i", $hospital_nabh_ID);
		mysqli_stmt_execute($stmt_vaccine_date);
		$resultData_vaccine_date = mysqli_stmt_get_result($stmt_vaccine_date);

		$row_vaccine_count = mysqli_fetch_assoc($resultData_vaccine_date);
		$vaccine_day = "vaccine_per_day".$day;

		if($_SESSION['user_number'] == 1){
			$user1_aadhar_ID = $_SESSION['aadhar_ID'];
			$vaccine_per_day = $row_vaccine_count[$vaccine_day] - 1;
		}
		else if($_SESSION['user_number'] == 2){
			$user2_aadhar_ID = $_SESSION['aadhar_ID_person2'];
			$vaccine_per_day = $row_vaccine_count[$vaccine_day] - 1;
		}
		else if($_SESSION['user_number'] == 3){
			$user1_aadhar_ID = $_SESSION['aadhar_ID'];
			$user2_aadhar_ID = $_SESSION['aadhar_ID_person2'];
			$vaccine_per_day = $row_vaccine_count[$vaccine_day] - 2;
		}

		//UPDATING VACCINE PER DAY IN THE VACCINE DATE TABLE
		$query_vaccine_count = "UPDATE vaccine_date SET ".$vaccine_day." = ".$vaccine_per_day." WHERE hospital_nabh_ID = ".$hospital_nabh_ID.";";
		$result_vaccine_count = mysqli_query($con_hosp, $query_vaccine_count);

		if(!$result_vaccine_count){
			header("Location: index2.php?error=stmtfailed");
			exit();
		}

		//STORING THE DATE OF VACCINATION
		$d1 = strtotime("+$day day");
		$d1date= date("Y-m-d ", $d1);

		//FOR USER 1
		if($user1_aadhar_ID != NULL){
			
			//SELECTING FROM THE HOSPITAL STOCK TABLE
			$sql_hosp_stock = "SELECT * FROM hospital_stock WHERE hospital_nabh_id = ? ;";
			$stmt_hosp_stock = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_hosp_stock, $sql_hosp_stock)){
				header("Location: index2.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_hosp_stock, "i", $hospital_nabh_ID);
			mysqli_stmt_execute($stmt_hosp_stock);
			$resultData_hosp_stock = mysqli_stmt_get_result($stmt_hosp_stock);

			$row_hosp_stock = mysqli_fetch_assoc($resultData_hosp_stock);
			$cur_covaxin = $row_hosp_stock['curr_covaccine'] - 1;

			//UPDATING THE CURRENT STOCK IN THE HOSPITAL STOCK TABLE
			$query_hosp_stock = "UPDATE hospital_stock SET curr_covaccine = ".$cur_covaxin." WHERE hospital_nabh_id = ".$hospital_nabh_ID.";";
			$result_hosp_stock = mysqli_query($con_hosp, $query_hosp_stock);

			if(!$result_hosp_stock){
				header("Location: index2.php?error:stmtfailed");
				exit();
			}

			//CHECKING IF ALREADY PRESENT IN THE TABLE
			$resultAlreadyExists = idExists($con, $user1_aadhar_ID);

			//CREATING AN ENTRY IF NOT PRESENT IN THE TABLE
			if(!$resultAlreadyExists){
				createEntry($con, $user1_aadhar_ID);
			}

			if($dose == "dose1"){
				$query_people_dose = "UPDATE people_slot_booking SET dose1 = 1, dose1_date = '$d1date',  covaxin = 1, hospital_nabh_ID = ".$hospital_nabh_ID." WHERE aadhar_ID = ".$user1_aadhar_ID.";";
				$result_people_dose = mysqli_query($con, $query_people_dose);

				if(!$result_people_dose){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}
				/*
				else{
					header("Location: index2.php");
					exit();
				}*/
			}

			else if($dose == "dose2"){
				$query_people_dose_2 = "UPDATE people_slot_booking SET dose1 = 1, dose2 = 1, dose2_date = '$d1date', covaxin = 1, hospital_nabh_ID = ".$hospital_nabh_ID." WHERE aadhar_ID = ".$user1_aadhar_ID.";";
				$result_people_dose_2 = mysqli_query($con, $query_people_dose_2);

				if(!$result_people_dose_2){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}/*
				else{
					header("Location: index2.php");
					exit();
				}*/
			}

		}

		if($user2_aadhar_ID != NULL){

			//SELECTING FROM THE HOSPITAL STOCK TABLE
			$sql_hosp_stock = "SELECT * FROM hospital_stock WHERE hospital_nabh_id = ? ;";
			$stmt_hosp_stock = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_hosp_stock, $sql_hosp_stock)){
				header("Location: index2.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_hosp_stock, "i", $hospital_nabh_ID);
			mysqli_stmt_execute($stmt_hosp_stock);
			$resultData_hosp_stock = mysqli_stmt_get_result($stmt_hosp_stock);

			$row_hosp_stock = mysqli_fetch_assoc($resultData_hosp_stock);
			$cur_covaxin = $row_hosp_stock['curr_covaccine'] - 1;

			//UPDATING THE CURRENT STOCK IN THE HOSPITAL STOCK TABLE
			$query_hosp_stock = "UPDATE hospital_stock SET curr_covaccine = ".$cur_covaxin." WHERE hospital_nabh_id = ".$hospital_nabh_ID.";";
			$result_hosp_stock = mysqli_query($con_hosp, $query_hosp_stock);

			if(!$result_hosp_stock){
				header("Location: index2.php?error:stmtfailed");
				exit();
			}

			$resultAlreadyExists = idExists($con, $user2_aadhar_ID);

			if(!$resultAlreadyExists){
				createEntry($con, $user2_aadhar_ID);
			}

			if($dose == "dose1"){

				$query_people_dose = "UPDATE people_slot_booking SET dose1 = 1, dose1_date = '$d1date', covaxin = 1, hospital_nabh_ID = ".$hospital_nabh_ID."  WHERE aadhar_ID = ".$user2_aadhar_ID.";";
				$result_people_dose = mysqli_query($con, $query_people_dose);

				if(!$result_people_dose){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}/*
				else{

					header("Location: index2.php");
					exit();
				}*/
			}

			else if($dose == "dose2"){
				$query_people_dose_2 = "UPDATE people_slot_booking SET dose2 = 1, dose2_date = '$d1date', covaxin = 1, hospital_nabh_ID = ".$hospital_nabh_ID."  WHERE aadhar_ID = ".$user2_aadhar_ID.";";
				$result_people_dose_2 = mysqli_query($con, $query_people_dose_2);

				if(!$result_people_dose_2){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}/*
				else{

					header("Location: index2.php");
					exit();
				}*/
			}

		}
		if(true){
			header("Location: index2.php");
			exit();
		}

	}


	if(isset($_POST[$covishield])){

		//Assigning Session Variables
		$hospital_nabh_ID = $_SESSION['hospital_nabh_ID'];
		$day = $_SESSION['day'];
		$dose = $_SESSION['dose'];

		//SETTING FOR USER1 OR USER2
		$user1_aadhar_ID = NULL;
		$user2_aadhar_ID = NULL;

		//SELECTING VACCINE PER DAY FROM VACCINE DATE DEPENDING ON THE HOSPITAL
		$sql_vaccine_date = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
		$stmt_vaccine_date = mysqli_stmt_init($con_hosp);

		if(!mysqli_stmt_prepare($stmt_vaccine_date, $sql_vaccine_date)){
			header("Location: index2.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt_vaccine_date, "i", $hospital_nabh_ID);
		mysqli_stmt_execute($stmt_vaccine_date);
		$resultData_vaccine_date = mysqli_stmt_get_result($stmt_vaccine_date);

		$row_vaccine_count = mysqli_fetch_assoc($resultData_vaccine_date);
		$vaccine_day = "vaccine_per_day".$day;

		if($_SESSION['user_number'] == 1){
			$user1_aadhar_ID = $_SESSION['aadhar_ID'];
			$vaccine_per_day = $row_vaccine_count[$vaccine_day] - 1;
		}
		else if($_SESSION['user_number'] == 2){
			$user2_aadhar_ID = $_SESSION['aadhar_ID_person2'];
			$vaccine_per_day = $row_vaccine_count[$vaccine_day] - 1;
		}
		else if($_SESSION['user_number'] == 3){
			$user1_aadhar_ID = $_SESSION['aadhar_ID'];
			$user2_aadhar_ID = $_SESSION['aadhar_ID_person2'];
			$vaccine_per_day = $row_vaccine_count[$vaccine_day] - 2;
		}

		//UPDATING VACCINE PER DAY IN THE VACCINE DATE TABLE
		$query_vaccine_count = "UPDATE vaccine_date SET ".$vaccine_day." = ".$vaccine_per_day." WHERE hospital_nabh_ID = ".$hospital_nabh_ID.";";
		$result_vaccine_count = mysqli_query($con_hosp, $query_vaccine_count);

		if(!$result_vaccine_count){
			header("Location: index2.php?error=stmtfailed");
			exit();
		}

		//STORING THE DATE OF VACCINATION
		$d1 = strtotime("+$day day");
		$d1date= date("Y-m-d ", $d1);

		//FOR USER 1
		if($user1_aadhar_ID != NULL){

			//SELECTING FROM THE HOSPITAL STOCK TABLE
			$sql_hosp_stock = "SELECT * FROM hospital_stock WHERE hospital_nabh_id = ? ;";
			$stmt_hosp_stock = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_hosp_stock, $sql_hosp_stock)){
				header("Location: index2.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_hosp_stock, "i", $hospital_nabh_ID);
			mysqli_stmt_execute($stmt_hosp_stock);
			$resultData_hosp_stock = mysqli_stmt_get_result($stmt_hosp_stock);

			$row_hosp_stock = mysqli_fetch_assoc($resultData_hosp_stock);
			$cur_covishield = $row_hosp_stock['curr_covisheild'] - 1;

			//UPDATING THE CURRENT STOCK IN THE HOSPITAL STOCK TABLE
			$query_hosp_stock = "UPDATE hospital_stock SET curr_covisheild = ".$cur_covishield." WHERE hospital_nabh_id = ".$hospital_nabh_ID.";";
			$result_hosp_stock = mysqli_query($con_hosp, $query_hosp_stock);

			if(!$result_hosp_stock){
				header("Location: index2.php?error:stmtfailed");
				exit();
			}
		
			//CHECKING IF ALREADY PRESENT IN THE TABLE
			$resultAlreadyExists = idExists($con, $user1_aadhar_ID);

			//CREATING AN ENTRY IF NOT PRESENT IN THE TABLE
			if(!$resultAlreadyExists){
				createEntry($con, $user1_aadhar_ID);
			}

			if($dose == "dose1"){
				$query_people_dose = "UPDATE people_slot_booking SET dose1 = 1, dose1_date = '$d1date',  covishield = 1, hospital_nabh_ID = ".$hospital_nabh_ID." WHERE aadhar_ID = ".$user1_aadhar_ID.";";
				$result_people_dose = mysqli_query($con, $query_people_dose);

				if(!$result_people_dose){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}
				/*
				else{
					header("Location: index2.php");
					exit();
				}*/
			}

			else if($dose == "dose2"){
				$query_people_dose_2 = "UPDATE people_slot_booking SET dose1 = 1, dose2 = 1, dose2_date = '$d1date', covishield = 1, hospital_nabh_ID = ".$hospital_nabh_ID." WHERE aadhar_ID = ".$user1_aadhar_ID.";";
				$result_people_dose_2 = mysqli_query($con, $query_people_dose_2);

				if(!$result_people_dose_2){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}/*
				else{
					header("Location: index2.php");
					exit();
				}*/
			}

		}

		if($user2_aadhar_ID != NULL){

			//SELECTING FROM THE HOSPITAL STOCK TABLE
			$sql_hosp_stock = "SELECT * FROM hospital_stock WHERE hospital_nabh_id = ? ;";
			$stmt_hosp_stock = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_hosp_stock, $sql_hosp_stock)){
				header("Location: index2.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_hosp_stock, "i", $hospital_nabh_ID);
			mysqli_stmt_execute($stmt_hosp_stock);
			$resultData_hosp_stock = mysqli_stmt_get_result($stmt_hosp_stock);

			$row_hosp_stock = mysqli_fetch_assoc($resultData_hosp_stock);
			$cur_covishield = $row_hosp_stock['curr_covisheild'] - 1;

			//UPDATING THE CURRENT STOCK IN THE HOSPITAL STOCK TABLE
			$query_hosp_stock = "UPDATE hospital_stock SET curr_covisheild = ".$cur_covishield." WHERE hospital_nabh_id = ".$hospital_nabh_ID.";";
			$result_hosp_stock = mysqli_query($con_hosp, $query_hosp_stock);

			if(!$result_hosp_stock){
				header("Location: index2.php?error:stmtfailed");
				exit();
			}
			
			$resultAlreadyExists = idExists($con, $user2_aadhar_ID);

			if(!$resultAlreadyExists){
				createEntry($con, $user2_aadhar_ID);
			}

			if($dose == "dose1"){

				$query_people_dose = "UPDATE people_slot_booking SET dose1 = 1, dose1_date = '$d1date', covishield = 1, hospital_nabh_ID = ".$hospital_nabh_ID."  WHERE aadhar_ID = ".$user2_aadhar_ID.";";
				$result_people_dose = mysqli_query($con, $query_people_dose);

				if(!$result_people_dose){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}/*
				else{

					header("Location: index2.php");
					exit();
				}*/
			}

			else if($dose == "dose2"){
				$query_people_dose_2 = "UPDATE people_slot_booking SET dose2 = 1, dose2_date = '$d1date', covishield = 1, hospital_nabh_ID = ".$hospital_nabh_ID."  WHERE aadhar_ID = ".$user2_aadhar_ID.";";
				$result_people_dose_2 = mysqli_query($con, $query_people_dose_2);

				if(!$result_people_dose_2){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}/*
				else{

					header("Location: index2.php");
					exit();
				}*/
			}

		}
		if(true){
			header("Location: index2.php");
			exit();
		}

	}

	function idExists( $con, $user_aadhar_ID ){

		$sql = "SELECT * FROM people_slot_booking WHERE aadhar_ID = ?;";
		$stmt = mysqli_stmt_init($con);

		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: index2.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt, "i", $user_aadhar_ID);
		mysqli_stmt_execute($stmt);

		$resultData = mysqli_stmt_get_result($stmt);

		if($row = mysqli_fetch_assoc($resultData)){
			$result = true;
			return $result;
		}

		else{
			$result = false;
			return $result;
		}

		mysqli_stmt_close($stmt);
	}

	function getUser($con,$aadhar){

			$sql_user = "SELECT * FROM people_slot_booking WHERE aadhar_ID = ?;";
			$stmt_user = mysqli_stmt_init($con);

			if(!mysqli_stmt_prepare($stmt_user, $sql_user)){
				header("Location: patient_dashboard.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_user, "i", $aadhar);
			mysqli_stmt_execute($stmt_user);

			$resultData = mysqli_stmt_get_result($stmt_user);

			$row = mysqli_fetch_assoc($resultData);
			return $row;
	}

	function createEntry($con, $user_aadhar_ID){

		$sql = "INSERT INTO people_slot_booking (aadhar_ID) VALUES (?);";
		$stmt = mysqli_stmt_init($con);

		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: index2.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt, "i", $user_aadhar_ID);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

	}

/*
	if(isset($_POST[cancel_user1_dose1]))
	{
		$aadhar = $_SESSION['aadhar_ID'];

		$temp = date("Y-m-d");
		$today = date_create($temp);

		//RETREIVING ROWS FROM PEOPLE SLOT BOOKING
		$row_user1_dose1 = getUser($con, $aadhar);
		$user_hosp_nabh = $row_user1_dose1['hospital_nabh_ID'];

		//FOR VACCINE_PER_DAY, SELECTING THE DAY
		$diff1 = date_diff($today, date_create($row_user1_dose1['dose1_date']));
		$diff = intval($diff1->format("%a"));
		$vpd_diff = "vaccine_per_day".$diff;

		if ($row_user1_dose1['covaxin'] == 1){
			//updating in people slot booking table
			$cancelquery_user1_dose1 = "UPDATE people_slot_booking SET dose1 = 0, dose1_date = '', covaxin = 0 WHERE aadhar_ID = '".$aadhar."';";
			$result_user1_dose1 = mysqli_query($con_hosp, $cancelquery_user1_dose1);

			if(!$result_user1_dose1){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			//retreiving vaccine_per_day for all days from vaccine date table
			$sql_user1_dose1 = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
			$stmt_sql_user1_dose1 = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_sql_user1_dose1, $sql_user1_dose1)){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_sql_user1_dose1, "i", $user_hosp_nabh);
			mysqli_stmt_execute($stmt_sql_user1_dose1);
			$resultData_vaccine_date_user1_dose1 = mysqli_stmt_get_result($stmt_sql_user1_dose1);

			$row_vaccine_count_data = mysqli_fetch_assoc($resultData_vaccine_date_user1_dose1);
			$n = $row_vaccine_count_data[$vpd_diff]+1;   //getting the current number of vaccines in vaccine date table for the particular day vpd_diff

			//updating vaccine_per_day for the given vpd and hospital_nabh_ID in vaccine_date table
			$cancelquery_user1_dose1 = "UPDATE vaccine_date SET ".$vpd_diff." = '".$n."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
			$result_user1_dose1 = mysqli_query($con_hosp, $cancelquery_user1_dose1);

			if(!$result_user1_dose1){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			//retreiving current availble vaccines for a particular hospital_nabh_ID
			$sql_hosp_stock_user1_dose1 = "SELECT * FROM hospital_stock WHERE hospital_nabh_ID = ? ;";
			$stmt_sql_hosp_stock_user1_dose1 = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_sql_hosp_stock_user1_dose1, $sql_hosp_stock_user1_dose1)){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_sql_hosp_stock_user1_dose1, "i", $user_hosp_nabh);
			mysqli_stmt_execute($stmt_sql_hosp_stock_user1_dose1);
			$resultData_hospital_stock_user1_dose1 = mysqli_stmt_get_result($stmt_sql_hosp_stock_user1_dose1);

			$row_hospital_stock_data = mysqli_fetch_assoc($resultData_hospital_stock_user1_dose1);
			$m = $row_hospital_stock_data['curr_covaccine'] + 1;

			//updating hospital stock current covaxin count for given hospital_nabh_ID
			$cancelquery_hospital_stock_user1_dose1 = "UPDATE hospital_stock SET curr_covaccine = '".$m."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
			$result_hospital_stock_user1_dose1 = mysqli_query($con_hosp, $cancelquery_hospital_stock_user1_dose1);

			if(!$result_hospital_stock_user1_dose1){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			else{
				echo "Hello";
				header("location: index.php");
				exit();
			}
		}
	}*/
/*
		else if($row_user1_dose1['covishield'] == 1){

				//updating in people slot booking table
				$cancelquery_user1_dose1 = "UPDATE people_slot_booking SET dose1 = 0, dose1_date = '', covishield = 0 WHERE aadhar_ID = '".$aadhar."';";
				$result_user1_dose1 = mysqli_query($con_hosp, $cancelquery_user1_dose1);

				if(!$result_user1_dose1){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				//retreiving vaccine_per_day for all days from vaccine date table
				$sql_user1_dose1 = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
				$stmt_sql_user1_dose1 = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_sql_user1_dose1, $sql_user1_dose1)){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_sql_user1_dose1, "i", $user_hosp_nabh);
				mysqli_stmt_execute($stmt_sql_user1_dose1);
				$resultData_vaccine_date_user1_dose1 = mysqli_stmt_get_result($stmt_sql_user1_dose1);

				$row_vaccine_count_data = mysqli_fetch_assoc($resultData_vaccine_date_user1_dose1);
				$n = $row_vaccine_count_data[$vpd_diff] + 1;   //getting the current number of vaccines in vaccine date table for the particular day vpd_diff

				//updating vaccine_per_day for the given vpd and hospital_nabh_ID in vaccine_date table
				$cancelquery_user1_dose1 = "UPDATE vaccine_date SET ".$vpd_diff." = '".$n."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
				$result_user1_dose1 = mysqli_query($con_hosp, $cancelquery_user1_dose1);

				if(!$result_user1_dose1){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				//retreiving current availble vaccines for a particular hospital_nabh_ID
				$sql_hosp_stock_user1_dose1 = "SELECT * FROM hospital_stock WHERE hospital_nabh_ID = ? ;";
				$stmt_sql_hosp_stock_user1_dose1 = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_sql_hosp_stock_user1_dose1, $sql_hosp_stock_user1_dose1)){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_sql_hosp_stock_user1_dose1, "i", $user_hosp_nabh);
				mysqli_stmt_execute($stmt_sql_hosp_stock_user1_dose1);
				$resultData_hospital_stock_user1_dose1 = mysqli_stmt_get_result($stmt_sql_hosp_stock_user1_dose1);

				$row_hospital_stock_data = mysqli_fetch_assoc($resultData_hospital_stock_user1_dose1);
				$m = $row_hospital_stock_data['curr_covisheild'] + 1;

				//updating hospital stock current covaxin count for given hospital_nabh_ID
				$cancelquery_hospital_stock_user1_dose1 = "UPDATE hospital_stock SET curr_covisheild = '".$m."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
				$result_hospital_stock_user1_dose1 = mysqli_query($con_hosp, $cancelquery_hospital_stock_user1_dose1);

				if(!$result_hospital_stock_user1_dose1){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				else{
					header("location: index.php");
					exit();
				}
			}
	}

	//CANCELLATION FOR USER 1 DOSE 2
	if(isset($_POST['cancel_user1_dose2'])){

		$aadhar = $_SESSION['aadhar_ID'];

		$temp = date("Y-m-d");
		$today = date_create($temp);

		//RETREIVING ROWS FROM PEOPLE SLOT BOOKING
		$row_user1_dose2 = getUser($con, $aadhar);
		$user_hosp_nabh = $row_user1_dose2['hospital_nabh_ID'];

		//FOR VACCINE_PER_DAY, SELECTING THE DAY
		$diff1 = date_diff($today, date_create($row_user1_dose2['dose2_date']));
		$diff = intval($diff1->format("%a"));
		$vpd_diff = "vaccine_per_day".$diff;

		if ($row_user1_dose2['covaxin'] == 1){
			//updating in people slot booking table
			$cancelquery_user1_dose2 = "UPDATE people_slot_booking SET dose2 = 0, dose2_date = '', covaxin = 0 WHERE aadhar_ID = '".$aadhar."';";
			$result_user1_dose2 = mysqli_query($con_hosp, $cancelquery_user1_dose2);

			if(!$result_user1_dose2){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			//retreiving vaccine_per_day for all days from vaccine date table
			$sql_user1_dose2 = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
			$stmt_sql_user1_dose2 = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_sql_user1_dose2, $sql_user1_dose2)){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_sql_user1_dose2, "i", $user_hosp_nabh);
			mysqli_stmt_execute($stmt_sql_user1_dose2);
			$resultData_vaccine_date_user1_dose2 = mysqli_stmt_get_result($stmt_sql_user1_dose2);

			$row_vaccine_count_data = mysqli_fetch_assoc($resultData_vaccine_date_user1_dose2);
			$n = $row_vaccine_count_data[$vpd_diff] + 1;   //getting the current number of vaccines in vaccine date table for the particular day vpd_diff

			//updating vaccine_per_day for the given vpd and hospital_nabh_ID in vaccine_date table
			$cancelquery_user1_dose2 = "UPDATE vaccine_date SET ".$vpd_diff." = '".$n."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
			$result_user1_dose2 = mysqli_query($con_hosp, $cancelquery_user1_dose2);

			if(!$result_user1_dose2){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			//retreiving current availble vaccines for a particular hospital_nabh_ID
			$sql_hosp_stock_user1_dose2 = "SELECT * FROM hospital_stock WHERE hospital_nabh_ID = ? ;";
			$stmt_sql_hosp_stock_user1_dose2 = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_sql_hosp_stock_user1_dose2, $sql_hosp_stock_user1_dose2)){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_sql_hosp_stock_user1_dose2, "i", $user_hosp_nabh);
			mysqli_stmt_execute($stmt_sql_hosp_stock_user1_dose2);
			$resultData_hospital_stock_user1_dose2 = mysqli_stmt_get_result($stmt_sql_hosp_stock_user1_dose2);

			$row_hospital_stock_data = mysqli_fetch_assoc($resultData_hospital_stock_user1_dose2);
			$m = $row_hospital_stock_data['curr_covaccine'] + 1;

			//updating hospital stock current covaxin count for given hospital_nabh_ID
			$cancelquery_hospital_stock_user1_dose2 = "UPDATE hospital_stock SET curr_covaccine = '".$m."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
			$result_hospital_stock_user1_dose2 = mysqli_query($con_hosp, $cancelquery_hospital_stock_user1_dose2);

			if(!$result_hospital_stock_user1_dose2){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			else{
				header("location: index.php");
				exit();
			}
		}

		else if($row_user1_dose2['covishield'] == 1){

				//updating in people slot booking table
				$cancelquery_user1_dose2 = "UPDATE people_slot_booking SET dose2 = 0, dose2_date = '', covishield = 0 WHERE aadhar_ID = '".$aadhar."';";
				$result_user1_dose2 = mysqli_query($con_hosp, $cancelquery_user1_dose2);

				if(!$result_user1_dose2){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				//retreiving vaccine_per_day for all days from vaccine date table
				$sql_user1_dose2 = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
				$stmt_sql_user1_dose2 = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_sql_user1_dose2, $sql_user1_dose2)){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_sql_user1_dose2, "i", $user_hosp_nabh);
				mysqli_stmt_execute($stmt_sql_user1_dose2);
				$resultData_vaccine_date_user1_dose2 = mysqli_stmt_get_result($stmt_sql_user1_dose2);

				$row_vaccine_count_data = mysqli_fetch_assoc($resultData_vaccine_date_user1_dose2);
				$n = $row_vaccine_count_data[$vpd_diff] + 1;   //getting the current number of vaccines in vaccine date table for the particular day vpd_diff

				//updating vaccine_per_day for the given vpd and hospital_nabh_ID in vaccine_date table
				$cancelquery_user1_dose2 = "UPDATE vaccine_date SET ".$vpd_diff." = '".$n."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
				$result_user1_dose2 = mysqli_query($con_hosp, $cancelquery_user1_dose2);

				if(!$result_user1_dose2){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				//retreiving current availble vaccines for a particular hospital_nabh_ID
				$sql_hosp_stock_user1_dose2 = "SELECT * FROM hospital_stock WHERE hospital_nabh_ID = ? ;";
				$stmt_sql_hosp_stock_user1_dose2 = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_sql_hosp_stock_user1_dose2, $sql_hosp_stock_user1_dose2)){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_sql_hosp_stock_user1_dose2, "i", $user_hosp_nabh);
				mysqli_stmt_execute($stmt_sql_hosp_stock_user1_dose2);
				$resultData_hospital_stock_user1_dose2 = mysqli_stmt_get_result($stmt_sql_hosp_stock_user1_dose2);

				$row_hospital_stock_data = mysqli_fetch_assoc($resultData_hospital_stock_user1_dose2);
				$m = $row_hospital_stock_data['curr_covisheild'] + 1;

				//updating hospital stock current covaxin count for given hospital_nabh_ID
				$cancelquery_hospital_stock_user1_dose2 = "UPDATE hospital_stock SET curr_covisheild = '".$m."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
				$result_hospital_stock_user1_dose2 = mysqli_query($con_hosp, $cancelquery_hospital_stock_user1_dose2);

				if(!$result_hospital_stock_user1_dose2){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				else{
					header("location: index.php");
					exit();
				}
		}
	}

	if(isset($_POST['cancel_user2_dose1']))
	{
		$aadhar = $_SESSION['aadhar_ID_person2'];

		$temp = date("Y-m-d");
		$today = date_create($temp);

		//RETREIVING ROWS FROM PEOPLE SLOT BOOKING
		$row_user2_dose1 = getUser($con, $aadhar);
		$user_hosp_nabh = $row_user2_dose1['hospital_nabh_ID'];

		//FOR VACCINE_PER_DAY, SELECTING THE DAY
		$diff1 = date_diff($today, date_create($row_user2_dose1['dose1_date']));
		$diff = intval($diff1->format("%a"));
		$vpd_diff = "vaccine_per_day".$diff;

		if ($row_user2_dose1['covaxin'] == 1){
			//updating in people slot booking table
			$cancelquery_user2_dose1 = "UPDATE people_slot_booking SET dose1 = 0, dose1_date = '', covaxin = 0 WHERE aadhar_ID = '".$aadhar."';";
			$result_user2_dose1 = mysqli_query($con_hosp, $cancelquery_user2_dose1);

			if(!$result_user2_dose1){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			//retreiving vaccine_per_day for all days from vaccine date table
			$sql_user2_dose1 = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
			$stmt_sql_user2_dose1 = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_sql_user2_dose1, $sql_user2_dose1)){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_sql_user2_dose1, "i", $user_hosp_nabh);
			mysqli_stmt_execute($stmt_sql_user2_dose1);
			$resultData_vaccine_date_user2_dose1 = mysqli_stmt_get_result($stmt_sql_user2_dose1);

			$row_vaccine_count_data = mysqli_fetch_assoc($resultData_vaccine_date_user1_dose1);
			$n = $row_vaccine_count_data[$vpd_diff]+1;   //getting the current number of vaccines in vaccine date table for the particular day vpd_diff

			//updating vaccine_per_day for the given vpd and hospital_nabh_ID in vaccine_date table
			$cancelquery_user2_dose1 = "UPDATE vaccine_date SET ".$vpd_diff." = '".$n."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
			$result_user2_dose1 = mysqli_query($con_hosp, $cancelquery_user2_dose1);

			if(!$result_user2_dose1){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			//retreiving current availble vaccines for a particular hospital_nabh_ID
			$sql_hosp_stock_user2_dose1 = "SELECT * FROM hospital_stock WHERE hospital_nabh_ID = ? ;";
			$stmt_sql_hosp_stock_user2_dose1 = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_sql_hosp_stock_user2_dose1, $sql_hosp_stock_user2_dose1)){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_sql_hosp_stock_user2_dose1, "i", $user_hosp_nabh);
			mysqli_stmt_execute($stmt_sql_hosp_stock_user2_dose1);
			$resultData_hospital_stock_user2_dose1 = mysqli_stmt_get_result($stmt_sql_hosp_stock_user2_dose1);

			$row_hospital_stock_data = mysqli_fetch_assoc($resultData_hospital_stock_user2_dose1);
			$m = $row_hospital_stock_data['curr_covaccine'] + 1;

			//updating hospital stock current covaxin count for given hospital_nabh_ID
			$cancelquery_hospital_stock_user2_dose1 = "UPDATE hospital_stock SET curr_covaccine = '".$m."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
			$result_hospital_stock_user2_dose1 = mysqli_query($con_hosp, $cancelquery_hospital_stock_user2_dose1);

			if(!$result_hospital_stock_user2_dose1){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			else{
				header("location: index.php");
				exit();
			}
		}

		else if($row_user2_dose1['covishield'] == 1){

				//updating in people slot booking table
				$cancelquery_user2_dose1 = "UPDATE people_slot_booking SET dose1 = 0, dose1_date = '', covishield = 0 WHERE aadhar_ID = '".$aadhar."';";
				$result_user2_dose1 = mysqli_query($con_hosp, $cancelquery_user1_dose1);

				if(!$result_user2_dose1){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				//retreiving vaccine_per_day for all days from vaccine date table
				$sql_user2_dose1 = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
				$stmt_sql_user2_dose1 = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_sql_user2_dose1, $sql_user2_dose1)){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_sql_user2_dose1, "i", $user_hosp_nabh);
				mysqli_stmt_execute($stmt_sql_user2_dose1);
				$resultData_vaccine_date_user2_dose1 = mysqli_stmt_get_result($stmt_sql_user2_dose1);

				$row_vaccine_count_data = mysqli_fetch_assoc($resultData_vaccine_date_user2_dose1);
				$n = $row_vaccine_count_data[$vpd_diff] + 1;   //getting the current number of vaccines in vaccine date table for the particular day vpd_diff

				//updating vaccine_per_day for the given vpd and hospital_nabh_ID in vaccine_date table
				$cancelquery_user2_dose1 = "UPDATE vaccine_date SET ".$vpd_diff." = '".$n."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
				$result_user2_dose1 = mysqli_query($con_hosp, $cancelquery_user2_dose1);

				if(!$result_user2_dose1){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				//retreiving current availble vaccines for a particular hospital_nabh_ID
				$sql_hosp_stock_user2_dose1 = "SELECT * FROM hospital_stock WHERE hospital_nabh_ID = ? ;";
				$stmt_sql_hosp_stock_user2_dose1 = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_sql_hosp_stock_user2_dose1, $sql_hosp_stock_user2_dose1)){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_sql_hosp_stock_user2_dose1, "i", $user_hosp_nabh);
				mysqli_stmt_execute($stmt_sql_hosp_stock_user2_dose1);
				$resultData_hospital_stock_user2_dose1 = mysqli_stmt_get_result($stmt_sql_hosp_stock_user2_dose1);

				$row_hospital_stock_data = mysqli_fetch_assoc($resultData_hospital_stock_user2_dose1);
				$m = $row_hospital_stock_data['curr_covisheild'] + 1;

				//updating hospital stock current covaxin count for given hospital_nabh_ID
				$cancelquery_hospital_stock_user2_dose1 = "UPDATE hospital_stock SET curr_covisheild = '".$m."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
				$result_hospital_stock_user2_dose1 = mysqli_query($con_hosp, $cancelquery_hospital_stock_user1\2_dose1);

				if(!$result_hospital_stock_user2_dose1){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				else{
					header("location: index.php");
					exit();
				}
		}

	}

	if(isset($_POST['cancel_user2_dose2']))
	{
		$aadhar = $_SESSION['aadhar_ID_person2'];

		$temp = date("Y-m-d");
		$today = date_create($temp);

		//RETREIVING ROWS FROM PEOPLE SLOT BOOKING
		$row_user2_dose2 = getUser($con, $aadhar);
		$user_hosp_nabh = $row_user2_dose2['hospital_nabh_ID'];

		//FOR VACCINE_PER_DAY, SELECTING THE DAY
		$diff1 = date_diff($today, date_create($row_user2_dose2['dose1_date']));
		$diff = intval($diff1->format("%a"));
		$vpd_diff = "vaccine_per_day".$diff;

		if ($row_user2_dose2['covaxin'] == 1){
			//updating in people slot booking table
			$cancelquery_user2_dose2 = "UPDATE people_slot_booking SET dose2 = 0, dose2_date = '', covaxin = 0 WHERE aadhar_ID = '".$aadhar."';";
			$result_user2_dose2 = mysqli_query($con_hosp, $cancelquery_user2_dose2);

			if(!$result_user2_dose2){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			//retreiving vaccine_per_day for all days from vaccine date table
			$sql_user2_dose2 = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
			$stmt_sql_user2_dose2 = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_sql_user2_dose2, $sql_user2_dose2)){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_sql_user2_dose2, "i", $user_hosp_nabh);
			mysqli_stmt_execute($stmt_sql_user2_dose2);
			$resultData_vaccine_date_user2_dose2 = mysqli_stmt_get_result($stmt_sql_user2_dose2);

			$row_vaccine_count_data = mysqli_fetch_assoc($resultData_vaccine_date_user2_dose2);
			$n = $row_vaccine_count_data[$vpd_diff]+1;   //getting the current number of vaccines in vaccine date table for the particular day vpd_diff

			//updating vaccine_per_day for the given vpd and hospital_nabh_ID in vaccine_date table
			$cancelquery_user2_dose2 = "UPDATE vaccine_date SET ".$vpd_diff." = '".$n."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
			$result_user2_dose2 = mysqli_query($con_hosp, $cancelquery_user2_dose2);

			if(!$result_user2_dose2){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			//retreiving current availble vaccines for a particular hospital_nabh_ID
			$sql_hosp_stock_user2_dose2 = "SELECT * FROM hospital_stock WHERE hospital_nabh_ID = ? ;";
			$stmt_sql_hosp_stock_user2_dose2 = mysqli_stmt_init($con_hosp);

			if(!mysqli_stmt_prepare($stmt_sql_hosp_stock_user2_dose2, $sql_hosp_stock_user2_dose2)){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_sql_hosp_stock_user2_dose2, "i", $user_hosp_nabh);
			mysqli_stmt_execute($stmt_sql_hosp_stock_user2_dose2);
			$resultData_hospital_stock_user2_dose2 = mysqli_stmt_get_result($stmt_sql_hosp_stock_user2_dose2);

			$row_hospital_stock_data = mysqli_fetch_assoc($resultData_hospital_stock_user2_dose2);
			$m = $row_hospital_stock_data['curr_covaccine'] + 1;

			//updating hospital stock current covaxin count for given hospital_nabh_ID
			$cancelquery_hospital_stock_user2_dose2 = "UPDATE hospital_stock SET curr_covaccine = '".$m."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
			$result_hospital_stock_user2_dose2 = mysqli_query($con_hosp, $cancelquery_hospital_stock_user2_dose2);

			if(!$result_hospital_stock_user2_dose2){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			else{
				header("location: index.php");
				exit();
			}
		}

		else if($row_user2_dose2['covishield'] == 1){

				//updating in people slot booking table
				$cancelquery_user2_dose2 = "UPDATE people_slot_booking SET dose2 = 0, dose2_date = '', covishield = 0 WHERE aadhar_ID = '".$aadhar."';";
				$result_user2_dose2 = mysqli_query($con_hosp, $cancelquery_user2_dose2);

				if(!$result_user2_dose2){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				//retreiving vaccine_per_day for all days from vaccine date table
				$sql_user2_dose2 = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
				$stmt_sql_user2_dose2 = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_sql_user2_dose2, $sql_user2_dose2)){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_sql_user2_dose2, "i", $user_hosp_nabh);
				mysqli_stmt_execute($stmt_sql_user2_dose2);
				$resultData_vaccine_date_user2_dose2 = mysqli_stmt_get_result($stmt_sql_user2_dose2);

				$row_vaccine_count_data = mysqli_fetch_assoc($resultData_vaccine_date_user2_dose2);
				$n = $row_vaccine_count_data[$vpd_diff] + 1;   //getting the current number of vaccines in vaccine date table for the particular day vpd_diff

				//updating vaccine_per_day for the given vpd and hospital_nabh_ID in vaccine_date table
				$cancelquery_user2_dose2 = "UPDATE vaccine_date SET ".$vpd_diff." = '".$n."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
				$result_user2_dose2 = mysqli_query($con_hosp, $cancelquery_user2_dose2);

				if(!$result_user2_dose2){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				//retreiving current availble vaccines for a particular hospital_nabh_ID
				$sql_hosp_stock_user2_dose2 = "SELECT * FROM hospital_stock WHERE hospital_nabh_ID = ? ;";
				$stmt_sql_hosp_stock_user2_dose2 = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_sql_hosp_stock_user2_dose2, $sql_hosp_stock_user2_dose2)){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_sql_hosp_stock_user2_dose2, "i", $user_hosp_nabh);
				mysqli_stmt_execute($stmt_sql_hosp_stock_user2_dose2);
				$resultData_hospital_stock_user2_dose2 = mysqli_stmt_get_result($stmt_sql_hosp_stock_user2_dose2);

				$row_hospital_stock_data = mysqli_fetch_assoc($resultData_hospital_stock_user2_dose2);
				$m = $row_hospital_stock_data['curr_covisheild'] + 1;

				//updating hospital stock current covaxin count for given hospital_nabh_ID
				$cancelquery_hospital_stock_user2_dose2 = "UPDATE hospital_stock SET curr_covisheild = '".$m."' WHERE hospital_nabh_ID = '".$user_hosp_nabh."';";
				$result_hospital_stock_user2_dose2 = mysqli_query($con_hosp, $cancelquery_hospital_stock_user2_dose2);

				if(!$result_hospital_stock_user2_dose2){
					header("Location: index.php?error=stmtfailed");
					exit();
				}

				else{
					header("location: index.php");
					exit();
				}
		}

	}
	*/
	/*
		$hospital_nabh_ID = $_SESSION['hospital_nabh_id'];
		$sql_vaccine_date = "SELECT * FROM vaccine WHERE hospital_nabh_ID = ? ;";
		$stmt_vaccine_date = mysqli_stmt_init($con_hosp);

		if(!mysqli_stmt_prepare($stmt_vaccine_date, $sql_vaccine_date)){
			header("Location: patient_dashboard.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt_vaccine_date, "i", $hospital_nabh_ID);
		mysqli_stmt_execute($stmt_vaccine_date);
		$resultData_vaccine_date = mysqli_stmt_get_result($stmt_vaccine_date);

		$row_vaccine_count = mysqli_fetch_assoc($resultData_vaccine_date);
		$vaccine_per_day = $row_vaccine_count['vaccine_per_day'] - 1;

		$query_vaccine_count = "UPDATE vaccine SET vaccine_per_day = ".$vaccine_per_day." WHERE hospital_nabh_ID = ".$hospital_nabh_ID.";";
		$result_vaccine_count = mysqli_query($con_hosp, $query_vaccine_count);

		if($result_vaccine_count){
			header("Location: patient_dashboard.php");
			exit();
		}

		else{
			header("Location: patient_dashboard.php?error=stmtfailed");
			exit();
		}
		*/
		/*
		$hospital_nabh_ID = $_SESSION['hospital_nabh_id'];
		$day = $_SESSION['day'];

		$sql_vaccine_date = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ? ;";
		$stmt_vaccine_date = mysqli_stmt_init($con_hosp);

		if(!mysqli_stmt_prepare($stmt_vaccine_date, $sql_vaccine_date)){
			header("Location: index2.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt_vaccine_date, "i", $hospital_nabh_ID);
		mysqli_stmt_execute($stmt_vaccine_date);
		$resultData_vaccine_date = mysqli_stmt_get_result($stmt_vaccine_date);

		$row_vaccine_count = mysqli_fetch_assoc($resultData_vaccine_date);
		$vaccine_day = "vaccine_per_day".$day;

		$vaccine_per_day = $row_vaccine_count[$vaccine_day] - 1;

		$query_vaccine_count = "UPDATE vaccine_date SET ".$vaccine_day." = ".$vaccine_per_day." WHERE hospital_nabh_ID = ".$hospital_nabh_ID.";";
		$result_vaccine_count = mysqli_query($con_hosp, $query_vaccine_count);

		if(!$result_vaccine_count){
			header("Location: index2.php?error=stmtfailed");
			exit();
		}

		$sql_hosp_stock = "SELECT * FROM hospital_stock WHERE hospital_nabh_id = ? ;";
		$stmt_hosp_stock = mysqli_stmt_init($con_hosp);

		if(!mysqli_stmt_prepare($stmt_hosp_stock, $sql_hosp_stock)){
			header("Location: index2.php?error=stmtfailed");
			exit();
		}

		mysqli_stmt_bind_param($stmt_hosp_stock, "i", $hospital_nabh_ID);
		mysqli_stmt_execute($stmt_hosp_stock);
		$resultData_hosp_stock = mysqli_stmt_get_result($stmt_hosp_stock);

		$row_hosp_stock = mysqli_fetch_assoc($resultData_hosp_stock);
		$cur_covaxin = $row_hosp_stock['curr_covaccine'] - 1;

		$query_hosp_stock = "UPDATE hospital_stock SET curr_covaccine = ".$cur_covaxin." WHERE hospital_nabh_id = ".$hospital_nabh_ID.";";
		$result_hosp_stock = mysqli_query($con_hosp, $query_hosp_stock);

		if($result_hosp_stock){
			header("Location: index2.php");
			exit();
		}

		else{
			header("Location: index2.php?error=stmtfailed");
			exit();
		}
		*/
