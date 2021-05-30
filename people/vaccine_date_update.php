<?php
/*
	$con = mysqli_connect("localhost","root","","hosp_login");
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
*/
	include("connection_hosp.php");
	date_default_timezone_set("Asia/Kolkata");
	$i=1;
	$query_update = "CREATE event IF NOT EXISTS newevent ON SCHEDULE EVERY 10 SECOND STARTS CURRENT_TIMESTAMP
			DO
			UPDATE vaccine_date SET vaccine_per_day1 = vaccine_per_day2, vaccine_per_day2 = vaccine_per_day3, vaccine_per_day3 = vaccine_per_day4, vaccine_per_day4 = vaccine_per_day5, vaccine_per_day5 = vaccine_per_day6, vaccine_per_day6 = vaccine_per_day7, vaccine_per_day7 = vaccine_per_day;";

	$result_update = mysqli_query($con_hosp, $query_update);
	
	echo "Event has been created.";

	$qry = "SELECT * FROM vaccine_date;";
	$result = mysqli_query($con_hosp, $qry);

	while($row = mysqli_fetch_array($result)){
		echo $row['hospital_nabh_ID'];
		echo "<br/>";
		echo $row['vaccine_per_day1'];
		echo "<br/>";
	}
	
	mysqli_close($con_hosp);
#, 
?>
