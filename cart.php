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
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $isThere = false;
    $i = 0;
    // if cart is empty
    if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
        $_SESSION["cart_array"] = array(0 => array("item_id" => $pid, "quantity" => 1));
    }
    else {
    // if item is already there
        foreach ($_SESSION["cart_array"] as $item) {
            $i++;
            while (list($key, $value) = each($item)) {
                if ($key == "item_id" && $value == $pid) {
                    array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $item['quantity'] + 1)));
                    $isThere = true;
                }
            }
        }
    // Add new item
        if ($isThere == false) {
            array_push($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => 1));
        }
    }
    header("location: cart.php");
    exit();
}
?>
<?php
// Empty shopping cart
if (isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
    unset($_SESSION["cart_array"]);
}
?>
<?php
// Edit item quantitty
if (isset($_POST['item_to_edit']) && $_POST['item_to_edit'] != "") {

    $item_to_edit = $_POST['item_to_edit'];
    $quantity = $_POST['quantity'];
    $quantity = preg_replace('#[^0-9]#i', '', $quantity);
    if ($quantity >= 1000) {
        $quantity = 999;
    }
    if ($quantity < 1) {
        $quantity = 1;
    }
    if ($quantity == "") {
        $quantity = 1;
    }
    $i=0;
    foreach ($_SESSION["cart_array"] as $item) {
            $i++;
            while (list($key, $value) = each($item)) {
                if ($key == "item_id" && $value == $item_to_edit) {
                    array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_edit, "quantity" => $quantity)));
            }
        }
    }
    header("location: cart.php");
    exit();  
}
?>
<?php
// Remove item
if (isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "") {
    $key_to_remove = $_POST['index_to_remove'];
    if (count($_SESSION["cart_array"]) <= 1) {
        unset($_SESSION["cart_array"]);
    } else {
        unset($_SESSION["cart_array"]["$key_to_remove"]);
        sort($_SESSION["cart_array"]);
    }   
}
?>
<?php
// Cancel promotion code
if (isset($_GET['cancel']) && $_GET['cancel'] == "cancelCode") {
    $promotion_code = "";
}
?>
<?php
// Get promotion code
$promotion_code = "";
if (isset($_POST['promotion_code']) && $_POST['promotion_code'] != "") {
    $promotion_code = $_POST['promotion_code'];
    $sql = mysql_query("SELECT * FROM promotion WHERE promotion_code='$promotion_code' LIMIT 1");
    $promotion_count = mysql_num_rows($sql);
    if ($promotion_count > 0) {
        while ($row = mysql_fetch_array($sql)) {
            $discount_price = $row["discount_price"];
            $discount_percentage = $row["discount_percentage"];
            $expire_date = $row["expire_date"];
        }
    }else{
        echo "No code in the system with the ID.";
        exit();
    }
}
?>
<?php
// Display the cart
$cart_output = "";
$promotion_box = "";
$cart_total = "";
$deduction_price = "";
if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
    $cart_output = '<h3 class = "text-center">Your shopping cart is empty</h3>';
}else{
    $i = 0;
    foreach ($_SESSION["cart_array"] as $item) {
        $item_id = $item['item_id'];
        $sql = mysql_query("SELECT * FROM product WHERE product_id='$item_id' LIMIT 1");
        while ($row = mysql_fetch_array($sql)) {
            $product_name = $row["product_name"];
            $price = $row["price"];
            $stock = $row["stock"];
        }
        
        setlocale(LC_MONETARY, "en_US");
        
        $cart_output .= '<tr>';
        $cart_output .= '<td><img src="./images/' . $item_id . '.jpg" alt=' . $product_name . ' width="auto" height="100"/><a href="product.php?product_id=' . $item_id . '"> &nbsp;&nbsp;' . $product_name . '</td>';

        $cart_output .= '<td>';

        // Show a message and adjust quantity if item quantity exceed stock quantity
        if ($item['quantity']>$stock) {
                    $message = "Sorry, it is out of stock!";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                    $item['quantity'] = $stock;
                }

        // Show discount price if more than
        if ($item['quantity']>12 && $item['quantity']<97) {
           $cart_output .= '<s>$' . $price . '</s><br />';
           $price = round($price * 0.9, 2);
        }
        else if ($item['quantity']>96){
            $cart_output .= '<s>$' . $price . '</s><br />';
            $price = round($price * 0.8, 2);
        }
        $priceTotal = $price * $item['quantity'];
        $cart_total = $priceTotal + $cart_total;

        $price = money_format("%10.2n", $price);

        $cart_output .=  $price . '</td>';

        $cart_output .= '<td><form action="cart.php" method="post">
            <input name="quantity" type="text" value="' . $item['quantity'] . '" size="3" maxlength="3"/>
            <button name="editBtn' .$item_id. '" type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
            <input name="item_to_edit" type="hidden" value="' . $item_id. '"/></form><br />
            ' . $stock . ' in stock</td>';

        $priceTotal = money_format("%10.2n", $priceTotal);

        $cart_output .= '<td>' . $priceTotal . '</td>';
        $cart_output .= '<td><form action="cart.php" method="post">
            <button name="deleteBtn' . $item_id . '" type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-remove"></span> Remove</button>
            <input name="index_to_remove" type="hidden" value="' . $i . '"/></form></td>';
        $cart_output .= '</tr>';    
        $i++;
    }
    $promotion_box .= '<div align="right">
            <form action="cart.php" method="post" >
                <label>Promotion Code: </label>';
    
    if ($promotion_code != "") {
        $promotion_box .= '<input name="promotion_code" type="text" value="' . $promotion_code . '" size="10" maxlength="10"/>
                <button name="applyBtn" type="submit" class="btn btn-default btn-sm"> Apply</button>
                </form>';
        if ($expire_date < date('Y-m-d')) {
            $promotion_box .= '<p>Your promotion code is expired.</p>';
        }
        else{$promotion_box .= '<p><strong>Sub Total: $' . $cart_total . '</strong></p>';
            if ($discount_price != "" && $discount_price != 0) {
                $cart_total = $cart_total - $discount_price;
                $deduction_price = $discount_price;
                $promotion_box .= '<p>Discount Price: $' . $discount_price . '</p>';
            }
            else if ($discount_percentage != "" && $discount_percentage != 0) {
                $percentage_discount = round($cart_total * $discount_percentage, 2);
                $cart_total = $cart_total - $percentage_discount;
                $deduction_price = $percentage_discount;
                setlocale(LC_MONETARY, "en_US");
                $percentage_discount = money_format("%10.2n", $percentage_discount);
                $promotion_box .= '<p>Discount Price: ' . $percentage_discount . '</p>';
            }
            $_SESSION["deduction_price"] = $deduction_price;
        }
        $promotion_box .= '<p><a href="cart.php?cancel=cancelCode" class="btn btn-default btn-sm"> Cancel this Code</a></p>';
       
    }else{
        $promotion_box .= '<input name="promotion_code" type="text" value="" size="10" maxlength="10"/>
                <button name="applyBtn" type="submit" class="btn btn-default btn-sm"> Apply</button>
                </form>';
    }
    $promotion_box .= '</div>';
    setlocale(LC_MONETARY, "en_US");
    $cart_total = money_format("%10.2n", $cart_total);
    $cart_total = '<div><p align="right"><strong>Cart Total: ' . $cart_total . '</strong></p></div>';
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Shopping Cart</title> 
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
            <h3>Your Shopping Cart</h3>
        	<table class="table table-hover"> 
			     <tr> 
			         <th>Product</th> 
			         <th>Unit Price</th> 
			         <th>Quantity</th> 
			         <th>Total</th> 
			         <th>Remove</th> 
			     </tr> 
<?php echo $cart_output; ?>
			 </table>
            <hr>
<?php echo $promotion_box; ?>        
<?php echo $cart_total; ?>
            <p><a href="product_list.php" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-hand-left"></span> Continue Shopping</a></p>  
            <p><a href="cart.php?cmd=emptycart" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span> Empty Your Shopping Cart</a></p>
            <p><a href="billinginfo.php" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-ok"></span> Checkout</a></p>  
                
        </div><!--end of main-->
    </div><!--end container--> 
</body>
</html>