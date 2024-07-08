<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access!'
    ]);
    exit;
}

include('connect.php');

$data = json_decode(file_get_contents('php://input'), true);
$product_id = isset($data['product_id']) ? (int) $data['product_id'] : 0;

try {
    if ($product_id > 0) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => 'Product successfully deleted from the system.'
        ];
    } else {
        throw new Exception('Invalid product ID.');
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
?>