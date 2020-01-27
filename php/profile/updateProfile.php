<?php
session_start();

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$message='';
$validation_error = '';
$userID= $_SESSION["ID"];

// name validation ---------
if(empty($form_data->stationName))
{
 $error[] = 'Station Name is Required';
}
else
{
 $stationName = $form_data->stationName;
}
//name validation ********************

//phone validation----------------------
if(empty($form_data->phone))
    {
    $error[] = 'phone is Required';
    }
    elseif (!is_numeric($form_data->phone))
        {
            $error[] = 'invalid phone format';
        }
            
        elseif (!(strlen((string)$form_data->phone)==8))
        {
            $error[] = 'phone must contain 8 numbers exactly';
        }
        else{
            $phone = htmlspecialchars($form_data->phone);
            }
//phone validation ***************************************


// email validation ----------------------------------------
if(empty($form_data->email))
{
 $error[] = 'Email is Required';
}
else
{
 if(!filter_var($form_data->email, FILTER_VALIDATE_EMAIL))
 {
  $error[] = 'Invalid Email Format';
 }
 else
 {
    $email = $form_data->email;
  
 }
}
// email validation ***************************************

// address validation ---------
if(!empty($form_data->address))
{
    $address = htmlspecialchars($form_data->address);
}

//address validation ********************


// cehck if phone exists ------------------------
if(empty($error))
{
    $sql="select * from users where phone = '$phone' and not ID=$userID;";
    $res=mysqli_query($connect,$sql);
    if (mysqli_num_rows($res) > 0) 
    {
        
        $error[] = 'Phone already exists';
    }
}
// check if phone exists ***********************************

// check if email exists ----------------------------------
if(empty($error))
{
    $sql="select * from users where email = '$email' and not ID=$userID;";
    $res=mysqli_query($connect,$sql);
    if (mysqli_num_rows($res) > 0) 
    {
        
        $error[] = 'Email already exists';
    }
}
// check if email exists **************************************

 if(empty($error))
 {
    $sql="UPDATE `users` 
          SET `email`='$email',`phone`=$phone,`stationName`='$stationName',`address`='$address'
          WHERE ID= $userID;";
    if(mysqli_query($connect,$sql)) 
    {
        
        $message = 'Profile info Updated';
    } 
 }
 else
{
 $validation_error = implode(" | ", $error);
}

$output = array(
 'error'  => $validation_error,
 'message' => $message
);

echo json_encode($output);

?>