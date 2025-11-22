<?php
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $stmt = $conn->prepare("UPDATE orders SET status = 'Completed' WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        echo "<script>alert('Order marked as completed.'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Failed to update order.'); window.location='admin.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>



