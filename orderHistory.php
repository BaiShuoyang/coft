<!DOCTYPE html><?php
session_start();

if (!isset($_SESSION['valid_user'])) //Redirect users to login page if the user has not logged in yet
{
  session_destroy();
  header("Location: index.php?login_fail=1");
  exit();
}else{
?>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<script type="text/javascript" src="js/picnet.table.filter.min.js"></script>
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>
<style type="text/css">

a:not(.back_btn) {
color: #0b78a1;
text-decoration: none;
}

a:not(.back_btn):hover {
color: #22b8f0;
}
th{
  padding: 3px;
}
</style>
<link rel="stylesheet" href="welcome.css">
<style type="text/css">
td{
  padding:2px;
  text-align: center;
}

</style>
<link href="footable.core.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="jquery.mmenu.css" />
<script type="text/javascript">
$(document).ready(function() {
    // run test on initial page load
    checkSize();

    // run test on resize of the window
    $(window).resize(checkSize);
});
</script>
<script src="js/footable.js" type="text/javascript"></script>
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
<form action="generateInvoice.php" id="invoiceForm" method="post">
<?php

  $username=$_SESSION['valid_user'];

  @ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  if($_SESSION['user_identity'] == "admin"){
    $query = "SELECT * FROM booking ORDER BY booking_date DESC";
  }else{
    $query = "select * from booking where username = '".$username."'";
  }

  $result = $db->query($query);
  $num_results = $result->num_rows;

  if($num_results==0){
     echo '<h4 style="text-align:center; margin-top:50px">No bookings found.<h4>';
  }else{

  echo "<h3 style='text-align:center; margin-top:50px; color:#0b78a1'>Number of bookings: ".$num_results."</h3><hr>";
  echo "<table border='1' style='width:100%; font-size:0.8em;' class='footable'>
        <thead>
          <tr style='background-color:#c8c8c8;'>
            <th filter-type='ddl' data-toggle='true'>User</th>
            <th filter-type='ddl'>Billed To</th>
            <th filter-type='ddl'>Facility Name</th>
            <th data-hide='phone'>Start Time</th>
            <th data-hide='phone'>End Time</th>
            <th data-hide='phone'>Message</th>
            <th data-hide='phone'>Total Price</th>
            <th filter-type='ddl'>Booking Date</th>
            <th>Select</th></tr>
        </thead>";
  for ($i=0; $i<$num_results; $i++){ 
  $row = $result->fetch_assoc();
  $query_bill = "SELECT * FROM billing_information WHERE booking_id = ".$row['booking_id'];
  $result_bill = $db->query($query_bill);
  if(!$result_bill){
     echo '<script type="text/javascript">alert("Your query to retrieve billing information failed.");</script>';
     exit;
  }
  $row_bill = $result_bill->fetch_assoc();

  echo "<tr>
          <td>".$row['username']."</td>
          <td><a href='billList.php?booking_id=".$row['booking_id']."'>".$row_bill['name']."</a></td>
          <td>".$row['facility_name']."</td>
          <td>".$row['start_event']."</td>
          <td>".$row['end_event']."</td>
          <td>".$row['message']."</td>
          <td>$".$row['total_price']."</td>
          <td>".$row['booking_date']."</td>";
    if($row['billed']==0){
      echo  "<td><input type='checkbox' name='selected_booking[]' value='".$row['booking_id']."'></td>";
    }else{
      echo  "<td>Billed</td>";
    }
    echo "</tr>";
  }
  echo "</table>";
}

    if(isset($_GET['emailFail'])){
      echo '<script type="text/javascript">alert("Email sending failed for work request form generation. Please try again.");</script>';
    }
?>

<script type="text/javascript">
$(document).ready(function() {
  $('#orderTable').tableFilter();
});

</script>
<?php 
if($_SESSION['user_identity'] == "admin"){?>
<input type="submit" value="Generate Work Request Form" class="button" style="margin-left: auto;margin-right: auto;width:190px;display:block; margin-top:10px;">
<?php
  }
?>
</form>
<br>
</div>
        <footer>Copyright &copy; 2014
        </footer>
</body>
<script type="text/javascript">
$('.footable').footable({ 
  calculateWidthOverride: function() { 
    return {width: $(window).width()}; 
  }, 
  breakpoints: { phone: 650 },
  addRowToggle: false
}); 

$(function () {
    $('.footable').footable();
  });

</script>
</html>
<?php }?>

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