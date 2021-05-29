<?php

	session_start();
	#include("connection.php");
	include("connection1.php");

#	if(!(isset($_SESSION["aadhar_ID"]))){
#		header("Location: login.php");
#	}

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

	function countUser(){

			$sql_user = "SELECT * FROM people WHERE phone = ?;";
			$stmt_user = mysqli_stmt_init($con);
			$count = 0;

			if(!mysqli_stmt_prepare($stmt_user, $sql_user)){
				header("Location: patient_dashboard.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_user, "i", $_SESSION["phone"]);
			mysqli_stmt_execute($stmt_user);

			$resultData = mysqli_stmt_get_result($stmt_user);

			while($row = mysqli_fetch_assoc($resultData)){
				$count = $count + 1;
			}
	}
?>

<!DOCTYPE HTML>
<html>
<head>
	<title>Index_page</title>
</head>
<body>

<!--	<a href = "logout.php">Logout</a>
--><h1>Welcome to index page</h1>

	<br>
<!--	Hello, <?php #echo $_SESSION["name"]; ?>
-->
	<br>
	<br>
	<h2>Add Members. You can add upto a maximum of three members.</h2>
	<br>
	<button onclick="addPerson()">Person 2</button>
	<br>
	<br>

		<form action="" method="post" enctype="multipart/form-data" id="registerForm" style="display: none;">
			<table align="center" width="50%" cellpadding="5px" cellspacing="2px" border="2" style="background-image: linear-gradient(lightgray, gray);">
				<tr>
					<th colspan="2">Login</th>
				</tr>
				<tr>
					<td><label for="aadhar">Photo ID proof</label></td>
					<td><input type="file" name="aadhar"></td>
				</tr>
				<tr>
					<td><label for="aadhar_ID">Aadhar ID</label></td>
					<td><input id="aadhar_ID" type="Number" name="aadhar_ID" placeholder="Enter your aadhar ID"></td>
				</tr>
				<tr>
					<td><label for="name">Name as in Aadhar</label></td>
					<td><input id="name" type="text" name="name" placeholder="Enter your name as in aadhar card"></td>
				</tr>
				<tr>
					<td><label for="gender">Gender</td>
					<td>
					<input id = "gender" type="radio" name="gender" value="male">Male<br>
					<input id = "gender" type="radio" name="gender" value="female">Female<br>
					<input id = "gender" type="radio" name="gender" value="other">Others<br>
					</td>
				</tr>
				<tr>
					<td><label for="age">Age</label></td>
					<td><input id="age" type="Number" name="age" placeholder="Enter your age"></td>
				</tr>
				<tr rowspan="2">
					<td colspan="2"><input id="button" type="submit" value="Submit" name="submit">
					<input id="button" type="reset" value="Reset" name="reset"><br>
				</tr>
			</table>
		</form>

	<h2>Booking Appointment</h2>
	<br>
	<button onclick="selectPerson()">Current user</button>
	<br>

	<div id="bookingAppointment">
	<br>
	<br>

	<form action="patient_dashboard.php" method="POST">
		<label for="city">Choose a city:</label>

		<select name="city" id="city">
			<option value="chennai">chennai</option>
			<option value="coimbatore">coimbatore</option>
			<option value="pondy">pondy</option>
			<option value="madurai">madurai</option>
		</select>

		<input id="button" type="submit" value="Find" name="Find">
	</form>
	<br>
	<br>
	<br>

	<form method="POST" action="patient_dashboard.php">
	<?php
		if(isset($_POST['Find'])){

			$city = $_POST['city'];

			$sql_hosp = "SELECT * FROM hospital_details WHERE hospital_city = ?;";
			$stmt_hosp = mysqli_stmt_init($con);

			if(!mysqli_stmt_prepare($stmt_hosp, $sql_hosp)){
				header("Location: patient_dashboard.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_hosp, "s", $city);
			mysqli_stmt_execute($stmt_hosp);

			$resultData = mysqli_stmt_get_result($stmt_hosp);

			while($row = mysqli_fetch_assoc($resultData)){

				echo "<tr>
						<td><b>NABH ID:</b></td>
						<td>".$row["hospital_nabh_id"]."</td>
					  </tr>
					  <tr>
						<td><b>Name:</b></td>
						<td>".$row["hospital_name"]."</td>
					  </tr><br>";

			$sql_vaccine = "SELECT * FROM hospital_stock WHERE hospital_nabh_id = ?;";
			$stmt_vaccine = mysqli_stmt_init($con);

			if(!mysqli_stmt_prepare($stmt_vaccine, $sql_vaccine)){
				header("Location: patient_dashboard.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt_vaccine, "i", $row['hospital_nabh_id']);
			mysqli_stmt_execute($stmt_vaccine);

			$resultData_vaccine = mysqli_stmt_get_result($stmt_vaccine);
			$row_vaccine = mysqli_fetch_assoc($resultData_vaccine);

			echo date("Y-m-d");
			if($row_vaccine['curr_covaccine'] > 0){
				echo "<br>Covaxin Count: ".$row_vaccine['curr_covaccine'];
			}
			else{
				echo "Covaxin Not available!";
			}
			if($row_vaccine['curr_covisheild'] > 0){
				echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
			}
			else{
				echo "Covishield Not available!";
			}
			echo "<br>Vaccines Per Day: ".$row_vaccine['VaccinesPerDay'];
			echo "<br>";
			?>
			<input type="submit" value="covaxin">
			<input type="submit" value="covishield"><br>

			<?php
			$d = strtotime("+1 day");
			echo date("Y-m-d", $d);
			if($row_vaccine['curr_covaccine'] > 0){
				echo "<br>Covaxin Count: ".$row_vaccine['curr_covaccine'];
			}
			else{
				echo "Covaxin Not available!";
			}
			if($row_vaccine['curr_covisheild'] > 0){
				echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
			}
			else{
				echo "Covishield Not available!";
			}
			echo "<br>Vaccines Per Day: ".$row_vaccine['VaccinesPerDay'];
			echo "<br>";
			?>
			<input type="submit" value="covaxin">
			<input type="submit" value="covishield"><br>

			<?php
			$d = strtotime("+2 day");
			echo date("Y-m-d", $d);
			if($row_vaccine['curr_covaccine'] > 0){
				echo "<br>Covaxin Count: ".$row_vaccine['curr_covaccine'];
			}
			else{
				echo "Covaxin Not available!";
			}
			if($row_vaccine['curr_covisheild'] > 0){
				echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
			}
			else{
				echo "Covishield Not available!";
			}
			echo "<br>Vaccines Per Day: ".$row_vaccine['VaccinesPerDay'];
			echo "<br>";
			?>
			<input type="submit" value="covaxin">
			<input type="submit" value="covishield"><br>

			<?php
			$d = strtotime("+3 day");
			echo date("Y-m-d", $d);
			if($row_vaccine['curr_covaccine'] > 0){
				echo "<br>Covaxin Count: ".$row_vaccine['curr_covaccine'];
			}
			else{
				echo "Covaxin Not available!";
			}
			if($row_vaccine['curr_covisheild'] > 0){
				echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
			}
			else{
				echo "Covishield Not available!";
			}
			echo "<br>Vaccines Per Day: ".$row_vaccine['VaccinesPerDay'];
			echo "<br>";
			?>
			<input type="submit" value="covaxin">
			<input type="submit" value="covishield"><br>

			<?php
			$d = strtotime("+4 day");
			echo date("Y-m-d", $d);
			if($row_vaccine['curr_covaccine'] > 0){
				echo "<br>Covaxin Count: ".$row_vaccine['curr_covaccine'];
			}
			else{
				echo "Covaxin Not available!";
			}
			if($row_vaccine['curr_covisheild'] > 0){
				echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
			}
			else{
				echo "Covishield Not available!";
			}
			echo "<br>Vaccines Per Day: ".$row_vaccine['VaccinesPerDay'];
			echo "<br>";
			?>
			<input type="submit" value="covaxin">
			<input type="submit" value="covishield"><br>

			<?php
			$d = strtotime("+5 day");
			echo date("Y-m-d", $d);
			if($row_vaccine['curr_covaccine'] > 0){
				echo "<br>Covaxin Count: ".$row_vaccine['curr_covaccine'];
			}
			else{
				echo "Covaxin Not available!";
			}
			if($row_vaccine['curr_covisheild'] > 0){
				echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
			}
			else{
				echo "Covishield Not available!";
			}
			echo "<br>Vaccines Per Day: ".$row_vaccine['VaccinesPerDay'];
			echo "<br>";
			?>
			<input type="submit" value="covaxin">
			<input type="submit" value="covishield"><br>

			<?php
			$d = strtotime("+6 day");
			echo date("Y-m-d", $d);
			if($row_vaccine['curr_covaccine'] > 0){
				echo "<br>Covaxin Count: ".$row_vaccine['curr_covaccine'];
			}
			else{
				echo "Covaxin Not available!";
			}
			if($row_vaccine['curr_covisheild'] > 0){
				echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
			}
			else{
				echo "Covishield Not available!";
			}
			echo "<br>Vaccines Per Day: ".$row_vaccine['VaccinesPerDay'];
			echo "<br>";
			?>
			<input type="submit" value="covaxin">
			<input type="submit" value="covishield"><br><br>
		<?php
			}
		}
		?>

	</form>

	</p>
	</div>
	<script>

	function addPerson() {
		var x = document.getElementById("registerForm");
		if (x.style.display === "none") {
			x.style.display = "block";
		}
		else {
			x.style.display = "none";
		}
	}
	function selectPerson() {
		var x = document.getElementById("bookingAppointment");
		if (x.style.display === "none") {
			x.style.display = "block";
		}
		else {
			x.style.display = "none";
		}
	}

	</script>

</body>
</html>
