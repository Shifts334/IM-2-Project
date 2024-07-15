<?php
session_start();

$table_name = 'purchase_requests';
$table_assoc = 'pr_item';
$date_needed = $_POST['dateNeeded'];
$status = $_POST['PRStatus'];
$estimated_cost = $_POST['estimatedCost'];
$reason = $_POST['reason'];

$PR_date_requested = date('Y-m-d'); // Assuming the current date for PRDateRequested
$user = $_SESSION['user']; //user data
$requested_by = $user['userID']; // Assuming this is passed from the form

$PR_id = isset($_POST['PRID']) ? $_POST['PRID'] : null;

$itemid = $_POST['itemID'];
$reQuant = $_POST['requestQuantity'];
$estCost = $_POST['productEstimatedCost'];
try {
    include('connect.php');

    if ($PR_id) {
        // Update existing purchase request without changing 'requestedBy'
        $command = "UPDATE $table_name SET PRDateRequested = :PRDateRequested, dateNeeded = :dateNeeded, PRStatus = :PRStatus, estimatedCost = :estimatedCost, reason = :reason WHERE PRID = :PRID";
        $stmt = $conn->prepare($command); //check pr date requested
        $stmt->bindParam(':PRID', $PR_id);

        $commandItem = "UPDATE $table_assoc SET itemID = :item, requestQuantity = :req, estimatedCost = :est WHERE PRID = :PRID";
        $gftf = $conn->prepare($commandItem);
    } else {
        // Insert new purchase request
        $command = "INSERT INTO $table_name (requestedBy, PRDateRequested, dateNeeded, PRStatus, estimatedCost, reason) VALUES (:requestedBy, current_timestamp(), :dateNeeded, :PRStatus, :estimatedCost, :reason)";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':requestedBy', $requested_by); 

        $commandItem = "INSERT INTO $table_assoc (PRID, itemID, requestQuantity, estimatedCost) VALUES (:PRID, :item, :req, :est)";
        $gftf = $conn->prepare($commandItem);
        
    }

    $stmt->bindParam(':dateNeeded', $date_needed);
    $stmt->bindParam(':PRStatus', $status);
    $stmt->bindParam(':estimatedCost', $estimated_cost);
    $stmt->bindParam(':reason', $reason);
    $stmt->execute();
    
    $NEW = ($PR_id) ? $PR_id :$conn->lastInsertId(); //testing
    
    foreach ($itemid as $index => $itemId) {
        $gftf->execute([
            ':PRID' => $NEW,
            ':item' => $itemId,
            ':req' => $reQuant[$index],
            ':est' => $estCost[$index]
        ]);
    }
    $message = "Purchase request successfully " . ($PR_id ? "updated" : "added") . ".";
    $_SESSION['success_message'] = $message;
    header('location: ../PR.php');
} catch (PDOException $e) {
    // Handle any database errors
    $_SESSION['error_message'] = 'Error processing purchase request: ' . $e->getMessage();
    header('location: ../PR.php');
}

function getNewPR(){
    include('connect.php');
    
    $new = $conn->prepare("SELECT PRID FROM purchase_requests ORDER BY PRID DESC LIMIT 1");
    $new->execute();
    $new->setFetchMode(PDO::FETCH_ASSOC);

    return $new->fetchAll();
}
?>