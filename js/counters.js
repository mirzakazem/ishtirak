app.controller('counters_controller', function($scope, $http, $timeout)
{  
    

    $scope.resetForm = function(){
        $scope.feedbackShow=false;
        $scope.submitButton="Add";
        $scope.submitButtonClass="btn-success";
        $scope.prepend = "Customer ID";
        $scope.customer={}
        $scope.customer.isCounter='no';
        $scope.customer.ampere='5';

    }
    $scope.resetForm();

    $scope.complete = function(string){  
       // console.log($scope.customer.id)

        if(($scope.customer.customer===undefined)||$scope.customer.customer=="")
        {
            $scope.hidethis = true;   
        }
        else
       {   
             $scope.hidethis = false;  
            var output = [];
            
            $http({ 
                method:"POST",
                url:"php/counters/getCountersCustomers.php",
                data:$scope.customer
            }).then(function(response){
                //console.log("the status is :"+response.data);
                $scope.customers = response.data.customers;
               /* console.log("First Name: "+response.data.fName)
                console.log("last Name: "+response.data.lName)
                console.log("phone Name: "+response.data.phone)
                console.log("email Name: "+response.data.email)*/
                },
            function (error) {
            
                console.log(response.data,response.status);
                }
            );

            angular.forEach($scope.customers, function(customer){  
                 output.push(customer);  
                 });  
            $scope.customers = output;
        } 
   }
   
   $scope.fillTextbox = function(string){  
    
    $scope.customer.customer =string.fullName;  
    $scope.customer.id=string.id;
    $scope.prepend = "Customer ID";
    //console.log($scope.customer);
    //console.log($scope.customer.id)
    
    $scope.hidethis = true;  
    }

    $scope.resetCounter= function (){
        $scope.hidethis = true;
    }

    $scope.addCounter= function (){
        if( $scope.submitButton=="Add")
    {
        console.log($scope.customer)
        //console.log($scope.submitButton)
        $http({
            method:"POST",
            url:"php/counters/addCounter.php",
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
            $scope.getCounters ();
            }
        },
        function (error) {
        
            console.log("callback error status: "+error.status);
            }
        );
    }
    else 
        {
            console.log($scope.submitButton)
            $http({
                method:"POST",
                url:"php/counters/editCounter.php",
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
            $scope.getCounters ();
            }
            },
            function (error) {
            
                console.log("callback error status: "+error.status);
                }
            ); 
        }
    }
       
     $scope.getCounters = function (){
        $http({ 
            method:"POST",
            url:"php/counters/getCounters.php",
           
        }).then(function(response){
          
            $scope.counters = response.data.counters;
            $scope.total = response.data.total
            console.log(response.data)
            },
        function (error) {
        
            console.log(response.data,response.status);
            }
        );
     }

     $scope.getCounters ();

     $scope.deleteCounter = function(id)
     {
         if(confirm("are you sure you want to delete this counter?")){     
         $http({
                                 
         method: 'POST',
         url:  'php/counters/deleteCounter.php',
         data:  id
         }).then(function (response)
         { 
          //the below alert for debugging purposes
          console.log("received: "+response.data.feedback);
            
          $scope.alertTimer();
          $scope.feedbackClass = response.data.feedbackClass;
          $scope.feedback = response.data.feedback;

         if(response.data.feedbackClass=='success')
          {
          $scope.submitButton="Add";
          $scope.customer={};
          $scope.getCounters ();
          } 

         }, function (response) 
         {
         console.log(response.data,response.status);
         });
 
         };
     }

     $scope.toggleActive = function(id)
     {
        if(confirm("are you sure you want to change this counter's status ?")){     
            $http({
                                    
            method: 'POST',
            url:  'php/counters/toggleDisabledCounter.php',
            data:  id
            }).then(function (response)
            { 
            $scope.feedbackClass = 'valid-feedback';
            $scope.feedbackMessage = response.data.message; 
            $scope.customer={}     
            $scope.getCounters();
            }, function (response) 
            {
            console.log(response.data,response.status);
            });
    
            };
     }


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
    $scope.editCounter = function(counter)
    {
       
       console.log(counter);
        $scope.customer= angular.copy(counter);
        $scope.customer.customer=angular.copy(counter.fullName)
        $scope.prepend = "Counter ID";
        $scope.submitButton='Edit';
        $scope.submitButtonClass="btn-primary";
        
    } 
     
    $scope.alertTimer= function(){
        $scope.feedbackShow=true;
        $timeout( function(){
               $scope.feedbackShow=false;
          }, 3000)
          
        }  
      
}
);