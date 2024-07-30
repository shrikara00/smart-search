<?php
// updateProfile.php
session_start();
$originalMail = $_SESSION['email'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the updated values from the request
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Check if the phone number already exists
$checkQuery = "SELECT `mail` FROM `user/admin` WHERE `phone` = " . $phone . " AND `mail` != '" . $originalMail . "'";
$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    echo "exists";
} else {
    // Perform the update operation
    $sql = "UPDATE `user/admin` SET `uname` = '" . $username . "', `phone` =  " . $phone . "  WHERE `mail`='" . $originalMail . "'";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
}
$conn->close();
?>