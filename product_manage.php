<?php
include 'db.php';
session_start();
if (!isset($_SESSION['Email'])) {
    header("Location: index.php"); 
    exit();
}
// Fetch existing categories for the dropdown
$categorySql = "SELECT * FROM category";
$categoryResult = mysqli_query($conn, $categorySql);

if (isset($_POST['btnUpload'])) {
    $productName = mysqli_real_escape_string($conn, $_POST['pName']);
    $productDescription = mysqli_real_escape_string($conn, $_POST['pDescription']);
    $productPrice = $_POST['pPrice'];
    $categoryName = $_POST['pCategory']; 
    $quantity = $_POST['pQuantity'];
    $images = $_FILES['pImage'];

    // Check if the product name already exists
    $checkSql = "SELECT * FROM product WHERE name = '$productName'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Product name already exists. Please choose a different name.');</script>";
    } else {
        // Create the target directory based on the selected category
        $targetDir = "img/uploads/" . $categoryName . "/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
        }

        // Loop through each uploaded image
        foreach ($images['tmp_name'] as $key => $tmp_name) {
            $fileName = basename($images['name'][$key]);
            $targetFilePath = $targetDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($tmp_name, $targetFilePath)) {
                // Store the product details in the database
                $sql = "INSERT INTO product (name, img, description, price, category_id, quantity) VALUES ('$productName', '$targetFilePath', '$productDescription', '$productPrice', (SELECT id FROM category WHERE name = '$categoryName'), '$quantity')";
                $result = mysqli_query($conn, $sql);
            }
        }

        if ($result) {
            echo "<script>alert('Product added successfully.'); window.location.href='product_manage.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding product: " . mysqli_error($conn) . "');</script>";
        }
    }
}

if (isset($_POST['btnDelete'])) {
    $deleteId = $_POST['deleteId'];
    // Fetch the image path before deletion
    $sql = "SELECT img FROM product WHERE id = $deleteId";
    $deleteResult = mysqli_query($conn, $sql);
    $deleteRow = mysqli_fetch_assoc($deleteResult);
    $imagePath = $deleteRow['img'];

    $sql = "DELETE FROM product WHERE id = $deleteId";
    if (mysqli_query($conn, $sql)) {
        // Delete the image file from the server
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        echo "<script>alert('Product deleted successfully.'); window.location.href='product_manage.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting product: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="admincss/product.css">
</head>
<body>
<?php
include 'admin_menubar.php' ;
?>
<form method="POST" action="" enctype="multipart/form-data" class="product-form">
<h2 align="center"><u>Add New Product</u></h2>
    <label for="pName">Product Name:</label>
    <input type="text" name="pName" id="pName" required>
    
    <label for="pImage">Choose Product Images:</label>
    <input type="file" name="pImage[]" id="pImage" accept="image/*" multiple required>
    
    <label for="pDescription">Description:</label>
    <textarea name="pDescription" id="pDescription" required></textarea>
    
    <label for="pPrice">Price:</label>
    <input type="number" name="pPrice" id="pPrice" step="0.01" required>
    
    <label for="pCategory">Select Category:</label>
    <select name="pCategory" id="pCategory" required>
        <option value="">Select a category</option>
        <?php while ($categoryRow = mysqli_fetch_assoc($categoryResult)): ?>
            <option value="<?php echo htmlspecialchars($categoryRow['name']); ?>">
                <?php echo htmlspecialchars($categoryRow['name']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <label for="pQuantity">Quantity:</label>
    <input type="number" name="pQuantity" id="pQuantity" required>
    <button type="submit" name="btnUpload">Add Product</button>
</form>

<hr style="border: 3px solid #ccc; width: 100%; margin: 10px 0;">

<form method="GET" action="" class="search-form">
    <input type="text" name="search" placeholder="Search by ID or Name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <input type="submit" value="Search">
    <input type="submit" name="showAll" value="Show All">
</form>
<h2 align="center">Existing Products</h2>
<table class="product-table" align="center">   
     <?php
    $sql = "SELECT p.*, c.name AS category_name FROM product p JOIN category c ON p.category_id = c.id";
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
     
        $sql .= " WHERE p.id = '$searchTerm' OR p.name LIKE '%$searchTerm%' OR p.status LIKE '$searchTerm%'";
    }
    if (isset($_GET['showAll'])) {
        $sql = "SELECT p.*, c.name AS category_name FROM product p JOIN category c ON p.category_id = c.id";
    }
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td rowspan='7' class='product-image'><img src='" . $row['img'] . "' alt='" . $row['name'] . "'></td>";
            echo "<td>
                    <form method='POST' action='productedit.php' style='display:inline;'>
                        <input type='hidden' name='editId' value='" . $row['id'] . "'>
                        <input type='submit' name='btnEdit' class='button edit-button' value='Edit'>
                    </form>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='deleteId' value='" . $row['id'] . "'>
                        <input type='submit' name='btnDelete' class='button delete-button' value='Delete' onclick='return confirm(\"Are you sure you want to delete this product?\");'>
                    </form>
                </td>";
            echo "<tr><td>ID: " . $row['id'] . "</td></tr>";
            echo "<tr><td>Name: " . $row['name'] . "</td></tr>";
            echo "<tr><td>Price: &#8377;" . number_format($row['price'], 2) . "</td></tr>";
            echo "<tr><td>Category: " . htmlspecialchars($row['category_name']) . "</td></tr>"; // Display category name
            echo "<tr><td>Quantity: " . $row['quantity'] . "</td></tr>";
            echo "<tr><td>Status: " . htmlspecialchars($row['status']) . "</td></tr>";
            echo "<tr>";
            echo "<td colspan='8' class='product-description'> <h3>Description : </h3>" . $row['description'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr <td colspan='8'>No products found.</td></tr>";
    } ?>
</table>
<?php
mysqli_close($conn);
?>
</body> </html>