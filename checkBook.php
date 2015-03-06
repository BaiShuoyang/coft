<?php
//This file is used to check the booking intervals inserted by users agains the booking details in the database.
//This file will return 'Empty', 'Conflict', 'Ok' for the cases of empty fields, existing conflicts against the details in the database, and ok.

session_start();

$itemname = $_GET['itemname'];
$start = $_GET['start'];
$end = $_GET['end'];
$start = DateTime::createFromFormat('d/m/Y H:i', $start);
$end = DateTime::createFromFormat('d/m/Y H:i', $end);
$start = $start->format('Y/m/d H:i');
$end = $end->format('Y/m/d H:i');

$start_day = date('l', strtotime($start));
$end_day = date('l', strtotime($end));

$seconds_diff = strtotime($end) - strtotime("now");
$days_diff = $seconds_diff/(3600 *24);
if($days_diff >= 31){
    //Advanced booking period: The user can only make bookings within one month ahead.
    echo 'BeyondOneMonth';
    exit;
}
// var_dump($start);

$start = DateTime::createFromFormat('Y/m/d H:i', $start);
$end = DateTime::createFromFormat('Y/m/d H:i', $end);

$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

$query_item = "SELECT * FROM item WHERE facility_name='$itemname'";

$result_item = $db_conn->query($query_item);

$row_item = $result_item->fetch_assoc();

if((strpos($row_item['available_day'], $start_day)===false) || (strpos($row_item['available_day'], $end_day)===false)){
    echo 'NotAllowed';
    unset($result_item);
    unset($row_item);
    $db_conn->close();
    exit;
}

$query = "SELECT * FROM booking WHERE facility_name='$itemname' AND approved = 1"; //Only select the bookings which have been approved
 
$result = $db_conn->query($query);

$num_results = $result->num_rows;

for ($i=0; $i <$num_results; $i++) {
         $row = $result->fetch_assoc();
         $row_start = DateTime::createFromFormat('Y/m/d H:i', $row['start_event']);
         $row_end = DateTime::createFromFormat('Y/m/d H:i', $row['end_event']);

         if( $start == '' || $end == '' ){
         	echo 'Empty';

         	unset($result);
         	unset($row);
         	unset($num_results);
         	$db_conn->close();
         	exit;
         }

         if( ($end > $row_start && $start <= $row_start) || ($start < $row_end && $end >= $row_end)
         	|| ($start >= $row_start && $end <= $row_end) || ($start <= $row_start && $end >= $row_end) ){
         	
         	echo 'Conflict';

         	unset($result);
         	unset($row);
         	unset($num_results);
         	$db_conn->close();
         	exit;
         }

}


echo 'Ok';


?>