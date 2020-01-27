app.controller('profile_controller', function($scope, $http)
{
    $scope.feedback=true;
    $scope.toggleDisable=true;
    $scope.submitButton='Edit';
    
// Get Profile ------------------------------------------------
    $scope.getProfile = function(){
        
        $http({
         method:"POST",
         url:"php/profile/getProfile.php",
         
        }).then(function(response){
            //console.log(response.data);
            $scope.profile = response.data.profile[0];
            $scope.toggleDisable=true;
            $scope.submitButton='Edit';
            
            //console.log($scope.profile)
            
            

        },
        function (error) {
      
            console.log(response.data,response.status);
          }
        );
       };
       
       $scope.getProfile();
 // Get Profile ***********************************************************

 // Edit Profile ------------------------------------------------
 $scope.editProfile = function(){

    if($scope.submitButton=='Update'){
        $scope.updateProfile();
    }
    
    else{
        
        $scope.toggleDisable=false;
        $scope.submitButton='Update';
    }
    
    
     
    }
// Edit Profile ***********************************************************  

// update Profile ------------------------------------------------
    $scope.updateProfile = function(){
        
    if(confirm("are you sure you want to update your info ?")){ 
        //console.log($scope.profile)
        $http({
     method:"POST",
     url:"php/profile/updateProfile.php",
     data:$scope.profile
    }).then(function(response){
        //console.log(response.data);
        if(response.data.error != '')
        {
         $scope.feedbackClass = 'invalid-feedback';
         $scope.feedbackMessage = response.data.error;
        }
        else
        {
         $scope.feedbackClass = 'valid-feedback';
         $scope.feedbackMessage = response.data.message; 
         $scope.submitButton="Edit";
       $scope.getProfile ();
        }
    },
    function (error) {
  
        console.log(response.data,response.status);
      }
    );
    }

   };
// Update Profile ***********************************************************
});
