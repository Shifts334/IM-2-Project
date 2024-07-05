<?php
session_start();

$table_name = $_SESSION['table'];
$product_name = $_POST['prodName'];
$description = $_POST['description'];
$created_by = $_POST['created_by'];
$product_id = isset($_POST['id']) ? $_POST['id'] : null;
$img = $_FILES['img']['name'];
$target_dir = "../productImages/";
$target_file = $target_dir . basename($img);

try {
    include('connect.php');

    if ($product_id) {
        // Update existing product
        if (!empty($img)) {
            move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
            $command = "UPDATE $table_name SET product_name = :product_name, description = :description, img = :img, updated_at = NOW() WHERE id = :id";
            $stmt = $conn->prepare($command);
            $stmt->bindParam(':img', $img);
        } else {
            $command = "UPDATE $table_name SET product_name = :product_name, description = :description, updated_at = NOW() WHERE id = :id";
            $stmt = $conn->prepare($command);
        }
        $stmt->bindParam(':id', $product_id);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $product_name . ' successfully updated.'
        ];
    } else {
        // Insert new product
        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        $command = "INSERT INTO $table_name (product_name, description, created_by, created_at, img) VALUES (:product_name, :description, :created_by, NOW(), :img)";
        $stmt = $conn->prepare($command);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':created_by', $created_by);
        $stmt->bindParam(':img', $img);
        $stmt->execute();

        $response = [
            'success' => true,
            'message' => $product_name . ' successfully added to the system.'
        ];
    }
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../productAdd.php');
?>