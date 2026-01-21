<?php
session_start();
include 'db.php';
if (!isset($_SESSION['Email'])) {
    header("Location: index.php"); 
    exit();
}

$sql = "SELECT f.id, f.rating, f.comment, f.add_on, c.name AS user_name FROM feedback f JOIN customer c ON f.customer_id = c.id 
        ORDER BY f.id DESC";
$result = mysqli_query($conn, $sql);

function displayStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '<span class="star filled">&#9733;</span>'; // Filled star
        } else {
            $stars .= '<span class="star">&#9734;</span>'; // Empty star
        }
    }
    return $stars;
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_sql = "DELETE FROM feedback WHERE id = '$delete_id'";
    
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Feedback deleted successfully!'); window.location.href='feedback_manage.php';</script>";
    } else {
        echo "<script>alert('Error deleting feedback: " . mysqli_error($conn) . "');</script>";
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
    <link rel="stylesheet" href="admincss/feedback.css">
</head>
<body>
<?php include 'admin_menubar.php'; ?>
<h1>All Feedback Record</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User Name</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Action</th> <!-- New column for actions -->
        </tr>
    </thead>  
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo displayStars($row['rating']); ?></td> <!-- Display stars here -->
                    <td><?php echo htmlspecialchars($row['comment']); ?></td>
                    <td><?php echo $row['add_on']; ?></td>
                    <td>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No feedback found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>