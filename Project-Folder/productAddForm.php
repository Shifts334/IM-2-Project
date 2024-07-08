<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'products';
$user = $_SESSION['user'];

$pageTitle = 'Add Product';
include('partials/header.php');
?>

<div id="dashboardMainContainer">
    <?php include('partials/sideBar.php') ?>

    <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include('partials/topNavBar.php') ?>

        <div class="dashboard_content d-flex justify-content-center">
            <div class="container">
                <div class="card m-5">
                    <div class="card-header p-3 bg-white">
                        <h2 class="card-title m-2">Add Product</h2>
                    </div>
                    <div class="card-body p-5">
                        <form action="database/product_DB_add.php" method="POST" class="AddForm">
                            <input type="hidden" name="id" id="product_id">
                            <div class="addFormContainer mb-3">
                                <label for="prodName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="prodName" id="prodName">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="d-flex flex-row-reverse flex-wrap">
                                <button type="submit" class="btn btn-primary mx-1 mt-4">Submit</button>
                                <a href="productAdd.php" class="btn btn-secondary mx-1 mt-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../partials/footer.php'); ?>