<?php 

session_start(); // 1. Start the session

if (!isset($_SESSION['user_id']) or $_SESSION['role'] != 'owner') {
    // Redirect them to the login page
    header("Location: login.html");
    exit;
}

// 3. FORM PROCESSING: Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- 1. Handle the File Upload ---
    $pet_image_path = null; // Default to null if no image is uploaded
    
    // Check if a file was uploaded without errors
    if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] == 0) {
        
        $target_dir = "uploads/"; // The directory we just created
        
        // Get the original filename
        $original_filename = basename($_FILES["pet_image"]["name"]);
        
        // Create a unique filename to prevent overwriting
        // e.g., "653d6f1a8-my-dog.jpg"
        $unique_filename = uniqid() . '-' . $original_filename;
        
        $target_file = $target_dir . $unique_filename;

        // Try to move the uploaded file from its temporary location to our new folder
        if (move_uploaded_file($_FILES["pet_image"]["tmp_name"], $target_file)) {
            // Success! Store the path to be saved in the database
            $pet_image_path = $target_file;
        } else {
            // Handle file move error (optional)
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // --- 2. Database Connection ---
    $host = 'localhost';
    $db_name = 'CareForMyPet';
    $username = 'root';
    $password = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // --- 3. Get Data from Form and Session ---
        $name = $_POST['name'];
        $breed = $_POST['breed'];
        $age = $_POST['age'];
        $health_needs = $_POST['health_needs'];
        $dietary_needs = $_POST['dietary_needs'];
        $special_requirements = $_POST['special_requirements'];
        $user_id = $_SESSION['user_id']; // The owner's ID
        // The $pet_image_path variable is from Step 1 above

        // --- 4. Prepare and Execute SQL (NOW INCLUDES IMAGE PATH) ---
        // We now have 8 columns to insert
        $sql = "INSERT INTO pets (name, breed, age, health_needs, dietary_needs, special_requirements, user_id, pet_image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        // The order must match the 8 question marks
        $stmt->execute([
            $name, 
            $breed, 
            $age, 
            $health_needs, 
            $dietary_needs, 
            $special_requirements, 
            $user_id,
            $pet_image_path // Our new variable
        ]);

        // --- 5. Success: Redirect ---
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