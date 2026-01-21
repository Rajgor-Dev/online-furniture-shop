<?php
session_start();
include 'db.php';

if (!isset($_SESSION['UID'])) {
    header("Location: index.php"); 
    exit();
}

$user_id = $_SESSION['UID'];
$query = "SELECT * FROM customer WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$userData = mysqli_fetch_assoc($result);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    // Update profile information
  if (isset($_POST['update'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
   
      // Check if the email already exists for another user in the customer table
    $emailCheckQuery = "
        SELECT id FROM customer WHERE email = '$email' AND id != $user_id
        UNION
        SELECT email FROM admin WHERE email = '$email'
    "; // Exclude current user from customer table


    $emailCheckResult = mysqli_query($conn, $emailCheckQuery);

    if (mysqli_num_rows($emailCheckResult) > 0) {
        // Email already exists
        echo "<script>alert('This email is already in use by another account. Please use a different email.');</script>";
    } else {
        // Update user data in the database
        $updateQuery = "UPDATE customer SET name = '$username', email = '$email', contact_no = '$contact_no' WHERE id = $user_id";
        
        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>alert('Profile updated successfully.');</script>";
            // Refresh user data after update
            $query = "SELECT name, email, contact_no FROM customer WHERE id = $user_id";
            $result = mysqli_query($conn, $query);
            $userData = mysqli_fetch_assoc($result);
        } else {
            echo "<script>alert('Error updating profile: " . mysqli_error($conn) . "');</script>";
        }
    }
}

    // Change password
    if (isset($_POST['cpwd'])) {
        $oldPassword = mysqli_real_escape_string($conn, $_POST['upwd']);
        $newPassword = mysqli_real_escape_string($conn, $_POST['npwd']);

        // Verify the old password
        if (password_verify($oldPassword, $userData['password'])) {
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updatePasswordQuery = "UPDATE customer SET password = '$hashedNewPassword' WHERE id = $user_id";
            if (mysqli_query($conn, $updatePasswordQuery)) {
                echo "<script>alert('Password changed successfully.');</script>";
            } else {
                echo "<script>alert('Error changing password: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Old password is incorrect.');</script>";
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
	 <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="usercss/customerprofile.css">
</head>
<body>
    <nav class="menu-bar">
    <div class="menu-icons">
        <a href="index.php" title="Home"><img src="img/icon/home.jpg" alt="Home-icon" class="icon" /></a>
        <a href="product_display.php" title="Product"><img src="img/icon/sofa.jpg" alt="category-icon" class="icon" /></a> 
		<a href="contact.php" title="Contact Us"><img src="img/icon/about.jpg" alt="Contact Us-icon" class="icon" /></a>
        <a href="feedback.php" title="Feedback"><img src="img/icon/feedback.jpg" alt="feedback-icon" class="icon" /></a>
    </div>
     <form method="GET" action="product_display.php">
      <div class="search-container">
        <input type="text" name="search_query" placeholder="Search..." required />
        <button type="submit"><img src="img/icon/search.jpg" alt="Search" class="icon" /></button>
    </form>
	</div>
	
<form method="POST" action="logout.php">
    <button type="submit" name="logout" class="logout-button">Logout</button>
</form>	
<div class="icon-container">
	<a href="cart.php" title="View Cart"><img src="img/icon/add-to-cart.jpg" alt="Add to Cart" class="icon"  /></a>
    <a href="order_history.php" title="Order History"><img src="img/icon/order-history.jpg" alt="Order History" class="icon" /></a>
</div>
</nav>

<form method="POST" action="" >
<div class="profile-form">
  <h1>Manage Your Profile</h1>
    <br/>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userData['name']); ?>" required />
    <br/>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required />
    <br/>
    <label for="contact_no">Contact Number:</label>
    <input type="text" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($userData['contact_no']); ?>" required />
    <br/>
    <button type="submit" name="update">Update</button><br/>
	</div>
	</form>
	<form method="POST" action=""  onsubmit="return validateRegistration()">
	<div class="profile-form">
	 <h1>Change Your Password</h1>
    <br/>
    <label for="opass">Old Password:</label>
    <input type="password" id="upwd" name="upwd" placeholder="Enter old password" required />
    <br/>
    <label for="npass">New Password:</label>
    <input type="password" id="npwd" name="npwd" placeholder="Enter new password" required />
	<span class="toggle-password" onclick="togglePasswordVisibility()">Show</span>
	 <div id="passwordRequirements">
            <p id="lengthRequirement">Password must be 8 characters long.</p>
            <p id="uppercaseRequirement">Password must contain at least one uppercase letter.</p>
            <p id="lowercaseRequirement">Password must contain at least one lowercase letter.</p>
            <p id="numberRequirement">Password must contain at least one digit.</p>
            <p id="specialCharRequirement">Password must contain at least one special character.</p>
        </div>
    <br/>
    <button type="submit" name="cpwd">Change Password</button><br/>
	</div>
	</form>
<script>
let isPasswordVisible = false;

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('npwd');
    const passwordInput1 = document.getElementById('upwd');
    const toggleText = document.querySelector('.toggle-password');
    isPasswordVisible = !isPasswordVisible;
    passwordInput.type = isPasswordVisible ? 'text' : 'password';
    passwordInput1.type = isPasswordVisible ? 'text' : 'password';
    toggleText.textContent = isPasswordVisible ? 'Hide' : 'Show';
}

// Add event listeners for focus and input events
document.getElementById('npwd').addEventListener('focus', showRequirements);
document.getElementById('npwd').addEventListener('input', validatePassword);

// Initially hide all requirement messages
document.getElementById('passwordRequirements').style.display = 'none';

function showRequirements() {
    // Show all requirement messages when the password field is focused
    document.getElementById('passwordRequirements').style.display = 'block';
}

function validatePassword() {
    const password = document.getElementById('npwd').value;

    // Password validation regex
    const lengthPattern = /^.{8}$/; // At least 10 characters
    const uppercasePattern = /[A-Z]/; // At least one uppercase letter
    const lowercasePattern = /[a-z]/; // At least one lowercase letter
    const numberPattern = /\d/; // At least one number
    const specialCharPattern = /[@$!%*?&]/; // At least one special character

    // Get requirement elements
    const lengthRequirement = document.getElementById('lengthRequirement');
    const uppercaseRequirement = document.getElementById('uppercaseRequirement');
    const lowercaseRequirement = document.getElementById('lowercaseRequirement');
    const numberRequirement = document.getElementById('numberRequirement');
    const specialCharRequirement = document.getElementById('specialCharRequirement');

    // Validate each requirement
    const isLengthValid = lengthPattern.test(password);
    const isUppercaseValid = uppercasePattern.test(password);
    const isLowercaseValid = lowercasePattern.test(password);
    const isNumberValid = numberPattern.test(password);
    const isSpecialCharValid = specialCharPattern.test(password);

    // Update requirement messages
    lengthRequirement.style.color = isLengthValid ? 'green' : 'red';
    uppercaseRequirement.style.color = isUppercaseValid ? 'green' : 'red';
    lowercaseRequirement.style.color = isLowercaseValid ? 'green' : 'red';
    numberRequirement.style.color = isNumberValid ? 'green' : 'red';
    specialCharRequirement.style.color = isSpecialCharValid ? 'green' : 'red';

   
  // Hide messages if all requirements are met
    if (isLengthValid && isUppercaseValid && isLowercaseValid && isNumberValid && isSpecialCharValid) {
        lengthRequirement.style.display = 'none';
        uppercaseRequirement.style.display = 'none';
        lowercaseRequirement.style.display = 'none';
        numberRequirement.style.display = 'none';
        specialCharRequirement.style.display = 'none';
       ;
    } else {
        lengthRequirement.style.display = 'block';
        uppercaseRequirement.style.display = 'block';
        lowercaseRequirement.style.display = 'block';
        numberRequirement.style.display = 'block';
        specialCharRequirement.style.display = 'block';
       
    }
    // Return true if all requirements are met, otherwise return false
    return isLengthValid && isUppercaseValid && isLowercaseValid && isNumberValid && isSpecialCharValid;
}

function validateRegistration() {
    // Call validatePassword to check if all requirements are met
    if (!validatePassword()) {
        alert("Please ensure all password requirements are met.");
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}
</script>

</body> </html>