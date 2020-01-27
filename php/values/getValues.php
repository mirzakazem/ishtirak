<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];
// Get the data


$sql = "

      SELECT * FROM `getvalues` WHERE `userID`='$userID' order by month DESC, boxorder DESC
      ";

if($result = mysqli_query($connect,$sql))
{
  $count = mysqli_num_rows($result);

  $counter = 0;
  while($row = mysqli_fetch_assoc($result))
  {
      $values[$counter]['id']    = $row['id'];
      $values[$counter]['counterID']    = $row['counterID'];
      $values[$counter]['boxOrder']  = $row['boxOrder'];
      $values[$counter]['fullName']  = $row['fullName'];
      $values[$counter]['previous']  = $row['previousValue'];
      $values[$counter]['new']  = $row['newValue'];
      $values[$counter]['consumption']  = $row['consumption'];
      $values[$counter]['month']  = $row['month'];
      $values[$counter]['issued']  = $row['issued'];
    
      $counter++;
  }
}

$data = array(
    'values'  => $values,
    'total' => $count
   );

$output = json_encode($data);
echo $output;
mysqli_close($connect);
exit;