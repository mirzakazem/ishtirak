<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];


// Get  the data and assign variables

$date = json_decode(file_get_contents("php://input"));

$date=substr($date,0,10);


$year=substr($date,0,4);
$month=substr($date,5,2);
$month=(int)$month+1;// because php decrease string by 1, so i add 1;
if($month==13)// to avoid get value 13 to january
{
  $month=1;
  $year=(int)$year+1;
}

$day="01";

$mydate= $year."-".$month."-".$day;

$lastDayOfTheMonth=date("Y-m-t", strtotime($mydate));



// get and assign *********************************************

// search and assign results for variable ----------------------------
$sql = "
select countersboxorder.*, countersvalues.newValue, countersvalues.`ID` as valueID, countersvalues.`month` 
from countersboxorder left outer join (select * from countersvalues where countersvalues.month>='$mydate' 
and countersvalues.month<='$lastDayOfTheMonth' ) as countersvalues on countersboxorder.ID= countersvalues.`counterID` 
where countersboxorder.userID = '$userID' and countersboxorder.isCounter='yes' and countersboxorder.disabled=0 order by countersboxorder.boxOrder
";

if($result = mysqli_query($connect,$sql))
{
  $count = mysqli_num_rows($result);

  $counter = 0;
  while($row = mysqli_fetch_assoc($result))
  {
      $counters[$counter]['id']  = $row['id'];
      $counters[$counter]['boxOrder']  = $row['boxOrder'];
      $counters[$counter]['fullName']  = $row['fullName'];
      $counters[$counter]['createdOn']  = $row['createdOn'];
      $counters[$counter]['newValue']  = $row['newValue'];
      $counters[$counter]['valueID']  = $row['valueID'];

      if($row['disabled']==1){
        $counters[$counter]['disabled']='true';
      }
      else{
        $counters[$counter]['disabled']='false';
      }


      $counter++;
  }
}

$data = array(
    'date' => $mydate,
    'year' => $year,
    'month' => $month,
    'day' => $day,
    'counters'=>$counters
   );

$output = json_encode($data);
echo $output;
mysqli_close($connect);
exit;
