<?php
//approveBooking.php
session_start();

?><!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<style type="text/css">
td{
	padding:5px;
}

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

  $query_booking = "SELECT * FROM booking WHERE approved = 0";

  $result_booking = $db->query($query_booking);

  $num_results_booking = $result_booking->num_rows;

?>
  

<div class="content"> 
   <h3 style="text-align:center;color:#0b78a1; margin-top: 50px;">Booking Approval</h3><hr>
   <table border="1" style="width:100%; font-size: 0.9em">
	<thead style="background-color:#c8c8c8">
	<tr>
		<td>No.</td>
		<td>User Name</td>
    <td>Facility</td>
    <td>Date</td>
    <td>Booking Interval</td>
    <td>Message</td>
		<th>Action</th>
	</tr>
	</thead>
	<tbody>
   <?php
	$total = 0;
		for ($i = 0; $i < $num_results_booking; $i++) { 

  		$row = $result_booking->fetch_assoc();

		echo '<tr>';
		echo '<td>'.($i+1).'</td>';
		echo "<td><a href='userList.php?username=".$row['username']."&user_identity=".$row['user_identity']."'>".$row['username']."</a></td>";
		echo '<td>'.$row['facility_name'].'</td>';
    echo '<td>'.$row['booking_date'].'</td>';
		echo '<td>'.$row['start_event'].' - '.$row['end_event'].'</td>';
		echo '<td>'.$row['message'].'</td>';
		echo '<td><a href=processApproveBooking.php?approveId='.$row['booking_id'].'>Approve</a>&nbsp;&nbsp;&nbsp;
				  <a href=processApproveBooking.php?denyId='.$row['booking_id'].'>Deny</a></td>';		
		echo '</tr>';

		unset($row);

		}

        $result_booking->free();
        $db->close();

        if(isset($_GET['conflict'])){
          echo '<script type="text/javascript">alert("The time slot has already been booked by someone else.");</script>';
        }
        if(isset($_GET['emailFail'])){
          echo '<script type="text/javascript">alert("Email sending failed for your approval or denying. Please try again.");</script>';
        }
        
	?>
	</tbody>
	</table>
</div>
<footer>Copyright &copy; 2014
</footer>
</div>
</body>
</html>