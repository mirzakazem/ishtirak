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

$IDs=$form_data->IDs;   
$array=implode(',', $IDs);

$sql="update countersvalues 
set countersvalues.issued=0
where countersvalues.id in ( select valueid from receipts where receipts.id in ($array));
 ";

mysqli_query($connect,$sql);

$sql = "update `receipts`
        set active=0
        WHERE `ID` in  ($array)";
mysqli_query($connect,$sql);


$feedback= mysqli_affected_rows($connect).' receipts has been deleted';
$feedbackClass = 'success';
}


$output = array(
        'feedback' => $feedback,
        'feedbackClass' => $feedbackClass,
 'form_data'=>$form_data
);

echo json_encode($output);


?>
