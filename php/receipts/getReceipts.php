<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];

// Get the status

$search = json_decode(file_get_contents("php://input"));

if($search->status=="Paid"){
  $status=1;
}
elseif($search->status=="Unpaid"){
  $status=0;
}
else{ $status='%';}


if(empty($search->from)&&empty($search->to))//no date filters
{
  $sql = "
  SELECT * from getreceipts

  where userID = $userID and status like '$status'; 
 ";
}
  elseif (!empty($search->from)&&empty($search->to))//only from filled
  {
    $from=$search->from;
    $sql = "
    SELECT * from getreceipts
    where userID = $userID and status like '$status' and month >='$from'; 

  ";

  }
  elseif (empty($search->from)&&!empty($search->to))// only to filled
  {
    $to=$search->to;
    $sql = "
    SELECT * from getreceipts
    where userID = $userID and status like '$status' and month <='$to'; 
  ";
 
  }
  elseif (!empty($search->from)&&!empty($search->to))// status from and to are both filled
  {
    $from=$search->from;
    $to=$search->to;
    $sql = "
    SELECT * from getreceipts
    where userID = $userID and status like '$status' and month >='$from' and month <='$to'; 
  "; 
  
  }

if($result = mysqli_query($connect,$sql))
{
  $count = mysqli_num_rows($result);
  $unpaidTotal=0;
  $value=0;

  $counter = 0;
  
  while($row = mysqli_fetch_assoc($result))
  {
      $receipts[$counter]['id']    = $row['ID'];
      $receipts[$counter]['fullName']  = $row['fullName'];
      $receipts[$counter]['boxOrder']  = $row['boxOrder'];
      $receipts[$counter]['month']  = $row['month'];
      $receipts[$counter]['value']  = $row['value'];

      if($row['status']==0){
        $receipts[$counter]['status']="Unpaid";
        $unpaidTotal++;
       
      }   
      else{
        $receipts[$counter]['status']="Paid";
      }
      $value=$value+$row['value'];
      
      $counter++;
  }
}

$data = array(
    'receipts'  => $receipts,
    'unpaidTotal' => $unpaidTotal,
    'value'=>$value,
    'count'=>$count
   );

$output = json_encode($data);


echo $output;


mysqli_close($connect);
exit;