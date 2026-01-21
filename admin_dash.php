<?php
session_start();
include 'db.php';
if (!isset($_SESSION['Email'])) {
    header("Location: index.php"); 
    exit();
}


// Pagination setup for categories
$categoryLimit = 5; 
$categoryPage = isset($_GET['category_page']) ? (int)$_GET['category_page'] : 1; 
$categoryOffset = ($categoryPage - 1) * $categoryLimit; 
$categoryQuery = "SELECT id, name, img FROM category LIMIT $categoryLimit OFFSET $categoryOffset";
$categoryResult = mysqli_query($conn, $categoryQuery);

// Count total categories for pagination
$totalCategoriesCountQuery = "SELECT COUNT(*) as total FROM category";
$totalCategoriesCount = mysqli_fetch_assoc(mysqli_query($conn, $totalCategoriesCountQuery))['total'];
$totalCategoryPages = ceil($totalCategoriesCount / $categoryLimit); 

// Pagination setup for product
$productLimit = 5; 
$productPage = isset($_GET['product_page']) ? (int)$_GET['product_page'] : 1; 
$productOffset = ($productPage - 1) * $productLimit; 
$productQuery = "SELECT img, name, price FROM product LIMIT $productLimit OFFSET $productOffset";
$productResult = mysqli_query($conn, $productQuery);

// Count total products for pagination
$totalProductsCountQuery = "SELECT COUNT(*) as total FROM product";
$totalProductsCount = mysqli_fetch_assoc(mysqli_query($conn, $totalProductsCountQuery))['total'];
$totalProductPages = ceil($totalProductsCount / $productLimit); // Calculate total product pages

// Fetch orders for the current month
$currentMonth = date('Y-m'); 

// Pagination setup for order
$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit;
$orderQuery = "SELECT o.id, c.name AS customer_name, o.total_amount, o.order_date, o.address, o.city, o.state, o.status 
               FROM `order` o
               JOIN customer c ON o.customer_id = c.id
               WHERE DATE_FORMAT(o.order_date, '%Y-%m') = '$currentMonth'
               ORDER BY 
                o.id DESC
			   LIMIT $limit OFFSET $offset";

$orderResult = mysqli_query($conn, $orderQuery);
if (!$orderResult) {
    die("Error fetching orders: " . mysqli_error($conn));
}

// Count total orders for pagination
$totalOrdersCountQuery = "SELECT COUNT(*) as total FROM `order` WHERE DATE_FORMAT(order_date, '%Y-%m') = '$currentMonth'";
$totalOrdersCount = mysqli_fetch_assoc(mysqli_query($conn, $totalOrdersCountQuery))['total'];
$totalPages = ceil($totalOrdersCount / $limit); 


// Fetch total orders for the order status section
$totalOrdersQuery = "SELECT COUNT(*) as total FROM `order`";
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, $totalOrdersQuery))['total'];

$deliveredOrdersQuery = "SELECT COUNT(*) as delivered FROM `order` WHERE status = 'delivered'";
$deliveredOrders = mysqli_fetch_assoc(mysqli_query($conn, $deliveredOrdersQuery))['delivered'];

$pendingOrdersQuery = "SELECT COUNT(*) as pending FROM `order` WHERE status = 'pending'";
$pendingOrders = mysqli_fetch_assoc(mysqli_query($conn, $pendingOrdersQuery))['pending'];

$canceledOrdersQuery = "SELECT COUNT(*) as canceled FROM `order` WHERE status = 'cancelled'";
$cancelledOrders = mysqli_fetch_assoc(mysqli_query($conn, $canceledOrdersQuery))['canceled'];

?>

<html>
<head>
    <title>Home Decor - Furniture</title> 
    <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
    <link rel="stylesheet" href="admincss/admin_style.css">
</head>
<body>
    <header>
        <div class="logo-container">
		<form action="logout.php" method="POST" style="display:inline;">
        <button type="submit" class="logout-button">Logout</button>
          </form>
            <img src="img/icon/shop.jpg" height="100px" width="150px" alt="Logo" />
            <div class="text-container">
                <h1>HOME DECOR</h1>
                <h2>F U R N I T U R E</h2>
            </div>  
        </div>
    </header>

<div class="welcome-message">
    <a href="adminProfile_manage.php" style="text-decoration: none; color: inherit;">Welcome, Boss!</a>
</div>
<hr style="border: 4px solid #e5e5e5; margin: 5px 0;">
	
<div class="order-status-container">
    <div class="order-status blue" onclick="window.location.href='order_manage.php?status=all'">
        <p class="order-number"><?php echo $totalOrders; ?></p>
        <p>Total Orders</p>
    </div>
    <div class="order-status green" onclick="window.location.href='order_manage.php?status=Delivered'">
        <p class="order-number"><?php echo $deliveredOrders; ?></p>
        <p>Delivered Orders</p>
    </div>
    <div class="order-status yellow" onclick="window.location.href='order_manage.php?status=Pending'">
        <p class="order-number"><?php echo $pendingOrders; ?></p>
        <p>Pending Orders</p>
    </div>
    <div class="order-status red" onclick="window.location.href='order_manage.php?status=Cancelled'">
        <p class="order-number"><?php echo $cancelledOrders; ?></p>
        <p>Cancelled Orders</p>
    </div>
</div>
	
    <h2>Your Orders</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Total Amount</th>
                <th>Order Date</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($orderRow = mysqli_fetch_assoc($orderResult)): ?>
                <tr>
                    <td><?php echo $orderRow['id']; ?></td>
                    <td><?php echo htmlspecialchars($orderRow['customer_name']); ?></td>
                    <td> &#8377;<?php echo number_format($orderRow['total_amount'], 2); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($orderRow['order_date'])); ?></td>
                    <td><?php echo htmlspecialchars($orderRow['address']); ?></td>
                    <td><?php echo htmlspecialchars($orderRow['city']); ?></td>
                    <td><?php echo htmlspecialchars($orderRow['state']); ?></td>
                    <td><?php echo htmlspecialchars($orderRow['status']); ?></td>
                    <td>
                        <a href="orderbill.php?id=<?php echo $orderRow['id']; ?>" class="view-button">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
  <hr style="border: 4px solid #ccc; margin: 20px 0;">
 <div class="container-grid">
    <a href="customer_manage.php" class="container customer-manage">
        <h3>Customer Manage</h3>
    </a>
	<a href="category_manage.php" class="container category-manage">
        <h3>Category Manage</h3>
    </a>
    <a href="product_manage.php" class="container product-manage">
        <h3>Product Manage</h3>
    </a>
    <a href="order_manage.php" class="container order-manage">
        <h3>Order Manage</h3>
    </a>
    <a href="feedback_manage.php" class="container feedback-manage">
        <h3>Feedback Manage</h3>
    </a>
</div>

<hr style="border: 3px solid #ccc; margin: 20px 0;">

<h2>Available Categories</h2>
  <div class="category-grid">
        <?php while ($categoryRow = mysqli_fetch_assoc($categoryResult)): ?>
            <div class="category-box">
                <img src="<?php echo $categoryRow['img']; ?>" alt="<?php echo $categoryRow['name']; ?>">
                <p><?php echo htmlspecialchars($categoryRow['name']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
	
    <div class="pagination">
        <a href="?category_page=<?php echo max(1, $categoryPage - 1); ?>"> &lt; </a>
        <?php for ($i = 1; $i <= $totalCategoryPages; $i++): ?>
            <a href="?category_page=<?php echo $i; ?>" class="<?php echo ($i == $categoryPage) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        <a href="?category_page=<?php echo min($totalCategoryPages, $categoryPage + 1); ?>"> &gt; </a>
   </div>
	
<hr style="border: 3px solid #ccc; margin: 20px 0;">

    <h2>Available Products</h2>
   <div class="product-grid">
    <?php while ($row = mysqli_fetch_assoc($productResult)): ?>
        <div class="product-box">
            <img src="<?php echo $row['img']; ?>" alt="<?php echo $row['name']; ?>">
            <p><?php echo htmlspecialchars($row['name']); ?></p>
            <p>Rs.<?php echo number_format($row['price'], 2); ?></p>
        </div>
    <?php endwhile; ?>
    </div>

   <div class="pagination">
    <a href="?product_page=<?php echo max(1, $productPage - 1); ?>"> &lt; </a>
    <?php for ($i = 1; $i <= $totalProductPages; $i++): ?>
        <a href="?product_page=<?php echo $i; ?>" class="<?php echo ($i == $productPage) ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
    <a href="?product_page=<?php echo min($totalProductPages, $productPage + 1); ?>"> &gt; </a>
    </div>
	
<hr style="border: 3px solid #ccc; margin: 20px 0;">
	<div class="footer">
        <p>© 2025 Furniture Shop. All rights reserved.</p>
    </div>
<?php
mysqli_close($conn);
?>
</body> </html>