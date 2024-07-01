<?php
    session_start();

    $table_name = $_SESSION['table'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $encrypted = password_hash($password, PASSWORD_DEFAULT);

    try{
        $command = "INSERT INTO 
                    $table_name(fname, lname, password, email, created_at, updated_at) 
                    VALUES 
                    (:fname, :lname, :encrypted, :email, NOW(), NOW())";

        include('connect.php');
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':encrypted', $encrypted);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $response = [
            'success'=> true,
            'message'=> $fname . ' ' . $lname .' successfully added to the system.'
        ];

    } catch(PDOException $e){
        $response = [
            'success'=> false,
            'message'=> $e->getMessage()
        ];
    }

    $_SESSION['response'] = $response;
    header('location: ../userAdd.php'); 
?>