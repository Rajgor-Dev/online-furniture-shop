<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['UID'])) {
    header("Location: index.php"); 
    exit;
}

// Get customer ID from session
$customer_id = $_SESSION['UID'];

// Get form data
$shipping_address = mysqli_real_escape_string($conn, $_POST['shipping_address']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$state = mysqli_real_escape_string($conn, $_POST['state']);
$payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
$total_amount = $_POST['total_amount']; // Get the raw input

// Debugging: Check the value of total_amount
//echo "Raw total amount: " . htmlspecialchars($total_amount) . "<br>"; // Debugging line

// Remove commas and any non-numeric characters (except for the decimal point)
$total_amount = preg_replace('/[^\d.]/', '', $total_amount);

// Convert to float and check if it's numeric
$total_amount = floatval($total_amount);

if (!is_numeric($total_amount) || $total_amount <= 0) {
    echo "Total amount is not a valid number.";
    exit;
}

// Format to 2 decimal places
$total_amount = number_format($total_amount, 2, '.', '');

// Insert into orders table
$order_query = "INSERT INTO `order` (customer_id, address, city, state, order_date, total_amount, status) 
                VALUES ('$customer_id', '$shipping_address', '$city', '$state', NOW(), '$total_amount', 'Pending')";

if (mysqli_query($conn, $order_query)) {
    // Get the last inserted order ID
    $order_id = mysqli_insert_id($conn);

    // Insert payment information
    $payment_query = "INSERT INTO payment (order_id, type, status) 
                      VALUES ('$order_id', '$payment_method', 'Pending')";
    mysqli_query($conn, $payment_query);

// Check if a product ID is provided
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $quantity = 1; // Assuming quantity is 1 for the specific product
        $product_query = "SELECT name, price, quantity as a_quantity FROM product WHERE id = $product_id";
        $product_result = mysqli_query($conn, $product_query);
        $product = mysqli_fetch_assoc($product_result);

        if ($product) {
            $price = $product['price'];
            $product_total = $price * $quantity;

            // Insert the specific product into order_detail
            $order_details_query = "INSERT INTO order_detail (order_id, product_id, quantity, price) 
                                    VALUES ($order_id, $product_id, $quantity, $price)";
            mysqli_query($conn, $order_details_query);

            // Update the product's available quantity
            $new_quantity = $product['a_quantity'] - $quantity;
            $update_product_query = "UPDATE product SET quantity = $new_quantity WHERE id = $product_id";
            mysqli_query($conn, $update_product_query);
        }
    } else {   
   // Fetch products from the shopping cart
    $cart_query = "SELECT sc.product_id, sc.quantity, p.price, p.quantity as a_quantity 
                   FROM cart sc 
                   JOIN product p ON sc.product_id = p.id 
                   WHERE sc.customer_id = $customer_id";
    $cart_result = mysqli_query($conn, $cart_query);

    // Insert each product into order_details table
    while ($product = mysqli_fetch_assoc($cart_result)) {
        $product_id = $product['product_id'];
        $quantity = $product['quantity'];
        $price = $product['price'];
        $product_total = $price * $quantity;

        $order_details_query = "INSERT INTO order_detail (order_id, product_id, quantity, price) 
                                VALUES ($order_id, $product_id, $quantity, $price)";
        mysqli_query($conn, $order_details_query);
		    // Update the product's available quantity
        $new_quantity = $product['a_quantity'] - $quantity;
        $update_product_query = "UPDATE product SET quantity = $new_quantity WHERE id = $product_id";
        mysqli_query($conn, $update_product_query);
    }

    // Optionally, clear the shopping cart
    $clear_cart_query = "DELETE FROM cart WHERE customer_id = $customer_id";
    mysqli_query($conn, $clear_cart_query);
	}
    header("Location: congo.php?id=" . $order_id);
    exit(); 
} else {
    $error_message = mysqli_error($conn);
    echo "<script>alert('Error placing order: " . addslashes($error_message) . "');</script>";
}

mysqli_close($conn);
?>