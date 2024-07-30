<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'project';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$email = $_POST['email'];

$sql = "SELECT * FROM `user/admin` WHERE `mail` = '" . $email . "'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
   echo 'exists';
} else {
    echo 'not exists';
}
$conn->close();
?>