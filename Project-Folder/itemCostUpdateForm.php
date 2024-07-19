<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$user = $_SESSION['user'];
$_SESSION['table'] = 'item_costs';

if (isset($_GET['itemID'])) {
    $_SESSION['itemID'] = $_GET['itemID'];
}

$pageTitle = 'Update Cost';
include('partials/header.php');

$itemID = $_GET['itemID'];
$itemName = $_GET['itemName'];
$supplierName = $_GET['supplierName'] ?? ''; // Get the supplierName from the URL if present
$currentPrice = $_GET['currentPrice'] ?? ''; // Get the current price from the URL if present

?>

<div id="dashboardMainContainer">
    <?php include('partials/sideBar.php') ?>

    <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include('partials/topNavBar.php') ?>

        <div class="dashboard_content d-flex justify-content-center">
            <div class="container mt-3">
                <div class="card m-5">
                    <div class="card-header p-3 bg-white">
                        <h2 class="card-title m-2">Edit cost for <?= htmlspecialchars($supplierName) ?></h2>
                        <span class="d-flex align-items-center">
                            <div class="vr mx-2"></div>
                            <span id="itemName" class="text-muted">Item: <span id="itemName"><?= htmlspecialchars($itemName) ?></span></span>
                        </span>
                    </div>
                    <div class="card-body p-5" style="max-height: calc(100vh - 300px); overflow-y: auto;">
                        <form action="database/cost_DB_update.php" method="POST" class="AddForm">

                            <input type="hidden" name="itemID" value="<?= htmlspecialchars($itemID); ?>">
                            <input type="hidden" name="supplierName" value="<?= htmlspecialchars($supplierName); ?>">

                            <div class="addFormContainer mb-3">
                                <div class="form-label">Supplier</div>
                                <span class="form-control"><?= htmlspecialchars($supplierName) ?></span>
                            </div>

                            <div class="addFormContainer mb-3">
                                <label for="itemCost" class="form-label">Item Cost</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" min="0" max="99999.99" class="form-control" name="itemCost" id="itemCost" value="<?= htmlspecialchars($currentPrice) ?>" required>
                                </div>

                                <small class="text-muted my-1">Current Cost: ₱<?= htmlspecialchars($currentPrice) ?></small>
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

<?php include('partials/footer.php'); ?>