<?php
session_start();
include('connect.php');

$data = $_POST;
$user_id = (int) $data['user_id'];

try {
    $command = "DELETE FROM users WHERE id={$user_id}";
    $conn->exec($command);

    $response = [
        'success' => true,
        'message' => 'User successfully deleted from the system.'
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
?>