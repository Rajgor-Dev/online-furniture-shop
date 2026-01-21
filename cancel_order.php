<?php
session_start();
include 'db.php';
if (!isset($_SESSION['UID'])) {
    echo "Please log in to cancel an order.";
    exit;
}

if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $orderDetailsQuery = "SELECT product_id, quantity FROM order_detail WHERE order_id = $order_id";
    $orderDetailsResult = mysqli_query($conn, $orderDetailsQuery);

    if ($orderDetailsResult) {
        // Loop through each order detail
        while ($orderDetail = mysqli_fetch_assoc($orderDetailsResult)) {
            $product_id = $orderDetail['product_id'];
            $quantity = $orderDetail['quantity'];

            // Update the product quantity in the product table
            $updateProductQuery = "UPDATE product SET quantity = quantity + $quantity WHERE id = $product_id";
            mysqli_query($conn, $updateProductQuery);
        }
    }

    // Update the order status to 'Cancelled'
    $cancel_query = "UPDATE `order` SET status = 'Cancelled' WHERE id = $order_id AND customer_id = " . $_SESSION['UID'];
    
    if (mysqli_query($conn, $cancel_query)) {
        echo "Order cancelled successfully.";
    } else {
        echo "Error cancelling order: " . mysqli_error($conn);
    }
} else {
    echo "No order ID specified.";
}
header("Location: order_history.php");
exit();
?>