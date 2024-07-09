<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to add or update a user.'
    ];
    header('location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
$created_by = $user['userID']; // Assuming 'userID' is the key for the user ID in the session data

$table_name = 'users'; // Directly using the users table name
$user_id = isset($_POST['userID']) ? $_POST['userID'] : null;
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$department = $_POST['department'];
$permissions = $_POST['permissions'];
$email = $_POST['email'];
$password = $_POST['password'];
$work_status = $_POST['workStatus'];

try {
    include('connect.php');

    if ($user_id) {
        // Update existing user
        if (!empty($password)) {
            $command = "UPDATE $table_name SET fname = :fname, lname = :lname, department = :department, permissions = :permissions, email = :email, password = :password, workStatus = :workStatus WHERE userID = :userID";
            $stmt = $conn->prepare($command);
            $stmt->bindParam(':password', $password);
        } else {
            $command = "UPDATE $table_name SET fname = :fname, lname = :lname, department = :department, permissions = :permissions, email = :email, workStatus = :workStatus WHERE userID = :userID";
            $stmt = $conn->prepare($command);
        }
        $stmt->bindParam(':userID', $user_id, PDO::PARAM_INT);
    } else {
        // Insert new user
        $command = "INSERT INTO $table_name (fname, lname, department, permissions, email, password, workStatus) VALUES (:fname, :lname, :department, :permissions, :email, :password, :workStatus)";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':password', $password);
    }

    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':permissions', $permissions);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':workStatus', $work_status);
    
    $stmt->execute();

    $response = [
        'success' => true,
        'message' => ($user_id ? 'User successfully updated.' : 'User successfully added to the system.')
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../userAdd.php');
?>