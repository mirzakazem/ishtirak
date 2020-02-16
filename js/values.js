app.controller('values_controller', function($scope, $http,$filter)
{  
    $scope.feedback=true;
    $scope.submitButton="OK";
    $scope.hidethis = true;
    $scope.counter={};
    $scope.prepend = "Counter ID";
    $scope.counter.month="";
    
    
     
   
   $scope.getValuesCustomers = function(){

    //$scope.counter.month=$filter('date')($scope.counter.month,"yyyy-MM-dd")
    console.log($scope.counter.month)
    $http({
                                 
        method: 'POST',
        url:  'php/values/getValuesCustomers.php',
        data:  $scope.counter.month
        }).then(function (response)
        { 
            
            console.log(response.data)
            $scope.customers=response.data.counters;
            $scope.totalCounters=response.data.totalCounters;
            $scope.nullValusCounter=response.data.nullValusCounter;
        }, function (response) 
        {
        console.log(response.data,response.status);
        });


   }

    $scope.resetCounter= function (){
        $scope.hidethis = true;
       
    }

    $scope.addValue= function (id,boxOrder, addValue, newValue, valueID)
    {
        $scope.counter.counterID=id;
        $scope.counter.boxOrder=boxOrder;
        $scope.counter.addValue=addValue;
        $scope.counter.newValue=newValue;
        $scope.counter.valueID=valueID;
        //$scope.counter.month=$filter('date')($scope.counter.month,"yyyy-MM-dd")

        console.log("sent counterID: "+$scope.counter.counterID);
        console.log("sent boxorder: "+$scope.counter.boxOrder);
        console.log("add value: "+$scope.counter.addValue);
        console.log('the selected date :'+$scope.counter.month)
       console.log("new value: "+$scope.counter.newValue)
       console.log("valueId : "+$scope.counter.valueID)

        if($scope.counter.newValue==null)
        {
            $http({
                method:"POST",
                url:"php/values/addValue.php",
                data:$scope.counter
            }).then(function(response){
                 //the below alert for debugging purposes
           console.log("received: "+response.data.feedback)
            
           $scope.feedbackShow='true';
           $scope.feedbackClass = response.data.feedbackClass;
           $scope.feedback = response.data.feedback;

          if(response.data.feedbackClass=='success')
           {
            $scope.submitButton="OK";
           $scope.customer={};
           $scope.getValues ();
           }    
            },
            function (error) {
            
                console.log("callback error status: "+error.status);
                }
            );
        }
        else 
        {
            
                
                //$scope.counter.month=$filter('date')($scope.counter.month,"yyyy-MM-dd")
                $http({
                    method:"POST",
                    url:"php/values/editValue.php",
                    data:$scope.counter
                }).then(function(response){
                        //the below alert for debugging purposes
                        console.log("received: "+response.data.feedback)
                            
                        $scope.feedbackShow='true';
                        $scope.feedbackClass = response.data.feedbackClass;
                        $scope.feedback = response.data.feedback;

                        if(response.data.feedbackClass=='success')
                        {
                            $scope.submitButton="OK";
                        $scope.customer={};
                        $scope.getValues ();
                        } 
                },
                function (error) {
                
                    console.log("callback error status: "+error.status);
                    }
                ); 
            
        }
    }
    
       
     $scope.getValues = function (){
        $http({ 
            method:"POST",
            url:"php/values/getValues.php",
           
        }).then(function(response){
            //console.log(response.data.values);
            $scope.values = response.data.values;
            $scope.total = response.data.total
            
           
            },
        function (error) {
        
            console.log(response.data,response.status);
            }
        );
     }

     $scope.getValues ();

     $scope.deleteValue = function(id)
     {
         if(confirm("are you sure you want to delete this counter?"))
         {     
         $http({
                                 
         method: 'POST',
         url:  'php/values/deleteValue.php',
         data:  id
         }).then(function (response)
         {
            //the below alert for debugging purposes
            console.log("received: "+response.data.feedback)
                            
            $scope.feedbackShow='true';
            $scope.feedbackClass = response.data.feedbackClass;
            $scope.feedback = response.data.feedback;

            if(response.data.feedbackClass=='success')
            {
                $scope.submitButton="OK";
            $scope.customer={};
            $scope.getValues ();
            }  
        
         
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

    $scope.editValue = function(object)
    {
       
       
        $scope.counter= angular.copy(object);
        $scope.counter.customer = angular.copy(object.fullName);
        $scope.prepend = "Value ID";
        $scope.submitButton='Edit';
        $scope.counter.month=new Date(object.month);
        
    } 

    $scope.resetForm= function(){
        $scope.hidethis = true;
        $scope.submitButton='OK';
        $scope.prepend = "Counter ID";
        $scope.customers="";
        $scope.feedbackMessage='';
    }
     
        
      
}
);