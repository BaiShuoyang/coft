<?php
//editUserAccess.php

session_start();

if(isset($_GET['editId'])) {$editId = $_GET['editId'];}else{
	echo '<script type="text/javascript">alert("Error: Pass by GET failed.");</script>';
    exit;
}

?><!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<link rel="stylesheet" href="welcome.css">
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery.mmenu.min.js"></script>
<style type="text/css">
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

}
td.tag{
text-align: right;
width:40%;
}
</style>
<script type="text/javascript">

$(document).ready( function(){

	$('#btnAdd').click(function(){
		var masterBox = document.getElementById("MasterSelectBox");
		var pairBox = document.getElementById("PairedSelectBox");
		var defaultSelected = true;
		var selected = true;
		var optionName = new Option(masterBox.options[masterBox.selectedIndex].text,
		masterBox.options[masterBox.selectedIndex].value,
		defaultSelected, selected);
		masterBox.remove(masterBox.selectedIndex);
		var length = pairBox.length;
		pairBox.options[length] = optionName;
	});

	$('#btnRemove').click(function(){
		var masterBox = document.getElementById("MasterSelectBox");
		var pairBox = document.getElementById("PairedSelectBox");
		var defaultSelected = true;
		var selected = true;
		var optionName = new Option(pairBox.options[pairBox.selectedIndex].text,
		pairBox.options[pairBox.selectedIndex].value,
		defaultSelected, selected);
		pairBox.remove(pairBox.selectedIndex);
		var length = masterBox.length;
		masterBox.options[length] = optionName;
	});


});


function selectAll(){
	var pairBox = document.getElementById("PairedSelectBox");
	for (var i=0; i < pairBox.options.length; i++) {
    	pairBox.options[i].selected = true;
    }
    return true;
}

function init() {
    'use strict';
    
    // Confirm that document.getElementById() can be used:
    if (document && document.getElementById) {
        var accessForm = document.getElementById("accessForm");
        accessForm.onsubmit = selectAll;
    }

}

// Assign an event listener to the window's load event:
window.onload = init;

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
  		 <!-- <span id="nav_first"><li><a id = "modal_trigger" href="#modal">Login</a></li></span> -->
         <span id="nav_hide" style="display:none"></span>
         	 <li><a href="results.php">Facility List</a></li>
	         <li><a href="orderHistory.php">Order History</a></li>
       </ul>
  </nav>


<?php

if (isset($_SESSION['valid_user'])){ ?>

<script language="JavaScript">

 // document.getElementById("nav_first").style.display = 'none';
 document.getElementById("nav_hide").style.display = '';
 document.getElementById("nav_hide").innerHTML = "<li><a>Hi, <?php echo $_SESSION['valid_user']; ?></a></li><li><a href='logout.php'>Sign Out</a></li><li><a href='editUserInformation.php'>My Account</a></li>";
</script>

<?php } ?>

<?php

	$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

	  if (mysqli_connect_errno()) {
	     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
	     exit;
	  }

	$query= "SELECT * FROM normal_user WHERE user_id = $editId";

    $result = $db_conn->query($query);

    if(!$result){
    	echo '<script type="text/javascript">alert("Error: Retrieving user information failed. Please try again later.");</script>';
     	exit;
    }

    $row = $result->fetch_assoc();

    $facility_array = explode(",",$row['facility_access']);
    
    $number_array = sizeof($facility_array);

    $query2 = "SELECT * FROM item";

	$result2 = $db_conn->query($query2);

	if(!$result2){
    	echo '<script type="text/javascript">alert("Error: Retrieving facility information failed. Please try again later.");</script>';
     	exit;
    }

    $num_results2 = $result2->num_rows;

?>

<div class="content" style="min-height:450px; font-size:0.9em"> 
	<h3 style="text-align:center;color:#0b78a1;margin-top:50px;">User Access - <?php echo $row['username']?></h3><hr>
		<form id="accessForm" action="processUserAccess.php" method="post">
		<table style="margin-left:auto; margin-right:auto; width:50%; text-align:center" cellspacing="5">
		<tr><td>All facilities:</td>
			<td style="text-align:center">
			<select id="MasterSelectBox" multiple size="6" style="width: 300px; height:auto; margin:0; font-size:85%" class="box">
		    	<?php for ($i=0; $i < $num_results2; $i++) {
	               $row2 = $result2->fetch_assoc();
	               if(!in_array($row2['facility_name'], $facility_array)){
	              ?>
	            <option value="<?php echo $row2['facility_name']?>"><?php echo $row2['facility_name']?></option>
	           <?php }
	       		}	?>
			</select>
		</td>
		</tr>
		<tr><td></td>
			<td style="text-align:center">
				<button type="button" id="btnAdd">Add</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="btnRemove">Remove</button>
			</td>
		</tr>
		 <tr>
		 	<td>Current access:</td>
			<td style="text-align:center">
				<select id="PairedSelectBox" name="current_access[]" multiple size="6" style="width: 300px; height:auto; margin:0; font-size:85%" class="box">
					<?php 
			            if($row['facility_access']!=""){
			              for ($i=0; $i < $number_array; $i++) {
			              ?>
			            <option value="<?php echo $facility_array[$i]?>"><?php echo $facility_array[$i];?></option>
			        <?php }
		            }?>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align:center"><input type="submit" value="Save changes" class="button"></td>
		</tr>
	</table>
	<input type="hidden" value="<?php echo $editId?>" name="user_id" id="user_id">
	</form>
</div>


<footer>Copyright &copy; 2014
</footer>
</div>
</body>
<?php
$db_conn->close();
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
</html>