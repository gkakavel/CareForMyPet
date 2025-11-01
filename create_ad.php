<?php
session_start(); // 1. Start the session

// 2. Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    header("Location: login.html");
    exit;
}

// 3. FORM PROCESSING: Check if the "Create Ad" form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- Get Data from Form ---
    $pet_id = $_POST['pet_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $location = $_POST['location'];
    $status = 'open'; // We'll set the default status to 'open'

    // --- Database Connection Details ---
    $host = 'localhost';
    $db_name = 'CareForMyPet';
    $username = 'root';
    $password = 'root';

    try {
        // --- Create Connection ---
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // --- Prepare and Execute SQL to save the ad ---
        $sql = "INSERT INTO ads (pet_id, start_date, end_date, location, status) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $pet_id,
            $start_date,
            $end_date,
            $location,
            $status
        ]);

        // --- Success: Redirect back to the dashboard ---
        header("Location: dashboard.php");
        exit;

    } catch(PDOException $e) {
        // If it fails, store the error in the $page_error variable
        // The HTML at the bottom of the page will then display it
        $page_error = "ERROR: Could not create the ad. " . $e->getMessage();
    }
}


// 4. Data Fetching (for the GET request, to build the form)
$pets = []; // Initialize the array
$page_error = null; // Variable to hold any errors

// --- Database Connection ---
$host = 'localhost';
$db_name = 'CareForMyPet';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // --- Fetch this owner's pets ---
    $sql = "SELECT pet_id, name FROM pets WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    
    // Get all pets as an associative array
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    // If database connection fails, we'll store the error
    $page_error = "Error fetching your pets: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create a New Ad</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <h1>Create a New Ad</h1>

    <?php if ($page_error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($page_error); ?></p>
    <?php endif; ?>


    <?php 
    // ---- CHECK IF USER HAS PETS ----
    if (!empty($pets)): 
    ?>
        
        <form action="create_ad.php" method="POST">
            
            <p>
                <label for="pet_id">Which pet needs care?</label><br>
                <select id="pet_id" name="pet_id">
                    <option value="">-- Select a Pet --</option>
                    
                    <?php 
                    // ---- THE LOOP YOU STARTED ----
                    // This creates one <option> for each of the user's pets
                    foreach ($pets as $pet): 
                    ?>
                        <option value="<?php echo htmlspecialchars($pet['pet_id']); ?>">
                            <?php echo htmlspecialchars($pet['name']); ?>
                        </option>
                    <?php 
                    // End the loop
                    endforeach; 
                    ?>

                </select>
            </p>

            <p>
                <label for="start_date">Start Date:</label><br>
                <input type="date" id="start_date" name="start_date" required>
            </p>

            <p>
                <label for="end_date">End Date:</label><br>
                <input type="date" id="end_date" name="end_date" required>
            </p>

            <p>
                <label for="location">Location (e.g., your city):</label><br>
                <input type="text" id="location" name="location" required>
            </p>
            
            <p>
                <input type="submit" value="Create Ad">
            </p>
        
        </form>

    <?php 
    // ---- This runs if the $pets array was empty ----
    else: 
    ?>
        <p>You must <a href="add_pet.php">add a pet</a> before you can create an ad.</p>
    <?php 
    // End the 'if (!empty($pets))' check
    endif; 
    ?>

    <p><a href="dashboard.php">Back to Dashboard</a></p>

</body>
</html>