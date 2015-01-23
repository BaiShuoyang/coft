<!DOCTYPE html><?php


session_start();

$itemname = $_POST['itemname'];
$start = $_POST['start'];
$end = $_POST['end'];
$message = $_POST['message'];
if(isset($_POST['price'])){
	$price = $_POST['price'];
  if(!is_numeric($price)){
    echo '<script type="text/javascript">alert("Error: The price you input is not a number.");</script>';
    unset($price);
    exit();
  }

}
if(isset($_POST['user_identity'])){
	$identity = $_POST['user_identity'];
}
if(isset($_POST['number'])){
	$number = $_POST['number'];
  if(!is_numeric($number)){
    echo '<script type="text/javascript">alert("Error: The number of fibre you input is not a number.");</script>';
    unset($number);
    exit();
  }
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

		$query = "select * from item where facility_name = '".$itemname."'";

  		$result = $db->query($query);

  		$num_results = $result->num_rows;

  		if($num_results == 0){
  			echo '<script type="text/javascript">alert("No facility item in database.");</script>';
  		    exit;
  		}

  		$row = $result->fetch_assoc();

  		if($row['isFabricationFacility']==1){
  			$total = $price * $number;
  		}else{
			$start_time = strtotime($start);
			$end_time = strtotime($end);
	  		$time_difference = round(($end_time - $start_time)/(60*60),2); //In hours (float)

	  		if(($_SESSION['user_identity']=="internal") || ($_SESSION['user_identity']=="admin")){
	  			$price_per_day = $row['price'] * $row['charge_internal'];
	  		}else if($_SESSION['user_identity']=="external"){
	  			$price_per_day = $row['price'] * $row['charge_external'];
	  		}else{
	  			echo '<script type="text/javascript">alert("There is problem with user identity.");</script>';
	  		    exit;
	  		}
  		
  			$total = $time_difference / 10 * $price_per_day;
  		}

  		if($_SESSION['user_identity']=="internal"){
  			$query2 = "select * from internal_user where username = '".$username."'";
  		}else if($_SESSION['user_identity']=="admin"){
  			$query2 = "select * from admin_user where username = '".$username."'";
  		}else{
  			$query2 = "select * from external_user where username = '".$username."'";
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
    var newEmail = document.getElementById("newEmail");
    var newPhone = document.getElementById("newPhone");

    var pos1 = newEmail.value.search(/^[\w.-]+@[\w.-]+\.[\w.-]{2,4}$/);

    if(pos1!=0){
      alert("The email you typed is not in proper format.");
      return false;
    }

    var pos2 = newPhone.value.search(/^\+65[689][\d]{7}$/);

    if(pos2!=0){
      alert("The phone number you typed in not in the proper format, please follow +6562345678");
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
  <div class="cssmenu"><ul>
       <span id="nav_first"><li><a id = "modal_trigger" href="#modal">Login</a></li></span>
         <span id="nav_hide" style="display:none"></span>
           <li><a href="results.php">Facility List</a></li>
           <li><a href="orderHistory.php">Order History</a></li>
       </ul>
  </div>
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

<div class="content" style="min-height:450px"> 
<h2 style="text-align:center;margin-top:50px">Booking Confirmation</h2>
	<table border="0" style="width:80%" cellspacing="15">
		<tr><td class="tag">Facility Name:</td>
			<td><?php echo $itemname ?></td>
		</tr>
		<tr><td class="tag">Time:</td>
			<td><?php echo $start." - ".$end ?></td>
		</tr>
		<?php if($row['isFabricationFacility']==1){?>
		<tr><td class="tag">Basic Price per Fibre:</td>
			<td>SGD <?php echo $price ?></td>
		</tr>
		<?php }else{?>
		<tr><td class="tag">Basic Price per day:</td>
			<td>SGD <?php echo $price_per_day ?></td>
		</tr>
		<?php }?>
		<tr><td class="tag">Total Price:</td>
			<td>SGD <?php echo $total ?></td>
		</tr>
		<tr><td class="tag">Message:</td>
			<td><?php echo $message ?></td>
		</tr>
	<table>
		<hr>
	<form id="confirmForm" method="POST" action="processCheckout.php">	
	<table border="0" cellspacing="15">
		<caption><h2>Reservation Contact</h2></caption>
		<tr><td class="tag">Name:</td>
			<td><p style="margin-left: 50px;"><?php echo $row_user['username'] ?></p></td>
		</tr>
		<tr><td class="tag">Email:</td>
			<td><input type="text" required value="<?php echo $row_user['email'] ?>" class="box" id="newEmail" name="newEmail"></td>
		</tr>
		<tr><td class="tag">Phone:</td>
			<td><input type="text" required value="<?php echo $row_user['phone'] ?>" class="box" id="newPhone" name="newPhone"></td>
		</tr>
		<tr><td colspan="2" style="text-align:center"><h4>* Upon checking out, an email with NTU invoice will be sent to your email address.</h4></td></tr>
		<tr><td></td><td><input type="submit" class="button" value="Check Out">
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
	<?php if($row['isFabricationFacility']==1){?>
	<input type="hidden" value="<?php echo $identity; ?>" id="identity" name="identity">
  	<?php }?>

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

</html>