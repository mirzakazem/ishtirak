app.controller('costumers_controller', function($scope, $http, $timeout)
{   $scope.feedback = true;
    

    $scope.submitButton="Add";
    $scope.customer={};
    
    
    $scope.getCustomers = function(){
        
        $http({
         method:"POST",
         url:"php/customers/getCustomers.php",
         
        }).then(function(response){
            //console.log(response.data);
            $scope.customers = response.data.customers;
            $scope.total = response.data.total;
            
            

        },
        function (error) {
      
            //console.log(response.data,response.status);
          }
        );
       };

    $scope.addCostumer = function(){
        
        //console.log(customer)
        $http({
         method:"POST",
         url:"php/customers/addCustomer.php",
         data:$scope.customer
        }).then(function(response){
         //the below alert for debugging purposes
         //console.log(response.data)
         
        
         if(response.data.error != '')
         {
          $scope.feedbackClass = 'invalid-feedback';
          $scope.feedbackMessage = response.data.error;
         }
         else
         {
          $scope.feedbackClass = 'valid-feedback';
          $scope.feedbackMessage = response.data.message; 
          $scope.submitButton="Add";
          $scope.customer={};
        $scope.getCustomers ();
         }
        },
        function (error) {
      
          console.log("callback error status: "+error.status);
          }
        );
       };
       

    $scope.deleteCustomer = function(id)
    {
        if(confirm("are you sure you want to delete this user?")){     
        $http({
                                
        method: 'POST',
        url:  'php/customers/deleteCustomer.php',
        data:  id
        }).then(function (response)
        { 
        $scope.feedbackClass = 'valid-feedback';
        $scope.feedbackMessage = response.data.message;      
        $scope.getCustomers();
        }, function (response) 
        {
        console.log(response.data,response.status);
        });

        };
    } 

    $scope.editCustomer = function(customer)
    {
       
       
        $scope.customer= angular.copy(customer);
        $scope.submitButton='Edit';
        
    }

    $scope.resetForm= function(){
        
        $scope.submitButton='Add';
    }
    
    $scope.getCustomers (); 

    $scope.lastClick='';
    $scope.clickCouner=0;

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
}
);