var sideBarisOpen = true;
    var toggleBtn = document.getElementById('toggleBtn');
    var dashboard_sidebar = document.getElementById('dashboard_sidebar');
    var dashboard_content_container = document.getElementById('dashboard_content_container');
    var dashboard_logo = document.getElementById('dashboard_logo');
    var userImage = document.getElementById('userImage');

    toggleBtn.addEventListener('click', function(event) {
        event.preventDefault();

        if (sideBarisOpen) {
            dashboard_sidebar.style.width = '10%';
            dashboard_content_container.style.width = '90%';
            dashboard_logo.style.fontSize = '25px';
            userImage.style.width = '40px';

            var menuText = document.getElementsByClassName('menuText');
            for (var i = 0; i < menuText.length; i++) {
                menuText[i].style.display = 'none';
            }

            document.getElementsByClassName('dashboard_menu_lists')[0].style.textAlign = 'center';
            sideBarisOpen = false;
        } else {
            dashboard_sidebar.style.width = '20%';
            dashboard_content_container.style.width = '80%';
            dashboard_logo.style.fontSize = '30px';
            userImage.style.width = '60px';

            var menuText = document.getElementsByClassName('menuText');
            for (var i = 0; i < menuText.length; i++) {
                menuText[i].style.display = 'inline-block';
            }

            document.getElementsByClassName('dashboard_menu_lists')[0].style.textAlign = 'left';
            sideBarisOpen = true;
        }
    });