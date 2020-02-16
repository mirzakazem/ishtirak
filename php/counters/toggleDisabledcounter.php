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
//update counter by it's id.
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
        $feedback='Status has been changed';
    }

$feedbackClass = 'success';
}


$output = array(
    'feedback' => $feedback,
    'feedbackClass' => $feedbackClass,
   );
   
   echo json_encode($output);