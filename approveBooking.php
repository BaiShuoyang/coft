<?php
//approveBooking.php
session_start();

?><!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModalTerm.min.js"></script>
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>
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
<script type="text/javascript">
function setValueToPass(denyid){
  // alert(denyid);
  document.getElementById("DenyId").value = denyid;
}
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
       <span id="nav_first"><li><a>Login</a></li></span>
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

<?php

 @ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query_booking = "SELECT * FROM booking WHERE approved = 1 AND start_event >= CURDATE()";

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
    echo '<td><a class = "modal_trigger2" href="#modalTerm" onclick = "setValueToPass('.$row['booking_id'].')">Revoke</a></td>';
		// echo '<td><a href=processApproveBooking.php?denyId='.$row['booking_id'].'>Revoke</a></td>';		
		echo '</tr>';

		unset($row);

		}

        $result_booking->free();
        $db->close();

        // if(isset($_GET['conflict'])){
        //   echo '<script type="text/javascript">alert("The time slot has already been booked by someone else.");</script>';
        // }
        if(isset($_GET['emailFail'])){
          echo '<script type="text/javascript">alert("Email sending failed for your approval or denying. Please try again.");</script>';
        }
        
	?>
	</tbody>
	</table>
</div>

<div id="modalTerm" class="popupContainer" style="display:none;">
    <header class="popupHeader">
      <span class="header_title">Email</span>
      <span class="modal_close"><i class="fa fa-times"></i></span>
    </header>
    <section class="popupBody" style="text-align:justify;line-height:1.5em; width:80%">
      <div class="user_login">
        <form id="emailForm" method="POST" action="processApproveBooking.php">
          <textarea rows="18" cols="67" name="Content" id="Content" required style="resize:none; font-family: Times New Roman; color: #000">Dear user,

I am sorry to inform you that your booking has been revoked by the administrator. This could because you have not taken required training before using the facility.

The detail of your revoked booking is as below:

    Facility Name:
    Time:
    Total Price:  
    Message:

If you have any queries, you could send email to epshum@ntu.edu.sg.

Regards,
COFT Office

This is an automatically generated confirmation email. Please do not reply directly.</textarea>
          <table style="margin-top:1px">
            <tr><td><input type="submit" name="Submit" id="Submit" value="Send" class="button"></td>
              </tr>
          </table>
          <input type="hidden" value="" id="DenyId" name="DenyId">
        </form>
      </div>
    </section>
  </div>

<script type="text/javascript">
  $(".modal_trigger2").leanModalTerm({top : 100, overlay : 0.6, closeButton: ".back_btn" });
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