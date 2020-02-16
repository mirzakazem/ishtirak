<?php
session_start();

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$message='';
$validation_error = '';


$userID=$_SESSION["ID"];

$userStatus=$_SESSION["expired"];
//check expiration status ------------
if($userStatus!=0){
    $error[] = 'user is expired';
}


// check counter ID *****************
if(empty($form_data->counterID)){
    $error[] = 'counter ID required';   
}
else{
    $counterID=$form_data->counterID;
}

$valueID = $form_data->valueID;
// check counter ID *******************

//new value validation -----------------------------
if(empty($form_data->addValue) || !isset($form_data->addValue))
{
    $error[] = 'new value is Required';
    
}
elseif(!is_numeric($form_data->addValue))
        {
            $error[] = 'invalid new value format';
        }
        else{
            $new = $form_data->addValue;
            }
//new value validation ******************************



// date validation ---------------------------------
if(empty($form_data->month))
{
    $error[] = 'date is Required';
    
}
else {
$year=substr($form_data->month,0,4);
$month=substr($form_data->month,5,2);
$month=(int)$month+1;// because php decrease string by 1, so i add 1;
if($month==13)// to avoid get value 13 to january
{
  $month=1;
  $year=(int)$year+1;
}

$day="01";

$month= $year."-".$month."-".$day;
}
// date validation***************************** 


// get the real date -------------------------------

if(empty($error)){

    $sql="
    SELECT `month` FROM `countersvalues` WHERE ID='$valueID'
    ";
    $result = mysqli_query($connect,$sql);
    
        $row = mysqli_fetch_assoc($result);
        $DBDate = $row['month'];
    
}
// get the real date ***************************************

// validate given data, cehck if the update will conflict with the next values ---------------------

if(empty($error))
{
    $sql="
    SELECT `ID`,`newValue`, `month` FROM `countersvalues` WHERE `counterID`='$counterID' and `month`>'$DBDate' order by `createdOn` asc limit 1
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
            $error[] = " can't updated value has next value"; 
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
// validate given data, cehck if the update will conflict with the next values ****************


// validate given data, cehck if the update will conflict with the last values ---------------
if(empty($error))
{
    $sql="
    SELECT `ID`,`newValue`, `month` FROM `countersvalues` WHERE `counterID`='$counterID' and month < '$DBDate' order by `createdOn` desc limit 1
    ";
    if($result = mysqli_query($connect,$sql))
    {   
        $row = mysqli_fetch_assoc($result);

        $DBLastNewValue = $row['newValue'];
        $DBLastmonth= $row['month'];
        $DBLastID= $row['ID'];

        $lastCount = mysqli_num_rows($result);

        if($lastCount>0)
        {
            if($DBLastNewValue>=$new)
            {
            $error[] = 'new value conflicted with Last value, check value '.$DBLastID;
            }
      
            if(empty($error))
            {   
                if($DBLastmonth>=$month)
                {
                $error[] = 'new Date conflicted with Last date, check value '.$DBLastID;
                }
            }
        }  
    }
}
// validate given data, cehck if the update will conflict with the last values *************************


// insert the data --------------------------------------------------------------------

if(empty($error))
{
    $feedbackClass='success';
    $consumption=$new-$DBLastNewValue;
    $sql= "
    UPDATE `countersvalues` SET `newValue`='$new', `consumption`='$consumption', `month`='$month', `issued`='0' where ID='$valueID'
        ";
        if (mysqli_query($connect, $sql)) 
        {
        $feedback = 'value has been updated';
        } 
         
        $sql= "
        DELETE FROM `receipts` WHERE valueID='$valueID'
        ";
        mysqli_query($connect, $sql); 
        
 // insert the data *****************************************************************       
                  
}
else{
    $feedbackClass='danger';    
    $feedback = implode(", ", $error);
}

      

$output = array(
    'feedback' => $feedback,
    'feedbackClass' => $feedbackClass,
);

echo json_encode($output);

?>
