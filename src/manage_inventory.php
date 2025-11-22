<?php
$conn = new mysqli("localhost", "root", "", "smart_step_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_material'])) {
    $material_name = $_POST['material_name'];
    $unit_type = $_POST['unit_type'];
    $current_stock = $_POST['current_stock'];
    $min_required_stock = $_POST['min_required_stock'];
    $required_stock = $_POST['required_stock'];

    $conn->query("INSERT INTO inventory (material_name, unit_type, current_stock, min_required_stock, required_stock) 
                  VALUES ('$material_name', '$unit_type', $current_stock, $min_required_stock, $required_stock)");

    header("Location: manage_inventory.php");
    exit();
}

if (isset($_POST['update_stock'])) {
    $id = $_POST['id'];
    $new_stock = $_POST['new_stock'];

    $conn->query("UPDATE inventory SET current_stock = $new_stock WHERE id = $id");

    header("Location: manage_inventory.php");
    exit();
}

if (isset($_POST['delete_material'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM inventory WHERE id = $id");
    header("Location: manage_inventory.php");
    exit();
}

$result = $conn->query("SELECT * FROM inventory");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Inventory - Smart Step</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100" style="font-family: 'Poppins', sans-serif;">

<!-- Navbar -->
<nav class="bg-blue-600 p-4 text-white">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Smart Step - Manage Inventory</h1>
        <ul class="flex space-x-4">
            <li><a href="admin.php" class="hover:bg-white hover:text-blue-600 px-3 py-1 rounded">Dashboard</a></li>
            <li><a href="index.php" class="hover:bg-white hover:text-blue-600 px-3 py-1 rounded">Home</a></li>
            <li><a href="order.php" class="hover:bg-white hover:text-blue-600 px-3 py-1 rounded">Order</a></li>
            <li><a href="inventory.php" class="hover:bg-white hover:text-blue-600 px-3 py-1 rounded">Inventory</a></li>
            <li><a href="upload_product.php" class="hover:bg-white hover:text-blue-600 px-3 py-1 rounded">Upload Shoes</a></li>
            <li><a href="update_quantity.php" class="hover:bg-white hover:text-blue-600 px-3 py-1 rounded">Update Shoes</a></li>
            <li><a href="logout.php" class="hover:bg-white hover:text-blue-600 px-3 py-1 rounded">Logout</a></li>
        </ul>
    </div>
</nav>

<!-- Inventory Overview -->
<section class="container mx-auto mt-10 px-4">
    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
        <h2 class="text-3xl font-bold mb-2">Manage Your Inventory Efficiently</h2>
        <p class="text-gray-700">Track raw materials in real-time and ensure seamless shoe production.</p>

        <!-- Inventory Table -->
        <div class="mt-6 overflow-x-auto">
            <table class="w-full table-auto border border-gray-200 text-left">
                <thead>
                    <tr class="bg-gray-500 text-white">
                        <th class="p-3">Material</th>
                        <th class="p-3">Current Stock</th>
                        <th class="p-3">Required Stock</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Actions</th>
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
                            <td class="p-3 text-center space-x-2">
                                <!-- Update Button (opens modal) -->
                                <button onclick="openUpdateModal(<?php echo $row['id']; ?>, <?php echo $row['current_stock']; ?>)" class="bg-blue-500 text-white px-3 py-1 rounded">Update</button>

                                <!-- Delete Form -->
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_material" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Add New Material Section -->
<section class="container mx-auto mt-10 px-4">
    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
        <h2 class="text-2xl font-bold mb-4">Add New Raw Material</h2>
        <form method="POST" class="grid grid-cols-1 gap-4 max-w-xl mx-auto">
            <input type="text" name="material_name" placeholder="Material Name" required class="border p-2 rounded">
            <input type="text" name="unit_type" placeholder="Unit Type (e.g. pieces)" required class="border p-2 rounded">
            <input type="number" name="current_stock" placeholder="Initial Stock Quantity" required class="border p-2 rounded">
            <input type="number" name="min_required_stock" placeholder="Minimum Required Stock" required class="border p-2 rounded">
            <input type="number" name="required_stock" placeholder="Required Stock" required class="border p-2 rounded">
            <button type="submit" name="add_material" class="bg-green-500 text-white px-4 py-2 rounded">Add Material</button>
        </form>
    </div>
</section>

<!-- Update Modal -->
<div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-xl font-semibold mb-4">Update Stock</h3>
        <form method="POST">
            <input type="hidden" name="id" id="updateId">
            <input type="number" name="new_stock" id="newStockInput" placeholder="New Stock" required class="border w-full p-2 rounded mb-4">
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeUpdateModal()" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                <button type="submit" name="update_stock" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal JS -->
<script>
function openUpdateModal(id, currentStock) {
    document.getElementById("updateModal").classList.remove("hidden");
    document.getElementById("updateId").value = id;
    document.getElementById("newStockInput").value = currentStock;
}

function closeUpdateModal() {
    document.getElementById("updateModal").classList.add("hidden");
}
</script>

<!-- Footer -->
<footer class="bg-gray-800 text-white text-center p-4 mt-10">
    <p>&copy; 2025 Smart Step. All rights reserved.</p>
</footer>

</body>
</html>
