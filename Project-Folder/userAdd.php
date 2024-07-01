<?php
//session start
session_start();
if(!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'users'; 
$user = $_SESSION['user'];
$users = include('database/showUsers.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/userAdd.css">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <script src="https://example.com/fontawesome/v6.5.2/js/fontawesome.js" data-auto-replace-svg="nest"></script>
    <script src="https://example.com/fontawesome/v6.5.2/js/solid.js"></script>
    <script src="https://example.com/fontawesome/v6.5.2/js/brands.js"></script>
    <title>User Add</title>
</head>
<body>
    <div id="dashboardMainContainer">
        <!-- include Sidebar file -->
        <?php include('partials/sideBar.php') ?>

        <div class="dashboard_content_container" id="dashboard_content_container">
            <!-- include topNavigation file -->
            <?php include('partials/topNavBar.php') ?>

            <div class="dashboard_content">
                <div class="row">
                    <div class="column column-5">
                        <h2 class="profiles"><i class="fa fa-plus"></i>Add Profile</h2>
                        <div class="dashboard_content_main">
                            <!-- Add User Container -->
                            <div id="userAddContainer">
                                <form action="database/user_DB_add.php" method="POST" class="AddForm">
                                    <div class="addFormContainer">
                                        <label for="fname" class="formInput">First Name</label>
                                        <input type="text" class="formInput" name="fname" id="fname">
                                    </div>
                                    <div class="addFormContainer">
                                        <label for="lname" class="formInput">Last Name</label>
                                        <input type="text" class="formInput" name="lname" id="lname">
                                    </div>
                                    <div class="addFormContainer">
                                        <label for="email">Email</label>
                                        <input type="text" class="formInput" name="email" id="email">
                                    </div>
                                    <div class="addFormContainer">
                                        <label for="password">Password</label>
                                        <input type="text" class="formInput" name="password" id="password">
                                    </div>
                                    <button type="submit" class="addBtn"><i class="fa fa-send"></i> Submit Form</button>
                                </form>
                                <?php
                                    if(isset($_SESSION['response'])){
                                        $response_message = $_SESSION['response']['message'];
                                        $is_success = $_SESSION['response']['success'];
                                ?>
                                    <div class="responseMessage">
                                        <p class="responseMessage <?=$is_success ? 'responseMessage_success' : 'responseMessage_error' ?>">
                                            <?=$response_message ?>
                                        </p>
                                    </div>
                                <?php unset($_SESSION['response']); } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Other Column Display -->
                    <div class="column column-7">
                        <h2 class="profiles"><i class="fa fa-list"></i>List of Users</h2>
                        <div class="users">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $index = 0; // Initialize index
                                    foreach($users as $user) { ?>
                                        <tr>
                                            <td><?= ++$index ?></td>
                                            <td><?= htmlspecialchars($user['fname']) ?></td>
                                            <td><?= htmlspecialchars($user['lname']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= date('M d, Y @ h:i:s: A', strtotime($user['created_at'])) ?></td>
                                            <td><?= date('M d, Y @ h:i:s: A', strtotime($user['updated_at'])) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="javascript/script.js"></script>
</body>
</html>