<?php
session_start();

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$message='';
$validation_error = '';
$userID= $_SESSION["ID"];


// KWPrice value validation
if(empty($form_data->KWPrice))
    {
    $error[] = 'Kilo whatt Price is Required';
    }
    elseif (!is_numeric($form_data->KWPrice))
        {
            $error[] = 'KWPrice ampere must be number';
        }
         
        else{
            $KWPrice = htmlspecialchars($form_data->KWPrice);
            }

// A5 value validation
if(empty($form_data->A5))
    {
    $error[] = '5 ampere value is Required';
    }
    elseif (!is_numeric($form_data->A5))
        {
            $error[] = '5 ampere must be number';
        }
         
        else{
            $A5 = htmlspecialchars($form_data->A5);
            }

// A10 value validation
if(empty($form_data->A10))
    {
    $error[] = '10 ampere value is Required';
    }
    elseif (!is_numeric($form_data->A10))
        {
            $error[] = '10 ampere must be number';
        }
         
        else{
            $A10 = htmlspecialchars($form_data->A10);
            }

// A15 value validation
if(empty($form_data->A15))
    {
    $error[] = '15 ampere value is Required';
    }
    elseif (!is_numeric($form_data->A15))
        {
            $error[] = '15 ampere must be number';
        }
         
        else{
            $A15 = htmlspecialchars($form_data->A15);
            }

// A20 value validation
if(empty($form_data->A20))
    {
    $error[] = '20 ampere value is Required';
    }
    elseif (!is_numeric($form_data->A20))
        {
            $error[] = '20 ampere must be number';
        }
         
        else{
            $A20 = htmlspecialchars($form_data->A20);
            }

// month validation
if(!empty($form_data->month))
{
    
    $month =$form_data->month;

    $newYear=substr($month,0,4);
    $newmonth=substr($month,5,2)+1;
    $newday=substr($month,8,2);

    $nextMonth=$newYear."-".$newmonth."-".$newday;
}
else {
    $error[] = 'month is Required';
}            
           



// insert the data
if(empty($error))
{
        // get the non-issued values
    $sql = "

    INSERT INTO `receipts`(`counterID`, `valueID`, `userID`,`consumption`,`month`) 
    Select counterID, ID, userID, consumption, month
    From countersvalues
    where userID = $userID and active = 1 and issued=0 and month>= '$month' and month<'$nextMonth';

    
    ";

    if($result = mysqli_query($connect,$sql)){
        // set the got values as issued
        $sql = "

        UPDATE countersvalues
            INNER JOIN
            receipts ON countersvalues.ID = receipts.valueID 
            SET 
        issued = 1;";

        if($result = mysqli_query($connect,$sql))
        {
            // update ampere in receipts table
            $sql = "

            UPDATE receipts
            INNER JOIN
            counters ON counters.ID = receipts.counterID 
            SET receipts.ampere= counters.ampere
            ";  
          
            mysqli_query($connect,$sql); 

            // update KWPrice in receipts table
            $sql = "

            UPDATE receipts
                SET KWPrice=$KWPrice 
            where finilized=0;
            ";  
          
            mysqli_query($connect,$sql);   
            
            // update Fixed fees in receipts table
            $sql = "

            UPDATE receipts
                SET fixedFees=
            CASE
            WHEN ampere = 5 THEN $A5
            WHEN ampere = 10 THEN $A10
            WHEN ampere = 15 THEN $A15
            WHEN ampere = 20 THEN $A20 
            END
            WHERE finilized = 0;
            
            ";  
          
            mysqli_query($connect,$sql); 

            // update Value fees in receipts table
            $sql = "

            UPDATE receipts
                SET `Value` = (`KWPrice`*`consumption`)+`fixedFees` , finilized=1
            
            WHERE finilized = 0;
            
            ";  
            
            mysqli_query($connect,$sql); 
            
            
        $message= mysqli_affected_rows($connect)." new receipts has been issued";    
        }

    }
    

}
else
        {
        $validation_error = implode(" | ", $error);
        }

       
$output = array(
 'error'  => $validation_error,
 'message' => $message
);

echo json_encode($output);
//print_r($_POST);


/*
$startdate ="2019-04-01";
$year=substr($startdate,0,4);
$month=substr($startdate,5,2)+1;
$day=substr($startdate,8,2);

$nextdate=$year."-".$month."-".$day;
echo($nextdate);
*/
?>    