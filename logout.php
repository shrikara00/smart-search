<?php
// Start the session
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Delete records from text_result and image_result tables
$host = "localhost";
$username = "root";
$password = "";
$database = "project";

// Create a new mysqli connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete records from text_result table
$sqlDeleteText = "DELETE FROM text_result";
if ($conn->query($sqlDeleteText) === false) {
    echo "Error deleting records from text_result table: " . $conn->error;
}

//Delete records from image_result table
$sqlDeleteImage = "DELETE FROM image_result";
if ($conn->query($sqlDeleteImage) === false) {
    echo "Error deleting records from image_result table: " . $conn->error;
}

// Close the database connection
$conn->close();

// Delete folders in Offline Search/text directory
$directory = "Offline Search/text";

if (is_dir($directory)) {
    $files = glob($directory . '/*');
    foreach ($files as $file) {
        if (is_dir($file)) {
            // Remove the directory and its contents recursively
            deleteDirectory($file);
        }
    }
}
// Delete folders in Offline Search/image directory
$directory = "Offline Search/image";

if (is_dir($directory)) {
    $files = glob($directory . '/*');
    foreach ($files as $file) {
        if (is_dir($file)) {
            // Remove the directory and its contents recursively
            deleteDirectory($file);
        }
    }
}

// Function to delete a directory and its contents recursively
function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return;
    }

    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        $path = $dir . '/' . $file;

        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }

    rmdir($dir);
}


// Send a response indicating successful logout
http_response_code(200);
?>