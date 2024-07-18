<?php
header('Content-Type: application/json');

// Adjust the path to connect.php based on its actual location
include('connect.php');  // Ensure this path is correct

if (isset($_GET['PRID'])) {
    $PRID = $_GET['PRID'];

    try {
        $stmt = $conn->prepare("SELECT item.itemName, pr_item.requestQuantity, pr_item.estimatedCost 
                                FROM pr_item 
                                JOIN item ON pr_item.itemID = item.itemID 
                                WHERE pr_item.PRID = :PRID
                                ORDER BY item.itemName ASC");
        $stmt->bindParam(':PRID', $PRID, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($products);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No PRID provided']);
}
