<?php
//processUserAccess.php

$user_id = $_POST['user_id'];

if(isset($_POST['current_access'])){
  $facilities = $_POST['current_access'];
  $approved_access = '';
  $number_facility = sizeof($facilities);
  for($i = 0; $i < $number_facility; $i++){
      $approved_access = $approved_access.$facilities[$i].",";
  }
  $approved_access = trim($approved_access, ","); //Remove the last , after the last element
}

$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

$query_update = "UPDATE normal_user SET facility_access = '$approved_access' WHERE user_id = '".$user_id."'";

$result = $db_conn->query($query_update);

if(!$result){
	echo '<script type="text/javascript">alert("Error: Update facility access failed.");</script>';
	include 'approveExternalUser.php';
	$db_conn->close();
    exit;
}

header("Location: approveExternalUser.php");

$db_conn->close();

?>