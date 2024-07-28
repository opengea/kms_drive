<?// Function to validate user credentials
function validateCredentials($username, $password) {
    // Read usernames and passwords from the file
    $users = file("../../private/users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Loop through each line in the file
    foreach ($users as $user) {
        // Split the line into username and password
        list($storedEmail, $storedPassword, $storedName, $storedPhone, $storedKey) = explode(":", $user);

        // Check if the provided username and password match
        if ($username === $storedEmail && $password === $storedPassword) {
            return true; // Credentials are valid
        }
    }
    return false; // Credentials are invalid
}

function validateKey($key) {

   // Read usernames and passwords from the file
    $users = file("../../private/users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Loop through each line in the file
    foreach ($users as $user) {
        // Split the line into username and password
        list($storedEmail, $storedPassword, $storedName, $storedPhone, $storedKey) = explode(":", $user);

        // Check if the provided username and password match
        if ($storedKey === $key) {
	   $user=[
		"name" => $storedName,
		"email" => $storedEmail,
		"phone" => $storedPhone,
		"key" => $key
	    ];
            return $user;

        }
    }
    return false; // Credentials are invalid


}
?>
