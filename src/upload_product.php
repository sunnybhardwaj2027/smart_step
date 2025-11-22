<?php
$conn = new mysqli("localhost", "root", "", "smart_step_db");

$edit = false;
$product = [
    'name' => '',
    'description' => '',
    'price' => '',
    'discount_price' => '',
    'rating' => '',
    'reviews' => '',
    'quantity' => '',
    'size' => '',
    'image' => ''
];

if (isset($_GET['edit'])) {
    $edit = true;
    $edit_id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM products WHERE id = $edit_id");
    if ($res->num_rows === 1) {
        $product = $res->fetch_assoc();
    } else {
        echo "<script>alert('Product not found'); window.location='upload_product.php';</script>";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount_price = $_POST['discount_price'];
    $rating = $_POST['rating'];
    $reviews = $_POST['reviews'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;

    $imagePath = $product['image'];
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $imagePath = $target_file;
        } else {
            echo "<p class='text-red-600 mt-4'>Error uploading image.</p>";
        }
    }

    if ($edit_id) {
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, discount_price=?, rating=?, reviews=?, image=?, quantity=?, size=? WHERE id=?");
        $stmt->bind_param("ssdddssssi", $name, $description, $price, $discount_price, $rating, $reviews, $imagePath, $quantity, $size, $edit_id);
        $stmt->execute();
        echo "<script>alert('Product updated successfully!'); window.location='upload_product.php';</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, discount_price, rating, reviews, image, quantity, size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdddssds", $name, $description, $price, $discount_price, $rating, $reviews, $imagePath, $quantity, $size);
        $stmt->execute();
        echo "<p class='text-green-600 mt-4'>Product uploaded successfully!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $edit ? 'Update' : 'Upload' ?> Product - Smart Step</title>
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
<body class="bg-gray-100 min-h-screen flex flex-col items-center">
    <nav class="bg-blue-600 p-4 text-white w-full">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center">
            <h1 class="text-2xl font-bold">Smart Step - Admin Dashboard</h1>
            <ul class="flex flex-wrap gap-4 mt-2 sm:mt-0">
                <li><a href="admin.php" >Dashboard</a></li>
                <li><a href="index.php" >Home</a></li>
                <li><a href="order.php" >Order</a></li>
                <li><a href="inventory.php" >Inventory</a></li>
                <!-- <li><a href="upload_product.php" class="hover:underline">Upload Shoes</a></li> -->
                <li><a href="update_quantity.php" >Update Shoes</a></li>
                <li><a href="logout.php" >Logout</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mt-10">

        <h1 class="text-2xl font-bold mb-6"><?= $edit ? 'Update' : 'Upload' ?> Product</h1>
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php if ($edit): ?>
                <input type="hidden" name="edit_id" value="<?= $product['id']; ?>" />
            <?php endif; ?>
            <input type="text" name="name" placeholder="Product Name" value="<?= $product['name']; ?>" class="w-full p-2 border border-gray-300 rounded-lg" required />
            <input type="text" name="description" placeholder="Description" value="<?= $product['description']; ?>" class="w-full p-2 border border-gray-300 rounded-lg" required />
            <input type="number" step="0.01" name="price" placeholder="Price (MRP)" value="<?= $product['price']; ?>" class="w-full p-2 border border-gray-300 rounded-lg" required />
            <input type="number" step="0.01" name="discount_price" placeholder="price after discount" value="<?= $product['discount_price']; ?>" class="w-full p-2 border border-gray-300 rounded-lg" required />
            <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" value="<?= $product['rating']; ?>" class="w-full p-2 border border-gray-300 rounded-lg" required />
            <input type="number" name="reviews" placeholder="Number of Reviews" value="<?= $product['reviews']; ?>" class="w-full p-2 border border-gray-300 rounded-lg" required />
            <input type="number" name="quantity" placeholder="Stock Quantity" value="<?= $product['quantity']; ?>" class="w-full p-2 border border-gray-300 rounded-lg" required />
            <input type="text" name="size" placeholder="Size (e.g., 7, 8, 9)" value="<?= $product['size']; ?>" class="w-full p-2 border border-gray-300 rounded-lg" required />

            <input type="file" name="image" class="w-full p-2 border border-gray-300 rounded-lg" <?= $edit ? '' : 'required' ?> />
            <?php if ($edit && $product['image']): ?>
                <img src="<?= $product['image']; ?>" class="w-32 h-32 object-cover rounded mt-2" />
            <?php endif; ?>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700"><?= $edit ? 'Update' : 'Upload' ?> Product</button>
        </form>
    </div>
    
</body>
</html>
