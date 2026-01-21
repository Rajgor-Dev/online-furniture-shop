<div class="modal" id="loginPromptModal" style="display:none;">
 <div class="modal-content">
  <span class="close" onclick="closeLoginPromptModal()">&times;</span>
  <h2>Please Log In</h2>
  <p>You need to log in to add products to your cart.</p>
  <button  class="toggle-button" onclick="location.href='index.php'">OK</button>
 </div>
</div>

<div class="modal" id="successMessageModal" style="display:none;">
 <div class="modal-content">
   <span class="close" onclick="closeSuccessMessageModal()">&times;</span>
   <h2>Success!</h2>
   <p>Product added to cart successfully.</p>
   <button  class="toggle-button" onclick="closeSuccessMessageModal()">OK</button>
 </div>
</div>

<div class="modal" id="quantityMessageModal">
  <div class="modal-content">
    <span class="close" onclick="closequanMessageModal()">&times;</span>
    <h2>Soory!</h2>
    <p>Cannot add more than available quantity.</p>
    <button class="toggle-button" onclick="closequanMessageModal()">OK</button>
  </div>      
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['cart']))  {
    if (!isset($_SESSION['UID'])) {
        // User is not logged in, show the login prompt modal
        echo "<script>document.getElementById('loginPromptModal').style.display = 'block';</script>";
    } else {
        // User is logged in, check if the product is already in the cart
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['UID'];

        // Check the available quantity of the product
        $productQuery = "SELECT quantity FROM product WHERE id = $product_id";
        $productResult = mysqli_query($conn, $productQuery);
        $productData = mysqli_fetch_assoc($productResult);
        $availableQuantity = $productData['quantity'];

        // Check if the product is already in the cart
        $checkQuery = "SELECT quantity FROM cart WHERE customer_id = $user_id AND product_id = $product_id";
        $checkResult = mysqli_query($conn, $checkQuery);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            // Product exists in the cart
            $row = mysqli_fetch_assoc($checkResult);
            $cartQuantity = $row['quantity'];

            // Check if adding one more would exceed available quantity
            if ($cartQuantity >= $availableQuantity) {
                echo "<script>document.getElementById('quantityMessageModal').style.display = 'block';</script>";
            } else {
                // Increase quantity by 1
                $newQuantity = $cartQuantity + 1;
                $updateQuery = "UPDATE cart SET quantity = $newQuantity WHERE customer_id = $user_id AND product_id = $product_id";
                mysqli_query($conn, $updateQuery);
                echo "<script>document.getElementById('successMessageModal').style.display = 'block';</script>";
            }
        } else {
           
            if ($availableQuantity > 0) {
                $quantity = 1; 
                $insertQuery = "INSERT INTO cart (customer_id, product_id, quantity, add_on) VALUES ($user_id, $product_id, $quantity, NOW())";
                mysqli_query($conn, $insertQuery);
                echo "<script>document.getElementById('successMessageModal').style.display = 'block';</script>";
            } else {
                echo "<script>alert('Product is out of stock.');</script>";
            }
        }
    }
}
?>

<script type="text/javascript">
function closeSuccessMessageModal() {
    document.getElementById('successMessageModal').style.display = 'none';
}
function closeLoginPromptModal() {
    document.getElementById('loginPromptModal').style.display = 'none';
}
 function closequanMessageModal() {
            document.getElementById('quantityMessageModal').style.display = 'none';
        }
</script>