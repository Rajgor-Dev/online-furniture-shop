<?php
session_start();
include 'db.php';

if (isset($_POST['btnRegister'])) {
    $mUserName = $_POST['txtUserName'];
    $mEmail = $_POST['txtEmail'];
   $mPassword = password_hash($_POST['txtPassword'], PASSWORD_DEFAULT); 
    $mMobile = $_POST['txtMobile'];

    // Check if the email exists in the table
    $checkCustomerEmail = "SELECT * FROM customer WHERE email = '$mEmail'";
    $checkAdminEmail = "SELECT * FROM admin WHERE email = '$mEmail'";

    $customerResult = mysqli_query($conn, $checkCustomerEmail);
    $adminResult = mysqli_query($conn, $checkAdminEmail);

    if (mysqli_num_rows($customerResult) > 0 || mysqli_num_rows($adminResult) > 0) {
        echo "<script>alert('Error: The email address already exists. Please use a different email.'); window.location.href='index.php';</script>";
    } else {
        $mSql = "INSERT INTO customer(name, email, password, contact_no) VALUES('$mUserName', '$mEmail', '$mPassword', '$mMobile')";

        if (mysqli_query($conn, $mSql)) {
            echo "<script>alert('Sign-up Successfully... Now you can Sign-in.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='index.php';</script>";
        }
    }
}
?>