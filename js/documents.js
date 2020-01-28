app.controller('documents_controller', function($scope, $http)
{  
     $scope.address="download.docx"
     $scope.name="newname"


    $scope.getReceiptsPDF = function ()
    {        
     //AJAX call to get all the Receipts' info
     //initilze an object contains the receipts info
     //populate the received object in the vue 
     //check that download button works properly for all receipts
    }

   // $scope.getReceiptsPDF();
}
);