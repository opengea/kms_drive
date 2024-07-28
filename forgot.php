<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the email entered by the user
    $email = $_POST["email"];

    // Check if the email exists in the database or user file
    // You would replace this with your own logic to check if the email exists in your database or file system

    // For demonstration purposes, let's assume the email exists
    $password = getPassword($email);  // Retrieve the password for the user

    if ($password) {
    // Send the password recovery email to the user
    $to = $email;
    $subject = "Password Recovery";
    $message = "Your password is: " . $password;
    $headers = "From: no-reply@kms.cat"; // Replace with your own email address

    // Send email
    if (mail($to, $subject, $message, $headers)) {
        $status="sent";
    } else {
        $status="failed";
    }
    } else {

	$status="failed";
    }
}

function getPassword($email) {
    // Read the contents of the users.txt file into an array
    $users = file("../../private/users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Loop through each line in the file
    foreach ($users as $user) {
        // Split the line into email and password
        list($storedEmail, $storedPassword) = explode(":", $user);
        
        // Check if the provided email matches the stored email
        if ($email === $storedEmail) {
            return $storedPassword; // Return the password associated with the email
        }
    }
    return false; // Return false if email not found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">

</head>
<body>
<div class="main-container" style="display: flex; flex-direction: column; align-items: center;">

    <form id="forgot" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<b>Forgot Password</b><br><br><br>
	
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Recover Password</button>

    </form>

<div class="msg">
<? if ($status=="sent")   echo "<center><br>An email with your password has been sent to your email address.</center>";
   else if ($status=="failed")   echo "<center><br>Failed to send password recovery email. Please try again later.</center>";
?>
</div>
</div>
</body>
</html>

