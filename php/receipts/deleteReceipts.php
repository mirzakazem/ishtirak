<?php
session_start();

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$message='';
$validation_error = '';
$userID=$_SESSION["ID"];

//value validation

$IDs=$form_data->IDs;   
$array=implode(',', $IDs);

$sql="update countersvalues 
inner join receipts  on countersvalues.id=receipts.valueid
set countersvalues.issued=0
where receipts.id in ($array); ";

mysqli_query($connect,$sql);

$sql = "update `receipts`
        set active=0
        WHERE `ID` in  ($array)";
mysqli_query($connect,$sql);


$message= mysqli_affected_rows($connect).' receipts has been deleted';

 //$validation_error = implode(", ", $error);

 

$output = array(
 'error'  => $validation_error,
 'message' => $message,
 'form_data'=>$form_data
);

echo json_encode($output);


?>
