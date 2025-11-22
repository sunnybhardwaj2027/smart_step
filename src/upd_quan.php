<?php
$conn = new mysqli("localhost", "root", "", "smart_step_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    $stmt = $conn->prepare("UPDATE products SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_quantity, $product_id);
    $stmt->execute();

    echo "<script>alert('Product quantity updated successfully!'); window.location='admin.php';</script>";
} else {
    echo "<script>alert('Invalid request'); window.location='admin-dashboard.php';</script>";
}
?>
