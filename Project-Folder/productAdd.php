<?php
// session start
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'products';
$user = $_SESSION['user'];
$products = include('database/showProd.php');

$pageTitle = 'Add Product';
include('partials/header.php');
?>

<div id="dashboardMainContainer">
    <?php include('partials/sideBar.php') ?>

    <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include('partials/topNavBar.php') ?>

        <div class="dashboard_content">
            <div class="row">
                <div class="column column-5">
                    <h2 class="profiles"><i class="fa fa-plus"></i> Add / Edit Product Information</h2>
                    <div class="dashboard_content_main">
                        <div id="productAddContainer">
                            <form action="database/product_DB_add.php" method="POST" enctype="multipart/form-data" class="AddForm">
                                <div class="addFormContainer">
                                    <label for="prodName" class="formInput">Product Name</label>
                                    <input type="text" class="formInput" name="prodName" id="prodName" placeholder="Enter Product Name.." required>
                                </div>
                                <div class="addFormContainer">
                                    <label for="description" class="formInput">Description</label><br>
                                    <textarea class="productTextArea" id="description" name="description" placeholder="Enter Product Description.." required></textarea>
                                </div>
                                <div class="addFormContainer">
                                    <label for="img" class="formInput">Product Image</label><br>
                                    <input type="file" class="formInput" name="img" id="img" accept="image/*" required>
                                </div>
                                <button type="submit" class="addBtn"><i class="fa fa-send"></i> Submit Form</button>
                            </form>
                            <?php
                            if (isset($_SESSION['response'])) {
                                $response_message = $_SESSION['response']['message'];
                                $is_success = $_SESSION['response']['success'];
                            ?>
                                <div class="responseMessage">
                                    <p class="responseMessage <?= $is_success ? 'responseMessage_success' : 'responseMessage_error' ?>">
                                        <?= $response_message ?>
                                    </p>
                                </div>
                            <?php unset($_SESSION['response']);
                            } ?>
                        </div>
                    </div>
                </div>

                <div class="column column-7">
                    <h2 class="profiles"><i class="fa fa-list"></i> List of Products</h2>
                    <div class="products">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($products as $product) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['id']) ?></td>
                                        <td class="prodName"><?= htmlspecialchars($product['product_name']) ?></td>
                                        <td><img src="productImages/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" width="50"></td>
                                        <td class="description"><?= htmlspecialchars($product['description']) ?></td>
                                        <td><?= date('M d, Y @ h:i:s A', strtotime($product['created_at'])) ?></td>
                                        <td><?= date('M d, Y @ h:i:s A', strtotime($product['updated_at'])) ?></td>
                                        <td>
                                            <a href="#" class="updateProduct" data-product-id="<?= $product['id'] ?>" data-prodName="<?= htmlspecialchars($product['product_name']) ?>" data-description="<?= htmlspecialchars($product['description']) ?>" data-img="<?= htmlspecialchars($product['img']) ?>"><i class="fa fa-pencil"></i>Edit</a> <br>
                                            <a href="#" class="deleteProduct" data-product-id="<?= $product['id'] ?>" data-prodName="<?= htmlspecialchars($product['product_name']) ?>"><i class="fa fa-trash"></i>Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <p class="productCount"><?= count($products) ?> Products</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="javascript/script.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function script() {
    this.initialize = function() {
        this.registerEvents();
    };

    this.registerEvents = function() {
        document.addEventListener('click', function(e) {
            let targetElement = e.target;
            let classList = targetElement.classList;

            if (classList.contains('deleteProduct')) {
                e.preventDefault();
                let productId = targetElement.dataset.productId;
                let prodName = targetElement.dataset.prodName;

                if (window.confirm('Are you sure you want to delete ' + prodName + '?')) {
                    $.ajax({
                        method: 'POST',
                        data: {
                            product_id: productId
                        },
                        url: 'database/deleteProd.php',
                        dataType: 'json',
                        success: function(data) {
                            alert(data.message);
                            if (data.success) {
                                location.reload();
                            }
                        }
                    });
                } else {
                    console.log('Will not delete');
                }
            }

            if (classList.contains('updateProduct')) {
                e.preventDefault();
                let productId = targetElement.dataset.productId;
                let prodName = targetElement.dataset.prodName;
                let description = targetElement.dataset.description;
                let img = targetElement.dataset.img;

                document.getElementById('user_id').value = productId;
                document.getElementById('prodName').value = prodName;
                document.getElementById('description').value = description;
                document.getElementById('img').value = img;
            }
        });
    };

    this.initialize();
}

new script();
</script>
</body>
</html>