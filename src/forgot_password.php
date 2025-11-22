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

    // Check if email exists
    $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($check->num_rows == 0) {
        echo "<script>alert('No account found with this email.'); window.location.href='signup.php';</script>";
        exit();
    } else {
        // Generate OTP and save in session
        $_SESSION['otp'] = rand(100000, 999999);
        $_SESSION['email'] = $email;
        $_SESSION['full_name'] = $name;
        $_SESSION['reset_password'] = true;

        $subject = "OTP for Password Reset - Smart Step";
        $message = "Hi $name,\n\nYour OTP to reset your Smart Step account password is: " . $_SESSION['otp'] . "\n\nPlease use this to proceed.\n\nThanks,\nSmart Step Team";
        $headers = "From: Smart Step <prince1p100@gmail.com>";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('OTP sent to your email.'); window.location.href='verify_otp.php';</script>";
        } else {
            echo "<script>alert('Failed to send OTP. Please try again later.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Smart Step</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- Custom font override -->
    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }
        nav ul li a {
    padding: 4px 8px;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
}

nav ul li a:hover {
    text-decoration: none;
    color: blue;
    background-color: #ffffff;
}

    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-center mb-4">Forgot Password</h2>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required class="border p-2 w-full mb-3 rounded" />
            <input type="email" name="email" placeholder="Email Address" required class="border p-2 w-full mb-3 rounded" />
            <button type="submit" name="send_otp" class="bg-blue-600 text-white w-full py-2 rounded hover:bg-blue-700">Send OTP</button>
        </form>
        <p class="mt-4 text-center">
            Remembered password? <a href="login.php" class="text-blue-600 hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
