<?php
session_start();
$email = $_SESSION['email'];

// Retrieve the feedback and datetime from the POST request
$feedback = $_POST['feedback'];
$datetime = $_POST['datetime'];

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create a new mysqli connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement to insert the feedback and datetime into the database
$sql = "INSERT INTO feedback (mail, message, datetime) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $email, $feedback, $datetime);

// Execute the statement
if ($stmt->execute()) {
    // Feedback inserted successfully
    echo "Feedback stored in the database.";
} else {
    // Error occurred while inserting the feedback
    echo "Error storing feedback: " . $stmt->error;
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>
