<?php
session_start();
$message = '';

$secretKey = "6LdKuYEqAAAAAOYHMUlTF0s1rgnfMh9DP3PiZTcj";  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if ($captchaResponse) {
        $verifyCaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse");
        $captchaSuccess = json_decode($verifyCaptcha);

        if ($captchaSuccess->success) {
            // CAPTCHA verified
            if ($username === 'admin' && $password === 'password') {
                $_SESSION['logged_in'] = true;
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $message = 'Incorrect username or password.';
            }
        } else {
            $message = 'CAPTCHA verification failed. Please try again.';
        }
    } else {
        $message = 'Please complete the CAPTCHA.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #000000, #434343);
            color: #fff;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 350px;
        }

        .login-container .icon {
            font-size: 80px;
            color: #fff;
            margin-bottom: 20px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-weight: 500;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .input-container input {
            width: 100%;
            padding: 10px 40px; /* Space for icons */
            border: none;
            border-radius: 5px;
            outline: none;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 14px;
            box-sizing: border-box;
        }

        .input-container input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-container .icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
        }

        .show-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            cursor: pointer;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #4CAF50;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #45a049;
        }

        .g-recaptcha {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="icon">
        <i class="fas fa-user-circle"></i>
    </div>
    <h2>Login</h2>
    <?php if ($message): ?>
        <div class="error-message"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="input-container">
            <i class="fas fa-user icon"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-container">
            <i class="fas fa-lock icon"></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span class="show-password" onclick="togglePassword()">Show</span>
        </div>

    
        <div class="g-recaptcha" data-sitekey="6LdKuYEqAAAAAADQAv_Ye5tgBSxr6LpdzFHCvs0O"></div>

        <button type="submit">Login</button>
    </form>
</div>

<!-- Google reCAPTCHA Script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleText = document.querySelector('.show-password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleText.textContent = 'Hide';
        } else {
            passwordField.type = 'password';
            toggleText.textContent = 'Show';
        }
    }
</script>


</body>
</html>
