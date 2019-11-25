<?php
error_reporting(0);


/***
Change Currencies from Number to Word.
***/
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




//Give Proper Format of Numbers Or Currencies
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


if(isset($_GET["id"]) && !empty($_GET["id"])){
	
 $currency_format = moneyFormatIndia(100000);
 $number_to_word = convertToIndianCurrency(100000);
 
	echo '$ '.$currency_format.'is in word as'.$number_to_word.;

}
?>
