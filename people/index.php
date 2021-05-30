<?php
  date_default_timezone_set("Asia/Kolkata");
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

	function countUser($con){

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

				if($row['password'] == NULL){
					$_SESSION['aadhar_ID_person2'] = $row['aadhar_ID'];
				}
			}

			return $count;
	}

	function getUser($con,$aadhar){

			$sql_user = "SELECT * FROM people WHERE aadhar_ID = ?;";
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

	      <h1 class="logo me-auto"><a href="index.php">Coviprotec</a></h1>
	      <nav id="navbar" class="navbar order-last order-lg-0">
	        <ul>
	          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
            <li><a class="nav-link scrollto" href="#bookedapointments">Booked Appointments</a></li>
	          <li><a class="nav-link scrollto" href="#addmember">Add Member</a></li>
	          <li><a class="nav-link scrollto" href="#about">About</a></li>
            <li><a class="nav-link scrollto" href="#faq">Faq</a></li>
	          <li><a class="nav-link" href="logout.php">Log out</a></li>
	        </ul>
	        <i class="bi bi-list mobile-nav-toggle"></i>
	      </nav><!-- .navbar -->
	      <a href="#appointment" class="appointment-btn scrollto"><span class="d-none d-md-inline">Book</span> Appointment</a>
	    </div>
  	</header>
<!-- End Header -->

<!-- ======= Hero Section ======= -->
 	<section id="hero" class="d-flex align-items-center">
	    <div class="container">
	      	<h1>Hello,<?php echo $_SESSION["name"]; ?></h1> 
	  		<h1>Welcome to CoviProtec</h1>
	      <a href="#about" class="btn-get-started scrollto">Get Started</a>
	    </div>
    </section>
<!-- End Hero -->

<!-- ======= Why Us Section ======= -->
    <section id="why-us" class="why-us">
      <div class="container">

        <div class="row">
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="content">
              <h3>Why CoviProtec?</h3>
              <p>
                Our aim is to facilitate vaccine registration. We with an common goal have partnered with other trustworthy organisations to provide you with best of services. We ensure that you can securely schedule an appointment to get vaccinated at a place of your convenience. Join us in our fight against <b>COVID-19</b>.
              </p>
              <div class="text-center">
                <a href="#" class="more-btn">Learn More <i class="bx bx-chevron-right"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="icon-boxes d-flex flex-column justify-content-center">
              <div class="row">
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box mt-4 mt-xl-0">
                    <i class="bx bx-receipt"></i>
                    <h4>Our service</h4>
                    <p>We provide informatuon on vaccine availability at different hospitals at your reach.</p>
                  </div>
                </div>
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box mt-4 mt-xl-0">
                    <i class="bx bx-cube-alt"></i>
                    <h4>Procedure</h4>
                    <p>We request you to take both the doses booked through our site. You will be prohibited to book for dose 2 directly without booking dose 1 through our site. You will be able to book for your dose 2 after 30 days have passed since taking dose 1.</p>
                  </div>
                </div>
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box mt-4 mt-xl-0">
                    <i class="bx bx-images"></i>
                    <h4>Other facilities</h4>
                    <p>We also provide you with the facility to cancel your appointment until 1 day before the booked date. We also provide you the link to get yourself a ride to the booked location.</p>
                  </div>
                </div>
              </div>
            </div><!-- End .content-->
          </div>
        </div>

      </div>
    </section>
<!-- End Why Us Section -->

<!-- this is the cancelation section -->
<section>
<div class="container" id="bookedapointments">
  <div class="section-title">
    <h2>Booked Appointments</h2>
  </div>
      <form action="cancel.inc.php" method = "POST">
      <?php
        $num = countUser($con);
        //to see if the user has only one user registered to account
        if ($num == 1)
        {
          //ID EXISTS FOR THE ONLY USER
          $exist = idExists( $con, $_SESSION['aadhar_ID'] );
          if($exist){
            //checking for vaccine type
            if ($exist['covaxin'] == 1){
              $vaccine = "covaxin";
            }

            else if($exist['covishield'] == 1){
              $vaccine = "covishield";
            }

            //looking to see whether user1 has registered for dose1
            if($exist['dose1'] == 1){

                $userdet = getUser($con, $_SESSION['aadhar_ID']);
                echo "<div class='alert alert-success'>
                      <li> aadhar_ID:".$userdet['aadhar_ID']."</li>
                      <li> Name:".$userdet['name']."</li>
                      <li>Gender:".$userdet['gender']."</li>
                      <li>Age:".$userdet['age']."</li>
                      <li>Phone Number:".$userdet['phone']."</li>
                      <li>dose 1</li>
                      <li>date of dose:".$exist['dose1_date']."</li>
                      <li>vaccine:".$vaccine."</li>";
      //                echo $exist['dose1_date'];
                      $now1 = date("Y-m-d");
                      $now = date_create($now1);
                      $diff = date_diff($now,date_create($exist['dose1_date']));
                      if( $diff->format("%R") == '+'){
                          $numdays = intval($diff->format("%a"));
                          if( $numdays >= 1 && $numdays <= 7){
                          echo "<a href='https://m.uber.com/looking' class='btn btn-dark'><i class='fab fa-uber'></i> Book Uber Ride</a>
                            <a href='https://www.olacabs.com/' class='btn btn-warning'><i class='fas fa-taxi'></i> Book Ola Ride</a>
                            <button type='submit' name='cancel_user1_dose1' class='btn btn-danger'>cancel appointment</button></div>";
                          }
                      }
            }

            //looking to see whether user1 has registered for dose1
            if($exist['dose2'] == 1){

                $userdet = getUser($con, $_SESSION['aadhar_ID']);
                echo "<div class='alert alert-success'>
                      <li> aadhar_ID:".$userdet['aadhar_ID']."</li>
                      <li> Name:".$userdet['name']."</li>
                      <li>Gender:".$userdet['gender']."</li>
                      <li>Age:".$userdet['age']."</li>
                      <li>Phone Number:".$userdet['phone']."</li>
                      <li>dose 2</li>
                      <li>date of dose:".$exist['dose2_date']."</li>
                      <li>vaccine:".$vaccine."</li>";
                      $now1 = date("Y-m-d");
                      $now = date_create($now1);
                      $diff = date_diff($now,date_create($exist['dose2_date']));
                      if( $diff->format("%R") == '+'){
                          $numdays = intval($diff->format("%a"));
                          if( $numdays >= 1 && $numdays <= 7){
                          echo "<a href='https://m.uber.com/looking' class='btn btn-dark'><i class='fab fa-uber'></i> Book Uber Ride</a>
                            <a href='https://www.olacabs.com/' class='btn btn-warning'><i class='fas fa-taxi'></i> Book Ola Ride</a>
                          <button type='submit' name='cancel_user1_dose2' class='btn btn-danger'>cancel appointment</button></div>";
                          }
                      }
            }
          }
        }
        //to see if the account has registered multiple users registered to it
        else if ($num == 2){

          //ID EXISTS FOR THE ONLY USER
          $exist = idExists( $con, $_SESSION['aadhar_ID'] );
          if($exist){
            //checking for vaccine type
            if ($exist['covaxin'] == 1){
              $vaccine = "covaxin";
            }
            else if($exist['covishield'] == 1){
              $vaccine = "covishield";
            }

            //looking to see whether user1 has registered for dose1
            $userdet = getUser($con, $_SESSION['aadhar_ID']);
            if($exist['dose1'] == 1){
                echo "<div class='alert alert-success'>
                      <li> aadhar_ID:".$userdet['aadhar_ID']."</li>
                      <li> Name:".$userdet['name']."</li>
                      <li>Gender:".$userdet['gender']."</li>
                      <li>Age:".$userdet['age']."</li>
                      <li>Phone Number:".$userdet['phone']."</li>
                      <li>dose 1</li>
                      <li>date of dose:".$exist['dose1_date']."</li>
                      <li>vaccine:".$vaccine."</li>";
                      $now1 = date("Y-m-d");
                      $now = date_create($now1);
                      $diff = date_diff($now,date_create($exist['dose1_date']));
                if( $diff->format("%R") == '+'){
                    $numdays = intval($diff->format("%a"));
                    if( $numdays >= 1 && $numdays <= 7){
                    echo "<a href='https://m.uber.com/looking' class='btn btn-dark'><i class='fab fa-uber'></i> Book Uber Ride</a>
                            <a href='https://www.olacabs.com/' class='btn btn-warning'><i class='fas fa-taxi'></i> Book Ola Ride</a>
                    <button type='submit' name='cancel_user1_dose1' class='btn btn-danger'>cancel appointment</button></div>";
                    }
                }
            }
            //looking to see whether user1 has registered for dose2
            if($exist['dose2'] == 1){
              echo "<div class='alert alert-success'>
                      <li> aadhar_ID:".$userdet['aadhar_ID']."</li>
                      <li> Name:".$userdet['name']."</li>
                      <li>Gender:".$userdet['gender']."</li>
                      <li>Age:".$userdet['age']."</li>
                      <li>Phone Number:".$userdet['phone']."</li>
                      <li>dose 2</li>
                      <li>date of dose:".$exist['dose2_date']."</li>
                      <li>vaccine:".$vaccine."</li>";
                      $now1 = date("Y-m-d");
                      $now = date_create($now1);
                      $diff = date_diff($now,date_create($exist['dose2_date']));
                      if( $diff->format("%R") == '+'){
                          $numdays = intval($diff->format("%a"));
                          if( $numdays >= 1 && $numdays <= 7){
                          echo "<a href='https://m.uber.com/looking' class='btn btn-dark'><i class='fab fa-uber'></i> Book Uber Ride</a>
                            <a href='https://www.olacabs.com/' class='btn btn-warning'><i class='fas fa-taxi'></i> Book Ola Ride</a>
                          <button type='submit' name='cancel_user1_dose2' class='btn btn-danger'>cancel appointment</button></div>";
                          }
                      }
            }
          }

            //ID EXISTS FOR USER 2
            $exist = idExists( $con, $_SESSION['aadhar_ID_person2'] );
            if($exist){
                //checking for vaccine type
              if ($exist['covaxin'] == 1){
                $vaccine = "covaxin";
              }
              else if($exist['covishield'] == 1){
                $vaccine = "covishield";
              }

              $userdet = getUser($con, $_SESSION['aadhar_ID_person2']);
              //looking to see whether user2 has registered for dose1
              if($exist['dose1'] == 1){
                  echo "<div class='alert alert-success'>
                      <li> aadhar_ID:".$userdet['aadhar_ID']."</li>
                      <li> Name:".$userdet['name']."</li>
                      <li>Gender:".$userdet['gender']."</li>
                      <li>Age:".$userdet['age']."</li>
                      <li>Phone Number:".$userdet['phone']."</li>
                      <li>dose 1</li>
                      <li>date of dose:".$exist['dose1_date']."</li>
                      <li>vaccine:".$vaccine."</li>";
                        $now1 = date("Y-m-d");
                        $now = date_create( $now1);
                        $diff = date_diff($now,date_create($exist['dose1_date']));
                        if( $diff->format("%R") == '+'){
                            $numdays = intval($diff->format("%a"));
                            if( $numdays >=1 && $numdays <= 7){
                            echo "<a href='https://m.uber.com/looking' class='btn btn-dark'><i class='fab fa-uber'></i> Book Uber Ride</a>
                            <a href='https://www.olacabs.com/' class='btn btn-warning'><i class='fas fa-taxi'></i> Book Ola Ride</a>
                            <button type='submit' name='cancel_user2_dose1' class='btn btn-danger'>cancel appointment</button></div>";
                            }
                        }
              }
              //looking to see whether user2 has registered for dose2
              if($exist['dose2'] == 1){
                  echo "<div class='alert alert-success'>
                      <li> aadhar_ID:".$userdet['aadhar_ID']."</li>
                      <li> Name:".$userdet['name']."</li>
                      <li>Gender:".$userdet['gender']."</li>
                      <li>Age:".$userdet['age']."</li>
                      <li>Phone Number:".$userdet['phone']."</li>
                      <li>dose 2</li>
                      <li>date of dose:".$exist['dose2_date']."</li>
                      <li>vaccine:".$vaccine."</li>";
                        $now1 = date("Y-m-d");
                        $now = date_create($now1);
                        $diff = date_diff($now,date_create($exist['dose2_date']));
                        if( $diff->format("%R") == '+'){
                            $numdays = intval($diff->format("%a"));
                            if( $numdays >= 1 && $numdays <= 7){
                            echo "<a href='https://m.uber.com/looking' class='btn btn-dark'><i class='fab fa-uber'></i> Book Uber Ride</a>
                            <a href='https://www.olacabs.com/' class='btn btn-warning'><i class='fas fa-taxi'></i> Book Ola Ride</a>
                            <button type='submit' name='cancel_user2_dose2' class='btn btn-danger'>cancel appointment</button></div>";
                            }
                        }
              }
            }
        }

      ?>


      </form>
</div>
</section>
<!-- this is the cancelation section-->

<!-- ======= Appointment Section ======= -->
    <section id="appointment" class="appointment section-bg">
      <div class="container">
<?php
	 $num = countUser($con);
	if ($num == 1){
        echo '<div class="section-title">
          <h2>Add Member</h2>
          <p>Click the button below to register for one of your loved ones so that you can start booking appointment together!</p>
        </div>

          <div class="row">
            <div class="col-md-4 form-group text-center">
                <a class="more-btn" onclick="addPerson()">Add Person 2 <i class="bi bi-plus-circle-fill"></i></a>
            </div>
          </div>';
    }
?>
          <br>
        <form action="addMembersSignup.inc.php" method="post" role="form"  id="registerForm" enctype="multipart/form-data" style="display: none;">
          <div class="row">
            <div class="col-md-4 form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Name as in Aadhar">
            </div>
            <div class="col-md-4 form-group mt-3 mt-md-0">
              <input type="number" class="form-control" name="aadhar_ID" id="aadhar_ID" placeholder="Enter your aadhar number" data-msg="Please enter a valid aadhar ID">
            </div>
            <div class="col-md-4 form-group mt-3 mt-md-0">
              <input type="file" class="form-control" name="aadhar" id="file" placeholder="Photo ID proof">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 form-group mt-3">
              <select name = "gender" class="form-select">
                <label for="gender">Select Gender</label>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="others">Others</option>
              </select>
            </div>
            <div class="col-md-4 form-group mt-3">
              <input type="number" class="form-control" name="age" id="age" placeholder="Enter Age">
            </div>
          </div>
          <br>
          <div class="text-center"><button type="reset" class="btn btn-primary" name="reset">Reset</button></div>         
          <br>
          <div class="text-center"><button type="submit" class="btn btn-primary" name="submit">Add Member</button></div>
        </form>
      </div>
    </section>
<!-- End Appointment Section -->

<!-- ======= Services Section ======= -->
    <section id="appointment" class="services">
      <div class="container">

        <div class="section-title">
          <h2>Appoinment Booking</h2>
          <p>Choose for which user you want to book a slot for:</p>
        </div>

    <form method="POST" action="index2.php">
  	<?php

		$numUsers = countUser($con);
        echo '<div class="row">';
        echo '
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="icon-box">
              <div class="icon"><i class="fas fa-user"></i></div>
              <h4><input type = "submit" class="btn btn-primary" value = "Account Holder" name ="user1"></h4>
              <p>Click here to schedule an appointment for the account holder</p>
            </div>
          </div>';

		if($numUsers == 2){         
			echo '
		          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0">
		            <div class="icon-box">
		              <div class="icon"><i class="fas fa-heart"></i></div>
		              <h4><input type = "submit" class="btn btn-primary" value = "Partner" name ="user2"></h4>
		              <p>Click here to schedule an appointment for the person you added</p>
		            </div>
		          </div>

		          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0">
		            <div class="icon-box">
		              <div class="icon"><i class="fas fa-user-friends"></i></div>
		              <h4><input type = "submit" class="btn btn-primary" value = "Combined" name ="combined"></h4>
		              <p>Click here to schedule an appointment for combined booking i.e both account holder and person you added</p>
		            </div>
		          </div>';
		    }
        echo '</div>';
    ?>
	</form>
	</div>
  </section>

 <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container-fluid">

        <div class="row">
          <div class="col-xl-5 col-lg-6 d-flex justify-content-center align-items-stretch position-relative">
            <div style="margin: auto;">
              <iframe width="560" height="315" src="https://www.youtube.com/embed/Pao8171B354" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
          </div>

          <div class="col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5">
            <h3>What are the Benefits of Getting the COVID-19 Vaccine?</h3>
            <p>COVID-19 is a highly infectious and, in some cases, highly dangerous disease. Some populations, including the elderly and persons with underlying medical conditions (i.e., comorbidities) are at greater risk for severe symptoms and even death. Natural immunity combined with vaccine-induced immunity appears to be the most effective means of safeguarding against COVID-19.</p>

            <div class="icon-box">
              <div class="icon"><i class="fas fa-heartbeat"></i></div>
              <h4 class="title"><a href="">Protection against COVID-19</a></h4>
              <p class="description">The best means of staying healthy is to avoid infection by the SARS-CoV-2 virus. If, however, you do become infected, vaccination appears to limit the risk of developing severe or even fatal symptoms. This is because the currently available vaccine primes your immunological system for producing antibodies without causing you to get sick. If you do become infected, your body is prepared to fight the disease.</p>
            </div>

            <div class="icon-box">
              <div class="icon"><i class="fas fa-hospital"></i></div>
              <h4 class="title"><a href="">Protection for your family and friends</a></h4>
              <p class="description">By getting the COVID-19 vaccine, you also lessen the chance of spreading the COVID-19 pathogen to family members, friends, or other people with whom you have contact.</p>
            </div>

            <div class="icon-box">
              <div class="icon"><i class="fas fa-capsules"></i></div>
              <h4 class="title"><a href="">High rates of effectiveness</a></h4>
              <p class="description">All FDA-approved medications are clinically tested before release to the public. The Pfizer-BioNTech vaccine has been shown to be 94-95 percent effective within two weeks of full inoculation. In other words, the vaccines will safeguard 19 out of every 20 persons inoculated from becoming seriously ill with COVID-19.</p>
            </div>

          </div>
        </div>

      </div>
    </section><!-- End About Section -->

        <!-- ======= Frequently Asked Questions Section ======= -->
    <section id="faq" class="faq section-bg">
      <div class="container">

        <div class="section-title">
          <h2>Frequently Asked Questions</h2>
          <p> In the initial phase, COVID-19 vaccine was provided to the priority group - Health Care and Front-line workers. The second phase vaccinations, which started on March 1, 2021 allowed for all Indians above the age of 60 and Indians between the age of 45 and 59 with comorbidities to be vaccinated.</p>
        </div>

        <div class="faq-list">
          <ul>
            <li data-aos="fade-up">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" class="collapse" data-bs-target="#faq-list-1">Where can I get the vaccine from? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-1" class="collapse show" data-bs-parent=".faq-list">
                <p>
                  Vaccines are available from Government and Private Health Facilities as notified, known as COVID Vaccination Centres (CVCs)
                </p>
              </div>
            </li>

            <li data-aos="fade-up" data-aos-delay="100">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-2" class="collapsed">Which COVID-19 vaccines are licensed in India? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-2" class="collapse" data-bs-parent=".faq-list">
                <p>
                  Two vaccines that have been granted emergency use authorization by the Central Drugs Standard Control Organization (CDSCO) in India are Covishield® (AstraZeneca's vaccine manufactured by Serum Institute of India) and Covaxin® (manufactured by Bharat Biotech Limited).
                </p>
              </div>
            </li>

            <li data-aos="fade-up" data-aos-delay="200">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-3" class="collapsed">Where should I register for the vaccination? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-3" class="collapse" data-bs-parent=".faq-list">
                <p>
                  Register on the Co-WIN Portal or vaccination drive and schedule your vaccination appointment. https://www.cowin.gov.in/home
                </p>
              </div>
            </li>

            <li data-aos="fade-up" data-aos-delay="300">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-4" class="collapsed">Will I get any certificate that I am vaccinated? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-4" class="collapse" data-bs-parent=".faq-list">
                <p>
                  Yes, a provisional certificate would be provided after the first dose. On completion of second dose, the hospital will be providing a hard copy of the vaccination certificate which can be used for future references.
                </p>
              </div>
            </li>

            <li data-aos="fade-up" data-aos-delay="400">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-5" class="collapsed">What is the composition of both the vaccines? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-5" class="collapse" data-bs-parent=".faq-list">
                <p>
                  Composition of Covishield includes inactivated adenovirus with segments of Coronavirus, Aluminium Hydroxide Gel, L-Histidine, L-Histidine Hydrochloride Monohydrate, Magnesium Chloride Hexahydrate, Polysorbate 80, Ethanol, Sucrose, Sodium Chloride, and Disodium Edetate Dihydrate (EDTA). Composition of Covaxin includes inactivated Coronavirus, Aluminum Hydroxide Gel, TLR 7/8 Agonist, 2-Phenoxyethanol and Phosphate Buffered Saline [NKA1].
                </p>
              </div>
            </li>

          </ul>
        </div>

      </div>
    </section><!-- End Frequently Asked Questions Section -->

 <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">About us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Services</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Vaccine</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">covid vaccine information</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Help people get vaccines</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container d-md-flex py-4">

      <div class="me-md-auto text-center text-md-start">
        <div class="copyright">
          &copy; Copyright <strong><span>Covid Drive</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
          Designed And Developed By <a href="#"> Lalit , Shashi and barath</a>
        </div>
      </div>
    </div>
  </footer>
<!-- End Footer -->

	<script type="text/javascript">

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
	/*
	function selectPerson(clicked) {
		var x = document.getElementById("bookingAppointment");
		if (x.style.display === "none") {
			x.style.display = "block";

			var y = document.getElementById("sample");
			y.innerHTML = "Button clicked by ID: "+clicked;
		}
		else {
			x.style.display = "none";

		var y = document.getElementById("sample");
		y.innerHTML = "Button clicked by ID: "+clicked;
		}
		/*
		$.ajax({
			url: "index.php",
			data: { user_number: clicked },
		});


		var j = clicked;

		<?php $_SESSION['user_number'] = "<script>document.write(j)</script>"?>


	}
	*/
	</script>

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