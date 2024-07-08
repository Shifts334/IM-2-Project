<?php
session_start();

$table_name = $_SESSION['table'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];
$encrypted = password_hash($password, PASSWORD_DEFAULT);
$userId = isset($_POST['id']) ? $_POST['id'] : null;

try {
    include('connect.php');

    if ($userId) {
        // Update existing user
        if (!empty($password)) {
            $command = "UPDATE $table_name SET fname = :fname, lname = :lname, email = :email, password = :encrypted, updated_at = NOW() WHERE id = :id";
            $stmt = $conn->prepare($command);
            $stmt->bindParam(':encrypted', $encrypted);
        } else {
            $command = "UPDATE $table_name SET fname = :fname, lname = :lname, email = :email, updated_at = NOW() WHERE id = :id";
            $stmt = $conn->prepare($command);
        }
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $message = $fname . ' ' . $lname . ' successfully updated.';
    } else {
        // Insert new user
        $command = "INSERT INTO $table_name (fname, lname, password, email, created_at, updated_at) VALUES (:fname, :lname, :encrypted, :email, NOW(), NOW())";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':encrypted', $encrypted);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $message = $fname . ' ' . $lname . ' successfully added to the system.';
    }

    $_SESSION['success_message'] = $message;
    header('location: ../userAdd.php');
} catch (PDOException $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('location: ../userAdd.php');
}
