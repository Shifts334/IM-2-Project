<?php
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'item'; // Use the new table name
$user = $_SESSION['user'];
$products = include('database/showProd.php');

$pageTitle = 'Product Management';
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
                        <h2 class="card-title m-2"><i class="fa fa-list"></i> List of Products</h2>
                        <a href="productAddForm.php" class="btn btn-primary m-2">
                            Add New Product
                        </a>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive flex-grow-1" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <table class="table table-hover table-striped border-top">
                                <thead class="bg-white">
                                    <tr class="userAdd sticky-top">
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Unit of Measure</th>
                                        <th>Item Type</th>
                                        <th>Quantity</th>
                                        <th>Min Stock Level</th>
                                        <th>Item Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 0;
                                    foreach ($products as $product) { ?>
                                        <tr>
                                            <td class="pt-3"><?= ++$index ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['itemName']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['unitOfMeasure']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['itemType']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['quantity']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['minStockLevel']) ?></td>
                                            <td class="pt-3"><?= htmlspecialchars($product['itemStatus']) ?></td>
                                            <td class="text-center">
                                                <a href="productUpdateForm.php?itemID=<?= $product['itemID'] ?>" class="btn btn-sm btn-outline-primary m-1">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger deleteProduct m-1" data-product-id="<?= $product['itemID'] ?>" data-product-name="<?= htmlspecialchars($product['itemName']) ?>">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted mt-0 mx-3"><?= count($products) ?> Products</p>
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
            if (e.target.closest('.deleteProduct')) {
                e.preventDefault();
                const deleteButton = e.target.closest('.deleteProduct');
                const productId = deleteButton.dataset.productId;
                const productName = deleteButton.dataset.productName;

                if (confirm(`Are you sure you want to delete ${productName}?`)) {
                    fetch('database/deleteProd.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                itemID: productId
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