<?php
session_start();
include 'db.php';

if (!isset($_SESSION['UID'])) {
    header("Location: index.php"); 
    exit;
}

$customer_id = $_SESSION['UID'];
$customer_query = "SELECT * FROM customer WHERE id = $customer_id";
$customer_result = mysqli_query($conn, $customer_query);
$customer = mysqli_fetch_assoc($customer_result);

$cities = [];
$query = "SELECT * FROM cities";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cities[] = $row;
    }
}

$total_amount = 0; 

$last_order_query = "SELECT address,city FROM `order` WHERE customer_id = $customer_id ORDER BY order_date DESC LIMIT 1 ";
$last_order_result = mysqli_query($conn, $last_order_query);
$last_order = mysqli_fetch_assoc($last_order_result);

$last_shipping_address = $last_order ? $last_order['address'] : '';
$last_city = $last_order ? $last_order['city'] : '';

// Check if the buy button is clicked
if (isset($_GET['id'])) {
    $product_id = $_GET['id']; 
    $quantity = 1;

    $product_query = "SELECT name, price FROM product WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);
    $product = mysqli_fetch_assoc($product_result);

    if ($product) {
        $total_amount = $product['price'] * $quantity; 
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Decor - Furniture</title>
    <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
    <link rel="stylesheet" href="usercss/shipping.css">
    <script>
        function confirmOrder() {
		const shippingAddress = document.getElementById("shipping_address").value.trim();
        const city = document.getElementById("city").value.trim();
        const confirmation = confirm("Are you sure you want to place this order?");
        
        if (!shippingAddress) {
            alert("Please fill in the shipping address.");
        }
       else if (!city) {
            alert("Please select a city."); 
        }
		else if (confirmation) {
            document.getElementById("orderForm").submit();
        }

        }

        function showUPIModal() {
            document.getElementById("upiModal").style.display = "block"; 
        }

        function closeModal() {
            document.getElementById("upiModal").style.display = "none"; 
        }
    </script>
</head>
<body>
	<button class="back-button" onclick="window.location.href='cart.php'">Back to Cart</button> 
    <div class="container">
		<h2>ADD Shipping Information</h2>
        <form id="orderForm" action="process_order.php" method="POST">
            <div class="form-group">
                <label for="full_name">Name:</label>
                <input type="text" id="full_name" name="name" value="<?php echo $customer['name']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $customer['email']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="tel" id="contact_number" name="contact_number" value="<?php echo $customer['contact_no']; ?>" readonly>
            </div>

            <div class="form-group">
				<label for="shipping_address">Shipping Address:</label>
				<textarea id="shipping_address" name="shipping_address" rows="4" cols="30" required><?php echo htmlspecialchars($last_shipping_address); ?></textarea>
			</div>

           <div class="form-group">
                <label for="city">City:</label>
                <select id="city" name="city" required>
                    <option value="">Select a city</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?php echo $city['city_name']; ?>" <?php echo ($city['city_name'] == $last_city) ? 'selected' : ''; ?>>
                            <?php echo $city['city_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="state">State:</label>
                <select id="state" name="state" required>
                    <option value="Gujarat" selected><?php echo $city['state_name']; ?></option>
                </select>
            </div>

            <div class="form-group">
                <label>Payment Method:</label>
                <div>
                    <input type="radio" id="cod" name="payment_method" value="COD" checked>Cash on Delivery 
                    
                </div>
                <div>
                    <input type="radio" id="upi" name="payment_method" value="UPI" onclick="showUPIModal()">UPI
                 
                </div>
            </div>

            <h3>Your Order</h3>
             <table class="product-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_GET['id'])): ?>
                       <tr>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $quantity; ?></td>
                        <td><?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo number_format($total_amount, 2); ?></td>
                       </tr>
				<input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>" />
                <?php elseif (!isset($_GET['id'])) : ?>
				<?php
				$cart_query = "SELECT sc.product_id, sc.quantity, p.name, p.price 
							FROM cart sc 
							JOIN product p ON sc.product_id = p.id 
							WHERE sc.customer_id = $customer_id";
				$cart_result = mysqli_query($conn, $cart_query);

				if (!$cart_result) {
					die("Query failed: " . mysqli_error($conn));
				}

				if (mysqli_num_rows($cart_result) == 0): ?>
				<tr>
					<td colspan="4">No products in the cart.</td>
				</tr>
				<?php else: ?>
				<?php while ($products = mysqli_fetch_assoc($cart_result)): 
				$product_total = $products['price'] * $products['quantity'];
				$total_amount = $total_amount + $product_total;
				?>
				<tr>
					<td><?php echo htmlspecialchars($products['name']); ?></td>
					<td><?php echo htmlspecialchars($products['quantity']); ?></td>
					<td><?php echo number_format($products['price'], 2); ?></td>
					<td><?php echo number_format($product_total, 2); ?></td>
				</tr>
				<?php endwhile; ?>
				<?php endif; ?>                        
                <?php endif; ?>
                </tbody>
            </table>

			<div class="form-group" style="display: flex; align-items: center; margin-top: 10px;">
				<label for="total_amount" style="margin-right: 10px;">Payable Amount:</label>
				<input type="text" id="total_amount" name="total_amount" value="<?php echo number_format($total_amount, 2); ?>" readonly style="flex: 1; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;">
			</div>

			<button type="submit" class="confirm-order" onclick="confirmOrder()">Order</button>
        </form>
    </div>

    <div id="upiModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Scan the below QR code</h2>
            <img src="img/icon/qr_code.jpg" alt="UPI QR Code">
            <p style="color: red;">After payment, please send a screenshot to <strong>+91 7041411208</strong>.</p>
        </div>
    </div>

    <script>
        window.onclick = function(event) {
            if (event.target == document.getElementById("upiModal")) {
                closeModal();
            }
        }
    </script>
</body>
</html>