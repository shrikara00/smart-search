<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icons/icons8-search-100.png" type="image/png">
    <link rel="stylesheet" href="register.css">
    <script src="ajax.js"></script>
    <title>SignUp</title>
</head>

<body>
    <?php
    session_start();
    // Check if form is submitted
    if (isset($_POST["submit"])) {
        // Get form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];

        // Connect to database
        $conn = new mysqli("localhost", "root", "", "project");

        // Check connection
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            exit();
        }

        // Check if email already exists in the database
        $sql = "SELECT * FROM `user/admin` WHERE `mail` = '" . $email . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Email already exists, show error message
    
        } else {
            // Check if phone number already exists in the database
            $sql = "SELECT * FROM `user/admin` WHERE `phone` = " . $phone . "";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Phone number already exists, show error message
            } else {
                // Insert record into database
                $sql = "INSERT INTO `user/admin` (`mail`,`uname`,`password`,`phone`) VALUES ('" . $email . "', '" . $name . "', '" . $password . "', " . $phone . ")";

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['registration_success'] = true;
                    echo "<script>alert('Registration successful');</script>";
                    echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 1000);</script>";
                    exit();
                } else {
                    echo "Error adding record: " . $conn->error;
                }
            }
        }

        // Close connection
        $conn->close();
    }
    ?>
    <div class="box">
        <div class="header">
            <h2>registration</h2>
        </div>

        <form class="myform" id="myform" method="post" action="#">

            <div class="form-control">
                <input id="name" type="text" name="name" pattern="^[A-Za-z\s]+$" required>
                <span id="name_span">Name</span>
            </div>

            <div class="form-control">
                <input id="email" type="email" name="email" required oninput="checkEmailExists()">
                <span id="mail_span">Email</span>
                <small class="error" id="e-error"></small>
            </div>

            <div class="form-control">
                <input id="pasw" type="password" name="password" required minlength="8">
                <span id="pas_span">Password</span>
                <img id="pass-icon" class="pass-icon" src="icons/eye.png" onclick="showpass()"></img>
            </div>

            <div class="form-control">
                <input id="cpasw" type="password" name="cpassword" required oninput="checkPasswordMatch()">
                <span id="cpas_span">Confirm Password</span>
                <img id="cpass-icon" class="pass-icon" src="icons/eye.png" onclick="showCpass()"></img>
            </div>

            <div class="form-control">
                <input id="phone" type="tel" name="phone" required minlength="10" maxlength="10" pattern="[0-9]{10}" oninput="checkPhoneExists()">
                <span id="phone_span">Phone number</span>
                <small class="error" id="p-error"></small>
            </div>

            <input type="submit" id="submit" class="submit" form="myform" value="Submit" name="submit">

            <p>Have an account?
                <a id="reg" href="login.php">Login here</a>
            </p>
        </form>
    </div>

    <script>

        // Email exists checking
        function checkEmailExists() {
            var emailInput = document.getElementById("email");
            var errorMessageElement = document.getElementById("e-error");

            // AJAX request to validate credentials
            $.ajax({
                url: 'emailCheck.php',
                type: 'POST',
                data: {
                    email: emailInput.value
                },
                success: function (response) {
                    if (response === 'exists') {
                        emailInput.setCustomValidity('Please check your Email-Id');
                        errorMessageElement.textContent = "Email already exists";
                    } else {
                        errorMessageElement.textContent = "";
                        emailInput.setCustomValidity('');
                    }
                }
            });
        }
        // Phone number exists checking
        function checkPhoneExists() {
            var phoneInput = document.getElementById("phone");
            var errorMessageElement = document.getElementById("p-error");

            // AJAX request to validate credentials
            $.ajax({
                url: 'phoneCheck.php',
                type: 'POST',
                data: {
                    phone: phoneInput.value
                },
                success: function (response) {
                    if (response === 'exists') {
                        phoneInput.setCustomValidity('Please check your phone number');
                        errorMessageElement.textContent = "Phone number already exists";
                    } else {
                        errorMessageElement.textContent = "";
                        phoneInput.setCustomValidity('');
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
        var b;
        function showCpass() {
            if (b == 1) {
                document.getElementById('cpasw').type = 'password';
                document.getElementById('cpass-icon').src = "icons/eye.png";
                b = 0;
            }
            else {
                document.getElementById('cpasw').type = 'text';
                document.getElementById('cpass-icon').src = "icons/hidden.png";
                b = 1
            }
        }

        const inputFields = document.querySelectorAll('input');

        function validateInputFields() {
            inputFields.forEach(inputField => {
                const inputValue = inputField.value.trim();

                if (inputValue !== '') {
                    inputField.classList.add('input-invalid');
                } else {
                    inputField.classList.remove('input-invalid');
                }
            });
        }

        inputFields.forEach(inputField => {
            inputField.addEventListener('input', validateInputFields);
            inputField.addEventListener('blur', validateInputFields);
        });

        function checkPasswordMatch() {
            var pass = document.getElementById('pasw');
            var cpass = document.getElementById('cpasw');
            if (pass.value != cpass.value) {
                cpass.setCustomValidity('Password must match');
            } else {
                cpass.setCustomValidity('');
            }
        }
    </script>
</body>

</html>