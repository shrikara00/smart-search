<?php
    //Admin or user check
    session_start();
    $personLogedIn = $_SESSION['email'];

?>    

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <style>
    /* CSS styles */
    body {
      font-family: Braah;
      margin: 0;
      padding: 20px;
      background-image:linear-gradient( rgba(148, 1, 246, 0.512), purple);
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
      background-size: cover;
      overflow: hidden;
    }

    .QueryQuest{
      font-size: 30px;
      color: black;
      font: bolder;
      font-weight: 500;


    }

    p {
      margin-top: 2%;
      font-size: 1.3em;
      color: #fff;
      font-weight: 50;
    }

    h1{
        letter-spacing: -.02em;
        margin: auto;
        font-weight: 800;
        font-size: 3em;
        word-spacing: 30px;
    }

    h5{
        letter-spacing: -.02em;
        font-size: 1.5em;
        font-weight: 800;

    }

    .container {
      max-width: 800px;
      margin: 0 auto;
    }

    button {
      top: 10px;
      margin-left: 90%;
      width: 50px;
      height: 40px;
      position: absolute;
      background: transparent;
      cursor: pointer;
  
    }

    img{
      height: 30px;
    }

  </style>
</head>
<body>

    <p class="QueryQuest">QueryQuest</p>
    <button class="icons" id="home"><img src="Google icons/home_FILL1_wght400_GRAD0_opsz48.png"></button>
  <div class="container">
    <h1>Your personal data is nobody's business.</h1>
    <p>Welcome to our search engine! We strive to provide you with the best search experience and help you find what you're looking for quickly and efficiently.</p>
    <p>At our search engine, we prioritize user privacy and data security. We have implemented robust measures to protect your personal information and ensure that your searches remain private.</p>
    <p>If you have any feedback, questions, or suggestions, please don't hesitate to contact us. We value your input and are continuously working to improve our search engine based on user needs and preferences.</p>
    <h5>Thank you for choosing our search engine!</h5>
  </div>
  <script>
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
</body>
</html>
