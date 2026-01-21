<?php
session_start();
if (!isset($_SESSION['Email'])) {
    header("Location: index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Decor - Furniture</title>
    <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
    <link rel="stylesheet" href="admincss/category.css">
</head>
<body>
<?php
include 'admin_menubar.php' ;
?>

<form method="POST" action="" enctype="multipart/form-data" class="category-form">
    <h2><u>ADD New Categories</u></h2>
    <label for="cName">Enter Category Name:</label>
    <input type="text" name="cName" id="cName" placeholder="Category Name" required>
    <br>
    <label for="categoryImage">Choose Category Image:</label>
    <input type="file" name="categoryImage" id="categoryImage" accept="image/*" required>
    <br>
    <button type="submit" name="btnUpload">Add Category</button>
</form>
 
<hr style="border: 3px solid #ccc; width: 100%; margin: 10px 0;">

<form method="GET" action="" class="search-form">
    <input type="text" name="search" placeholder="Search by ID or Name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <input type="submit" value="Search">
    <input type="submit" name="showAll" value="Show All">
</form>
<h2>Existing Categories</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
     <?php
      include 'db.php' ;
    $sql = "SELECT * FROM category";

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
        $sql .= " WHERE id = '$searchTerm' OR name LIKE '$searchTerm%'"; 
    }

    if (isset($_GET['showAll'])) {
        $searchTerm = '';
        $sql = "SELECT * FROM category";
    }
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            
            // Ensure the image path is correct
            $imagePath = htmlspecialchars($row['img']);
            echo "<td><img src='" . $imagePath . "' alt='" . htmlspecialchars($row['name']) . "' width='100'></td>";
            
            echo "<td>
            <form method='POST' style='display:inline;'>
                <input type='hidden' name='editId' value='" . $row['id'] . "'>
                <input type='submit' name='btnEdit' class='button edit-button' value='Edit' onclick='openModal(\"" . htmlspecialchars($row['name']) . "\", \"" . htmlspecialchars($row['img']) . "\", " . $row['id'] . "); return false;'>
            </form>
            <form method='POST' style='display:inline;'>
                <input type='hidden' name='deleteId' value='" . $row['id'] . "'>
                <input type='submit' name='btnDelete' class='button delete-button' value='Delete' onclick='return confirm(\"Are you sure you want to delete this category?\");'>
            </form>
          </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4' style='text-align:center;'>No categories found.</td></tr>";
    }
    ?>
</table>
<?php
// Function to sanitize category name
function sanitizeCategoryName($name) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9_\-]/', '_', $name))); // Sanitize and convert to lowercase
}

// Handle form submission for adding a new category
if (isset($_POST['btnUpload'])) {
    $categoryName = $_POST['cName'];
    $sanitizedCategoryName = sanitizeCategoryName($categoryName);
    $image = $_FILES['categoryImage'];

    // Check if the category already exists
    $checkSql = "SELECT * FROM category WHERE LOWER(name) = '$sanitizedCategoryName'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Category already exists. Please choose a different name.');</script>";
    } else {
        // Check if the image was uploaded without errors
        if ($image['error'] == 0) {
            // Create a valid filename
            $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $newFileName = $sanitizedCategoryName . '.' . $fileExtension;
            $targetDir = 'img/uploads/category_img/';

            // Make sure this directory exists and is writable
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $targetFilePath = $targetDir . $newFileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
                // Store the full path in the database
                $sql = "INSERT INTO category (name, img) VALUES ('$categoryName', '$targetFilePath')";

                // Execute the query
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Category added successfully.'); window.location.href='category_manage.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                echo "<script>alert('Error moving uploaded file. Please check the directory permissions.');</script>";
            }
        } else {
            echo "<script>alert('Error uploading file: " . $image['error'] . "');</script>";
        }
    }
}

if (isset($_POST['btnUpdate'])) {
    $updateId = $_POST['updateId'];
    $updatedName = $_POST['ucName'];
    $sanitizedUpdatedName = sanitizeCategoryName($updatedName);
    $updatedImage = $_FILES['ucategoryImage'];

    // Check if the category already exists
    $checkSql = "SELECT * FROM category WHERE LOWER(name) = '$sanitizedUpdatedName' AND id != $updateId";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Category already exists. Please choose a different name.');</script>";
    } else {
        // Check if a new image was uploaded
        if ($updatedImage['error'] == 0) {
            // Create a valid filename
            $fileExtension = pathinfo($updatedImage['name'], PATHINFO_EXTENSION);
            $newFileName = $sanitizedUpdatedName . '.' . $fileExtension;
            $targetDir = 'img/uploads/category_img/';
            $targetFilePath = $targetDir . $newFileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($updatedImage['tmp_name'], $targetFilePath)) {
                // Update the category in the database with the new image
                $sql = "UPDATE category SET name = '$updatedName', img = '$targetFilePath' WHERE id = $updateId";
            } else {
                echo "<script>alert('Error moving uploaded file.');</script>";
            }
        } else {
            // If no new image is uploaded, just update the name
            $sql = "UPDATE category SET name = '$updatedName' WHERE id = $updateId";
        }

        // Execute the update query
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Category updated successfully.'); window.location.href='category_manage.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}

if (isset($_POST['btnDelete'])) {
    $deleteId = $_POST['deleteId'];
    // Fetch the image path before deletion
    $sql = "SELECT img FROM category WHERE id = $deleteId";
    $deleteResult = mysqli_query($conn, $sql);
    $deleteRow = mysqli_fetch_assoc($deleteResult);
    $imagePath = $deleteRow['img'];

    $sql = "DELETE FROM category WHERE id = $deleteId";
    if (mysqli_query($conn, $sql)) {
        // Delete the image file from the server
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
          echo "<script>alert('Category deleted successfully.'); window.location.href='category_manage.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting Category: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="modal" id="updateModal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
      
        <form method="POST" action="" enctype="multipart/form-data" class="category-form">
		  <h2>Update Category</h2>
            <input type="hidden" name="updateId" id="updateId" value="">
            <label for="ucName">Update Category Name:</label>
            <input type="text" name="ucName" id="ucName" required>
            <br>
            <label for="ucategoryImage">Choose New Category Image (optional):</label>
            <input type="file" name="ucategoryImage" id="ucategoryImage" accept="image/*">
            <br>
            <button type="submit" name="btnUpdate">Update Category</button>
        </form>
    </div>
</div>
<script>
  function openModal(categoryName, imagePath, categoryId) {
   document.getElementById('ucName').value = categoryName; 
   document.getElementById('updateId').value = categoryId; 
   document.getElementById('updateModal').style.display = 'block';
}
  function closeModal() {
   document.getElementById('updateModal').style.display = 'none';
}
  window.onclick = function(event) {
   if (event.target == document.getElementById('updateModal')) {
    closeModal();
   }
  }
</script>
<?php
mysqli_close($conn);
?>
</body>
</html>