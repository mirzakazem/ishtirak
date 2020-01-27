<?php

//register.php

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));

$message = '';
$validation_error = '';

$name;$email;$password;

// name validation
if(empty($form_data->name))
{
 $error[] = 'Name is Required';
}
else
{
 $name = $form_data->name;
}

//phone validation
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

// email validation
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


// password validation
if(empty($form_data->password))
{
 $error[] = 'Password is Required';
}
else
{
 $password = password_hash($form_data->password, PASSWORD_DEFAULT);
}

// cehck if email exists
if(empty($error))
{
    $sql="select * from users where email = '$email';";
    $res=mysqli_query($connect,$sql);
    if (mysqli_num_rows($res) > 0) 
    {
        
        $error[] = 'Email already exists';
    }
}

// cehck if phone exists
if(empty($error))
{
    $sql="select * from users where phone = '$phone';";
    $res=mysqli_query($connect,$sql);
    if (mysqli_num_rows($res) > 0) 
    {
        
        $error[] = 'Phone already exists';
    }
}


// insert the data
if(empty($error))
{
    $sql= "
 INSERT INTO users (email, password, stationName) VALUES ('$email', '$password','$name')
 ";

 if (mysqli_query($connect, $sql)) 
 {
 $message = 'Registration Completed';
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
