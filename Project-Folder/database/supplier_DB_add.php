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

$table_name = 'supplier'; // Directly using the supplier table name
$company_name = $_POST['companyName'];
$address = $_POST['address'];
$contact_num = $_POST['contactNum'];
$supplier_email = $_POST['supplierEmail'];
$supplier_id = isset($_POST['supplierID']) ? $_POST['supplierID'] : null;

try {
    include('connect.php');

    if ($supplier_id) {
        // Update existing supplier
        $command = "UPDATE $table_name SET companyName = :companyName, address = :address, contactNum = :contactNum, supplierEmail = :supplierEmail WHERE supplierID = :supplierID";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':supplierID', $supplier_id, PDO::PARAM_INT);
        $stmt->bindParam(':companyName', $company_name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':contactNum', $contact_num);
        $stmt->bindParam(':supplierEmail', $supplier_email);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $company_name . ' successfully updated.'
        ];
    } else {
        // Insert new supplier
        $command = "INSERT INTO $table_name (companyName, address, contactNum, supplierEmail) VALUES (:companyName, :address, :contactNum, :supplierEmail)";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':companyName', $company_name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':contactNum', $contact_num);
        $stmt->bindParam(':supplierEmail', $supplier_email);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $company_name . ' successfully added to the system.'
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