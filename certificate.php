<?php
error_reporting(0);


/**
*
Change Money format from Number to Word.
*
**/
function convertToIndianCurrency($number) {
 $no = round($number);
 $decimal = round($number - ($no = floor($number)), 2) * 100;    
 $digits_length = strlen($no);    
 $i = 0;
 $str = array();
 $words = array(
  0 => '',
  1 => 'One',
  2 => 'Two',
  3 => 'Three',
  4 => 'Four',
  5 => 'Five',
  6 => 'Six',
  7 => 'Seven',
  8 => 'Eight',
  9 => 'Nine',
  10 => 'Ten',
  11 => 'Eleven',
  12 => 'Twelve',
  13 => 'Thirteen',
  14 => 'Fourteen',
  15 => 'Fifteen',
  16 => 'Sixteen',
  17 => 'Seventeen',
  18 => 'Eighteen',
  19 => 'Nineteen',
  20 => 'Twenty',
  30 => 'Thirty',
  40 => 'Forty',
  50 => 'Fifty',
  60 => 'Sixty',
  70 => 'Seventy',
  80 => 'Eighty',
  90 => 'Ninety');
 $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
 while ($i < $digits_length) {
  $divider = ($i == 2) ? 10 : 100;
  $number = floor($no % $divider);
  $no = floor($no / $divider);
  $i += $divider == 10 ? 1 : 2;
  if ($number) {
   $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
   $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
 } else {
   $str [] = null;
 }  
}

$Rupees = implode('', array_reverse($str));
$paise = ($decimal) ? "And Paise" . ($words[$decimal - $decimal%10]) ."" .($words[$decimal%10])  : '';
return ($Rupees ? ' Rupees ' .$Rupees: '').$paise." Only";
}





function moneyFormatIndia($num) {
	$explrestunits = "" ;
	if(strlen($num)>3) {
		$lastthree = substr($num, strlen($num)-3, strlen($num));
$restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
$expunit = str_split($restunits, 2);
for($i=0; $i<sizeof($expunit); $i++) {
// creates each of the 2's group and adds a comma to the end
	if($i==0) {
$explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
} else {
	$explrestunits .= $expunit[$i].",";
}
}
$thecash = $explrestunits.$lastthree;
} else {
	$thecash = $num;
}
return $thecash; // writes the final format where $currency is the currency symbol.
}






function certificate_name($inv_id){
 require("./../connection.php");
 $s = "SELECT * FROM individual_investor_details WHERE individual_investor_id='".$inv_id."' LIMIT 1";
 $r= mysqli_query($conn, $s);

 if (mysqli_num_rows($r) == 1) {
  $row_1=mysqli_fetch_assoc($r);

  $name = $row_1["i_first_name"].' '.$row_1["i_middle_name"].' '.$row_1["i_last_name"];
}
return $name;
}



function get_scheme($id){

  require("./../connection.php");

    $sql = "SELECT * FROM investment_scheme WHERE scheme_id='".$id."' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

     $row=mysqli_fetch_assoc($result);
     $days = $row["tenure_in_days"];
   //  print_r($row);
   }
 
 return $days;
}





if(isset($_GET["inv_id"]) && !empty($_GET["inv_id"])){

require('code128.php');

//To Generate Barcode
$code = $_GET["certificate_number"];

$pdf = new PDF_Code128();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
//$pdf->Image('./tutorial/certificate.jpg',5,6,200);


$pdf->cell(86,60,$code,0,0,'C');

// //Cerificate Barcode
$pdf->Code128(85,30,$code,98,5,'C');

// //Certificate Number
$pdf->Cell(90,56,$code,0,0,'C');

// //Name on Certificate
$pdf->Ln(2);
$name = certificate_name($_GET["inv_id"]);
$pdf->SetFont('Times','',14);
$pdf->Cell(190,98,$name,0,0,'C');

// //Amount on Certificate
$pdf->Ln(12);
$pdf->SetFont('Times','',13);
$amount = moneyFormatIndia($_GET["payment_amount"]);

$word = ' ( '.convertToIndianCurrency($_GET["payment_amount"]).')';

$pdf->Cell(190,94,'Rs. '.$amount.$word,0,0,'C');

// //Payout Date on Certificate
//$payout_date = certificate_payout_date($_GET["inv_id"]);
//$tenure_days = certificate_tenure_date($_GET["inv_id"],$_GET["transaction_id"]);

// $pdf->Ln(13);
// $pdf->SetFont('Times','',13);
// $pdf->Cell(190,140,$payout_date.' ( '.$tenure_days.'th DAY FROM THE DATE OF INVESTMENT ).',0,0,'C');

$investment_date =  date("d-m-Y", strtotime($_GET["investment_date"]));
$pdf->Ln(13);
$pdf->SetFont('Times','',13);
$pdf->Cell(214,78,$investment_date,0,0,'C');

//Tenure days
$tenure_days = get_scheme($_GET['scheme_id']);
$payout_date =  date("d-m-Y", strtotime($_GET["payout_date"]));
$pdf->Ln(13);
$pdf->SetFont('Times','',13);
$pdf->Cell(196,110,$payout_date.' ( '.$tenure_days.'th DAY FROM THE DATE OF INVESTMENT ).',0,0,'C');

ob_start(); 
$pdf->Output();

}
?>
