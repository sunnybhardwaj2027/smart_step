<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

// Check DB Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle OTP Generation and Sending
if (isset($_POST['send_otp'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $name = $conn->real_escape_string($_POST['full_name']);

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered. Please login.'); window.location.href='login.php';</script>";
        exit();
    } else {
        // Generate OTP and store in session
        $_SESSION['otp'] = rand(100000, 999999);
        $_SESSION['email'] = $email;
        $_SESSION['full_name'] = $name;

        $subject = "Your OTP for Smart Step";
        $message = "Hi $name,\n\nYour OTP is: " . $_SESSION['otp'] . "\n\nPlease enter this OTP to verify your email.\n\nThanks,\nSmart Step Team";
        $headers = "From: Smart Step <subhashree.s237@gmail.com>";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('OTP sent to your email.'); window.location.href='verify_otp.php';</script>";
        } else {
            echo "<script>alert('Failed to send OTP. Please try again later.');</script>";
        }
    }
}
?>
