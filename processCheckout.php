<?php
//processCheckout.php
session_start();

$itemname = $_POST['itemname'];
$start = $_POST['start'];
$end = $_POST['end'];
$message = $_POST['message'];
$total_price = $_POST['total_price'];
$need_remind = $_POST['need_remind'];
$identity = $_POST['identity'];

$userEmail = $_POST['userEmail'];
$billName = $_POST['billName'];
$billEmail = $_POST['billEmail'];
$billPhone = $_POST['billPhone'];
$billAdd1 = $_POST['billAddline1'];
$billAdd2 = $_POST['billAddline2'];
$billPostal = $_POST['billPostal'];
$billOrganization = $_POST['Organization'];

$username = $_SESSION['valid_user'];

@ $db_conn = new mysqli('localhost','root','fyp.2013','coft');

		  if (mysqli_connect_errno()) {
		     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
		     exit;
		  }

if($need_remind=='1'){
  $remind = 0; //checkReturnDate.php will only check those bookings with remind = 0
}else if($need_remind=='0'){
  $remind = -1; //checkReturnDate.php will ignore those bookings with remind = -1 because that facility is not removable thus no need reminding
} 
//In booking table, the username is the name of the user who makes the booking
$query = "INSERT INTO booking VALUES (NULL, '$username', '$itemname', '".date("Y\-m\-d")."', '$start', '$end', '$message', $total_price, 1, $remind, 0, '".$identity."')";

$result = $db_conn->query($query);

// $approveId = $db_conn->insert_id;

$bookingId = $db_conn->insert_id;

if(!$result){
  echo '<script type="text/javascript">alert("Your query for inserting booking failed.");</script>';
  exit;
}

//In bill_information table, the username is the name is the name of the billing person
$query_bill = "INSERT INTO billing_information VALUES ($bookingId, '$billName', '$billEmail', '$billAdd1', '$billAdd2', '$billPostal', '$billPhone', '$billOrganization')";

$result_bill = $db_conn->query($query_bill);

if(!$result_bill){
  echo '<script type="text/javascript">alert("Your query for inserting bill information failed.");</script>';
  exit;
}

if (!get_magic_quotes_gpc()){ //default is add slashes to get, post, cookies
    $billPhone = addslashes($billPhone);
    $username = addslashes($username);
    $billEmail = addslashes($billEmail);
  }

// $db_conn->close();

// if(isset($identity)){

// header("Location: processApproveBooking.php?approveId=$approveId&source=1");

// }else{
// header("Location: postBooking.php"); 
// }
// exit();

$query_admin = "SELECT * FROM admin_user";
$result_admin = $db_conn->query($query_admin);

if(!$result_admin){
  echo '<script type="text/javascript">alert("Your query to retrieve admin information failed.");</script>';
  include 'results.php';
  exit;
}

$row_admin = $result_admin ->fetch_assoc();
$admin_email = $row['email'];

////////////////////////////////////////////
//Send email to the user with attachment //
///////////////////////////////////////////

$htmlbody = "
Dear $username,

This email serves as a confirmation for receiving your reservation at Centre for Optical Fibre Technology.
The detail of your booking is as below:


    Facility Name: $itemname
    Time:          $start - $end
    Total Price:   SGD $total_price
    Message:       $message



Regards,
COFT Office

This is an automatically generated confirmation email. Please do not reply directly.";

//define the receiver of the email, email is sent to the current user, not the billing email address
$to = $userEmail;

//define the subject of the email 
$subject = 'Confirmation from Centre for Optical Fibre Technology'; 

//define the headers we want passed. Note that they are separated with \r\n 
$headers = "From: Centre for Optical Fibre Technology\r\nBCC:$admin_email"; //bcc administrator


//send the email
$mail = mail( $to, $subject , $htmlbody, $headers, '-f'.$admin_email );

// if(!$mail){
//   $query = "UPDATE booking SET approved = 0 WHERE booking_id = $approveId";
//   $db_conn->query($query);
//   header("Location: approveBooking.php?emailFail=1");
//   $db_conn->close();
//   exit;

// }

$db_conn->close();

header("Location: postBooking.php"); 

exit();
?>