<?php
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

// Prepare the SQL statement to select all feedbacks
$sql = "SELECT mail, message, datetime FROM feedback";
$result = $conn->query($sql);

// Check if any feedbacks are found
if ($result->num_rows > 0) {
    // Create an array to store the feedbacks
    $feedbacks = array();

    // Fetch the feedbacks from the result set
    while ($row = $result->fetch_assoc()) {
        // Add each feedback to the array
        $feedbacks[] = $row;
    }

    // Convert the feedbacks array to JSON format
    $jsonFeedbacks = json_encode($feedbacks);

    // Return the JSON response
    header('Content-Type: application/json');
    echo $jsonFeedbacks;
} else {
    // No feedbacks found
    echo "No feedbacks found.";
}

// Close the database connection
$conn->close();
?>
