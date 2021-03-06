<?php

session_start();

if(isset($_GET['approveId'])) {$approveId = $_GET['approveId'];}
if(isset($_GET['denyId'])) {$denyId = $_GET['denyId'];}

// var_dump($approveId);

if(isset($approveId)){

@ $db_conn = new mysqli('localhost','root','fyp.2013','coft');

		  if (mysqli_connect_errno()) {
		     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
		     exit;
		  }

$query3 = "SELECT * FROM booking WHERE booking_id =  $approveId";
$result3 = $db_conn->query($query3);
if(!$result3){
	echo '<script type="text/javascript">alert("Query failed.");</script>';
	exit;
}

$row3 = $result3->fetch_assoc();

$start =  DateTime::createFromFormat('Y/m/d H:i', $row3['start_event']);
$end =  DateTime::createFromFormat('Y/m/d H:i', $row3['end_event']);

$query4 = "SELECT * FROM booking WHERE facility_name='".$row3['facility_name']."' AND approved = 1"; //Only select the bookings which have been approved
 
$result = $db_conn->query($query4);

$num_results = $result->num_rows;

for ($i=0; $i <$num_results; $i++) {
         $row = $result->fetch_assoc();
         $row_start = DateTime::createFromFormat('Y/m/d H:i', $row['start_event']);
         $row_end = DateTime::createFromFormat('Y/m/d H:i', $row['end_event']);

         if( ($end >= $row_start && $start <= $row_start) || ($start <= $row_end && $end >= $row_end)
         	|| ($start >= $row_start && $end <= $row_end) || ($start <= $row_start && $end >= $row_end) ){
         	
         	$db_conn->close();
         	header("Location: approveBooking.php?conflict=1"); 
         	exit;
         }

}


$query = "UPDATE booking SET approved = 1 WHERE booking_id = $approveId";

$db_conn->query($query);

$query2 = "SELECT * FROM booking WHERE booking_id = $approveId";

$result = $db_conn->query($query2);

$num_result = $result->num_rows;

if($num_result!=1){
	echo '<script type="text/javascript">alert("Updating booking failed.");</script>';
	exit;
}

$row = $result->fetch_assoc();

$booking_id = $row['booking_id'];

$query_external = "SELECT * FROM external_user WHERE username ='".$row['username']."'";
 
$result_external = $db_conn->query($query_external);

$query_internal = "SELECT * FROM internal_user WHERE username ='".$row['username']."'";
 
$result_internal = $db_conn->query($query_internal);

$query_admin = "SELECT * FROM admin_user WHERE username ='".$row['username']."'";
 
$result_admin = $db_conn->query($query_admin);

if($result_external->num_rows >0){

	$row_user = $result_external->fetch_assoc();

}else if($result_internal->num_rows >0){

	$row_user = $result_internal->fetch_assoc();

}else if($result_admin->num_rows >0){

	$row_user = $result_admin->fetch_assoc();

}else{

	echo '<script type="text/javascript">alert("Error: The username of the booking is not found in the database.");</script>';
	exit;

}   


$username = $row_user['username'];
$company = $row_user['company'];
$addline1 = $row_user['addline1'];
$addline2 = $row_user['addline2'];
$postal = $row_user['postalcode'];
$phone = $row_user['phone'];

$result_facility = $db_conn->query("SELECT * FROM item WHERE facility_name ='".$row['facility_name']."'");

if($result_facility->num_rows!=1){
	echo '<script type="text/javascript">alert("Query failed when retrieving facility information.");</script>';
	exit;
}

$row_facility = $result_facility->fetch_assoc();

$id = $row_facility['facility_id'];
$itemname = $row_facility['facility_name'];


if(($row['user_identity']=="internal") || ($row['user_identity']=="admin")){
  			$price_per_day = $row_facility['price'] * $row_facility['charge_internal'];
}else if($row['user_identity']=="external"){
  			$price_per_day = $row_facility['price'] * $row_facility['charge_external'];
}else{
  			echo '<script type="text/javascript">alert("There is problem with user identity of this booking.");</script>';
  		    exit;
}

$total_price = $row['total_price'];
$message = $row['message'];
$start = $row['start_event'];
$end = $row['end_event'];
$email = $row_user['email'];


//////////////////////////////////////////////

//Generate the invoice from template pdf file

/////////////////////////////////////////////

require_once('fpdf/fpdf.php');
require_once('fpdi/fpdi.php');

$pdf = new FPDI();

$pageCount = $pdf->setSourceFile("attachment/template.pdf");
$tplIdx = $pdf->importPage(1, '/MediaBox');

$pdf->addPage();
$pdf->useTemplate($tplIdx);

$pdf->SetFont('Helvetica');
$pdf->SetFontSize(10);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetXY(14, 40);
$pdf->Write(5, 'Bill To:');
$pdf->SetXY(14, 45);
$pdf->Write(5, $username);
$pdf->SetXY(14, 50);
$pdf->Write(5, $company);
$pdf->SetXY(14, 55);
$pdf->Write(5, $addline1);
$pdf->SetXY(14, 60);
$pdf->Write(5, $addline2);
$pdf->SetXY(14, 65);
$pdf->Write(5, $postal);


$pdf->SetXY(104, 40);
$pdf->Write(5, 'Ship To:');
$pdf->SetXY(104, 45);
$pdf->Write(5, $username);
$pdf->SetXY(104, 50);
$pdf->Write(5, $company);
$pdf->SetXY(104, 55);
$pdf->Write(5, $addline1);
$pdf->SetXY(104, 60);
$pdf->Write(5, $addline2);
$pdf->SetXY(104, 65);
$pdf->Write(5, $postal);

//First table
//First row header
$pdf->SetFont('Helvetica','B',10);
$pdf->SetXY(14, 75);
$pdf->SetFillColor(234, 234, 234);
$pdf->Cell(40, 5, 'Invoice Date',1,0,"C",TRUE);
$pdf->Cell(40, 5, 'Invoice #',1,0,"C",TRUE);
$pdf->Cell(40, 5, 'Customer #',1,0,"C",TRUE);
$pdf->Cell(40, 5, 'Purchase Order #',1,0,"C",TRUE);
$pdf->Cell(30, 5, 'DOI #',1,0,"C",TRUE);

//Second row data
$pdf->SetXY(14, 80);
$pdf->SetFont('Helvetica');
$pdf->Cell(40, 5, date("d\-M\-Y"),1,0,"C",FALSE);
$pdf->Cell(40, 5, $booking_id,1,0,"C",FALSE);
$pdf->Cell(40, 5, $phone,1,0,"C",FALSE);
$pdf->Cell(40, 5, $booking_id,1,0,"C",FALSE);
$pdf->Cell(30, 5, 'DOI #',1,1,"C",FALSE);



//Second table
//First row header
$pdf->SetXY(14, 90);
$pdf->SetFont('Helvetica','B',10);
$pdf->SetFillColor(234, 234, 234);
$pdf->Cell(30, 5, 'Product #',1,0,"C",TRUE);
$pdf->Cell(80, 5, 'Product Description',1,0,"C",TRUE);
$pdf->Cell(20, 5, 'Qty',1,0,"C",TRUE);
$pdf->Cell(30, 5, 'Unit Price',1,0,"C",TRUE);
$pdf->Cell(30, 5, 'Total',1,0,"C",TRUE);

//Second row
$pdf->SetXY(14, 95);
$pdf->SetFont('Helvetica');
$pdf->Cell(30, 5, $id,1,0,"C",FALSE);
$pdf->Cell(80, 5, $itemname,1,0,"C",FALSE);
$pdf->Cell(20, 5, '1',1,0,"C",FALSE);
$pdf->Cell(30, 5, '$'.number_format($price_per_day,2),1,0,"C",FALSE);
$pdf->Cell(30, 5, '$'.number_format($total_price,2),1,0,"C",FALSE);

//Third row
$pdf->SetXY(14, 100);
$pdf->Cell(110, 25, "Comments:\n".$message,1,0,"L",FALSE);
$pdf->SetXY(124, 100);
$pdf->Cell(50, 5, 'Subtotal',1,0,"L",FALSE);
$pdf->SetXY(124, 105);
$pdf->Cell(50, 5, 'Discount',1,0,"L",FALSE);
$pdf->SetXY(124, 110);
$pdf->Cell(50, 5, 'Sales Tax',1,0,"L",FALSE);
$pdf->SetXY(124, 115);
$pdf->Cell(50, 5, 'Shipping and Handling',1,0,"L",FALSE);
$pdf->SetXY(124, 120);
$pdf->Cell(50, 5, 'Total',1,0,"L",FALSE);

$pdf->SetXY(174, 100);
$pdf->Cell(30, 5, '$'.number_format($total_price,2),1,0,"C",FALSE);
$pdf->SetXY(174, 105);
$pdf->Cell(30, 5, '$0.00',1,0,"C",FALSE);
$pdf->SetXY(174, 110);
$pdf->Cell(30, 5, '$0.00',1,0,"C",FALSE);
$pdf->SetXY(174, 115);
$pdf->Cell(30, 5, '$0.00',1,0,"C",FALSE);
$pdf->SetXY(174, 120);
$pdf->Cell(30, 5, '$'.number_format($total_price,2),1,0,"C",FALSE);

$pdf->AddPage();

$tplIdx = $pdf->importPage(2,'/MediaBox');

$pdf->useTemplate($tplIdx);

$pdf->Output('attachment/invoice_'.$username.'.pdf', 'F');



////////////////////////////////////////////
//Send email to the user with attachment //
///////////////////////////////////////////

$htmlbody = "
Dear $username,

This email serves as a confirmation for receiving your reservation at Centre for Optical Fibre Technology.
The detail of your booking is as below:


		Facility Name: $itemname
		Time:          $start - $end
		Basic Price per day:   SGD $price_per_day
		Total Price:   SGD $total_price
		Message:       $message



Regards,
COFT Office

This is an automatically generated confirmation email. Please do not reply directly.";

//define the receiver of the email 
$to = $email;

//define the subject of the email 
$subject = 'Confirmation from Centre for Optical Fibre Technology'; 

//create a boundary string. It must be unique 
//so we use the MD5 algorithm to generate a random hash 
$random_hash = md5(date('r', time())); 

//define the headers we want passed. Note that they are separated with \r\n 
$headers = "From: Centre for Optical Fibre Technology\r\nBCC:austinbai927@gmail.com"; //bcc administrator

//add boundary string and mime type specification 
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 

//read the atachment file contents into a string,
//encode it with MIME base64,
//and split it into smaller chunks
$attachment = chunk_split(base64_encode(file_get_contents('attachment/invoice_'.$username.'.pdf'))); 

//define the body of the message.
$message = "--PHP-mixed-$random_hash\r\n"."Content-Type: multipart/alternative; boundary=\"PHP-alt-$random_hash\"\r\n\r\n";
$message .= "--PHP-alt-$random_hash\r\n"."Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";


//Insert the html message.
$message .= $htmlbody;
$message .="\r\n\r\n--PHP-alt-$random_hash--\r\n\r\n";

//include attachment
$message .= "--PHP-mixed-$random_hash\r\n"
."Content-Type: application/zip; name=\"invoice_$username.pdf\"\r\n"
."Content-Transfer-Encoding: base64\r\n"
."Content-Disposition: attachment\r\n\r\n";
$message .= $attachment;
$message .= "/r/n--PHP-mixed-$random_hash--";

//send the email
$mail = mail( $to, $subject , $message, $headers, '-faustinbai927@gmail.com' );

if(!$mail){
	$query = "UPDATE booking SET approved = 0 WHERE booking_id = $approveId";
	$db_conn->query($query);
	header("Location: approveBooking.php?emailFail=1");
	$db_conn->close();
	exit;

}

$db_conn->close();

if(isset($_GET['source'])){
	header("Location: results.php"); 
}else{
	header("Location: approveBooking.php"); 
}
exit();

}else if(isset($denyId)){

	@ $db_conn = new mysqli('localhost','root','fyp.2013','coft');

		  if (mysqli_connect_errno()) {
		     echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
		     exit;
		  }

	$query = "UPDATE booking SET approved = -1 WHERE booking_id = $denyId";

	$db_conn->query($query);

	$query2 = "SELECT * FROM booking WHERE booking_id = $denyId";

	$result = $db_conn->query($query2);

	$num_result = $result->num_rows;

	if($num_result!=1){
		echo '<script type="text/javascript">alert("Updating booking failed.");</script>';
		exit;
	}

	$row = $result->fetch_assoc();

	if($row['user_identity']=="internal"){
		$query_user = "SELECT * FROM internal_user WHERE username = '".$row['username']."'";
	}else if($row['user_identity']=="external"){
		$query_user = "SELECT * FROM external_user WHERE username = '".$row['username']."'"; 
	}else if($row['user_identity']=="admin"){
		$query_user = "SELECT * FROM admin_user WHERE username = '".$row['username']."'"; 
	}else{
		echo '<script type="text/javascript">alert("User identity is invalid for the booking.");</script>';
		exit;
	}

	$result_user = $db_conn->query($query_user);

	$num_result_user = $result_user->num_rows;

	if($num_result_user!=1){
		echo '<script type="text/javascript">alert("There are more than one user with the same user name.");</script>';
		exit;
	}

	$row_user = $result_user->fetch_assoc();
	$username = $row_user['username'];
	$itemname = $row['facility_name'];
	$email = $row_user['email'];

$message = "
Dear $username,

Thank you for your booking in Centre for Optical Fibre Technology. 

I am sorry to inform your that your booking for $itemname has been denied by the administrator. This could because you have not taken required training before using the facility.

If you have any queries, you could send email to epshum@ntu.edu.sg.

Regards,
COFT Office

This is an automatically generated confirmation email. Please do not reply directly.";

	//define the receiver of the email 
	$to = $email;

	//define the subject of the email 
	$subject = 'Notification from Centre for Optical Fibre Technology'; 

	$headers = "From: Centre for Optical Fibre Technology\r\nBCC:austinbai927@gmail.com"; //bcc administrator

	$mail = mail( $to, $subject , $message, $headers, '-faustinbai927@gmail.com');

	if(!$mail){
		$query = "UPDATE booking SET approved = 0 WHERE booking_id = $denyId";
		$db_conn->query($query);
		header("Location: approveBooking.php?emailFail=1");
		$db_conn->close();
		exit;

	}


	$db_conn->close();
	
	if(isset($_GET['source'])){
		header("Location: results.php"); 
	}else{
		header("Location: approveBooking.php"); 
	} 
	exit();

}

$db_conn->close();

if(isset($_GET['source'])){
	header("Location: results.php"); 
}else{
	header("Location: approveBooking.php"); 
}
exit();

?>