<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to add or update a product.'
    ];
    header('location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
$created_by = $user['id']; // Assuming 'id' is the key for the user ID in the session data

$table_name = $_SESSION['table'];
$product_name = $_POST['prodName'];
$description = $_POST['description'];
$product_id = isset($_POST['id']) ? $_POST['id'] : null;

try {
    include('connect.php');

    if ($product_id) {
        // Update existing product
        $command = "UPDATE $table_name SET product_name = :product_name, description = :description, updated_at = NOW() WHERE id = :id";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':id', $product_id);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $product_name . ' successfully updated.'
        ];
    } else {
        // Insert new product
        $command = "INSERT INTO $table_name (product_name, description, created_by, created_at) VALUES (:product_name, :description, :created_by, NOW())";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':created_by', $created_by);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $product_name . ' successfully added to the system.'
        ];
    }
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../productAdd.php');
?>