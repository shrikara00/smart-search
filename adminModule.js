document.getElementById('manage users').addEventListener('click', manageUsers);

function manageUsers() {
    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Set up a callback function to handle the AJAX response
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Parse the response as JSON
                var userList = JSON.parse(xhr.responseText);

                // Get the container element to append the users list
                var container = document.getElementById('user-list');

                // Clear the container
                container.innerHTML = '';

                // Check if there are users
                if (userList.length > 0) {
                    // Iterate over the user list and create checkboxes
                    userList.forEach(function (email) {
                        var checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.value = email;

                        var label = document.createElement('label');
                        label.textContent = email;

                        var userContainer = document.createElement('div');
                        userContainer.appendChild(checkbox);
                        userContainer.appendChild(label);
                        userContainer.classList.add('users');

                        container.appendChild(userContainer);
                    });
                    //user id css after checkbox is selected
                    var checkboxes = document.querySelectorAll('#user-list input[type="checkbox"]');
                    checkboxes.forEach(function (checkbox) {
                        checkbox.addEventListener('change', function () {
                            var parentDiv = checkbox.closest('.users');
                            if (checkbox.checked) {
                                parentDiv.style.background = '#DED8ED';
                            } else {
                                parentDiv.style.background = ''; // Remove the background color when unchecked
                            }
                        });
                    });
                } else {
                    // Display "No users" message
                    var noUsersMessage = document.createElement('p');
                    noUsersMessage.textContent = 'No users';

                    container.appendChild(noUsersMessage);
                }
            } else {
                console.error('Error: ' + xhr.status);
            }
        }
    };

    // Open the AJAX request
    xhr.open('GET', 'manageUsers.php', true);

    // Send the request
    xhr.send();


    //showModal
    var deleteUserDialog = document.getElementById('manage-users-dialog');
    deleteUserDialog.showModal();

}

//delete users..
document.getElementById('delete-user').addEventListener('click', deleteUser);
function deleteUser() {
    // Get the list of selected user IDs
    var selectedUsers = [];
    var checkboxes = document.querySelectorAll('#user-list input[type="checkbox"]:checked');
    checkboxes.forEach(function (checkbox) {
        selectedUsers.push(checkbox.value);
    });

    if (selectedUsers.length === 0) {
        alert('No users selected.');
        return;
    }

    // Ask for confirmation before deleting
    var confirmed = confirm('Are you sure you want to delete the selected users?');
    if (!confirmed) {
        return;
    }

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Set up a callback function to handle the AJAX response
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Parse the response as JSON
                var response = JSON.parse(xhr.responseText);

                if (response.status === 'success') {
                    alert(response.message);
                    // Remove deleted users from the user interface
                    checkboxes.forEach(function (checkbox) {
                        var userContainer = checkbox.closest('.users');
                        userContainer.parentNode.removeChild(userContainer);
                    });
                    // Check if there are any remaining users
                    var container = document.getElementById('user-list');
                    if (container.children.length === 0) {
                        var noUsersMessage = document.createElement('p');
                        noUsersMessage.textContent = 'No users left';

                        container.appendChild(noUsersMessage);
                    }
                } else {
                    alert(response.message);
                }
            } else {
                console.error('Error: ' + xhr.status);
            }
        }
    };

    // Open the AJAX request
    xhr.open('POST', 'deleteUsers.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Set the data to be sent in the request body
    var data = 'selectedUsers=' + JSON.stringify(selectedUsers);

    // Send the request
    xhr.send(data);
}


//Mnage users close
document.getElementById('manageUserClose').addEventListener('click', function () {
    var deleteUserDialog = document.getElementById('manage-users-dialog');
    deleteUserDialog.close();
});


//FEEDBACKS RECEIVED 

document.getElementById('feedbacks received').addEventListener('click', feedbackReceived);

function feedbackReceived() {
    var container = document.getElementById('all-feedbacks-container');

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Set up a callback function to handle the AJAX response
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Check if the response is the "No feedbacks found." message
                if (xhr.responseText === "No feedbacks found.") {
                    container.innerHTML = '<p>No feedbacks found.</p>';
                } else {
                    // Parse the response as JSON
                    var feedbacks = JSON.parse(xhr.responseText);

                    // Clear the container
                    container.innerHTML = '';

                    // Iterate over the feedbacks and create elements for each entry
                    feedbacks.forEach(function (feedback) {
                        var feedbackList = document.createElement('div');
                        feedbackList.classList.add('feedback-list');

                        var checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';

                        var mailLabel = document.createElement('label');
                        mailLabel.classList.add('mail-label');
                        mailLabel.textContent = feedback.mail;

                        var dateTimeLabel = document.createElement('label');
                        dateTimeLabel.classList.add('date-label');
                        dateTimeLabel.textContent = feedback.datetime;

                        var message = document.createElement('p');
                        message.classList.add('feedback-text');
                        message.textContent = feedback.message;

                        feedbackList.appendChild(checkbox);
                        feedbackList.appendChild(mailLabel);
                        feedbackList.appendChild(dateTimeLabel);
                        feedbackList.appendChild(message);

                        container.appendChild(feedbackList);
                    });
                    var checkboxes = document.querySelectorAll('#all-feedbacks-container input[type="checkbox"]');
                    checkboxes.forEach(function (checkbox) {
                        checkbox.addEventListener('change', function () {
                            var parentDiv = checkbox.closest('.feedback-list');
                            if (checkbox.checked) {
                                parentDiv.style.background = '#DED8ED';
                            } else {
                                parentDiv.style.background = ''; // Remove the background color when unchecked
                            }
                        });
                    });
                }
            } else {
                console.error('Error: ' + xhr.status);
            }
        }
    };

    // Open the AJAX request
    xhr.open('GET', 'getFeedbacks.php', true);

    // Send the request
    xhr.send();


    var feedbackOpen = document.getElementById('feedbacks-dialog');
    feedbackOpen.showModal();

}

//delete selected feedbacks
document.getElementById('feedback-delete').addEventListener('click', deleteSelectedFeedbacks);

function deleteSelectedFeedbacks() {
    var checkboxes = document.querySelectorAll('.feedback-list input[type="checkbox"]');
    var feedbacksToDelete = [];
    var checkboxesSelected = false;

    checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
            checkboxesSelected = true;
            var feedbackDiv = checkbox.parentNode;

            var feedbackId = checkbox.dataset.feedbackId;
            var emailLabel = feedbackDiv.querySelector('.mail-label');
            var email = emailLabel.textContent;
            var dateTime = feedbackDiv.querySelector('.date-label').textContent;
            var feedbackText = feedbackDiv.querySelector('.feedback-text').textContent;

            feedbacksToDelete.push({ id: feedbackId, email: email, text: feedbackText, date: dateTime });
        }
    });

    if (!checkboxesSelected) {
        alert('No feedback is selected to delete.');
        return;
    }

    // Display the confirmation message
    var confirmed = confirm('Are you sure you want to delete the selected feedback(s)?');
    if (!confirmed) {
        return;
    }

    // Remove the div containing the checkbox after the user confirms deletion
    checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
            var feedbackDiv = checkbox.parentNode;
            feedbackDiv.parentNode.removeChild(feedbackDiv);
        }
    });

    // Check if the 'all-feedbacks-container' is empty
    var allFeedbacksContainer = document.getElementById('all-feedbacks-container');
    if (!allFeedbacksContainer.hasChildNodes()) {
        allFeedbacksContainer.textContent = 'No feedback found.';
    }

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Set up a callback function to handle the AJAX response
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                alert('Feedback deleted successfully.');
            } else {
                console.error('Error: ' + xhr.status);
            }
        }
    };

    // Open the AJAX request
    xhr.open('POST', 'deleteFeedbacks.php', true);

    // Set the content type for POST request
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Send the request with the selected feedbacks data
    xhr.send('feedbacks=' + encodeURIComponent(JSON.stringify(feedbacksToDelete)));
}


//feedback dialog close
document.getElementById('feedback-close').addEventListener('click', function () {
    var feedbackClose = document.getElementById('feedbacks-dialog');
    feedbackClose.close();
})



//  VIEW COUNT ..
document.getElementById('view count').addEventListener('click', viewCount);

function viewCount() {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var userCount = xhr.responseText;
            document.getElementById("userCount").innerText = userCount;
        }
    };

    xhr.open("GET", "viewCount.php", true);
    xhr.send();

    var viewDialog = document.getElementById('view-box');
    viewDialog.showModal();
}

document.getElementById('view-close').addEventListener('click', function () {
    var viewDialog = document.getElementById('view-box');
    viewDialog.close();
});