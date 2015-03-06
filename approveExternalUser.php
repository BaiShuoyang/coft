<!DOCTYPE html><?php
//approveExternalUser.php
session_start();

?>
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
<style type="text/css">
td{
  padding:2px;
  text-align: center;
}
</style>
<link href="footable.core.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>
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

  $query_user = "SELECT * FROM normal_user WHERE approved = 0";

  $result_user = $db->query($query_user);

  $num_results_user = $result_user->num_rows;

?>
  

<div class="content" style="min-height:450px;"> 
   <h3 style="text-align:center;color:#0b78a1;margin-top:50px;">External User Registration</h3><hr>
   <table border="1" style="width:100%; font-size: 0.9em;" class="footable">
	<thead style="background-color:#c8c8c8;">
	<tr>
		<th data-toggle="true">User Name</th>
		<th>Email</th>
		<th data-hide="phone">Address</th>
		<th data-hide="phone">Postal Code</th>
		<th data-hide="phone">Phone</th>
		<th>Faculty</th>
    <th>Facility Access</th>
		<th>Action</th>
	</tr>
	</thead>
	<tbody>
   <?php
	$total = 0;
		for ($i = 0; $i < $num_results_user; $i++) { 

  		$row = $result_user->fetch_assoc();

      $facility_array = explode(",",$row['facility_access']);
      $number_array = sizeof($facility_array);

		echo '<tr style="font-weight:bold;">';
		echo '<td>'.$row['username'].'</td>';
		echo '<td>'.$row['email'].'</td>';
		echo '<td>'.$row['addline1'].' '.$row['addline2'].'</td>';
		echo '<td>'.$row['postalcode'].'</td>';
		echo '<td>'.$row['phone'].'</td>';
		echo '<td>'.$row['faculty'].'</td>';
    ?>
    <form name="accessForm" action="processApproveUser.php?approveEmail=<?php echo $row['email']?>" method="post">
    <td><select name="Approved_access[]" id="Approved_access" required multiple size="5" class="box" style="height:auto; margin:0; font-size:85%; ">
            <?php for ($i=0; $i < $number_array; $i++) {
              ?>
            <option value="<?php echo $facility_array[$i]?>"><?php echo $facility_array[$i];?></option>
           <?php }?>
            </select>
    </td>
    <?php
    echo '<td><a onclick="document.accessForm.submit()" style="cursor:pointer; margin:0;">Approve</a><br><br>
				  <a href=processApproveUser.php?denyEmail='.$row['email'].'>Deny</a></td>';		
		echo '</tr>';

		unset($row);
    
    }
    ?>
    </form>
    
    <?php

    $query_user2 = "SELECT * FROM normal_user WHERE approved = 1";

    $result_user2 = $db->query($query_user2);

    $num_results_user2 = $result_user2->num_rows;

    for ($k = 0; $k < $num_results_user2; $k++) { 

      $row2 = $result_user2->fetch_assoc();

      $facility_array2 = explode(",",$row2['facility_access']);
      $number_array2 = sizeof($facility_array2);

    echo '<tr>';
    echo '<td>'.$row2['username'].'</td>';
    echo '<td>'.$row2['email'].'</td>';
    echo '<td>'.$row2['addline1'].' '.$row['addline2'].'</td>';
    echo '<td>'.$row2['postalcode'].'</td>';
    echo '<td>'.$row2['phone'].'</td>';
    echo '<td>'.$row2['faculty'].'</td>';
    ?>
    <td><select multiple size="5" class="box" style="height:auto; margin:0; font-size:85%">
            <?php 
            if($row2['facility_access']!=""){
              for ($i=0; $i < $number_array2; $i++) {
              ?>
            <option value="<?php echo $facility_array2[$i]?>"><?php echo $facility_array2[$i];?></option>
           <?php }
            }?>
        </select>
    </td>
    <?php
    echo '<td><a href=editUserAccess.php?editId='.$row2['user_id'].'>Edit Access</a></td>';    
    echo '</tr>';

		}

        $result_user->free();
        $db->close();
	?>
	</tbody>
	</table>
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

</div><footer>Copyright &copy; 2014
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