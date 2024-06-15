<?php
session_start();
include "koneksi.php";

$error_message = "";
$fullname = "";
$phone_number = "";
$name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $fullname = trim($_POST['fullname']);
    $phone_number = trim($_POST['nohp']);
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    $repass = trim($_POST['repassword']);

    // Check if username already exists
    $check_username = "SELECT * FROM users WHERE username = ?";
    $stmt_username = $conn->prepare($check_username);
    $stmt_username->bind_param("s", $name);
    $stmt_username->execute();
    $res_username = $stmt_username->get_result();

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email = ?";
    $stmt_email = $conn->prepare($check_email);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $res_email = $stmt_email->get_result();

    // Check if phone number already exists
    $check_phone = "SELECT * FROM users WHERE phone_number = ?";
    $stmt_phone = $conn->prepare($check_phone);
    $stmt_phone->bind_param("s", $phone_number);
    $stmt_phone->execute();
    $res_phone = $stmt_phone->get_result();
    
    if ($res_username->num_rows > 0) {
        $error_message = "<p>This username is used, try another one please!</p><br>";
    } elseif ($res_phone->num_rows > 0) {
        $error_message = "<p>This phone number is used, try another one please!</p><br>";
    } elseif ($res_email->num_rows > 0) {
        $error_message = "<p>This email is used, try another one please!</p><br>";
    } else {
        if ($pass === $repass) {
            // Hash the password don't use it yet
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            // Insert the new user into the database
            $sql = "INSERT INTO users (fullname, phone_number, username, email, password) VALUES (?, ?, ?, ?, ?)";
            
            // Close previous statements
            $stmt_email->close();
            $stmt_phone->close();
            
            // Prepare the insert statement
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $fullname, $phone_number, $name, $email, $hashed_password);

            if ($stmt->execute()) {
                // Redirect to the success page
                header("Location: sign-up-success.html");
                exit;
            } else {
                $error_message = "<p>There was an error registering your account. Please try again.</p><br>";
            }
        } else {
            $error_message = "<p>Passwords do not match.</p><br>";
        }
    }
    // Close the statement
    $stmt_email->close();
    $stmt_phone->close();
    if (isset($stmt)) {
        $stmt->close();
    }
}
// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - K2 RentCar</title>
    <link href="src/output.css" rel="stylesheet">
    <link rel="icon" href="src/assets/img/k2rentcar.png">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
</head> 
<style>
    .i {
        color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .i i {
        transition: .3s;
    }

    .input-div>div {
        position: relative;
        height: 45px;
    }

    .input-div>div>h5 {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #ffffff;
        font-size: 18px;
        transition: .3s;
    }

    .input-div:before,
    .input-div:after {
        content: '';
        position: absolute;
        bottom: -2px;
        width: 0%;
        height: 2px;
        background-color: #EB002B;
        transition: .4s;
    }

    .input-div:before {
        right: 50%;
    }

    .input-div:after {
        left: 50%;
    }

    .input-div.focus:before,
    .input-div.focus:after {
        width: 50%;
    }

    .input-div.focus>div>h5 {
        top: -5px;
        font-size: 15px;
    }

    .input-div.focus>.i>i {
        color: #EB002B;
    }
</style> 
<body style="background-image: url(src/assets/img/login/bg-login.jpg);">
    <div class="h-screen flex justify-center items-center">
        <div class="bg-dongker rounded-2xl w-1/3 pt-10 pb-10 px-16 border-merah border-8">
            <form action="#" method="POST">
                <div class="flex font-bold justify-center">
                    <img class="h-36 w-32" src="src/assets/img/login/k2rentcar.png">
                </div>
                <div class="input-div border-b-2 relative grid my-5 py-1 focus:outline-none"
                    style="grid-template-columns: 7% 93%;">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Full Name</h5>
                        <input 
                            type="text" 
                            name="fullname"
                            class="absolute w-full h-full py-2 px-3 outline-none inset-0 text-abu"
                            style="background:none;"
                            value="<?php echo htmlspecialchars($fullname); ?>" 
                            required>
                    </div>
                </div>

                <div class="input-div border-b-2 relative grid my-5 py-1 focus:outline-none"
                    style="grid-template-columns: 7% 93%;">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Username</h5>
                        <input 
                            type="text" 
                            name= "username"
                            class="absolute w-full h-full py-2 px-3 outline-none inset-0 text-abu"
                            style="background:none;"
                            value="<?php echo htmlspecialchars($name); ?>" 
                            required>
                    </div>
                </div>

                <div class="input-div border-b-2 relative grid my-5 py-1 focus:outline-none"
                    style="grid-template-columns: 7% 93%;">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Phone Number</h5>
                        <input 
                            type="text" 
                            name="nohp"
                            class="absolute w-full h-full py-2 px-3 outline-none inset-0 text-abu"
                            style="background:none;"
                            value="<?php echo htmlspecialchars($phone_number); ?>" 
                            required>
                    </div>
                </div>

                <div class="input-div border-b-2 relative grid my-5 py-1 focus:outline-none"
                    style="grid-template-columns: 7% 93%;">
                    <div class="i">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="div">
                        <h5>E-mail</h5>
                        <input 
                            type="text" 
                            name="email"
                            class="absolute w-full h-full py-2 px-3 outline-none inset-0 text-abu"
                            style="background:none;"
                            value="<?php echo htmlspecialchars($email); ?>" 
                            required>
                    </div>
                </div>
                <div class="input-div border-b-2 relative grid my-5 py-1 focus:outline-none"
                    style="grid-template-columns: 7% 93%;">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input 
                            type="password"
                            name="password"
                            class="absolute w-full h-full py-2 px-3 outline-none inset-0 text-abu"
                            style="background:none;">
                    </div>
                </div>
                <div class="input-div border-b-2 relative grid my-5 py-1 focus:outline-none"
                    style="grid-template-columns: 7% 93%;">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Re-enter Password</h5>
                        <input 
                            type="password"
                            name="repassword"
                            class="absolute w-full h-full py-2 px-3 outline-none inset-0 text-abu"
                            style="background:none;">
                    </div>
                </div>
                <div class="text-merah">
                    <?php
                    // Display error message if any
                    if (!empty($error_message)) {
                        echo $error_message;
                    }
                    ?>
                </div>
                <div class="flex justify-center">
                    <button type="submit" name="register" class="w-40 py-2 rounded-lg bg-white text-dongker mx-auto hover:shadow-lg hover:opacity-50 duration-300 ease-in-out"  >Sign Up</button><!--formaction="sign-up-success.html"-->
                </div>
                <div class="w-full mt-10">
                    <p class="font-medium text-white text-center text-xs">Sudah punya akun? <span class="text-dongker">_______</span> <a href="login.php" class="font-bold text-merah hover:text-white">Login.</a></p>
                </div>
            </form> 
        </div>
    </div>
    <script>
        const inputs = document.querySelectorAll("input");


        function addcl() {
            let parent = this.parentNode.parentNode;
            parent.classList.add("focus");
        }

        function remcl() {
            let parent = this.parentNode.parentNode;
            if (this.value == "") {
                parent.classList.remove("focus");
            }
        }


        inputs.forEach(input => {
            input.addEventListener("focus", addcl);
            input.addEventListener("blur", remcl);
        });
    </script>
</body>

</html>

