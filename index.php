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
    <link rel="stylesheet" href="index.css?v=3">
    <link rel="stylesheet" href="module.css?v=6">
    <link rel="icon" href="icons/icons8-search-100.png" type="image/png">
    <title>QueryQuest</title>
</head>

<body>

    <div id="top-bar">
        <div class="bar_1">
            <button class="icons" id="about" onclick="window.location.href='aboutUs.php'">About us</button>
            
            <button class="icons" id="profile">Profile</button>
            
            <button class="icons" id="search history">Search History</button>
            
            <button class="icons" id="feedback">Feedback</button>

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

    <!-- FEEDBACK -->
    <dialog feedback-dialog id="feedback-dialog">
        <h1>Feedback</h1>

        <div id='feedback-text-div'>
            <textarea name="feedback-text" id="feedback-text" placeholder="Write yor feedback here..."></textarea>
        </div>

        <button id="submit-feedback">Submit</button>
        <button id='feedback-close-button'>Cancel</button>
        <dialog>

            <!-- SCRIPT -->

            <script src="module.js"></script>
            <script>
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

                function blanckValidate() {
                    var bar = document.getElementById('search-bar');
                    if (bar.value.trim() === "") {
                        bar.setCustomValidity('Please write appropriate query');
                    }
                    else {
                        bar.setCustomValidity('');
                    }
                }

                // FEEDBAK
                document.getElementById('feedback').addEventListener('click', function () {
                    var feedback = document.getElementById('feedback-dialog');
                    feedback.showModal();
                });
                //submit feedback
                document.getElementById('submit-feedback').addEventListener('click', submitFeedback);

                function submitFeedback() {
                    var feedback = document.getElementById('feedback-text').value.trim();

                    // Check if feedback is empty
                    if (feedback === '') {
                        alert('Please write your feedback');
                        document.getElementById('feedback-text').focus();
                        return;
                    }

                    // Get current date and time
                    var currentDate = new Date();

                    // Extract date components
                    var day = String(currentDate.getDate()).padStart(2, '0');
                    var month = String(currentDate.getMonth() + 1).padStart(2, '0');
                    var year = currentDate.getFullYear();

                    // Extract time components
                    var hours = currentDate.getHours();
                    var minutes = String(currentDate.getMinutes()).padStart(2, '0');
                    var ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12 || 12; // Convert to 12-hour format

                    // Format the date and time
                    var formattedDateTime = day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + ampm;

                    // Create an XMLHttpRequest object
                    var xhr = new XMLHttpRequest();

                    // Set up a callback function to handle the AJAX response
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // Feedback submitted successfully
                                alert('Feedback submitted successfully.');
                                document.getElementById('feedback-text').value = '';
                                var feedbackDialog = document.getElementById('feedback-dialog');
                                feedbackDialog.close();
                            } else {
                                // Error occurred while submitting feedback
                                console.error('Error submitting feedback. Status: ' + xhr.status);
                            }
                        }
                    };

                    // Prepare the AJAX request
                    xhr.open('POST', 'feedback.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                    // Create the data to be sent
                    var data = 'feedback=' + encodeURIComponent(feedback) + '&datetime=' + encodeURIComponent(formattedDateTime);

                    // Send the request with the data
                    xhr.send(data);

                }

                //feedback close
                document.getElementById('feedback-close-button').addEventListener('click', function () {
                    var feedback = document.getElementById('feedback-dialog');
                    feedback.close();
                });
            </script>


</body>

</html>