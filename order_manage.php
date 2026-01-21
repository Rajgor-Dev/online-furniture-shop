<?php 
session_start();
include 'db.php';
if (!isset($_SESSION['Email'])) {
    header("Location: index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Home Decor - Furniture</title> 
    <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
    <link rel="stylesheet" href="admincss/order.css">
</head>
<body>
<?php
include 'admin_menubar.php' ;
?>
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by ID or Name">
    <input type="submit" value="Search">
    <input type="submit" name="showAll" value="Show All">
</form>
<h1>All Order Records</h1>
<?php
$sql = "SELECT 
            o.id AS order_id, 
            c.name AS customer_name, 
            c.contact_no, 
            o.total_amount, 
            o.order_date, 
            o.status
        FROM 
            `order` o
        JOIN 
            `customer` c ON o.customer_id = c.id
	    ORDER BY 
                o.id DESC";
				
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
    // Modify the SQL query to search by order ID, customer name, or status
    $sql = "SELECT 
            o.id AS order_id, 
            c.name AS customer_name, 
            c.contact_no, 
            o.total_amount, 
            o.order_date, 
            o.status
        FROM 
            `order` o
        JOIN 
            `customer` c ON o.customer_id = c.id  WHERE o.id = '$searchTerm' OR c.name LIKE '$searchTerm%' OR o.status LIKE '$searchTerm%'";
}

if (isset($_GET['showAll'])) {
   
}
$result = mysqli_query($conn, $sql);
if (isset($_GET['status']) && !empty($_GET['status']) && $_GET['status'] !== 'all') {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $sql = "SELECT 
            o.id AS order_id, 
            c.name AS customer_name, 
            c.contact_no, 
            o.total_amount, 
            o.order_date, 
            o.status
        FROM 
            `order` o
        JOIN 
            `customer` c ON o.customer_id = c.id  WHERE o.status = '$status'";
}
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Contact No</th>
                <th>Total Amount</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Determine the status class
        $statusClass = '';
        switch ($row['status']) {
            case 'Pending':
                $statusClass = 'status-pending';
                break;
            case 'Shipped':
                $statusClass = 'status-shipped';
                break;
            case 'Delivered':
                $statusClass = 'status-delivered';
                break;
            case 'Cancelled':
                $statusClass = 'status-cancelled';
                break;
        }
        // Display the status with the appropriate class
        $statusText = $row['status']; 
        echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['customer_name']}</td>
                <td>{$row['contact_no']}</td>
                <td>{$row['total_amount']}</td>
                <td>{$row['order_date']}</td>
                <td class='$statusClass'>$statusText</td>
                <td><a href='orderbill.php?id={$row['order_id']}' class='view-button'>View</a></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No orders found.</p>";
}
mysqli_free_result($result);
mysqli_close($conn);
?>
</body>
</html>