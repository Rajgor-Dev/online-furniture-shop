<?php
include 'db.php';
session_start();
if (!isset($_SESSION['Email'])) {
    header("Location: index.php");
    exit();
}

$admin_email = $_SESSION['Email'];
$query = "SELECT * FROM admin WHERE email = '$admin_email'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['updateEmail'])) {
        $newEmail = mysqli_real_escape_string($conn, $_POST['txtEmail']);
        
        $updateEmailQuery = "UPDATE admin SET email = '$newEmail' WHERE email = '$admin_email'";
        if (mysqli_query($conn, $updateEmailQuery)) {
            $_SESSION['Email'] = $newEmail; 
            echo "<script>alert('Email updated successfully.'); window.location.href='adminProfile_manage.php';</script>";
            exit();
        } else {
            $errorMessages[] = "Error updating email: " . mysqli_error($conn);
        }
    }

    if (isset($_POST['changePassword'])) {
		$oldPassword = $_POST['upwd'];
        $newPassword = $_POST['txtPassword'];
		  // Verify the old password
        if (password_verify($oldPassword, $admin['password'])) {
         $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

         $updatePasswordQuery = "UPDATE admin SET password = '$newPasswordHashed' WHERE email = '$admin_email'";
         if (mysqli_query($conn, $updatePasswordQuery)) {
            echo "<script>alert('Password updated successfully.'); window.location.href='adminProfile_manage.php';</script>";
            exit();
         } else {
            $errorMessages[] = "Error updating password: " . mysqli_error($conn);
         }
	    } else {
        echo "<script>alert('Old password is incorrect.');</script>";
        }
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Account</title>
    <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
	<link rel="stylesheet" href="admincss/adminProfile.css">
</head>
<body>
 <?php
  include 'admin_menubar.php';
  ?>
    <div class="container">
        <h2>Manage Your Account</h2>
        <div class="section">
            <h3>Change Email</h3>
            <form method="POST" action="">
                <label for="txtEmail">Email:</label>
                <input type="email" name="txtEmail" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                <button type="submit" name="updateEmail">Update Email</button>
            </form>
        </div></div>
		<div class="container">
        <div class="section">
    <h3>Change Password</h3>
    <form method="POST" action="" onsubmit="return validateRegistration()">
	     <label for="opass">Old Password:</label>
         <input type="password" id="upwd" name="upwd" placeholder="Enter old password" required />
        <br/>
        <label for="txtPassword">New Password:</label>
        <input type="password" name="txtPassword" id="txtPassword" placeholder="Enter new password" required>
        <span class="toggle-password" onclick="togglePasswordVisibility()">Show</span>
        <div id="passwordRequirements">
            <p id="lengthRequirement">Password must be 10 characters long.</p>
            <p id="uppercaseRequirement">Password must contain at least one uppercase letter.</p>
            <p id="lowercaseRequirement">Password must contain at least one lowercase letter.</p>
            <p id="numberRequirement">Password must contain at least one digit.</p>
            <p id="specialCharRequirement">Password must contain at least one special character.</p>
        </div>
        <div class="alert" id="passwordError" style="color: red;"></div>
        <button type="submit" name="changePassword">Change Password</button>
    </form>
</div>
  </div>

<script>
let isPasswordVisible = false;

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('txtPassword');
    const toggleText = document.querySelector('.toggle-password');
    isPasswordVisible = !isPasswordVisible;
    passwordInput.type = isPasswordVisible ? 'text' : 'password';
    toggleText.textContent = isPasswordVisible ? 'Hide' : 'Show';
}

// Add event listeners for focus and input events
document.getElementById('txtPassword').addEventListener('focus', showRequirements);
document.getElementById('txtPassword').addEventListener('input', validatePassword);

// Initially hide all requirement messages
document.getElementById('passwordRequirements').style.display = 'none';

function showRequirements() {
    // Show all requirement messages when the password field is focused
    document.getElementById('passwordRequirements').style.display = 'block';
}

function validatePassword() {
    const password = document.getElementById('txtPassword').value;

    const lengthPattern = /^.{10}$/; 
    const uppercasePattern = /[A-Z]/;
    const lowercasePattern = /[a-z]/;
    const numberPattern = /\d/; 
    const specialCharPattern = /[@$!%*?&]/; 

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
</body>
</html>