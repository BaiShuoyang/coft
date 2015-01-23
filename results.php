<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<script src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<style type="text/css">
.notification{
  -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .7);
  -webkit-border-radius: 2px;
  -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .7);
  -moz-border-radius: 2px;
  box-shadow: 0 1px 1px rgba(0, 0, 0, .7);
  border-radius: 2px;
  background-image: -webkit-linear-gradient(#fa3c45, #dc0d17); /* Safari/Chrome */
  background-image: -moz-linear-gradient(#fa3c45, #dc0d17); /* Firefox */
  background-image: linear-gradient(#fa3c45, #dc0d17);
  background-image: -ms-linear-gradient(#fa3c45, #dc0d17); /* IE10- */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fa3c45', endColorstr='#dc0d17',GradientType=0 ); /* IE6-9 */
  min-height: 13px; 
  padding:1px 3px;
  position:relative;
  left:40px;
  top:-23px;
  color:#fff;
  display: inline-block;
  line-height: normal;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, .4);
  -webkit-background-clip: padding-box;
  -moz-background-clip: padding-box;
  background-clip: padding-box;
  font-size: 14px;
  white-space-collapsing:discard;
}
</style>
<link rel="stylesheet" href="welcome.css">
<style type="text/css">
td{
  text-align: center;
} 
</style>

<script type="text/javascript">

function show_editable(){
  document.getElementById("edit_button").style.display = 'none';
  document.getElementById("text").style.display = 'none';
  document.getElementById("Announcement").style.display = '';
  document.getElementById("submit_button").style.display = '';
  document.getElementById("submit_button").disabled = false;
}


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
  <div class="cssmenu"><ul>
       <span id="nav_first"><li><a id = "modal_trigger" href="#modal">Login</a></li></span>
         <span id="nav_hide" style="display:none"></span>
           <li><a href="results.php">Facility List</a></li>
           <li><a href="orderHistory.php">Order History</a></li>
       </ul>
  </div>
  <div id="crumb">
       <ul>
        <li><a href="http://www.coft.eee.ntu.edu.sg/aboutUs/Pages/CentreFacilities.aspx">COFT</a></li>
        <li class="slash">/</li>
        <li><a class="active">Facility List</a></li>
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



<?php

     // reserve for input from search box
  //$searchtype=$_GET['searchtype'];
  //$searchterm=trim($_GET['searchterm']);

  // $searchbrand=$_GET['brand'];

  // if (!get_magic_quotes_gpc()){ //default is add slashes to get, post, cookies
  //   $searchtype = addslashes($searchtype);
  //   $searchterm = addslashes($searchterm);
  //   $searchbrand = addslashes($searchbrand);
  // }

  @ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query = "select * from item";

  $result = $db->query($query);

  $num_results = $result->num_rows;

  //For displaying the number of external users waiting to be approved
  $query_user = "select * from external_user where approved = 0"; 
  //Approve = 0 means administrator has not approve or deny yet, approve = 1 means the user is approved, approve = -1 means the user is denied
  $result_user = $db->query($query_user);
  $num_results_user = $result_user->num_rows;

  $query_booking = "select * from booking where approved = 0";
  $result_booking = $db->query($query_booking);
  $num_results_booking = $result_booking->num_rows;

  $query_announcement = "SELECT * FROM announcement ORDER BY announcement_id desc"; 
  $result_announcement = $db->query($query_announcement);
  $row_announcement = $result_announcement->fetch_assoc();

  ?>

<div class="content"> 

  <div style="width: 95%; display:table">

    <?php
    if(isset($_SESSION['user_identity'])){
    if($_SESSION['user_identity'] == "admin"){
    ?>
       <div style="float: right; padding: 5px; margin: 5px; left: 20px;background-color: transparent; margin-top:10px">
        <a href="newFacility.php"><img src="Image/Icon/add.png" alt="Add" height="32" width="32"></a>&nbsp;&nbsp;&nbsp;
        <a href="editFacility.php"><img src="Image/Icon/edit.png" alt="Edit" height="32" width="32"></a>&nbsp;&nbsp;&nbsp;
        <a href="deleteFacility.php"><img src="Image/Icon/delete.png" alt="Delete" height="32" width="32"></a>
        <a href="approveExternalUser.php" style="text-decoration:none;"><span class="notification"><?php echo $num_results_user;?></span><img src="Image/Icon/user.png" alt="User" height="32" width="32"></a>
        <a href="approveBooking.php" style="text-decoration:none;"><span class="notification"><?php echo $num_results_booking;?></span><img src="Image/Icon/message.png" alt="Message" height="32" width="32"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </div>
      <div style="height:40px"></div>
    <?php }} ?>

    <div class="announcement" style="margin-top:20px; width:50%;">
    <form id="announceForm" action="processSaveAnnounce.php" method="post">
      <p style="font-weight:bold; margin-top:5px; color:#003478;">Annoucement:</p>
      <p style="margin-top:5px; color:#003478;" id="text"><?php echo $row_announcement['text']; ?></p>
      <textarea rows="2" cols="53" required name="Announcement" id="Announcement" style="display:none; resize: none;"><?php echo $row_announcement['text'];?></textarea>
      <?php
    if(isset($_SESSION['user_identity'])){
    if($_SESSION['user_identity'] == "admin"){
    ?>
      <br>
      <br>
      <input type="button" class="button" value="Edit" id="edit_button" onclick="show_editable()">
      <input type="submit" disabled class="button" value="Save" id="submit_button" style="display:none">
      <?php }} ?>
    </form>
    </div>
    <table style="border-collapse:separate; border-spacing:1.5em;">

    <?php

      for ($i=0; $i <$num_results; $i++) {

         $row = $result->fetch_assoc();

         $isFab = $row['isFabricationFacility'];
         $isUnpublish = $row['isUnpublish'];

    ?>
      <tr style="height: 190px; overflow:hidden">
        <td height="190px">
          <div style="text-decoration:none; color:#000">
            <img class="facility_image" src="<?php echo $row['photo1'] ?>" width="200" height="150" alt="Thumbnail image" align="right">
          </div>
        </td>
        <td width="80%" height="160px">
          <div style="text-decoration:none; color:#000; text-align: justify">
            <p style="font-weight:bold; height=20%"><?php echo stripslashes($row['facility_name']) ?></p>
            <?php if($isUnpublish==1){?>
            <p style="height=75%; overflow: hidden; color: #ff0000; font-weight: bold"><?php echo stripslashes($row['announcement']) ?></p>
            <?php }else{?>
            <p style="height=75%; overflow: hidden;"><?php echo stripslashes($row['description']) ?></p>
            <?php }?>
            <?php if(($isFab==1) && ($isUnpublish!=1)){?><p style="height=5%">For bookings, please send emails to <a href="mailto:epshum@ntu.edu.sg">us</a>.</p>
            <?php }?>
          </div>
        </td>
        <td width="190px" style="text-align:left">
          <?php if(($isFab!=1) || ($_SESSION['user_identity']=="internal") || ($_SESSION['user_identity']=="admin")){  
              //External users cannot see the check availability button of fab service
            if(($row['start_publish'] <= date("Y-m-d")) && (date("Y-m-d") <= $row['end_publish'])){
              ?><a href="item.php?itemname=<?php echo stripslashes($row['facility_name']) ?>" style="text-decoration:none;text-align:center; font-weight:bold" class="check_btn">Check Availability</a>
          <?php }

            }
          ?>
        </td>
     </tr>

    <?php 

        }
    ?>
    </table>
  </div>

</div>

        <footer>Copyright &copy; 2014
        </footer>
</div>

  <?php
      
      $result->free();
      $db->close();
    ?>
</body>
</html>