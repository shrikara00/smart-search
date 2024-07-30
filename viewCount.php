<?php
//viewcount.php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$connection = mysqli_connect($servername, $username, $password, $dbname);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to count the number of users
$query = "SELECT COUNT(*) AS mail FROM `user/admin`";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);
$userCount = $row['mail'];

if ($userCount > 3) {
    $userCount -= 3;
} else {
    $userCount = 0;
}

// Close the database connection
mysqli_close($connection);

// Return the user count as the response
echo $userCount;
?>