<?php
//processReset.php
$identity = $_POST['identity'];
$token = $_POST['token'];
$password = $_POST['Password'];

$password = md5($password);

$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

if($identity=="internal"){

	$query = "UPDATE internal_user SET password = '$password' WHERE token = '$token'";
	$result = $db_conn->query($query);

}else if($identity=="external"){

	$query = "UPDATE external_user SET password = '$password' WHERE token = '$token'";
	$result = $db_conn->query($query);

}else if($identity=="admin"){

	$query = "UPDATE admin_user SET password = '$password' WHERE token = '$token'";
	$result = $db_conn->query($query);

}else{

	echo '<script type="text/javascript">alert("Error: Identity passed by GET failed.");</script>';
    exit;	
}

if(!$result){
	echo '<script type="text/javascript">alert("Error: Query failed.");</script>';
    exit;
}

header("Location: index.php"); 
$db_conn->close();
exit();

?>