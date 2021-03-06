<?php

//login.php

include('../database_connection.php');

session_start();

$form_data = json_decode(file_get_contents("php://input"));

$validation_error = '';

$email;$password;

// check the entered email
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

// check the entered password
if(empty($form_data->password))
{
 $error[] = 'Password is Required';
}
else {
  $password=$form_data->password;
}

// check login credentials
if(empty($error))
{

 $sql="select * from users where email = '$email';";
    $res=mysqli_query($connect,$sql);
    if (mysqli_num_rows($res) == 1) 
    {
    $row = mysqli_fetch_assoc($res);
    
   if(password_verify($password, $row['password']))
    {
     
    $_SESSION["ID"] = $row["ID"];
    $userID=$row["ID"];

    //get expiry date
    $expiryDate= date("Y-m-d", strtotime($row['expiryDate']));
    $currentDate= date("Y-m-d");

    //check if user expired, then: update expired to 1, else keep it 0.
    if($currentDate>$expiryDate){
      $_SESSION["expired"] =1;
      $sql = "UPDATE `users` SET `expired`='1' WHERE `ID`=$userID";
    mysqli_query($connect,$sql);
    }
    else{
      $_SESSION["expired"] =0;
    }
    
    $timeStamp= date('Y-m-d H:i:s');

    $sql = "UPDATE `users` SET `lastLogin`='$timeStamp' WHERE `ID`=$userID";
    mysqli_query($connect,$sql);
    }
    else
    {
     $validation_error = 'Wrong Password';
    }

   }
  
  else
  {
   $validation_error = 'Wrong Email';
  }
 
}

else
{
 $validation_error = implode(", ", $error);
}

$output = array(
 'error' => $validation_error
);

//print_r($_SESSION);
//echo $_SESSION["name"];
echo json_encode($output);

?>
