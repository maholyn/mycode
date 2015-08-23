<?php
session_start();
require("connection.php");
?>
<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Check Out</title> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container"> 
        <div class="jumbotron">
            <h1 class="text-center">SAKETOPIA</h1>
            <br />
            <p align="right">
                <a href="product_list.php" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-home"></span> home</a>
                <a href="cart.php" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart</a>
            </p> 
        </div>
        <div class="main">
            <h3>Your Billing Info</h3>
            <form class="form-horizontal" role="form" action="checkout.php" enctype="multipart/form-data" name="billingForm" id="billingForm" method="post">
                
                <div class="form-group">
                    <label class="control-label col-sm-2" for="first_name">First Name:</label>
                    <div class="col-sm-5">
                        <input name="first_name" type="text" class="form-control" id="first_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="last_name">Last Name:</label>
                    <div class="col-sm-5">
                        <input name="last_name" type="text" class="form-control" id="last_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="street">Street:</label>
                    <div class="col-sm-5">
                        <input name="street" type="text" class="form-control" id="street" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="city">City:</label>
                    <div class="col-sm-3">
                        <input name="city" type="text" class="form-control" id="city" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="state">State:</label>
                    <div class="col-sm-1">
                        <input name="state" type="text" class="form-control" id="state" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="zip">Zip:</label>
                    <div class="col-sm-3">
                        <input name="zip" type="text" class="form-control" id="zip" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="size">Email:</label>
                    <div class="col-sm-5">
                        <input name="email" type="email" class="form-control" id="email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="size">Phone:</label>
                    <div class="col-sm-5">
                        <input name="phone" type="text" class="form-control" id="phone" required>
                </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-offset-2 col-sm-10">
                        <button name="continue" type="continue" class="btn btn-default" id="button">Continue to Checkout</button>
                    </div>
                </div>

            </form>
        </div><!--end of main-->
    </div><!--end container--> 
</body>
</html>