<?php 
//getEvent.php for preparing the event_array for calenar displaying
session_start();
$itemname = $_GET['itemname'];

        $db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

        $query = "SELECT * FROM booking WHERE facility_name = '$itemname' AND approved = 1";
        $result = $db_conn->query($query);

        $num_results = $result->num_rows;

        for ($i=0; $i <$num_results; $i++) {
             $row = $result->fetch_assoc();
            $event_array[] = array(
                'id' => $row['booking_id'],
                'title' => $row['username'],
                'start' => $row['start_event'],
                'end' => $row['end_event'],
				'description' => $row['message'],
            );
        }

    echo json_encode($event_array);

?>