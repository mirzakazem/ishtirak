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
    $feedback = 'user is expired';
    $feedbackClass = 'danger';
}

if(empty($feedback))
{
//value validation
$value=$form_data->value;
$IDs=$form_data->IDs;   
$array=implode(',', $IDs);

if($value==1){
$sql = "UPDATE `receipts` SET`status`=1 WHERE `ID` in  ($array)";
}
elseif($value==0){
    $sql = "UPDATE `receipts` SET`status`=0 WHERE `ID` in  ($array)";
}

    mysqli_query($connect,$sql);


$feedback= mysqli_affected_rows($connect).' receipts has been updated';
$feedbackClass = 'success';
}


 

$output = array(
    'feedback' => $feedback,
    'feedbackClass' => $feedbackClass,
 'form_data'=>$form_data
);

echo json_encode($output);


?>
