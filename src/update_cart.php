<?php
session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cart_id = (int)$_POST['cart_id'];
$quantity = (int)$_POST['quantity'];

$stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("iii", $quantity, $cart_id, $_SESSION['user_id']);
$stmt->execute();

echo "<script>window.location='cart.php';</script>";
?>
