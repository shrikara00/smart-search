<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="ajax.js"></script>
    <link rel="icon" href="icons/icons8-search-100.png" type="image/png">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <?php
    session_start();
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['pasw'];
        $conn = new mysqli("localhost", "root", "", "project");
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            exit();
        }
        $stmt = $conn->prepare("SELECT * FROM `user/admin` WHERE `mail` = ? AND `password`= ? ");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "<script>alert('Login successful');</script>";
            $_SESSION['email'] = $email;
            $_SESSION['login'] = true;
            $adminEmails = array(
                "shri.karantha@gmail.com",
                "nagarajkamth08@gmail.com",
                "saliyandarshan639@gmail.com"
            );
            if (in_array($email, $adminEmails)) {
                $_SESSION['isAdmin'] = true;
                $_SESSION['isUser'] = false;
                echo "<script>setTimeout(function() { window.location.href = 'adminIndex.php?login_success=true'; }, 1000);</script>";
                exit();
            } else {
                $_SESSION['isAdmin'] = false;
                $_SESSION['isUser'] = true;
                echo "<script>setTimeout(function() { window.location.href = 'index.php?login_success=true'; }, 1000);</script>";
                exit();
            }
        }
        $stmt->close();
        $conn->close();
    }
    ?>
    <div class="box">
        <div class="header">
            <h2>Login</h2>
        </div>
        <form class="myform" id="myform" method="post" action="">
            <div class="form-control">
                <input id="email" type="email" name="email" required>
                <span id="e_mail">Email Id</span>
            </div>
            <div class="form-control">
                <input id="pasw" type="password" name="pasw" required onchange="checkerror()">
                <span id="pas">Password</span>
                <img id="pass-icon" class="pass-icon" src="icons/eye.png" onclick="showpass()"></img>
            </div>
            <small class="error" id="error"></small>
            <input type="submit" id="submitBtn" class="submit" form="myform" value="Submit" name="submit">
            <p>New user?
                <a id="reg" href="register.php">Register here</a>
            </p>
        </form>
    </div>
    <script>

        function checkerror() {
            var emailInput = document.getElementById("email");
            var passwordInput = document.getElementById("pasw");
            var errorMessageElement = document.getElementById("error");

            // AJAX request to validate credentials
            $.ajax({
                url: 'logcheck.php',
                type: 'POST',
                data: {
                    pass: passwordInput.value,
                    email: emailInput.value
                },
                success: function (response) {
                    if (response === 'exists') {
                        errorMessageElement.textContent = "";
                        emailInput.setCustomValidity('');
                    } else {
                        emailInput.setCustomValidity('Please check your email and password');
                        errorMessageElement.textContent = "Invalid username or password";
                    }
                }
            });
        }

        var a = 0;
        function showpass() {
            if (a === 0) {
                document.getElementById('pasw').type = 'password';
                document.getElementById('pass-icon').src = "icons/eye.png";
                a = 1;
            } else {
                document.getElementById('pasw').type = 'text';
                document.getElementById('pass-icon').src = "icons/hidden.png";
                a = 0;
            }
        }

        const email = document.getElementById('email');

        function validateEmail() {
            const emailValue = email.value.trim();

            if (emailValue != '') {
                email.classList.add('email-invalid');
            } else {
                email.classList.remove('email-invalid');
            }
        }

        email.addEventListener('input', validateEmail);
        email.addEventListener('blur', validateEmail);


    </script>
</body>

</html>