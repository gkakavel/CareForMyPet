<?php
session_start(); // 1. Start the session

// 2. Check if the user is logged in
// If the 'user_id' key isn't in their session, they aren't logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect them to the login page
    header("Location: login.html");
    exit;
}

// 3. If they ARE logged in, greet them
$first_name = $_SESSION['first_name'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Your Dashboard</title>
    </head>
    <body>

        <h1>Welcome to your Dashboard, <?php echo htmlspecialchars($first_name); ?>!</h1>
        
        <p>You are logged in.</p>

        <?php
        if ($_SESSION['role'] == 'owner') {
            echo "You are a Pet Owner. <p><a href='create_ad.php'>Create an Ad</a></p>";
        } else {
            echo "You are a Pet Carer. <p><a href='find_pet.php'>Find a Pet</a></p>";
        }
        ?>
        <p><a href="logout.php">Log Out</a></p>

    </body>
</html>