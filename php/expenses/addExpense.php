<?php
session_start();

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$message='';
$validation_error = '';


//Title validation
if(empty($form_data->title))
{
 $error[] = 'title is Required';
}
else
{
 $title = htmlspecialchars($form_data->title);
}


//value validation
if(empty($form_data->value))
    {
    $error[] = 'value is Required';
    }
    elseif (!is_numeric($form_data->value))
        {
            $error[] = 'value must be in numbers';
        }
         
        else{
            $value = htmlspecialchars($form_data->value);
            }

// date validation
if(!empty($form_data->date))
{
    $date = htmlspecialchars($form_data->date);
}
else {
    $error[] = 'date is Required';
}


// note validation
if(!empty($form_data->note))
{
   
    $note = htmlspecialchars($form_data->note);
    
    
}
else
{
    $note = ""; 
}




$userID=$_SESSION["ID"];


// insert the data
if(empty($error))
{
    if(empty($form_data->id))
    {
        $sql= "
        INSERT INTO expenses (userID, title, value, note, date) 
        VALUES ('$userID', '$title','$value', '$note', '$date')
        ";
        if (mysqli_query($connect, $sql)) 
        {
        $message = 'payment has been added';
        
        }
    }
    else
    {
        $id=$form_data->id;
        $sql= "
        update expenses set title = '$title' , value = '$value', note = '$note', date = '$date' 
        where ID= '$id' and userID=$userID;
        "; 
        if (mysqli_query($connect, $sql)) 
        {
        $message = 'info of payment '.$id.' has been updated';
        
        }
    }    
        
        
        
}

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
