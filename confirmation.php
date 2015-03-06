<!DOCTYPE html><?php


session_start();

$itemname = $_POST['itemname'];
$start_old = $_POST['start'];
$end_old = $_POST['end'];
$start = DateTime::createFromFormat('d/m/Y H:i', $start_old);
$end = DateTime::createFromFormat('d/m/Y H:i', $end_old);
$start = $start->format('Y/m/d H:i');
$end = $end->format('Y/m/d H:i');

$message = $_POST['message'];
if(isset($_POST['user_identity'])){
	$identity = $_POST['user_identity'];
}

$username = $_SESSION['valid_user'];

$total = 0;
		
		if (!get_magic_quotes_gpc()){ //default is add slashes to get, post, cookies
		    $itemname = addslashes($itemname);
		  }

		@ $db = new mysqli('localhost','root','fyp.2013','coft');

		  if (mysqli_connect_errno()) {
		     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
		     exit;
		  }

		  $query = "SELECT * FROM item WHERE facility_name = '".$itemname."'";

  		$result = $db->query($query);

  		$num_results = $result->num_rows;

  		if($num_results == 0){
  			echo '<script type="text/javascript">alert("No facility item in database.");</script>';
  		    exit;
  		}

  		$row = $result->fetch_assoc();

			$start_time = strtotime($start);
			$end_time = strtotime($end);
	  	$time_difference = round(($end_time - $start_time)/(60*60),2); //In hours (float)

      if($row['isCleanRoomFacility'] == 1){
        //For clean room facility, the price field in database is price per hour
        $total = $row['price'] * $time_difference;
        $price_per_hour = $row['price'];

      }else{
        //For non clean room facility, the price field in database is item price
        $price_per_hour = $row['price'] * $row['charge_internal'];
        $total = $price_per_hour * $time_difference;
      }
	  		if($identity=="internal"){
	  			$total = $total * 1;
	  		}else if($identity=="external"){
	  			$total = $total * $row['charge_external'] / $row['charge_internal'];

          if($row['isCleanRoomFacility'] == 1){
              $total = $total + 100; //Plus the $100 for clean room facilities
          }

          $price_per_hour = $price_per_hour * $row['charge_external'] / $row['charge_internal'];
	  		}else{
	  			echo '<script type="text/javascript">alert("There is problem with user identity.");</script>';
	  		    exit;
	  		}

       if($_SESSION['user_identity']=="normal"){
          $query2 = "SELECT * FROM normal_user WHERE username = '".$username."'";
          }else if($_SESSION['user_identity']=="admin"){
            $query2 = "SELECT * FROM admin_user WHERE username = '".$username."'";
          }

          $result2 = $db->query($query2);

          $num_results2 = $result2->num_rows;

          if($num_results2==0){
            echo '<script type="text/javascript">alert("No user information in database.");</script>';
              exit;
          }

          $row_user = $result2->fetch_assoc();
?>
<html lang="en">
<head>
<title>Booking Confirmation</title>
<meta charset="utf-8">
<link rel="stylesheet" href="welcome.css">
<style type="text/css">
td{
	text-align: left;
	padding-left: 15px;
}

td.tag{
text-align: right;
width:40%;
font-weight: bold;
}

</style>
<script type="text/javascript">
// Script - login.js

// Function called when the form is submitted.
// Function validates data and returns a Boolean value.
function validateForm() {
    'use strict';
    
    // Get references to the form elements:
    var billEmail = document.getElementById("billEmail");
    var billPhone = document.getElementById("billPhone");
    var billPostal = document.getElementById("billPostal");

    var pos1 = billEmail.value.search(/^[\w.-]+@[\w.-]+\.[\w.-]{2,4}$/);

    if(pos1!=0){
      alert("The email you typed is not in proper format.");
      return false;
    }

    var pos2 = billPhone.value.search(/^\+65[689][\d]{7}$/);

    if(pos2!=0){
      alert("The phone number you typed in not in the proper format, please follow +6512345678");
      return false;
    }

    var pos3 = billPostal.value.search(/^[\d]{6}$/);

    if(pos3!=0){
      alert("The postal code you typed is not in the proper format");
      return false;
    }



    
} // End of validateForm() function.

// Function called when the window has been loaded.
// Function needs to add an event listener to the form.
function init() {
    'use strict';
    
    // Confirm that document.getElementById() can be used:
    if (document && document.getElementById) {
        var confirmForm = document.getElementById("confirmForm");
        confirmForm.onsubmit = validateForm;
    }

} // End of init() function.

// Assign an event listener to the window's load event:
window.onload = init;
</script>
<!-- Below javascript libraries enable the function of "required" for ie and safari-->
<!-- cdn for modernizr, if you haven't included it already -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
<!-- polyfiller file to detect and load polyfills -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
<script>
  webshims.activeLang('en-AU'); //Set the format of the date to mm/dd/yyyy
  webshims.setOptions('waitReady', false);
  webshims.polyfill('forms forms-ext');
</script>

<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>
<link type="text/css" rel="stylesheet" href="jquery.mmenu.css" />
<script type="text/javascript">
$(document).ready(function() {
    // run test on initial page load
    checkSize();

    // run test on resize of the window
    $(window).resize(checkSize);
});
</script>
</head>

<body>
<div id="wrapper">
  <header>
     <div id="wrapper_header_left">
       <a href="index.php"><img class="logo" src="Image/logo.png" alt="Company Logo" ></a>
     </div>
   <div id="wrapper_header_right">
    <h2 class="title">Centre for Optical Fibre Technology</h2>
   </div>
  </header>
  <div id="burger" style="width:100%; background-color: #003478; height: 35px; display: none;"><a href="#menu"><img class="hamburger" src="Image/Icon/burger.png" alt="=" ></a></div>
<nav class="cssmenu" id="menu"><ul>
       <span id="nav_first"><li><a id = "modal_trigger" href="#modal">Login</a></li></span>
         <span id="nav_hide" style="display:none"></span>
           <li><a href="results.php">Facility List</a></li>
           <li><a href="orderHistory.php">Order History</a></li>
       </ul>
  </nav>
  <div id="crumb">
       <ul>
        <li><a href="http://www.coft.eee.ntu.edu.sg/aboutUs/Pages/CentreFacilities.aspx">COFT</a></li>
        <li class="slash">/</li>
        <li><a href="results.php">Facility List</a></li>
        <li class="slash">/</li>
        <li><a href="item.php?itemname=<?php echo $itemname?>">Select Time</a></li>
        <li class="slash">/</li>
        <li><a class="active">Confirmation</a></li>
       </ul>
  </div>

<?php

if (isset($_SESSION['valid_user'])){ ?>

<script language="JavaScript">

 document.getElementById("nav_first").style.display = 'none';
 document.getElementById("nav_hide").style.display = '';
 document.getElementById("nav_hide").innerHTML = "<li><a>Hi, <?php echo $_SESSION['valid_user']; ?></a></li><li><a href='logout.php'>Sign Out</a></li><li><a href='editUserInformation.php'>My Account</a></li>";

</script>

<?php } ?>

<div id="modal" class="popupContainer" style="display:none;">
    <header class="popupHeader">
      <span class="header_title">Login</span>
      <span class="modal_close"><i class="fa fa-times"></i></span>
    </header>
    
    <section class="popupBody">

      <div class="user_login">
        <form id="loginForm" method="post" action="processLogin.php">
          <label>Username</label>
          <input type="text" name="username" id="username"/>
          <br />

          <label>Password</label>
          <input type="password" name="loginPwd" id="loginPwd"/>
          <br />

          <table style="margin-top:20px">
            <tr><td><input type="submit" name="Submit" id="Submit" value="Log in" class="button"></td>
              <td><a href="#" class="back_btn">Cancel</a></td>
              </tr>
          </table>
        </form>

      </div>
    </section>
  </div>

<script type="text/javascript">
  $("#modal_trigger").leanModal({top : 200, overlay : 0.6, closeButton: ".back_btn" });
</script>

<div class="content" style="min-height:450px; font-size:0.9em;"> 
<h3 style="text-align:center;margin-top:50px; color:#0b78a1">Booking Confirmation</h3>
	<table border="0" style="width:80%" cellspacing="15">
		<tr><td class="tag">Facility Name:</td>
			<td><?php echo $itemname ?></td>
		</tr>
		<tr><td class="tag">Time:</td>
			<td><?php echo $start_old." - ".$end_old ?></td>
		</tr>
		<tr><td class="tag">Basic Price per Hour:</td>
			<td>SGD <?php echo $price_per_hour ?></td>
		</tr>
		<tr><td class="tag">Total Price:</td>
			<td>SGD <?php echo $total ?></td>
		</tr>
		<tr><td class="tag">Message:</td>
			<td><?php echo $message ?></td>
		</tr>
	</table>
		<hr>
	<form id="confirmForm" method="POST" action="processCheckout.php">	
	<table border="0" cellspacing="15">
		<caption><h3 style="text-align:center;color:#0b78a1">Billing Information</h3></caption>
		<tr><td class="tag">Name:</td>
			<td><input type="text" required value="<?php if($identity == "internal"){echo $row_user['username'];} ?>" class="box" id="billName" name="billName"></td>
		</tr>
		<tr><td class="tag">Email:</td>
			<td><input type="text" required value="<?php if($identity == "internal"){echo $row_user['email'];} ?>" class="box" id="billEmail" name="billEmail"></td>
		</tr>
		<tr><td class="tag">Phone:</td>
			<td><input type="text" required value="<?php if($identity == "internal"){echo $row_user['phone'];} ?>" class="box" id="billPhone" name="billPhone"></td>
		</tr>
    <tr><td class="tag">Address Line 1:</td>
    <td><input type="text" name="billAddline1" id="billAddline1" value="<?php if($identity == "internal"){echo $row_user['addline1'];} ?>" size = "30" required class="box"></td></tr>
    <tr><td class="tag">Address Line 2:<br>(Optional)</td>
    <td><input type="text" name="billAddline2" id="billAddline2" value="<?php if($identity == "internal"){echo $row_user['addline2'];} ?>" size = "30" class="box"></td></tr>
    <tr><td class="tag">Postal Code:</td>
    <td><input type="text" name="billPostal" id="billPostal" value="<?php if($identity == "internal"){echo $row_user['postalcode'];} ?>" size = "30" required class="box"></td></tr>
    <tr><td class="tag">Organization:</td>
    <td><input type="text" name="Organization" id="Organization" value="<?php if($identity == "internal"){echo $row_user['faculty'];} ?>" size = "30" required class="box"></td></tr>
		<tr><td colspan="2" style="text-align:center"><h4>* Upon checking out, a confirmation email will be sent to your email address.</h4></td></tr>
		<tr><td colspan="2" style="text-align:center"><input type="submit" class="button" value="Check Out">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="item.php?itemname=<?php echo $itemname?>" class="back_btn">Cancel</a></td>
		</tr>
	</table>
	<input type="hidden" value="<?php echo $itemname ?>" id="itemname" name="itemname">
	<input type="hidden" value="<?php echo $start ?>" id="start" name="start">
	<input type="hidden" value="<?php echo $end ?>" id="end" name="end">
	<input type="hidden" value="<?php echo $message ?>" id="message" name="message">
	<input type="hidden" value="<?php echo $total ?>" id="total_price" name="total_price">
	<input type="hidden" value="<?php echo $row['need_remind'] ?>" id="need_remind" name="need_remind">
	<input type="hidden" value="<?php echo $identity; ?>" id="identity" name="identity">
  <input type="hidden" value="<?php echo $row_user['email'];?>" id="userEmail" name="userEmail">
  <!-- Above email is the email of current user, this will be used to send confirmation email-->

	</form> 
</div>
        <footer>Copyright &copy; 2014
        </footer>
</body>

<?php
		unset($row);

		$result->free();
        $db->close();
?>
<script type="text/javascript">
function checkSize(){
  if ($(".title").css("float") != "right" ){
    document.getElementById("burger").style.display = '';
    $(function() {
      $('nav#menu').mmenu();
    });
  }
}
</script>

</html>

