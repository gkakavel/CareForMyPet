<?php 

session_start(); // 1. Start the session

if (!isset($_SESSION['user_id']) or $_SESSION['role'] != 'owner') {
    // Redirect them to the login page
    header("Location: login.html");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add a New Pet</title>
    </head>
    <body>

        <h1>Add a New Pet</h1>
        
        <form action="add_pet.php" method="POST">
            <label for="pet_name">Pet Name:</label>
            <input type="text" id="pet_name" name="name" required><br><br>

            <label for="breed">Pet Type:</label>
            <input type="text" id="breed" name="breed" required><br><br>

            <label for="age">Age:</label>
            <input type="text" id="age" name="age" required><br><br>

            <label for="health_needs">Health Needs:</label>
            <textarea id="health_needs" name="health_needs"></textarea><br><br>

            <label for="dietary_needs">Dietary Needs:</label>
            <textarea id="dietary_needs" name="dietary_needs"></textarea><br><br>

            <label for="special_requirements">Special Requirements:</label>
            <textarea id="special_requirements" name="special_requirements"></textarea><br><    br>

            <input type="submit" value="Add Pet">
        </form>

        <p><a href="dashboard.php">Back to Dashboard</a></p>

    </body>
</html>