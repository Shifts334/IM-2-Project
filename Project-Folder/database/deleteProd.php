<?php
session_start();
include('connect.php');

$product_id = (int) $_POST['product_id'];

try {
    // Delete product image
    $stmt = $conn->prepare("SELECT img FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && file_exists("../productImages/" . $product['img'])) {
        unlink("../productImages/" . $product['img']);
    }

    // Delete product from the database
    $command = "DELETE FROM products WHERE id = :id";
    $stmt = $conn->prepare($command);
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();

    $response = [
        'success' => true,
        'message' => 'Product successfully deleted from the system.'
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
?>