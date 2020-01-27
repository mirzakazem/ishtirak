<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];
// Get the data


$sql = "

  SELECT  counters.id, counters.customerID, counters.ampere, counters.box, counters.order, counters.createdOn,counters.isCounter, counters.initialValue,counters.disabled, customers.fullName, customers.phone , customers.address
  FROM counters
  inner join customers
  on counters.customerid=customers.id
  where counters.userID = '$userID' and customers.active = 1   
  order by  createdOn DESC
;
 ";

if($result = mysqli_query($connect,$sql))
{
  $count = mysqli_num_rows($result);

  $counter = 0;
  while($row = mysqli_fetch_assoc($result))
  {
      $counters[$counter]['id']    = $row['id'];
      $counters[$counter]['customerID']  = $row['customerID'];
      $counters[$counter]['ampere']  = $row['ampere'];
      $counters[$counter]['box']  = $row['box'];
      $counters[$counter]['order']  = $row['order'];
      $counters[$counter]['isCounter']  = $row['isCounter'];
      $counters[$counter]['initialValue']  = $row['initialValue'];

      
      if($row['disabled']==0)// knowing that the variable named disabled, but the algo work as checking if "Active" to not confuse the end-user
      {
        $counters[$counter]['disabled']='yes';
        $counters[$counter]['btnClass']='success';
      }
      else
      {
        $counters[$counter]['disabled']='no';
        $counters[$counter]['btnClass']='secondary';  
      }

      $counters[$counter]['address']  = $row['address'];
      $counters[$counter]['createdOn']  = $row['createdOn'];
      $counters[$counter]['fullName']  = $row['fullName'];
    
      $counter++;
  }
}

$data = array(
    'counters'  => $counters,
    'total' => $count
   );

$output = json_encode($data);
echo $output;
mysqli_close($connect);
exit;