<?php
session_start();
if (!$_SESSION['login']) {
    exit();
}
$email = $_SESSION['email'];
?>
<!DOCTYPE html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminIndex.css?v=2">
    <link rel="stylesheet" href="module.css">
    <link rel="stylesheet" href="adminModule.css?v=3">
    <link rel="icon" href="icons/icons8-search-100.png" type="image/png">
    <title>QueryQuest</title>

</head>

<body>
    <!-- HTML -->
    <div id="top-bar">
        <div class="bar_1">
            <button class="icons" id="about" onclick="window.location.href='aboutUs.php'">About us</button>


            <button class="icons" id="search history">Search history</button>

            <button class="icons" id="manage users">Manage users</button>

            <button class="icons" id="view count">User count</button>

            <button class="icons" id="feedbacks received">Feedbacks received</button>

            <button class="icons" id="profile">Profile</button>
            <button class="icons" id="logout">Logout</button>

        </div>
    </div>

    <!--logo-->
    <p class="QueryQuest">QueryQuest</p>

    <!--Search bar-->
    <form id="Myform" method="post" action="QueryQuest.php">
        <div id='bar-container-div'>
            <input id="search-bar" name="query" type="text" placeholder="Search..." required oninput="blanckValidate()">
            <button type="submit" name="search" id="btn"><img src="Google icons/pngwing.com.png"
                    id="search-button-image"></button>
        </div>
    </form>

    <!--  PROFILE    -->
    <dialog profile-dialog id="profile-box">
        <label>My profile</label>

        <img id="face" src="icons/profile.png">

        <input profile-name type="text" id='uname' name="uname" class='profile-fields'>

        <input profile-mail type="text" id="umail" name="umail" readonly class='profile-fields'>

        <input profile-phone type="tel" id="uphone" name="uphone" class='profile-fields'>

        <button id="update">Update</button>
        <button id='profileclose'>Cancel</button>
    </dialog>

    <!--  SEARCH HISTORY    -->
    <dialog search-history-dialog id="history-dialog">
        <h1>Search History</h1>

        <div id="select-all-checkbox-div"></div>
        <div id="display-history">
            <div id='recent'></div>
            <div id='earlier'></div>

        </div>
        <button id="delete">Delete</button>
        <button id="searchBoxClose">Cancel</button>
    </dialog>

    <!--  MANAGE USERS    -->
    <dialog search-history-dialog id="manage-users-dialog">
        <h1>Manage users</h1>

        <div id="user-list"></div>

        <button id="delete-user">Delete</button>
        <button id="manageUserClose">Cancel</button>
    </dialog>

    <!-- FEEDBACK RECEIVED   -->
    <dialog feedback-received dialog id="feedbacks-dialog">
        <h1>Feedbacks</h1>

        <div id='all-feedbacks-container'></div>

        <button id="feedback-delete">Delete</button>
        <button id="feedback-close">Cancel</button>
    </dialog>

    <!-- View Count -->
    <dialog view-dialog id="view-box">
        <h1>Viwer's Visited</h1>

        <label id="usrcnt">Current number of users are: </label><br><br>
        <p id="userCount"></p>

        <button id='view-close' class="close">Close</button>
    </dialog>



    <!-- SCRIPT  -->
    <script src="module.js"></script>
    <script src="adminModule.js"></script>

    <script>
        function blanckValidate() {
            var bar = document.getElementById('search-bar');
            if (bar.value.trim() === "") {
                bar.setCustomValidity('Please write appropriate query');
            }
            else {
                bar.setCustomValidity('');
            }
        }
        // Get all the icon elements
        const images = document.querySelectorAll('.icons');

        // Iterate over each icon
        images.forEach(image => {
            image.addEventListener('mouseenter', function () {
                image.classList.add('show');
            });

            image.addEventListener('mouseleave', function () {
                image.classList.remove('show');
            });
        });
        images.forEach(image => {
            image.addEventListener('focus', function () {
                image.classList.add('show');
            });

            image.addEventListener('blur', function () {
                image.classList.remove('show');
            });
        });

    </script>
</body>

</html>