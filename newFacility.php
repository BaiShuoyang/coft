<?php
//newFacility.php
session_start();
?><!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<style>
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

a:not(.back_btn) {
color: #0b78a1;
text-decoration: none;
}

a:not(.back_btn):hover {
color: #22b8f0;
}

</style>
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
<script type="text/javascript">
// Script - login.js

// Function called when the form is submitted.
// Function validates data and returns a Boolean value.
function validateForm() {
    'use strict';
    
    // Get references to the form elements:
    var start = document.getElementById("Start");
    var end = document.getElementById("End");
    var photo = document.getElementById("Photo");
    var quantity = document.getElementById("Quantity");
    var price = document.getElementById("Price");
    var charge_internal = document.getElementById("Charge_internal");
    var charge_external = document.getElementById("Charge_external");
    var time = document.getElementById("Time");

    //Check if the end publishing time is before starting publishing time
    if(start.value > end.value){
      alert("End time should be after start time.");
      return false;
    }


    //Check if the extension of uploaded files is proper
    if(isset(photo.value)){
      if(!/(\.bmp|\.gif|\.jpg|\.jpeg|\.png)$/i.test(photo.value)) {
        alert("Invalid image file type.");
        // photo.form.reset();
        // photo.focus();
        return false;
      }

      //Check if the number of uploaded files exceeds four
      if(photo.files.length > 4){
        alert("You can only upload up to four images.");
        return false;
      }
   }


    //Check if quantity is digital number
    var pos1 = quantity.value.search(/^[\d]{1,}$/);

    if(pos1!=0){
      alert("The quantity you typed is not in proper format.");
      return false;
    }

    //Check if price is digital number
    var pos2 = price.value.search(/^[0-9]+\.?[0-9]{0,}$/);

    if(pos2!=0){
      alert("The price you typed is not in proper format.");
      return false;
    }

    //Check if charge is digital number
    var pos3 = charge_internal.value.search(/^[\d]{1,}$/);

    if(pos3!=0){
      alert("The charge percentage for internal users you typed is not in proper format.");
      return false;
    }

    //Check if charge is digital number
    var pos4 = charge_external.value.search(/^[\d]{1,}$/);

    if(pos4!=0){
      alert("The charge percentage for external users you typed is not in proper format.");
      return false;
    }

    //Check if time is digital number
    var pos5 = time.value.search(/^[\d]{1,}$/);

    if(pos5!=0){
      alert("The time unit you typed is not in proper format.");
      return false;
    }
    
} // End of validateForm() function.


// Function called when the window has been loaded.
// Function needs to add an event listener to the form.
function init() {
    'use strict';
    
    // Confirm that document.getElementById() can be used:
    if (document && document.getElementById) {
        var regForm = document.getElementById("facilityForm");
        regForm.onsubmit = validateForm;                
    }

} // End of init() function.

// Assign an event listener to the window's load event:
window.onload = init;

function togglePriceClean(){
        if(document.getElementById("IsClean").checked){
            document.getElementById("IsFab").disabled = true;
            document.getElementById("priceTag").innerHTML = "Fixed Price Per Day (in SGD):"
            document.getElementById("Price").readOnly = true;
            document.getElementById("Price").style.backgroundColor = "#eaeaea";
            document.getElementById("Price").value = "100";
            document.getElementById("Charge_internal").readOnly = true;
            document.getElementById("Charge_internal").style.backgroundColor = "#eaeaea";
            document.getElementById("Charge_internal").value = "100";
            document.getElementById("Charge_external").readOnly = true;
            document.getElementById("Charge_external").style.backgroundColor = "#eaeaea";
            document.getElementById("Charge_external").value = "100";
        }else{
            document.getElementById("IsFab").disabled = false;
            document.getElementById("priceTag").innerHTML = "Item Price (in SGD):"
            document.getElementById("Price").readOnly = false;
            document.getElementById("Price").style.backgroundColor = "#fff";
            document.getElementById("Price").value = "";
            document.getElementById("Charge_internal").readOnly = false;
            document.getElementById("Charge_internal").style.backgroundColor = "#fff";
            document.getElementById("Charge_internal").value = "";
            document.getElementById("Charge_external").readOnly = false;
            document.getElementById("Charge_external").style.backgroundColor = "#fff";
            document.getElementById("Charge_external").value = "";
          }
}

function togglePriceFab(){
        if(document.getElementById("IsFab").checked){
            document.getElementById("IsClean").disabled = true;
            document.getElementById("priceTag").innerHTML = "Price needs to be manually input by internal users when booking";
            document.getElementById("Price").readOnly = true;
            document.getElementById("Price").style.backgroundColor = "#eaeaea";
            document.getElementById("Price").value = "0";
            document.getElementById("Charge_internal").readOnly = true;
            document.getElementById("Charge_internal").style.backgroundColor = "#eaeaea";
            document.getElementById("Charge_internal").value = "100";
            document.getElementById("Charge_external").readOnly = true;
            document.getElementById("Charge_external").style.backgroundColor = "#eaeaea";
            document.getElementById("Charge_external").value = "100";
        }else{
            document.getElementById("IsClean").disabled = false;
            document.getElementById("priceTag").innerHTML = "Item Price (in SGD):"
            document.getElementById("Price").readOnly = false;
            document.getElementById("Price").style.backgroundColor = "#fff";
            document.getElementById("Price").value = "";
            document.getElementById("Charge_internal").readOnly = false;
            document.getElementById("Charge_internal").style.backgroundColor = "#fff";
            document.getElementById("Charge_internal").value = "";
            document.getElementById("Charge_external").readOnly = false;
            document.getElementById("Charge_external").style.backgroundColor = "#fff";
            document.getElementById("Charge_external").value = "";
          }
}

function toggleUnpublish(){

        if(document.getElementById("IsUnpublish").checked){
            document.getElementById("Start").readOnly = true;
            document.getElementById("Start").style.backgroundColor = "#eaeaea";
            document.getElementById("Start").value = "2015-01-01"; //Set start and end publishing time to past dates
            document.getElementById("End").readOnly = true;
            document.getElementById("End").style.backgroundColor = "#eaeaea";
            document.getElementById("End").value = "2015-01-01";    
            document.getElementById("Announcement_row").style.display = '';
        }else{
            document.getElementById("Start").readOnly = false;
            document.getElementById("Start").style.backgroundColor = "#fff";
            document.getElementById("End").readOnly = false;
            document.getElementById("End").style.backgroundColor = "#fff";
            document.getElementById("Announcement_row").style.display = 'none';
          }
}
</script>
<link rel="stylesheet" href="welcome.css">
<style type="text/css">
.box{
  margin-left: 0px;
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


<?php 
//Handle query from editFacility.php

if(isset($_POST['editFacility'])){

  $itemname = $_POST['editFacility'];

  @ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query = "select * from item where facility_name = '".$itemname."'";

  $result = $db->query($query);

  $num_results = $result->num_rows;

  if($num_results!=1){
     echo '<script type="text/javascript">alert("Error: No item or more than one item has the same name in database.");</script>';
     exit;
  }

  $row = $result->fetch_assoc();

  $db->close();
}

?>



<div class="content"> 
   <h3 style="text-align:center;color:#0b78a1; margin-top:50px;">Facility Registration</h3><hr>
  <form id="facilityForm" action="processFacilityRegister.php" method="post" style="text-align: center;" enctype="multipart/form-data" accept-charset="UTF-8">
  	<table cellspacing="20"> 
		<tr><td class="tag">Facility Name:</td>
		<td><input type="text" name="Itemname" id="Itemname" size = "30" required class="box"  <?php if(isset($_POST['editFacility'])){?> value = "<?php echo $row['facility_name']?>" <?php }?>></td></tr>
		<tr><td class="tag">Alias Name:<br><span style="font-size:80%">*Max size of alias name is 10</span></td>
    <td><input type="text" name="Alias" id="Alias" size = "30" required maxlength="10" class="box"  <?php if(isset($_POST['editFacility'])){?> value = "<?php echo $row['alias']?>" <?php }?>></td></tr>
    <tr><td class="tag">Description:</td>
		<td><textarea rows="4" cols="40" name="Description" id="Description" required><?php if(isset($_POST['editFacility'])){ echo $row['description']; }?></textarea></td></tr>
		<tr><td class="tag">Is Clean Room Facility:</td>
    <td><input type="checkbox" name="IsClean" id="IsClean" onclick="togglePriceClean()" value="1" <?php if(isset($_POST['editFacility'])){ if($row['isCleanRoomFacility']==1) echo "checked"; }?>></td></tr>  
    <tr><td class="tag">Is Fabrication Facility/Services:</td>
    <td><input type="checkbox" name="IsFab" id="IsFab" onclick="togglePriceFab()" value="1" <?php if(isset($_POST['editFacility'])){ if($row['isFabricationFacility']==1) echo "checked"; }?>></td></tr>  
    <tr><td class="tag">Quantity:</td>
		<td><input type="text" name="Quantity" id="Quantity" size = "30" required class="box" <?php if(isset($_POST['editFacility'])){?> value = "<?php echo $row['quantity']?>" <?php }?>></td></tr>
		<tr><td class="tag" id="priceTag">Item Price (in SGD):</td>
    <td><input type="text" name="Price" id="Price" size = "30" required class="box" <?php if(isset($_POST['editFacility'])){?> value = "<?php echo $row['price']?>" <?php }?>></td></tr>
    <tr><td class="tag">Charge for internal users per day (in %):</td>
		<td><input type="text" name="Charge_internal" id="Charge_internal" size = "30" required class="box" <?php if(isset($_POST['editFacility'])){?> value = "<?php echo 100*$row['charge_internal']?>" <?php }?>></td></tr>
		<tr><td class="tag">Charge for external users per day (in %):</td>
    <td><input type="text" name="Charge_external" id="Charge_external" size = "30" required class="box" <?php if(isset($_POST['editFacility'])){?> value = "<?php echo 100*$row['charge_external']?>" <?php }?>></td></tr>
		<tr><td class="tag">Available Day:</td>
		<td> <label><input type="checkbox" name="available_day[]" id="Mon" value="Monday" checked> Monday</label><br>
   			 <label><input type="checkbox" name="available_day[]" id="Tue" value="Tuesday" checked> Tuesday</label><br>
   			 <label><input type="checkbox" name="available_day[]" id="Wed" value="Wednesday" checked> Wednesday</label><br>
   			 <label><input type="checkbox" name="available_day[]" id="Thu" value="Thursday" checked> Thursday</label><br>
   			 <label><input type="checkbox" name="available_day[]" id="Fri" value="Friday" checked> Friday</label><br>
   			 <label><input type="checkbox" name="available_day[]" id="Sat" value="Saturday" checked> Saturday</label><br>
   			 <label><input type="checkbox" name="available_day[]" id="Sun" value="Sunday" checked> Sunday</label><br>
	    </td></tr>

      <?php if(isset($_POST['editFacility'])){?>

      <script type="text/javascript">
      var days = "<?php echo $row['available_day']; ?>";

      if(days.indexOf("Monday")==-1){
        document.getElementById("Mon").checked = false;
      }

      if(days.indexOf("Tuesday")==-1){
        document.getElementById("Tue").checked = false;
      }

      if(days.indexOf("Wednesday")==-1){
        document.getElementById("Wed").checked = false;
      }

      if(days.indexOf("Thursday")==-1){
        document.getElementById("Thu").checked = false;
      }

      if(days.indexOf("Friday")==-1){
        document.getElementById("Fri").checked = false;
      }

      if(days.indexOf("Saturday")==-1){
        document.getElementById("Sat").checked = false;
      }

      if(days.indexOf("Sunday")==-1){
        document.getElementById("Sun").checked = false;
      }

      </script>

      <?php }?>

		<tr><td class="tag">Starting Publishing Date:</td>
		<td><input type="date" name="Start" id="Start" size = "30" required class="box" <?php if(isset($_POST['editFacility'])){?> value = "<?php echo $row['start_publish']?>" <?php }?>></td></tr>
		<tr><td class="tag">Ending Publishing Date:</td>
		<td><input type="date" name="End" id="End" size = "30" required class="box" <?php if(isset($_POST['editFacility'])){?> value = "<?php echo $row['end_publish']?>" <?php }?>></td></tr>
	  <tr><td class="tag">Unpublish:</td>
    <td><input type="checkbox" name="IsUnpublish" id="IsUnpublish" onclick="toggleUnpublish()" value="1" <?php if(isset($_POST['editFacility'])){ if($row['isUnpublish']==1) echo "checked"; }?>></td></tr>
    <tr style="display:none;" id="Announcement_row"><td class="tag">Announcement:</td>
    <td><textarea rows="4" cols="30" name="Announcement" id="Announcement" required style="resize: none;"><?php if(isset($_POST['editFacility'])){ echo $row['announcement']; }?></textarea></td></tr>
    <tr><td class="tag">Upload Photos (Please select at most four photos):<br><span style="font-size:80%">*File size must be less than 10 MB.</span></td>
	  <td><input type="file" name="Photo[]" multiple id="Photo" accept="image/*"/></td></tr>
    <tr><td class="tag">Is Removable:<br><span style="font-size:80%">*Bookings of removable items will need reminding SMS.</span></td>
        <td><select size="1" name="Need_remind">
          <option value="0" <?php if(isset($_POST['editFacility'])){ if($row['need_remind']==0) echo "selected"; }?>>No</option>
          <option value="1" <?php if(isset($_POST['editFacility'])){ if($row['need_remind']==1) echo "selected"; }?>>Yes</option>
        </select></td>
    </tr>
		<tr><td colspan="2" style="text-align:center"><input type="submit" name="Submit" id="Submit" value="Register" class="button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    	<a href="results.php" class="back_btn">Cancel</a></td>
    	</tr>
	</table>
 </form>
</div>

      <script type="text/javascript">

        <?php
        if(isset($_POST['editFacility'])){ if($row['isFabricationFacility']==1){
        ?>
        togglePriceFab();
        <?php }}?>
        <?php
        if(isset($_POST['editFacility'])){ if($row['isCleanRoomFacility']==1){
        ?>
        togglePriceClean();
        <?php }}?>
        <?php
        if(isset($_POST['editFacility'])){ if($row['isUnpublish']==1){
        ?>
        toggleUnpublish();
        <?php }}?>
      
      </script>

<footer>Copyright &copy; 2014
</footer>
</div>
</body>
</html>