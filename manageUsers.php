<?php
//manageUsers.php
// Make a database connection
$mysqli = new mysqli("localhost", "root", "", "project");

// Check if the connection was successful
if ($mysqli->connect_errno) {
  // Handle the connection error
  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  exit();
}

// Array to store the user emails
$userEmails = array();

// Query to fetch user emails from the registration table excluding specific emails
$query = "SELECT mail FROM `user/admin` WHERE mail NOT IN ('shri.karantha@gmail.com', 'saliyandarshan639@gmail.com', 'nagarajkmath08@gmail.com')";

// Execute the query
$result = $mysqli->query($query);

// Check if there are any rows returned
if ($result->num_rows > 0) {
  // Loop through the rows and fetch the email values
  while ($row = $result->fetch_assoc()) {
    $userEmails[] = $row['mail'];
  }
}

// Close the database connection
$mysqli->close();

// Check if there are any user emails
if (!empty($userEmails)) {
  // Return the user emails as JSON response
  header('Content-Type: application/json');
  echo json_encode($userEmails);
} else {
  // Return "no users" message as JSON response
  echo json_encode(array('message' => 'No users'));
}
?>
