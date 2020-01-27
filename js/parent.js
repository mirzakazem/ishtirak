// the Main JS file


app.config(function($routeProvider) {
    $routeProvider
 
    .when("/main", {
	
        templateUrl : "views/main.html"
    })
    .when("/customers", {
        templateUrl : "views/customers.html"
    })
	.when("/counters", {
        templateUrl : "views/counters.html"
    })
    .when("/values", {
        templateUrl : "views/values.html"
    })
	.when("/receipts", {
        templateUrl : "views/receipts.html"
    })
	.when("/expenses", {
        templateUrl : "views/expenses.html"
    })
	.when("/profile", {
        templateUrl : "views/profile.html"
    })
    /*
    .when("/costumer", {
        templateUrl : "views/costumer.html"
    })
    */
	.otherwise({redirectTo : '/customers'})
	;
});
