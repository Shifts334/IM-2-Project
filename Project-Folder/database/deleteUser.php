<?php
session_start();
include('connect.php');

$data = json_decode(file_get_contents('php://input'), true);
$user_id = isset($data['user_id']) ? (int) $data['user_id'] : 0;

try {
    if ($user_id > 0) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => 'User successfully deleted from the system.'
        ];
    } else {
        throw new Exception('Invalid user ID.');
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
