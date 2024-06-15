<?php
session_start();
include "koneksi.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the username exists in the database
    $check = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password']))  {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Check user role
            $role = $user['role']; // Assuming 'role' is the column name
            if ($role == 'admin') {
                header("Location: AdminKendaraan.php");
                exit;
            } elseif ($role == 'penyewa') {
                header("Location: dashboard.php");
                exit;
            } else {
                header("Location: landing-page.html");
            }
        } else {
            $error_message = "<p>Incorrect password. Please try again.</p><br>";
        }
    } else {
        $error_message = "<p>No account found with that username. Please try again.</p><br>";
    }
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - K2 RentCar</title>
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
                        <h5>Username</h5>
                        <input 
                            type="text" 
                            name="username" 
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
                        <h5>Password</h5>
                        <input 
                            type="password" 
                            name="password" 
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
                    <button 
                            type="submit" 
                            name="login" 
                            class="w-40 py-2 rounded-lg bg-white text-dongker mx-auto hover:shadow-lg hover:opacity-50 duration-300 ease-in-out" formaction="#">Login</button>
                </div>
                <div class="w-full mt-10">
                    <p class="font-medium text-white text-center text-xs">Belum punya akun? <span class="text-dongker">_______</span> <a href="sign-up.php" target="_blank" class="font-bold text-merah hover:text-white">Sign Up.</a></p>
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

