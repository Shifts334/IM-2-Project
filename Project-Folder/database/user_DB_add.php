<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'] ?? null;
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $department = $_POST['department'];
    $permissions = $_POST['permissions'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $workStatus = $_POST['workStatus'];

    if ($userID) {
        // Update existing user
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $command = "UPDATE users SET fname = :fname, lname = :lname, department = :department, permissions = :permissions, email = :email, password = :password, workStatus = :workStatus WHERE userID = :userID";
            $stmt = $conn->prepare($command);
            $stmt->bindParam(':password', $hashed_password);
        } else {
            $command = "UPDATE users SET fname = :fname, lname = :lname, department = :department, permissions = :permissions, email = :email, workStatus = :workStatus WHERE userID = :userID";
            $stmt = $conn->prepare($command);
        }
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    } else {
        // Insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $command = "INSERT INTO users (fname, lname, department, permissions, email, password, workStatus) VALUES (:fname, :lname, :department, :permissions, :email, :password, :workStatus)";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':password', $hashed_password);
    }

    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':permissions', $permissions);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':workStatus', $workStatus);

    $stmt->execute();
}

$_SESSION['response'] = $response;
header('location: ../userAdd.php');
?>