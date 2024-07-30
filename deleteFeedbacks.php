<?php
// Get the selected feedbacks data
$feedbacksToDelete = json_decode($_POST['feedbacks'], true);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create a new MySQLi object
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete the selected feedbacks
foreach ($feedbacksToDelete as $feedback) {
    $email = $feedback['email'];
    $text = $feedback['text'];
    $date = $feedback['date'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM feedback WHERE mail = ? AND message = ? AND datetime = ? ");
    $stmt->bind_param("sss", $email, $text, $date);

    // Execute the delete statement
    if ($stmt->execute() !== TRUE) {
        echo "Error deleting feedback: " . $stmt->error;
        exit;
    }
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();

// Send a success response
http_response_code(200);
?>
