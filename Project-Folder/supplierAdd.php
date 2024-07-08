<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'suppliers';
$user = $_SESSION['user'];
$suppliers = include('database/showSupp.php');

$pageTitle = 'Supplier Management';
include('partials/header.php');
?>

<div id="dashboardMainContainer">
    <?php include('partials/sideBar.php') ?>

    <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include('partials/topNavBar.php') ?>

        <div class="dashboard_content d-flex justify-content-center">
            <div class="container m-0 p-0 mw-100">
                <div class="card h-100 border-0">
                    <div class="card-header p-3 bg-white d-flex justify-content-between">
                        <h2 class="card-title m-2"><i class="fa fa-list"></i> List of Suppliers</h2>
                        <a href="supplierAddForm.php" class="btn btn-primary m-2">
                            Add New Supplier
                        </a>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive flex-grow-1" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <table class="table table-hover table-striped border-top">
                                <thead class="bg-white">
                                    <tr class="userAdd sticky-top">
                                        <th>#</th>
                                        <th>Supplier Name</th>
                                        <th>Location</th>
                                        <th>Email</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 0;
                                    foreach ($suppliers as $supplier) { ?>
                                        <tr>
                                            <td class="pt-3"><?= ++$index ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($supplier['supplier_name']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($supplier['supplier_location']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($supplier['email']) ?></td>
                                            <td class="pt-3"><?= date('M d, Y @ h:i:s A', strtotime($supplier['created_at'])) ?></td>
                                            <td class="pt-3"><?= date('M d, Y @ h:i:s A', strtotime($supplier['updated_at'])) ?></td>
                                            <td class="text-center">
                                                <a href="supplierUpdateForm.php?id=<?= $supplier['id'] ?>" class="btn btn-sm btn-outline-primary m-1">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger deleteSupplier m-1" data-supplier-id="<?= $supplier['id'] ?>" data-supplier-name="<?= htmlspecialchars($supplier['supplier_name']) ?>">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted mt-0 mx-3"><?= count($suppliers) ?> Suppliers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "alert('" . addslashes($_SESSION['success_message']) . "');";
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo "alert('Error: " . addslashes($_SESSION['error_message']) . "');";
            unset($_SESSION['error_message']);
        }
        ?>
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.deleteSupplier')) {
                e.preventDefault();
                const deleteButton = e.target.closest('.deleteSupplier');
                const supplierId = deleteButton.dataset.supplierId;
                const supplierName = deleteButton.dataset.supplierName;

                if (confirm(`Are you sure you want to delete ${supplierName}?`)) {
                    fetch('database/deleteSupp.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                supplier_id: supplierId
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            if (data.success) {
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                }
            }
        });
    });
</script>

<?php include('partials/footer.php'); ?>