<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];



//Delete counter by it's id.
$id = json_decode(file_get_contents("php://input"));

// get it's disabled status

    $sql = "UPDATE `counters` SET `disabled`= 

    case
    
    when `disabled`=0 then 1
    when `disabled`=1 then 0
    END
    
    WHERE `ID`=$id
    ";

    if(mysqli_query($connect,$sql)){
        $message='Status has been changed';
    }



$output = array(
    'message' => $message
   );
   
   echo json_encode($output);