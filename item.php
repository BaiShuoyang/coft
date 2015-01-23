<!DOCTYPE html><?php
session_start();

if(!isset($_SESSION['valid_user'])){
  header("Location: index.php?redirect=1"); 
  exit;
}

if($_SESSION['user_identity']=="external_nonapproved"){
  header("Location: postRegister.php"); 
  exit;
}

if(isset($_GET['itemname'])){
 $itemname=$_GET['itemname'];
}

 ?>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<script src="js/jquery-1.11.0.min.js"></script>
<script src="js/lightbox.min.js"></script>
<script type="text/javascript" src="js/jquery-ajax.js"></script>

<script type="text/javascript">

function checkBook(){

  var startNode = document.getElementById("datetimepicker1");
  var endNode = document.getElementById("datetimepicker2");
  var start = startNode.value;
  var end = endNode.value;

  if((start == '')||(end == '')){
    alert("Please select the time slots.");
    return false;
  }

  if(start>=end){
    alert("End time should be after start time.");
    return false;
  }

  if(start.substr(0,10) != end.substr(0,10)){
    alert("Start time and end time should be within the same day.");
    return false;
  }

  $.ajax({ 
  type: "POST", 
  url: "checkBook.php", 
  data: "start="+ start +"&end="+ end + "&itemname=<?php echo $itemname ?>", 
    success: function(msg){ 
      if(msg == 'Conflict')
        {   //Conflict with the time interval
            alert("The time slot has already been booked by someone else. Please try other slots.");
            return false;
        } 
      if(msg == 'Ok'){
          // There is no conflict for the booking time interval, enable the button.
          document.getElementById("checkButton").style.display = 'none';
          document.getElementById("bookButton").style.display = '';
          document.getElementById("bookButton").disabled = false;
      	  document.getElementById("datetimepicker1").disabled = true;
      	  document.getElementById("datetimepicker2").disabled = true;
          return true;
        }
      if(msg == 'Empty'){
        // If the user has not finished inserting
        return false;
      }
      if(msg == 'NotAllowed')
        {   //The days of the week are not allowed
            alert("The days of the week you booked are not valid.");
            return false;
        } 
      if(msg == 'BeyondOneMonth')
        {  //The booking is more than one month later
            alert("You can only make bookings within the following one month.");
            return false;
        }
    }
  });

}

function enableInputField() {
    'use strict';
		document.getElementById("datetimepicker1").disabled = false;
        document.getElementById("datetimepicker2").disabled = false;
}

</script>

<link rel='stylesheet' href='fullcalendar/fullcalendar.css' />
<style type="text/css">
select {
width:200px;
margin-top:8px;
font-size: 17px;
background-color: #fff;
border: 1px solid #ccc;
box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
border-radius: 4px;
color:#000;
-webkit-appearance:none;
outline:none;
line-height: 20px;
color: #555;
padding: 4px 6px;
margin-left: 50px;
}

img{ 
  border: solid 1px transparent; 
  -moz-box-shadow: 1px 1px 5px transparent; 
  -webkit-box-shadow: 1px 1px 5px transparent; 
  box-shadow: 1px 1px 5px transparent; 
}

a:hover img:not(.logo) { 
  border: solid 1px #CCC; 
  -moz-box-shadow: 1px 1px 5px #999; 
  -webkit-box-shadow: 1px 1px 5px #999; 
  box-shadow: 1px 1px 5px #999; 
}
</style>
<script type="text/javascript" src='fullcalendar/lib/jquery.min.js'></script>
<script type="text/javascript" src='fullcalendar/lib/moment.min.js'></script>
<script type="text/javascript" src='fullcalendar/fullcalendar.js'></script>
<script type="text/javascript">
var jQuery_calendar = $.noConflict(true);
</script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>

<script type="text/javascript">
    
  $(document).ready(function() {

    // page is now ready, initialize the calendar...

    jQuery_calendar('#calendar').fullCalendar({
         weekends: true,// put your options and callbacks here
         theme: false,
         header:{
              left:   'prev,next, today',
              center: 'title',
              right:  'agendaDay,agendaWeek'
          },

          minTime: "08:00:00",
          maxTime: "18:00:00",

         hiddenDays: hideDays,
         handleWindowResize: true, //Reserve for Responsive Web Design
        
        events: "getEvent.php?itemname=<?php echo $itemname;?>",
        eventRender: function(event, element) { 
            element.find('.fc-title').append("<br>" + event.description); 
        },
        defaultView: 'agendaWeek',
        contentHeight: 600,
  
  });
  
 });

</script>
<link rel="stylesheet" href="welcome.css">
</head>
<body onload="auto()">
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
        <li><a href="results.php">Facility List</a></li>
        <li class="slash">/</li>
        <li><a class="active">Select Time</a></li>
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
        <form id="loginForm" method="post" action="processLogin.php?source=item.php?itemname=<?php echo $itemname;?>">
          <label>Username</label>
          <input type="text" name="username" id="username"/>
          <br />

          <label>Password</label>
          <input type="password" name="loginPwd" id="loginPwd"/>
          <br />

          <table style="margin-top:20px">
            <tr><td><input type="submit" name="Submit" id="Submit" value="Log in" class="button"></td>
              <td><a href="#" class="back_btn"><i class="fa fa-angle-double-left"></i> Cancel</a></td>
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
   
  if (!$itemname) {
     echo '<script type="text/javascript">alert("Pass by GET failed.");</script>';
     exit;
  }

  if (!get_magic_quotes_gpc()){ //default is add slashes to get, post, cookies
    //$searchtype = addslashes($searchtype);
    //$searchterm = addslashes($searchterm);
    $itemname = addslashes($itemname);
  }

  @ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query = "select * from item where facility_name = '".$itemname."'";

  $result = $db->query($query);

  //$num_results = $result->num_rows;
  $row = $result->fetch_assoc();
  
?>

<script type="text/javascript">

var days = "<?php echo $row['available_day']; ?>";

var hideDays = new Array();

if(days.indexOf("Monday")==-1){
     hideDays[hideDays.length] = 1;
}
if(days.indexOf("Tuesday")==-1){
     hideDays[hideDays.length] = 2;
}
if(days.indexOf("Wednesday")==-1){
     hideDays[hideDays.length] = 3;
}
if(days.indexOf("Thursday")==-1){
     hideDays[hideDays.length] = 4;
}
if(days.indexOf("Friday")==-1){
     hideDays[hideDays.length] = 5;
}
if(days.indexOf("Saturday")==-1){
     hideDays[hideDays.length] = 6;
}
if(days.indexOf("Sunday")==-1){
     hideDays[hideDays.length] = 0;
}

</script>

<div class="content" style="min-height:1200px;"> 
  <div class="itemLeft" style="margin-top:50px;">

  <script type="text/javascript">

    var x=0;

    function rotate(num){
    fs=document.ff.slide;
    x=num%fs.length;
    if(x<0) x=fs.length-1;
    document.images.show.src=fs.options[x].value;
    fs.selectedIndex=x;}

    function auto() {
    rotate(++x);setTimeout("auto()", 6000);}
    
  </script>
  <div id="slider">
  <form name="ff" style="margin:0">
    <table>
    <tr><td align="left" ><img src="<?php echo $row['photo1'] ?>" name="show" width="360" height="270" alt="image" align="left"></td></tr>
    <tr style="display:none;"><td align="center" style="border:1px solid; border-color:#C0C0C0">
    <select name="slide" onChange="rotate(this.selectedIndex);">
    <?php if($row['photo1']!=''){?><option value="<?php echo $row['photo1'] ?>" class="img-shadow">Description for photo-1 </option><?php }?>
    <?php if($row['photo2']!=''){?><option value="<?php echo $row['photo2'] ?>" class="img-shadow">Description for photo-2 </option><?php }?>
    <?php if($row['photo3']!=''){?><option value="<?php echo $row['photo3'] ?>" class="img-shadow">Description for photo-3 </option><?php }?>
    <?php if($row['photo4']!=''){?><option value="<?php echo $row['photo4'] ?>" class="img-shadow">Description for photo-4 </option><?php }?>
    </select>
    </td></tr>
    </table>
  </form>
  </div>

  <div class="itemLeftBottom">
    <?php if($row['photo1']!=''){?><a href="<?php echo $row['photo1'] ?>" data-lightbox="image-1" data-title=""><img src="<?php echo $row['photo1'] ?>" width="80" height="60" alt="image1"></a><?php }?>
    <?php if($row['photo2']!=''){?><a href="<?php echo $row['photo2'] ?>" data-lightbox="image-2" data-title=""><img src="<?php echo $row['photo2'] ?>" width="80" height="60" alt="image2"></a><?php }?>
    <?php if($row['photo3']!=''){?><a href="<?php echo $row['photo3'] ?>" data-lightbox="image-3" data-title=""><img src="<?php echo $row['photo3'] ?>" width="80" height="60" alt="image3"></a><?php }?>
    <?php if($row['photo4']!=''){?><a href="<?php echo $row['photo4'] ?>" data-lightbox="image-4" data-title=""><img src="<?php echo $row['photo4'] ?>" width="80" height="60" alt="image4"></a><?php }?>
  </div>
  <form id="bookForm" onsubmit="return enableInputField()" style="margin-top:40px" method="POST" action="confirmation.php">
  <fieldset>
    <legend>Booking Detail</legend>
    <table cellspacing="15" style="width:60%">
      <tr><td style="text-align: left">Start:</td>
          <td><input required name="start" id="datetimepicker1" type="text" class="box"></td>
      </tr>
      <tr><td style="text-align: left">End:</td>
          <td><input required name="end" id="datetimepicker2" type="text" class="box"></td>
      </tr>
      <tr><td style="text-align: left">Message:</td>
          <td><input type="text" name="message" id="message" class="box"></td>
      </tr>
      <?php if($row['isFabricationFacility']==1){?>
      <tr><td style="text-align: left">Number of fibres:</td>
          <td><input type="text" name="number" id="number" class="box" required></td>
      </tr>
      <tr><td style="text-align: left">Price per fibre:</td>
          <td><input type="text" name="price" id="price" class="box" required></td>
      </tr>
      <tr><td style="text-align: left">User Identity:</td>
          <td><select name="user_identity" id="user_identity" required>
            <option value="internal">Internal</option>
            <option value="external">External</option>
          </select>
          </td>
      </tr>
      <?php }?>
      <tr><td></td><td>
          <input type="button" value="Check it" class="back_btn" id="checkButton" onclick="return checkBook()" style="vertical-align: middle; margin-left:50px">
          <input type="submit" disabled value="Book it" class="button" id="bookButton" style="vertical-align: middle; margin-left:50px; display:none">
      </td></tr>
   </table>
  </fieldset>
  <input type="hidden" name="itemname" value="<?php echo $itemname?>">
</form>
</div>


<div class="itemRight">

<div class="messageItem">
  <table style="line-height:35px;">
    <tr><td style="color:#000;text-align:left;font-weight:bold"><?php echo $row['facility_name'] ?></td></tr>
    <tr><td style="color:#000;text-align:justify"><?php echo $row['description'] ?></td></tr>
  </table>
</div>

  <div id='calendar'></div>

</div>
</div>
        <footer>Copyright &copy; 2014
        </footer>
</div>
</body>

<link rel="stylesheet" type="text/css" href="timepicker/jquery.datetimepicker.css" >
<script src="timepicker/jquery.js"></script>
<script src="timepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
var jQuery_picker = $.noConflict(true);
</script>

<script type="text/javascript">
jQuery_picker('#datetimepicker1').datetimepicker({
 datepicker:true,
 <?php if($row['isCleanRoomFacility'] == 1){?>
  allowTimes:[
  '08:00'
  ],
 <?php }else{?>
 allowTimes:[
  '08:00',
  '13:00'
  ],
 <?php }?>
});

jQuery_picker('#datetimepicker2').datetimepicker({
 datepicker:true,
 <?php if($row['isCleanRoomFacility'] == 1){?> //Booking internal for clean room facilities is one day instead of half day
  allowTimes:[
  '18:00'
  ],
 <?php }else{?>
 allowTimes:[
  '13:00',
  '18:00'
  ],
  <?php }?>
});
</script>
</html>