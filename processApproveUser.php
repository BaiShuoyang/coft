<?php
//processApprove.php
session_start();

$approveEmail = $_GET['approveEmail'];
$denyEmail = $_GET['denyEmail'];

if(isset($approveEmail)){

	@ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query_approve = "select * from external_user where email = '".$approveEmail."'";

  $result_approve = $db->query($query_approve);

  $num_results_approve = $result_approve->num_rows;

  if($num_results_approve>1){
  	 echo '<script type="text/javascript">alert("Error: More than one users use the same email address.");</script>';
     exit;
  }

  $row_approve = $result_approve->fetch_assoc();

  $query_update = "UPDATE external_user SET approved = 1 WHERE email = '".$approveEmail."'";

  $result_update = $db->query($query_update);

  if (!$result_update){
    			echo '<script type="text/javascript">alert("Your query failed.");</script>';
                $db->close();
    			exit();
    }else{ 
				      $headers = "From: Centre for Optical Fibre Technology\r\nBCC:austinbai927@gmail.com"; //bcc administrator

				  		// the message
						$msg = "Dear ".$row_approve['username'].",\n\nThank you for registration in Centre for Optical Fibre Technology. Your account has been approved by the system administrator. You can start to make bookings with your account. \n\nIf you have any other queries, please contact us through email.\n\n\n\nRegards,\nCOFT Office\n\n\nThis is an automatically generated confirmation email. Please do not reply directly.";

					// send email
					mail($approveEmail,"Your account in COFT has been approved",$msg,$headers,'-faustinbai927@gmail.com');

	    		    header("Location: approveExternalUser.php"); 
	                $db->close();
	    		    exit();
    }

}else if(isset($denyEmail)){

	@ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query_deny = "select * from external_user where email = '".$denyEmail."'";

  $result_deny = $db->query($query_deny);

  $num_results_deny = $result_deny->num_rows;

  if($num_results_deny > 1){
  	 echo '<script type="text/javascript">alert("Error: More than one users use the same email address.");</script>';
     exit;
  }

  $query_update = "UPDATE external_user SET approved = -1 WHERE email = '".$denyEmail."'";

  $result_update = $db->query($query_update);

  if (!$result_update){
    			echo '<script type="text/javascript">alert("Your query failed.");</script>';
                $db->close();
    			exit();
    }else{ 		
    			$headers = "From: Centre for Optical Fibre Technology\r\nBCC:austinbai927@gmail.com"; //bcc administrator

				  		// the message
				$msg = "Dear ".$row_approve['username'].",\n\nThank you for registration in Centre for Optical Fibre Technology. Sorry to tell you that your account has been denied by the administrator. Thank you for your interest. \n\nIf you have any other queries, please contact us through email.\n\n\n\nRegards,\nCOFT Office\n\n\nThis is an automatically generated confirmation email. Please do not reply directly.";

					// send email
				mail($denyEmail,"Your account in COFT has been denied",$msg,$headers,'-faustinbai927@gmail.com');

    		    header("Location: approveExternalUser.php"); 
            $db->close();
    		    exit();
    }

}

header("Location: approveExternalUser.php"); 
$db->close();
exit;


?>