<?php
session_start();
$personLoggedIn = $_SESSION['email'];

// Retrieve the selected items and email from the request
$selectedItems = $_POST['selectedItems'];

// Connect to the database (replace with your database credentials)
$conn = mysqli_connect('localhost', 'root', '', 'project');

// Loop through the selected items and delete corresponding rows
foreach ($selectedItems as $item) {
    // Sanitize the item value to prevent SQL injection
    $item = mysqli_real_escape_string($conn, $item);

    // Perform the deletion query using the $item and $personLoggedIn values
    $sql = "DELETE FROM search_history WHERE history = '$item' AND mail = '$personLoggedIn'";
    mysqli_query($conn, $sql);
}

// Check if any rows were affected by the deletion
$rowsAffected = mysqli_affected_rows($conn);

// Check if the deletion was successful
if ($rowsAffected > 0) {
    $response = ['success' => true];
    echo json_encode($response);
} else {
    $response = ['success' => false];
    echo json_encode($response);
}

// Close the database connection
mysqli_close($conn);
?>
