<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];



//Delete record by id.
$id = json_decode(file_get_contents("php://input"));

    $sql = "DELETE FROM countersvalues WHERE id = '$id' LIMIT 1";
    mysqli_query($connect,$sql);

    $sql= "
    DELETE FROM `receipts` WHERE valueID='$id'
    ";
    mysqli_query($connect, $sql); 



$message='value id  '.$id.' has been deleted';

$output = array(
    'message' => $message
   );
   
   echo json_encode($output);