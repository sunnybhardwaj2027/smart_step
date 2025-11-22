<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['status'] === 'Pending') {
                echo "<script>alert('Please verify your email first.');</script>";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                if($user['role'] === 'Manager') {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }
            }
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('User not found. Please sign up.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Smart Step</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center m-0">

    <div class="bg-white p-6 rounded-lg border-gray-300 shadow-md w-full max-w-sm text-center">
        <h2 class="text-3xl font-bold text-black-500">Login</h2>
        <form method="POST" class="mt-6">
            <input type="email" name="email" placeholder="Email Address" required
                class="border border-gray-300 px-4 py-2 w-full mt-3 rounded-lg text-base" />
            <input type="password" name="password" placeholder="Password" required
                class="border border-gray-300 px-4 py-2 w-full mt-3 rounded-lg text-base" />
            <button type="submit" name="login"
                class="bg-blue-500 text-white px-4 py-2 w-full rounded-lg text-base mt-5 hover:bg-blue-600 transition">Login</button>
        </form>
        <p class="text-sm mt-4">
            <a href="forgot_password.php" class="text-blue-500 hover:underline">Forgot Password?</a>
        </p>
        <p class="text-sm mt-2">
            Don't have an account? <a href="signup.php" class="text-blue-500 hover:underline">Sign Up</a>
        </p>
    </div>

</body>
</html>
