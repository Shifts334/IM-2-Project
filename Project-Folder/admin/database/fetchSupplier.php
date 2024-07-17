<?php
// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to perform this action.'
    ];
    header('location: ../login.php');
    exit();
}

include('connect.php');

function getSuppliers($conn)
{
    try {
        $stmt = $conn->prepare("SELECT supplierID, companyName FROM supplier");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    }
}

// Get suppliers and handle the response for AJAX calls
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getSuppliers') {
    $suppliers = getSuppliers($conn);
    if (isset($suppliers['error'])) {
        http_response_code(500);
        echo json_encode(['error' => $suppliers['error']]);
    } else {
        echo json_encode($suppliers);
    }
    exit();
}

// Fetch suppliers for the form
$suppliers = getSuppliers($conn);
if (isset($suppliers['error'])) {
    echo "<p>Error fetching suppliers: " . $suppliers['error'] . "</p>";
    $suppliers = [];
}
