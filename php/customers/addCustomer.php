<?php
session_start();

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$message='';
$validation_error = '';
$userID=$_SESSION["ID"];

//first name validation------------
if(empty($form_data->fullName))
{
 $error[] = 'full Name is Required';
}
else
{
 $fullName = htmlspecialchars($form_data->fullName);
}
//first name validation **************


//phone validation -----------------
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
//phone validation ******************

// address validation ----------------
if(!empty($form_data->address))
{
    $address = htmlspecialchars($form_data->address);
}
else {
    $address="";
}
// address validation*************************

// email validation ----------------------
if(!empty($form_data->email))
{
    if(!filter_var($form_data->email, FILTER_VALIDATE_EMAIL))
    {
    $error[] = 'Invalid Email Format';
    }
    else
    {
        $email = htmlspecialchars($form_data->email);
    
    }
}
else
{
    $email = ""; 
}
// email validation ***************************

// cehck if costumer exists ----------------------
if(empty($error))
{
    if(empty($form_data->id))
    {
    $sql="select * from customers where phone = '$phone' and userID=$userID;  ";
    $res=mysqli_query($connect,$sql);
    if (mysqli_num_rows($res) > 0) 
        {
            
            $error[] = 'costumer already exists, duplicated phone numbers ';
        }
    }
    else
    {
        $id=$form_data->id;
        $sql="select * from customers where phone = '$phone' and userID=$userID and not ID=$id;  ";
        $res=mysqli_query($connect,$sql);
        if (mysqli_num_rows($res) > 0) 
        {
            
            $error[] = 'costumer already exists, duplicated phone numbers ';
        }
    }

}

// cehck if costumer exists  ***************************


// insert the data --------------------------------
if(empty($error))
{
    if(empty($form_data->id))
    {
        $sql= "
        INSERT INTO customers (userID, fullName, phone, address, email) 
        VALUES ('$userID', '$fullName','$phone', '$address', '$email')
        ";
        if (mysqli_query($connect, $sql)) 
        {
        $message = 'Registration Completed';
        
        }
    }
    else
    {
        $id=$form_data->id;
        $sql= "
        update customers set fullName = '$fullName', phone = '$phone', address = '$address', email = '$email' 
        where ID= '$id' and userID=$userID;
        "; 
        if (mysqli_query($connect, $sql)) 
        {
        $message = 'Cutomer info has been updated';
        
        }
    }    
               
}
// insert data ***************************************************************************
else
        {
        $validation_error = implode(", ", $error);
        }

$output = array(
 'error'  => $validation_error,
 'message' => $message
);

echo json_encode($output);
//echo json_encode($form_data);

?>
