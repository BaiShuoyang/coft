<?php
//forgetPassword.php

session_start();

if(isset($_GET['fail'])){

  echo '<script type="text/javascript">alert("The email you entered does not exist. Please try again.");</script>';

}
?><!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">

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
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>
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

}
td.tag{
text-align: right;
width:40%;
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
  

<div class="content"> 
   <h3 style="text-align:center;color:#0b78a1;margin-top:50px">Password Recovery</h3><hr>
  <form id="regForm" action="processRecovery.php" method="post" style="text-align: center;">
    <p style="text-align:justify">Please enter the email address for your account. An email will be sent to you. Once you have received the email, you will be able to set a new password for your account.</p>
  	<table cellspacing="20"> 
		<tr><td class="tag">Email:</td>
		<td><input type="text" name="Email" id="Email" size = "30" required class="box"></td></tr>
		<tr><td colspan="2" style="text-align:center"><input type="submit" name="Submit" id="Submit" value="Submit" class="button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="index.php" class="back_btn">Cancel</a></td>
    </tr>
	</table>
 </form>
</div>


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