<?php
//checkReturnDate.php
//This script is supposely run by the Windows Scheduled Task every 5 minutes indefinitely

  define('__ROOT__', dirname(__FILE__)); 
  require_once(__ROOT__.'\SMSClass.php'); 

  $account_key = '5b030de9';
  $account_secret = '7e41727b';
  $host_name = 'COFT';

  @ $db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');


  $query = "SELECT * FROM booking WHERE reminded = 0 AND approved = 1"; //Only check the approved bookings
  
  $result = $db_conn->query($query);

  $num_results = $result->num_rows;

  for ($i=0; $i <$num_results; $i++) {

         $row = $result->fetch_assoc();
		 
		 $end_time = strtotime($row['end_event']);

		 $now = strtotime(date("Y/m/d H:i"));

		 $time_difference = round(($end_time - $now)/60,2); //In minutes (float)


		 if(($time_difference < 1440.00) && ($row['reminded']==0) && ($time_difference > 0.00)){   //Send reminding sms 24 hours before the return time

		 		$query1 = "select * from external_user where username='".$row['username']."'";
				$query2 = "select * from internal_user where username='".$row['username']."'";
				$query3 = "select * from admin_user where username='".$row['username']."'";
				  
				$result_external = $db_conn->query($query1);
				$result_internal = $db_conn->query($query2);
				$result_admin = $db_conn->query($query3);

				if ($result_external->num_rows >0 ){

					$row_user = $result_external ->fetch_assoc();
				    $user_phone = $row_user['phone'];

				  }else if ($result_internal->num_rows >0 ){

				    $row_user = $result_internal ->fetch_assoc();
				    $user_phone = $row_user['phone'];

				  }else if ($result_admin->num_rows >0 ){

				    $row_user = $result_admin ->fetch_assoc();
				    $user_phone = $row_user['phone'];

				  }

		 		$sms = new NexmoMessage($account_key, $account_secret);

		 		$sms_content = 'Dear '.$row_user['username'].', please be reminded that your booking session for '.$row['facility_name']
		 						.' will expire in 24 hours. Please make sure the facility you booked is returned on time. Otherwise, additional charge will be imposed.';

				$sms->sendText( $user_phone, $host_name, $sms_content );

				$query_update = "UPDATE booking SET reminded = 1 WHERE booking_id = ".$row['booking_id'];
  
 				$db_conn->query($query_update);
		 }

     }

  $db_conn->close();




?>