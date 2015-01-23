<?php
session_start();
// include "conn.php";
/*if (isset($_POST['Submit'])) {
	if (empty($_POST['Email']) || empty ($_POST['Name']) || 
		empty ($_POST['Password']) || empty ($_POST['ConfirmPassword'])) {
	echo "All records to be filled in";
	exit;}
	}*/ //The field check has been performed in registration.php

$username = $_POST['Username'];	
$password = $_POST['Password'];
$email = $_POST['Email'];
$addline1 = $_POST['Addline1'];	
$addline2 = $_POST['Addline2'];
$postal = $_POST['Postal'];
$phone = $_POST['Phone'];
$company = $_POST['Company'];
$approved = 0;

$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

//Check if the email has been used
$query_external = "SELECT * FROM external_user WHERE email='$email'";
 
$result_external = $db_conn->query($query_external);

$query_internal = "SELECT * FROM internal_user WHERE email='$email'";
 
$result_internal = $db_conn->query($query_internal);

$query_admin = "SELECT * FROM admin_user WHERE email='$email'";
 
$result_admin = $db_conn->query($query_admin);

// $checkuser = mysql_query("SELECT * FROM customer WHERE email='$email'"); 

// $username_exist = mysql_num_rows($checkuser);

/*************/
/*Internal new users shall be inserted to database by administrator*/
/*************/

if(($result_external->num_rows >0) || ($result_internal->num_rows >0) || ($result_admin->num_rows >0)){
    echo '<script type="text/javascript">alert("I am sorry but the email you specified has already been taken.  Please pick another one.");</script>';
    unset($email);
    session_destroy();
    include 'registration.php';
    exit();
}

//Check if the username has been used
$query_external2 = "SELECT * FROM external_user WHERE username='$username'";
 
$result_external2 = $db_conn->query($query_external2);

$query_internal2 = "SELECT * FROM internal_user WHERE username='$username'";
 
$result_internal2 = $db_conn->query($query_internal2);

$query_admin2 = "SELECT * FROM admin_user WHERE username='$username'";
 
$result_admin2 = $db_conn->query($query_admin2);

if(($result_external2->num_rows >0) || ($result_internal2->num_rows >0) || ($result_admin2->num_rows >0)){
    echo '<script type="text/javascript">alert("I am sorry but the user name has already been taken.  Please pick another one.");</script>';
    unset($username);
    session_destroy();
    include 'registration.php';
    exit();
}

$password = md5($password);
// echo $password;
$sql = "INSERT INTO external_user
		VALUES (NULL, '$username', '$password', '$email', '$addline1', '$addline2', '$postal', '$phone', '$company', $approved, '".date("Y\-m\-d")."', '')";
// echo "<br>".$sql."<br>";
// $result = mysql_query($sql);

$result1 = $db_conn->query($sql);

if (!$result1) {
	echo '<script type="text/javascript">alert("Your query failed.");</script>';
  $db_conn->close();
  exit();
}else{
    $_SESSION['valid_user'] = $username;  
    $_SESSION['user_identity'] = "external_nonapproved";  
    header("Location: postRegister.php"); 
    $db_conn->close();
    exit();}
	
?>