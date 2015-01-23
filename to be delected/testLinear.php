<?php
//testLinear.php
require_once ('../jpgraph/src/jpgraph.php');
require_once ('../jpgraph/src/jpgraph_bar.php');
require_once ('../jpgraph/src/jpgraph_date.php');

$db_conn = new mysqli('localhost', 'root', '', 'coft');
 
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

$graph_profit->title->Set("Profit Distribution for ".$row_date_profit['booking_year']); 

for ($i=0; $i <$num_results_date_profit; $i++) {

				// var_dump($row_date_profit);
				// $datax_profit[]=strtotime("01-".$row_date_profit['booking_month']."-".$row_date_profit['booking_year']);
				$datay_profit_external[$row_date_profit['booking_month']-1]=(float) $row_date_profit['profit'];
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

$graph_profit->Add($gbplot);

$graph_profit->Stroke();


?>