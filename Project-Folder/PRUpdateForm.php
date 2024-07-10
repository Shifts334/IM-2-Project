<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'purchase_requests';
$user = $_SESSION['user'];

$pageTitle = 'Update Purchase Request';
include('partials/header.php');

$requestData = [];
if (isset($_GET['id'])) {
    include('database/connect.php');
    $stmt = $conn->prepare("SELECT * FROM purchase_requests WHERE PRID = :PRID");
    $stmt->execute(['PRID' => $_GET['id']]);
    $requestData = $stmt->fetch(PDO::FETCH_ASSOC);
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

<?php include('partials/footer.php'); ?>