<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SESSION['role'] == 'Manager') {
    echo "<script>alert('You are not allowed to access this page!'); window.location='admin.php';</script>";
    exit();
}
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products - Smart Step</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-blue-600 p-2 text-white">
        <div class="max-w-[1400px] mx-auto px-8 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Smart Step - Products</h1>
            <nav class="p-4 text-white">
                <ul class="flex space-x-6 items-center">
                    <li><a href="index.php" class="hover:bg-white hover:text-blue-600 px-2 py-1 rounded">Home</a></li>
                    <li><a href="cart.php" class="hover:bg-white hover:text-blue-600 px-2 py-1 rounded">View Cart</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Customer'): ?>
                        <li><a href="my_orders.php" class="hover:bg-white hover:text-blue-600 px-2 py-1 rounded">My Orders</a></li>
                    <?php endif; ?>
                    <li class="hover:bg-white hover:text-blue-600 px-2 py-1 rounded">
                        <?php
                        if (isset($_SESSION["user_id"])) {
                            echo "<a href='logout.php' >Logout</a>";
                        } else {
                            echo "<a href='login.php' >Login</a>";
                        }
                        ?>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="max-w-[1400px] mx-auto mt-10 px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-transform transform hover:-translate-y-1">
                <img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>" class="w-full h-52 object-cover rounded-md">
                <div class="mt-3">
                    <h2 class="text-lg font-semibold"><?= $row['name']; ?></h2>
                    <p class="text-sm text-gray-600 mt-1"><?= $row['description']; ?></p>
                    <p class="text-lg font-semibold text-green-600 mt-2">
                        $<?= $row['discount_price']; ?>
                        <span class="line-through text-sm text-gray-500 ml-2">$<?= $row['price']; ?></span>
                    </p>

                    <div class="flex items-center text-yellow-500 mt-2">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <?php if ($i < $row['rating']): ?>
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.561-.954L10 0l2.949 5.956 6.561.954-4.755 4.635 1.123 6.545z"/></svg>
                            <?php else: ?>
                                <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.561-.954L10 0l2.949 5.956 6.561.954-4.755 4.635 1.123 6.545z"/></svg>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span class="ml-2 text-sm text-gray-600">(<?= $row['reviews']; ?> reviews)</span>
                    </div>

                    <p class="text-sm text-gray-700 mt-1">Available: <?= $row['quantity']; ?> pcs</p>
                    <p class="text-sm text-gray-700">Size: <?= $row['size']; ?></p>
                </div>

                <div class="mt-4">
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                        <label class="block text-sm text-gray-600 mb-1">Quantity:</label>
                        <input type="number" name="quantity" value="1" min="1" max="<?= $row['quantity']; ?>" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center">
                        <a href="product_detail.php?id=<?= $row['id'] ?>" class="block mt-3 w-full bg-blue-500 text-white text-center py-2 rounded font-semibold uppercase text-sm hover:bg-blue-600 transition">View</a>
                        <button type="submit" class="mt-2 w-full bg-blue-500 text-white py-2 rounded font-semibold uppercase text-sm hover:bg-blue-600 transition">Add to Cart</button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <!-- Footer -->
<footer class="bg-gray-800 text-white text-center py-4 mt-10">
    &copy; 2025 Smart Step. All rights reserved.
</footer>
</body>
</html>
