<?php

session_start();

if(isset($_POST['DenyId'])) {$denyId = $_POST['DenyId'];}
if(isset($_POST['Content'])) {$content = $_POST['Content'];}

if(isset($denyId)){

	@ $db_conn = new mysqli('localhost','root','fyp.2013','coft');

		  if (mysqli_connect_errno()) {
		     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
		     exit;
		  }

	$query = "UPDATE booking SET approved = -1 WHERE booking_id = $denyId";

	$query_update = $db_conn->query($query);

	if(!$query_update){
		echo '<script type="text/javascript">alert("Updating booking failed.");</script>';
		exit;
	}

	$query2 = "SELECT * FROM booking WHERE booking_id = $denyId";

	$result = $db_conn->query($query2);

	$num_result = $result->num_rows;


	$row = $result->fetch_assoc();
	$start = $row['start_event'];
	$end = $row['end_event'];
	$total_price = $row['total_price'];
	$message = $row['message'];

	if(($row['user_identity']=="internal") || ($row['user_identity']=="external")){
		$query_user = "SELECT * FROM normal_user WHERE username = '".$row['username']."'";
	}else if($row['user_identity']=="admin"){
		$query_user = "SELECT * FROM admin_user WHERE username = '".$row['username']."'"; 
	}else{
		echo '<script type="text/javascript">alert("User identity is invalid for the booking.");</script>';
		$query = "UPDATE booking SET approved = 1 WHERE booking_id = $denyId";
		$db_conn->query($query);
		exit;
	}

	$result_user = $db_conn->query($query_user);

	$num_result_user = $result_user->num_rows;

	if($num_result_user!=1){
		echo '<script type="text/javascript">alert("There are more than one user with the same user name.");</script>';
		$query = "UPDATE booking SET approved = 1 WHERE booking_id = $denyId";
		$db_conn->query($query);
		exit;
	}

	$row_user = $result_user->fetch_assoc();
	$username = $row_user['username'];
	$itemname = $row['facility_name'];
	$email = $row_user['email'];

	$query_admin = "SELECT * FROM admin_user";
	$result_admin = $db_conn->query($query_admin);

	if(!$result_admin){
	  echo '<script type="text/javascript">alert("Your query to retrieve admin information failed.");</script>';
	  $query = "UPDATE booking SET approved = 1 WHERE booking_id = $denyId";
	  $db_conn->query($query);
	  include 'results.php';
	  exit;
	}

	$row_admin = $result_admin ->fetch_assoc();
	$admin_email = $row['email'];

$htmlbody = "$content";
// "
// Dear $username,

// I am sorry to inform your that your booking for $itemname has been revoked by the administrator. This could because you have not taken required training before using the facility.

// The detail of your revoked booking is as below:


//     Facility Name: $itemname
//     Time:          $start - $end
//     Total Price:   SGD $total_price
//     Message:       $message


// If you have any queries, you could send email to epshum@ntu.edu.sg.

// Regards,
// COFT Office

// This is an automatically generated confirmation email. Please do not reply directly.";

	//define the receiver of the email 
	$to = $email;

	//define the subject of the email 
	$subject = 'Notification from Centre for Optical Fibre Technology'; 

	$headers = "From: Centre for Optical Fibre Technology\r\nBCC:$admin_email"; //bcc administrator

	$mail = mail( $to, $subject , $htmlbody, $headers, '-f'.$admin_email);

	if(!$mail){
		$query = "UPDATE booking SET approved = 1 WHERE booking_id = $denyId";
		$db_conn->query($query);
		header("Location: approveBooking.php?emailFail=1");
		$db_conn->close();
		exit;
	}


	$db_conn->close();
	
	// if(isset($_GET['source'])){
	// 	header("Location: results.php"); 
	// }else{
	// 	header("Location: approveBooking.php"); 
	// } 
	// exit();

}

// if(isset($_GET['source'])){
// 	header("Location: results.php"); 
// }else{
header("Location: approveBooking.php"); 
// }
// exit();

?>