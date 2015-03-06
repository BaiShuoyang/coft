<?php
//postAccessDenied.php
session_start();

?><!DOCTYPE html>
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


<div class="content" style="min-height:450px;"> 
<h4 style="margin-top:50px">Dear user, you do not have access to this facility. Please try <a href="results.php">other facilities.</a></h4>
<h4>If you have any queries, you could send email to epshum@ntu.edu.sg.</h4>
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
