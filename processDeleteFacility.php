<?php
//processDeleteFacility.php
session_start();

$itemname = $_POST['deleteFacility'];

//function to delete a directory with all the files inside
function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

@ $db = new mysqli('localhost','root','fyp.2013','coft');

  if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     exit;
  }

  $query = "select * from item where facility_name = '".$itemname."'";

  $result = $db->query($query);

  $num_results = $result->num_rows;

  if($num_results!=1){
     echo '<script type="text/javascript">alert("Error: More than one item of the same name in database.");</script>';
     exit;
  }

  $query_delete = "DELETE FROM item WHERE facility_name = '".$itemname."'";

  $result_delete = $db->query($query_delete);

	if (!$result_delete){
    			echo '<script type="text/javascript">alert("Your query failed.");</script>';
                $db_conn->close();
    			exit();
    }else{ 
    		    //Delete the images in the server folder
            $dir="Image/Facility/".$itemname;
            deleteDir($dir);

            header("Location: results.php"); 
            $db_conn->close();
    		    exit();
    }

?>