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
?>
<!DOCTYPE HTML>
<html>
<head> 
	<title>Vaccine Booking</title>
</head>
<body>
	
	<a href = "logout.php">Logout</a>
	<br><br>
	<a href = "index.php">Go to dashboard!</a>
	<br><br>
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
				echo "Welcome Current User.";
				echo $_SESSION["name"];
				echo $_SESSION["aadhar_ID"];
				echo "<br>";
			}
			else if($_SESSION['user_number'] == 2){
				echo "Welcome second Member.";
				echo $_SESSION["aadhar_ID_person2"];
				echo "<br>";
			}
			else if($_SESSION['user_number'] == 3){
				echo "Welcome. You have chosen for combined booking!";
				echo $_SESSION["aadhar_ID"];
				echo "<br>";
				echo $_SESSION["aadhar_ID_person2"];
				echo "<br>";
			}
			?>
			
	<br>
	<form method="POST">
		<label for="dose">Choose the Dose:</label>
		<select name="dose" id="dose" required>
			<option value="">NIL</option>
			<option value="dose1">dose1</option>
			<option value="dose2">dose2</option>
		</select>

		<label for="city">Choose a city:</label>
		
		<select name="city" id="city">
			<option value="chennai">chennai</option>
			<option value="coimbatore">coimbatore</option>
			<option value="pondy">pondy</option>
			<option value="madurai">madurai</option>
		</select>
		
		<input type="submit" value="Find" name="Find">
	</form>
	<br>
	<br>
	<br>
	
	<form method="POST" action="patient_dashboard.inc.functions.php">
	
	<?php
		if(isset($_POST['Find'])){
			 
			$city = $_POST['city'];
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
	
				echo "<tr>
						<td><b>NABH ID:</b></td>
						<td>".$row["hospital_nabh_id"]."</td>
					  </tr>
					  <tr>
						<td><b>Name:</b></td>
						<td>".$row["hospital_name"]."</td>
					  </tr><br>";
			
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
			
			$_SESSION['hospital_nabh_id'] = $row["hospital_nabh_id"];
			
			$j = 1;
			while($j < 8){
				
				$d = strtotime("+$j day");
				echo date("Y-m-d ", $d);
				
				$covaxin = "covaxin".$j;
				$covishield = "covishield".$j;
				if($row_vaccine['curr_covaccine'] > 0){
				#	echo "<br>Covaxin Count: ".$row_vaccine['curr_covaccine'];
					echo "<input type='submit' name='".$covaxin."' value='covaxin'>";	
				}	
				else{
					echo "Covaxin Not available!";
				}
				if($row_vaccine['curr_covisheild'] > 0){
				#	echo "<br>Covishield Count: ".$row_vaccine['curr_covisheild'];
					echo "<input type='submit' name='".$covishield."' value='covishield'>";
				}
				else{
					echo "Covishield Not available!";
				}
				echo "<br>Vaccines Per Day: ".$row_vaccine['VaccinesPerDay'];
				echo "<br>";
				
				$j = $j + 1;
				$_SESSION['date'] = $d;
				
			#	echo "<input type='submit' name='submit' value='submit'><br>";
			}
			}
			
		}	
		?>
			
	</form>
	
	</div>
	
</body>
</html>