<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];
// Get the data


$sql = "

SELECT *
from users
where ID = $userID and active = 1

 ";

if($result = mysqli_query($connect,$sql))
{
  
  $row = mysqli_fetch_assoc($result);

      $user['expiryDate']    = $row['expiryDate'];
      if($row['expired']==0){
        $user['status']="Active until: ".$user['expiryDate'];
        $user['alertClass']='success';
      }
      else{
        $user['status']="Expired on: ".$user['expiryDate'];
        $user['alertClass']='danger';
      }

}


$data = array(
    'status'  => $user['status'],
    'expiryDate'=>$user['expiryDate'],
    'alertClass'=>$user['alertClass']
   );

$output = json_encode($data);
echo $output;
mysqli_close($connect);
exit;