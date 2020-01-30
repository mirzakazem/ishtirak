<?php

session_start();
$userID= $_SESSION["ID"];

$form_data = json_decode(file_get_contents("php://input"));

//validate and clean month
$from=$form_data->month;
//get the last day of the month
$to=date("Y-m-t", strtotime($from));

//check whether it's local or online server
$ip_server = $_SERVER['SERVER_ADDR']; 


if($ip_server =='160.153.133.148')
    {// online
    include('../../../database_connection.php');
    $userDirectory='/home/smp0h3cnvy4h/public_html/documents/'.$userID;
    
    
    //check whether the user folder existed, if not create it
    if (file_exists($userDirectory)) {
        echo "file $userDirectory exists";
    } else {
        mkdir($userDirectory, 0777 );
        if (file_exists($userDirectory)) {echo "Directory $userDirectory created successfully";}
        
    }

    //check whether the receipts folder existed, if not create it
    if (file_exists($userDirectory."/receipts")) {
        echo "file existed $userDirectory.'/receipts'";
    } else {
        mkdir($userDirectory."/receipts", 0777 );
        if (file_exists($userDirectory."/receipts")) {echo " Directory $userDirectory/receipts created successfully";}
        
    }
    }

else{//local
    include('../../../database_connection.php');
    $userDirectory='C:/wamp/www/Ishtirak/code/documents/'.$userID;

    //check whether the user folder existed, if not create it
    if (file_exists($userDirectory)) {
        echo "file $userDirectory exists";
    } else {
        mkdir($userDirectory, 0777 );
        if (file_exists($userDirectory)) {echo "Directory $userDirectory created successfully";}
        
    }

    //check whether the receipts folder existed, if not create it
    if (file_exists($userDirectory."/receipts")) {
        echo "file existed $userDirectory.'/receipts'";
    } else {
        mkdir($userDirectory."/receipts", 0777 );
        if (file_exists($userDirectory."/receipts")) {echo " Directory $userDirectory/receipts created successfully";}
        
    }
}

//get user pernal info
$sql = "
    SELECT * from users
    where ID = $userID 
  "; 
 
 if($result = mysqli_query($connect,$sql))
{
  $row = mysqli_fetch_assoc($result);
  
    $stationName = $row['stationName'];
    $phone    = $row['phone'];

}

//get receipt info

 $sql = "
    SELECT * from getreceipts
    where userID = $userID and month >='$from' and month <='$to'
    ORDER by createdOn DESC; 
  "; 
 
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
      $receipts[$counter]['customerPhone']  = $row['customerPhone'];
      $receipts[$counter]['ampere']  = $row['ampere'];
      $receipts[$counter]['month']  =substr($row['month'],0,7);
      $receipts[$counter]['consumption']  = $row['month'];
      $receipts[$counter]['KWPrice']  = $row['KWPrice'];
      $receipts[$counter]['previousValue']  = $row['previousValue'];
      $receipts[$counter]['newValue']  = $row['newValue'];
      $receipts[$counter]['consumption']  = $row['consumption'];
      $receipts[$counter]['fixedfees']  = $row['fixedfees'];
      $receipts[$counter]['value']  = $row['value'];
      $receipts[$counter]['createdOn']  = substr($row['createdOn'],0,11);
      
      $counter++;
  }
}

 

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


//$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(5);
//$pdf->SetRightMargin(0);
$pdf->SetFooterMargin(0);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'fa';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);

// ---------------------------------------------------------

// add a page
$pdf->AddPage();
$pdf->SetAutoPageBreak(TRUE, 0);

$pdf->SetFontSize(10);
// Restore RTL direction
$pdf->setRTL(true);

// set font
$pdf->SetFont('aefurat', '', 12);

// print newline
$pdf->Ln();



for ($x=0;$x<count($receipts);$x++){
  
  if($x!=0 && $x%5==0){$pdf->AddPage();}
  
$htmlcontent =
'
<style>
table{cellpadding:0, cellspacing:0;}
table,td{border: 1px solid black;}
</style>
<table>
    <tr>
    <th colspan="6" style="text-align:center;">'.$stationName.' </th>
    </tr>
    <br>
    <tr>
        <td  colspan="2" style="text-align:center;">الاسم </td>
        <td>الهاتف </td>
        <td>رقم العداد </td>
        <td>عدد الامبيرات </td>
        <td>عن شهر</td>
    </tr>
    
    <tr>
        <td  colspan="2">'.$receipts[$x]['fullName'].'</td>
        <td>'.$receipts[$x]['customerPhone'].' </td>
        <td>'.$receipts[$x]['boxOrder'].' </td>
        <td>'.$receipts[$x]['ampere'].'</td>
       <td>'.$receipts[$x]['month'].'</td>
    </tr>
     <br>
    <tr>
        <td>سعر الكيلو واط </td>
        <td>خدمة العداد </td>
        <td>العداد السابق  </td>
        <td>العداد الحالي  </td>
        <td>الاستهلاك </td>
        <td> المجموع</td>
        
    </tr>
     <tr>
        <td>'.$receipts[$x]['KWPrice'].'</td>
        <td>'.number_format($receipts[$x]['fixedfees']).' </td>
        <td>'.$receipts[$x]['previousValue'].' </td>
        <td>'.$receipts[$x]['newValue'].'</td>
        <td>'.$receipts[$x]['consumption'].' </td>
        <td>'.number_format($receipts[$x]['value']).' </td>
        
    </tr>
     <br>
    <tr>
        <td colspan="3">الرجاء تسديد الفواتير قبل الخامس من الشهر</td>
       <td>للصيانة '.$phone.' </td>
       <td>تاريخ الاصدار </td>
       <td>'.$receipts[$x]['createdOn'].'</td>
        
    </tr>
    <br>
</table>
<br>';

$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
}
// --------------------------------------------------------- 

//Close and output PDF document
$fileName='receipts-'.$userID.'-'.$from.'.pdf';

ob_end_clean();//output buffers cleaning

$pdf->Output($userDirectory.'/receipts/'.$fileName, 'F');

    
    


?>
