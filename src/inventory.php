<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "smart_step_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch inventory data
$sql = "SELECT material_name, current_stock, required_stock FROM inventory";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - Smart Step</title>
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
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Smart Step</h1>
            <ul class="flex space-x-6">
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="index.php" >Home</a></li>
            <li><a href="order.php" >Order</a></li>
            <!-- <li><a href="inventory.php" class="hover:underline">Inventory</a></li> -->
            <li><a href="upload_product.php">Upload Shoes</a></li>
            <li><a href="update_quantity.php" >Update Shoes</a></li>
            <li><a href="logout.php" >Logout</a></li>
            </ul>
        </div>
    </nav>
    
    <section class="container mx-auto mt-10 px-4">
  <div class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-3xl font-bold text-center">Manage Your Inventory Efficiently</h2>
    <p class="text-gray-700 text-center mt-2">Track raw materials in real-time and ensure seamless shoe production.</p>

    <!-- Inventory Table -->
    <div class="mt-6 overflow-x-auto">
      <table class="w-full border-collapse bg-white shadow-md">
        <thead>
          <tr class="bg-gray-500 text-white">
            <th class="p-3 text-left">Material Name</th>
            <th class="p-3 text-left">Current Stock</th>
            <th class="p-3 text-left">Required Stock</th>
            <th class="p-3 text-left">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border-b">
              <td class="p-3"><?php echo $row['material_name']; ?></td>
              <td class="p-3"><?php echo $row['current_stock']; ?></td>
              <td class="p-3"><?php echo $row['required_stock']; ?></td>
              <td class="p-3">
                <?php 
                  $status = "<span class='text-green-600'>Sufficient</span>";
                  if ($row['current_stock'] <= $row['required_stock'] * 0.5) {
                    $status = "<span class='text-red-600 font-bold'>Critical - Restock Needed!</span>";
                  } elseif ($row['current_stock'] <= $row['required_stock'] * 0.8) {
                    $status = "<span class='text-yellow-500 font-semibold'>Low Stock</span>";
                  }
                  echo $status;
                ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<section class="container mx-auto mt-10 px-4">
  <div class="bg-white shadow-lg rounded-lg p-6 text-center">
    <h3 class="text-2xl font-bold">Stock Alerts</h3>
    <p class="mt-2 text-gray-700">Receive notifications when stock levels are low.</p>
    <a href="manage_inventory.php" class="mt-4 inline-block bg-red-500 text-white px-6 py-3 text-lg font-semibold rounded-lg hover:bg-red-600">
      Request Restock
    </a>
  </div>
</section>

    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 mt-10">
        <p>&copy; 2025 Smart Step. All rights reserved.</p>
        <div class="mt-4">
            <a href="#" class="hover:underline mx-2">Inventory Reports</a>
            <a href="#" class="hover:underline mx-2">Production Insights</a>
            <a href="#" class="hover:underline mx-2">Restocking Policy</a>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
