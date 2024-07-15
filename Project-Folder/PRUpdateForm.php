<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'purchase_requests';
$user = $_SESSION['user'];

$pageTitle = 'Update Purchase Request';
include('partials/header.php');

$requestData = [];
$items = fetchItem();
if (isset($_GET['id'])) {
    include('database/connect.php');
    $stmt = $conn->prepare("SELECT * FROM purchase_requests WHERE PRID = :PRID");
    $stmt->execute(['PRID' => $_GET['id']]);
    
    $requestData = $stmt->fetch(PDO::FETCH_ASSOC);

     // getting items with id
    $stmt = $conn->prepare("SELECT * FROM pr_item WHERE PRID = :PRID");
    $stmt->execute(['PRID' => $_GET['id']]);
    $itemData = $stmt->fetch(PDO::FETCH_ASSOC);
   
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
                        <h2 class="card-title my-2 mx-4">Update Purchase Request</h2>
                    </div>
                    <div class="card-body p-5">
                        <form action="database/PR_DB_add.php" method="POST" class="AddForm">
                            <input type="hidden" name="PRID" id="request_id" value="<?= $requestData['PRID'] ?? '' ?>">
                            <div class="addFormContainer mb-3">
                                <label for="date_needed" class="form-label">Date Needed</label>
                                <input type="date" class="form-control" name="dateNeeded" id="date_needed" value="<?= $requestData['dateNeeded'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="estimated_cost" class="form-label">Estimated Cost</label>
                                <input type="text" class="form-control" name="estimatedCost" id="estimated_cost" value="<?= $requestData['estimatedCost'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="reason" class="form-label">Reason</label>
                                <input type="text" class="form-control" name="reason" id="reason" value="<?= $requestData['reason'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="PRStatus" class="form-label">Status</label>
                                <select class="form-control" name="PRStatus" id="PRStatus">
                                    <option value="pending" <?= isset($requestData['PRStatus']) && $requestData['PRStatus'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= isset($requestData['PRStatus']) && $requestData['PRStatus'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="converted" <?= isset($requestData['PRStatus']) && $requestData['PRStatus'] == 'converted' ? 'selected' : '' ?>>Converted</option>
                                </select>
                            </div>
                            <!-- loop adding the items here -->
                             <?php 
                                foreach ($itemData as $itemDatum) { ?>
                            <div class="productInput mb-2 d-flex">
                                <select class="form-control"  name="itemID[]" id="itemTemplate"  placeholder="Item">
                                <?php // needs fixing
                                    foreach ($items as $item) { ?>
                                    <option value="<?= htmlspecialchars($item['itemID'])?>"><?= htmlspecialchars($item['itemName']) ?></option>
                                <?php } ?>
                                </select>
                                <input type="number" class="form-control" name="requestQuantity[]" placeholder="Quantity" value="<?= $itemDatum['requestQuantity'] ?>">
                                <input type="text" class="form-control mx-2" name="productEstimatedCost[]" placeholder="Estimated Cost" step=".01" value="<?= $itemDatum['estimatedCost'] ?>">
                                <button type="button" class="btn btn-danger btn-sm removeProduct mx-2">Remove</button>
                            </div>
                             <?php } ?>
                            <!-- end of loop -->
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