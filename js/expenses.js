app.controller('expenses_controller', function($scope, $http,$filter)
{  
     $scope.feedback = true;
    
    $scope.search = {}
    $scope.search.from = "";
    $scope.search.to ="";
    $scope.search.bar="";

    $scope.submitButton="Add";
    $scope.expense={};
    

    $scope.clearSearch = function (){
        $scope.search = {};
        $scope.search.bar="";
        $scope.getExpenses();
    }


    $scope.getExpenses = function(){
       
        $scope.search.from = $filter('date')($scope.search.from,"yyyy-MM-dd")
        $scope.search.to = $filter('date')($scope.search.to,"yyyy-MM-dd")
        console.log("bar sent :"+$scope.search.bar)
        console.log("from sent :"+$scope.search.from);
        console.log("To   sent :"+$scope.search.to);
        
        $http({ 
         method:"POST",
         url:"php/expenses/getExpenses.php",
         data: $scope.search
        }).then(function(response){
            //console.log("the status is :"+response.data);
            $scope.expenses = response.data.expenses;
            
           $scope.total = response.data.total;
           $scope.value = response.data.value;

           /*console.log("from received :"+response.data.from);
           console.log("To   received :"+response.data.to);*/
            },
        function (error) {
      
            console.log(response.data,response.status);
          }
        );
        
       };
       
     

     
        $scope.addExpense = function(){
        //console.log($scope.expense)
         
        $http({
         method:"POST",
         url:"php/expenses/addExpense.php",
         data:$scope.expense
        }).then(function(response){
        //the below alert for debugging purposes
        console.log("received: "+response.data.feedback)
                            
        $scope.feedbackShow='true';
        $scope.feedbackClass = response.data.feedbackClass;
        $scope.feedback = response.data.feedback;

        if(response.data.feedbackClass=='success')
        {
          $scope.expense = {};
          $scope.submitButton="Add";
            $scope.getExpenses ();
         }
        },
        function (error) {
      
          console.log("callback error status: "+error.status);
          }
        ); 
        
       };
       
      
    
    $scope.deleteExpense = function(id)
    {
        if(confirm("are you sure you want to delete this payment?")){     
        $http({
                                
        method: 'POST',
        url:  'php/expenses/deleteExpense.php',
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
        $scope.getExpenses();
        } 

        }, function (response) 
        {
        console.log(response.data,response.status);
        });

        };
    } 
    
   
    $scope.editExpense = function(expense)
    {
       
       
        $scope.expense= angular.copy(expense);
        $scope.expense.date=new Date($scope.expense.date);
        
        $scope.submitButton='Edit';
        
    }
    
   
    $scope.resetForm= function(){
        
        $scope.submitButton='Add';
    }
    
   
    $scope.getExpenses (); 

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