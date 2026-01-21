<?php 
include 'db.php';
session_start();
if (isset($_POST['btnLogin'])) {
    $mEmail = $_POST['txtEmail'];
    $mPass = $_POST['txtPassword']; 

    // Check in the admin table
    $sqlAdmin = "SELECT * FROM admin WHERE email='$mEmail'";
    $resultAdmin = mysqli_query($conn, $sqlAdmin);

    if ($resultAdmin && mysqli_num_rows($resultAdmin) === 1) {
        $rowAdmin = mysqli_fetch_assoc($resultAdmin);
        
        if (password_verify($mPass, $rowAdmin['password'])) {
           
            $_SESSION['Email'] = $mEmail;
            echo "<script>alert('Login Successfully as Admin.'); window.location.href='admin_dash.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect Password.');  window.location.href='index.php';</script>";
            exit();
        }
    }

    // Check in the customer table
    $sqlCustomer = "SELECT * FROM customer WHERE email='$mEmail'";
    $resultCustomer = mysqli_query($conn, $sqlCustomer);

    if ($resultCustomer && mysqli_num_rows($resultCustomer) === 1) {
        $rowCustomer = mysqli_fetch_assoc($resultCustomer);
        
        if (password_verify($mPass, $rowCustomer['password'])) {
            // Check customer status
            if ($rowCustomer['status'] === 'active') {
               
                $_SESSION['UID'] = $rowCustomer['id'];
                $_SESSION['User Name'] = $rowCustomer['name'];
                echo "<script>alert('Login Successfully as Customer.'); window.location.href='index.php';</script>";
                exit();
            } else {
                echo "<script>alert('Your account is deactivated. Please contact customer care.'); window.location.href='index.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Incorrect Password.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email.');  window.location.href='index.php';</script>";
    }
}
?>