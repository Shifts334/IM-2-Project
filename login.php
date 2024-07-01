<?php
    session_start();
    if(isset($_SESSION['user'])) header('location: dashboard.php');

    $error_message = '';
    if($_POST){

        include('database/connect.php');
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = 'SELECT * FROM users WHERE users.email=:email';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $username);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt->fetch();
            
            if(password_verify($password, $user['password'])){
                $_SESSION['user'] = $user;
                header('Location: dashboard.php');
            } else {
                $error_message = "Please make sure that your credentials are correct.";
            }
        } else {
            $error_message = "Please make sure that your credentials are correct.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body id="loginBody">
    <?php if(!empty($error_message)) { ?>
        <div id="errorMessage">
            <strong>ERROR:</strong><p><?= $error_message ?></p>
        </div>
    <?php } ?>
    <div class="container">
        <div class="loginHeader">
            <h1>PALM GRASS HOTEL</h1>
            <p>Inventory Management System</p>
        </div>
        <div class="loginBody">
            <form action="login.php" method="POST">
                <div class="loginInputsContainer">
                    <label for="">Email</label>
                    <input placeholder="Email" name="username" type="text">
                </div>
                <div class="loginInputsContainer">
                    <label for="">Password</label>
                    <input placeholder="password" name="password" type="password">
                </div>
                <div class="loginButtonContainer">
                    <button>Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>