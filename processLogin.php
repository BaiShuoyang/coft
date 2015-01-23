<?php
session_start();

$username = $_POST['username'];
$password = $_POST['loginPwd'];

if (isset($_POST['username']) && isset($_POST['loginPwd']))
{
  // if the user has just tried to log in

  $db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

    if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query = "select * from external_user where username='".$username."'and password='".md5($password)."'";
  $query2 = "select * from internal_user where username='".$username."'and password='".md5($password)."'";
  $query3 = "select * from admin_user where username='".$username."'and password='".md5($password)."'";
  
  $result_external = $db_conn->query($query);
  $result_internal = $db_conn->query($query2);
  $result_admin = $db_conn->query($query3);

  if ($result_external->num_rows >0 )
  {
    // if they are in the database register the user id
    $row = $result_external ->fetch_assoc();
    $username = $row['username'];
    $_SESSION['valid_user'] = $username;  
    if($row['approved']==1){
        $_SESSION['user_identity'] = "external";
    }else{
        $_SESSION['user_identity'] = "external_nonapproved";
    }
    header("Location: results.php"); 
    exit(); 
  }else if ($result_internal->num_rows >0 ){
    $row = $result_internal ->fetch_assoc();
    $username = $row['username'];
    $_SESSION['valid_user'] = $username;  
    $_SESSION['user_identity'] = "internal";
    header("Location: results.php"); 
    exit(); 

  }else if ($result_admin->num_rows >0 ){
    $row = $result_admin ->fetch_assoc();
    $username = $row['username'];
    $_SESSION['valid_user'] = $username;  
    $_SESSION['user_identity'] = "admin";
    header("Location: results.php"); 
    exit(); 

  }else{
    unset($username);
    unset($password);
    session_destroy();
    header("Location: index.php?login_fail=1");
    exit();
  }
  $db_conn->close();
}else{
  session_destroy();
  header("Location: index.php?login_fail=1");
  exit();
}
?>
