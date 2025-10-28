<<?php
// We must start the session to check if the user is logged in
session_start();

// Check if the 'user_id' key exists in their "locker"
if (isset($_SESSION['user_id'])) {
    // If it exists, they are logged in!
    // Send them straight to their dashboard.
    header("Location: dashboard.php");
    exit;
}

// If the key does NOT exist, the if-block is skipped.
// We just show the normal public homepage.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Care For My Pet</title>
</head>
<body>

    <h1>Welcome to Care For My Pet</h1>
    <p>Find the best care for your pet, or become a carer today.</p>

    <p>
        <a href="login.html">Log In</a>
    </p>
    <p>
        <a href="register.html">Register</a>
    </p>

</body>
</html>