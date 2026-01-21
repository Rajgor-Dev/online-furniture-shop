<!DOCTYPE html>
<?php 
session_start();
include 'db.php';
if (!isset($_SESSION['UID'])) {
    header("Location: index.php"); 
    exit();
}
// Fetch orders for the logged-in user
$customer_id = $_SESSION['UID'];
$order_query = "SELECT o.id AS order_id, o.order_date, o.total_amount, o.status 
                FROM `order` o 
                WHERE o.customer_id = $customer_id 
                ORDER BY o.order_date DESC"; // Order by order_date in descending order
$order_result = mysqli_query($conn, $order_query);
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Home Decor - Furniture</title> <!-- Set the title -->
    <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="menu-bar">
        <div class="menu-icons">
            <a href="index.php" title="Home"><img src="img/icon/home.jpg" alt="Home-icon" class="icon" /></a>
            <a href="customerProfile_manage.php" title="Profile"><img src="img/icon/user.jpg" alt="Profile-icon" class="icon" /></a>
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
            <a href="cart.php" title="View Cart"><img src="img/icon/add-to-cart.jpg" alt="Add to Cart" class="icon" /></a>
        </div>
    </nav>


<!-- Modal for No Orders Message -->
<div class="modal" id="noOrdersModal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeNoOrdersModal()">&times;</span>
        <h2>No Orders Found</h2>
        <p>You have no orders yet.</p>
        <button class="toggle-button" onclick="redirectToHome()">OK</button>
    </div>
	<script> 
	function closeNoOrdersModal() {
    document.getElementById('noOrdersModal').style.display = 'none';
}

function redirectToHome() {
    closeNoOrdersModal(); // Close the modal
    window.location.href = 'index.php'; // Redirect to home page
}
	</script>
</div>
     <div class="container">
        <h2>Your Orders</h2>
       <?php if (mysqli_num_rows($order_result) == 0): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('noOrdersModal').style.display = 'block';
        });
    </script>
<?php else: ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($order_result)): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td>&#8377; <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td>
                                <a href="bill.php?id=<?php echo $order['order_id']; ?>" class="view-button">View</a>
                                <?php if ($order['status'] === 'Pending'): ?>
                                    <form method="POST" action="cancel_order.php" style="display:inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <button  type="submit" class="cancel-button" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>