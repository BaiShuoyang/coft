<?php
session_start();
//userList.php

$username = $_GET['username'];
$user_identity = $_GET['user_identity'];

  $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

if(($user_identity == "external") || ($user_identity == "external_nonapproved")){
	$query = "SELECT * FROM external_user WHERE username = '".$username."'";
}else if($user_identity == "internal"){
	$query = "SELECT * FROM internal_user WHERE username = '".$username."'";
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
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModalTerm.min.js"></script>
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

.box{
  background-color: #eaeaea;
}
</style>
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
   <h3 style="text-align:center;color:#0b78a1;margin-top:50px;">User Information</h3><hr>
  	<table cellspacing="20"> 
  		<?php if(($user_identity == "external") || ($user_identity == "external_nonapproved")){?>
  		 <tr><td class="tag">Registration Date:</td>
		<td><p style="margin-left:50px;"><?php echo $row['registration_date']; ?></p></td></tr>
		<?php }?>
		<tr><td class="tag">Username:</td>
		<td class="input"><input type="text" name="Username" id="Username" size = "30" value="<?php echo $row['username']?>" required class="box" readonly></td>
		</tr>
		<tr><td class="tag">Password:</td>
		<td class="input"><input type="password" name="Password" id="Password" size = "30" required class="box" readonly></td>
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
    <tr><td class="tag">Company:</td>
    <td class="input"><input type="text" name="Company" id="Company" size = "30" value="<?php echo $row['company']?>" required class="box" readonly></td>
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
</html>