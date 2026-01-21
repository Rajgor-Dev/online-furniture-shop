<?php
session_start();
include 'db.php';
if (!isset($_SESSION['UID'])) {
    header("Location: index.php"); 
    exit;
}
$order_id = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
    <style>
        body, html {
            height: 100%;
            width: 100%;
            margin: 0;
            overflow: hidden;
            color: white; 
            font-family: Arial, sans-serif; 
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Stack text vertically */
            text-align: center; 
            position: relative;
        }
        .bg-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1; /* Send video to the back */
        }
        .message {
            font-size: 3em; 
            z-index: 1; /* Ensure text is above the video */
        }
        .sub-message {
            font-size: 1.5em; 
            z-index: 1; /* Ensure text is above the video */
        }
    </style>
    <script>
        // Redirect to bill.php after 3 seconds
        setTimeout(function() {
            window.location.href = "bill.php?id=<?php echo $order_id; ?>";
        }, 3000);
    </script>
</head>
<body>
    <video autoplay muted loop class="bg-video">
        <source src="img/icon/congo.mp4" type="video/mp4"> 
        Your browser does not support the video tag.
    </video>
    <div class="message">Order Successfully Placed!</div>
    <div class="sub-message">Thank you for your purchase!</div>
</body>
</html>