<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
            padding: 20px 0; 
            width: 100%; 
            position: fixed; 
            top: 0; /* Align to the top of the viewport */
            left: 0; 
            z-index: 1000; /* Ensure it stays above other content */
            overflow: hidden;
        }

        .menu {
            list-style-type: none; /* Remove bullet points */
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
            display: flex; /* Use flexbox for horizontal layout */
            justify-content: center;
        }

        .menu li {
            margin: 0 20px; /* Space between menu items */
        }

        .menu a {
            color: #333; /* Dark gray text color */
            text-decoration: none; /* Remove underline */
            padding: 10px 15px; 
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s, color 0.3s, transform 0.3s; /* Smooth transition for hover effect */
            font-weight: 600; /* Bold font weight */
        }

        .menu a:hover,
        .menu a.active {
            background-color: #007bff; /* Blue background for hover and active */
            color: #ffffff; /* White text on hover and active */
            transform: translateY(-2px); /* Slight lift effect on hover */
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2); /* Shadow effect on hover */
        }
    </style>
</head>
<body>
<nav class="navbar">
    <ul class="menu">
        <li><a href="admin_dash.php">Home</a></li>
        <li><a href="customer_manage.php">Customer</a></li>
        <li><a href="category_manage.php">Category</a></li>
        <li><a href="product_manage.php">Product</a></li>
        <li><a href="order_manage.php">Order</a></li>
        <li><a href="feedback_manage.php">Feedback</a></li>
    </ul>
</nav>

<script>
    // Get the current URL path
    const currentPath = window.location.pathname.split('/').pop();
    // Get all menu links
    const menuLinks = document.querySelectorAll('.menu a');

    // Loop through the links and add the 'active' class to the current page
    menuLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
</script>
</body></html>