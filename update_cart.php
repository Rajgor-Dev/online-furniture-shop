<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $user_id = $_SESSION['UID'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity < 1) {
        // If quantity is less than 1, remove the item from the cart
        $deleteQuery = "DELETE FROM cart WHERE customer_id = $user_id AND product_id = $product_id";
        mysqli_query($conn, $deleteQuery);
    } else {
        $updateQuery = "UPDATE cart SET quantity = $quantity WHERE customer_id = $user_id AND product_id = $product_id";
        mysqli_query($conn, $updateQuery);
    }
}
?>