<?php
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT orders.*, products.name AS product_name, products.size, users.full_name 
        FROM orders 
        JOIN products ON orders.product_id = products.id 
        JOIN users ON orders.user_id = users.id 
        ORDER BY orders.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders - Admin Panel</title>
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
<body class="bg-gray-100 flex flex-col min-h-screen">

<!-- Navbar -->
<nav class="bg-blue-600 p-4 text-white">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Smart Step - Orders</h1>
        <ul class="flex space-x-6">
            <li><a href="admin.php" >Dashboard</a></li>
            <li><a href="index.php" >Home</a></li>
            <!-- <li><a href="order.php" >Order</a></li> -->
            <li><a href="inventory.php" >Inventory</a></li>
            <li><a href="upload_product.php" >Upload Shoes</a></li>
            <li><a href="update_quantity.php" >Update Shoes</a></li>
            <li><a href="logout.php" >Logout</a></li>
        </ul>
    </div>
</nav>

<!-- Orders Table -->
<!-- Orders Section -->
<section class="container mx-auto mt-10 px-4">
  <div class="bg-white shadow-lg rounded-lg p-6 text-center">
    <h2 class="text-2xl font-bold mb-6">All Orders</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full border text-sm">
        <thead>
          <tr class="bg-gray-100">
            <th class="p-4 border">Order ID</th>
            <th class="p-4 border">Customer Name</th>
            <th class="p-4 border">Product</th>
            <th class="p-4 border">Size</th>
            <th class="p-4 border">Quantity</th>
            <th class="p-4 border">Total Price</th>
            <th class="p-4 border">Status</th>
            <th class="p-4 border">Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr class="text-center border-t">
                <td class="p-4"><?= $row['id'] ?></td>
                <td class="p-4"><?= htmlspecialchars($row['full_name']) ?></td>
                <td class="p-4"><?= htmlspecialchars($row['product_name']) ?></td>
                <td class="p-4"><?= htmlspecialchars($row['size']) ?></td>
                <td class="p-4"><?= $row['quantity'] ?></td>
                <td class="p-4">₹<?= number_format($row['total_price'], 2) ?></td>
                <td class="p-4">
                  <?php
                  $status = $row['status'];
                  $color = match ($status) {
                      'Pending' => 'bg-yellow-200 text-yellow-800',
                      'Completed' => 'bg-green-200 text-green-800',
                      'Cancelled' => 'bg-red-200 text-red-800',
                      default => 'bg-gray-200 text-gray-800',
                  };
                  ?>
                  <span class="px-4 py-2 rounded-full <?= $color ?>"><?= $status ?></span>
                </td>
                <td class="p-4"><?= date("d M Y", strtotime($row['created_at'])) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center p-4 text-gray-600">No orders found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 text-white text-center p-4 mt-10">
    <p>&copy; 2025 Smart Step. All rights reserved.</p>
</footer>

</body>
</html>
