<?php
//processSaveAnnounce.php

$announcement = $_POST['Announcement'];

$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

$sql = "INSERT INTO announcement
		VALUES (NULL, '$announcement', '".date("Y\-m\-d")."')";

 
$result = $db_conn->query($sql);

if(!$result){
  echo '<script type="text/javascript">alert("Your query failed.");</script>';
  $db_conn->close();
  include 'results.php';
  exit();
}else{
	$db_conn->close();
	header("Location: results.php"); 
	exit();
}
?>