<?php
//processRecovery.php
session_start();

$email = $_POST['Email'];	

$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

//Check if the email has been used
$query_normal = "SELECT * FROM normal_user WHERE email='$email'";
 
$result_normal = $db_conn->query($query_normal);

$query_admin = "SELECT * FROM admin_user WHERE email='$email'";
 
$result_admin = $db_conn->query($query_admin);


//so we use the MD5 algorithm to generate a random hash 
$token = md5(date('r', time())); 

//check if the email exists in the database
if($result_normal->num_rows >0){
	$identity = "normal";
    $query_normal2 = "UPDATE normal_user SET token = '$token' WHERE email='$email'";
    $result_normal2 = $db_conn->query($query_normal2);
    if(!$result_normal2){
    	echo '<script type="text/javascript">alert("Error: Updating failed. Please try again later.");</script>';
     	exit;
    }

}else if($result_admin->num_rows >0){
	$identity = "admin";
	$query_admin2 = "UPDATE admin_user SET token = '$token' WHERE email='$email'";
    $result_admin2 = $db_conn->query($query_admin2);
    if(!$result_admin2){
    	echo '<script type="text/javascript">alert("Error: Updating failed. Please try again later.");</script>';
     	exit;
    }

}else{
    
    header("Location: forgetPassword.php?fail=1"); 
    $db_conn->close();
    exit;

}

  $query_admin = "SELECT * FROM admin_user";
  $result_admin = $db_conn->query($query_admin);

  if(!$result_admin){
    echo '<script type="text/javascript">alert("Your query to retrieve admin information failed.");</script>';
    include 'results.php';
    exit;
  }

  $row_admin = $result_admin ->fetch_assoc();
  $admin_email = $row['email'];

$htmlbody = "Dear user,

A request has been made to reset your coft account password. 
Click on the URL below and proceed with resetting your password.

http://155.69.222.18/coft/resetPassword.php?identity=$identity&token=$token 

Thank you.


Regards,
COFT Office

This is an automatically generated confirmation email. Please do not reply directly.";

//define the receiver of the email 
$to = $email;

//define the subject of the email 
$subject = 'COFT Password Reset Request'; 

//create a boundary string. It must be unique 
//so we use the MD5 algorithm to generate a random hash 
$random_hash = md5(date('r', time())); 

//define the headers we want passed. Note that they are separated with \r\n 
$headers = "From: Centre for Optical Fibre Technology\r\nBCC:$admin_email"; //bcc administrator

//add boundary string and mime type specification 
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 

//Insert the html message.
$message = $htmlbody;


//send the email
$mail = mail( $to, $subject , $message, $headers, '-f'.$admin_email );


    unset($email);
    header("Location: index.php"); 
    $db_conn->close();
    exit();

?>