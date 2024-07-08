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
$supplier_id = isset($data['supplier_id']) ? (int) $data['supplier_id'] : 0;

try {
    if ($supplier_id > 0) {
        $stmt = $conn->prepare("DELETE FROM suppliers WHERE id = :id");
        $stmt->bindParam(':id', $supplier_id, PDO::PARAM_INT);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => 'Supplier successfully deleted from the system.'
        ];
    } else {
        throw new Exception('Invalid supplier ID.');
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
?>