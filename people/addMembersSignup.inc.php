<?php

	session_start();
	include("connection.php");
	include("addMembersFunctions.php");
	if(isset($_POST['submit'])){
		
		$file = $_FILES["aadhar"];
		
		$fileName = $_FILES["aadhar"]["name"];
		$fileTmpName = $_FILES["aadhar"]["tmp_name"];
		$fileSize = $_FILES["aadhar"]["size"];
		$fileError = $_FILES["aadhar"]["error"];

		$fileExt = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));
		
		$allowed = array('pdf', 'jpg', 'jpeg');
		
		if(in_array($fileActualExt, $allowed)){
	
			if( $fileError === 0 ){
				
				if($fileSize < 500000){
					$fileNameNew = uniqid('', true).".".$fileActualExt;
					$fileDestination = 'uploads/'.$fileNameNew;
					move_uploaded_file($fileTmpName, $fileDestination);
				}
				
				else{
					header("Location: index.php?error=filetoobig");
					exit();
					echo "File is too big! File size should be less than 500KB.";
				}
				
			}
			
			else{
				header("Location: index.php?error=fileuploaderror");
				exit();
				echo "There was an error uploading your file!";
			}		
		}
	
		else{
			echo "Files of type PDF or JPG or JPEG can be uploaded";
		}
	
		$aadhar = $fileNameNew;
		$aadhar_ID = $_POST['aadhar_ID'];
		$name = $_POST['name'];
		$gender = $_POST['gender'];
		$age = $_POST['age'];
		$phone = $_SESSION["phone"];
		
		if(emptyInputSignUp($aadhar, $aadhar_ID, $name, $gender, $age) !== false){
			header("Location: index.php?error=emptyinput");
			exit();
		}
		
		if(invalidName( $name ) !== false){
			header("Location: index.php?error=invalidname");
			exit();
		}
		
		if(idExists( $con, $aadhar_ID ) !== false){
			header("Location: index.php?error=idexists");
			exit();
		}
		
	
		createUser($con, $aadhar, $aadhar_ID, $name, $gender, $age, $phone);
		
	}
	
	else{
		header("Location: index.php?error=fileincorrectformat");
			exit();
		header("Location: index.php");
		exit();
	}