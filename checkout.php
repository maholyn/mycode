<?php
session_start();
require("connection.php");
require("uspsrate.php");
?>
<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
if(isset($_POST['email'])) {
    $discount = "";
    if(isset($_SESSION["deduction_price"])){
        $discount = $_SESSION["deduction_price"];
    }   

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $email_to = $_POST['email'];
    $phone = $_POST['phone'];

    $_SESSION["billing_info"] = array("first_name" => $first_name, "last_name" => $last_name, "street" => $street, "city" => $city, "state" => $state, "zip" => $zip, "email_to" => $email_to, "phone" => $phone);
}
?>
<?php
if(isset($_POST['submit'])) {
    $discount = "";
    if(isset($_SESSION["deduction_price"])){
        $discount = $_SESSION["deduction_price"];
    }
    //if (isset($_SESSION["billing_info"])){

    
        $email_from = 'info@maholyn.com';
        $subject = 'Order confirmation';

        $shipping = $_POST['shipping_cost'];
        


    //$to = $email_to;
    $to = 'maholynyc@gmail.com';
    $email_message = "Your Billing Info:\n\n";
    //$email_message .= "First Name: " . $first_name . "\n";
    //$email_message .= "Last Name: ".$last_name."\n";
    //$email_message .= "Street: ".$street."\n";
    //$email_message .= "City: ".$city."\n";
    //$email_message .= "State: ".$state."\n";
    //$email_message .= "Zip: ".$zip."\n";
    //$email_message .= "Email: ".$email_to."\n";
    //$email_message .= "Phone: ".$phone."\n";
 
  
$email_message .= "\n".'========================================================'."\n";
$email_message .= 'Your Placed Order:'."\n\n";


$i = 0;
$cart_total = "";
    foreach ($_SESSION["cart_array"] as $item) {
        $item_id = $item['item_id'];
        $sql = mysql_query("SELECT * FROM product WHERE product_id='$item_id' LIMIT 1");
        while ($row = mysql_fetch_array($sql)) {
            $product_name = $row["product_name"];
            $price = $row["price"];
            $stock = $row["stock"];
            $weight = $row["weight"];
            
        }
        if ($item['quantity']>12 && $item['quantity']<97) {
           $price = round($price * 0.9, 2);
        }
        else if ($item['quantity']>96){
            $price = round($price * 0.8, 2);
        }
        $priceTotal = $price * $item['quantity'];
        $cart_total = $priceTotal + $cart_total;

        setlocale(LC_MONETARY, "en_US");
        $priceTotal = money_format("%10.2n", $priceTotal);
     
        $email_message .= "Product Name: " . $product_name . "\n";
        $email_message .= "Price: $" . $price . "\n";
        $email_message .= "Quantity: " . $item['quantity'] . "\n";
        $email_message .= "Total: " . $priceTotal . "\n\n";
  
        $stock = $stock - $item['quantity'];
        $sql = mysql_query("UPDATE product SET stock='$stock' WHERE product_id='$item_id'");
        $i++;
    }

    $email_message .= "SubTotal: $" . $cart_total ."\n";

    if ($discount != "") {
        $email_message .= "Discount Price: -$" . $discount ."\n";
        $cart_total = $cart_total - $discount;
       } 

    $email_message .= "Shipping Cost: $". $shipping ."\n";
      
    setlocale(LC_MONETARY, "en_US");
    $cart_total = money_format("%10.2n", $cart_total);

    $email_message .= "Grand Total: ". $cart_total ."\n";

    

$headers = 'From: ' .$email_from."\r\n".
'Reply-To: '.$email_from."\r\n".
'X-Mailer: PHP/' . phpversion();


mail($to, $subject, $email_message, $headers);

// Empty cart
session_destroy();
Header('Location: shoppingSuccess.php');
//}
}
?>
<?php
// Display the cart
$cart_output = "";
$discount_output = "";
$cart_weight = "";
$cart_total = "";
$cart_total_output = "";
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
            $weight = $row["weight"];
        }
        if ($item['quantity']>12 && $item['quantity']<97) {
           $price = round($price * 0.9, 2);
        }
        else if ($item['quantity']>96){
            $price = round($price * 0.8, 2);
        }
        $priceTotal = $price * $item['quantity'];
        $cart_total = $priceTotal + $cart_total;

        $item_weight = $weight * $item['quantity'];
        $cart_weight = $item_weight + $cart_weight;

        setlocale(LC_MONETARY, "en_US");
        $priceTotal = money_format("%10.2n", $priceTotal);

        $cart_output .= '<tr>';
        $cart_output .= '<td><img src="./images/' . $item_id . '.jpg" alt=' . $product_name . ' width="auto" height="100"/>&nbsp;&nbsp;' . $product_name . '</td>';
        $cart_output .= '<td>$' . $price . '</td>';
        $cart_output .= '<td>' . $item['quantity'] . '</td>';
        $cart_output .= '<td>' . $priceTotal . '</td>';
        $cart_output .= '</tr>';
        $i++;
    } 
    $discount_output .= '<div align="right"><p>SubTotal: $' . $cart_total .'<p>';
    if ($discount != "") {
        
        $cart_total = $cart_total - $discount;
        setlocale(LC_MONETARY, "en_US");
        $discount = money_format("%10.2n", $discount);
        $discount_output .= '<p>Discount Price: -' . $discount .'<p></div>';
        
       }
    
    $shipping_price = USPSRate($cart_weight, $zip);
    $cart_total = $shipping_price + $cart_total;
    setlocale(LC_MONETARY, "en_US");
    $cart_total = money_format("%10.2n", $cart_total);
    $cart_total_output .= '<div><p align="right">Shipping Cost: $' . $shipping_price . '</p></div>';
    $cart_total_output .= '<div><p align="right"><strong>Cart Total: ' . $cart_total . '</strong></p></div>';
    
}
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
            <h3>Your Order Summary</h3>
            <table class="table table-striped"> 
                 <tr> 
                     <th>Product</th> 
                     <th>Unit Price</th> 
                     <th>Quantity</th> 
                     <th>Total</th> 
                 </tr> 
<?php echo $cart_output; ?>
            </table>
            <hr>
<?php echo $discount_output; ?>           
<?php echo $cart_total_output; ?>
            <hr>
            <h3>Your Billing Info</h3>
            <table class="table">
                    <tbody>
                        <tr>
                            <td>First Name: </td>
                            <td><?php echo $first_name; ?></td>
                        </tr>
                        <tr>
                            <td>Last Name: </td>
                            <td><?php echo $last_name; ?></td>
                        </tr>
                        <tr>
                            <td>Street: </td>
                            <td><?php echo $street; ?></td>
                        </tr>
                        <tr>
                            <td>City: </td>
                            <td><?php echo $city; ?></td>
                        </tr>
                        <tr>
                            <td>State: </td>
                            <td><?php echo $state; ?></td>
                        </tr>
                        <tr>
                            <td>Zip: </td>
                            <td><?php echo $zip; ?></td>
                        </tr>
                        <tr>
                            <td>Email: </td>
                            <td><?php echo $email_to; ?></td>
                        </tr>
                        <tr>
                            <td>Phone: </td>
                            <td><?php echo $phone; ?></td>
                        </tr>
                    </tbody>
                </table>
               
                    <form action="checkout.php" method="post">
                        <button name="submit" type="submit" class="btn btn-default" id="button">Submit</button>
                        <input name="shipping_cost" type="hidden" value="<?php echo $shipping_price; ?>"/>
                    </form>

        </div><!--end of main-->
    </div><!--end container--> 
</body>
</html>