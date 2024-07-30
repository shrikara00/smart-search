<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'project';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$phone = $_POST['phone'];
$sql = "SELECT * FROM `user/admin` WHERE `phone` = " . $phone . "";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
   echo 'exists';
} else {
    echo 'not exists';
}
$conn->close();
?>