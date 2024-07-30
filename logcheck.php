<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'project';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$email = $_POST['email'];
$pass = $_POST['pass'];

$query = "SELECT `password` FROM `user/admin` WHERE `mail`='$email' and `password`='$pass'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo 'exists';
} else {
    echo 'not exists';
}
$conn->close();
?>