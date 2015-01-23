<?php
session_start();

if (!isset($_SESSION['valid_user'])) //Redirect users to login page if the user has not logged in yet
{
  session_destroy();
  header("Location: index.php?login_fail=1");
  exit();
}else{
?>
<!DOCTYPE html>
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
td{
  padding:3px;
}
th{
  padding: 1px;
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

  $username=$_SESSION['valid_user'];

  @ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  if($_SESSION['user_identity'] == "admin"){
    $query = "select * from booking";
  }else{
    $query = "select * from booking where username = '".$username."'";
  }

  $result = $db->query($query);
  $num_results = $result->num_rows;

  if($num_results==0){
     echo '<h4 style="text-align:center; margin-top:50px">No bookings found.<h4>';
  }else{

  echo "<h3 style='text-align:center; margin-top:50px;'>Number of bookings: ".$num_results."</h3>";
  echo "<table border='1' style='width:90%; font-size:0.9em' >
      <tr style='background-color:#c8c8c8;'><th>Booking Number</th><th>Facility Name</th><th>Booking Start Time</th><th>Booking End Time</th><th>Message</th><th>Total Price</th><th>Booking Date</th></tr>";
  for ($i=0; $i<$num_results; $i++){ 
  $row = $result->fetch_assoc();
  echo "<tr><td>".$row['booking_id']."</td><td>".$row['facility_name']."</td><td>".$row['start_event']."</td><td>".$row['end_event']."</td><td>".$row['message']."</td><td>$".$row['total_price']."</td><td>".$row['booking_date']."</td></tr>";
  }
  echo "</table>";
}
?>

</div>
        <footer>Copyright &copy; 2014
        </footer>
</body>
</html>
<?php }?>