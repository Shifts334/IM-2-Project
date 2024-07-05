<?php
// session start
session_start();
if (!isset($_SESSION['user'])) header('location: login.php');
$_SESSION['table'] = 'users';
$user = $_SESSION['user'];
$users = include('database/showUsers.php');

$pageTitle = 'User Add';
include('partials/header.php');
?>

<div id="dashboardMainContainer">
    <?php include('partials/sideBar.php') ?>

    <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include('partials/topNavBar.php') ?>

        <div class="dashboard_content">
            <div class="row">
                <div class="column column-5">
                    <h2 class="profiles"><i class="fa fa-plus"></i>Add / Edit Profile</h2>
                    <div class="dashboard_content_main">
                        <div id="userAddContainer">
                            <form action="database/user_DB_add.php" method="POST" class="AddForm">
                                <input type="hidden" name="id" id="user_id">
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
                    <h2 class="profiles"><i class="fa fa-list"></i>List of Users</h2>
                    <div class="users">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($users as $user) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['id']) ?></td>
                                        <td class="fname"><?= htmlspecialchars($user['fname']) ?></td>
                                        <td class="lname"><?= htmlspecialchars($user['lname']) ?></td>
                                        <td class="email"><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= date('M d, Y @ h:i:s: A', strtotime($user['created_at'])) ?></td>
                                        <td><?= date('M d, Y @ h:i:s: A', strtotime($user['updated_at'])) ?></td>
                                        <td>
                                            <a href="#" class="updateUser" data-user-id="<?= $user['id'] ?>" data-fname="<?= htmlspecialchars($user['fname']) ?>" data-lname="<?= htmlspecialchars($user['lname']) ?>" data-email="<?= htmlspecialchars($user['email']) ?>"><i class="fa fa-pencil"></i>Edit</a> <br>
                                            <a href="#" class="deleteUser" data-user-id="<?= $user['id'] ?>" data-fname="<?= htmlspecialchars($user['fname']) ?>" data-lname="<?= htmlspecialchars($user['lname']) ?>">
                                                <i class="fa fa-trash"></i>Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <p class="userCount"><?= count($users) ?> Users</p>
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

                if (classList.contains('deleteUser')) {
                    e.preventDefault();
                    let userId = targetElement.dataset.userId;
                    let fname = targetElement.dataset.fname;
                    let lname = targetElement.dataset.lname;
                    let fullName = fname + ' ' + lname;

                    if (window.confirm('Are you sure you want to delete ' + fullName + '?')) {
                        $.ajax({
                            method: 'POST',
                            data: {
                                user_id: userId
                            },
                            url: 'database/deleteUser.php',
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

</body>
</html>