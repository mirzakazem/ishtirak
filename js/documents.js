app.controller('documents_controller', function($scope, $http)
{  
     $scope.address="documents/receipts/22/test.pdf"
     $scope.name="newname.pdf"
     $scope.documents={}

      //AJAX call to get all the Receipts' info
    $scope.getReceiptsPDF = function ()
    { 
      $http({ 
         method:"POST",
         url:"php/documents/getReceiptsPDF.php",
     }).then(function(response){
       
         $scope.filecount = response.data.filecount;
         $scope.documents=response.data.documents;
         console.log(response.data)
         },
     function (error) {
     
         console.log(response.data,response.status);
         }
     );       
     
     //initilze an object contains the receipts info
     //populate the received object in the vue 
     //check that download button works properly for all receipts
    }

   $scope.getReceiptsPDF();


   $scope.orderByMe = function(x)
   {
       $scope.myOrderBy = x;
       if(x==$scope.lastClick) 
       {
           
           if($scope.clickCouner==1)
           {   
               $scope.direction= true;
               $scope.clickCouner=0;
           }
           else
           {
               $scope.direction= false; 
               $scope.clickCouner=1;
           }    
       }
       else
       {
       $scope.direction= false; 
       $scope.clickCouner=1;
       }
       $scope.lastClick=x;

       //console.log("lastclick is :"+$scope.lastClick+"   the direction is :"+$scope.direction);
   }

}//end of main function


);