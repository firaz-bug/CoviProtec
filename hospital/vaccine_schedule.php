<?php

  date_default_timezone_set("Asia/Kolkata");
	session_start();
	include("connection1.php");
	include("connection2.php");
	include("hospDashFunctions.php");

	$Currentvaccine_det = check_login($con);
	$vpd = $Currentvaccine_det['curr_covaccine'];
	$month = date('m');
	$day = date('d');
	$year = date('Y');

	$today = $year . '-' . $month . '-' . $day;

	

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

      <h1 class="logo me-auto"><a href="index1.php">Covid Drive</a></h1>
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
  <br>
  <br>

<section>
  <form method="post">
    <div class="form-group">
      <div class="section-title">
      <label for="appointment"><b>Enter date you want to check appointment for:</b></label>
      <input type="date" id="appointment" name="appointment" value="<?php echo '$today';?>" min="">
      <input type="submit" class="appointment-btn scrollto" name="datesubmit">
      </div>
    </div>
  </form>
  <br>
  <div class="container-fluid">
    <table class="table table-dark" id="appointments">
      <thead>
        <tr>
          <th>aadhar_ID</th>
          <th>name</th>
          <th>gender</th>
          <th>age</th>
          <th>phone number</th>
        </tr>
    </thead>
    <?php
    if (isset($_POST['datesubmit'])){
      $date = $_POST['appointment'];
      $sql1 = "SELECT * FROM people_slot_booking WHERE hospital_nabh_id = ? and (dose1_date = ? or dose2_date = ?);";
      $stmt1 = mysqli_stmt_init($conn);

      if(!mysqli_stmt_prepare($stmt1, $sql1)){
        header("Location: index1.php?error=stmtfailed");
        exit();
      }

      mysqli_stmt_bind_param($stmt1, "iss", $_SESSION['user_id'], $date, $date);
      mysqli_stmt_execute($stmt1);

      $resultData1 = mysqli_stmt_get_result($stmt1);
      while ($row1= mysqli_fetch_array($resultData1, MYSQLI_ASSOC)){

        $sql2 = "SELECT * FROM people WHERE aadhar_ID = ?;";
        $stmt2 = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt2, $sql2)){
          header("Location: index1.php?error=stmtfailed");
          exit();
        }

        mysqli_stmt_bind_param($stmt2, "i", $row1['aadhar_ID']);
        mysqli_stmt_execute($stmt2);

        $resultData2 = mysqli_stmt_get_result($stmt2);
        $row2 = mysqli_fetch_assoc($resultData2);
        echo  "   <tr>
                    <td>".$row2['aadhar_ID']."</td>
                    <td>".$row2['name']."</td>
                    <td>".$row2['gender']."</td>
                    <td>".$row2['age']."</td>
                    <td>".$row2['phone']."</td>
                  </tr>";
    }
  }

   ?>
    </table>
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
