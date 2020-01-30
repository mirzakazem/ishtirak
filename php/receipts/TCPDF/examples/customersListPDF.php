<?php

session_start();
//$userID= $_SESSION["ID"];
$userID= 27;
$from='2019-12-01';
$to='2020-01-1';

// get the needed data from the database
include('../../database_connection.php');

 $sql = "
    SELECT * from getreceipts
    where userID = $userID and month >='$from' and month <'$to' order by createdOn Desc; 
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
$pdf->AddPage('L', 'A4');
$pdf->SetAutoPageBreak(TRUE, 0);

$pdf->SetFontSize(10);
// Restore RTL direction
$pdf->setRTL(true);

// set font
$pdf->SetFont('aefurat', '', 12);

// print newline
$pdf->Ln();

$tbl_header = '<style>
table{cellpadding:0; cellspacing:0 width:100%;}
table,td{border: 1px solid black;}
</style>
<table>
    
    <tr>
        <td width="35px">N</td>
        <td  width="200px">الاسم </td>
        <td>الهاتف</td>
        <td width="90px">رقم العداد </td>
        <td width="60px">عدد الامبيرات </td>
        <td width="60px">عن شهر</td>
        <td width="70px">سعر الكيلو واط </td>
        <td>خدمة العداد </td>
        <td>العداد السابق  </td>
        <td>العداد الحالي  </td>
        <td>الاستهلاك </td>
        <td>المجموع</td>
    </tr>';
$tbl_footer = '</table>';
$tbl ='';



for ($x=0;$x<count($receipts);$x++)
{
$counter=$x+1;
 $tbl .= '
 <tr>
    <td>'.$counter.'</td>
    <td>'.$receipts[$x]['fullName'].'</td>
    <td>'.$receipts[$x]['customerPhone'].'</td>
    <td>'.$receipts[$x]['boxOrder'].'</td>
    <td>'.$receipts[$x]['ampere'].'</td>
    <td>'.$receipts[$x]['month'].'</td>
    <td>'.$receipts[$x]['KWPrice'].'</td>
    <td>'.number_format($receipts[$x]['fixedfees']).'</td>
    <td>'.$receipts[$x]['previousValue'].'</td>
    <td>'.$receipts[$x]['newValue'].'</td>
    <td>'.$receipts[$x]['consumption'].'</td>
    <td>'.number_format($receipts[$x]['value']).'</td>
           
 </tr>
  ';
 $counter++;  } 


$pdf->WriteHTML($tbl_header.$tbl.$tbl_footer, true, 0, true, 0);

// ---------------------------------------------------------

//Close and output PDF document
$dirPath='/home/smp0h3cnvy4h/PDF/'.$userID.'/customersList';
$fileName='customersList-'.$userID.'-'.$from.'.pdf';


if (file_exists($dirPath)) {
    echo "The file $dirPath exists";
} else {
    mkdir("$dirPath", 0777 );
    echo "Directory $dirPath created successfully";
}
$pdf->Output($dirPath.'/'.$fileName, 'FI');
echo "pdf file  $fileName created successfully";
?>
//============================================================+
// END OF FILE
//============================================================+
