<?php 
include "setup.php";
include "functions.php";

if ($_POST) { 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $fp = fopen("log/visits.log", "a");
    fwrite($fp,  date('d-m-Y H:i:s')." ".$_SERVER['REMOTE_ADDR']." LOGIN ".print_r($_POST,true)); 
    // Validate user credentials
    if (validateCredentials($username, $password)) {
        // Redirect to the dashboard or another page upon successful login
	session_start();
	$_SESSION['logged']=$username;
        header("Location: index.php");
        exit;
    } else {
        // Display error message if credentials are invalid
	$show_error=true;
    }
}
?>

<? } ?>
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

