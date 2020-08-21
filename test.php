<?php
session_start();

print_r($_SESSION);

//just a comment
$givenDate=date("Y-m-d", strtotime('2020-02-029'));


$currentDate = date("Y-m-d");
$lastDayinMonth=date("Y-m-t", strtotime($currentDate));

echo"Given ".$givenDate." currentDate: ".$lastDayinMonth;

if($currentDate>$givenDate){
    echo"expire";
}
else{
    echo"active";
}

echo"just for testing";


?>
