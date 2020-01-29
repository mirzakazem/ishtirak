
app.controller('receipts', function($scope,$http,$filter) {

//--------------------- Receipts menu-------------------------	
	$scope.val=0;
	$scope.entered="";
    $scope.options = "hidden";
    
    
    $scope.IDs=[];
   
	$scope.menu = function (checkbox,id){

        
        
		if(checkbox==true)
        {
            $scope.val++;
            $scope.IDs.push(id);
            
        }
		else
		{
            $scope.val--;
            $scope.IDs.splice($scope.IDs.indexOf(id),1)
           
        }
       

		if($scope.val>0)
		{
            $scope.options="visibile";
            

        }
		else
		{
            $scope.options="hidden";
            

        }
		
        console.log($scope.IDs)
        

        
    }
    
//initializing ------------------------------------
$scope.receipts = {}

$scope.search = {}

$scope.search.from = "";
$scope.search.to ="";
$scope.search.status="";

$scope.feedback=true;

$scope.showAllReceipts=true;
$scope.showOneReceipt=false;
    
// initializing **********************    
	
	$scope.issueReceipts = function(){
		
		$scope.receipt.month=$filter('date')($scope.receipt.month,"yyyy-MM-dd")
        console.log("sent: "+$scope.receipt.month)
        $http({
            method:"POST",
            url:"php/receipts/issueReceipts.php",
            data:$scope.receipt
        }).then(function(response){
            //the below alert for debugging purposes
            //console.log("received data: "+response.data.KWPrice) 
           
           $scope.form_data=response.data.form_data;
            if(response.data.error != '')
            {

            $scope.feedbackClass = 'invalid-feedback';
            $scope.feedbackMessage = response.data.error;
            }
            else
            {
            $scope.printReceiptPDF();
            $scope.feedbackClass = 'valid-feedback';
            $scope.feedbackMessage = response.data.message; 
            $scope.submitButton="Add";
            $scope.receipt={};
            $scope.getReceipts ();
            }
        },
        function (error) {
        
            console.log("callback error status: "+error.status);
            }
        );
    }

    $scope.printReceiptPDF = function()
    {
        $scope.receipt.month=$filter('date')($scope.receipt.month,"yyyy-MM-dd")
        console.log("sent: "+$scope.receipt.month)
        $http({
            method:"POST",
            url:"php/receipts/TCPDF/examples/receiptsPDF.php",
            data:$scope.receipt
        }).then(function(response){
           console.log(response.data)
            },
        function (error) {
        
            console.log("callback error status: "+error.status);
            }
        );
    }

    
    $scope.getReceipts = function(){
        //$scope.search.from = $filter('date')($scope.search.from,"yyyy-MM-dd")
        //$scope.search.to = $filter('date')($scope.search.to,"yyyy-MM-dd")
        /*console.log("status sent :"+$scope.search.status)
        console.log("from sent :"+$scope.search.from);
        console.log("To   sent :"+$scope.search.to);*/
        
        $http({ 
         method:"POST",
         url:"php/receipts/getReceipts.php",
         data: $scope.search
        }).then(function(response){
            //console.log("the status is :"+response.data);
            $scope.receipts = response.data.receipts;
            
           $scope.unpaidTotal = response.data.unpaidTotal;
           $scope.value = response.data.value;
           $scope.count = response.data.count;

           /*console.log("from received :"+response.data.from);
           console.log("To   received :"+response.data.to);*/
            },
        function (error) {
      
            console.log(response.data,response.status);
          }
        );

    }
    $scope.getReceipts();

    $scope.clearSearch = function (){
        $scope.search = {};
        $scope.search.status="";
        $scope.getReceipts();

        $scope.IDs = [];
        $scope.options="hidden"//the menu items
        $scope.val=0//to hide the menu items
        
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

    $scope.updateStatus=function(value){
        if(confirm("are you sure you want to change the Status ?"))     
         {
            $scope.data ={};
            $scope.data = {"IDs":$scope.IDs , "value":value};
        $http({
            method:"POST",
            url:"php/receipts/updateReceipts.php",
            data:$scope.data
        }).then(function(response){
            //the below alert for debugging purposes
            console.log("received data: "+response.data.form_data) 
           
            $scope.IDs = [];
            $scope.options="hidden"//the menu items
            $scope.val=0//to hide the menu items
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
            $scope.receipt={};
            $scope.getReceipts ();
            }
        },
        function (error) {
        
            console.log("callback error status: "+error.status);
            }
        )
    }
    }

    $scope.deleteReceipts=function(){
        if(confirm("are you sure you want to Delete the selected receipts ?"))     
         {
            $scope.data ={};
            $scope.data = {"IDs":$scope.IDs};
        $http({
            method:"POST",
            url:"php/receipts/deleteReceipts.php",
            data:$scope.data
        }).then(function(response){
           
            $scope.IDs = [];
            $scope.options="hidden"//the menu items
            $scope.val=0//to hide the menu items
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
            $scope.receipt={};
            $scope.getReceipts ();
            }
        },
        function (error) {
        
            console.log("callback error status: "+error.status);
            }
        )
    }
    }

    // show receipt ---------------------------------------------

    $scope.showReceipt = function(receiptID){
        // to hide the receipt options menu
        $scope.options = "hidden";

        $scope.data ={};
        $scope.data = {"receiptID":receiptID};
        console.log("before:"+ $scope.showAllReceipts);
            $http({
                method:"POST",
                url:"php/receipts/showReceipt.php",
                data:$scope.data
            }).then(function(response){
               
                console.log(response.data.receipt)
                if(response.data.error != '')
                {
                    
                }
                else
                {
                    $scope.receipt=response.data.receipt;

                    $scope.showAllReceipts=false;
                    $scope.showOneReceipt=true;
                   // console.log("after: "+ $scope.showAllReceipts);
                
                }
            },
            function (error) {
            
                console.log("callback error status: "+error.status);
                }
            )
        
        }
    

    // show receipt ********************************************

    // print receipt PDF ---------------------------------------------

    $scope.printReceipt = function(){
        for(i=0;i<$scope.IDs.length;i++)
         {
             
        $scope.data ={};
        $scope.data = {"receiptID":$scope.IDs[i]};
        $http({
            method:"POST",
            url:"php/receipts/printReceiptPDF.php",
            data:$scope.data
        }).then(function(response){
            
               console.log(response.data.fullName)

                var pdf = new jsPDF('L', 'pt', 'A5');

                
                pdf.text("Name", 220, 205, {lang: 'ar'})

                //$scope.html ='<p>الاسم: '+response.data.fullName+ '</p>';

                $scope.fileName =   response.data.fileName;   
                pdf.fromHTML($scope.text,20,20,{
                'width':200}
                );
                
                pdf.save($scope.fileName);
            
        }
        ) 
    }
}
    // print receipt PDF ********************************************

    $scope.hideReceipt = function(){
        $scope.showAllReceipts=true;
        $scope.showOneReceipt=false;
    }
});
