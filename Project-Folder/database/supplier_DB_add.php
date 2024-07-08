<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to add or update a supplier.'
    ];
    header('location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
$created_by = $user['id']; // Assuming 'id' is the key for the user ID in the session data

$table_name = 'suppliers'; // Directly using the suppliers table name
$supplier_name = $_POST['supplier_name'];
$supplier_location = $_POST['supplier_location'];
$email = $_POST['email'];
$supplier_id = isset($_POST['id']) ? $_POST['id'] : null;

try {
    include('connect.php');

    if ($supplier_id) {
        // Update existing supplier
        $command = "UPDATE $table_name SET supplier_name = :supplier_name, supplier_location = :supplier_location, email = :email, updated_at = NOW() WHERE id = :id";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':id', $supplier_id);
        $stmt->bindParam(':supplier_name', $supplier_name);
        $stmt->bindParam(':supplier_location', $supplier_location);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $supplier_name . ' successfully updated.'
        ];
    } else {
        // Insert new supplier
        $command = "INSERT INTO $table_name (supplier_name, supplier_location, email, created_by, created_at) VALUES (:supplier_name, :supplier_location, :email, :created_by, NOW())";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':supplier_name', $supplier_name);
        $stmt->bindParam(':supplier_location', $supplier_location);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':created_by', $created_by);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $supplier_name . ' successfully added to the system.'
        ];
    }
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../supplierAdd.php');
?>