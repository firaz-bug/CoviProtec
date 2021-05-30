<?php

	session_start();
	include("connection.php");
	include("connection_hosp.php");

	if(!(isset($_SESSION["aadhar_ID"]))){
		header("Location: login.php");
	}

	if(isset($_GET["error"])){
		if($_GET["error"] == "emptyinput"){
			echo "<p>Enter all the fiels</p>";
		}
		else if($_GET["error"] == "invalidname"){
			echo "<p>Name can contain only alphabets</p>";
		}
		else if($_GET["error"] == "passworddontmatch"){
			echo "<p>Password doesn't match</p>";
		}
		else if($_GET["error"] == "stmtfailed"){
			echo "<p>Something went wrong. Try again.</p>";
		}
		else if($_GET["error"] == "none"){
			echo "<p>Congratulations! You have successfully registered.</p>";
		}
		else if($_GET["error"] == "idexists"){
			echo "<p>Invalid Aadhar ID</p>";
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
			return $row;
		}

		else{
			$result = false;
			return $result;
		}
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>CoviProtec</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/logo.png" rel="icon">
  <link href="../assets/img/logo.png" rel="logo">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body>



  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

      <h1 class="logo me-auto"><a href="index.php">CoviProtec</a></h1>
      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link" href="logout.php">Log out</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

      <a href="index.php" class="appointment-btn scrollto"><span class="d-none d-md-inline">Return to</span> Dashboard</a>

    </div>
  </header>
  <!-- End Header -->
<br>
<br>
<br>
<br>
	<form method="POST">

		<div class="container" style="text-align: center;">
			<label for="dose"><b>Choose The Dose For</b></label>
			<?php

					if(isset($_POST['user1'])){
						$_SESSION['user_number'] = 1;
					}

					else if(isset($_POST['user2'])){
						$_SESSION['user_number'] = 2;
					}

					else if(isset($_POST['combined'])){
						$_SESSION['user_number'] = 3;
					}

					if($_SESSION['user_number'] == 1){
						echo "Aadhar Id : ";
						echo $_SESSION["aadhar_ID"];
						echo "<br>";
						$resultexist = idExists($con, $_SESSION['aadhar_ID']);
						if (!$resultexist){
							echo"<select name='dose' id='dose' class='form-select' required>
										<option value=''>NIL</option>
										<option value='dose1'>dose1</option>

							</select>";
						}
						else{

							if($resultexist['dose1'] == 0){
								echo"<select name='dose' id='dose' class='form-select' required>
											<option value=''>NIL</option>
											<option value='dose1'>dose1</option>
											</select>";
							}
							else if($resultexist['dose1'] == 1){


								$now1 = date("Y-m-d");
								$now = date_create($now1);
								$diff = date_diff(date_create($resultexist['dose1_date']),$now);

								if( $diff->format("%R") == '+'){
										$numdays = intval($diff->format("%a"));
										if( $numdays >= 30){
														echo "<select name='dose' id='dose' class='form-select' required>
																	<option value=''>NIL</option>
																	<option value='dose2'>dose2</option>
																	</select>";
										}
										else{
													echo"<select name='dose' id='dose' class='form-select' required>
														<option value=''>NIL</option>
														</select>";

													echo "<p>It is advised that the second dose of vaccine should be taken only after a 30 days interval!
																the option for choosing dose2 will be available only after 30 days have passed after taking dose1</p>";
												}
								}

								else{
										echo"<select name='dose' id='dose' class='form-select' required>
												<option value=''>NIL</option>
												</select>";
												echo "<p>Your dose 1 is yet to be completed</p>";
								}

							}

							else	if($resultexist['dose2'] == 1){
									echo"<select name='dose' id='dose' required>
												<option value=''>NIL</option>
												</select>";

							}
		
						}
					}

					else if($_SESSION['user_number'] == 2){
						echo "Aadhar Id : ";
						echo $_SESSION["aadhar_ID_person2"];
						echo "<br>";
						$resultexist = idExists($con, $_SESSION['aadhar_ID_person2']);
						if (!$resultexist) {
							// code...
							echo"<select name='dose' id='dose' class='form-select' required>
										<option value=''>NIL</option>
										<option value='dose1'>dose1</option>

							</select>";
						}
						else{
							if($resultexist['dose1'] == 0){
								echo"<select name='dose' id='dose' class='form-select' required>
											<option value=''>NIL</option>
											<option value='dose1'>dose1</option>
											</select>";
							}
							else if($resultexist['dose1'] == 1){


								$now1 = date("Y-m-d");
								$now = date_create($now1);
								$diff = date_diff(date_create($resultexist['dose1_date']),$now);

								if( $diff->format("%R") == '+'){
										$numdays = intval($diff->format("%a"));
										if( $numdays >= 30){
														echo "<select name='dose' id='dose'
														class='form-select' required>
																	<option value=''>NIL</option>
																	<option value='dose2'>dose2</option>
																	</select>";
										}
										else{
													echo"<select name='dose' id='dose' class='form-select' required>
														<option value=''>NIL</option>
														</select>";

													echo "<p>It is advised that the second dose of vaccine should be taken only after a 30 days interval!
																the option for choosing dose2 will be available only after 30 days have passed after taking dose1</p>";
												}
									}

							else{
									echo"<select name='dose' id='dose' class='form-select' required>
											<option value=''>NIL</option>
											</select>";
											echo "<p>Your dose 1 is yet to be completed</p>";
										}

							}
							else	if($resultexist['dose2'] == 1){
									echo"<select name='dose' id='dose' class='form-select' required>
												<option value=''>NIL</option>
												</select>";
								}
						}
					}
					else if($_SESSION['user_number'] == 3){
						echo "Aadhar ID : ";
						echo $_SESSION["aadhar_ID"];
						echo " And ";
						$resultexist1 = idExists($con, $_SESSION['aadhar_ID']);
						echo $_SESSION["aadhar_ID_person2"];
						echo "<br>";
						$resultexist2 = idExists($con, $_SESSION['aadhar_ID_person2']);

							if(!$resultexist1){
								createEntry($con, $_SESSION['aadhar_ID']);
							}
							if(!$resultexist2){
								createEntry($con, $_SESSION['aadhar_ID_person2']);
							}

							//IF ANYONE HAS ALREADY TAKEN VACCINE DOSE 2
							if($resultexist1['dose1'] == 0 && $resultexist2['dose1'] == 0){
								echo"<select name='dose' id='dose' class='form-select' required>
											<option value=''>NIL</option>
											<option value='dose1'>dose1</option>
											</select><br>";
							}
							//IF ANYONE HAS ALREADY TAKEN DOSE 1
							else if(($resultexist1['dose1'] == 1 && $resultexist2['dose1'] == 0) || ($resultexist1['dose1'] == 0 && $resultexist2['dose1'] == 1) || ($resultexist1['dose2'] == 1 && $resultexist2['dose2'] == 0) ||
							 ($resultexist1['dose2'] == 0 && $resultexist2['dose2'] == 1)){
								echo"Sorry you guys cant book together since one of you have already taken dose 1, Please proceed to book individually.";
							}
							//IF BOTH HAVE NOT TAKEN ANYTHING
							else if($resultexist1['dose1'] == 1 && $resultexist2['dose1'] == 1){

								$now1 = date("Y-m-d");
								$now = date_create($now1);
								$diff1 = date_diff(date_create($resultexist1['dose1_date']),$now);
								$diff2 = date_diff(date_create($resultexist2['dose1_date']),$now);
								if( $diff1->format("%R") == '+' &&  $diff2->format("%R") == '+'){
										$numdays1 = intval($diff1->format("%a"));
										$numdays2 = intval($diff2->format("%a"));
										if( $numdays1 >= 30 && $numdays2 >= 30){
											echo "<select name='dose' id='dose' class='form-select' required>
														<option value=''>NIL</option>
														<option value='dose2'>dose2</option>
														</select>";
										}
										else{
													echo"<select name='dose' id='dose' class='form-select' required>
															<option value=''>NIL</option>
															</select>";
													echo "<p>It is advised that the second dose of vaccine should be taken only after a 30 days interval!
																the option for choosing dose2 will be available only after 30 days have passed after taking dose1</p>";
												}
								}
								else{
									echo"<select name='dose' id='dose' class='form-select' required>
											<option value=''>NIL</option>
											</select>";
											echo "<p>Your dose 1 is yet to be completed</p>";
										}


							}
							else if($resultexist1['dose2'] == 1 && $resultexist2['dose2'] == 1){
								echo "You both have already been vaccinated successfully and cannot book again, thank you";
							}

					}
					?>
					<br>
<!--
		<select name="dose" id="dose" required>
			<option value="">NIL</option>
			<option value="dose1">dose1</option>
			<option value="dose2">dose2</option>
		</select>
-->
		<label for="city">Choose a city:</label>

		<select name="city" id="city" class="form-select">
			<option value="chennai">Chennai</option>
			<option value="coimbatore">Coimbatore</option>
			<option value="pondy">pondicherry</option>
			<option value="madurai">Madurai</option>
			<option value="trichy">Tiruchirappalli</option>
			<option value="salem">Salem</option>
			<option value="vellore">Vellore</option>
			<option value="thanjavur">Thanjavur</option>
			<option value="ooty">Ooty</option>
			<option value="kanyakumari">Kanyakumari</option>
			<option value="hosur">Hosur</option>
			<option value="tirunelveli">Tirunelveli</option>
			<option value="kodaikanal">Kodaikanal</option>
			<option value="rameshwaram">Rameshwaram</option>
			<option value="namakkal">Namakkal</option>
			<option value="krishnagiri">Krishnagiri</option>
		</select>			
		</select>
		<br>
		<input type="submit" class="btn btn-primary" value="Find" name="Find">
	</div>
	</form>
	<br>
	<br>
	<br>

    <section id="appointment" class="appointment section-bg">
      <div class="container">
		<form method="POST" action="patient_dashboard.inc.functions.php">

		<?php
			if(isset($_POST['Find'])){

				$city = $_POST['city'];
				$_SESSION['city'] = $city;
				$_SESSION['dose'] = $_POST['dose'];

				$sql_hosp = "SELECT * FROM hospital_details WHERE hospital_city = ?;";
				$stmt_hosp = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_hosp, $sql_hosp)){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_hosp, "s", $city);
				mysqli_stmt_execute($stmt_hosp);

				$resultData = mysqli_stmt_get_result($stmt_hosp);

				while($row = mysqli_fetch_assoc($resultData)){

					echo "	<table class='table'>
							<thead>
							<tr>
							<td><b>NABH ID:</b></td>
							<td>".$row["hospital_nabh_id"]."</td>
							<td></td>
							<td></td>
						  	</tr>
						  	<tr>
							<td><b>Name:</b></td>
							<td>".$row["hospital_name"]."</td>
							<td></td>
							<td></td>
						  	</tr>
						  	</thead>
						 ";

				$sql_vaccine = "SELECT * FROM hospital_stock WHERE hospital_nabh_id = ?;";
				$stmt_vaccine = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_vaccine, $sql_vaccine)){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_vaccine, "i", $row['hospital_nabh_id']);
				mysqli_stmt_execute($stmt_vaccine);

				$resultData_vaccine = mysqli_stmt_get_result($stmt_vaccine);
				$row_vaccine = mysqli_fetch_assoc($resultData_vaccine);
				
				//SELECTING FROM VACCINE DATE FOR VACCINE PER DAY
				$sql_vaccine_per_day = "SELECT * FROM vaccine_date WHERE hospital_nabh_id = ?;";
				$stmt_vaccine_per_day = mysqli_stmt_init($con_hosp);

				if(!mysqli_stmt_prepare($stmt_vaccine_per_day, $sql_vaccine_per_day)){
					header("Location: index2.php?error=stmtfailed");
					exit();
				}

				mysqli_stmt_bind_param($stmt_vaccine_per_day, "i", $row['hospital_nabh_id']);
				mysqli_stmt_execute($stmt_vaccine_per_day);

				$resultData_vaccine_per_day = mysqli_stmt_get_result($stmt_vaccine_per_day);
				$row_vaccine_per_day = mysqli_fetch_assoc($resultData_vaccine_per_day);


	//			$_SESSION['hospital_nabh_id'] = $row["hospital_nabh_id"];
				echo "<tr>
						<th>Date</th>
						<th>To register for covaxin</th>
						<th>To register for covishield</th>
						<th>Doses per Day</th>
					</tr>";
				$j = 1;
				while($j < 8){

					$d = strtotime("+$j day");
					echo "<tr><th>".date("Y-m-d ", $d)."</th>";

					$covaxin =  $row['hospital_nabh_id']."covaxin".$j;
					$covishield = $row['hospital_nabh_id']."covishield".$j;
					$vaccine_stock_per_day = "vaccine_per_day".$j;
					
					if($row_vaccine['curr_covaccine'] > 0 && $row_vaccine_per_day[$vaccine_stock_per_day] > 0){
						if($_SESSION['user_number'] == 3 && ($row_vaccine_per_day[$vaccine_stock_per_day] <= 1 || $row_vaccine['curr_covaccine'] <= 1)){
							echo "<th>Only one vaccine is left for that day. So both of you cannot book.</th>"; 
						}
						else{
							#echo "<br>Covaxin Count: ".$row_vaccine['curr_covaccine'];
							echo "<th><input type='submit' class='btn btn-primary' name='".$covaxin."' value='covaxin'>
								</th>
								";
						}
					}
					else{
						echo "<th>Covaxin Not available! Or the upper limit of total number of vaccines per day of the hospital has crossed!</th>";
					}
					
					if($row_vaccine['curr_covisheild'] > 0 && $row_vaccine_per_day[$vaccine_stock_per_day] > 0){
						if($_SESSION['user_number'] == 3 && ($row_vaccine_per_day[$vaccine_stock_per_day] <= 1 || $row_vaccine['curr_covisheild'] <= 1)){
							echo "<th>Only one vaccine is left for that day. So both of you cannot book.</th>"; 
						}
						else{
							#echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
							echo "<th><input type='submit' class='btn btn-primary' name='".$covishield."' value='covishield'></th>";
						}
					}
					else{
						echo "<th>Covishield Not available! Or the upper limit of total number of vaccines per day of the hospital has crossed!</th>";
					}
					/*	
					#	echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
						echo "<th><input type='submit' class='btn btn-primary' name='".$covishield."' value='covishield'></th>";
					}
					else{
						echo "<th>Covishield Not available!</th>";
					}*/
					echo "<td>".$row_vaccine['VaccinesPerDay']."</td></tr>";

					$j = $j + 1;
					$_SESSION['date'] = $d;

				#	echo "<input type='submit' name='submit' value='submit'><br>";
				}
				}

			}
			?>

		</form>
		</div>
	</section>
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/vendor/purecounter/purecounter.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>
</body>
</html>
