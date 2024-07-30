
<?php
//profile.php

session_start();
$email=$_SESSION['email'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Perform the query to fetch the username
$sql = "SELECT `uname`, `mail`, `phone` FROM `user/admin` WHERE `mail` = '".$email."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the data from the result
    $row = $result->fetch_assoc();
    $username = $row["uname"];
    $email = $row["mail"];
    $phone = $row["phone"];

    $data = array(
        "username" => $username,
        "email" => $email,
        "phone" => $phone
    );

    echo json_encode($data);
} else {
    echo json_encode(array("error" => "No data found"));
}

$conn->close();
?>
