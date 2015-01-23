<?php
//processCheckout.php
session_start();

$itemname = $_POST['itemname'];
$start = $_POST['start'];
$end = $_POST['end'];
$newEmail = $_POST['newEmail'];
$newPhone = $_POST['newPhone'];
$message = $_POST['message'];
$total_price = $_POST['total_price'];
$need_remind = $_POST['need_remind'];
if(isset($_POST['identity'])){
  $identity = $_POST['identity'];
}

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

if(isset($identity)){
  //If $identity is set, it means current booking is for Fabrication Facility, which does not need approval from administrator
  $query = "insert into booking values (NULL, '$username', '$itemname', '".date("Y\-m\-d")."', '$start', '$end', '$message', $total_price, 0, $remind, '".$identity."')";

}else{

  $query = "insert into booking values (NULL, '$username', '$itemname', '".date("Y\-m\-d")."', '$start', '$end', '$message', $total_price, 0, $remind, '".$_SESSION['user_identity']."')";
}

$result = $db_conn->query($query);
$approveId = $db_conn->insert_id;

if(!$result){
    echo '<script type="text/javascript">alert("Your query for inserting booking failed.");</script>';
  exit;
}

if (!get_magic_quotes_gpc()){ //default is add slashes to get, post, cookies
    $newPhone = addslashes($newPhone);
    $username = addslashes($username);
    $newEmail = addslashes($newEmail);
  }

//Query to update the email and phone of the current user
if($_SESSION['user_identity']=="internal"){
  			$query2 = "update internal_user set email = '".$newEmail."', phone = '".$newPhone."' where username = '".$username."'";
 }else if($_SESSION['user_identity']=="external"){
  			$query2 = "update external_user set email = '".$newEmail."', phone = '".$newPhone."' where username = '".$username."'";
 }else if($_SESSION['user_identity']=="admin"){
 			$query2 = "update admin_user set email = '".$newEmail."', phone = '".$newPhone."' where username = '".$username."'";
 }

$result2 = $db_conn->query($query2);

if(!$result2){
  	echo '<script type="text/javascript">alert("Your query for updating user information failed.");</script>';
	exit;
}

$db_conn->close();

if(isset($identity)){

header("Location: processApproveBooking.php?approveId=$approveId&source=1");

}else{
header("Location: postBooking.php"); 
}
exit();


?>