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

$item_id = isset($_GET['itemID']) ? (int) $_GET['itemID'] : 0;
$company_name = isset($_GET['companyName']) ? $_GET['companyName'] : '';

if ($item_id > 0 && !empty($company_name)) {
    try {
        $stmt = $conn->prepare("
            SELECT item_costs.supplierID 
            FROM item_costs 
            JOIN supplier ON item_costs.supplierID = supplier.supplierID 
            WHERE item_costs.itemID = :itemID AND supplier.companyName = :companyName
        ");
        $stmt->bindParam(':itemID', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':companyName', $company_name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode([
                'success' => true,
                'supplierID' => $result['supplierID']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Supplier not found for the given item.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid item ID or company name.'
    ]);
}
