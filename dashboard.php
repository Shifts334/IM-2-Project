<?php
//session start
session_start();
if(!isset($_SESSION['user'])) header('location: login.php');
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/userAdd.css">
    <script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
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
                <div class="dashboard_content_main">
                    
                </div>
            </div>
        </div>
    </div>

    <script src ="javascript/script.js"></script>
</body>
</html>