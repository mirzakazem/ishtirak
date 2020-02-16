<?php
session_start();

print_r($_SESSION);

//just a comment
$givenDate=date('2020-01-01');


$currentDate = date("Y-m-d");
$lastDayinMonth=date("Y-m-t", strtotime($currentDate));

echo"Given ".$givenDate." lastDy: ".$lastDayinMonth;

if($givenDate>$lastDayinMonth){
    echo"no future";
}
else{
    echo"ok";
}



?>