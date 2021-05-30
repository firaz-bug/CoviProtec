<?php

	session_start();
	include("connection1.php");

	if(isset($_GET["error"])){
		if($_GET["error"] == "emptyinput"){
			echo "<p>Enter all the fields</p>";
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
			echo "<p>Invalid NABH ID</p>";
		}
		else if($_GET["error"] == "filetoobig"){
			echo "<p>File size is too big!! Size should be less than 500 KB!</p>";
		}
		else if($_GET["error"] == "fileuploaderror"){
			echo "<p>There was an error uploading your file! Try again!</p>";
		}
		else if($_GET["error"] == "fileincorrectformat"){
			echo "<p>Files of type PDF or JPG or JPEG can be uploaded!</p>";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Kodinger">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Hospital Registeration Page &mdash;</title>
	<link href="../assets/img/logo.png" rel="icon">
  	<link href="../assets/img/logo.png" rel="logo">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../assets/css/my-login.css">
</head>
<body class="my-login-page" style="background-color:#D3D3D3">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div class="card-wrapper">
					<div class="brand">
						<img src="../assets/img/logo.png" alt="">
					</div>
					<div class="card fat">
						<div class="card-body">
							<h4 class="card-title">Hospital Registeration</h4>
							<form action="signup1.inc.php" method="post" enctype="multipart/form-data" class="my-login-validation" novalidate="">
								<div class="form-group">
									<label for="name">Name Of Hospital</label>
									<input id="hname" type="text" class="form-control" name="hname" required autofocus>
									<div class="invalid-feedback">
										What's the Hospital name?
									</div>
								</div>

								<div class="form-group">
								<label for="name">Hospital email</label>
									<input id="email" type="email" class="form-control" name="email" required autofocus>
									<div class="invalid-feedback">
										What's the Hospital email?
									</div>
								</div>


								<div class="form-group">
									<label for="nabh_ID">NABH ID</label>
									<input id="nabh_id" type="number" class="form-control" name="nabh_id" required>
									<div class="invalid-feedback">
										 invalid nabh ID
									</div>
								</div>

								<div class="form-group">
									<label for="licence">Upload licence</label>
									<input id="license" type="file" class="form-control" name="license" required style="padding:2px;">
									<div class="invalid-feedback">
										 No file
									</div>
								</div>

								<div class="form-group">
								<label for="city">Select City of Operation</label>
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
								</div>

								<div class="form-group">
								<label for="address">Hospital address</label>
									<textarea name="address" rows="4" cols="40" placeholder="enter detailed area and street address here!..." id="address" required></textarea>
									<div class="invalid-feedback">
										What's the Hospital email?
									</div>
								</div>

								<div class="form-group">
									<label for="phone">phone number</label>
									<input id="phone" type="text" class="form-control" name="phone" required>
									<div class="invalid-feedback">
										 Enter phone number
									</div>
								</div>

								<div class="form-group">
									<label for="password">Password</label>
									<input id="password" type="password" class="form-control" name="password" required data-eye>
									<div class="invalid-feedback">
										Password is required
									</div>
								</div>

								<div class="form-group">
									<label for="con_password">Confirm Password</label>
									<input id="con_password" type="password" class="form-control" name="con_password" required data-eye>
									<div class="invalid-feedback">
										Password is required
									</div>
								</div>

								<div class="form-group">
									<div class="custom-checkbox custom-control">
										<input type="checkbox" name="agree" id="agree" class="custom-control-input" required="">
										<label for="agree" class="custom-control-label">I agree to the <a href="#">Terms and Conditions</a></label>
										<div class="invalid-feedback">
											You must agree with our Terms and Conditions
										</div>
									</div>
								</div>

								<div class="form-group m-0">
									<button type="submit" class="btn btn-primary btn-block" name="submit">
										Register
									</button>
								</div>
								<div class="mt-4 text-center">
									Already have an account? <a href="login1.php">Login</a>
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
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="../assets/js/my-login.js"></script>
</body>
</html>