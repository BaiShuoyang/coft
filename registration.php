<?php
session_start();

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

a:not(.back_btn):hover {
color: #22b8f0;
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
<script type="text/javascript" src="js/jquery.leanModalTerm.min.js"></script>
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
    var password = document.getElementById("Password");
    var confirmpassword = document.getElementById("ConfirmPassword");
    var postal = document.getElementById("Postal");
    var phone = document.getElementById("Phone");

    // Validate!
    if(password.value!==confirmpassword.value){
      alert("The passwords you typed do not match");
      return false;
    }

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
        var regForm = document.getElementById("regForm");
        regForm.onsubmit = validateForm;
    }

} // End of init() function.

// Assign an event listener to the window's load event:
window.onload = init;
</script>

<script language="javascript">
//For password strength checking
	jQuery(document).ready(function() {
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
width:84%;
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
  padding-left: 15px;
}

td.tag{
text-align: right;
width:40%;
font-weight: bold;
}

.box{
width:300px;
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

<div id="modalTerm" class="popupContainer" style="display:none;color:#000000">
		<header class="popupHeader">
			<span class="header_title">Terms of Service</span>
			<span class="modal_close"><i class="fa fa-times"></i></span>
		</header>
		<section class="popupBody" style="text-align:justify;line-height:1.5em">
				<p>PLEASE READ THESE TERMS OF SERVICE ("AGREEMENT" OR "TERMS OF SERVICE") CAREFULLY BEFORE USING THE WEBSITE AND SERVICES OFFERED BY FOREVER YOUNG. THIS AGREEMENT SETS FORTH THE LEGALLY BINDING TERMS AND CONDITIONS FOR YOUR USE OF THE WEBSITE AT HTTP://WWW.FOREVERYOUNG.COM (THE "SITE") AND ALL SERVICES PROVIDED BY FOREVER YOUNG ON THE SITE. 
					<br><br>By using the Site in any manner, including but not limited to visiting or browsing the Site, you (the "user" or "you") agree to be bound by this Agreement, including those additional terms and conditions and policies referenced herein and/or available by hyperlink. This Agreement applies to all users of the Site, including without limitation users who are vendors, customers, merchants, contributors of content, information and other materials or services on the Site.</p>
                <h2>Membership Eligibility</h2>
                <p>Age: Forever Young's services are available only to, and may only be used by, individuals who are 18 years and older who can form legally binding contracts under applicable law. You represent and warrant that you are at least 18 years old and that all registration information you submit is accurate and truthful. Forever Young may, in its sole discretion, refuse to offer access to or use of the Site to any person or entity and change its eligibility criteria at any time. This provision is void where prohibited by law and the right to access the Site is revoked in such jurisdictions. Individuals under the age of 18 must at all times use Forever Young's services only in conjunction with and under the supervision of a parent or legal guardian who is at least 18 years of age. In this all cases, the adult is the user and is responsible for any and all activities. Compliance: You agree to comply with all local laws regarding online conduct and acceptable content. You are responsible for all applicable taxes. In addition, you must abide by Forever Young's policies as stated in the Agreement and the Forever Young policy documents listed below (if applicable to your activities on or use of the Site) as well as all other operating rules, policies and procedures that may be published from time to time on the Site by Forever Young, each of which is incorporated herein by reference and each of which may be updated by Forever Young from time to time without notice to you: • The DOs & DON'Ts of Forever Young • Fees Policy • Privacy Policy • Copyright and Intellectual Property Policy • Trademark Guidelines • Direct Checkout Terms of Use • Gift Cards Terms of Use • Billing Policy • API Terms of Use In addition, some services offered through the Site may be subject to additional terms and conditions promulgated by Forever Young from time to time; your use of such services is subject to those additional terms and conditions, which are incorporated into this Agreement by this reference. Password: Keep your password secure. You are fully responsible for all activity, liability and damage resulting from your failure to maintain password confidentiality. You agree to immediately notify Forever Young of any unauthorized use of your password or any breach of security. You also agree that Forever Young cannot and will not be liable for any loss or damage arising from your failure to keep your password secure. You agree not to provide your username and password information in combination to any other party other than Forever Young without Forever Young's express written permission. Account Information: You must keep your account information up-to-date and accurate at all times, including a valid email address. To sell items on Forever Young you must provide and maintain valid payment information such as valid credit card information and a valid PayPal account. Account Transfer: You may not transfer or sell your Forever Young account and User ID to another party. If you are registering as a business entity, you personally guarantee that you have the authority to bind the entity to this Agreement. Right to Refuse Service: Forever Young's services are not available to temporarily or indefinitely suspended Forever Young members. Forever Young reserves the right, in Forever Young's sole discretion, to cancel unconfirmed or inactive accounts. Forever Young reserves the right to refuse service to anyone, for any reason, at any time.</p>
                <h2>Privacy</h2>
                <p>Except as provided in Forever Young's Privacy Policy Forever Young will not sell or disclose your personal information (as defined in the Privacy Policy) to third parties without your explicit consent. Forever Young stores and processes Content on computers located in Singapore that are protected by physical as well as technological security.</p>
		</section>
</div>	

<script type="text/javascript">
	$("#modal_trigger").leanModal({top : 200, overlay : 0.6, closeButton: ".back_btn" });
</script>
  
<?php

  @ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query = "SELECT * FROM item";

  $result = $db->query($query);

  $num_results = $result->num_rows;

?>

<div class="content" style="font-size:0.9em;"> 
   <h3 style="text-align:center;color:#0b78a1;margin-top:50px;">User Registration</h3><hr>
  <form id="regForm" action="processRegister.php" method="post" style="text-align: center;">
  	<table cellspacing="20"> 
		<tr><td class="tag">Username:</td>
		<td><input type="text" name="Username" id="Username" size = "30" required class="box"></td></tr>
		<tr><td class="tag">Password:</td>
		<td><input type="password" name="Password" id="Password" size = "30" required class="box"></td>
		<td style="padding-left:0; min-width:80px;"><span style="color:green; font-size:90%;" id='Result'></span></td></tr>
		<tr><td class="tag">Confirm Password:</td>
		<td><input type="password" name="ConfirmPassword" id="ConfirmPassword" size = "30" required class="box"></td></tr>
		<tr><td class="tag">Email Address:</td>
    <td><input type="email" name="Email" id="Email" size = "30" required class="box"></td></tr>
    <tr><td class="tag">Address Line 1:</td>
		<td><input type="text" name="Addline1" id="Addline1" size = "30" required class="box"></td></tr>
		<tr><td class="tag">Address Line 2:<br>(Optional)</td>
		<td><input type="text" name="Addline2" id="Addline2" size = "30" class="box"></td></tr>
		<tr><td class="tag">Postal Code:</td>
		<td><input type="text" name="Postal" id="Postal" size = "30" required class="box"></td></tr>
		<tr><td class="tag">Phone:</td>
		<td><input type="text" name="Phone" id="Phone" size = "30" required class="box"></td></tr>
    <tr><td class="tag">Faculty:</td>
    <td><input type="text" name="Company" id="Company" size = "30" required class="box"></td></tr>
    <tr><td class="tag">Select facilities to register for:</td>
        <td><select name="Facility_access[]" id="Facility_access" required multiple size="5" class="box" style="height:auto">
            <?php for ($i=0; $i < $num_results; $i++) {
               $row = $result->fetch_assoc();
              ?>
            <option value="<?php echo $row['facility_name']?>"><?php echo $row['facility_name']?></option>
           <?php }?>
            </select><td>
    </tr>
		<tr><td colspan="2" style="text-align:center"><input type="checkbox" name="CheckTerm" id="CheckTerm" required value="checked"><a id = "modal_trigger2" href="#modalTerm" style="text-decoration: none;">I agree with the terms of service.</a></td></tr>
    <tr><td colspan="2" style="text-align:center"><input type="submit" name="Submit" id="Submit" value="Register" class="button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="index.php" class="back_btn">Cancel</a></td>
    </tr>
	</table>
 </form>
</div>

<script type="text/javascript">
	$("#modal_trigger2").leanModalTerm({top : 60, overlay : 0.6, closeButton: ".back_btn" });
</script>

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