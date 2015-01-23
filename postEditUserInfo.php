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
           <li><a href="results.php">Facility List</a></li>
           <li><a href="orderHistory.php">Order History</a></li>
       </ul>
  </div>


<div class="content" style="min-height:450px;"> 
<h4 style="margin-top:50px;">Changes of your account information have been saved successfully. You will be redirected to facility list page in 3 seconds.</h4>
</div>
        <footer>Copyright &copy; 2014
        </footer>
</body>
</html>