<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product by ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<h1 class='text-center text-red-600 mt-10'>Invalid Product ID</h1>";
    exit();
}

$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if (!$product) {
    echo "<h1 class='text-center text-red-600 mt-10'>Product not found</h1>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $product['name'] ?> - Smart Step</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }
        header .container ul li a {
    padding: 4px 8px;          
    border-radius: 4px;        
    transition: background-color 0.3s, color 0.3s; 
}

header .container ul li a:hover {
    text-decoration: none;
    color: blue;
    background-color: #ffffff;
}

    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<!-- Navbar -->
<header class="bg-blue-600 p-4 text-white">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Smart Step - Products</h1>
        <nav class="bg-blue-600 text-white">
            <ul class="flex space-x-6 items-center">
                <li><a href="index.php" >Home</a></li>
                <li><a href="cart.php" class="hover:underline">View Cart</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Customer'): ?>
                    <li><a href="my_orders.php" class="hover:underline">My Orders</a></li>
                <?php endif; ?>
                <li>
                    <?php
                    if (isset($_SESSION["user_id"])) {
                        echo "<a href='logout.php' class='hover:underline'>Logout</a>";
                    } else {
                        echo "<a href='login.php' class='hover:underline'>Login</a>";
                    }
                    ?>
                </li>
            </ul>
        </nav>
    </div>
</header>

<!-- Product Details -->
<main class="flex-grow">
    <section class="container mx-auto py-12 px-4 max-w-4xl">
        <div class="bg-white p-6 rounded-lg shadow-md grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Image -->
            <div>
                <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="w-full h-72 object-cover rounded-lg">
            </div>

            <!-- Product Info -->
            <div>
                <h2 class="text-2xl font-bold mb-2"><?= $product['name'] ?></h2>

                <!-- Price -->
                <div class="mb-3">
                    <?php if ($product['discount_price'] < $product['price']): ?>
                        <span class="text-lg text-red-600 font-semibold mr-2">$<?= number_format($product['discount_price'], 2) ?></span>
                        <span class="line-through text-gray-500">$<?= number_format($product['price'], 2) ?></span>
                    <?php else: ?>
                        <span class="text-lg text-gray-800 font-semibold">$<?= number_format($product['price'], 2) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Rating -->
                <div class="mb-2">
                    <span class="text-yellow-500 font-bold">★ <?= number_format($product['rating'], 1) ?></span>
                    <span class="text-gray-600">(<?= $product['reviews'] ?> reviews)</span>
                </div>

                <!-- Description -->
                <p class="text-gray-700 text-sm mb-4"><?= nl2br($product['description']) ?></p>

                <!-- Size & Quantity -->
                <p class="text-sm"><strong>Size:</strong> <?= $product['size'] ?></p>
                <p class="mb-4 text-sm"><strong>In Stock:</strong> <?= $product['quantity'] ?> item(s)</p>

                <!-- Add to Cart -->
                <?php if ($product['quantity'] > 0): ?>
                    <form action="add_to_cart.php" method="POST" class="mt-4">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <label for="quantity" class="block mb-1 font-medium text-sm">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" min="1" max="<?= $product['quantity'] ?>" required class="border p-2 w-24 mb-4 rounded">
                        <br>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 text-sm">
                            Add to Cart
                        </button>
                    </form>
                <?php else: ?>
                    <p class="text-red-600 font-semibold mt-6">Out of Stock</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-gray-800 text-white text-center py-4">
    &copy; <?= date("Y") ?> Smart Step. All rights reserved.
</footer>

</body>
</html>
