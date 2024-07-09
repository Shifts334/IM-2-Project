<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'supplier';
$user = $_SESSION['user'];

$pageTitle = 'Update Supplier';
include('partials/header.php');

$supplierData = [];
if (isset($_GET['supplierID'])) {
    include('database/connect.php');
    $stmt = $conn->prepare("SELECT * FROM supplier WHERE supplierID = :supplierID");
    $stmt->execute(['supplierID' => $_GET['supplierID']]);
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
                            <input type="hidden" name="supplierID" id="supplierID" value="<?= $supplierData['supplierID'] ?? '' ?>">
                            <div class="addFormContainer mb-3">
                                <label for="companyName" class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="companyName" id="companyName" value="<?= $supplierData['companyName'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" id="address" value="<?= $supplierData['address'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="contactNum" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" name="contactNum" id="contactNum" value="<?= $supplierData['contactNum'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="supplierEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" name="supplierEmail" id="supplierEmail" value="<?= $supplierData['supplierEmail'] ?? '' ?>">
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