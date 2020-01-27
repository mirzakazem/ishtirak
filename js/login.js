
app.controller('login_register_controller', function($scope, $http){
 $scope.closeMsg = function(){
  $scope.alertMsg = false;
 };

 $scope.login_form = true;

 $scope.showRegister = function(){
  $scope.login_form = false;
  $scope.register_form = true;
  $scope.alertMsg = false;
 };

 $scope.showLogin = function(){
  $scope.register_form = false;
  $scope.login_form = true;
  $scope.alertMsg = false;
 };

 $scope.submitRegister = function(){
  
  $http({
   method:"POST",
   url:"php/login/register.php",
   data:$scope.registerData
  }).then(function(response){
   //the below alert for debugging purposes
   //alert(response.data);   
   $scope.alertMsg = true;

   if(response.data.error != '')
   {
    $scope.alertClass = 'alert-danger';
    $scope.alertMessage = response.data.error;
   }
   else
   {
    $scope.alertClass = 'alert-success';
    $scope.alertMessage = response.data.message;
    $scope.registerData = {};
   }
  },
  function (error) {

    console.log("callback error status: "+error.status);
    }
  );
 };

 $scope.submitLogin = function(){
 
  $http({
   method:"POST",
   url:"php/login/login.php",
   data:$scope.loginData
  }).then(function(response){
   //the below alert for debugging purposes
   console.log(response.data);
   if(response.data.error != '')
   {
    $scope.alertMsg = true;
    $scope.alertClass = 'alert-danger';
    $scope.alertMessage = response.data.error;
   }
   else
   {
    location.reload();
   }
  }
  , function (error) {

    console.log("callback error status: "+error.status);
    }
  );
 };

});