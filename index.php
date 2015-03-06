<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">

<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>

<style type="text/css">

a:not(.back_btn) {
color: #0b78a1;
text-decoration: none;
}

a:not(.back_btn):hover {
color: #22b8f0;
}

</style>
<link rel="stylesheet" href="welcome.css">
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
  		 <!-- <span id="nav_first"><li><a id = "modal_trigger" href="#modal">Login</a></li></span> -->
         <span id="nav_hide" style="display:none"></span>
         	 <li><a href="results.php">Facility List</a></li>
	         <li><a href="orderHistory.php">Order History</a></li>
       </ul>
  </nav>


<?php

if (isset($_SESSION['valid_user'])){ ?>

<script language="JavaScript">

 // document.getElementById("nav_first").style.display = 'none';
 document.getElementById("nav_hide").style.display = '';
 document.getElementById("nav_hide").innerHTML = "<li><a>Hi, <?php echo $_SESSION['valid_user']; ?></a></li><li><a href='logout.php'>Sign Out</a></li><li><a href='editUserInformation.php'>My Account</a></li>";
</script>

<?php } ?>

<!-- <div id="modal" class="popupContainer" style="display:none;">
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
	</div> -->

<script type="text/javascript">
// 	$("#modal_trigger").leanModal({top : 200, overlay : 0.6, closeButton: ".back_btn" });
// </script>
	
<div class="content" style="min-height:450px;">
	<?php if(isset($_GET['redirect'])){?>
		<p class="messageWarning">Please login or register to view the availability of the facilities.</p>
	<?php }?>
	<?php if(isset($_GET['login_fail'])){?>
		<div class="messageWarning">
			<h4>Warning</h4>
			<p>Username and password do not match or you do not have an account yet.</p>
		</div>
	<?php }?>
	<div class="messageWelcome">
	<h3>Welcome to Facility Booking System</h3>
	<p>Please log in using your registered account.</p>
	<p>If you are new users, please click "Create an account".</p>
	</div>

	<?php if(isset($_SESSION['valid_user'])){?>
          <div style="margin:50px auto;height:200px; padding-left:60px;"><a href='logout.php' class="back_btn">Sign Out</a></div>
          <?php }else{?>
	<form id="loginForm" action="processLogin.php" method="post" class="loginForm">
  	<table cellspacing="10"> 
		<tr><td><input type="text" name="username" id="username" size = "20" required class="box" placeholder="Username"></td></tr>
		<tr><td><input type="password" name="loginPwd" id="loginPwd" size = "20" required class="box" placeholder="Password"></td></tr>
		<tr><td><input type="submit" name="Submit" id="Submit" value="Log in" class="button" style="margin-left:50px;float:left"></td></tr>
		<tr><td><a href="registration.php" style="margin-left:50px;float:left;">Create an account</a></td></tr>
		<tr><td><a href="forgetPassword.php" style="margin-left:50px;float:left;">Forget password</a></td></tr>
	</table>
 	</form>
    <?php }?>

</div>
<footer>Copyright &copy; 2014
</footer>
</div>
</body>
<script type="text/javascript">
//This JavaScript is to show the placeholder in early IE version
function supports_input_placeholder() {
        var i = document.createElement('input');
        return 'placeholder' in i;
    }

if (!supports_input_placeholder()) {
    var fields = document.getElementsByTagName('INPUT');
    for (var i = 0; i < fields.length; i++) {
        if (fields[i].hasAttribute('placeholder')) {
            fields[i].defaultValue = fields[i].getAttribute('placeholder');
            fields[i].onfocus = function () { if (this.value == this.defaultValue) this.value = ''; }
            fields[i].onblur = function () { if (this.value == '') this.value = this.defaultValue; }
        }
    }
}
</script>
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


