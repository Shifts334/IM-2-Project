<?php
session_start();

$table_name = 'purchase_requests';
$date_needed = $_POST['dateNeeded'];
$status = $_POST['PRStatus'];
$estimated_cost = $_POST['estimatedCost'];
$reason = $_POST['reason'];
$requested_by = $_POST['requestedBy']; // Assuming this is passed from the form
$PR_date_requested = date('Y-m-d'); // Assuming the current date for PRDateRequested

$PR_id = isset($_POST['PRID']) ? $_POST['PRID'] : null;

try {
    include('connect.php');

    if ($PR_id) {
        // Update existing purchase request without changing 'requestedBy'
        $command = "UPDATE $table_name SET PRDateRequested = :PRDateRequested, dateNeeded = :dateNeeded, PRStatus = :PRStatus, estimatedCost = :estimatedCost, reason = :reason WHERE PRID = :PRID";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':PRID', $PR_id);
    } else {
        // Insert new purchase request
        $command = "INSERT INTO $table_name (requestedBy, PRDateRequested, dateNeeded, PRStatus, estimatedCost, reason) VALUES (:requestedBy, :PRDateRequested, :dateNeeded, :PRStatus, :estimatedCost, :reason)";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':requestedBy', $requested_by);
    }

    $stmt->bindParam(':PRDateRequested', $PR_date_requested);
    $stmt->bindParam(':dateNeeded', $date_needed);
    $stmt->bindParam(':PRStatus', $status);
    $stmt->bindParam(':estimatedCost', $estimated_cost);
    $stmt->bindParam(':reason', $reason);
    $stmt->execute();

    $message = "Purchase request successfully " . ($PR_id ? "updated" : "added") . ".";
    $_SESSION['success_message'] = $message;
    header('location: ../PR.php');
} catch (PDOException $e) {
    // Handle any database errors
    $_SESSION['error_message'] = 'Error processing purchase request: ' . $e->getMessage();
    header('location: ../PR.php');
}
?>