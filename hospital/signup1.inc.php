<?php

#	include("upload.php");
	include("connection1.php");
	include("functions1.php");
	if(isset($_POST['submit'])){

		$file = $_FILES["license"];

		$fileName = $_FILES["license"]["name"];
		$fileTmpName = $_FILES["license"]["tmp_name"];
		$fileSize = $_FILES["license"]["size"];
		$fileError = $_FILES["license"]["error"];

		$fileExt = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));

		$allowed = array('pdf', 'jpg', 'jpeg');

		if(in_array($fileActualExt, $allowed)){

			if( $fileError === 0 ){

				if($fileSize < 500000){
					$fileNameNew = uniqid('', true).".".$fileActualExt;
					$fileDestination = 'uploads1/'.$fileNameNew;
					move_uploaded_file($fileTmpName, $fileDestination);
				}

				else{
					header("Location: signup1.php?error=filetoobig");
					exit();
					echo "File is too big! File size should be less than 500KB.";
				}

			}

			else{
				header("Location: signup1.php?error=fileuploaderror");
				exit();
				echo "There was an error uploading your file!";
			}
		}

		else{
			header("Location: signup1.php?error=fileincorrectformat");
			exit();
			echo "Files of type PDF or JPG or JPEG can be uploaded";
		}

		$license = $fileNameNew;
		$nabh_id = $_POST['nabh_id'];
		$hname = $_POST['hname'];
		$email = $_POST['email'];
		$city = $_POST['city'];
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$password = $_POST['password'];
		$con_password = $_POST['con_password'];


		if(emptyInputSignUp($license, $nabh_id, $hname, $email, $city, $phone, $password, $con_password, $address) !== false){
			header("Location: signup1.php?error=emptyinput");
			exit();
		}

		if(pwdMatch( $password, $con_password ) !== false){
			header("Location: signup1.php?error=passworddontmatch");
			exit();
		}

		if(idExists( $con, $nabh_id ) !== false){
			header("Location: signup1.php?error=idexists");
			exit();
		}


		createUser($con, $license, $nabh_id, $hname, $email, $city, $phone, $password, $address);

	}

	else{
		header("Location: signup1.php");
		exit();
	}
