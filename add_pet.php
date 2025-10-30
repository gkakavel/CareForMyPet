<?php 

session_start(); // 1. Start the session

if (!isset($_SESSION['user_id']) or $_SESSION['role'] != 'owner') {
    // Redirect them to the login page
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- Get Data from Form and Session ---
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $health_needs = $_POST['health_needs'];
    $dietary_needs = $_POST['dietary_needs'];
    $special_requirements = $_POST['special_requirements'];
    $user_id = $_SESSION['user_id']; // The owner's ID

    // Database Connection Details
    $host = 'localhost';
    $db_name = 'CareForMyPet';
    $username = 'root';
    $password = 'root';

    // Create Connection (PDO)
    try {
        // --- Create Connection ---
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // --- Prepare and Execute SQL ---
        $sql = "INSERT INTO pets (name, breed, age, health_needs, dietary_needs, special_requirements, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
        $stmt = $pdo->prepare($sql);
            
        $stmt->execute([
                $name, 
                $breed, 
                $age, 
                $health_needs, 
                $dietary_needs, 
                $special_requirements, 
                $user_id
        ]);

        // --- Success: Redirect back to the dashboard ---
        // This prevents re-submitting the form if the user refreshes
        header("Location: dashboard.php");
        exit;

    } catch(PDOException $e) {
            die("ERROR: Could not save pet. " . $e->getMessage());
    }
}
// 4. HTML DISPLAY: If it's not a POST request, just show the form
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add a New Pet</title>
    </head>
    <body>

        <h1>Add a New Pet</h1>
        
        <form action="add_pet.php" method="POST" enctype="multipart/form-data">
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
            <textarea id="special_requirements" name="special_requirements"></textarea><br><br>

            <label for="pet_image">Pet Photo:</label>
            <input type="file" id="pet_image" name="pet_image"><br><br>

            <input type="submit" value="Add Pet">
        </form>

        <p><a href="dashboard.php">Back to Dashboard</a></p>

    </body>
</html>