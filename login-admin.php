<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['login_user'])) {
    header("location: home.html");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; 

    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);


    if ($row) {
        if ($password == $row['password']) { 
            $_SESSION['login_user'] = $username;
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            header("location: home.html");
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="login-admin.css">
    <link href='https://cdn.boxicons.com/3.0.3/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <style>
       
        .error-msg {
            color: #ff4d4d;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
            background: rgba(0,0,0,0.5);
            padding: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1> Login </h1>
            
            <?php if($error != "") { echo "<div class='error-msg'>$error</div>"; } ?>

            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bx-user'></i> 
            </div>
             <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bx-lock'></i> 
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox">Remember Me</label>
                <a href="#">Forgot Password? </a>
            </div>

            <button type="submit" class="btn">Login</button>
            <div class="register-link">
                <p>Don't have an account? <a href="register-admin.html">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>