<?php
session_start();
include 'db.php';

// Check if editId is set
if (isset($_POST['editId'])) {
    $editId = $_POST['editId'];
    $sql = "SELECT * FROM product WHERE id = $editId";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
}

if (isset($_POST['btnUpdate'])) {
    $updateId = $_POST['updateId'];
    $updatedName = mysqli_real_escape_string($conn, $_POST['upName']);
    $updatedDescription = mysqli_real_escape_string($conn, $_POST['upDescription']);
    $updatedPrice = $_POST['upPrice'];
    $updatedCategoryName = $_POST['upCategory'];
    $updatedQuantity = $_POST['upQuantity'];
    $updatedStatus = $_POST['upStatus']; // Capture the updated status
    $updatedImages = $_FILES['upImage'];

    // Check if the updated product name already exists 
    $checkSql = "SELECT * FROM product WHERE name = '$updatedName' AND id != $updateId";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        // Product name already exists
        echo "<script>alert('Product name already exists. Please choose a different name.'); window.location.href='product_manage.php';</script>";
    } else {
        // Prepare the SQL query to update the product details
        $sql = "UPDATE product SET 
                    name = '$updatedName', 
                    description = '$updatedDescription', 
                    price = '$updatedPrice', 
                    category_id = (SELECT id FROM category WHERE name = '$updatedCategoryName'), 
                    quantity = '$updatedQuantity', 
                    status = '$updatedStatus' 
                WHERE id = $updateId";

        // Execute the update query for product details
        if (mysqli_query($conn, $sql)) {
            // Check if new images were uploaded
            if (!empty($updatedImages['tmp_name'][0])) {
                // Create the target directory based on the selected category
                $targetDir = "img/uploads/" . $updatedCategoryName . "/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
                }

                // Loop through each uploaded image
                foreach ($updatedImages['tmp_name'] as $key => $tmp_name) {
                    $fileName = basename($updatedImages['name'][$key]);
                    $targetFilePath = $targetDir . $fileName;

                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($tmp_name, $targetFilePath)) {
                        // Update the image path in the database
                        $imageUpdateSql = "UPDATE product SET img = '$targetFilePath' WHERE id = $updateId";
                        mysqli_query($conn, $imageUpdateSql);
                    } else {
                        echo "<script>alert('Error uploading image: " . $updatedImages['name'][$key] . "'); window.location.href='product_manage.php';</script>";
                    }
                }
            }
         echo "<script>alert('Product updated successfully.'); window.location.href='product_manage.php';</script>";
         exit();
        } else {
           echo "<script>alert('Error updating product: " . mysqli_error($conn) . "'); window.location.href='product_manage.php';</script>";
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
    <link rel="stylesheet" href="admincss/product.css">
</head>
<body>
<?php
include 'admin_menubar.php' ;
?>
<div class="modal" id="updateModal">
    <div class="modal-content">
        <form method="POST" action="#" enctype="multipart/form-data" class="product-form">
		 <h2 align="center"><u>Update Product</u></h2>
            <input type="hidden" name="updateId" id="updateId" value="<?php echo $product['id']; ?>">
            
            <label for="upName">Update Product Name:</label>
            <input type="text" name="upName" id="upName" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            
			<label for="ucategoryImage">Choose New Product Image (optional):</label>
            <input type="file" name="upImage[]" id="upImage[]" accept="image/*" multiple>
			
            <label for="upDescription">Update Description:</label>
            <textarea name="upDescription" id="upDescription" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            
            <label for="upPrice">Update Price:</label>
            <input type="number" name="upPrice" id="upPrice" value="<?php echo $product['price']; ?>" step="0.01" required>
            
            <label for="upCategory">Select Category:</label>
            <select name="upCategory" id="upCategory" required>
                <option value="">Select a category</option>
                <?php
                // Fetch categories for the dropdown
                $categorySql = "SELECT * FROM category";
                $categoryResult = mysqli_query($conn, $categorySql);
                while ($categoryRow = mysqli_fetch_assoc($categoryResult)): ?>
                    <option value="<?php echo htmlspecialchars($categoryRow['name']); ?>" <?php echo ($categoryRow['name'] == $product['category_id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($categoryRow['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label for="upQuantity">Update Quantity:</label>
            <input type="number" name="upQuantity" id="upQuantity" value="<?php echo $product['quantity']; ?>" required>
           
            <label for="upStatus">Status:</label>
            <select name="upStatus" id="upStatus" required>
                <option value="best" <?php echo ($product['status'] == 'best') ? 'selected' : ''; ?>>Best</option>
                <option value="normal" <?php echo ($product['status'] == 'normal') ? 'selected' : ''; ?>>Normal</option>
            </select>
          <button type="submit" name="btnUpdate">Update Product</button>
        </form>
    </div>
</div>
</body> </html>