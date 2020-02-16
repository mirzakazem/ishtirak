<?php
session_start();

include('../database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));
$feedback='';
$validation_error = '';


$userID=$_SESSION["ID"];
$customerID="";

$userStatus=$_SESSION["expired"];
//check expiration status ------------
if($userStatus!=0){
    $error[] = 'user is expired';
}
// ******************************



//id validation----------------------------------
if(empty($form_data->id))
    {
    $error[] = 'invalid customer, please select customer from the list';
    }
    else{
        $customerID = $form_data->id;
        }
//id validation************************************** 

//if counter validation------------------------------------
if(empty($form_data->isCounter))
    {
    $error[] = 'counter is Required';
    }
    else{
            $isCounter = htmlspecialchars($form_data->isCounter);
            }

//initial value validation------------------------------------
if(!empty($form_data->isCounter) && ($form_data->isCounter)=='yes')
{
    if (!is_numeric($form_data->initialValue) || ($form_data->initialValue<0) )
        {
            $error[] = 'invalid initial value';
        }
    else{
            $initialValue = htmlspecialchars($form_data->initialValue);
            }
}

//ampere validation------------------------------------
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


//ampere validation *****************************************

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
if(empty($error))
{
$sql= "
        select id from counters where userid='$userID' and box='$box' and counters.order='$order'
        ";
        if($result = mysqli_query($connect,$sql))
        {
        $count = mysqli_num_rows($result);
        }
        if ($count>0)
            {
                $error[] = 'counter conflict';   
            } 

}
//check order if already exists *************************

// insert the data Case counter "no"------------------------------------------
if(empty($error))
{
    $feedbackClass='success';
    if($isCounter=='no')
    {
        $sql= "
        INSERT INTO counters (userID, customerID, ampere, box, counters.order, isCounter) 
        VALUES ('$userID', '$customerID', '$ampere','$box', '$order','$isCounter')
        ";
        if (mysqli_query($connect, $sql)) 
        {
        $feedback = 'Breaker has been added';
        
        } 
    }
    elseif($isCounter=='yes')
    {
        $sql= "
        INSERT INTO counters (userID, customerID, ampere, box, counters.order, isCounter,initialValue) 
        VALUES ('$userID', '$customerID', '$ampere','$box', '$order','$isCounter','$initialValue')
        ";
        if (mysqli_query($connect, $sql)) 
        {
        $feedback = 'counter has been added';
        
        }  
    }
                     
}
else
        {
        $feedbackClass='danger';    
        $feedback = implode(", ", $error);
        }
        
// insert the data *******************************************
$output = array(
 'feedback' => $feedback,
 'feedbackClass' => $feedbackClass,
 'id'=>$customerID
);

echo json_encode($output);
//echo json_encode($form_data);

?>
