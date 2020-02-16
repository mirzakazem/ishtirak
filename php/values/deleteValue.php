<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];

$userStatus=$_SESSION["expired"];
//check expiration status ------------
if($userStatus!=0){
    $feedback = 'user is expired';
    $feedbackClass = 'danger';
}

if(empty($feedback))
{
//Delete record by id.
$id = json_decode(file_get_contents("php://input"));

    $sql = "DELETE FROM countersvalues WHERE id = '$id' LIMIT 1";
    mysqli_query($connect,$sql);

    $sql= "
    DELETE FROM `receipts` WHERE valueID='$id'
    ";
    mysqli_query($connect, $sql); 



$feedback='value id  '.$id.' has been deleted';
$feedbackClass = 'success';
}

$output = array(
    'feedback' => $feedback,
    'feedbackClass' => $feedbackClass
   );
   
   echo json_encode($output);