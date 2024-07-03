<div class="dashboard_sidebar" id="dashboard_sidebar">
    <!-- add logo here -->
    <h3 class="dashboard_logo" id="dashboard_logo">Palm Grass Hotel</h3>
    <div class="dashboard_sidebar_user">
        <img src="images/userImg.png" alt="User" id="userImage"/>
        <span> <?=$user['fname'].' '.$user['lname']?></span>
    </div>

    <div class="dashboard_sidebar_menus">
        <ul class="dashboard_menu_lists">
            <li class="menuActive">
                <a href="dashboard.php"><i class="fa fa-dashboard"></i> <span class="menuText"> Dashboard</span></a>
            </li>
            <li>
                <a href="userAdd.php"><i class="fa fa-user"></i> <span class="menuText"> Profile List</span></a>
            </li>
            <li>
                <a href=""><i class="fa fa-server"></i> <span class="menuText"> Inventory</span></a>
            </li>
            <li>
                <a href=""><i class="fa fa-book"></i> <span class="menuText"> Purchase History</span></a>
            </li>
            
        </ul>
    </div>
</div>