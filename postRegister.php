<?php
//postRegister.php
session_start();

?><!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
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
<?php
if(isset($_SESSION['valid_user']) && ($_SESSION['user_identity'] == "external_nonapproved")){
?>
<h4 style="margin-top:50px">Thank you for your registration. The system administrator will approve your request as soon as possible.</h4>
<h4>However, you can only use your account once you receive an account approval email from the administrator.</h4>
<?php }?>
</div>
<footer>Copyright &copy; 2014
</footer>
</div>
</body>
</html>
