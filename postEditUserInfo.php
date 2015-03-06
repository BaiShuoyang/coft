<?php
//postEditUserInfo.php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Paperless Lab</title>
<meta charset="utf-8">
<meta http-equiv="refresh" content="3;url=results.php" />
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
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
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
           <li><a href="results.php">Facility List</a></li>
           <li><a href="orderHistory.php">Order History</a></li>
       </ul>
  </nav>


<div class="content" style="min-height:450px;"> 
<h4 style="margin-top:50px;">Changes of your account information have been saved successfully. You will be redirected to facility list page in 3 seconds.</h4>
</div>
        <footer>Copyright &copy; 2014
        </footer>
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