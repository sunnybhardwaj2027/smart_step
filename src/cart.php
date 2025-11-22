<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'Manager') {
    echo "<script>alert('You are not allowed to access this page!'); window.location='admin.php';</script>";
    exit();
}

$user_id = intval($_SESSION['user_id']);
$result = $conn->query("SELECT cart.*, products.quantity AS stock FROM cart 
                        JOIN products ON cart.product_id = products.id 
                        WHERE cart.user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Cart - Smart Step</title>
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
<body class="bg-gray-100 font-poppins">

<header class="bg-blue-600 p-3 text-white">
    <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
        <h1 class="text-2xl font-bold">Smart Step - Products</h1>

        <nav class="mt-2 md:mt-0">
            <ul class="flex space-x-6 items-center">
                <li>
                    <a href="index.php"
                       class="px-2 py-1 rounded transition-colors duration-300 hover:bg-white hover:text-blue-600">
                        Home
                    </a>
                </li>
                <li>
                    <a href="products.php"
                       class="px-2 py-1 rounded transition-colors duration-300 hover:bg-white hover:text-blue-600">
                        Products
                    </a>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Customer'): ?>
                <li>
                    <a href="my_orders.php"
                       class="px-2 py-1 rounded transition-colors duration-300 hover:bg-white hover:text-blue-600">
                        My Orders
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <?php
                    if (isset($_SESSION["user_id"])) {
                        echo "<a href='logout.php' class='px-2 py-1 rounded transition-colors duration-300 hover:bg-white hover:text-blue-600'>Logout</a>";
                    } else {
                        echo "<a href='login.php' class='px-2 py-1 rounded transition-colors duration-300 hover:bg-white hover:text-blue-600'>Login</a>";
                    }
                    ?>
                </li>
            </ul>
        </nav>
    </div>
</header>

</body>
</html>



    <div class="container mx-auto mt-10 px-4">
        <h2 class="text-3xl font-bold mb-6">Your Cart</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="overflow-auto">
                <table class="w-full bg-white rounded-lg shadow-md text-sm md:text-base">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-3">Product</th>
                            <th class="p-3">Price</th>
                            <th class="p-3">Quantity</th>
                            <th class="p-3">Total</th>
                            <th class="p-3">Stock</th>
                            <th class="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php $total = 0; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php 
                                $subtotal = $row['quantity'] * $row['discount_price']; 
                                $total += $subtotal;
                            ?>
                            <tr class="border-t">
                                <td class="p-3 flex items-center gap-3">
                                    <img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>" class="w-16 h-16 object-cover rounded-md">
                                    <span><?= $row['name']; ?></span>
                                </td>
                                <td class="p-3">$<?= number_format($row['discount_price'], 2); ?></td>
                                <td class="p-3">
                                    <form action="update_cart.php" method="POST" class="flex justify-center items-center gap-2">
                                        <input type="hidden" name="cart_id" value="<?= $row['id']; ?>">
                                        <input type="number" name="quantity" value="<?= $row['quantity']; ?>" min="1" max="<?= $row['stock']; ?>" class="w-16 p-1 border rounded text-center">
                                        <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Update</button>
                                    </form>
                                </td>
                                <td class="p-3">$<?= number_format($subtotal, 2); ?></td>
                                <td class="p-3"><?= $row['stock']; ?></td>
                                <td class="p-3">
                                    <a href="remove_from_cart.php?id=<?= $row['id']; ?>" class="text-red-500 hover:underline">Remove</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-right">
                <h3 class="text-xl font-bold">Total: $<?= number_format($total, 2); ?></h3>
                <form action="checkout.php" method="post">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 mt-4 rounded-lg hover:bg-blue-700">Proceed to Checkout</button>
                </form>
            </div>
        <?php else: ?>
            <p class="text-lg">Your cart is empty. <a href="products.php" class="text-blue-500 underline">Shop Now</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
