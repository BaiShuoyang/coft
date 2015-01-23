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
$query_external = "SELECT * FROM external_user WHERE email='$email'";
 
$result_external = $db_conn->query($query_external);

$query_internal = "SELECT * FROM internal_user WHERE email='$email'";
 
$result_internal = $db_conn->query($query_internal);

$query_admin = "SELECT * FROM admin_user WHERE email='$email'";
 
$result_admin = $db_conn->query($query_admin);


//so we use the MD5 algorithm to generate a random hash 
$token = md5(date('r', time())); 

//check if the email exists in the database
if($result_external->num_rows >0){
	$identity = "external";
    $query_external2 = "UPDATE external_user SET token = '$token' WHERE email='$email'";
    $result_external2 = $db_conn->query($query_external2);
    if(!$result_external2){
    	echo '<script type="text/javascript">alert("Error: Updating failed. Please try again later.");</script>';
     	exit;
    }

}else if($result_internal->num_rows >0){
	$identity = "internal";
	$query_internal2 = "UPDATE internal_user SET token = '$token' WHERE email='$email'";
    $result_internal2 = $db_conn->query($query_internal2);
    if(!$result_internal2){
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

$htmlbody = "
Dear user,

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
$headers = "From: Centre for Optical Fibre Technology\r\nBCC:austinbai927@gmail.com"; //bcc administrator

//add boundary string and mime type specification 
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 

//define the body of the message.
$message = "--PHP-mixed-$random_hash\r\n"."Content-Type: multipart/alternative; boundary=\"PHP-alt-$random_hash\"\r\n\r\n";
$message .= "--PHP-alt-$random_hash\r\n"."Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";


//Insert the html message.
$message .= $htmlbody;
$message .="\r\n\r\n--PHP-alt-$random_hash--\r\n\r\n";


//send the email
$mail = mail( $to, $subject , $message, $headers, '-faustinbai927@gmail.com' );


    unset($email);
    header("Location: index.php"); 
    $db_conn->close();
    exit();

?>