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

include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getSuppliers') {
    if (!isset($_GET['itemID'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Item ID is required']);
        exit();
    }

    $itemID = $_GET['itemID'];

    try {
        $stmt = $conn->prepare("SELECT supplier.companyName, supplier.status, item_costs.cost 
                                FROM item_costs 
                                JOIN supplier ON item_costs.supplierID = supplier.supplierID 
                                WHERE item_costs.itemID = :itemID 
                                ORDER BY item_costs.cost ASC");
        $stmt->bindParam(':itemID', $itemID, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($results);
        exit();
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

$table_name = 'item';
$item_name = $_POST['itemName'];
$unit_of_measure = $_POST['unitOfMeasure'];
$item_type = $_POST['itemType'];
$quantity = $_POST['quantity'];
$min_stock_level = $_POST['minStockLevel'];
$item_status = $_POST['itemStatus'];
$item_id = isset($_POST['itemID']) ? $_POST['itemID'] : null;

try {

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