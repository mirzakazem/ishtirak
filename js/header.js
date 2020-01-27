//--------------------- Header Controller-------------------------
app.controller('header', function($scope) 

{
	$scope.selectedTab="";
	
	$scope.tab = function(item)
	{
	$scope.selectedTab=item;
	
   
   
	} 
	
}
);
//------------------------------------