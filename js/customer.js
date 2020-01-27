//--------------------- Receipts Controller-------------------------
app.controller('costumer', function($scope) {
	
	$scope.val=0;
	$scope.entered="";
	$scope.options = "hidden";
	
	$scope.menu = function (checkbox){
	
		if(checkbox==true)
		{$scope.val++;}
		else
		{$scope.val--;}
		
		if($scope.val>0)
		{$scope.options="visibile";}
		else
		{$scope.options="hidden";}
		
		
	}
	//-------------------------------------toggle counter receipts
	$scope.counters="none";
	$scope.receipts="block";
	$scope.notes="none";
	
	
	$scope.costumerInfo="block";
	$scope.counterInfo="none";
	$scope.noteInfo="none";
	//------------------------------------- toggle active between buttons
	$scope.receiptsBtn="btn-white";
	$scope.countersBtn="btn-dark";
	$scope.notesBtn="btn-dark";
	
	$scope.showReceipts = function (){
		
	$scope.counters="none";
	$scope.notes="none";
	$scope.receipts="block";
	
	$scope.costumerInfo="block";
	$scope.counterInfo="none";
	$scope.noteInfo="none";
	
	$scope.receiptsBtn="btn-white";
	$scope.countersBtn="btn-dark";
	$scope.notesBtn="btn-dark";
	
	}
	
	$scope.showCounters = function (){
		
	$scope.counters="block";
	$scope.receipts="none";
	$scope.notes="none";
	
	$scope.costumerInfo="none";
	$scope.counterInfo="block";
	$scope.noteInfo="none";
	
	$scope.receiptsBtn="btn-dark";
	$scope.countersBtn="btn-white";
	$scope.notesBtn="btn-dark";
	
	
	}
	
	$scope.showNotes = function (){
		
	$scope.counters="none";
	$scope.receipts="none";
	$scope.notes="block";
	
	$scope.costumerInfo="none";
	$scope.counterInfo="none";
	$scope.noteInfo="block";
	
	$scope.receiptsBtn="btn-dark";
	$scope.countersBtn="btn-dark";
	$scope.notesBtn="btn-white";
	
	
	}
	
	
});
//------------------------------------