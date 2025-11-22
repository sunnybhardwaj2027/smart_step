<?php

session_start();
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $cart_id = intval($_GET['id']);
    $user_id = intval($_SESSION['user_id']);

    // Validate if the cart item exists
    $check_stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
    $check_stmt->bind_param("ii", $cart_id, $user_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows === 0) {
        $check_stmt->close();
        header("Location: cart.php?error=Invalid cart item");
        exit();
    }
    $check_stmt->close();

    // Delete the item from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);

    if ($stmt->execute()) {
        header("Location: cart.php?message=Item removed");
    } else {
        error_log("Error removing item: " . $stmt->error);
        header("Location: cart.php?error=Failed to remove item");
    }

    $stmt->close();
} else {
    header("Location: cart.php?error=Invalid request");
}

$conn->close();
?>