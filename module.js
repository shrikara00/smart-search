document.getElementById('logout').addEventListener('click', Logout);
function Logout() {
  // Make an AJAX request to a PHP file to handle session deletion
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "logout.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // Clear browser history and redirect to the sign-in page
        window.history.replaceState({}, document.title, "login.php");
        window.location.href = "login.php";
      } else {
        // Handle any errors or display a message to the user
      }
    }
  };
  xhr.send();
};

//profile
//module.js
document.getElementById('profile').addEventListener('click', Profile);

function Profile() {
  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      var username = response.username;
      var email = response.email;
      var phone = response.phone;

      // Set the value to the HTML fields
      document.getElementById("uname").value = username;
      document.getElementById("umail").value = email;
      document.getElementById("uphone").value = phone;
    }
  };

  xhr.open("GET", "profile.php", true);
  xhr.send();

  var profile = document.getElementById('profile-box');
  profile.showModal();

}

// UPDATE clicked
document.getElementById('update').addEventListener('click', function () {
  // Get the values from the HTML fields
  var username = document.getElementById("uname").value.trim();
  var email = document.getElementById("umail").value;
  var phone = document.getElementById("uphone").value.trim();

  // Check if the name contains numbers or special characters
  var nameRegex = /^[a-zA-Z\s]+$/;
  if (!nameRegex.test(username)) {
    alert("Invalid name. Please enter a valid name without numbers or special characters.");
    document.getElementById('uname').focus();
    return;
  }

  // Check if the phone number contains any characters and has 10 digits
  var phoneRegex = /^\d{10}$/;
  if (!phoneRegex.test(phone)) {
    alert("Invalid phone number. Please enter a 10-digit phone number without any characters.");
    document.getElementById('uphone').focus();
    return;
  }


  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = xhr.responseText;
        if (response === "success") {
          alert("Profile updated successfully!");
        } else if (response === "exists") {
          alert('Phone number already exists. Please use different.');
        }
        else {
          alert("Profile update failed.");
        }
      } else {
        alert("An error occurred while updating the profile.");
      }
    }
  };

  // Prepare the data to be sent
  var data = new FormData();
  data.append("username", username);
  data.append("email", email);
  data.append("phone", phone);

  xhr.open("POST", "updateProfile.php", true);
  xhr.send(data);

});


//close profile button
document.getElementById('profileclose').addEventListener('click', function () {
  var profile = document.getElementById('profile-box');
  profile.close();
});


//SEARCH HISTORY
document.getElementById('search history').addEventListener('click', searchHistory);

function searchHistory() {
  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);

      // Get the search history container element
      var searchHistoryContainer = document.getElementById("display-history");

      var recentContainer = document.getElementById("recent");
      var earlierContainer = document.getElementById("earlier");

      // Remove existing "Select All" checkbox and label
      var selectAllCheckbox = document.getElementById("select-all-checkbox");
      var selectAllLabel = document.getElementById('select-all-lable');
      if (selectAllCheckbox) {
        selectAllCheckbox.remove();
      }
      if (selectAllLabel) {
        selectAllLabel.remove();
      }

      searchHistoryContainer.innerHTML = '';

      // Clear any existing history entries
      if (recentContainer) {
        recentContainer.innerHTML = "";
      }

      if (earlierContainer) {
        earlierContainer.innerHTML = "";
      }


      if (response.today.length > 0 || response.earlier.length > 0) {
        if (response.today && response.today.length > 0) {
          // Create label for recent history
          var recentLabel = document.createElement("label");
          recentLabel.innerText = "Recent";
          recentLabel.id = 'recent-label';
          recentContainer.appendChild(recentLabel);

          // Iterate over the today's history array and create div elements
          response.today.forEach(function (entry) {
            var div = document.createElement("div");
            div.classList.add("history-entry");

            var checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.classList.add('checkbox');
            checkbox.value = entry;

            var label = document.createElement("label");
            label.innerText = entry;

            div.appendChild(checkbox);
            div.appendChild(label);

            recentContainer.appendChild(div);
            searchHistoryContainer.appendChild(recentContainer);
          });
        }

        if (response.earlier && response.earlier.length > 0) {
          // Create label for earlier history
          var earlierLabel = document.createElement("label");
          earlierLabel.innerText = "Earlier";
          earlierLabel.id = 'earlier-label';
          earlierContainer.appendChild(earlierLabel);

          // Iterate over the earlier history array and create div elements
          response.earlier.forEach(function (entry) {
            var div = document.createElement("div");
            div.classList.add("history-entry");

            var checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.classList.add('checkbox');
            checkbox.value = entry;

            var label = document.createElement("label");
            label.innerText = entry;

            div.appendChild(checkbox);
            div.appendChild(label);

            earlierContainer.appendChild(div);
            searchHistoryContainer.appendChild(earlierContainer);
          });
        }

        // Create the "Select All" checkbox
        var selectAllCheckbox = document.createElement("input");
        selectAllCheckbox.type = "checkbox";
        selectAllCheckbox.id = "select-all-checkbox";

        var selectAllLabel = document.createElement("label");
        selectAllLabel.innerText = "Select All";
        selectAllLabel.id = 'select-all-lable';

        // Append the "Select All" checkbox to the container
        var searchHistoryContainer = document.getElementById('select-all-checkbox-div');
        searchHistoryContainer.appendChild(selectAllCheckbox);
        searchHistoryContainer.appendChild(selectAllLabel);

        // Add event listener to "Select All" checkbox
        selectAllCheckbox.addEventListener("change", function () {
          var checkboxes = document.querySelectorAll('.checkbox');

          checkboxes.forEach(function (checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
          });
        });

        //user id css after checkbox is selected
        var checkboxes = document.querySelectorAll('#display-history input[type="checkbox"]');
        checkboxes.forEach(function (checkbox) {
          checkbox.addEventListener('change', function () {
            var parentDiv = checkbox.closest('.history-entry');
            if (checkbox.checked) {
              parentDiv.style.background = '#DED8ED';
            } else {
              parentDiv.style.background = ''; // Remove the background color when unchecked
            }
          });
        });

        document.getElementById('select-all-checkbox').addEventListener('change', function () {
          var selectAll = document.getElementById('select-all-checkbox');
          var historyEntries = document.querySelectorAll('.history-entry');
          if (selectAll.checked) {
            historyEntries.forEach(function (entry) {
              entry.style.background = '#DED8ED';
            });
          }
          else {
            historyEntries.forEach(function (entry) {
              entry.style.background = '';
            });
          }
        });

      } else {
        // If both recent and earlier history are empty, display a message

        var message = document.createElement("div");
        message.innerText = "Your search history is empty.";
        searchHistoryContainer.appendChild(message);
      }

    }
  };

  xhr.open("GET", "searchHistory.php", true);
  xhr.send();

  var historyBox = document.getElementById('history-dialog');
  historyBox.showModal();
}

//delete history
document.getElementById('delete').addEventListener('click', deleteSelectedItems);

function deleteSelectedItems() {
  var checkboxes = document.querySelectorAll('.checkbox');
  var selectedItems = [];

  checkboxes.forEach(function (checkbox) {
    if (checkbox.checked) {
      selectedItems.push(checkbox.value);
    }
  });

  if (selectedItems.length === 0) {
    alert('No items selected.');
    return;
  }

  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        alert('Selected items deleted successfully.');
        checkboxes.forEach(function (checkbox) {
          if (checkbox.checked) {
            var historyEntry = checkbox.closest('.history-entry');
            historyEntry.parentNode.removeChild(historyEntry);
          }
        });
        var container = document.getElementById('display-history');
        if (container.children.length === 0) {
          var selectAllCheckbox = document.getElementById('select-all-checkbox');
          var selectAllLabel = document.getElementById('select-all-lable');
          selectAllCheckbox.remove();
          selectAllLabel.remove();

          var noUsersMessage = document.createElement('p');
          noUsersMessage.textContent = 'Your history is empty';

          container.appendChild(noUsersMessage);
        }
      } else {
        alert('Failed to delete selected items.');
      }
    }
  };

  var data = new FormData();
  selectedItems.forEach(function (item) {
    data.append('selectedItems[]', item);
  });

  xhr.open('POST', 'deleteHistory.php', true);
  xhr.send(data);
}


document.getElementById('searchBoxClose').addEventListener('click', function () {
  var historyBox = document.getElementById('history-dialog');
  historyBox.close();
})