<?php
//processFacilityRegister.php
session_start();
$itemname = $_POST['Itemname'];
$alias = $_POST['Alias'];
$description = $_POST['Description'];
$quantity = $_POST['Quantity'];
if(isset($_POST['Price'])) {$price = $_POST['Price'];}
if(isset($_POST['IsClean'])) {$isCleanRoomFacility = $_POST['IsClean'];}else{$isCleanRoomFacility="0";}
// $time = 60*$_POST['Time']; //The time unit user inputs is in hour, need to convert to minutes
if(isset($_POST['IsUnpublish'])) {$isUnpublish = $_POST['IsUnpublish'];}else{$isUnpublish="0";}
if(isset($_POST['Announcement'])) {$announcement = $_POST['Announcement'];}else{$announcement=" ";}
$available_day = $_POST['available_day'];
$start = $_POST['Start'];
$end = $_POST['End'];
$charge_internal = $_POST['Charge_internal']/100; //Convert the percentage to decimal number
$charge_external = $_POST['Charge_external']/100;
$need_remind = $_POST['Need_remind'];


$day = '';
for($i = 0; $i < 5; $i++){
	if(isset($available_day[$i])){
		$day = $day.$available_day[$i].",";
	}
}
$day = trim($day, ","); //Remove the last , after the last element


//Check the uploaded files 
$photo_dir = array();

if(isset($_FILES['Photo'])){
    $errors= array();
    $photo_number = sizeof($_FILES['Photo']['tmp_name']);
    if($photo_number > 4){
        echo '<script type="text/javascript">alert("You can only upload up to four photos.");</script>';
        include 'results.php';
        exit;
    }
	foreach($_FILES['Photo']['tmp_name'] as $key => $tmp_name ){

		$file_name = $_FILES['Photo']['name'][$key];
		$file_size =$_FILES['Photo']['size'][$key];
		$file_tmp =$_FILES['Photo']['tmp_name'][$key];

        $name_array = explode('.', $file_name); //Divide the file name to an array
        $image_ext = strtolower(end($name_array)); //Point to the last element of the array

        // Allow certain file formats
        if($file_name != ""){//If no files are uploaded, the checking is not performed.
            if($image_ext != "jpg" && $image_ext != "png" && $image_ext != "jpeg" && $image_ext != "gif" ){

                echo '<script type="text/javascript">alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");</script>';
                include 'results.php';
                exit;
            }
        }

        if($file_size > 10485760){ //In bytes
			$errors[]='File size must be less than 10 MB';
        }	

        // $check = getimagesize($file_tmp);
        // if($check == false) {
        //     echo '<script type="text/javascript">alert("File is not an image.");</script>';
        //     include 'results.php';
        //     exit;
        // }	

        $desired_dir="Image/Facility/".$itemname;

        if(empty($errors)==true){

            if(is_dir($desired_dir)==false){
                mkdir("$desired_dir", 0700);		// Create directory if it does not exist
            }
            if(is_dir("$desired_dir/".$file_name)==false){
                move_uploaded_file($file_tmp,"$desired_dir/".$file_name);
            }else{									//rename the file if another one exist
                $new_dir="$desired_dir/".$file_name.time();
                 rename($file_tmp,$new_dir) ;				
            }

            $photo_dir[] = 	"$desired_dir/".$file_name;
        
        }else{
                // print_r($errors);
        		echo '<script type="text/javascript">alert("File size must be less than 10 MB.");</script>';
                include 'results.php';
     			exit;
        }
    }
}

$db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

if (mysqli_connect_errno()) {
     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
     include 'results.php';
     exit;
}

$query_check = "SELECT * FROM item WHERE facility_name='$itemname'";
 
$result_check = $db_conn->query($query_check);

if($result_check->num_rows >0){//If the facility exists in the database, adding information of the same facility name will update the record in the database 

    $photo_dir = array_pad($photo_dir, 4, ''); //Pad the array to the length of four


// var_dump($_FILES['Photo']);
/* 
UPLOAD_ERR_OK
Value: 0; There is no error, the file uploaded with success.

UPLOAD_ERR_INI_SIZE
Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.

UPLOAD_ERR_FORM_SIZE
Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.

UPLOAD_ERR_PARTIAL
Value: 3; The uploaded file was only partially uploaded.

UPLOAD_ERR_NO_FILE
Value: 4; No file was uploaded.

UPLOAD_ERR_NO_TMP_DIR
Value: 6; Missing a temporary folder. Introduced in PHP 5.0.3.

UPLOAD_ERR_CANT_WRITE
Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.

UPLOAD_ERR_EXTENSION
Value: 8; A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0.
*/
    
if($_FILES['Photo']['error'][0] === UPLOAD_ERR_NO_FILE){
     $query_update = "UPDATE item
                    SET facility_name = '$itemname', alias = '$alias', description = '$description', isCleanRoomFacility = $isCleanRoomFacility, quantity = '$quantity', price = '$price', charge_internal = '$charge_internal', 
                    charge_external = '$charge_external', available_day = '$day', start_publish = '$start', end_publish = '$end', isUnpublish = $isUnpublish, announcement = '$announcement', need_remind = $need_remind
                    WHERE facility_name='$itemname'";
}else{
    $query_update = "UPDATE item
                    SET facility_name = '$itemname', alias = '$alias', description = '$description', isCleanRoomFacility = $isCleanRoomFacility, quantity = '$quantity', price = '$price', charge_internal = '$charge_internal', 
                    charge_external = '$charge_external', available_day = '$day', start_publish = '$start', end_publish = '$end', isUnpublish = $isUnpublish, announcement = '$announcement', need_remind = $need_remind,
                    photo1 = '$photo_dir[0]', photo2 = '$photo_dir[1]', photo3 = '$photo_dir[2]', photo4 = '$photo_dir[3]' 
                    WHERE facility_name='$itemname'";
}
    $result_update = $db_conn->query($query_update);
    if (!$result_update){

                echo '<script type="text/javascript">alert("Your update query failed.");</script>';
                $db_conn->close();
                exit();
    }else{ 
                header("Location: results.php"); 
                $db_conn->close();
                exit();
    }

}else{

if($_FILES['Photo']['error'][0] === UPLOAD_ERR_NO_FILE){
    $query = "INSERT INTO item
    VALUES (NULL, '$itemname', '$alias', '$description', $isCleanRoomFacility, '$quantity', '$price', '$charge_internal', '$charge_external',
    		'$day', '$start', '$end', $isUnpublish, '$announcement', '', '', '', '', $need_remind)";
}else{
    $query = "INSERT INTO item
    VALUES (NULL, '$itemname', '$alias', '$description', $isCleanRoomFacility, '$quantity', '$price', '$charge_internal', '$charge_external',
            '$day', '$start', '$end', $isUnpublish, '$announcement', '$photo_dir[0]', '$photo_dir[1]', '$photo_dir[2]', '$photo_dir[3]', $need_remind)";
}
    $result = $db_conn->query($query);
    if (!$result){
    			echo '<script type="text/javascript">alert("Your query failed.");</script>';
                $db_conn->close();
    			exit();
    }else{ 
    		    header("Location: results.php"); 
                $db_conn->close();
    		    exit();
    }
}
?>