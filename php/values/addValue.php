<?php
session_start();

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$message='';
$validation_error = '';
$userID= $_SESSION["ID"];

$userStatus=$_SESSION["expired"];
//check expiration status ------------
if($userStatus!=0){
    $error[] = 'user is expired';
}
//********************************

// get the boxOrder

$boxOrder=$form_data->boxOrder;
//id validation ------------------------------------------------
 
$counterID="";

if(empty($form_data->counterID) || !isset($form_data->counterID))
{
    $error[]='invalid counter, select counter from list';
    
}
else{
    $counterID=$form_data->counterID;
}
//id validation  **************************************************

//new value validation ------------------------------------------
if(empty($form_data->addValue))
    {
    $error[] = 'new value for counter '.$boxOrder .' is Required';
    }
    elseif(!is_numeric($form_data->addValue))
        {
            $error[] = 'invalid new value format';
        }
        else{
            $new = $form_data->addValue;
            }

//new value validation *********************************************

// date validation ---------------------------------------------
if(empty($form_data->month))
{
    $error[] = 'date for counter'.$boxOrder .'is Required';
    
}
else {
    
$year=substr($form_data->month,0,4);
$month=substr($form_data->month,5,2);
$month=(int)$month+1;// because php decrease string by 1, so i add 1;
if($month==13)// to avoid get value 13 to january
{
  $month="01";
  $year=(int)$year+1;
}

$day="01";


$month= date($year."-".$month."-".$day);


}
// date validation ***************************************** 

// get the last day of the current month
$currentDate = date("Y-m-d");
$lastDayinMonth=date("Y-m-t", strtotime($currentDate));

if($month>$lastDayinMonth){
    $error[] = " can't insert data in future";
}

// check if the counter has value in the same month
if(empty($error))
    {
        $sql="
        SELECT * FROM `countersvalues` 
        WHERE `counterID`='$counterID' and month='$month' limit 1 "; 
        $result = mysqli_query($connect,$sql);
        $count = mysqli_num_rows($result);
        if($count>0)
        {
        $error[] = 'value already inserted '.$boxOrder;
        }
        
    }

//check if value for next months inserted, if so flag the error. (inserted value should be the most recent value)
if(empty($error)){
    $sql=" select * from countersValues where counterID='$counterID' and month>'$month'";
    $result = mysqli_query($connect,$sql);
    $row = mysqli_fetch_assoc($result);
    $count = mysqli_num_rows($result);
    if($count>0){ $error[] = 'this value is not the most recent Value';}
}    


// validate given data with the last value and date -------------------------------------------------------
if(empty($error))
{
    $sql="
    SELECT `newValue`, `month` FROM `countersvalues` WHERE `counterID`='$counterID'  and month<'$month' order by `createdOn` desc limit 1    ";
    if($result = mysqli_query($connect,$sql))
    {
        $count = mysqli_num_rows($result);

        $row = mysqli_fetch_assoc($result);

        $DBLastNewValue = $row['newValue'];
        $DBmonth= $row['month'];

        if($count==0){// check if this is the 1st value give the lastNewValue the value of the initialValue
            $sql=" SELECT initialValue FROM `counters` WHERE `ID`='$counterID' "  ; 
            $result = mysqli_query($connect,$sql);
            $row = mysqli_fetch_assoc($result);

        $DBLastNewValue = $row['initialValue'];

        }

        if($DBLastNewValue>=$new)
        {
          $error[] = 'invalid new value for counter '.$boxOrder;
        }
      
        if(empty($error))
        {   
            if($month<=$DBmonth)
            {
            $error[] = 'invalid Date for counter '.$boxOrder;
            }
        }  
    }
}
// validate given data with the last value and date ************************************

// validate given data, cehck if the update will conflict with the next values ---------------------
/*
if(empty($error))
{
    $sql="
    SELECT `ID`,`newValue`, `month` FROM `countersvalues` WHERE `counterID`='$counterID' and `month`>'$month' order by `createdOn` asc limit 1
    ";
    if($result = mysqli_query($connect,$sql))
    {   
        $row = mysqli_fetch_assoc($result);

        $DBNextNewValue = $row['newValue'];
        $DBNextmonth= $row['month'];
        $DBNextID= $row['ID'];

        $nextCount = mysqli_num_rows($result);
        if($nextCount>0)
        {
        {
        
        

        if($DBNextNewValue<=$new)
        {
          $error[] = 'new value conflicted with next value, check value '.$DBNextID;
        }
      
        if(empty($error))
        {   
            if($DBNextmonth<=$month)
            {
            $error[] = 'new Date conflicted with next date, check value '.$DBNextID;
            }
        }
         } 
    }
}
}
*/
// validate given data, cehck if the update will conflict with the next values ****************

// insert the data -------------------------------------------------

if(empty($error))
{
    $feedbackClass='success';
    $consumption=$new-$DBLastNewValue;    
    $sql= "
        INSERT INTO countersvalues (counterID, userID, previousValue, newValue, consumption, month) 
        VALUES ('$counterID', '$userID' , '$DBLastNewValue' , '$new', '$consumption', '$month')
        ";
        if (mysqli_query($connect, $sql)) 
        {
        $feedback = 'new value has been added for '.$boxOrder;
        
        }   
   // insert the data *****************************************               
}
else
        {
            $feedbackClass='danger';    
            $feedback = implode(", ", $error);
        }
        



$output = array(
    'feedback' => $feedback,
 'feedbackClass' => $feedbackClass,
    'id'=>$counterID,
    'month'=>$month,
    'lasDay'=>$lastDayinMonth
);
   
echo json_encode($output);
//echo json_encode($form_data);

?>
