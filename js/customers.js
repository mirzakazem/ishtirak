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
           console.log("received: "+response.data.feedback)
            
           $scope.alertTimer();
           $scope.feedbackClass = response.data.feedbackClass;
           $scope.feedback = response.data.feedback;

          if(response.data.feedbackClass=='success')
           {
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
             //the below alert for debugging purposes
           console.log("received: "+response.data.feedback)
            
           $scope.alertTimer();
           $scope.feedbackClass = response.data.feedbackClass;
           $scope.feedback = response.data.feedback;

          if(response.data.feedbackClass=='success')
           {
           $scope.submitButton="Add";
           $scope.customer={};
           $scope.getCustomers ();
           }
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
     
    $scope.alertTimer= function(){
        $scope.feedbackShow=true;
        $timeout( function(){
               $scope.feedbackShow=false;
          }, 3000)
          
        }
}
);