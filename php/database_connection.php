<?php
// check whether the server is local or online
$ip_server = $_SERVER['SERVER_ADDR']; 
if($ip_server =='160.153.133.148')
    {// online
        $servername = "localhost";
        $username = "kazem";
        $password = "@dm1nIshtiraK";
        $dbname="ishtirak";
    }
else{//local
   
$servername = "localhost";
$username = "root";
$password = "";
$dbname="ishtirak";
}


// Create connection
$connect = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connect,"utf8");
// Check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
//else echo "DB Connected successfully";
// set the time zone
date_default_timezone_set("Asia/Beirut");
?>