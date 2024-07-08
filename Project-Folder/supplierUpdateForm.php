<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'suppliers';
$user = $_SESSION['user'];

$pageTitle = 'Update Supplier';
include('partials/header.php');

$supplierData = [];
if (isset($_GET['id'])) {
    include('database/connect.php');
    $stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $supplierData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div id="dashboardMainContainer">
    <?php include('partials/sideBar.php') ?>

    <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include('partials/topNavBar.php') ?>

        <div class="dashboard_content d-flex justify-content-center">
            <div class="container">
                <div class="card m-5">
                    <div class="card-header p-3 bg-white">
                        <h2 class="card-title m-2">Update Supplier</h2>
                    </div>
                    <div class="card-body p-5">
                        <form action="database/supplier_DB_add.php" method="POST" class="AddForm">
                            <input type="hidden" name="id" id="supplier_id" value="<?= $supplierData['id'] ?? '' ?>">
                            <div class="addFormContainer mb-3">
                                <label for="supplier_name" class="form-label">Supplier Name</label>
                                <input type="text" class="form-control" name="supplier_name" id="supplier_name" value="<?= $supplierData['supplier_name'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="supplier_location" class="form-label">Supplier Location</label>
                                <input type="text" class="form-control" name="supplier_location" id="supplier_location" value="<?= $supplierData['supplier_location'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" id="email" value="<?= $supplierData['email'] ?? '' ?>">
                            </div>
                            <div class="d-flex flex-row-reverse flex-wrap">
                                <button type="submit" class="btn btn-primary mx-1 mt-4">Submit</button>
                                <a href="supplierAdd.php" class="btn btn-secondary mx-1 mt-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>