<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$cart_items = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");

if ($cart_items->num_rows > 0) {
    while ($item = $cart_items->fetch_assoc()) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        // $size = $item['size'];
        $total_price = $item['discount_price'] * $quantity;
        $created_at = date("Y-m-d H:i:s");
        $status = "Pending";

        // Check if stock is sufficient
        $product_result = $conn->query("SELECT quantity, name FROM products WHERE id = $product_id");
        $product = $product_result->fetch_assoc();

        if ($product['quantity'] < $quantity) {
            echo "<script>alert('Insufficient stock for {$product['name']}. Available: {$product['quantity']}'); window.location='cart.php';</script>";
            exit();
        }

        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, size, quantity, total_price, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisidss", $user_id, $product_id, $size, $quantity, $total_price, $status, $created_at);
        $stmt->execute();

        // Reduce stock in products
        $conn->query("UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id");
    }

    // Clear the cart after order is placed
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    echo "<script>alert('Checkout successful! Your order has been placed.'); window.location='products.php';</script>";
} else {
    echo "<script>alert('Your cart is empty.'); window.location='products.php';</script>";
}

$conn->close();
?>
