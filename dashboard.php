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
            echo "<h2>Owner Dashboard</h2>";
            echo "<ul>";
            echo "<li><a href='add_pet.php'>Add a New Pet</a></li>";
            echo "<li><a href='create_ad.php'>Create a New Ad</a></li>";
            echo "</ul>";
        } else {
            echo "<h2>Carer Dashboard</h2>";
            echo "<p><a href='find_pet.php'>Find a Pet</a></p>";
        }
        ?>
        
        <p><a href="logout.php">Log Out</a></p>

    </body>
</html>