<?php
//resetPassword.php
session_start();

if(isset($_GET['identity'])) {$identity = $_GET['identity'];}
	 else{
	 	echo '<script type="text/javascript">alert("Error: Identity passed by GET failed.");</script>';
        exit;
 	}
if(isset($_GET['token'])) {$token = $_GET['token'];}
	else{
	 	echo '<script type="text/javascript">alert("Error: Token passed by GET failed.");</script>';
        exit;
 	}

$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

if($identity=="normal"){

	$query = "SELECT * FROM normal_user WHERE token = '$token'";
	$result = $db_conn->query($query);

}else if($identity=="admin"){

	$query = "SELECT * FROM admin_user WHERE token = '$token'";
	$result = $db_conn->query($query);

}else{

	echo '<script type="text/javascript">alert("Error: Identity passed by GET failed.");</script>';
      exit;	
}

if(!$result){
	echo '<script type="text/javascript">alert("Error: Token invalid.");</script>';
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<link rel="stylesheet" href="welcome.css">
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
<style type="text/css">
table{
width:100%;
margin-top:5px;
margin-bottom:5px;
margin-left: auto;
margin-right: auto;

}

tr{
margin-left: auto;
margin-right: auto;
}

td{
text-align: left;

}
td.tag{
text-align: right;
width:40%;
}
</style>
<script type="text/javascript">
// Script - login.js

// Function called when the form is submitted.
// Function validates data and returns a Boolean value.
function validateForm() {
    'use strict';
    
    // Get references to the form elements:
    var password = document.getElementById("Password");
    var confirmpassword = document.getElementById("ConfirmPassword");

    // Validate!
    if(password.value!==confirmpassword.value){
      alert("The passwords you typed do not match");
      return false;
    }
    
} // End of validateForm() function.

// Function called when the window has been loaded.
// Function needs to add an event listener to the form.
function init() {
    'use strict';
    
    // Confirm that document.getElementById() can be used:
    if (document && document.getElementById) {
        var resetForm = document.getElementById("resetForm");
        resetForm.onsubmit = validateForm;
    }

} // End of init() function.

// Assign an event listener to the window's load event:
window.onload = init;
</script>
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
           <li></li>
           <li></li>
       </ul>
  </nav>

<div class="content" style="min-height:450px"> 
  <form id="resetForm" action="processReset.php" method="post" style="text-align: center; margin-top:50px">
    <table cellspacing="20"> 
    	<tr><td class="tag">New Password:</td>
		<td><input type="password" name="Password" id="Password" size = "30" required class="box"></td></tr>
		<tr><td class="tag">Confirm Password:</td>
		<td><input type="password" name="ConfirmPassword" id="ConfirmPassword" size = "30" required class="box"></td></tr>
		<tr><td colspan="2" style="text-align:center"><input type="submit" name="Submit" id="Submit" value="Confirm" class="button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    <a href="index.php" class="back_btn">Cancel</a></td>
	    </tr>
	</table>
	<input type="hidden" value="<?php echo $identity ?>" id="identity" name="identity">
	<input type="hidden" value="<?php echo $token ?>" id="token" name="token">
 </form>
</div>


<footer>Copyright &copy; 2014
</footer>
</div>
</body>
<?php
$db_conn->close();
?>
</html>
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
