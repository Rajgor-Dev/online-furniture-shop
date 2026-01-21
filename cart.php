<?php
session_start();
include 'db.php';

if (!isset($_SESSION['UID'])) {
    $loginMessage = "Please log in to view your shopping cart.";
} else {
    $user_id = $_SESSION['UID'];
    $query = "SELECT p.id, p.name, p.price, p.img, sc.quantity, p.quantity as available_quantity 
              FROM cart sc 
              JOIN product p ON sc.product_id = p.id 
              WHERE sc.customer_id = $user_id";
    $result = mysqli_query($conn, $query);

    $cartItems = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cartItems[] = $row;
        }
    }

    if (isset($_GET['remove_id'])) {
        $remove_id = intval($_GET['remove_id']);
        $deleteQuery = "DELETE FROM cart WHERE customer_id = $user_id AND product_id = $remove_id";
        mysqli_query($conn, $deleteQuery);
        header("Location: cart.php"); 
        exit();
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
    <link rel="stylesheet" href="style.css">
    <script>
        function updateQuantity(productId, quantity, availableQuantity) {
            if (quantity > availableQuantity) {
                // Show modal if requested quantity exceeds available quantity
                document.getElementById('quantityMessage').innerText = `Only ${availableQuantity} available.`;
                document.getElementById('quantityMessageModal').style.display = 'block';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Reload the page to reflect changes
                    location.reload();
                }
            };
            xhr.send("product_id=" + productId + "&quantity=" + quantity);
        }
    </script>
    <style>
    
 .buttons button {
            background-color: #007bff; /* Blue background */
            color: white; 
            border: none; 
            border-radius: 5px; 
            padding: 10px 20px; 
            font-size: 16px;
            cursor: pointer; 
            transition: background-color 0.3s, transform 0.2s; /* Smooth transition for hover effects */
            margin: 5px; 
			margin-bottom: 20px;
        }

        .buttons button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-2px); /* Slight lift effect on hover */
        }

        .buttons button:disabled {
            background-color: #ccc; /* Gray background for disabled buttons */
            cursor: not-allowed; /* Not allowed cursor for disabled buttons */
        }
    </style>
</head>
<body>
     <nav class="menu-bar">
        <div class="menu-icons">
            <a href="index.php" title="Home"><img src="img/icon/home.jpg" alt="Home-icon" class="icon" /></a>
            <?php if (isset($_SESSION['UID'])): ?>
             <a href="customerProfile_manage.php" title="Profile"><img src="img/icon/user.jpg" alt="Profile-icon" class="icon" /></a>
            <?php endif; ?>
            <a href="product_display.php" title="Product"><img src="img/icon/sofa.jpg" alt="category-icon" class="icon" /></a>    
            <a href="contact.php" title="Contact Us"><img src="img/icon/about.jpg" alt="Contact Us-icon" class="icon" /></a>
            <a href="feedback.php" title="Feedback"><img src="img/icon/feedback.jpg" alt="feedback-icon" class="icon" /></a>
        </div>
        <form method="GET" action="product_display.php">
            <div class="search-container">
                <input type="text" name="search_query" placeholder="Search..." required />
                <button type="submit"><img src="img/icon/search.jpg" alt="Search" class="icon" /></button>
            </div>
        </form>
		<div class="add-to-cart-icon">
         <?php if (isset($_SESSION['UID'])): ?>
         <a href="order_history.php" title="Order History"><img src="img/icon/order-history.jpg" alt="Order History" class="icon" /></a>
         <?php endif; ?>
        </div>
    </nav>
    
    <header>
        <h2>Your Shopping Cart</h2>
    </header>

        <table>
        <?php if (isset($loginMessage)): ?>
		 <tr>
            <td colspan="6" style="text-align: center; color: red;">
            <h2 style="text-align: center; color: red;"><?php echo $loginMessage; ?></h2>
            <div class="buttons">
                <button onclick="location.href='index.php'">Login Here</button>
            </div> 
			 </td>
          </tr>
			</table>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($cartItems)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: red;">
                            <h3>Your cart is currently empty.</h3>
                            <p>It looks like you haven't added anything to your cart yet. Start shopping now!</p>
                            <div class="buttons">
                                <button onclick="location.href='product_display.php'">Browse Products</button>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $totalPrice = 0; 
                    foreach ($cartItems as $item): 
                        $itemTotal = $item['price'] * $item['quantity'];
                        $totalPrice += $itemTotal; 
                    ?>
                        <tr>
                            <td><a href="?remove_id=<?php echo $item['id']; ?>" style="color: red;">Remove</a></td>
                            <td><img src="<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 200px; height: auto;"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>&#8377;<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>, <?php echo $item['available_quantity']; ?>)" <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>-</button>
                                <input type="number" value="<?php echo $item['quantity']; ?>" min="1" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value, <?php echo $item['available_quantity']; ?>)">
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>, <?php echo $item['available_quantity']; ?>)">+</button>
                            </td>
                            <td>&#8377;<?php echo number_format($itemTotal, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="shipping-process">
                <p class="grand-total"><strong>Grand Total: &nbsp;&nbsp;&#8377;<?php echo number_format($totalPrice, 2); ?></strong></p>
                <div class="buttons">
                    <button onclick="location.href='product_display.php'">Continue Shopping</button>
                    <button onclick="location.href='shipping.php'" name="shipping" align="right">Proceed to Shipping</button>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

<div class="modal" id="quantityMessageModal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('quantityMessageModal').style.display='none'">&times;</span>
        <h2>Quantity Exceeded</h2>
        <p id="quantityMessage">Only available quantity can be added to the cart.</p>
        <button class="toggle-button" onclick="document.getElementById('quantityMessageModal').style.display='none'">OK</button>
    </div>
</div>

<script>
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target == document.getElementById('quantityMessageModal')) {
            document.getElementById('quantityMessageModal').style.display = "none";
        }
    }
</script>