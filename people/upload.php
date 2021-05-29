<?php

	if(isset($_POST["submit"])){
		
		
		
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
					header("Location: signup.php?uploadsuccess");
					exit();
				}
				
				else{
					echo "File is too big! File size should be less than 500KB.";
				}
				
			}
			
			else{
				echo "There was an error uploading your file!";
			}		
		}
	
		else{
			echo "Files of type PDF or JPG or JPEG can be uploaded";
		}
	
	}
?>