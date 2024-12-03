<?php

	session_start();
	include("connection1.php");
	include("hospDashFunctions.php");

	$Currentvaccine_det = check_login($con);


	if(isset($_GET["error"])){
		if($_GET["error"] == "emptyinput"){
			echo "<p>Enter all the fields</p>";
		}
		if($_GET["error"] == "vaccinesPD"){
			echo "<p>Vaccines per day should be less than 100</p>";
		}
		if($_GET["error"] == "stmtfailed"){
			echo "<p>Something went wrong. Try again.</p>";
		}
		if($_GET["error"] == "none"){
			echo "<p>Thank you! Your stock has been updated.</p>";
		}
		if($_GET["error"] == "currdataNull"){
			echo "<p>You have not set your available number of vaccines.Please do so inorder to start vacination booking in your hospital.</p>";
		}


	}

	function idExists( $con, $nabh ){

			$sql = "SELECT * FROM vaccine_date WHERE hospital_nabh_ID = ?;";
			$stmt = mysqli_stmt_init($con);

			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("Location: index.php?error=stmtfailed");
				exit();
			}

			mysqli_stmt_bind_param($stmt, "i", $nabh);
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Kodinger">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Hospital Login Page</title>
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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../assets/css/my-login.css">
</head>

<body class="my-login-page" style="background-color:#D3D3D3">
	<div style="margin:2px; ">
		<a href="index1.php">
			<button class="btn btn-primary"><i class="bi bi-arrow-left-circle"></i> back to dashboard</button>
		</a>
	</div>
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div class="card-wrapper">
					<div class="brand">
						<img src="../assets/img/logo.png" alt="logo">
					</div>
					<div class="card fat">
						<div class="card-body">
							<h4 class="card-title">Hospital Stock update</h4>
							<form method="POST" action="hospDash.inc.php" enctype="multipart/form-data">

								<div class="form-group">
									<label for="new_covaccine">stock of covaxin to add </label>
									<input id="new_covaccine" type="number" class="form-control" name="new_covaccine" value="" required autofocus>
									<div class="invalid-feedback">
										invalid
									</div>
								</div>

								<div class="form-group">
									<label for="new_covisheild">stock of covishield to add</label>
									<input id="new_covisheild" type="number" class="form-control" name="new_covisheild" value="" required autofocus>
									<div class="invalid-feedback">
										invalid
									</div>
								</div>

								<?php
								if( !idExists($con,$_SESSION['user_id'])){

								echo"<div class='form-group'>
											<label for='vaccine_perDay'>Amount of vaccine to use per day</label>
											<input id='vaccines_perDay' type='number' class='form-control' name='vaccines_perDay' value='' required autofocus>
										<div class='invalid-feedback'>
										invalid
										</div>
								</div>";
							}
							?>

								<div class="form-group m-0">
									<button type="reset" class="btn btn-primary btn-block" name="reset">
										Reset
									</button>
								</div>
								<br>
								<div class="form-group m-0">
									<button type="submit" class="btn btn-primary btn-block" name="submit">
										Submit
									</button>
								</div>
							</form>
						</div>
					</div>
					<div class="footer">
						Copyright &copy; 2021 &mdash; Covid Drive
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="../assets/js/my-login.js"></script>
</body>
</html>
