<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$orders = $conn->query("SELECT o.*, p.name AS product_name, p.image 
                        FROM orders o 
                        JOIN products p ON o.product_id = p.id 
                        WHERE o.user_id = $user_id 
                        ORDER BY o.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - Smart Step</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

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
<body class="bg-gray-100 min-h-screen ">
<header class="bg-blue-600 p-2 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Smart Step - Products</h1>
            <nav class="bg-blue-600 p-4 text-white">
                <div class="container mx-auto flex justify-between items-center">
                    <ul class="flex space-x-6 items-center">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="products.php" >Products</a></li>
                        
                        <li>
                            <?php
                            if (isset($_SESSION["user_id"])) {
                                echo "<a href='logout.php'>Logout</a>";
                            } else {
                                echo "<a href='login.php' >Login</a>";
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </nav>

        </div>
</header>
    <h1 class="text-3xl font-bold mb-6 text-center text-blue-600 pt-2">My Orders</h1>

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6 space-y-4">
        <?php if ($orders->num_rows > 0): ?>
            <?php while ($row = $orders->fetch_assoc()): ?>
                <div class="border p-4 rounded-lg flex items-center space-x-4">
                    <img src="<?= $row['image'] ?>" alt="Product" class="w-20 h-20 object-cover rounded" />
                    <div>
                        <h2 class="text-xl font-semibold"><?= $row['product_name'] ?></h2>
                        <p>Size: <?= $row['size'] ?> | Quantity: <?= $row['quantity'] ?></p>
                        <p>Status: <span class="text-blue-600 font-medium"><?= $row['status'] ?></span></p>
                        <p class="text-gray-500 text-sm">Ordered on: <?= date("d M Y", strtotime($row['created_at'])) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-gray-600">You haven't placed any orders yet.</p>
        <?php endif; ?>
    </div>
    <!-- Footer -->
<footer class="bg-gray-800 text-white text-center py-4 mt-10">
    &copy; 2025 Smart Step. All rights reserved.
</footer>
</body>
</html>
