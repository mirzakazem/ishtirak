<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];
// Get the data


$sql = "

SELECT customers.*, count(counters.customerID) AS NumberOfCounters
FROM customers  
left join counters
on customers.ID= counters.customerID
where customers.userID = $userID and customers.active = 1 
group by customers.ID
order by customers.createdOn Desc;;
 ";

if($result = mysqli_query($connect,$sql))
{
  $count = mysqli_num_rows($result);

  $counter = 0;
  while($row = mysqli_fetch_assoc($result))
  {
      $customers[$counter]['id']    = $row['ID'];
      $customers[$counter]['fullName']  = $row['fullName'];
      $customers[$counter]['phone']  = $row['phone'];
      $customers[$counter]['address']  = $row['address'];   
      $customers[$counter]['email']  = $row['email'];
      $customers[$counter]['NumberOfCounters']  = $row['NumberOfCounters'];
      $customers[$counter]['createdOn']  = $row['createdOn'];
      $counter++;
  }
}

$data = array(
    'customers'  => $customers,
    'total' => $count
   );

$output = json_encode($data);
echo $output;
mysqli_close($connect);
exit;