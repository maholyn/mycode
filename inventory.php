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
// Delete an item
if (isset($_GET['deleteid'])) {
	echo "Are you sure to delete this item with ID of" . $_GET['deleteid'] . "? <a href='inventory.php?yesdelete=" .$_GET['deleteid']. "'>YES</a> | <a href='inventory.php'>NO</a>";
	exit();
}
if (isset($_GET['yesdelete'])) {
	$id_to_delete=$_GET['yesdelete'];
	$sql = mysql_query("DELETE FROM product WHERE product_id='$id_to_delete' LIMIT 1") or die(mysql_error());
	$pictodelete=("./images/$id_to_delete.jpg");
	if (file_exists($pictodelete)) {
		unlink($pictodelete);
	}
	header("location: inventory.php");
	exit();
}
?>
<?php
// Add new item into the system
if (isset($_POST['product_name'])) {
	$product_name = mysql_real_escape_string($_POST['product_name']);
	$price = mysql_real_escape_string($_POST['price']);
	$category = mysql_real_escape_string($_POST['category']);
	$size = mysql_real_escape_string($_POST['size']);
	$description = mysql_real_escape_string($_POST['description']);
	$stock = mysql_real_escape_string($_POST['stock']);

	$sql = mysql_query("SELECT product_id FROM product WHERE product_name='$product_name' LIMIT 1");
	$productMatch = mysql_num_rows($sql);
	if ($productMatch > 0) {
		echo 'Sorry, the "Product Name" already exists. It is going to be duplicated into the system. <a href="inventory.php">click here</a>';
		exit();
	}
	$sql = mysql_query("INSERT INTO product (product_name, price, category, size, description, stock)
		VALUES('$product_name', '$price', '$category', '$size', '$description', '$stock')") or die(mysql_error());
	
	$pid = mysql_insert_id();
	$newname = "$pid.jpg";
	move_uploaded_file($_FILES["fileField"]["tmp_name"], "./images/$newname");
	header("location: inventory.php");
	var_dump($_POST);
	exit();
}
?>
<?php
// Grab the list
$product_list = "";
$sql = mysql_query("SELECT * FROM product ORDER BY product_name ASC");
$product_count = mysql_num_rows($sql);
if($product_count > 0){
	while ($row = mysql_fetch_array($sql)) {
		$product_id = $row["product_id"];
		$product_name = $row["product_name"];
		$category = $row["category"];
		$price = $row["price"];
		$stock = $row["stock"];

		$product_list .= '<tr>';
		$product_list .= '<td>' . $product_id  . '</td>';
		$product_list .= '<td>' . $product_name  . '</td>';
		$product_list .= '<td>' . $category  . '</td>';
		$product_list .= '<td>$' . $price  . '</td>';
		$product_list .= '<td>' . $stock  . '</td>';
		$product_list .= '<td><a href="inventory_edit.php?pid=' . $product_id . '"><span class="glyphicon glyphicon-pencil"></span>edit</a> &bull; 
		<a href="inventory.php?deleteid=' . $product_id . '"><span class="glyphicon glyphicon-trash"></span>delete</a></tr>';
		$product_list .= '</tr>';
	}

}else{
	$product_list = "You have no product in your store yet";
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
			<div align="right"><a href="inventory.php#inventoryForm" class="btn btn-default btn-sm">
				<span class="glyphicon glyphicon-plus-sign"></span> Add New</a>
			</div>
			<div>
				<h3>Inventory List</h3>
				<table class="table table-hover">
				    <thead>
				      <tr>
				        <th>Product ID</th>
				        <th>Product Name</th>
				        <th>Category</th>
				        <th>Price</th>
				        <th>Stock</th>
				        <th>Action</th>
				      </tr>
				    </thead>
				    <tbody>
				     
				<?php echo $product_list; ?>

					</tbody>
				 </table>
			</div>

			<hr>

			<div>
				<a name="inventoryForm" id="inventoryForm"></a>
				<h3>Add New Item Form</h3>

				<form class="form-horizontal" role="form" action="inventory.php" enctype="multipart/form-data" name="inventoryForm" id="inventoryForm" method="post">
				
					<div class="form-group">
					    <label class="control-label col-sm-2" for="product_name">Product Name:</label>
					    <div class="col-sm-8">
					      <input name="product_name" type="text" class="form-control" id="product_name" required>
					    </div>
					</div>
					<div class="form-group">
					    <label class="control-label col-sm-2" for="price">Price:</label>
					    <div class="col-sm-2">
					      <input name="price" type="text" class="form-control" id="price" required>
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="category">Category:</label>
					    <div class="col-sm-3">
					      <input name="category" type="text" class="form-control" id="category" required>
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="size">Size:</label>
					    <div class="col-sm-2">
					      <input name="size" type="text" class="form-control" id="size" required>
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="description">Description:</label>
					    <div class="col-sm-8">
					      <textarea name="description" type="text" class="form-control" id="description" required></textarea>	
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="stock">Stock:</label>
					    <div class="col-sm-2">
					      <input name="stock" type="text" class="form-control" id="stock" required>
					    </div>
					</div><div class="form-group">
					    <label class="control-label col-sm-2" for="image">Product Image:</label>
					    <div class="col-sm-3">
					      <input name="fileField" type="file" class="form-control" id="fileField" required>
					    </div>
					</div>
					<div class="form-group"> 
					    <div class="col-sm-offset-2 col-sm-10">
					      <button name="submit" type="submit" class="btn btn-default" id="button">Add Item</button>
					    </div>
					</div>

				</form>
			</div>
		</div>
	</div><!--end container--> 
</body>
</html>

