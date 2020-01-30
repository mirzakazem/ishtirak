<?php
session_start();

include('../database_connection.php');

$userID= $_SESSION["ID"];
// Get the data

//check whether it's local or online server
$ip_server = $_SERVER['SERVER_ADDR']; 


if($ip_server =='160.153.133.148')
    {// online
   $directory = '/home/smp0h3cnvy4h/public_html/documents/'.$userID.'/receipts/';
    }
    else{
      //offline
      $directory = "C:/wamp/www/Ishtirak/code/documents/".$userID."/receipts/";
    }


$filecount = 0;
$files = glob($directory ."*");
if ($files){
 $filecount = count($files);
}



for ($i = 0; $i <$filecount; $i++) 
{
    if($ip_server =='160.153.133.148')
    {
        $documents[$i]['path']='../../documents/'.$userID.'/'.substr($files[$i],44);
        $documents[$i]['name']=substr($documents[$i]['path'],28); 
        $documents[$i]['modified']=date(" d m Y H:i", filemtime("$directory".$documents[$i]['name'])); 
    }
    else
    {
       $documents[$i]['path']=substr($files[$i],26); 
        $documents[$i]['name']=substr($documents[$i]['path'],22); 
        $documents[$i]['modified']=date(" d m Y H:i", filemtime("$directory".$documents[$i]['name'])); 
    }
        
    
  
  
}

//print_r($files);
//$currentPath=getcwd();

$data = array(
    'documents'=>$documents,
    'filecount'=>$filecount,
   );

$output = json_encode($data);
echo $output;
mysqli_close($connect);
exit;

?>