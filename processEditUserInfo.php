<?php
//processEditUserInfo.php
session_start();

$user_identity = $_SESSION['user_identity'];
$userid = $_POST['User_id'];
// $username = $_POST['Username'];	//It is assumed that user name is not allowed to change
$password = $_POST['Password'];
$email = $_POST['Email'];
$addline1 = $_POST['Addline1'];	
$addline2 = $_POST['Addline2'];
$postal = $_POST['Postal'];
$phone = $_POST['Phone'];
$faculty = $_POST['Faculty'];
$isPasswordChanged = $_POST['isPasswordChanged'];
// $approved = 0;

$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

$password = md5($password);


if(($user_identity == "normal") || ($user_identity == "normal_nonapproved")){

  if($isPasswordChanged=="yes"){
      	$sql = "UPDATE normal_user
      			SET password ='$password', email = '$email', addline1 = '$addline1', addline2 = '$addline2', postalcode = '$postal', phone = '$phone', faculty = '$faculty' 
      			WHERE user_id = '$userid'";
    }else{
        $sql = "UPDATE normal_user
            SET email = '$email', addline1 = '$addline1', addline2 = '$addline2', postalcode = '$postal', phone = '$phone', faculty = '$faculty' 
            WHERE user_id = '$userid'";
    }

}else if($user_identity == "admin"){
  if($isPasswordChanged=="yes"){
    	$sql = "UPDATE admin_user
    			SET password ='$password', email = '$email', addline1 = '$addline1', addline2 = '$addline2', postalcode = '$postal', phone = '$phone', faculty = '$faculty' 
    			WHERE user_id = '$userid'";
  }else{
          $sql = "UPDATE admin_user
          SET email = '$email', addline1 = '$addline1', addline2 = '$addline2', postalcode = '$postal', phone = '$phone', faculty = '$faculty' 
          WHERE user_id = '$userid'";
  }

}else{
	echo '<script type="text/javascript">alert("Error: User identity is not valid.");</script>';
    exit;
}

  $result = $db_conn->query($sql);

  if(!$result){
  	 echo '<script type="text/javascript">alert("Your query has failed.");</script>';
  	 $db_conn->close();
  	 include 'editUserInformation.php';
     exit;
  }else{
  	header("Location: postEditUserInfo.php"); 
    $db_conn->close();
    exit();
  }

?>