<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo "<script>alert('Please login to continue.'); window.location='login.php';</script>";
    exit();
}

// Manager is not allowed
if ($_SESSION['role'] == 'Manager') {
    echo "<script>alert('You are not allowed to access this page!'); window.location='admin.php';</script>";
    exit();
}

//  Add to Cart
$message = "";

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();

        if ($quantity > $product['quantity']) {
            $message = "Only {$product['quantity']} item(s) left in stock.";
        } else {
            $checkCartSql = "SELECT * FROM cart WHERE product_id = $product_id AND user_id = $user_id";
            $cartResult = $conn->query($checkCartSql);

            if ($cartResult->num_rows > 0) {
                $updateSql = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = $product_id AND user_id = $user_id";
                $conn->query($updateSql);
            } else {
                $insertSql = "INSERT INTO cart (product_id, name, description, price, discount_price, quantity, image, user_id) VALUES (
                    {$product['id']}, 
                    '{$product['name']}', 
                    '{$product['description']}', 
                    {$product['price']}, 
                    {$product['discount_price']}, 
                    $quantity, 
                    '{$product['image']}', 
                    $user_id
                )";
                $conn->query($insertSql);
            }

            $message = "Product added to cart successfully!";
        }
    } else {
        $message = "Product not found!";
    }
} else {
    $message = "Invalid request!";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Step - Add to Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ["Poppins", "sans-serif"],
                    },
                },
            },
        }
    </script>
</head>
<body class="flex flex-col min-h-screen bg-gray-50 font-poppins">

    <!-- Header -->
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto">
            <h1 class="text-xl font-semibold">Smart Step</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-4">
        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md text-center">
            <h2 class="text-xl font-bold mb-4">Cart Status</h2>
            <p class="text-gray-800"><?php echo $message; ?></p>
            <a href="products.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Back to Products</a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        &copy; <?php echo date("Y"); ?> Smart Step. All rights reserved.
    </footer>

</body>
</html>
