<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['email'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $email = $_SESSION['email'];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            session_unset();
            session_destroy();
            echo "<script>alert('Password reset successful! Please login.'); window.location='login.php';</script>";
            exit();
        } else {
            $error = "Something went wrong. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Smart Step</title>
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
        <h2 class="text-2xl font-bold text-center mb-4">Reset Password</h2>
        <?php if (isset($error)) echo "<p class='text-red-600 mb-2'>$error</p>"; ?>
        <form method="POST">
            <input type="password" name="password" placeholder="New Password" required class="border p-2 w-full mb-3 rounded" />
            <input type="password" name="confirm" placeholder="Confirm Password" required class="border p-2 w-full mb-3 rounded" />
            <button type="submit" class="bg-green-600 text-white w-full py-2 rounded hover:bg-green-700">Reset Password</button>
        </form>
    </div>
</body>
</html>
