//--------------------- Header Controller-------------------------
app.controller('header', function($scope,$http	) 

{
	$scope.selectedTab="";
	
	$scope.tab = function(item)
	{
	$scope.selectedTab=item;
	} 
	
	// get users activeness status and expiry date
	$scope.getUserInfo = function(){
        
        $http({
         method:"POST",
         url:"php/user/getUserInfo.php",
         
        }).then(function(response){
            //console.log(response.data);
            $scope.status = response.data.status;
			$scope.alertClass = response.data.alertClass;
            

        },
        function (error) {
      
            //console.log(response.data,response.status);
          }
        );
	   };
	   $scope.getUserInfo();
}
);
//------------------------------------