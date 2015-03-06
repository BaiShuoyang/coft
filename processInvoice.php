<?php
//processInvoice.php
session_start();

$selected_booking = $_POST['selected_booking'];
$number_selected = sizeof($selected_booking);
$content = $_POST['Content'];
$total_price = $_POST['total_price'];

// var_dump($selected_booking);
// var_dump($email);
// var_dump($total_price);

@ $db_conn = new mysqli('localhost','root','fyp.2013','coft');

if (mysqli_connect_errno()) {
 echo '<script type="text/javascript">alert("Error: Could not connect to database. Please try again later.");</script>';
 exit;
}

$query_bill = "SELECT * FROM billing_information WHERE booking_id = ".$selected_booking[0]; 
//Take the first selected booking and retrieve the billing person info
$result_bill = $db_conn->query($query_bill);
if(!$result_bill){
   echo '<script type="text/javascript">alert("Your query to retrieve billing information failed.");</script>';
   exit;
}

$row_bill = $result_bill->fetch_assoc();

$username = $row_bill['name'];
$company = $row_bill['organization'];
$addline1 = $row_bill['addline1'];
$addline2 = $row_bill['addline2'];
$postal = $row_bill['postalcode'];
$phone = $row_bill['phone'];


//////////////////////////////////////////////

//Generate the invoice from template pdf file

/////////////////////////////////////////////

require_once('fpdf/fpdf.php');
require_once('fpdi/fpdi.php');

$pdf = new FPDI();

$pageCount = $pdf->setSourceFile("attachment/template2.pdf");
$tplIdx = $pdf->importPage(1, '/MediaBox');

$pdf->addPage();
$pdf->useTemplate($tplIdx);

$pdf->SetFont('Helvetica');
$pdf->SetFontSize(10);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetXY(14, 40);
$pdf->Write(5, 'Job requested by:');
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
$pdf->Write(5, 'Delivered To:');
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



$query_form = "INSERT INTO work_request_form VALUES(NULL, '$username', '$total_price')";
$result_form = $db_conn->query($query_form);
$wr_id = $db_conn->insert_id;
if(!$result_form){
   echo '<script type="text/javascript">alert("Your query to insert work request failed.");</script>';
   exit;
}

//First table
//First row header
$pdf->SetFont('Helvetica','B',10);
$pdf->SetXY(14, 75);
$pdf->SetFillColor(234, 234, 234);
$pdf->Cell(40, 5, 'Work Request Date',1,0,"C",TRUE);
$pdf->Cell(40, 5, 'WR #',1,0,"C",TRUE);
$pdf->Cell(40, 5, 'Customer #',1,0,"C",TRUE);
$pdf->Cell(40, 5, 'Purchase Order #',1,0,"C",TRUE);
// $pdf->Cell(30, 5, 'DOI #',1,0,"C",TRUE);

//Second row data
$pdf->SetXY(14, 80);
$pdf->SetFont('Helvetica');
$pdf->Cell(40, 5, date("d\-M\-Y"),1,0,"C",FALSE);
$pdf->Cell(40, 5, $wr_id ,1,0,"C",FALSE);
$pdf->Cell(40, 5, $phone,1,0,"C",FALSE);
$pdf->Cell(40, 5, $wr_id ,1,0,"C",FALSE);
// $pdf->Cell(30, 5, 'DOI #',1,1,"C",FALSE);

//Second table
//First row header
$pdf->SetXY(14, 90);
$pdf->SetFont('Helvetica','B',10);
$pdf->SetFillColor(234, 234, 234);
$pdf->Cell(30, 5, 'Product #',1,0,"C",TRUE);
$pdf->Cell(80, 5, 'Product Description',1,0,"C",TRUE);
$pdf->Cell(20, 5, 'Qty',1,0,"C",TRUE);
$pdf->Cell(30, 5, 'Basic Price',1,0,"C",TRUE);
$pdf->Cell(30, 5, 'Total',1,0,"C",TRUE);

for($i=0; $i < $number_selected; $i++){
	$query_booking = "SELECT * FROM booking WHERE booking_id = $selected_booking[$i]";
    $result_booking = $db_conn->query($query_booking);
    if(!$result_booking){
	       echo '<script type="text/javascript">alert("Your query to retrieve booking information failed.");</script>';
	       exit;
    }
    $row_booking = $result_booking->fetch_assoc();
	$query_facility= "SELECT * FROM item WHERE facility_name = '".$row_booking['facility_name']."'";
    $result_facility = $db_conn->query($query_facility);
    if(!$result_facility){
       echo '<script type="text/javascript">alert("Your query to retrieve facility information failed.");</script>';
       exit;
    }
    $row_facility = $result_facility->fetch_assoc();
    $id = $row_facility['facility_id'];
    $itemname = $row_facility['facility_name'];
    $basic_price = $row_facility['price'];
    $booking_total = $row_booking['total_price'];
    $message = $row_booking['message'];

 
    //Second row
	$pdf->SetXY(14, 95+5*$i);
	$pdf->SetFont('Helvetica');
	$pdf->Cell(30, 5, $id,1,0,"C",FALSE);
	$pdf->Cell(80, 5, $itemname,1,0,"C",FALSE);
	$pdf->Cell(20, 5, '1',1,0,"C",FALSE);
	$pdf->Cell(30, 5, '$'.number_format($basic_price,2),1,0,"C",FALSE);
	$pdf->Cell(30, 5, '$'.number_format($booking_total,2),1,0,"C",FALSE);
}




//Third row
$pdf->SetXY(14, 95+5*$number_selected);
$pdf->Cell(110, 25, "Comments:\n".$message,1,0,"L",FALSE);
$pdf->SetXY(124, 95+5*$number_selected);
$pdf->Cell(50, 5, 'Subtotal',1,0,"L",FALSE);
$pdf->SetXY(124, 100+5*$number_selected);
$pdf->Cell(50, 5, 'Discount',1,0,"L",FALSE);
$pdf->SetXY(124, 105+5*$number_selected);
$pdf->Cell(50, 5, 'GST',1,0,"L",FALSE);
$pdf->SetXY(124, 110+5*$number_selected);
$pdf->Cell(50, 5, 'Shipping and Handling',1,0,"L",FALSE);
$pdf->SetXY(124, 115+5*$number_selected);
$pdf->Cell(50, 5, 'Total',1,0,"L",FALSE);

$pdf->SetXY(174, 95+5*$number_selected);
$pdf->Cell(30, 5, '$'.number_format($total_price,2),1,0,"C",FALSE);
$pdf->SetXY(174, 100+5*$number_selected);
$pdf->Cell(30, 5, '$0.00',1,0,"C",FALSE);
$pdf->SetXY(174, 105+5*$number_selected);
$pdf->Cell(30, 5, '$'.number_format(($total_price)*0.07,2),1,0,"C",FALSE);
$pdf->SetXY(174, 110+5*$number_selected);
$pdf->Cell(30, 5, '$0.00',1,0,"C",FALSE);
$pdf->SetXY(174, 115+5*$number_selected);
$pdf->Cell(30, 5, '$'.number_format(($total_price)*1.07,2),1,0,"C",FALSE);

$pdf->SetXY(24, 255);
$pdf->Write(5, $username);

// $pdf->AddPage();

// $tplIdx = $pdf->importPage(2,'/MediaBox');

// $pdf->useTemplate($tplIdx);

$pdf->Output('attachment/invoice_'.$wr_id.'.pdf', 'F');



////////////////////////////////////////////
//Send email to the user with attachment //
///////////////////////////////////////////

$htmlbody = "$content";

//define the receiver of the email 
$to = $row_bill['email'];

//define the subject of the email 
$subject = 'Work Request Form from Centre for Optical Fibre Technology'; 

//create a boundary string. It must be unique 
//so we use the MD5 algorithm to generate a random hash 
$random_hash = md5(date('r', time())); 

//define the headers we want passed. Note that they are separated with \r\n 
$headers = "From: Centre for Optical Fibre Technology";

//add boundary string and mime type specification 
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 

//read the atachment file contents into a string,
//encode it with MIME base64,
//and split it into smaller chunks
$attachment = chunk_split(base64_encode(file_get_contents('attachment/invoice_'.$wr_id.'.pdf'))); 

//define the body of the message.
$message = "--PHP-mixed-$random_hash\r\n"."Content-Type: multipart/alternative; boundary=\"PHP-alt-$random_hash\"\r\n\r\n";
$message .= "--PHP-alt-$random_hash\r\n"."Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";


//Insert the html message.
$message .= $htmlbody;
$message .="\r\n\r\n--PHP-alt-$random_hash--\r\n\r\n";

//include attachment
$message .= "--PHP-mixed-$random_hash\r\n"
."Content-Type: application/zip; name=\"invoice_$wr_id.pdf\"\r\n"
."Content-Transfer-Encoding: base64\r\n"
."Content-Disposition: attachment\r\n\r\n";
$message .= $attachment;
$message .= "/r/n--PHP-mixed-$random_hash--";

//send the email
$mail = mail( $to, $subject , $message, $headers);

if(!$mail){
	$query_fail = "DELETE FROM work_request_form WHERE wr_id = $wr_id";
	$db_conn->query($query_fail);
	header("Location: orderHistory.php?emailFail=1");
	$db_conn->close();
	exit;

}else{
	for($j=0; $j < $number_selected; $j++){
		$query_update = "UPDATE booking SET billed = 1 WHERE booking_id = $selected_booking[$j]";
	    $result_update = $db_conn->query($query_update);
	    if(!$result_update){
		       echo '<script type="text/javascript">alert("Your query to update booking information failed.");</script>';
		       exit;
	    }
	}
}

$db_conn->close();
header("Location: orderHistory.php"); 
?>