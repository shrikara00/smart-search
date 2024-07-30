<?php
// Check if the selectedUsers parameter is present and not empty
if (isset($_POST['selectedUsers']) && !empty($_POST['selectedUsers'])) {
    // Retrieve the selected user IDs from the POST data
    $selectedUsers = json_decode($_POST['selectedUsers'], true);

    // Create a connection to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement to delete the selected users from registration table
    $placeholders = implode(',', array_fill(0, count($selectedUsers), '?'));
    $sql = "DELETE FROM `user/admin` WHERE mail IN ($placeholders)";

    // Prepare the SQL statement to delete the associated rows from search_history table
    $searchHistorySql = "DELETE FROM search_history WHERE mail IN ($placeholders)";

    // Prepare the statements
    $stmt = $conn->prepare($sql);
    $stmtSearchHistory = $conn->prepare($searchHistorySql);

    // Bind the parameters
    $stmt->bind_param(str_repeat('s', count($selectedUsers)), ...$selectedUsers);
    $stmtSearchHistory->bind_param(str_repeat('s', count($selectedUsers)), ...$selectedUsers);

    // Execute the statements
    if ($stmt->execute() && $stmtSearchHistory->execute()) {
        // Return a success response
        $response = array(
            "status" => "success",
            "message" => "Users deleted successfully."
        );
        echo json_encode($response);
    } else {
        // Return an error response
        $response = array(
            "status" => "error",
            "message" => "Error deleting users: " . $stmt->error
        );
        echo json_encode($response);
    }

    // Close the statements and the database connection
    $stmt->close();
    $stmtSearchHistory->close();
    $conn->close();
} else {
    // Return an error response if the selectedUsers parameter is missing or empty
    $response = array(
        "status" => "error",
        "message" => "No users selected for deletion."
    );
    echo json_encode($response);
}
?>
