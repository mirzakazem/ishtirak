<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];
// Get the data

$text = json_decode(file_get_contents("php://input"));

$text=($text->customer);

$fullName = '%'.$text.'%';
$phone = '%'.$text.'%';


$sql = "

SELECT  id, fullName, phone
FROM customers 
where (userID = $userID and active = 1) and (fullName like '$fullName' or phone like '$phone') limit 5;
 ";

if($result = mysqli_query($connect,$sql))
{
  $count = mysqli_num_rows($result);

  $counter = 0;
  while($row = mysqli_fetch_assoc($result))
  {
      $customers[$counter]['id']  = $row['id'];
      $customers[$counter]['fullName']  = $row['fullName'];
      $customers[$counter]['phone']  = $row['phone']; 
 
      $counter++;
  }
}

$data = array(
    'customers'  => $customers,
    'fullName'  => $fullName,
    'phone'  => $phone,

    
   );

$output = json_encode($data);
echo $output;
mysqli_close($connect);
exit;