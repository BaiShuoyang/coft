<?php
//This php file should be executed on the last day of every month
	require_once ('../jpgraph/src/jpgraph.php');
	require_once ('../jpgraph/src/jpgraph_pie.php');
	require_once ('../jpgraph/src/jpgraph_bar.php');
	require_once ('../jpgraph/src/jpgraph_mgraph.php');
	require_once ('../jpgraph/src/jpgraph_line.php');
    require_once ('../jpgraph/src/jpgraph_date.php');
    require_once ('../jpgraph/src/jpgraph_utils.inc.php');
    require_once('../fpdf/fpdf.php');
	require_once('../fpdi/fpdi.php');

	// $data = array();
	// //This is an associative array, key is facility name, value is an array storing booking dates and number of bookings per booking date
 	$data_count = array();
 	$data_name = array();
 	$data_count_history = array();
 	$data_name_history = array();

	@ $db_conn = new mysqli('localhost', 'root', 'fyp.2013', 'coft');

	////////////////////////////////////////////////////////////
	//Generate graph for monthly booking
    ////////////////////////////////////////////////////////////

    //Generate Linear Graph
		
		$name_array = array();
		$result_name = $db_conn->query("SELECT facility_name FROM booking WHERE booking_date > DATE_SUB(CURDATE(), INTERVAL 1 MONTH) GROUP BY facility_name");

		$num_results_name = $result_name->num_rows;

		for ($i=0; $i <$num_results_name; $i++) {
			$row_name = $result_name->fetch_assoc();
			$name_array[] = $row_name['facility_name'];
		}

		// var_dump($name_array);


		// Create the new graph
			$graph_line = new Graph(540,500);
			 
			// Slightly larger than normal margins at the bottom to have room for
			// the x-axis labels
			$graph_line->SetMargin(40,40,30,230);
			 
			// Fix the Y-scale to go between [0,100] and use date for the x-axis
			$graph_line->SetScale('datint',0,20);
			$graph_line->title->Set("Booking Number for ".date('F'));
			 
			// Set the angle for the labels to 90 degrees
			$graph_line->xaxis->SetLabelAngle(90);
			// Adjust the start time for a day
			$graph_line->xaxis->scale->SetDateAlign(MONTHADJ_1);
			 
			// Force labels to only be displayed every one week
			$graph_line->xaxis->scale->ticks->Set(24*3600*7);
			 
			// Use hour:minute format for the labels
			$graph_line->xaxis->scale->SetDateFormat('m/d');

			$graph_line->img->SetAntiAliasing(false); 

			$graph_line->title->SetFont(FF_FONT1,FS_BOLD);

			$line = array();

		//Generate Line for all facilities appeared in booking history
		for ($k=0; $k <sizeof($name_array); $k++){

			$datay = array();
 			$datax = array();

			$query_date = "SELECT facility_name, COUNT(facility_name) AS count, booking_date
					FROM booking
					WHERE booking_date > DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND facility_name = '$name_array[$k]'
					GROUP BY facility_name, booking_date";

			$result_date = $db_conn->query($query_date);

			$num_results_date = $result_date->num_rows;

			for ($i=0; $i <$num_results_date; $i++) {
				$row_date = $result_date->fetch_assoc();
				$datax[]=strtotime($row_date['booking_date']);
				$datay[]=(int) $row_date['count'];
				
			}

			// echo "after one facility ".$k."<br>";
			// var_dump($datax);
			// echo "<br>";
			// var_dump($datay);

			$line[] = new LinePlot($datay,$datax);
		}		 
		 
		for ($m=0; $m <sizeof($line); $m++){
				 $graph_line->Add($line[$m]);
				 $color = substr(md5(rand()), 0, 6);
				 $line[$m] -> SetWeight ( 1.5 ); 
				 $line[$m] -> SetColor ( '#'.$color );
				 $line[$m] -> mark->SetType(MARK_UTRIANGLE);
				$line[$m] -> mark->SetColor('#'.$color);
				$line[$m] -> mark->SetFillColor('#'.$color);

			$result_getAlias3 = $db_conn->query("SELECT alias FROM item WHERE facility_name = '".$name_array[$m]."'");	
			$row_alias3 = $result_getAlias3->fetch_assoc();	

		    $alias = $row_alias3['alias'];
			
			$line[$m] -> SetLegend($alias);
		} 
		// $graph_line->Stroke();


		//Generate Pie Graph

		$query = "SELECT facility_name, COUNT(facility_name) AS count
					FROM booking
					WHERE booking_date > DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
					GROUP BY facility_name";

		$result = $db_conn->query($query);

		$num_results = $result->num_rows;

		for ($i=0; $i <$num_results; $i++) {

			$row = $result->fetch_assoc();	
			$result_getAlias = $db_conn->query("SELECT alias FROM item WHERE facility_name = '".$row['facility_name']."'");	
			$row_alias = $result_getAlias->fetch_assoc();	

		    $data_name[] = $row_alias['alias'];
		    $data_count[] = (int) $row['count'];
		    
		}
		 // var_dump($data_count);


		$graph_pie = new PieGraph(500,400);
		$graph_pie->SetShadow();
		 
		$graph_pie->title->Set("Booking Percentages for ".date('F'));
		$graph_pie->title->SetFont(FF_FONT1,FS_BOLD);
		 
		$p1 = new PiePlot($data_count);
		$p1->SetLegends($data_name);
		$p1->SetGuideLines(true,false);
		$p1->SetGuideLinesAdjust(1.1);

		$p1->SetLabelType(PIE_VALUE_PER);    
		$p1->value->Show();            
		$p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);    
		$p1->value->SetFormat('%2.1f%%');    
		 
		$graph_pie->Add($p1);
		// $graph_pie->Stroke();



		//Generate Bar Graph
		$graph_bar = new Graph(500,500);
		$graph_bar->SetScale('textint');
		 
		// Add a drop shadow
		$graph_bar->SetShadow();
		 
		// Adjust the margin a bit to make more room for titles
		$graph_bar->SetMargin(40,30,20,200);
		 
		// Create a bar pot
		$bplot = new BarPlot($data_count);
		 
		// Adjust fill color
		$bplot->SetFillColor('orange');
		$graph_bar->Add($bplot);
		 
		// Setup the titles
		$graph_bar->title->Set("Booking Numbers for ".date('F'));
		$graph_bar->xaxis->SetTickLabels($data_name);
		$graph_bar->xaxis->title->Set('Facility');
		$graph_bar->xaxis->SetTitleMargin(50);//Margin of axis title to the axis
		$graph_bar->xaxis->SetLabelAngle(90);
		$graph_bar->yaxis->title->Set('Number');
		 
		$graph_bar->title->SetFont(FF_FONT1,FS_BOLD);
		$graph_bar->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
		$graph_bar->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
		 
		// Display the graph
		// $graph_bar->Stroke();





		/////////////////////////////////////////////////////////////////
		//Generate graphs for history bookings
		/////////////////////////////////////////////////////////////////

    	//Generate Linear Graph
		
		$name_array2 = array();
		$result_name2 = $db_conn->query("SELECT facility_name FROM booking GROUP BY facility_name");

		$num_results_name2 = $result_name2->num_rows;

		for ($i=0; $i <$num_results_name2; $i++) {
			$row_name2 = $result_name2->fetch_assoc();
			$name_array2[] = $row_name2['facility_name'];
		}

		// var_dump($name_array);

		// Create the new graph
			$graph_line2 = new Graph(540,500);
			 
			// Slightly larger than normal margins at the bottom to have room for
			// the x-axis labels
			$graph_line2->SetMargin(40,40,30,230);
			 
			// Fix the Y-scale to go between [0,100] and use date for the x-axis
			$graph_line2->SetScale('datint',0,50);
			$graph_line2->title->Set("Numbers of History Bookings");
			 
			// Set the angle for the labels to 90 degrees
			$graph_line2->xaxis->SetLabelAngle(90);
			// Adjust the start time for a day
			$graph_line2->xaxis->scale->SetDateAlign(YEARADJ_1);
			 
			// Force labels to only be displayed every one month
			$graph_line2->xaxis->scale->ticks->Set(24*3600*32);
			 
			// Use hour:minute format for the labels
			$graph_line2->xaxis->scale->SetDateFormat('Y/m');

			$graph_line2->img->SetAntiAliasing(false); 

			$graph_line2->title->SetFont(FF_FONT1,FS_BOLD);

			$line2 = array();

		//Generate Line for all facilities appeared in booking history
		for ($k=0; $k <sizeof($name_array2); $k++){

			$datay2 = array();
 			$datax2 = array();

			$query_date2 = "SELECT facility_name, COUNT(facility_name) AS count, booking_date
					FROM booking
					WHERE facility_name = '$name_array2[$k]'
					GROUP BY facility_name, booking_date";

			$result_date2 = $db_conn->query($query_date2);

			$num_results_date2 = $result_date2->num_rows;

			for ($i=0; $i <$num_results_date2; $i++) {
				$row_date2 = $result_date2->fetch_assoc();
				$datax2[]=strtotime($row_date2['booking_date']);
				$datay2[]=(int) $row_date2['count'];
				
			}
			// echo "after one facility ".$k."<br>";
			// var_dump($datax);
			// echo "<br>";
			// var_dump($datay);

			$line2[] = new LinePlot($datay2,$datax2);
		}		 
		 
		for ($m=0; $m <sizeof($line2); $m++){
				 $graph_line2->Add($line2[$m]);
				 $color = substr(md5(rand()), 0, 6); //Random generate colors
				 $line2[$m] -> SetWeight ( 1.5 ); 
				 $line2[$m] -> SetColor ( '#'.$color );
				 $line2[$m] -> mark->SetType(MARK_UTRIANGLE);
				$line2[$m] -> mark->SetColor('#'.$color);
				$line2[$m] -> mark->SetFillColor('#'.$color);

			$result_getAlias3 = $db_conn->query("SELECT alias FROM item WHERE facility_name = '".$name_array2[$m]."'");	
			$row_alias3 = $result_getAlias3->fetch_assoc();	

		    $alias = $row_alias3['alias'];
			
			$line2[$m] -> SetLegend($alias);
		} 
		// $graph_line->Stroke();


		//Generate Pie Graph
		$query2 = "SELECT facility_name, COUNT(facility_name) AS count
					FROM booking
					GROUP BY facility_name";

		$result2 = $db_conn->query($query2);

		$num_results2 = $result2->num_rows;

		for ($i=0; $i <$num_results2; $i++) {

			$row2 = $result2->fetch_assoc();	
			$result_getAlias2 = $db_conn->query("SELECT alias FROM item WHERE facility_name = '".$row2['facility_name']."'");	
			$row_alias2 = $result_getAlias2->fetch_assoc();	

		    $data_name_history[] = $row_alias2['alias'];
		    $data_count_history[] = (int) $row2['count'];
		    
		}

		$graph_pie2 = new PieGraph(500,400);
		$graph_pie2->SetShadow();
		 
		$graph_pie2->title->Set("Percentages of History Bookings");
		$graph_pie2->title->SetFont(FF_FONT1,FS_BOLD);
		 
		$p2 = new PiePlot($data_count_history);
		$p2->SetLegends($data_name_history);
		$p2->SetGuideLines(true,false);
		$p2->SetGuideLinesAdjust(1.1);

		$p2->SetLabelType(PIE_VALUE_PER);    
		$p2->value->Show();            
		$p2->value->SetFont(FF_ARIAL,FS_NORMAL,9);    
		$p2->value->SetFormat('%2.1f%%');    
		 
		$graph_pie2->Add($p2);
		// $graph_pie->Stroke();

		$graph_bar2 = new Graph(500,500);
		$graph_bar2->SetScale('textint');
		 
		// Add a drop shadow
		$graph_bar2->SetShadow();
		 
		// Adjust the margin a bit to make more room for titles
		$graph_bar2->SetMargin(40,30,20,200);
		 
		// Create a bar pot
		$bplot2 = new BarPlot($data_count_history);
		 
		// Adjust fill color
		$bplot2->SetFillColor('orange');
		$graph_bar2->Add($bplot2);
		 
		// Setup the titles
		$graph_bar2->title->Set("Booking Numbers for ".date('Y'));
		$graph_bar2->xaxis->SetTickLabels($data_name_history);
		$graph_bar2->xaxis->title->Set('Facility');
		$graph_bar2->xaxis->SetTitleMargin(50);//Margin of axis title to the axis
		$graph_bar2->xaxis->SetLabelAngle(90);
		$graph_bar2->yaxis->title->Set('Number');
		 
		$graph_bar2->title->SetFont(FF_FONT1,FS_BOLD);
		$graph_bar2->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
		$graph_bar2->xaxis->title->SetFont(FF_FONT1,FS_BOLD);



	////////////////////////////////////////////////////////////
	//Generate graph for external user registration
    ////////////////////////////////////////////////////////////

   		//Generate Linear Graph
		// Create the new graph
			$graph_line3 = new Graph(540,500);
			 
			// Slightly larger than normal margins at the bottom to have room for
			// the x-axis labels
			$graph_line3->SetMargin(40,40,30,230);
			 
			// Fix the Y-scale to go between [0,100] and use date for the x-axis
			$graph_line3->SetScale('datint',0,50);
			$graph_line3->title->Set("Numbers of external user registration");
			 
			// Set the angle for the labels to 90 degrees
			$graph_line3->xaxis->SetLabelAngle(90);
			// Adjust the start time for a day
			$graph_line3->xaxis->scale->SetDateAlign(YEARADJ_1);
			 
			// Force labels to only be displayed every one month
			$graph_line3->xaxis->scale->ticks->Set(24*3600*32);
			 
			// Use hour:minute format for the labels
			$graph_line3->xaxis->scale->SetDateFormat('Y/m');

			$graph_line3->img->SetAntiAliasing(false); 

			$graph_line3->title->SetFont(FF_FONT1,FS_BOLD);

			$datay3 = array();
 			$datax3 = array();

			$query_date3 = "SELECT COUNT(username) AS count, registration_date
					FROM normal_user
					GROUP BY registration_date";

			$result_date3 = $db_conn->query($query_date3);

			$num_results_date3 = $result_date3->num_rows;

			for ($i=0; $i <$num_results_date3; $i++) {
				$row_date3 = $result_date3->fetch_assoc();
				$datax3[]=strtotime($row_date3['registration_date']);
				$datay3[]=(int) $row_date3['count'];
				
			}
			// echo "after one facility ".$k."<br>";
			// var_dump($datax);
			// echo "<br>";
			// var_dump($datay);

			$line3 = new LinePlot($datay3,$datax3);
		 
				 $graph_line3->Add($line3);
				 $color = substr(md5(rand()), 0, 6); //Random generate colors
				 $line3 -> SetWeight ( 1.5 ); 
				 $line3 -> SetColor ( '#'.$color );
				 $line3 -> mark->SetType(MARK_UTRIANGLE);
				 $line3 -> mark->SetColor('#'.$color);
				 $line3 -> mark->SetFillColor('#'.$color);

		// $graph_line->Stroke();


		//////////////////////////////////////////////////////////////////
		//Generate grouped bar graph for profit over the past year
		//////////////////////////////////////////////////////////////////

		$graph_profit = new Graph(540,500);
					 
		// Slightly larger than normal margins at the bottom to have room for
		// the x-axis labels
		$graph_profit->SetMargin(40,40,30,130);
		 
		// Fix the Y-scale to go between [0,100] and use date for the x-axis
		$graph_profit->SetScale('textlin',0,5000);

		$graph_profit->img->SetAntiAliasing(false); 

		$graph_profit->title->SetFont(FF_FONT1,FS_BOLD);

		$graph_profit->xaxis->SetTickLabels($gDateLocale->GetShortMonth());

		$datay_profit_external = array();
		// $datax_profit = array();

		$query_date_profit_external = "SELECT SUM(total_price) AS profit, month(booking_date) AS booking_month
										, year(booking_date) AS booking_year
										FROM booking
										WHERE booking_date > DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND user_identity = 'external'
										GROUP BY month(booking_date), year(booking_date)";

		$result_date_profit = $db_conn->query($query_date_profit_external);

		$num_results_date_profit = $result_date_profit->num_rows;

		$row_date_profit = $result_date_profit->fetch_assoc();

		$graph_profit->title->Set("Profit Distribution for ".date("Y")." in SGD"); 

		for ($i=0; $i <$num_results_date_profit; $i++) {

						// var_dump($row_date_profit);
						// $datax_profit[]=strtotime("01-".$row_date_profit['booking_month']."-".$row_date_profit['booking_year']);
						$datay_profit_external[$row_date_profit['booking_month']-1]=(float) $row_date_profit['profit'];
						if($row_date_profit['booking_year'] != date("Y")){
							//If the current row is not for current year, set to the y axis amount to zero
							$datay_profit_external[$row_date_profit['booking_month']-1] = 0.0;
						}
						$row_date_profit = $result_date_profit->fetch_assoc();
						
		}

		// var_dump($datax_profit);
		// var_dump($datay_profit_external);
		for($n=0; $n<12; $n++){
			if(!isset($datay_profit_external[$n])){
				$datay_profit_external[$n] = 0.0;
			}
		}

		// Create the bar plots
		$b1plot_external = new  BarPlot ($datay_profit_external);





		$datay_profit_internal = array();
		// $datax_profit = array();

		$query_date_profit_internal = "SELECT SUM(total_price) AS profit, month(booking_date) AS booking_month
										, year(booking_date) AS booking_year
										FROM booking
										WHERE booking_date > DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND user_identity = 'internal'
										GROUP BY month(booking_date), year(booking_date)";

		$result_date_profit_internal = $db_conn->query($query_date_profit_internal);

		$num_results_date_profit_internal = $result_date_profit_internal->num_rows;

		for ($i=0; $i <$num_results_date_profit_internal; $i++) {
						$row_date_profit_internal = $result_date_profit_internal->fetch_assoc();
						// var_dump($row_date_profit_internal);
						// $datax_profit[]=strtotime("01-".$row_date_profit_internal['booking_month']."-".$row_date_profit_internal['booking_year']);
						$datay_profit_internal[$row_date_profit_internal['booking_month']-1]=(float) $row_date_profit_internal['profit'];
						if($row_date_profit_internal['booking_year'] != date("Y")){
							//If the current row is not for current year, set to the y axis amount to zero
							$datay_profit_internal[$row_date_profit_internal['booking_month']-1] = 0.0;
						}
						
		}

		// var_dump($datax_profit);
		// var_dump($datay_profit_internal);
		for($n=0; $n<12; $n++){
			if(!isset($datay_profit_internal[$n])){
				$datay_profit_internal[$n] = 0.0;
			}
		}

		// Create the bar plots
		$b1plot_internal = new  BarPlot ($datay_profit_internal);

		$b1plot_internal->SetLegend("internal");
		$b1plot_external->SetLegend("external");
		 
		// // Create the grouped bar plot
		$gbplot = new  GroupBarPlot (array( $b1plot_external , $b1plot_internal ));
		$gbplot->SetWidth(0.6);

		// $graph_profit->yaxis->title->Set('SGD$');
		// $graph_profit->yaxis->SetTitleMargin(50);//Margin of axis title to the axis
		$graph_profit->title->SetFont(FF_FONT1,FS_BOLD);
		$graph_profit->yaxis->title->SetFont(FF_FONT1,FS_BOLD);

		$graph_profit->Add($gbplot);

		// $graph_profit->Stroke();



		$combine = new MGraph();
		$combine->SetMargin(2,2,2,2);
		$combine->Add($graph_bar);
		$combine->Add($graph_pie,500,0);
		$combine->Add($graph_line,1000,0);
		$combine->Add($graph_bar2,0,600);
		$combine->Add($graph_pie2,500,600);
		$combine->Add($graph_line2,1000,600);
		$combine->Add($graph_line3,0,1200);
		$combine->Add($graph_profit,600,1200);
		$combine->Stroke('../attachment/monthly_report/'.date('Y_M').'.png');


		


		$pdf = new FPDI();
		$pdf->addPage();
		$pdf->SetFont('Helvetica');
		$pdf->SetFontSize(10);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY(10, 10);
		$pdf->Write(5, 'Monthly Report for '.date('M Y'));
		$pdf->Image('../attachment/monthly_report/'.date('Y_M').'.png',10,20,200,230);
		$pdf->Output('../attachment/monthly_report/Monthly_Report_'.date('Y_M').'.pdf', 'F');


	//create a boundary string. It must be unique 
	//so we use the MD5 algorithm to generate a random hash 
	$random_hash = md5(date('r', time())); 

	//define the headers we want passed. Note that they are separated with \r\n 
	$headers = "From: Centre for Optical Fibre Technology"; 

	//add boundary string and mime type specification 
	$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 

	$htmlbody = "
Dear Administrator,

Monthly report for ".date('Y M')." is generated and attached.


Regards,
COFT Office

This is an automatically generated confirmation email. Please do not reply directly.";

	//read the atachment file contents into a string,
	//encode it with MIME base64,
	//and split it into smaller chunks
	$attachment = chunk_split(base64_encode(file_get_contents('../attachment/monthly_report/Monthly_Report_'.date('Y_M').'.pdf'))); 

	//define the body of the message.
	$message = "--PHP-mixed-$random_hash\r\n"."Content-Type: multipart/alternative; boundary=\"PHP-alt-$random_hash\"\r\n\r\n";
	$message .= "--PHP-alt-$random_hash\r\n"."Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";


	//Insert the html message.
	$message .= $htmlbody;
	$message .="\r\n\r\n--PHP-alt-$random_hash--\r\n\r\n";

	//include attachment
	$message .= "--PHP-mixed-$random_hash\r\n"
	."Content-Type: application/zip; name=\"Monthly_Report_".date('Y_M').".pdf\"\r\n"
	."Content-Transfer-Encoding: base64\r\n"
	."Content-Disposition: attachment\r\n\r\n";
	$message .= $attachment;
	$message .= "/r/n--PHP-mixed-$random_hash--";

	$query_admin = "SELECT * FROM admin_user";

	$result_admin = $db_conn->query($query_admin);

    if(!$result_admin){
	       echo '<script type="text/javascript">alert("Your query to retrieve admin information failed.");</script>';
	       exit;
    }

	$row_admin = $result_admin->fetch_assoc();
	$to = $row_admin['email'];
	// send email
    mail($to,"Monthly Report for COFT facilities",$message, $headers);


	$db_conn->close();

    ?>