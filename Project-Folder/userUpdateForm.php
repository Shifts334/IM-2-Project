<?php
// session start
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'users';
$user = $_SESSION['user'];

$pageTitle = 'Update User';
include('partials/header.php');

$userData = [];
if (isset($_GET['id'])) {
    include('database/connect.php');
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
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
                        <h2 class="card-title m-2">Update User</h2>
                    </div>
                    <div class="card-body p-5">
                        <form action="database/user_DB_add.php" method="POST" class="AddForm">
                            <input type="hidden" name="id" id="user_id" value="<?= $userData['id'] ?? '' ?>">
                            <div class="addFormContainer mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="fname" id="fname" value="<?= $userData['fname'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lname" id="lname" value="<?= $userData['lname'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" id="email" value="<?= $userData['email'] ?? '' ?>">
                            </div>
                            <div class="addFormContainer mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>
                            <div class="d-flex flex-row-reverse flex-wrap">
                                <button type="submit" class="btn btn-primary mx-1 mt-4">Submit</button>
                                <a href="userAdd.php" class="btn btn-secondary mx-1 mt-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function script() {
        this.initialize = function() {
            this.registerEvents();
        };

        this.registerEvents = function() {
            document.addEventListener('click', function(e) {
                let targetElement = e.target;
                let classList = targetElement.classList;

                if (classList.contains('updateUser')) {
                    e.preventDefault();
                    let userId = targetElement.dataset.userId;
                    let fname = targetElement.dataset.fname;
                    let lname = targetElement.dataset.lname;
                    let email = targetElement.dataset.email;

                    document.getElementById('user_id').value = userId;
                    document.getElementById('fname').value = fname;
                    document.getElementById('lname').value = lname;
                    document.getElementById('email').value = email;
                    document.getElementById('password').value = ''; // Clear password field
                }
            });
        };

        this.initialize();
    }

    new script();
</script>

<?php include('partials/footer.php'); ?>