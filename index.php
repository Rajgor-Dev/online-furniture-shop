<?php
session_start();
include 'db.php';
if (isset($_SESSION['Email'])) {
    header("Location: admin_dash.php"); 
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
 <nav class="menu-bar">
  <div class="menu-icons">
    <a href="index.php" title="Home"><img src="img/icon/home.jpg" alt="Home-icon" class="icon" /></a>
    <?php if (isset($_SESSION['UID'])): ?>
        <a href="customerProfile_manage.php" title="Profile"><img src="img/icon/user.jpg" alt="Profile-icon" class="icon" /></a>
    <?php else: ?>
        <a href="#" title="Sign-In" id="signIn"><img src="img/icon/user.jpg" alt="User-icon" class="icon" /></a>
    <?php endif; ?>
    <a href="product_display.php" title="Product"><img src="img/icon/sofa.jpg" alt="category-icon" class="icon" /></a>    
    <a href="contact.php" title="Contact Us"><img src="img/icon/about.jpg" alt="Contact Us-icon" class="icon" /></a>
    <a href="feedback.php" title="Feedback"><img src="img/icon/feedback.jpg" alt="feedback-icon" class="icon" /></a>
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
   <header>
     <div class="logo-container">
         <img src="img/icon/shop.jpg" height="100px" width="150px" alt="Logo" />
         <div class="text-container">
             <h1>HOME DECOR</h1>
             <h2>F U R N I T U R E</h2>
             <?php
             $username = '';
             if (isset($_SESSION['UID'])) {
                 $uid = $_SESSION['UID'];
                 $query = "SELECT name FROM customer WHERE id = $uid";
                 $result = mysqli_query($conn, $query);
                 if ($result) {
                     $row = mysqli_fetch_assoc($result);
                     if ($row) {
                       $username = $row['name'];
                     }
                 } 
             }
             ?>
         </div>  
     </div>
    </header>
  <?php if (!empty($username)): ?>
     <div class="welcome-message">
       Welcome, <?php echo htmlspecialchars($username); ?>
     </div>
  <?php endif; ?>
    <div class="modal" id="authModal" style="<?php echo isset($_SESSION['UID']) ? 'display:none;' : ''; ?>">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <!-- Login Form -->
            <div id="loginForm">
                <h2>Login</h2>
                <form method="POST" action="LP.php" onsubmit="return validateLogin()">
                    <input type="email" name="txtEmail" placeholder="Email" required>
                    <input type="password" name="txtPassword" id="loginPassword" placeholder="Password" required>
                    <button type="submit" name="btnLogin" id="btnLogin" class="login-button">Login</button>
                </form>
                <button class="toggle-button" onclick="toggleForms()">Are you a new user? Register here</button>
            </div>
            <!-- Registration Form -->
           <div id="registerForm" style="display:none;">
    <h2>Register</h2>
    <form method="POST" action="RP.php" onsubmit="return validateRegistration()">
        <input type="text" name="txtUserName" placeholder="Username" required>
        <input type="email" name="txtEmail"  placeholder="Email" required>
        <input type="password" name="txtPassword" id="registerPassword" placeholder="Password" required>
        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
		<span class="toggle-password" onclick="togglePasswordVisibility()">Show</span>
        <div id="passwordRequirements" style="display:none;">
            <p id="lengthRequirement" class="requirement">At least 8 characters</p>
            <p id="uppercaseRequirement" class="requirement">At least one uppercase letter</p>
            <p id="lowercaseRequirement" class="requirement">At least one lowercase letter</p>
            <p id="numberRequirement" class="requirement">At least one number</p>
            <p id="specialCharRequirement" class="requirement">At least one special character</p>
            <p id="matchRequirement" class="requirement">Passwords must match</p>
        </div>
        <input type="text" name="txtMobile" placeholder="Mobile Number" required>
        <button type="submit" name="btnRegister" id="btnRegister" class="register-button">Register</button>
    </form>
    <button class="toggle-button" onclick="toggleForms()">Already have an account? Login here</button>
</div>
        </div>
    </div>

<script type="text/javascript">
let isPasswordVisible = false;
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('registerPassword');
    const passwordInput1 = document.getElementById('confirmPassword');
    const toggleText = document.querySelector('.toggle-password');
    isPasswordVisible = !isPasswordVisible;
    passwordInput.type = isPasswordVisible ? 'text' : 'password';
    passwordInput1.type = isPasswordVisible ? 'text' : 'password';
    toggleText.textContent = isPasswordVisible ? 'Hide' : 'Show';
}
// Add event listeners for focus and input events
document.getElementById('registerPassword').addEventListener('focus', showRequirements);
document.getElementById('registerPassword').addEventListener('input', validatePassword);
document.getElementById('confirmPassword').addEventListener('input', validatePassword);

// Initially hide all requirement messages
document.getElementById('passwordRequirements').style.display = 'none';

function showRequirements() {
    // Show all requirement messages when the password field is focused
    document.getElementById('passwordRequirements').style.display = 'block';
}

function validatePassword() {
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Password validation regex
    const lengthPattern = /^.{8}$/; 
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
    const matchRequirement = document.getElementById('matchRequirement');

    // Validate each requirement
    const isLengthValid = lengthPattern.test(password);
    const isUppercaseValid = uppercasePattern.test(password);
    const isLowercaseValid = lowercasePattern.test(password);
    const isNumberValid = numberPattern.test(password);
    const isSpecialCharValid = specialCharPattern.test(password);
    const isMatchValid = password === confirmPassword;

    // Update requirement messages
    lengthRequirement.style.color = isLengthValid ? 'green' : 'red';
    uppercaseRequirement.style.color = isUppercaseValid ? 'green' : 'red';
    lowercaseRequirement.style.color = isLowercaseValid ? 'green' : 'red';
    numberRequirement.style.color = isNumberValid ? 'green' : 'red';
    specialCharRequirement.style.color = isSpecialCharValid ? 'green' : 'red';
    matchRequirement.style.color = isMatchValid ? 'green' : 'red';

    // Hide messages if all requirements are met
    if (isLengthValid && isUppercaseValid && isLowercaseValid && isNumberValid && isSpecialCharValid && isMatchValid) {
        lengthRequirement.style.display = 'none';
        uppercaseRequirement.style.display = 'none';
        lowercaseRequirement.style.display = 'none';
        numberRequirement.style.display = 'none';
        specialCharRequirement.style.display = 'none';
        matchRequirement.style.display = 'none';
    } else {
        lengthRequirement.style.display = 'block';
        uppercaseRequirement.style.display = 'block';
        lowercaseRequirement.style.display = 'block';
        numberRequirement.style.display = 'block';
        specialCharRequirement.style.display = 'block';
        matchRequirement.style.display = 'block';
    }
}

function validateRegistration() {
	
    // Check if all requirement messages are green
    const lengthRequirement = document.getElementById('lengthRequirement');
    const uppercaseRequirement = document.getElementById('uppercaseRequirement');
    const lowercaseRequirement = document.getElementById('lowercaseRequirement');
    const numberRequirement = document.getElementById('numberRequirement');
    const specialCharRequirement = document.getElementById('specialCharRequirement');
    const matchRequirement = document.getElementById('matchRequirement');

    // If any requirement is not green, alert the user and prevent form submission
    if (
        lengthRequirement.style.color !== 'green' ||
        uppercaseRequirement.style.color !== 'green' ||
        lowercaseRequirement.style.color !== 'green' ||
        numberRequirement.style.color !== 'green' ||
        specialCharRequirement.style.color !== 'green' ||
        matchRequirement.style.color !== 'green'
    ) {
        alert("Please ensure all password requirements are met.");
        return false; 
    }

    return true; // Allow form submission
}
</script>
 
<script type="text/javascript">
    // Show the sign in page when user clicks on sign in button
    document.getElementById('signIn').addEventListener('click', function() {
        document.getElementById('authModal').style.display = 'block'; // Show the modal
    });

    function closeModal() {
        document.getElementById('authModal').style.display = 'none';
    }

    function toggleForms() {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        if (loginForm.style.display === "none") {
            loginForm.style.display = "block";
            registerForm.style.display = "none";
			body.classList.add('no-scroll'); // Disable scrolling
        } else {
            loginForm.style.display = "none";
            registerForm.style.display = "block";
			body.classList.add('no-scroll'); // Disable scrolling
        }
    }

    // Open the modal after 2 seconds
    setTimeout(function() {
        document.getElementById('authModal').style.display = 'block';
    }, 2000); 
</script>
    <section class="featured-products">
        <h2 class ="featured-title">Facilities and Offer soon...</h2>
        <div class="slider-container">
            <button class="slider-button left" onclick="moveSlide(-1)">&#10094;</button>
            <div class="slider">
                <img id="main-image" src="img/ar.jpg" alt="Featured Product" />
            </div>
            <button class="slider-button right" onclick="moveSlide(1)">&#10095;</button>
        </div>
    </section>

    <script type="text/javascript">
        let currentIndex = 0;
        const images = [
            'img/ar.jpg',
            'img/sale.jpg'
        ];

        function moveSlide(direction) {
            currentIndex = (currentIndex + direction + images.length) % images.length; 
            document.getElementById('main-image').src = images[currentIndex];
        }

        function autoSlide() {
            currentIndex = (currentIndex + 1) % images.length; // Move to the next image
            document.getElementById('main-image').src = images[currentIndex];
        }

        // Set interval for automatic sliding every 5 seconds
        setInterval(autoSlide, 5000);
    </script>

    <?php
    $query = "SELECT * FROM product WHERE status = 'best'";
    $result = mysqli_query($conn, $query);

    $bestSellingProducts = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bestSellingProducts[] = $row;
        }
    }
    ?>
    <section class="best-selling">
        <h2 class="best-selling-title">Best Selling Products</h2>
        <div class="best-selling-products">
            <?php foreach ($bestSellingProducts as $product): ?>
                <div class="product">
                       <div class="product-info">
                        <a href="product_details.php?id=<?php echo urlencode($product['id']); ?>">
                            <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" /> <!-- Display product image -->
                            <p><strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
                            <p>&#8377;<?php echo htmlspecialchars($product['price']); ?></p>
                        </a>
                    </div>
                      <?php if (isset($product['quantity'])): // Check if quantity is set ?>
                        <?php if ($product['quantity'] > 0): ?>
                            <form method="POST" action="" style="display: flex; justify-content: center; gap: 10px;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="cart" class="add-to-cart">Add to Cart</button>
                               <button type="button" class="buy-product" id="buy" onclick="window.location.href='shipping.php?id=<?php echo $product['id']; ?>'">Buy Product</button>
                            </form>
                        <?php else: ?>
                            <h4 style="color: red;">Out of Stock</h4>
                        <?php endif; ?>
                    <?php else: ?>
                        <p style="color: red;">Quantity information not available.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
		include 'addcartlogic.php';
	?>
    <?php
		// Fetch categories 
		$sql = "SELECT name, img FROM category";
		$result = mysqli_query($conn, $sql); 
		$categories = [];
		if ($result) {
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					$categories[] = $row;
				}
			}
		} else {
			echo "Error executing query: " . mysqli_error($conn);
		}
	?>
    <div class="furniture-category">
        <h2 class="furniture-title">Furniture Categories</h2>
        <div class="furniture-images">
            <?php foreach ($categories as $category): ?>
                <div class="furniture-item" onclick="location.href='product_display.php?category_name=<?php echo urlencode($category['name']); ?>'">
                    <img src="<?php echo $category['img']; ?>" alt="<?php echo $category['name']; ?>">
                    <div class="furniture-item-name"><?php echo $category['name']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
   <?php
		include 'footer.php';
   ?>
</body>
</html>