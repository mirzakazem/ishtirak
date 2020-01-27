<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];
// Get the data


$sql = "

SELECT email, phone, stationName, address
FROM users 
where ID = $userID and active = 1 ;
 ";

if($result = mysqli_query($connect,$sql))
{
  $count = mysqli_num_rows($result);

  $counter = 0;
  while($row = mysqli_fetch_assoc($result))
  {
      $profile[$counter]['email']    = $row['email'];
      $profile[$counter]['phone']  = $row['phone'];
      $profile[$counter]['stationName']  = $row['stationName'];
      $profile[$counter]['address']  = $row['address'];   
     
      $counter++;
  }
}

$data = array(
    'profile'  => $profile
   );

$output = json_encode($data);

echo $output;

mysqli_close($connect);
exit;