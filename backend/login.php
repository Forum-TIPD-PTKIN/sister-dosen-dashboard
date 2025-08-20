<?php
session_start();
include "inc/config.php";
if (!isset($_SESSION['login'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">

<link rel="shortcut icon" href="<?=getPengaturan('favicon');?>">
    <title><?=getPengaturan('app_name');?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="assets/login/css/login.css" media="all" rel="stylesheet" type="text/css">  
    <script src="assets/dist/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/login/js/shake.js"></script>
    <script src="assets/login/js/login.js"></script>
    <script src="assets/login/js/jquery.backstretch.min.js"></script>

</head>
<body>
    <div class="centered-login-container">
    <div class="metro-login-card">
        <div class="metro-avatar">
            <img src="<?=getPengaturan('logo');?>" alt="Logo" style="border-radius:50%;background:#fff;object-fit:contain;box-shadow:0 2px 8px rgba(0,0,0,0.10);">
        </div>
        <div class="metro-login-title">Sign in to <?=getPengaturan('app_name');?></div>
        <form class="form-signin">
            <input type="text" id="username" class="form-control" placeholder="Username" required autofocus autocomplete="username">
            <input type="password" id="password" class="form-control" placeholder="Password" required autocomplete="current-password">
            <button class="btn btn-lg btn-login" id="login" type="submit">Sign In</button>
            <div class='invalid' style="display: none"></div>
        </form>
    </div>
</div>
<div class="metro-clock" id="metro-clock"></div>
<script>
function updateMetroClock() {
    var now = new Date();
    var hours = now.getHours().toString().padStart(2, '0');
    var minutes = now.getMinutes().toString().padStart(2, '0');
    var seconds = now.getSeconds().toString().padStart(2, '0');
    var day = now.toLocaleDateString('en-US', { weekday: 'long' });
    var date = now.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });
    document.getElementById('metro-clock').innerHTML =
        hours + ':' + minutes + ':' + seconds +
        '<div class="metro-date">' + day + ', ' + date + '</div>';
}
window.addEventListener('DOMContentLoaded', function() {
    updateMetroClock();
    setInterval(updateMetroClock, 1000);
});
$(function() {

      $.backstretch([
      "https://www.uinsyahada.ac.id/wp-content/uploads/2024/01/backgroundlogin.jpg"
  ], {duration: 3000, fade: 1000});



  
    
});
</script>
<style>
body {
    background: linear-gradient(120deg, #00b7c3 0%, #0078d7 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', 'Arial', sans-serif;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}
.centered-login-container {
    min-height: 100vh;
    width: 100vw;
    margin-top:100px;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
}
.metro-login-card {
    background: rgba(255,255,255,0.92);
    border-radius: 24px;
    box-shadow: 0 12px 48px 0 rgba(31,38,135,0.18);
    padding: 3.5rem 3.5rem;
    max-width: 480px;
    width: 100%;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    animation: fadeIn 1s cubic-bezier(.4,0,.2,1);
    justify-content: space-between;
    min-height: 500px;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}
.metro-avatar {
    width: 190px;
    height: 190px;
    border-radius: 50%;
    background: #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
    font-size: 4rem;
    color: #0078d7;
    box-shadow: 0 2px 12px rgba(0,0,0,0.10);
}
.metro-avatar img {
    width: 170px;
    height: 170px;
    border-radius: 50%;
    background: #fff;
    object-fit: contain;
    box-shadow: 0 2px 8px rgba(0,0,0,0.10);
}
.metro-login-title {
    color: #222;
    font-size: 2.2rem;
    font-weight: 600;
    margin-bottom: 1.7rem;
    letter-spacing: 0.5px;
    text-align: center;
}
.form-signin {
    width: 100%;
    margin-bottom: auto;
    flex-direction: column;
    flex: 1 1 auto;
    justify-content: center;
}
.form-signin .form-control {
    font-size: 1.3rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    padding: 1.1rem 1.2rem;
    border: 1.5px solid #e0e0e0;
    background: #f4f8fb;
    transition: border 0.2s, box-shadow 0.2s;
    font-family: 'Segoe UI', 'Arial', sans-serif;
}
.form-signin .form-control:focus {
    border-color: #00b7c3;
    box-shadow: 0 0 0 2px rgba(0,183,195,0.15);
    background: #fff;
}
.btn-login {
    background: #00b7c3;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 1.3rem;
    font-weight: 600;
    width: 100%;
    padding: 1.1rem 0;
    margin-top: 0.7rem;
    margin-bottom: 0;
    box-shadow: 0 2px 12px rgba(0,183,195,0.10);
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    letter-spacing: 0.5px;
}
.btn-login:hover, .btn-login:focus {
    background: #0078d7;
    color: #fff;
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 4px 24px rgba(0,120,215,0.18);
}
.invalid {
    color: #d32f2f;
    background: #fff3f3;
    border-radius: 8px;
    padding: 0.7rem 1.2rem;
    margin-top: 1.2rem;
    font-size: 1.1rem;
    text-align: center;
}
.metro-clock {
    position: fixed;
    left: 2.5vw;
    bottom: 2.5vw;
    color: #fff;
    font-family: 'Segoe UI', 'Arial', sans-serif;
    font-size: 2.4rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.18);
    z-index: 10;
    user-select: none;
    line-height: 1.1;
}
.metro-date {
    font-size: 1.3rem;
    font-weight: 400;
    opacity: 0.85;
    margin-top: 0.2rem;
}
@media (max-width: 700px) {
    .metro-login-card {
        padding: 1.5rem 0.5rem;
        max-width: 98vw;
    }
    .metro-login-title {
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }
    .metro-avatar {
        width: 80px;
        height: 80px;
        margin-bottom: 1.2rem;
    }
    .metro-avatar img {
        width: 70px;
        height: 70px;
    }
    .metro-clock {
        left: 4vw;
        bottom: 2vw;
        font-size: 1.3rem;
    }
}
</style>
</html>
<?php
} else {
  header("location:./");
}
?>