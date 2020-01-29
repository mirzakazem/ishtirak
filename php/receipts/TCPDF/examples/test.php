<?php
//Our example date.
//The 4th of February 2014.
$month = '2019-12-01';


$year=substr($month,0,4);
$month=substr($month,5,2);
$month=(int)$month+1;// because php decrease string by 1, so i add 1;
if($month==13)// to avoid get value 13 to january
{
  $month="01";
  $year=(int)$year+1;
}
//$year=(string) $year;
$day="01";
 
//get the first day of the month
$from= date($year."-".$month."-".$day);
//Last date of current month.
$to = date("Y-m-t", strtotime($from));
 
echo $from." ".$to;
?>

