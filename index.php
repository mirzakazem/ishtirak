<?php

//index.php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Ishtirak | Main</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">

	<!-- Font Awesome CSS -->
	<link rel="stylesheet" href="css/fontawesome-free-5.7.2-web/css/all.min.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<!-- global CSS -->
	
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/parent.css">
	<!-- Angular -->
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>
	
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  

  <style>
  .form_style
  {
   width: 600px;
   margin: 0 auto;
  }
  </style>
  
	
</head>
		
<body ng-app="myApp">
<?php
      if(!isset($_SESSION["ID"]))
      {
?>  
<div class='row'>

  <div ng-controller="login_register_controller" class="col-6 offset-3">

  <img src="img/logoIshtirak.png" class="rounded mx-auto d-block" height="200" alt="logo Ishtirak">

      <div class="alert {{alertClass}} alert-dismissible" ng-show="alertMsg">
      <a href="#" class="close" ng-click="closeMsg()" aria-label="close">&times;</a>
      {{alertMessage}}
      </div>

      <!-- login Form start-->
      <div class="panel panel-default" ng-show="login_form">
          <div class="panel-heading">
              <h3 class="panel-title">Login</h3>
          </div>
      <div class="panel-body">
          <form method="post" ng-submit="submitLogin()">
              <div class="form-group">
              <label>Email</label>
              <input type="text" name="email" ng-model="loginData.email" class="form-control" />
              </div>
              <div class="form-group">
              <label>Password</label>
              <input type="password" name="password" ng-model="loginData.password" class="form-control" />
              </div>
              <div class="form-group" align="center">
              <input type="submit" name="login" class="btn btn-primary" value="Login" />
              <br />
              <input type="button" name="register_link" class="btn btn-primary btn-link" ng-click="showRegister()" value="Register" />
              </div>
          </form>
      </div>
      </div><!-- login Form End-->


      
      
      <!-- Register Form start-->
      <div class="panel panel-default" ng-show="register_form">
        <div class="panel-heading">
          <h3 class="panel-title">Register</h3>
        </div>
        <div class="panel-body">
              <form method="post" ng-submit="submitRegister()" >
              <div class="form-group">
                <label>Station Name</label>
                <input type="text" name="name" placeholder="e.g. Al Nour Station" ng-model="registerData.name" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Phone number</label>
                <input type="text" name="name" placeholder="e.g. 70123456" ng-model="registerData.phone" class="form-control" required/>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" ng-model="registerData.email" class="form-control" required/>
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" ng-model="registerData.password" class="form-control" required/>
              </div>
              <div class="form-group" align="center">
                <input type="submit" name="register" class="btn btn-primary" value="Register" />
                <br />
                <input type="button" name="login_link" class="btn btn-primary btn-link" ng-click="showLogin()" value="Login" />
              </div>
              </form>
        </div>
      </div><!-- register Form End-->

  </div>  <!-- login col ended --> 
</div>   <!-- login row ended -->
     

<?php
}
else
{
?>
		
<!--Header -->
<div class="row">
			<div class="col-sm-12 padding" ng-include=" 'views/header.html' ">
			
			</div>
			
</div>			
<!-- content -->
<div class="row">
	<div class="col-sm-12">
		<div ng-view></div>
	</div>
</div>


<!--------------------------------Footer-->
<div class="row">
	<div class="col-12 footer padding" ng-include=" 'views/footer.html' ">
	
		
	</div>
</div>
<!-- ---------------------------------->



<?php
}
?>

<script>var app = angular.module("myApp", ["ngRoute"]);</script>
<script src="js/login.js"></script>

<script src="js/parent.js"></script>

<script src="js/customers.js"></script>
<script src="js/counters.js"></script>
<script src="js/values.js"></script>
<script src="js/receipts.js"> </script>
<script src="js/expenses.js"> </script>
<script src="js/customer.js"> </script>
<script src="js/header.js"> </script>
<script src="js/profile.js"> </script>
<script src="js/documents.js"> </script>


</body>
</html>
