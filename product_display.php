<?php
session_start();
include 'db.php';

$searchResults = [];

if (isset($_GET['search_query'])) {
    // Normalize the search query: convert to lowercase and remove spaces
    $searchQuery = strtolower(trim($_GET['search_query'])); 

    // Fetch the category ID based on the normalized search query
    $categoryQuery = "SELECT id FROM category WHERE LOWER(name) = '$searchQuery'";
    $categoryResult = mysqli_query($conn, $categoryQuery);
    
    if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
        $categoryRow = mysqli_fetch_assoc($categoryResult);
        $categoryId = $categoryRow['id'];

        // Fetch products matching the category ID
        $query = "SELECT * FROM product WHERE category_id = $categoryId";
        $result = mysqli_query($conn, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
        }
    } else {
        // If no category found, search for products by name or description
        $query = "SELECT * FROM product WHERE LOWER(name) LIKE '%$searchQuery%'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
        }
    }
} elseif (isset($_GET['category_name'])) {
    // Check if the category name is set
    $categoryName = strtolower(trim($_GET['category_name'])); // Sanitize input

    // Fetch the category ID based on the category name
    $categoryQuery = "SELECT id FROM category WHERE LOWER(name) = '$categoryName'";
    $categoryResult = mysqli_query($conn, $categoryQuery);
    
    if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
        $categoryRow = mysqli_fetch_assoc($categoryResult);
        $categoryId = $categoryRow['id'];

        // Fetch products matching the category ID
        $query = "SELECT * FROM product WHERE category_id = $categoryId";
        $result = mysqli_query($conn, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResults[] = $row;
            }
        }
    }
} else {
    // If no search query or category is provided, fetch all products
    $query = "SELECT * FROM product";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
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
    <link rel="stylesheet" href="usercss/product_display.css">
</head>
<body>

    <nav class="menu-bar">
    <div class="menu-icons">
        <a href="index.php" title="Home"><img src="img/icon/home.jpg" alt="Home-icon" class="icon" /></a>
        <?php if (isset($_SESSION['User Name'])): ?>
            <a href="customerProfile_manage.php" title="Profile"><img src="img/icon/user.jpg" alt="Profile-icon" class="icon" /></a>
        <?php else: ?>
            <a href="index.php" title="Sign-In" id="signIn"><img src="img/icon/user.jpg" alt="User-icon" class="icon" /></a>
        <?php endif; ?>
        <a href="product_display.php" title="Product"><img src="img/icon/sofa.jpg" alt="category-icon" class="icon" /></a>
		<a href="contact.php" title="Contact Us"><img src="img/icon/about.jpg" alt="Contact Us-icon" class="icon" /></a>
        <a href="feedback.php" title="Feedback"><img src="img/icon/feedback.jpg" alt="feedback-icon" class="icon" /></a>
       
    </div>
     <form method="GET" action="product_display.php">
      <div class="search-container">
        <input type="text" name="search_query" placeholder="Search..." required />
        <button type="submit"><img src="img/icon/search.jpg" alt="Search" class="icon" /></button>
    </form>
	</div>
	<div class="add-to-cart-icon">
	 <a href="cart.php" title="View Cart"><img src="img/icon/add-to-cart.jpg" alt="Add to Cart" class="icon" /></a>
	 <?php if (isset($_SESSION['UID'])): ?>
	 <a href="order_history.php" title="Order History"><img src="img/icon/order-history.jpg" alt="Order History" class="icon" /></a>
	 <?php endif; ?>
	 </div>
</nav>

<section class="search-results">
    <?php if (!empty($searchResults)): ?>
        <div class="products">
            <?php foreach ($searchResults as $product): ?>
                <div class="product">
                    <div class="product-info">
                        <a href="product_details.php?id=<?php echo urlencode($product['id']); ?>">
                            <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" /> <!-- Display product image -->
                            <p><strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
                            <p>&#8377;<?php echo htmlspecialchars($product['price']); ?></p>
                        </a>
                    </div>
                                      <?php if (isset($product['quantity'])): // Check if quantity is set ?>
                        <?php if ($product['quantity'] > 0): ?>
                               <form method="POST" action="" style="display: flex; justify-content: center; gap: 10px;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="cart" class="add-to-cart">Add to Cart</button>
                                <button type="button" class="buy-product" onclick="window.location.href='shipping.php?id=<?php echo urlencode($product['id']); ?>'">Buy Product</button>
                            </form>
                        <?php else: ?>
                            <h4 style="color: red;">Out of Stock</h4>
                        <?php endif; ?>
                    <?php else: ?>
                        <p style="color: red;">Quantity information not available.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
	<div class="msg">
        <p>No products found matching your search or category.</p> </div>
    <?php endif; ?>
</section>
    <?php
   include 'addcartlogic.php';
   ?>
  <?php
   include 'footer.php';
   ?>
</body>
</html>