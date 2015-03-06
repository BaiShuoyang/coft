<?php
session_start();
//billList.php

$booking_id = $_GET['booking_id'];

  $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

	$query = "SELECT * FROM billing_information WHERE booking_id = '".$booking_id."'";


  $result = $db->query($query);

  if(!$result){
  	 echo '<script type="text/javascript">alert("Your query has failed.");</script>';
     exit;
  }

  $row = $result->fetch_assoc();


?><!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<style>
a:not(.back_btn) {
color: #0b78a1;
text-decoration: none;
}

a.edit:hover {
color: #22b8f0;
background: url('Image/Icon/edit_small.png') no-repeat left;
cursor: pointer;
}

</style>
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModalTerm.min.js"></script>
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>
<link rel="stylesheet" href="welcome.css">
<style type="text/css">
td{
  text-align: left;
  padding-left: 15px;
}

td.tag{
text-align: right;
width:40%;
}

.box{
  background-color: #eaeaea;
}
</style>
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

      <!-- Username & Password Login form -->
      <div class="user_login">
        <form id="loginForm" method="post" action="processLogin.php">
          <label>Username</label>
          <input type="text" name="username" id="username"/>
          <br />

          <label>Password</label>
          <input type="password" name="loginPwd" id="loginPwd"/>
          <br />

          <table style="margin-top:20px">
            <tr><td style="text-align: center"><input type="submit" name="Submit" id="Submit" value="Log in" class="button"></td>
              <td style="text-align: center"><a href="#" class="back_btn"><i class="fa fa-angle-double-left"></i> Cancel</a></td>
              </tr>
          </table>
        </form>

      </div>
    </section>
  </div>

<script type="text/javascript">
	$("#modal_trigger").leanModal({top : 200, overlay : 0.6, closeButton: ".back_btn" });
</script>
  

<div class="content" style="font-size:0.9em;"> 
   <h3 style="text-align:center;color:#0b78a1;margin-top:50px;">Billing Information</h3><hr>
  	<table cellspacing="20"> 
		<tr><td class="tag">Name:</td>
		<td class="input"><input type="text" name="Username" id="Username" size = "30" value="<?php echo $row['name']?>" required class="box" readonly></td>
		</tr>
    <tr><td class="tag">Email Address:</td>
	    <td class="input"><input type="email" name="Email" id="Email" size = "30" value="<?php echo $row['email']?>" required class="box" readonly></td>
		</tr>
	    <tr><td class="tag">Address Line 1:</td>
		<td class="input"><input type="text" name="Addline1" id="Addline1" size = "30" value="<?php echo $row['addline1']?>" required class="box" readonly></td>
		</tr>
		<tr><td class="tag">Address Line 2:<br>(Optional)</td>
		<td class="input"><input type="text" name="Addline2" id="Addline2" size = "30" value="<?php echo $row['addline2']?>" class="box" readonly></td>
		</tr>
		<tr><td class="tag">Postal Code:</td>
		<td class="input"><input type="text" name="Postal" id="Postal" size = "30" value="<?php echo $row['postalcode']?>" required class="box" readonly></td>
		</tr>
		<tr><td class="tag">Phone:</td>
		<td class="input"><input type="text" name="Phone" id="Phone" size = "30" value="<?php echo $row['phone']?>" required class="box" readonly></td>
		</tr>
    <tr><td class="tag">Faculty:</td>
    <td class="input"><input type="text" name="Faculty" id="Faculty" size = "30" value="<?php echo $row['organization']?>" required class="box" readonly></td>
		</tr>
	</table>
</div>
<?php
  $db->close();
?>
<footer>Copyright &copy; 2014
</footer>
</div>
</body>
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