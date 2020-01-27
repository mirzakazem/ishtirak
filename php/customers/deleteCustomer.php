<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];



//Delete record by id.
$id = json_decode(file_get_contents("php://input"));

    $sql = "DELETE FROM `customers` WHERE `id` = '$id' and userID = $userID LIMIT 1";

    mysqli_query($connect,$sql);

$message='Customer has been deleted';

$output = array(
    'message' => $message
   );
   
   echo json_encode($output);