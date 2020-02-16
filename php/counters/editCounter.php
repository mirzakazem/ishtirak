<?php
session_start();

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$message='';
$validation_error = '';


$userID=$_SESSION["ID"];
$counterID="";

$userStatus=$_SESSION["expired"];
//check expiration status ------------
if($userStatus!=0){
    $error[] = 'user is expired';
}


//id validation ----------------------------------
if(empty($form_data->id))
    {
    $error[] = 'invalid customer, please select customer from the list';
    }
    else{
        $counterID = $form_data->id;
        }
//id validation *************************************

//ampere validation ---------------------------
if(empty($form_data->ampere))
    {
    $error[] = 'ampere is Required';
    }
    elseif (!is_numeric($form_data->ampere))
        {
            $error[] = 'invalid ampere format';
        }
        else{
            $ampere = htmlspecialchars($form_data->ampere);
            }

//ampere validation ******************************************


//box validation------------------------------------
if(empty($form_data->box))
    {
    $error[] = 'box number is Required';
    }
        elseif(strlen($form_data->box)>20)
        {
            $error[] = 'box number can not be more than 20 characters';   
        }
            
            else{
                $box =htmlspecialchars($form_data->box);
                }
//box validation *****************************************

//order validation------------------------------------
if(empty($form_data->order))
    {
    $error[] = 'order in box is Required';
    }
        elseif (!is_numeric($form_data->order))
        {
            $error[] = 'order must be number';   
        }
            elseif (($form_data->order)>99)
            {
                $error[] = 'order must be < 100';   
            }   
                
            else{
                        $order = htmlspecialchars($form_data->order);
                        
                        }
//order validation *****************************************

//check order if already exists -------------------------
$sql= "
        select id from counters where userid='$userID' and box='$box' and counters.order='$order' and not id='$counterID'
        ";
        if($result = mysqli_query($connect,$sql))
        {
        $count = mysqli_num_rows($result);
        if ($count>0)
            {
                $error[] = 'counter conflict';   
            } 
        }
        else{
            $error[] = 'check if order already exists marked true';
        }

//check order if already exists *************************

// insert the data ----------------------------------------
if(empty($error))
{
    $feedbackClass='success';
    
        $sql= "
        update counters set ampere = '$ampere', box='$box', counters.order='$order'
       where id='$counterID';
        ";
        if (mysqli_query($connect, $sql)) 
        {
        $feedback = 'Counter has been updated';
        
        }
              
}

else
        {
            $feedbackClass='danger';    
            $feedback = implode(", ", $error);
        }

$output = array(
    'feedback' => $feedback,
    'feedbackClass' => $feedbackClass,
 'id'=>$counterID
);
// insert the data *************************************************

echo json_encode($output);
//echo json_encode($form_data);

?>
