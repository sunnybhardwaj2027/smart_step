<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$products = $conn->query("SELECT * FROM products LIMIT 8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Step - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 text-gray-800 font-sans">

<!-- Navbar -->
<header class="bg-blue-700 p-4 text-white shadow">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Smart Step</h1>
        <ul class="flex items-center space-x-4">
            <li><a href="index.php" class="hover:bg-white hover:text-blue-600 px-2 py-1 rounded">Home</a></li>
            <li>
                <a href="products.php" class="hover:bg-white hover:text-blue-600 px-2 py-1 rounded">Shop</a>
            </li>
            <li>
                <a href="cart.php" class="hover:bg-white hover:text-blue-600 px-2 py-1 rounded">Cart</a>
            </li>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Customer'): ?>
                <li>
                    <a href="my_orders.php" class="hover:bg-white hover:text-blue-600 px-2 py-1 rounded">My Orders</a>
                </li>
            <?php endif; ?>

            <li>
                <?php
                    echo isset($_SESSION["user_id"])
                        ? "<a href='logout.php' class='hover:bg-white hover:text-blue-600 px-2 py-1 rounded'>Logout</a>"
                        : "<a href='login.php' class='hover:bg-white hover:text-blue-600 px-2 py-1 rounded'>Login</a>";
                ?>
            </li>
        </ul>
    </div>
</header>


<!-- Hero Section -->
<section class="bg-blue-100 py-20 text-center">
    <h2 class="text-5xl font-bold text-blue-800 mb-4">Step Into Comfort & Style</h2>
    <p class="text-lg text-gray-700 mb-6">Explore our latest collection of trendy and comfortable footwear.</p>
    <a href="products.php" class="bg-blue-600 text-white px-8 py-3 rounded-full text-lg hover:bg-blue-700 transition">Shop Now</a>
</section>

<!-- Categories -->
<section class="py-16 bg-white">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold mb-10 text-blue-800">Shop by Category</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div class="p-6 bg-blue-50 rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold mb-2 text-blue-700">Men's Shoes</h3>
                <p>Classy, casual, and comfy – all in one place.</p>
            </div>
            <div class="p-6 bg-blue-50 rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold mb-2 text-blue-700">Women's Shoes</h3>
                <p>Stylish, supportive, and sleek footwear for every day.</p>
            </div>
            <div class="p-6 bg-blue-50 rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-xl font-semibold mb-2 text-blue-700">Sports</h3>
                <p>Performance-driven shoes made for movement.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-16 px-4">
    <h2 class="text-3xl font-bold text-center mb-10 text-blue-800">Featured Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <?php while ($row = $products->fetch_assoc()): ?>
            <div class="bg-white p-4 rounded-lg shadow hover:shadow-xl transition">
                <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="w-full h-48 object-cover rounded">
                <h3 class="text-lg font-semibold mt-3 text-blue-900"><?= $row['name'] ?></h3>
                <p class="text-blue-600 font-bold mt-1">$<?= number_format($row['price'], 2) ?></p>
                <a href="product_detail.php?id=<?= $row['id'] ?>" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">View</a>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Testimonials -->
<section class="bg-blue-50 py-16">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold mb-10 text-blue-800">What Our Customers Say</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            <div class="bg-white p-6 rounded shadow">
                <p class="mb-4">"The quality is top-notch! I walk miles every day with ease."</p>
                <span class="font-semibold text-blue-700">- Anjali, Delhi</span>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="mb-4">"Affordable and stylish – Smart Step has changed my shoe game!"</p>
                <span class="font-semibold text-blue-700">- Rahul, Mumbai</span>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="mb-4">"Smooth shopping experience. Highly recommended!"</p>
                <span class="font-semibold text-blue-700">- Priya, Bangalore</span>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-16 bg-white">
    <div class="max-w-xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-4 text-blue-800">Subscribe to Our Newsletter</h2>
        <p class="mb-6 text-gray-600">Be the first to know about exclusive offers and new arrivals.</p>
        <form class="flex flex-col sm:flex-row gap-4 justify-center">
            <input type="email" placeholder="Enter your email" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Subscribe</button>
        </form>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white mt-10">
    <div class="container mx-auto py-6 px-4 flex flex-col md:flex-row justify-between items-center text-center md:text-left">
        <p>&copy; 2025 Smart Step. All rights reserved.</p>
        <div class="flex gap-4 mt-4 md:mt-0">
            <a href="#" class="hover:text-blue-400">Facebook</a>
            <a href="#" class="hover:text-blue-400">Instagram</a>
            <a href="#" class="hover:text-blue-400">Twitter</a>
        </div>
    </div>
</footer>

</body>
</html>
