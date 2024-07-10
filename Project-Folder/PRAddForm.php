<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'purchase_requests';
$user = $_SESSION['user'];

$pageTitle = 'Create Purchase Request';
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
                        <h2 class="card-title my-2 mx-4">Create Purchase Request</h2>
                    </div>
                    <div class="card-body p-5" style="max-height: calc(100vh - 300px); overflow-y: auto;">
                        <form action="database/PR_DB_add.php" method="POST" class="AddForm">
                            <div class="addFormContainer mb-3">
                                <label for="date_needed" class="form-label">Date Needed</label>
                                <input type="date" class="form-control" name="dateNeeded" id="date_needed">
                            </div>
                            <div class="addFormContainer mb-4">
                                <label for="estimated_cost" class="form-label">Estimated Cost</label>
                                <input type="text" class="form-control" name="estimatedCost" id="estimated_cost">
                            </div>
                            <div class="addFormContainer mb-4">
                                <label for="reason" class="form-label">Reason</label>
                                <input type="text" class="form-control" name="reason" id="reason">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="PRStatus" class="form-label">Status</label>
                                <select class="form-control" name="PRStatus" id="PRStatus">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="ordered">Ordered</option>
                                </select>
                            </div>
                            <div id="productContainer">
                                <div class="d-flex justify-content-between mb-3">
                                    <label for="product" class="form-label pt-3">Product/s</label>
                                    <button type="button" id="addProductButton" class="btn btn-primary mb-3">Add Product</button>
                                </div>
                                <div class="productInput mb-2 d-flex">
                                    <input type="text" class="form-control" name="itemID[]" placeholder="Item ID">
                                    <input type="text" class="form-control mx-2" name="supplierID[]" placeholder="Supplier ID">
                                    <input type="text" class="form-control" name="requestQuantity[]" placeholder="Quantity">
                                    <input type="text" class="form-control mx-2" name="productEstimatedCost[]" placeholder="Estimated Cost">
                                    <button type="button" class="btn btn-danger btn-sm removeProduct mx-2">Remove</button>
                                </div>
                            </div>
                            <div class="d-flex flex-row-reverse flex-wrap">
                                <button type="submit" class="btn btn-primary mx-1 mt-4">Submit</button>
                                <a href="PR.php" class="btn btn-secondary mx-1 mt-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productContainer = document.getElementById('productContainer');
        const addProductButton = document.getElementById('addProductButton');

        addProductButton.addEventListener('click', function() {
            const productInput = document.createElement('div');
            productInput.classList.add('productInput', 'mb-2', 'd-flex');
            productInput.innerHTML = `
                <input type="text" class="form-control" name="itemID[]" placeholder="Item ID">
                <input type="text" class="form-control mx-2" name="supplierID[]" placeholder="Supplier ID">
                <input type="text" class="form-control" name="requestQuantity[]" placeholder="Quantity">
                <input type="text" class="form-control mx-2" name="productEstimatedCost[]" placeholder="Estimated Cost">
                <button type="button" class="btn btn-danger btn-sm removeProduct mx-2">Remove</button>
            `;
            productContainer.appendChild(productInput);
        });

        productContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeProduct')) {
                e.target.parentElement.remove();
            }
        });
    });
</script>

<?php include('partials/footer.php'); ?>