<?php
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$totalOrders = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$pendingOrders = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status='Pending'")->fetch_assoc()['count'];
$completedOrders = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status='Completed'")->fetch_assoc()['count'];
$lowStockItems = $conn->query("SELECT COUNT(*) AS count FROM products WHERE quantity < 20")->fetch_assoc()['count'];

$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Smart Step</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-blue-600 p-4 text-white">
    <div class="container mx-auto flex flex-wrap justify-between items-center">
        <h1 class="text-2xl font-bold">Smart Step - Admin Dashboard</h1>
        <ul class="flex flex-wrap gap-4 mt-2 sm:mt-0">
            <li><a href="index.php" >Home</a></li>
            <li><a href="order.php" >Order</a></li>
            <li><a href="inventory.php" >Inventory</a></li>
            <li><a href="upload_product.php" >Upload Shoes</a></li>
            <li><a href="update_quantity.php" >Update Shoes</a></li>
            <li><a href="users.php" >Users</a></li>
            <li><a href="logout.php" >Logout</a></li>
        </ul>
    </div>
</nav>

<!-- Dashboard Metrics -->
<section class="container mx-auto mt-10 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-blue-200 p-6 rounded-lg text-center">
            <h3 class="text-2xl font-bold" id="totalOrders">0</h3>
            <p>Total Orders</p>
        </div>
        <div class="bg-yellow-200 p-6 rounded-lg text-center">
            <h3 class="text-2xl font-bold" id="pendingOrders">0</h3>
            <p>Pending Orders</p>
        </div>
        <div class="bg-green-200 p-6 rounded-lg text-center">
            <h3 class="text-2xl font-bold" id="completedOrders">0</h3>
            <p>Completed Orders</p>
        </div>
        <div class="bg-red-200 p-6 rounded-lg text-center">
            <h3 class="text-2xl font-bold" id="lowStockItems">0</h3>
            <p>Low Stock Alerts</p>
        </div>
    </div>
</section>

<!-- Product Inventory -->
<section class="container mx-auto mt-10 px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-wrap justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Product Inventory</h2>
            <input type="text" id="searchInput" placeholder="Search Products" class="border p-2 rounded w-full sm:w-80 text-sm" onkeyup="filterProducts()">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm" id="productTable">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 border">Image</th>
                        <th class="p-3 border">Name</th>
                        <th class="p-3 border">Size</th>
                        <th class="p-3 border">Quantity</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Update Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $products->fetch_assoc()): ?>
                        <tr class="border-t">
                            <td class="p-2"><img src="<?= $row['image'] ?>" class="w-16 h-16 object-cover"></td>
                            <td class="p-2"><?= $row['name'] ?></td>
                            <td class="p-2"><?= $row['size'] ?></td>
                            <td class="p-2"><?= $row['quantity'] ?></td>
                            <td class="p-2">
                                <?php if ($row['quantity'] < 20): ?>
                                    <span class="text-red-600 font-semibold">Low Stock</span>
                                <?php else: ?>
                                    <span class="text-green-600">In Stock</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3">
                                <form action="upd_quan.php" method="POST" class="flex items-center gap-2">
                                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                    <input type="number" name="new_quantity" placeholder="Qty" class="border p-2 w-24" required>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>



<!-- Pending Orders -->
<section class="container mx-auto mt-10 px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Pending Orders</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 border">Order ID</th>
                        <th class="p-3 border">User ID</th>
                        <th class="p-3 border">Product ID</th>
                        <th class="p-3 border">Size</th>
                        <th class="p-3 border">Quantity</th>
                        <th class="p-3 border">Total Price</th>
                        <th class="p-3 border">Created At</th>
                        <th class="p-3 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pendingResult = $conn->query("SELECT * FROM orders WHERE status = 'Pending'");
                    while ($order = $pendingResult->fetch_assoc()):
                    ?>
                    <tr class="border-t">
                        <td class="p-3"><?= $order['id'] ?></td>
                        <td class="p-3"><?= $order['user_id'] ?></td>
                        <td class="p-3"><?= $order['product_id'] ?></td>
                        <td class="p-3"><?= $order['size'] ?></td>
                        <td class="p-3"><?= $order['quantity'] ?></td>
                        <td class="p-3">$<?= number_format($order['total_price'], 2) ?></td>
                        <td class="p-3"><?= $order['created_at'] ?></td>
                        <td class="p-3">
                            <form method="POST" action="update_order_status.php">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Mark as Completed
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>


<!-- Footer -->
<footer class="bg-gray-800 text-white text-center py-4 mt-10">
    &copy; 2025 Smart Step. All rights reserved.
</footer>
<script>
function animateValue(id, start, end, duration) {
    let obj = document.getElementById(id);
    let range = end - start;
    let stepTime = Math.abs(Math.floor(duration / range));
    let startTime = new Date().getTime();
    let endTime = startTime + duration;
    let timer;

    function run() {
        let now = new Date().getTime();
        let remaining = Math.max((endTime - now) / duration, 0);
        let value = Math.round(end - (remaining * range));
        obj.innerText = value;
        if (value === end) clearInterval(timer);
    }

    timer = setInterval(run, stepTime);
    run();
}

// Use PHP to pass the values into JS
animateValue("totalOrders", 0, <?= $totalOrders ?>, 1000);
animateValue("pendingOrders", 0, <?= $pendingOrders ?>, 1000);
animateValue("completedOrders", 0, <?= $completedOrders ?>, 1000);
animateValue("lowStockItems", 0, <?= $lowStockItems ?>, 1000);

function filterProducts() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toLowerCase();
    let table = document.getElementById("productTable");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) { // Skip header row
        let td = tr[i].getElementsByTagName("td")[1]; // Product name column
        if (td) {
            let textValue = td.textContent || td.innerText;
            tr[i].style.display = textValue.toLowerCase().includes(filter) ? "" : "none";
        }
    }
}
</script>

</body>
</html>