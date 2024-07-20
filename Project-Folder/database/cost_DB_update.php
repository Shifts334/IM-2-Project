<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to add or update an item cost.'
    ];
    header('location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
$table_name = 'item_costs';

// Validate and sanitize input
$item_cost = filter_input(INPUT_POST, 'itemCost', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$item_id = filter_input(INPUT_POST, 'itemID', FILTER_VALIDATE_INT);
$supplier_name = filter_input(INPUT_POST, 'supplierName', FILTER_SANITIZE_STRING);

if (!$item_id || !$item_cost || !$supplier_name) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Invalid input. Please check your entries and try again.'
    ];
    header('location: ../itemCostUpdateForm.php');
    exit();
}

try {
    include('connect.php');

    // Retrieve the supplierID based on the supplierName
    $stmt = $conn->prepare("
        SELECT supplierID 
        FROM supplier 
        WHERE companyName = :supplierName
    ");
    $stmt->bindParam(':supplierName', $supplier_name, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $_SESSION['response'] = [
            'success' => false,
            'message' => 'Supplier not found for the given name.'
        ];
        header('location: ../itemCostUpdateForm.php');
        exit();
    }

    $supplier_id = $result['supplierID'];

    // Update the cost for the given itemID and supplierID
    $command = "
        UPDATE $table_name 
        SET cost = :cost 
        WHERE itemID = :itemID AND supplierID = :supplierID
    ";

    $stmt = $conn->prepare($command);
    $stmt->bindParam(':itemID', $item_id, PDO::PARAM_INT);
    $stmt->bindParam(':supplierID', $supplier_id, PDO::PARAM_INT);
    $stmt->bindParam(':cost', $item_cost);
    $stmt->execute();

    $response = [
        'success' => true,
        'message' => 'Item cost successfully updated.'
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../productAdd.php');
exit();
