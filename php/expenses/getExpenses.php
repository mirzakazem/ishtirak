<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];
// Get the data

$search = json_decode(file_get_contents("php://input"));

$bar=($search->bar).'%';

if(empty($search->from)&&empty($search->to)){
  $sql = "
SELECT id, title, value, note, date
FROM expenses 
where userID = $userID and  active = 1 and title like '$bar';
 ";
 $from="both are empty";
}
  elseif (!empty($search->from)&&empty($search->to))
  {
    $from=$search->from;
    $sql = "
  SELECT id, title, value, note, date
  FROM expenses 
  where userID = $userID and  active = 1 and date >='$from' and title like '$bar';
  ";

  }
  elseif (empty($search->from)&&!empty($search->to))
  {
    $to=$search->to;
    $sql = "
  SELECT id, title, value, note, date
  FROM expenses 
  where userID = $userID and  active = 1 and date <='$to' and title like '$bar';
  ";
  $from="to is filled";
  }
  elseif (!empty($search->from)&&!empty($search->to))
  {
    $from=$search->from;
    $to=$search->to;
    $sql = "
  SELECT id, title, value, note, date
  FROM expenses 
  where userID = $userID and  active = 1 and date >='$from' and date <='$to' and title like '$bar';
  ";
  $from="both are filled";
  }



if($result = mysqli_query($connect,$sql))
{
  $count = mysqli_num_rows($result);

  $counter = 0;
  $value=0;
  while($row = mysqli_fetch_assoc($result))
  {
      $expenses[$counter]['id']    = $row['id'];
      $expenses[$counter]['title']  = $row['title'];
      $expenses[$counter]['value']  = $row['value'];
      $expenses[$counter]['note']  = $row['note'];
      $expenses[$counter]['date']  = $row['date'];   
      
      $value= $value+$row['value'];
      $counter++;
  }
}

$data = array(
    'expenses'  => $expenses,
    'total' => $count,
    'from'=>$from,
    'value'=>$value
   );

$output = json_encode($data);


echo $output;


mysqli_close($connect);
exit;