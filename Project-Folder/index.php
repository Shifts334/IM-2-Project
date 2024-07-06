<?php
session_start();
if (isset($_SESSION['user'])) header('location: dashboard.php');

$error_message = '';
if ($_POST) {

    include('database/connect.php');
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = 'SELECT * FROM users WHERE users.email=:email';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: dashboard.php');
        } else {
            $error_message = "Please make sure that your credentials are correct.";
        }
    } else {
        $error_message = "Please make sure that your credentials are correct.";
    }
}

$pageTitle = 'Login';
$bodyClass = 'login-page';
include('partials/header.php');
?>

<div class="overlay"></div>
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card login-card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="images/Palm_Grass_logo.png" alt="Palm Grass Hotel" class="img-fluid logo">
                        <hr class="divider"/>
                    </div>

                    <?php if (!empty($error_message)) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> <?= $error_message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <form action="index.php" method="POST">
                        <div class="mb-4">
                            <label for="username" class="form-label login">Email</label>
                            <input type="email" class="form-control" id="username" name="username" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label login">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg mt-5 mb-4">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>