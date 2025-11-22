<?php
// Start session & connect to DB
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users from DB
$result = $conn->query("SELECT * FROM users");

// Handle User Deletion
if (isset($_POST['delete'])) {
    $user_id = $_POST['user_id'];
    $conn->query("DELETE FROM users WHERE id = $user_id");
    header("Location: users.php");
}

// Handle Role Update
if (isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    $conn->query("UPDATE users SET role = '$new_role' WHERE id = $user_id");
    header("Location: users.php");
}

// Handle Adding New User
if (isset($_POST['add_user'])) {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $status = $_POST['status'];

    $conn->query("INSERT INTO users (full_name, email, password, role, status) VALUES ('$name', '$email', '$password', '$role', '$status')");
    header("Location: users.php");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Smart Step</title>
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
            <h1 class="text-2xl font-bold">Smart Step - Admin Dashboard</h1>
            <ul class="flex space-x-6">
                <li><a href="admin.php" >Dashboard</a></li>
                <li><a href="index.php" >Home</a></li>
                <li><a href="order.php" >Order</a></li>
                <li><a href="inventory.php" >Inventory</a></li>
                <li><a href="upload_product.php" >Upload Shoes</a></li>
                <li><a href="update_quantity.php" >Update Shoes</a></li>
                <!-- <li><a href="users.php" >users</a></li> -->
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- User Management Section -->
    <section class="container mx-auto my-10 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-3xl font-bold text-center mb-6">👥 Manage User Accounts & Roles</h2>
        <p class="text-gray-600 text-center mb-6">View, modify, and assign roles to users.</p>

        <!-- User List Table -->
        <table class="w-full border-collapse border border-gray-300 bg-white">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">User ID</th>
                    <th class="border border-gray-300 px-4 py-2">Full Name</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Role</th>
                    <th class="border border-gray-300 px-4 py-2">Status</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli("localhost", "root", "", "smart_step_db");
                $result = $conn->query("SELECT * FROM users");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td class='border border-gray-300 px-4 py-2'>{$row['id']}</td>
                            <td class='border border-gray-300 px-4 py-2'>{$row['full_name']}</td>
                            <td class='border border-gray-300 px-4 py-2'>{$row['email']}</td>
                            <td class='border border-gray-300 px-4 py-2'>
                                <form method='POST' class='inline'>
                                    <input type='hidden' name='user_id' value='{$row['id']}'>
                                    <select name='role' class='border p-1' onchange='this.form.submit()'>
                                        <option " . ($row['role'] == 'Customer' ? 'selected' : '') . ">Customer</option>
                                        <option " . ($row['role'] == 'Manager' ? 'selected' : '') . ">Manager</option>
                                        <option " . ($row['role'] == 'Admin' ? 'selected' : '') . ">Admin</option>
                                    </select>
                                    <input type='hidden' name='update_role' value='1'>
                                </form>
                            </td>
                            <td class='border border-gray-300 px-4 py-2'>{$row['status']}</td>
                            <td class='border border-gray-300 px-4 py-2'>
                                <form method='POST' class='inline'>
                                    <input type='hidden' name='user_id' value='{$row['id']}'>
                                    <button type='submit' name='delete' class='bg-red-500 text-white px-2 py-1 rounded'>Delete</button>
                                </form>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Add New User Form -->
        <div class="mt-10">
            <h3 class="text-2xl font-bold">➕ Add New User</h3>
            <form method="POST" class="grid grid-cols-2 gap-4">
                <input type="text" name="full_name" placeholder="Full Name" required class="border p-2">
                <input type="email" name="email" placeholder="Email Address" required class="border p-2">
                <input type="password" name="password" placeholder="Password" required class="border p-2">
                <select name="role" class="border p-2">
                    <option>Customer</option>
                    <option>Manager</option>
                    <option>Admin</option>
                </select>
                <select name="status" class="border p-2">
                    <option>Active</option>
                    <option>Suspended</option>
                </select>
                <button type="submit" name="add_user" class="bg-blue-500 text-white px-4 py-2 rounded">Add User</button>
            </form>
        </div>
    </section>

    <footer class="bg-gray-800 text-white text-center p-4 mt-10">
        <p>&copy; 2025 Smart Step. All rights reserved.</p>
    </footer>
</body>
</html>
