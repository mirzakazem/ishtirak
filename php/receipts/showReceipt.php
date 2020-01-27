<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];
$validation_error = '';
// Get the status

$received = json_decode(file_get_contents("php://input"));

if(empty($received->receiptID)){
    $error[] = 'receipt ID not received';
}
else{
    $receiptID=$received->receiptID;
}


// emit the data
if(empty($error))
{
    $sql = "
  SELECT * from getreceipts

  where ID=$receiptID ; 
 ";

    if($result = mysqli_query($connect,$sql))
        {
            $row = mysqli_fetch_assoc($result);

            $receipt['id']    = $row['ID'];
            $receipt['fullName']    = $row['fullName'];
            $receipt['customerPhone']    = $row['customerPhone'];
            $receipt['boxOrder']    = $row['boxOrder'];
            $receipt['ampere']    = $row['ampere'];
            $receipt['month']    = $row['month'];
            $receipt['previousValue']    = $row['previousValue'];
            $receipt['newValue']    = $row['newValue'];
            $receipt['consumption']    = $row['consumption'];
            $receipt['KWPrice']    = $row['KWPrice'];
            $receipt['consumptionFees']    = $row['consumptionFees'];
            $receipt['fixedFees']    = $row['fixedfees'];
            $receipt['value']    = $row['value'];
            $receipt['createdOn']    = $row['createdOn'];

        }
        else{
            $error[] = 'query failed';
        }
}        
else
        {
        $validation_error = implode(" | ", $error);
        }

$data = array(
    'error'  => $validation_error,
    'receipt'=> $receipt
   );

$output = json_encode($data);


echo $output;


mysqli_close($connect);
exit;