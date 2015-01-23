<?php
//editFacility.php
session_start();
?><!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<style type="text/css">
select {
background-image:url(Image/Icon/arrow.png);
background-repeat:no-repeat;
background-position:350px;
width:400px;
padding:5px;
margin-top:8px;
line-height:1.5;
border-radius:5px;
background-color:#fff;
color:#000;
-webkit-appearance:none;
box-shadow:inset 0 0 10px 0 rgba(0,0,0,0.6);
outline:none;
font-weight:bold;
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

<?php

  @ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query = "select * from item";

  $result = $db->query($query);

  $num_results = $result->num_rows;

?>
<div class="content"> 
	<h3 style="text-align:center;color:#0b78a1; margin-top:50px">Facility Editing</h3><hr>
  <form id="editListForm" action="newFacility.php" method="post" style="text-align: center;">
  	<div class="select_join">
  		<select size="1" name="editFacility">
  		<?php for ($i=0; $i < $num_results; $i++) {
         $row = $result->fetch_assoc();
        ?>
	   <option value="<?php echo $row['facility_name']?>"><?php echo $row['facility_name']?></option>
	   <?php }?>
	</select>&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Submit" class="button"/>
</div>
 </form>
</div>

<?php $db->close();?>

<footer>Copyright &copy; 2014
</footer>
</div>
</body>
</html>