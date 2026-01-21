<?php
session_start();
include 'db.php';

// Get product ID from the URL and sanitize it
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query the database to fetch product details
$sql = "SELECT * FROM product WHERE id = '$product_id'";
$result = mysqli_query($conn, $sql);

// Check if product exists
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "Product not found.";
    exit; // Stop execution if product is not found
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
    <link rel="stylesheet" href="usercss/product_details.css"> 
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
            <a href="cart.php" title="View Cart"><img src="img/icon/add-to-cart.jpg" alt="Add to Cart" class="icon" /></a>
            <?php if (isset($_SESSION['UID'])): ?>
                <a href="order_history.php" title="Order History"><img src="img/icon/order-history.jpg" alt="Order History" class ="icon" /></a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="product-detail-container">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
        </div>
        <div class="product-info1">
            <h1><?php echo htmlspecialchars($row['name']); ?></h1>
            <p class="price">&#8377;<?php echo number_format($row['price'], 2); ?></p>
            <p class="quantity">Available Quantity: <?php echo htmlspecialchars($row['quantity']); ?></p>
            <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
            <?php if ($row['quantity'] > 0): ?>
                <form method="POST" action="">
                 <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                 <button type="submit" name="cart" class="add-to-cart">Add to Cart</button>
	             <button type="button" class="buy-product" onclick="window.location.href='shipping.php?id=<?php echo urlencode($row['id']); ?>'">Buy Product</button>
                </form>
            <?php else: ?>
                <p class="out-of-stock">Out of Stock</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
   include 'addcartlogic.php';
   ?>
</body>
</html>