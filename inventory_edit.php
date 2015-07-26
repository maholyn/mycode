<?php
session_start();
require("connection.php");
?>
<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
// Add an item into the system
if (isset($_POST['product_name'])) {
	$pid = mysql_real_escape_string($_POST['thisID']);
	$product_name = mysql_real_escape_string($_POST['product_name']);
	$price = mysql_real_escape_string($_POST['price']);
	$category = mysql_real_escape_string($_POST['category']);
	$size = mysql_real_escape_string($_POST['size']);
	$description = mysql_real_escape_string($_POST['description']);
	$stock = mysql_real_escape_string($_POST['stock']);

	$sql = mysql_query("UPDATE product SET product_name='$product_name', price='$price', category='$category', size='$size', description='$description', stock='$stock' WHERE product_id='$pid'");
	if ($_FILES['fileField']['tmp_name'] != "") {
		$newname = "$pid.jpg";
		move_uploaded_file($_FILES['fileField']['tmp_name'], "./images/$newname");	
	}
	
	header("location: inventory.php");
	var_dump($_POST);
	exit();
}
?>
<?php
// Get an item to edit
if (isset($_GET['pid'])) {
	$targetID = $_GET['pid'];
	$sql = mysql_query("SELECT * FROM product WHERE product_id='$targetID' LIMIT 1");
	$product_count = mysql_num_rows($sql);
	if($product_count > 0){
		while ($row = mysql_fetch_array($sql)) {
			$product_name = $row["product_name"];
			$price = $row["price"];
			$category = $row["category"];
			$size = $row["size"];
			$description = $row["description"];
			$stock = $row["stock"];
		}

	}else{
		echo "The item dose not exist.";
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inventory System</title> 
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
            <p class="text-center"><strong>INVENTORY SYSTEM</strong></p> 
        </div>

        <div class="main"> 

			<div align="right">
				<a href="inventory.php" class="btn btn-default btn-sm">
				<span class="glyphicon glyphicon-circle-arrow-left"></span> Back to List</a>&nbsp;
				<a href="inventory.php#inventoryForm" class="btn btn-default btn-sm">
				<span class="glyphicon glyphicon-plus-sign"></span> Add New</a>
			</div>
			
			<a name="inventoryForm" id="inventoryForm"></a>
			<h3>Edit Item Form</h3>
			
			<form class="form-horizontal" role="form" action="inventory_edit.php" enctype="multipart/form-data" name="inventoryForm" id="inventoryForm" method="post">
				
					<div class="form-group">
					    <label class="control-label col-sm-2" for="product_name">Product Name:</label>
					    <div class="col-sm-8">
					      <input name="product_name" type="text" class="form-control" id="product_name" value="<?php echo $product_name; ?>">
					    </div>
					</div>
					<div class="form-group">
					    <label class="control-label col-sm-2" for="price">Price:</label>
					    <div class="col-sm-2">
					      <input name="price" type="text" class="form-control" id="price" value="<?php echo $price; ?>">
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="category">Category:</label>
					    <div class="col-sm-3">
					      <input name="category" type="text" class="form-control" id="category" value="<?php echo $category; ?>">
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="size">Size:</label>
					    <div class="col-sm-2">
					      <input name="size" type="text" class="form-control" id="size" value="<?php echo $size; ?>">
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="description">Description:</label>
					    <div class="col-sm-8">
					      <textarea name="description" type="text" class="form-control" id="description"><?php echo $description; ?></textarea>	
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="stock">Stock:</label>
					    <div class="col-sm-2">
					      <input name="stock" type="text" class="form-control" id="stock" value="<?php echo $stock; ?>">
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="image">Product Image:</label>
					    <div class="col-sm-3">
					      <input name="fileField" type="file" class="form-control" id="fileField">
					    </div>
					</div>
					<div class="form-group"> 
					    <div class="col-sm-offset-2 col-sm-10">
					    	<input name="thisID" type="hidden" value="<?php echo $targetID; ?>">
					      <button name="submit" type="submit" class="btn btn-default" id="button">Edit this Item</button>
					    </div>
					</div>

				</form>
		</div>
	</div><!--end container--> 
</body>
</html>



