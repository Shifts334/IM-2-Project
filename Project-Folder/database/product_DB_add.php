<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to add or update an item.'
    ];
    header('location: ../login.php');
    exit();
}

$table_name = 'item'; // Use the new table name
$table2 = 'item_changes';
$item_name = $_POST['itemName'];
$unit_of_measure = $_POST['unitOfMeasure'];
$item_type = $_POST['itemType'];
$quantity = $_POST['quantity'];
$min_stock_level = $_POST['minStockLevel'];
$item_status = $_POST['itemStatus'];
$old_quantity = isset($_POST['oldQuantity']) ? $_POST['oldQuantity'] : null;
$reason = isset($_POST['comment']) ? $_POST['comment'] : null;
$item_id = isset($_POST['itemID']) ? $_POST['itemID'] : null;

try {
    include('connect.php');

    if ($item_id) {
        // Update existing item
        $command = "UPDATE $table_name SET itemName = :item_name, unitOfMeasure = :unit_of_measure, itemType = :item_type, quantity = :quantity, minStockLevel = :min_stock_level, itemStatus = :item_status WHERE itemID = :item_id";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':unit_of_measure', $unit_of_measure);
        $stmt->bindParam(':item_type', $item_type);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':min_stock_level', $min_stock_level);
        $stmt->bindParam(':item_status', $item_status);
        $stmt->execute();
        
        $adjusted = $quantity - $old_quantity;
        $command = "INSERT INTO $table2 (dateModified, itemID, comments,  oldQuantity, adjustedQuantity, newQuantity) VALUES (current_timestamp(), :item_id, :comment, :old_quantity, :adjusted, :quantity)";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':comment', $reason);
        $stmt->bindParam(':old_quantity', $old_quantity);
        $stmt->bindParam(':adjusted', $adjusted);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $item_name . ' successfully updated.'
        ];
    } else {
        // Insert new item
        $command = "INSERT INTO $table_name (itemName, unitOfMeasure, itemType, quantity, minStockLevel, itemStatus) VALUES (:item_name, :unit_of_measure, :item_type, :quantity, :min_stock_level, :item_status)";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':unit_of_measure', $unit_of_measure);
        $stmt->bindParam(':item_type', $item_type);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':min_stock_level', $min_stock_level);
        $stmt->bindParam(':item_status', $item_status);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $item_name . ' successfully added to the system.'
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