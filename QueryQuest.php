<?php
session_start();
if (!isset($_SESSION['login']) && $_SESSION['login'] === false) {
    exit();
}
$personLogedIn = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="QueryQuest.css?v=11">
    <link rel="stylesheet" href="module.css?v=4">
    <link rel="icon" href="icons/icons8-search-100.png" type="image/png">
    <title>QueryQuest</title>
</head>

<body>
    <nav>
        <div class="bar_1">
            <button class="icons" id="home">Home</button>

            <button class="icons" id="about" onclick="window.location.href='aboutUs.php'">About us</button>

            <button class="icons" id="profile">Profile</button>

            <button class="icons" id="search history">Search history</button>

            <button class="icons" id="logout">Logout</button>
        </div>
    </nav>

    <p class="QueryQuest">QueryQuest</p>

    <!--Search bar-->
    <form id="Myform" method="post" action="#">
        <div id='bar-container-div'>
            <input id="search-bar" name="query" type="text" placeholder="Search..." required oninput="blanckValidate()">
            <div id="side-buttons">
                <button id="x" type="button" onclick="clearfield()"><img
                        src="Google icons/close_FILL0_wght400_GRAD0_opsz48.png" id="x-image"></button>
                <div id="divider"></div>
                <button type="submit" name="search" id="search"><img
                        src="Google icons/search_FILL1_wght400_GRAD0_opsz48.png" id="search-button-image"></button>
            </div>
        </div>
        <div id="webimg">
            <button class='form-btns' name="search" type="submit" id="all">Web</button>
            <button class='form-btns' name="image" type="submit" id="image">Image</button>
            <button class='form-btns' name="video" type="submit" id="video">Video</button>
            <button class='form-btns' name="news" type="submit" id="news">News</button>
        </div>
    </form>


    <div id="content">
        <lable>
        </lable>
    </div>

    <!--  PROFILE    -->
    <dialog profile-dialog id="profile-box">
        <label>My profile</label>

        <img id="face" src="icons/profile.png">

        <input profile-name type="text" id='uname' name="uname" class='profile-fields'>

        <input profile-mail type="text" id="umail" name="umail" readonly class='profile-fields'>

        <input profile-phone type="tel" id="uphone" name="uphone" class='profile-fields'>

        <button id="update">Update</button>
        <button id='profileclose'>Cancel</button>
    </dialog>


    <!--  SEARCH HISTORY    -->
    <dialog search-history-dialog id="history-dialog">
        <h1> Search History</h1>

        <div id="select-all-checkbox-div"></div>

        <div id="display-history">
            <div id='recent'></div>
            <div id='earlier'></div>
        </div>

        <button id="delete">Delete</button>
        <button id="searchBoxClose">Cancel</button>
    </dialog>


    <script src="module.js"></script>
    <script>

        function blanckValidate() {
            var bar = document.getElementById('search-bar');
            if (bar.value.trim() === "") {
                bar.setCustomValidity('Please write appropriate query');
            }
            else {
                bar.setCustomValidity('');
            }
        }

        // Get all the icon elements
        const images = document.querySelectorAll('.icons');
        // Iterate over each icon
        images.forEach(image => {
            image.addEventListener('mouseenter', function () {
                image.classList.add('show');
            });

            image.addEventListener('mouseleave', function () {
                image.classList.remove('show');
            });
        });
        images.forEach(image => {
            image.addEventListener('focus', function () {
                image.classList.add('show');
            });

            image.addEventListener('blur', function () {
                image.classList.remove('show');
            });
        });

        function clearfield() {
            document.getElementById('search-bar').value = '';
            document.getElementById('search-bar').focus();
        }

        window.onload = function () {
            // HOME
            const home = document.getElementById('home');
            home.addEventListener('click', function () {
                <?php
                if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
                    // Redirect admins to adminIndex.php
                    echo "window.location.href = 'adminIndex.php';";
                } else {
                    // Redirect users to index.php
                    echo "window.location.href = 'index.php';";
                }
                ?>
            });
        };


    </script>


    <?php
    // Custom error handler function
    function errorHandler($errno, $errstr, $errfile, $errline)
    {
        // Display the error message or perform any custom actions
        $script = "<script>
                var box = document.getElementById('content');
                box.innerHTML = '" . $errstr . "';
                </script>";

        echo $script;
        // You can also log the error or take other actions as needed
    
        // Return false to prevent the default PHP error handler from being called
        return false;
    }

    // Set the custom error handler
    set_error_handler('errorHandler');

    //internet conncetion function
    function checkInternetConnection()
    {
        $url = 'https://www.google.com'; // Use any reliable website for checking connectivity
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Set a timeout value in seconds
    
        $response = curl_exec($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if ($response !== false) {
            return true; // Internet connection is available
        } else {
            return false; // Internet connection is not available
        }
    }
    ;

    function processResultTable()
    {
        // Establish a database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "project";

        $connection = new mysqli($servername, $username, $password, $dbname);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        // Retrieve all rows from the result table
        $query = "SELECT * FROM text_result";
        $result = $connection->query($query);

        while ($row = $result->fetch_assoc()) {
            $fileName = $row['folder_name'];
            $folderName = "Offline Search/text/" . $fileName;
            $siteFilePath = $folderName . '/site.txt';

            // Check if the folder exists
            if (!is_dir($folderName)) {
                // Delete the record from the database
                deleteRecordFromDatabase($row, $connection);

                continue; // Skip to the next iteration
            }

            // Check if site.txt exists and is empty
            if (!file_exists($siteFilePath) || filesize($siteFilePath) === 0) {
                // Delete the folder
                deleteFolder($folderName);

                // Delete the record from the database
                deleteRecordFromDatabase($row, $connection);
            }
        }
        //image table 
        $query = "SELECT * FROM `image_result`";
        $rows = $connection->query($query);

        while ($row = $rows->fetch_assoc()) {
            $fileName = $row['folder_name'];
            $folderName = "Offline Search/image/" . $fileName;

            // Check if the folder exists
            if (!is_dir($folderName)) {
                // Delete the folder
                //deleteFolder($folderName);
    
                // Delete the record from the database
                deleteRecordFromImageDatabase($row, $connection);

                continue; // Skip to the next iteration
            }
            // Check if the folder is empty
            $files = glob($folderName . '/*');
            if (empty($files)) {
                // Delete the folder
                deleteFolder($folderName);

                // Delete the record from the database
                deleteRecordFromImageDatabase($row, $connection);
            }
        }

        // Close the database connection
        $connection->close();
    }

    // Function to delete a folder and its contents recursively
    function deleteFolder($folderPath)
    {
        if (!is_dir($folderPath)) {
            return;
        }

        $files = glob($folderPath . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            } elseif (is_dir($file)) {
                deleteFolder($file);
            }
        }

        rmdir($folderPath);
    }

    // Function to delete a record from the database
    function deleteRecordFromDatabase($row, $connection)
    {
        // Construct the delete query using row content
        $folderName = $row['folder_name'];
        $query = "DELETE FROM text_result WHERE folder_name = '$folderName'";
        $connection->query($query);
    }
    function deleteRecordFromImageDatabase($row, $connection)
    {
        // Construct the delete query using row content
        $folderName = $row['folder_name'];
        $query = "DELETE FROM `image_result` WHERE folder_name = '$folderName'";
        $connection->query($query);
    }

    //call the function
    processResultTable();



    $search_query = trim($_POST['query']);

    $queryFolder = preg_replace('/[^a-zA-Z0-9\-_]/', '', $search_query);

    echo " <script>
                document.getElementById('search-bar').value='$search_query';
                var currentURL = window.location.href;
                // Check if the URL already contains a query parameter
                if (currentURL.includes('?query=')) {
                    // Remove the existing query parameter and everything after it
                    currentURL = currentURL.split('?query=')[0];
                }
                var updatedURL = currentURL + '?query=' + encodeURIComponent('$search_query');
                window.history.replaceState({}, '', updatedURL);
              </script>";

    if (isset($_POST['search'])) {
        echo "<script>
        const tabButtons = document.querySelectorAll('.form-btns');
        tabButtons.forEach(button => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
        });
        document.getElementById('all').classList.add('active');
        </script>";

        // Connect to database
        $conn = new mysqli("localhost", "root", "", "project");

        // Check connection
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            exit();
        }


        //check if query exists in database
        $result = $conn->query("SELECT folder_name FROM text_result");
        $found = false;

        // Check if the query returned at least one value
        if ($result->num_rows > 0) {
            // Iterate through the rows
            while ($row = $result->fetch_assoc()) {
                $query = $row['folder_name'];

                // Check if the current query matches the search query
                if ($query === $queryFolder) {

                    //insert for search history
                    // Prepare the SQL query to check for existing records
                    $check_sql = "SELECT * FROM search_history WHERE history = ? AND mail = ?";
                    $check_stmt = $conn->prepare($check_sql);
                    $check_stmt->bind_param("ss", $search_query, $personLogedIn);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();

                    // Check if a record already exists with the same search query and email
                    if ($check_result->num_rows > 0) {
                        // Update the datetime column to current date and time
                        $update_sql = "UPDATE search_history SET datetime = NOW() WHERE history = ? AND mail = ?";
                        $update_stmt = $conn->prepare($update_sql);
                        $update_stmt->bind_param("ss", $search_query, $personLogedIn);
                        $update_stmt->execute();
                    } else {
                        // Insert a new row with the search query and current date and time
                        $insert_sql = "INSERT INTO search_history (history, mail, datetime) VALUES (?, ?, NOW())";
                        $insert_stmt = $conn->prepare($insert_sql);
                        $insert_stmt->bind_param("ss", $search_query, $personLogedIn);
                        $insert_stmt->execute();
                    }

                    $found = true;

                    $folderName = "Offline Search/text/" . $queryFolder;

                    // Define the path to the gpt.txt file
                    $gptFilePath = $folderName . "/gpt.txt";

                    if (file_exists($gptFilePath)) {
                        // Read the content from the gpt.txt file
                        $gptContent = file_get_contents($gptFilePath);

                        // Encode HTML entities in the escaped content
                        $encodedContent = htmlentities($gptContent);

                        // Sanitize the gptContent by escaping special characters and ensuring valid JavaScript syntax
                        $escapedContent = str_replace(["\\", "'", "\""], ["\\\\", "\\'", "\\\""], $encodedContent);
                        $escapedContentWithLineBreaks = nl2br($encodedContent);


                        // Add the <div> wrapper to the content
                        $GPT = "<div class='gptresult'>" . $escapedContentWithLineBreaks . "</div>";

                        // Create the script to display the content
                        $script = "<script>
                            var box = document.getElementById('content');
                            box.innerHTML = `" . $GPT . "`;
                            </script>";

                        // Output the script
                        echo $script;
                    }

                    // Define the path to the site.txt file
                    $siteFilePath = $folderName . "/site.txt";

                    if (file_exists($siteFilePath)) {
                        // Read the contents of the site.txt file
                        $siteContent = file_get_contents($siteFilePath);

                        // Add the <div> wrapper to the content
                        $site = "<div class='web-div'>" . $siteContent . "</div>";

                        // Generate the JavaScript code
                        $scriptCode = "
                         <script>
                         var box = document.getElementById('content');
                         box.innerHTML += " . json_encode($site) . ";
                         </script>
                            ";

                        // Display the JavaScript code
                        echo $scriptCode;

                    }
                    break;
                }
            }
        }
        if (!$found) {
            //check if internet is available or not..
            if (checkInternetConnection()) { // Internet connection is available
    
                //insert for search history
                $check_sql = "SELECT * FROM search_history WHERE history = ? AND mail = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("ss", $search_query, $personLogedIn);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                // Check if a record already exists with the same search query and email
                if ($check_result->num_rows > 0) {
                    // Update the datetime column to current date and time
                    $update_sql = "UPDATE search_history SET datetime = NOW() WHERE history = ? AND mail = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ss", $search_query, $personLogedIn);
                    $update_stmt->execute();
                } else {
                    // Insert a new row with the search query and current date and time
                    $insert_sql = "INSERT INTO search_history (history, mail, datetime) VALUES (?, ?, NOW())";
                    $insert_stmt = $conn->prepare($insert_sql);
                    $insert_stmt->bind_param("ss", $search_query, $personLogedIn);
                    $insert_stmt->execute();
                }


                $qry = $conn->query("insert into text_result (folder_name) values ('" . $queryFolder . "')");

                $folderName = "Offline Search/text/" . $queryFolder;

                // Create the directory if it doesn't exist
                mkdir($folderName, 0777, true);

                //CHATGPT RESULT
    
                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://simple-chatgpt-api.p.rapidapi.com/ask",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                        'question' => $search_query
                    ]),
                    CURLOPT_HTTPHEADER => [
                        "X-RapidAPI-Host: simple-chatgpt-api.p.rapidapi.com",
                        "X-RapidAPI-Key: 6ac576a763msh5b272dc87b2386dp12103cjsn818ded80ce1c",
                        "content-type: application/json"
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);
                if ($err) {
                    echo "<script>
                        var box = document.getElementById('content');
                        box.innerHTML = '" . 'cURL Error #:' . $err . "';
                     </script>";
                } else {
                    $data = json_decode($response, true);
                    $message = $data['answer'];

                    //handling no answers..
                    $nonAnswerTexts = array(
                        "I'm sorry, but I don't understand what you are asking for",
                        "As of my knowledge",
                        "I'm sorry",
                        "Sorry, your input is not clear. ",
                        "I am an AI language model",
                        "As an AI language model",
                        "I apologize for the",
                        "I'm really sorry if I did something to upset you, but I'm here to provide assistance and support within my programming capabilities",
                        "If there's something specific you'd like help with, please let me know, and I'll do my best to assist you.",
                        " I'd be happy to help you ",
                        "I'm happy to help you",
                        "I'm happy to assist you",
                        "I'm",
                        "I ",
                        "HTML code ",
                        "CSS code",
                        "CSS:",
                        "HTML:",
                        "You can add this",
                        " Sure! Here is an example",
                        "Sure ",
                        " I will need some information ",
                        " Please provide ",
                        "Please provide the above details",
                        "I will"
                    );

                    $foundNonAnswer = false;
                    foreach ($nonAnswerTexts as $text) {
                        if (strpos($message, $text) !== false || strpos($message, $text) === 0) {
                            $foundNonAnswer = true;
                            break;
                        }
                    }
                    if ($foundNonAnswer) {
                        // Handle the case when $message contains a non-answer
                    } else {
                        // Handle the case when $message does not contain a non-answer
    
                        $GPT = "<div class='gptresult'> $message </div>";

                        // Define the path to the gpt.txt file
                        $gptFilePath = $folderName . "/gpt.txt";

                        $GPT = nl2br($GPT);

                        // Write the message to the gpt.txt file
                        file_put_contents($gptFilePath, $message);


                        echo "<script>
                                     var box = document.getElementById('content');
                                     box.innerHTML = " . json_encode($GPT) . ";
                                     </script>";
                    }
                }



                // GOOGLE WEBSITE
                $curl = curl_init();
                
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://real-time-web-search.p.rapidapi.com/search?q=$search_query&limit=100",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "X-RapidAPI-Host: real-time-web-search.p.rapidapi.com",
                        "X-RapidAPI-Key: 6679f8a3e0msh4b1b072b1ff8269p156b63jsn188a2ec40b37"
                    ],
                ]);
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                
                curl_close($curl);
                if ($err) {
                    echo "<script>
                        var box = document.getElementById('content');
                        box.innerHTML = '" . 'cURL Error #:' . $err . "';
                     </script>";
                } else {
                    $datas = json_decode($response, true);
                    $contents = '';
                    // Define the path to the site.txt file
                    $siteFilePath = $folderName . "/site.txt";
                    // Initialize the $fileContent variable
                    $fileContent = '';

                    foreach ($datas['data'] as $result) {
                        $title = "<h2 class='result-title'>" . $result['title'] . "</h2>";
                        $url = "<p><a target='_blank' href='" . $result['url'] . "'>" . $result['url'] . "</a></p>";
                        $description = "<p>" . $result['snippet'] . "</p>";


                        // Write the three results to the site.txt file
                        $fileContent .= $title . PHP_EOL . $url . PHP_EOL . $description . PHP_EOL . PHP_EOL;

                        $contents .= "<div>
                                                                $title
                                                                $url
                                                                $description
                                                                <hr>
                                                            </div>";
                    }
                    file_put_contents($siteFilePath, $fileContent);

                    echo "<script>
                                            var box = document.getElementById('content');
                                            box.innerHTML += " . json_encode($contents) . ";
                                             </script>";

                }



            } else {
                // No internet connection
                echo "<div id='no-internet-div'>
                        <label id='no-internet'>No internet</label><br>
                        <label id='try'>Try:</label><br>
        
                            <p id='p1'>Checking the network cables, modem, and router</p>
                            <p id='p2'>Reconnecting to Wi-Fi</p>

                        <label id='no_net_error'>ERR_INTERNET_DISCONNECTED</label><br><br>
                        </div>
                        ";
            }
        }
        $conn->close();
    }



    //image search
    if (isset($_POST['image'])) {
        echo "<script>
        const tabButtons = document.querySelectorAll('.form-btns');
        tabButtons.forEach(button => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
        });
        document.getElementById('image').classList.add('active');
        </script>";


        // Connect to database
        $conn = new mysqli("localhost", "root", "", "project");

        // Check connection
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            exit();
        }

        //check if query exists in database
        $result = $conn->query("SELECT folder_name FROM `image_result`");
        $found = false;

        // Check if the query returned at least one value
        if ($result->num_rows > 0) {
            // Iterate through the rows
            while ($row = $result->fetch_assoc()) {
                $query = $row['folder_name'];

                // Check if the current query matches the search query
                if ($query === $queryFolder) {

                    //insert for search history
                    $check_sql = "SELECT * FROM search_history WHERE history = ? AND mail = ?";
                    $check_stmt = $conn->prepare($check_sql);
                    $check_stmt->bind_param("ss", $search_query, $personLogedIn);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();

                    // Check if a record already exists with the same search query and email
                    if ($check_result->num_rows > 0) {
                        // Update the datetime column to current date and time
                        $update_sql = "UPDATE search_history SET datetime = NOW() WHERE history = ? AND mail = ?";
                        $update_stmt = $conn->prepare($update_sql);
                        $update_stmt->bind_param("ss", $search_query, $personLogedIn);
                        $update_stmt->execute();
                    } else {
                        // Insert a new row with the search query and current date and time
                        $insert_sql = "INSERT INTO search_history (history, mail, datetime) VALUES (?, ?, NOW())";
                        $insert_stmt = $conn->prepare($insert_sql);
                        $insert_stmt->bind_param("ss", $search_query, $personLogedIn);
                        $insert_stmt->execute();
                    }


                    $found = true;

                    $htmlFilePath = "Offline Search/image/$queryFolder/images.html";

                    if (file_exists($htmlFilePath)) {
                        // Read the content of the HTML file
                        $fileContent = file_get_contents($htmlFilePath);
                        // echo $fileContent;
    
                        // Extract the image and title information using regex
                        preg_match_all('/<p>(.*?)<\/p>\s*<p>(.*?)<\/p>.*?<img alt="(.*?)"[^>]*src="(.*?)"[^>]*>/s', $fileContent, $matches, PREG_SET_ORDER);

                        $pictures = '';

                        // Loop through the matched results
                        foreach ($matches as $match) {
                            $title = $match[1];
                            $link = $match[2];
                            $alt = $match[3]; // Store the alt attribute value in a separate variable
                            $imageSrc = $match[4]; // Store the image source URL
    
                            // Wrap the image and title in a div
                            $pictures .= "<div class='imgdiv'>
                                    <p>$title</p>
                                    <div class='image-container'><a href='$link' target='_blank'><img src='$imageSrc' alt='$alt'></a></div>
                                    </div>";
                        }
                        $pictures = "<div id='all-image-container'>$pictures</div>";

                        // Display the images and titles using JavaScript
                        echo "<script>
                            var box = document.getElementById('content');
                            box.innerHTML += " . json_encode($pictures) . ";
                            </script>";

                    }
                }
            }
        }
        if (!$found) {
            if (checkInternetConnection()) {
                // Internet connection is available
    
                $check_sql = "SELECT * FROM search_history WHERE history = ? AND mail = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("ss", $search_query, $personLogedIn);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                // Check if a record already exists with the same search query and email
                if ($check_result->num_rows > 0) {
                    // Update the datetime column to current date and time
                    $update_sql = "UPDATE search_history SET datetime = NOW() WHERE history = ? AND mail = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ss", $search_query, $personLogedIn);
                    $update_stmt->execute();
                } else {
                    // Insert a new row with the search query and current date and time
                    $insert_sql = "INSERT INTO search_history (history, mail, datetime) VALUES (?, ?, NOW())";
                    $insert_stmt = $conn->prepare($insert_sql);
                    $insert_stmt->bind_param("ss", $search_query, $personLogedIn);
                    $insert_stmt->execute();
                }


                $qry = $conn->query("insert into `image_result` (folder_name) values ('" . $queryFolder . "')");


                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://real-time-image-search.p.rapidapi.com/search?query=$search_query&region=us",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "X-RapidAPI-Host: real-time-image-search.p.rapidapi.com",
                        "X-RapidAPI-Key: 6679f8a3e0msh4b1b072b1ff8269p156b63jsn188a2ec40b37"
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "<script>
                        var box = document.getElementById('content');
                        box.innerHTML = '" . 'cURL Error #:' . $err . "';
                     </script>";
                } else {
                    $json = json_decode($response, true); // decode the JSON response
    
                    $folderName = "Offline Search/image/" . $queryFolder;
                    mkdir($folderName, 0777, true);

                    // Define the path to the image file
                    $imgFilePath = $folderName . "/images.html";
                    // Initialize the $fileContent variable
                    $fileContent = '';

                    $pictures = '';


                    if (isset($json['data'])) { // check if the 'image_results' key exists in the response
                        $imageResults = $json['data']; // get the image results array
                        foreach ($imageResults as $imageResult) { // loop through the image results
    
                            $title = "<p>" . $imageResult['title'] . "</p>";
                            $link = "<p>" . $imageResult['source_url'] . "</p>";
                            $image = '<div class="image-container"><a href="' . $imageResult['source_url'] . '" target="_blank"><img alt="Image" src="' . $imageResult['thumbnail_url'] . '"></a></div>';

                            $pictures .= "<div class='imgdiv'>
                                $title
                                $image
                                </div>";

                            // Append the title and image HTML code to the file content
                            $fileContent .= $title . $link . $image . "<br><br>";
                        }

                        // Wrap the file content in a basic HTML structure
                        $fileContent = "<html><body>" . $fileContent . "</body></html>";

                        // Write the images and titles to the HTML file
                        file_put_contents($imgFilePath, $fileContent);

                        $pictures = "<div id='all-image-container'>$pictures</div>";

                        echo "<script>
                                    var box = document.getElementById('content');
                                    box.innerHTML += " . json_encode($pictures) . ";
                                     </script>";
                    }
                }

            } else {
                // No internet connection
                // Handle the error or display a message to the user
                echo "<div id='no-internet-div'>
                        <label id='no-internet'>No internet</label><br>
                        <label id='try'>Try:</label><br>
        
                            <p id='p1'>Checking the network cables, modem, and router</p>
                            <p id='p2'>Reconnecting to Wi-Fi</p>

                        <label id='no_net_error'>ERR_INTERNET_DISCONNECTED</label><br><br>
                        </div>
                        ";
            }

        }

        $conn->close();
    }

    //VIDEO
    if (isset($_POST['video'])) {
        echo "<script>
        const tabButtons = document.querySelectorAll('.form-btns');
        tabButtons.forEach(button => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
        });
        document.getElementById('video').classList.add('active');
        </script>";

        $conn = new mysqli("localhost", "root", "", "project");

        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            exit();
        }
        if (checkInternetConnection()) {
            $check_sql = "SELECT * FROM search_history WHERE history = ? AND mail = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ss", $search_query, $personLogedIn);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            // Check if a record already exists with the same search query and email
            if ($check_result->num_rows > 0) {
                // Update the datetime column to current date and time
                $update_sql = "UPDATE search_history SET datetime = NOW() WHERE history = ? AND mail = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $search_query, $personLogedIn);
                $update_stmt->execute();
            } else {
                // Insert a new row with the search query and current date and time
                $insert_sql = "INSERT INTO search_history (history, mail, datetime) VALUES (?, ?, NOW())";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("ss", $search_query, $personLogedIn);
                $insert_stmt->execute();
            }

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://youtube-search-results.p.rapidapi.com/youtube-search/?q=$search_query",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: youtube-search-results.p.rapidapi.com",
                    "X-RapidAPI-Key: 6ac576a763msh5b272dc87b2386dp12103cjsn818ded80ce1c"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "<script>
                var box = document.getElementById('content');
                box.innerHTML = '" . 'cURL Error #:' . $err . "';
             </script>";
            } else {
                $json = json_decode($response, true); // decode the JSON response
    
                $videos = '';

                if (isset($json['items'])) {
                    $videoResults = $json['items'];
                    foreach ($videoResults as $video) {
                        if (isset($video['title']) && isset($video['url']) && isset($video['duration']) && isset($video['uploadedAt']) && isset($video['views'])) {

                            $title = "<h2 class='video-title'>" . $video['title'] . "</h2>";
                            $url = "<p class='video-link'><a target='_blank' href='" . $video['url'] . "'>" . $video['url'] . "</a></p>";

                            $duration = "<label class='video-duration'>Duration: " . $video['duration'] . "</label><br>";
                            $uploadedDate = "<label class='video-date'>Uploaded at: " . $video['uploadedAt'] . "</label><br>";
                            $views = $video['views'];
                            $formattedViews = '';

                            if ($views >= 1000000) {
                                $formattedViews = number_format($views / 1000000, 1) . 'm';
                            } elseif ($views >= 1000) {
                                $formattedViews = number_format($views / 1000, 0) . 'k';
                            } else {
                                $formattedViews = $views;
                            }
                            $views = "<label class='video-views'>Views: " . $formattedViews . "</label><br>";

                            $thumbnail = $video['bestThumbnail']['url'];
                            $image = '<div class="video-image"><img alt="Image" src="' . $thumbnail . '"></a></div>';

                            $videos .= "<div class='video-div'>
                    $title
                    $url
                    $duration
                    $uploadedDate
                    $views
                    $image
                    </div>";
                        }
                    }
                    echo "<script>
                    var box = document.getElementById('content');
                    box.innerHTML += " . json_encode($videos) . ";
                     </script>";
                }
            }
        } else {
            // No internet connection
            echo "<div id='no-internet-div'>
             <label id='no-internet'>No internet</label><br>
             <label id='try'>Try:</label><br>

                 <p id='p1'>Checking the network cables, modem, and router</p>
                 <p id='p2'>Reconnecting to Wi-Fi</p>

             <label id='no_net_error'>ERR_INTERNET_DISCONNECTED</label><br><br>
             </div>
             ";
        }
        $conn->close();
    }



    // NEWS
    if (isset($_POST['news'])) {
        echo "<script>
        const tabButtons = document.querySelectorAll('.form-btns');
        tabButtons.forEach(button => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
        });
        document.getElementById('news').classList.add('active');
        </script>";

        // Connect to database
        $conn = new mysqli("localhost", "root", "", "project");

        // Check connection
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            exit();
        }
        if (checkInternetConnection()) {
            // Internet connection is available
    
            $check_sql = "SELECT * FROM search_history WHERE history = ? AND mail = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ss", $search_query, $personLogedIn);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            // Check if a record already exists with the same search query and email
            if ($check_result->num_rows > 0) {
                // Update the datetime column to current date and time
                $update_sql = "UPDATE search_history SET datetime = NOW() WHERE history = ? AND mail = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $search_query, $personLogedIn);
                $update_stmt->execute();
            } else {
                // Insert a new row with the search query and current date and time
                $insert_sql = "INSERT INTO search_history (history, mail, datetime) VALUES (?, ?, NOW())";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("ss", $search_query, $personLogedIn);
                $insert_stmt->execute();
            }

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://google-news13.p.rapidapi.com/search?keyword=$search_query&lr=en-US",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: google-news13.p.rapidapi.com",
                    "X-RapidAPI-Key: fbda871c65msh9989a4c63ce0984p12f5f1jsne0264bf60b97"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "<script>
                var box = document.getElementById('content');
                box.innerHTML = '" . 'cURL Error #:' . $err . "';
             </script>";
            } else {
                $json = json_decode($response, true); // decode the JSON response
    
                $all_news = '';

                if (isset($json['items'])) { // check if the 'image_results' key exists in the response
                    $newsResults = $json['items']; // get the image results array
                    foreach ($newsResults as $news) { // loop through the image results
    
                        $title = "<h2 class='news-title'>" . $news['title'] . "</h2>";
                        $snippet = "<p class='news-snippet'>" . $news['snippet'] . "</p>";
                        $publisher = "<label class='news-publisher'>Publisher: " . $news['publisher'] . "</label><br>";

                        $timestamp = $news['timestamp'] / 1000; // Convert milliseconds to seconds
    
                        // Create a DateTime object using the converted timestamp
                        $date = new DateTime();
                        $date->setTimestamp($timestamp);

                        // Format the date as desired
                        $formattedTime = $date->format("F j, Y, g:i a");

                        // Create the time label with the formatted date
                        $time = "<label class='news-date'>Date: " . $formattedTime . "</label>";


                        $newsUrl = "<p class='news-url'><a target='_blank' href='" . $news['newsUrl'] . "'>" . $news['newsUrl'] . "</a></p>";

                        $thumbnail = $news['images']['thumbnail'];
                        $original = $news['images']['original'];

                        $image = '<div class="news-image"><a target="_blank" href="' . $original . '" target="_blank"><img alt="Image" src="' . $thumbnail . '"></a></div>';

                        $all_news .= "<div class='news'>
                    $title
                    $newsUrl
                    $snippet
                    $publisher
                    $time
                    $image
                    </div>";

                    }

                    echo "<script>
                        var box = document.getElementById('content');
                        box.innerHTML += " . json_encode($all_news) . ";
                         </script>";
                }
            }
        } else {
            // No internet connection
            echo "<div id='no-internet-div'>
                        <label id='no-internet'>No internet</label><br>
                        <label id='try'>Try:</label><br>
        
                            <p id='p1'>Checking the network cables, modem, and router</p>
                            <p id='p2'>Reconnecting to Wi-Fi</p>

                        <label id='no_net_error'>ERR_INTERNET_DISCONNECTED</label><br><br>
                        </div>
                        ";
        }
        $conn->close();
    }

    ?>
</body>

</html>