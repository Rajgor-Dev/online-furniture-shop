<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['Email'])) {
    header("Location: index.php"); 
    exit();
}

// Handle status toggle before any output
if (isset($_POST['btnToggle'])) {
    $toggleId = $_POST['toggleId'];

    // Fetch current status
    $statusQuery = "SELECT status FROM customer WHERE id = $toggleId";
    $statusResult = mysqli_query($conn, $statusQuery);
    $statusRow = mysqli_fetch_assoc($statusResult);
    
    // Determine new status
    $newStatus = $statusRow['status'] == 'active' ? 'deactive' : 'active'; // Toggle status

    // Update status in the database
    $sql = "UPDATE customer SET status = '$newStatus' WHERE id = $toggleId";
    if (mysqli_query($conn, $sql)) {
        header("Location: customer_manage.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Home Decor - Furniture</title>
 <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
 <link rel="stylesheet" href="admincss/customer.css">
</head>
<body>
<?php
include 'admin_menubar.php';
?>
<form method="GET" action="" class="search-form">
    <input type="text" name="search" placeholder="Search by ID or Name">
    <input type="submit" value="Search">
    <input type="submit" name="showAll" value="Show All">
</form>
<h2 align="center">Existing Customers</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact No</th>
        <th>Status</th>
    </tr>  
    <?php  
    $sql = "SELECT * FROM customer";
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $sql .= " WHERE id LIKE '%$search%' OR name LIKE '$search%' OR status LIKE '$search%'";
    } elseif (isset($_GET['showAll'])) {
        
    }
    $result = mysqli_query($conn, $sql);
    // Check if any records were found
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['contact_no']) . "</td>";
            // Display status as a clickable link
            $status = $row['status'] == 'active' ? 'Activate' : 'Deactivate';
            echo "<td>
                    <form method='POST' action=''>
                        <input type='hidden' name='toggleId' value='" . $row['id'] . "'>
                        <input type='submit' name='btnToggle' value='" . $status . "'>
                    </form>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'><h2>No records found.</h2></td></tr>";
    }
    ?>
</table>

<?php
 mysqli_close($conn);
?>
</body>
</html>