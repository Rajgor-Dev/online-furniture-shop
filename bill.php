<?php 
session_start();
include 'db.php';
if (!isset($_SESSION['UID'])) {
    header("Location: index.php"); 
    exit;
}
if (!isset($_GET['id'])) {
    die("Order ID not specified.");
}
$order_id = intval($_GET['id']); 

//Fetch order details, including payment information
$sql = "SELECT 
            o.id AS order_id, 
            o.customer_id, 
            c.name AS customer_name, 
            c.contact_no, 
            o.address, 
            o.city, 
            o.state, 
            o.order_date, 
            o.total_amount,
            od.product_id,
            od.quantity,
            od.price,
            p.type,
            p.status AS payment_status,
            o.status AS order_status,
            pr.name AS product_name  
        FROM 
            `order` o
        JOIN 
            `customer` c ON o.customer_id = c.id
        JOIN 
            `order_detail` od ON o.id = od.order_id
        JOIN 
            `payment` p ON o.id = p.order_id
        JOIN 
            `product` pr ON od.product_id = pr.id  
        WHERE 
            o.id = $order_id";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	$orderDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // Get the first row for customer and order info
    $firstRow = $orderDetails[0];
?>
    
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home Decor - Furniture</title>
<link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
<link rel="stylesheet" href="bill.css">
</head>
<body>
    <a href="order_history.php" class="back-button">Back to Orders</a>
    <div class="bill">
		<h1>HOME DECOR</h1>
		<p class="gst-number"><strong>GST Number:</strong> 24ACXP129225H12ZX</p>
		<hr>
		<div class="customer-info">
			<p><strong>Customer Name:</strong> <?php echo $firstRow['customer_name']; ?> 
			<span class="value-space"></span> 
			<strong>Contact No:</strong> <?php echo $firstRow['contact_no']; ?></p>
			<p><strong>Address:</strong> <?php echo $firstRow['address']; ?></p>
			<p><strong>City:</strong> <?php echo $firstRow['city']; ?> 
			<span class="value-space"></span> 
			<strong>State:</strong> <?php echo $firstRow['state']; ?></p>
		</div>
        <hr>
        <div class="order-info">
			<p><strong>Order ID:</strong> <?php echo $firstRow['order_id']; ?> 
			<span class="value-space"></span> 
			<strong>Order Date:</strong> <?php echo $firstRow['order_date']; ?></p>
			<p><strong>Order Status:</strong> <?php echo $firstRow['order_status']; ?></p>
            <p><strong>Payment Type:</strong> <?php echo $firstRow['type']; ?></p>
            <p><strong>Payment Status:</strong> <?php echo $firstRow['payment_status']; ?></p>
        </div>
        <hr>
        <h2>Product Details</h2>
        <table class="product-table">
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
       <?php
        $totalQty = 0;
        $subTotal = 0;

        foreach ($orderDetails as $detail) {
            echo "<tr>
            <td>{$detail['product_id']}</td>
            <td>{$detail['product_name']}</td>
            <td>{$detail['quantity']}</td>
            <td>&#8377; " . number_format($detail['price'], 2) . "</td>
            </tr>";
            $totalQty += $detail['quantity'];
            $subTotal += $detail['price'] * $detail['quantity'];
        }
        ?>
        </table>
        <hr>
        <p class="total">Total Quantity: <?php echo $totalQty; ?> <span class="value-space"></span>
        Sub Total: &#8377; <?php echo number_format($subTotal, 2); ?></p>
        <hr>
        <p class="total" style="font-weight: bold;">Grand Total: &#8377; <?php echo number_format($subTotal, 2); ?></p>
        <hr>
        <p align="center">Thank you for your order!</p>
    </div>
</body>
</html>
    <?php
} else {
    echo "No order found with the specified ID.";
}
mysqli_free_result($result);
mysqli_close($conn);
?>