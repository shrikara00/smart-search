<?php
// searchHistory.php
session_start();
$personLoggedIn = $_SESSION['email'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Retrieve search history for the logged-in person for the present date
$sqlToday = "SELECT history FROM search_history WHERE mail = '$personLoggedIn' AND DATE(datetime) = CURDATE() ORDER BY datetime DESC";
$resultToday = $conn->query($sqlToday);

if ($resultToday->num_rows > 0) {
    $historyToday = array();

    // Fetch each search history entry for today
    while ($row = $resultToday->fetch_assoc()) {
        $entry = $row["history"];
        $historyToday[] = $entry;
    }

    // Create an associative array with the today's search history data
    $data = array(
        "today" => $historyToday
    );
} else {
    $data = array(
        "today" => []
    );
}

// Retrieve search history for the logged-in person for earlier dates
$sqlEarlier = "SELECT history FROM search_history WHERE mail = '$personLoggedIn' AND DATE(datetime) < CURDATE() ORDER BY datetime DESC";
$resultEarlier = $conn->query($sqlEarlier);

if ($resultEarlier->num_rows > 0) {
    $historyEarlier = array();

    // Fetch each search history entry for earlier dates
    while ($row = $resultEarlier->fetch_assoc()) {
        $entry = $row["history"];
        $historyEarlier[] = $entry;
    }

    // Add the earlier search history data to the existing associative array
    $data["earlier"] = $historyEarlier;
} else {
    $data["earlier"] = [];
}

$conn->close();

// Set the response header to JSON
header('Content-Type: application/json');

// Encode the data array as JSON and echo the response
echo json_encode($data);
?>
