<?php
session_start();

if (!isset($_SESSION['otp']) || !isset($_SESSION['reset_password'])) {
    header("Location: forgot_password.php");
    exit();
}

if (isset($_POST['verify'])) {
    $user_otp = $_POST['otp'];
    if ($user_otp == $_SESSION['otp']) {
        // OTP verified, redirect to reset password
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP - Smart Step</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-center mb-4">Verify OTP</h2>
        <?php if (isset($error)) echo "<p class='text-red-600 mb-2'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required class="border p-2 w-full mb-3 rounded" />
            <button type="submit" name="verify" class="bg-blue-600 text-white w-full py-2 rounded hover:bg-blue-700">Verify OTP</button>
        </form>
    </div>
</body>
</html>
