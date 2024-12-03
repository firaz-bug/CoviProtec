<?php

	session_start();
	include("connection1.php");
	include("hospDashFunctions.php");

	$Currentvaccine_det = check_login($con);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Coviprotec</title>
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

      <h1 class="logo me-auto"><a href="index.php">Vaccine Drive</a></h1>
      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto active" href="index1.php">Home</a></li>
          <li><a class="nav-link scrollto" href="vaccine_schedule.php">Vaccine Schedule</a></li>
          <li><a class="nav-link" href="logout1.php">Log out</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

      <a href="update_stock.php" class="appointment-btn scrollto"><span class="d-none d-md-inline">Update</span>  Vaccines</a>

    </div>
  </header>
  <!-- End Header -->

    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts">
      <div class="container">
      	<div class="section-title">
      		<br>
      		</br>
	      	<h2><?php echo $_SESSION["name"]; ?> Hospital</h2>
	      	<br>
	      	<h3>Vaccine data</h3>
	      	<br>
	      	</br>
	        <div class="row">

			<div class="col-lg-3 col-md-6">
	            <div class="count-box">
	              <i class="fas fa-user-md"></i>
	              <?php
	              echo '<span data-purecounter-start="0" data-purecounter-end="'.$Currentvaccine_det['curr_covaccine'].'" data-purecounter-duration="1" class="purecounter"></span>'
	              ?>
	              <p>Available Covaxin Vaccines</p>
	            </div>
	        </div>


	          <div class="col-lg-3 col-md-6 mt-5 mt-md-0">
	            <div class="count-box">
	              <i class="far fa-hospital"></i>
	              <?php
	              echo '<span data-purecounter-start="0" data-purecounter-end="'.$Currentvaccine_det['curr_covisheild'].'" data-purecounter-duration="1" class="purecounter"></span>'
	              ?>
	              <p>Available Covisheild Vaccines</p>
	            </div>
	          </div>

	          <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
	            <div class="count-box">
	              <i class="fas fa-flask"></i>
	              <?php
	              echo '<span data-purecounter-start="0" data-purecounter-end="'.$Currentvaccine_det['VaccinesPerDay'].'" data-purecounter-duration="1" class="purecounter"></span>'
	              ?>
	              <p>Vaccines Per Day</p>
	            </div>
	          </div>

	          <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
	            <div class="count-box">
	              <i class="fas fa-award"></i>
	              <?php
                echo "<h4 style='font-size:23px;'>".$Currentvaccine_det['updation_date']."</h4>"
                ?>
	              <p>last updation date</p>
	            </div>
	          </div>

	        </div>
	    </div>    
      </div>
    </section><!-- End Counts Section -->



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
