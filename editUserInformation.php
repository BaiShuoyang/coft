<?php
session_start();

$username = $_SESSION['valid_user'];
$user_identity = $_SESSION['user_identity'];

  $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

if(($user_identity == "normal") || ($user_identity == "normal_nonapproved")){
	$query = "SELECT * FROM normal_user WHERE username = '".$username."'";
}else if($user_identity == "admin"){
	$query = "SELECT * FROM admin_user WHERE username = '".$username."'";
}else{
	echo '<script type="text/javascript">alert("Error: User identity is not valid.");</script>';
    exit;
}

  $result = $db->query($query);

  if(!$result){
  	 echo '<script type="text/javascript">alert("Your query has failed.");</script>';
     exit;
  }

  $num_results = $result->num_rows;

  if($num_results!=1){
     echo '<script type="text/javascript">alert("Error: No record or more than one record has the same name in database.");</script>';
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
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<script language="javascript" src="js/jquery.js"></script>
<script language="javascript" src="js/passwordStrengthMeter.js"></script>
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>
<script type="text/javascript">
// Script - login.js

// Function called when the form is submitted.
// Function validates data and returns a Boolean value.
function validateForm() {
    'use strict';
    
    // Get references to the form elements:
    var email = document.getElementById("Email");
    var postal = document.getElementById("Postal");
    var phone = document.getElementById("Phone");

    // Validate!
    var pos1 = email.value.search(/^[\w.-]+@[\w.-]+\.[\w.-]{2,4}$/);

    if(pos1!=0){
      alert("The email you typed is not in proper format.");
      return false;
    }

    var pos2 = postal.value.search(/^[\d]{6}$/);

    if(pos2!=0){
      alert("The postal code you typed is not in the proper format");
      return false;
    }

    var pos3 = phone.value.search(/^\+65[689][\d]{7}$/);

    if(pos3!=0){
      alert("The phone number you typed in not in the proper format, please follow +6512345678");
      return false;
    }


    
} // End of validateForm() function.

// Function called when the window has been loaded.
// Function needs to add an event listener to the form.
function init() {
    'use strict';
    
    // Confirm that document.getElementById() can be used:
    if (document && document.getElementById) {
        var infoForm = document.getElementById("infoForm");
        infoForm.onsubmit = validateForm;
    }

} // End of init() function.

// Assign an event listener to the window's load event:
window.onload = init;

function editPassword(){
            document.getElementById("Password").readOnly = false;
            document.getElementById("Password").select();
            document.getElementById("isPasswordChanged").value = "yes";
}

function editEmail(){
            document.getElementById("Email").readOnly = false;
            document.getElementById("Email").select();
}

function editAddline1(){
            document.getElementById("Addline1").readOnly = false;
            document.getElementById("Addline1").select();
}

function editAddline2(){
            document.getElementById("Addline2").readOnly = false;
            document.getElementById("Addline2").select();
}

function editPostal(){
            document.getElementById("Postal").readOnly = false;
            document.getElementById("Postal").select();
}

function editPhone(){
            document.getElementById("Phone").readOnly = false;
            document.getElementById("Phone").select();
}

function editFaculty(){
            document.getElementById("Faculty").readOnly = false;
            document.getElementById("Faculty").select();
}
</script>

<script language="javascript">
//For password strength checking
	jQuery(document).ready(function() {
		$('#Username').keyup(function(){$('#Result').html(passwordStrength($('#Password').val(),$('#Username').val()))})
		$('#Password').keyup(function(){$('#Result').html(passwordStrength($('#Password').val(),$('#Username').val()))})
	})
	function showMore()
	{
		$('#more').slideDown()
	}
</script>

<link rel="stylesheet" href="welcome.css">
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
padding-left: 20px;
}
td.tag{
text-align: right;
}
td.input{
width:52%;
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
   <h3 style="text-align:center;color:#0b78a1;margin-top:50px;">My Information</h3><hr>
  <form id="infoForm" action="processEditUserInfo.php" method="post" style="text-align: center;">
  	<table cellspacing="20"> 
  		<?php if(($user_identity == "normal") || ($user_identity == "normal_nonapproved")){?>
  		 <tr><td class="tag">Registration Date:</td>
		<td><p style="margin-left:50px;"><?php echo $row['registration_date']; ?></p></td></tr>
		<?php }?>
		<tr><td class="tag">Username:</td>
		<td class="input"><input type="text" name="Username" id="Username" size = "30" value="<?php echo $row['username']?>" required class="box" readonly></td>
		</tr>
		<tr><td class="tag">Password:</td>
		<td class="input"><input type="password" name="Password" id="Password" size = "30" required class="box" readonly> <span style="color:green; font-size:90%;" id='Result'></span></td>
		<td><a onclick="editPassword()" class="edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a></td></tr>
    <tr><td class="tag">Email Address:</td>
	    <td class="input"><input type="email" name="Email" id="Email" size = "30" value="<?php echo $row['email']?>" required class="box" readonly></td>
		<td><a onclick="editEmail()" class="edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a></td></tr>
	    <tr><td class="tag">Address Line 1:</td>
		<td class="input"><input type="text" name="Addline1" id="Addline1" size = "30" value="<?php echo $row['addline1']?>" required class="box" readonly></td>
		<td><a onclick="editAddline1()" class="edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a></td></tr>
		<tr><td class="tag">Address Line 2:<br>(Optional)</td>
		<td class="input"><input type="text" name="Addline2" id="Addline2" size = "30" value="<?php echo $row['addline2']?>" class="box" readonly></td>
		<td><a onclick="editAddline2()" class="edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a></td></tr>
		<tr><td class="tag">Postal Code:</td>
		<td class="input"><input type="text" name="Postal" id="Postal" size = "30" value="<?php echo $row['postalcode']?>" required class="box" readonly></td>
		<td><a onclick="editPostal()" class="edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a></td></tr>
		<tr><td class="tag">Phone:</td>
		<td class="input"><input type="text" name="Phone" id="Phone" size = "30" value="<?php echo $row['phone']?>" required class="box" readonly></td>
		<td><a onclick="editPhone()" class="edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a></td></tr>
	    <tr><td class="tag">Faculty:</td>
	    <td class="input"><input type="text" name="Faculty" id="Faculty" size = "30" value="<?php echo $row['faculty']?>" required class="box" readonly></td>
		<td><a onclick="editFaculty()" class="edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a></td></tr>
    <tr><td colspan="3" style="text-align:center"><input type="submit" name="Submit" id="Submit" value="Save Changes" class="button">
    </tr>
	</table>
	<input type="hidden" value="<?php echo $row['user_id'] ?>" id="User_id" name="User_id">
  <input type="hidden" id="isPasswordChanged" name="isPasswordChanged" value="no">
  <!-- Above input stores the flag for the status if the password is changed-->
 </form>
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