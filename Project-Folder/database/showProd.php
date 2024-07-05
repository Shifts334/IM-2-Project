<?php
    include('connect.php');


    $stmt = $conn->prepare("SELECT * FROM products ORDER BY id ASC");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt->fetchAll();
?>