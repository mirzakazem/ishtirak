<?php

//logout.php

session_start();

session_destroy();


//check whether it's local or online server
$ip_server = $_SERVER['SERVER_ADDR']; 
if($ip_server =='160.153.133.148')
    {// online
        header("location:../../index.php");
    }
else{//local
    header("location:index.php");
    
}

?>
