<!DOCTYPE html><?php
//generateInvoice.php
session_start();

if(isset($_POST['selected_booking'])){

$selected_booking = $_POST['selected_booking'];
$number_selected = sizeof($selected_booking);
// var_dump($selected_booking);

  @ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

$query_bill = "SELECT * FROM billing_information WHERE booking_id = ".$selected_booking[0];
    $result_bill = $db->query($query_bill);
    if(!$result_bill){
       echo '<script type="text/javascript">alert("Your query to retrieve billing information failed.");</script>';
       exit;
    }
    $row_bill = $result_bill->fetch_assoc();
    $billname = $row_bill['name'];
    $billemail = $row_bill['email'];
    $billaddress = $row_bill['addline1'];
    $billpostal = $row_bill['postalcode'];
    $billphone = $row_bill['phone'];
    $billorganization = $row_bill['organization'];

for($i=1; $i<$number_selected; $i++){
    $query_bill = "SELECT * FROM billing_information WHERE booking_id = ".$selected_booking[$i];
    $result_bill = $db->query($query_bill);
    if(!$result_bill){
       echo '<script type="text/javascript">alert("Your query to retrieve billing information failed.");</script>';
       exit;
    }
    $row_bill = $result_bill->fetch_assoc();
    if(($billname != $row_bill['name']) || ($billemail != $row_bill['email']) || ($billaddress != $row_bill['addline1'])
      || ($billpostal != $row_bill['postalcode']) || ($billphone != $row_bill['phone']) || ($billorganization != $row_bill['organization'])){
          echo '<script type="text/javascript">alert("Please select the bookings of single billing person.");</script>';
          $db->close();
          include 'orderHistory.php';
          exit;
      }
  }


?>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
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

<div class="content" style="min-height:450px"> 
<?php

echo "<h3 style='text-align:center; margin-top:50px; color:#0b78a1'>Number of bookings selected: ".$number_selected."</h3><hr>";
echo "<table border='1' style='width:100%; font-size:0.8em'>";
echo "<thead>
          <tr style='background-color:#c8c8c8;'>
            <th>User</th>
            <th>Requested By</th>
            <th>Facility Name</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Message</th>
            <th>Total Price</th>
            <th>Booking Date</th>
            </tr>
        </thead>";

  for($i=0; $i<$number_selected; $i++){
  	$query = "SELECT * FROM booking WHERE booking_id = $selected_booking[$i]";
	  $result = $db->query($query);
	  $row = $result->fetch_assoc();
    $query_bill = "SELECT * FROM billing_information WHERE booking_id = ".$row['booking_id'];
    $result_bill = $db->query($query_bill);
    if(!$result_bill){
       echo '<script type="text/javascript">alert("Your query to retrieve billing information failed.");</script>';
       exit;
    }
    $row_bill = $result_bill->fetch_assoc();
    $total = $total + $row['total_price'];
 	  echo "<tr>
          <td>".$row['username']."</td>
          <td><a href='billList.php?booking_id=".$row_bill['booking_id']."'>".$row_bill['name']."</a></td>
          <td>".$row['facility_name']."</td>
          <td>".$row['start_event']."</td>
          <td>".$row['end_event']."</td>
          <td>".$row['message']."</td>
          <td>$".$row['total_price']."</td>
          <td>".$row['booking_date']."</td>
          </tr>";
  }
  echo "<tr><td colspan='6'></td><td>$$total</td><td></td></tr></table>";
?>
<br>
<form id="emailForm" method="POST" action="processInvoice.php">
<textarea name="Content" id="Content" required class="emailtextarea">Dear user,

Thank you for your bookings in Centre for Optical Fibre Technology. The work request form of your bookings has been generated and attached.

Please proceed to NTU Shared Services (NSS) for the payment process.


Regards,
COFT Office

This is an automatically generated confirmation email. Please do not reply directly.
</textarea>
          <table style="margin-top:1px">
            <tr><td><input type="submit" name="Submit" id="Submit" value="Send Email" class="button" style="margin-left:auto; margin-right:auto; width: 84px; display:block"></td>
              </tr>
          </table>
          <input type="hidden" value="<?php echo $total; ?>" id="total_price" name="total_price">

         <?php foreach($selected_booking as $selected_booking_single)
          {
            echo '<input type="hidden" name="selected_booking[]" value="'. $selected_booking_single. '">';
          }
         ?>
</form>
</div>
<footer>Copyright &copy; 2014
        </footer>
</body>
</html>
<?php
}else{
  echo '<script type="text/javascript">alert("Please select at least one booking.");</script>';
  include 'orderHistory.php';
  exit;
}
?>

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