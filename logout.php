<?php 
include "setup.php";
session_start();
$_SESSION=array();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/')
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="main-container" style="display: flex; flex-direction: column; align-items: center;">
    <div class="login-container">
        <div class="login-card">
            <img src="/drive/img/drive.png" style="width:auto" alt="Google Logo" class="logo">
            <h2 class="title">Sign in</h2>
            <form action="login.php" method="post">
                <div class="input-container <? if ($show_error) echo " red" ?>">
                    <input type="text" id="username" name="username" placeholder="Email" required>
                </div>
                <div class="input-container <? if ($show_error) echo " red" ?>" >
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn">Next</button>
            </form>
            <p class="link" onclick="document.location='forgot.php'">Forgot password?</p>
        </div>
    </div>
<? if ($show_error) { ?>
    <div class="error-container">
	    <p class="description"><div class='alert'><?=$alert?></div><div class='left'>Please check your username and password and try again.</div></p>
    </div>
<? } ?>
</div>
</body>
</html>

