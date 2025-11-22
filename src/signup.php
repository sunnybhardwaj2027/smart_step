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
    $name = $_POST['full_name'];

    // Server-side validation for name (no digits allowed)
    if (preg_match('/\d/', $name)) {
        echo "<script>alert('Name cannot contain digits.');</script>";
    } else {
        // Check if email already exists
        $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            echo "<script>alert('Email already registered. Please login.');</script>";
        } else {
            $_SESSION['otp'] = rand(100000, 999999);
            $_SESSION['email'] = $email;
            $subject = "Your OTP for Smart Step";
            $message = "Hii $name, Your OTP is: " . $_SESSION['otp'] . ". Please enter this OTP to verify your email.";
            $headers = "From: prince1p100@gmail.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "<script>alert('OTP sent to your email.');</script>";
            } else {
                echo "<script>alert('Failed to send OTP.');</script>";
            }
        }
    }
}

// Handle Signup After OTP Verification
if (isset($_POST['signup'])) {
    $name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $role = $conn->real_escape_string($_POST['role']);
    $otp = $conn->real_escape_string($_POST['otp']);

    // Validate name (no digits allowed)
    if (preg_match('/\d/', $name)) {
        echo "<script>alert('Name cannot contain digits.');</script>";
    }
    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email address.');</script>";
    }
    // Validate password strength
    elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/", $password)) {
        echo "<script>alert('Password must contain at least 8 characters, including uppercase, lowercase, numbers, and special characters.');</script>";
    }
    // Validate if OTP matches and email matches
    elseif ($_SESSION['otp'] == $otp && $_SESSION['email'] == $email) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
        $stmt->execute();

        echo "<script>alert('Registration successful! Please verify your email.'); window.location='login.php';</script>";
        unset($_SESSION['otp'], $_SESSION['email']);
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - Smart Step</title>
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
    <script>
        function validateForm() {
            const nameField = document.querySelector('input[name="full_name"]');
            const name = nameField.value;

            // Check if the name contains digits
            if (/\d/.test(name)) {
                alert("Name cannot contain digits.");
                nameField.focus();
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-center">Sign Up</h2>
        <form method="POST" onsubmit="return validateForm();">
            <input type="text" name="full_name" placeholder="Full Name" required class="border p-2 w-full mt-3">
            <input type="email" name="email" placeholder="Email Address" required class="border p-2 w-full mt-3">
            <button type="submit" name="send_otp" class="bg-blue-500 text-white w-full p-2 rounded mt-4">Send OTP</button>
        </form>

        <form method="POST" onsubmit="return validateForm();">
            <input type="text" name="full_name" placeholder="Full Name" required class="border p-2 w-full mt-3">
            <input type="email" name="email" placeholder="Email Address" required class="border p-2 w-full mt-3">
            <input type="text" name="otp" placeholder="Enter OTP" required class="border p-2 w-full mt-3">
            <input type="password" name="password" placeholder="Password" required class="border p-2 w-full mt-3">
            <select name="role" class="border p-2 w-full mt-3">
                <option value="Customer">Customer</option>
                <option value="Manager">Manager</option>
            </select>
            <button type="submit" name="signup" class="bg-green-500 text-white w-full p-2 rounded mt-4">Sign Up</button>
        </form>

        <p class="mt-4 text-center">Already have an account? <a href="login.php" class="text-blue-500">Login</a></p>
    </div>
</body>
</html>
