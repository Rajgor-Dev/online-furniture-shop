<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Home Decor - Furniture</title>
    <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="usercss/feedback.css">
	<style>
    a {
       color: blue; 
    }

    a:hover {
       color: darkblue; 
       text-decoration: underline; 
    }  
	tr{
		
     background-color: #ffffff;
    }
	th{
		font-size: 1.2em; /* Slightly larger font size for header */
	}
 
	</style>
</head>
<body>
<nav class="menu-bar">
    <div class="menu-icons">
        <a href="index.php" title="Home"><img src="img/icon/home.jpg" alt="Home-icon" class="icon" /></a>
        <?php if (isset($_SESSION['UID'])): ?>
            <a href="customerProfile_manage.php" title="Profile"><img src="img/icon/user.jpg" alt="Profile-icon" class="icon" /></a>
        <?php endif; ?>
		<a href="product_display.php" title="Product"><img src="img/icon/sofa.jpg" alt="category-icon" class="icon" /></a> 
        <a href="contact.php" title="Contact Us"><img src="img/icon/about.jpg" alt="Contact Us-icon" class="icon" /></a>
    </div>
    <form method="GET" action="product_display.php">
        <div class="search-container">
            <input type="text" name="search_query" placeholder="Search..." required />
            <button type="submit"><img src="img/icon/search.jpg" alt="Search" class="icon" /></button>
        </div>
    </form>
    <div class="add-to-cart-icon">
        <a href="cart.php" title="View Cart"><img src="img/icon/add-to-cart.jpg" alt="Add to Cart" class="icon" /></a>
  <?php if (isset($_SESSION['UID'])): ?>
	 <a href="order_history.php" title="Order History"><img src="img/icon/order-history.jpg" alt="Order History" class="icon" /></a>
	 <?php endif; ?>
    </div>
</nav>

<div class="feedback-form-container">
    <?php if (isset($_SESSION['UID'])):  ?>
        <form method="POST" action="feedback.php">
            <h1 align="center"><u>Feedback Form</u></h1>
            <label for="rating">Rating:</label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required />
                <label for="star5" class="star">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4" />
                <label for="star4" class="star">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3" />
                <label for="star3" class="star">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2" />
                <label for="star2" class="star">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="1" />
                <label for="star1" class="star">&#9733;</label>
            </div>

            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" rows="4" required></textarea>

            <button type="submit" name="submit">Submit Feedback</button>
        </form>
    <?php else: ?>
        <h2>Please log in to submit your feedback.</h2>
        <a href="index.php">Login here</a>
    <?php endif; ?>
</div>

<?php
if (isset($_POST['submit'])) {
    // Get user ID from session (assuming user is logged in)
    $customer_id = isset($_SESSION['UID']) ? $_SESSION['UID'] : null;

    // Sanitize user inputs
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $sql = "INSERT INTO feedback (customer_id, rating, comment, add_on) VALUES ('$customer_id', '$rating', '$comment', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='feedback.php';</script>";
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }
    mysqli_close($conn);
}

// Function to display star rating
function displayStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '<span class="star2 filled">&#9733;</span>'; // Filled star
        } else {
            $stars .= '<span class="star2">&#9734;</span>'; // Empty star
        }
    }
    return $stars;
}

// Display user's feedback
if (isset($_SESSION['UID'])) {
    $customer_id = $_SESSION['UID'];
    $user_feedback_sql = "SELECT rating, comment, add_on FROM feedback WHERE customer_id = '$customer_id'  ORDER BY id DESC";
    $user_feedback_result = mysqli_query($conn, $user_feedback_sql);

    if (mysqli_num_rows($user_feedback_result) > 0) {
        echo "<h2>Your Feedback</h2>";
        echo "<table><tr><th>Rating</th><th>Comment</th><th>Date</th></tr>";
        while ($row = mysqli_fetch_assoc($user_feedback_result)) {
            echo "<tr><td>" . displayStars($row['rating']) . "</td><td>" . htmlspecialchars($row['comment']) . "</td><td>" . htmlspecialchars($row['add_on']) . "</td></tr>";
        }
        echo "</table>";
    }


// Display all feedback
$all_feedback_sql = "SELECT f.rating, f.comment, f.add_on, c.name FROM feedback f JOIN customer c ON f.customer_id = c.id WHERE f.customer_id <> '$customer_id'  ORDER BY f.id DESC";
$all_feedback_result = mysqli_query($conn, $all_feedback_sql);

echo "<h2>Other Feedback</h2>";
if (mysqli_num_rows($all_feedback_result) > 0) {
    echo "<table><tr><th>Customer</th><th>Rating</th><th>Comment</th><th>Date</th></tr>";
    while ($row = mysqli_fetch_assoc($all_feedback_result)) {
        echo "<tr><td>" . htmlspecialchars($row['name']) . "</td><td>" . displayStars($row['rating']) . "</td><td>" . htmlspecialchars($row['comment']) . "</td><td>" . htmlspecialchars($row['add_on']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No feedback available.</p>";
}
}
?>
</body>
</html>