<?php
session_start(); // Start the session at the very top

// 1. SECURITY CHECK: Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// 2. DATA FETCHING: Get data based on user role
$pets = []; // Initialize an empty array for pets
if ($_SESSION['role'] == 'owner') {
    
    // --- Database Connection ---
    $host = 'localhost';
    $db_name = 'CareForMyPet';
    $username = 'root';
    $password = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // --- Fetch this owner's pets ---
        $sql = "SELECT * FROM pets WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        
        // Get all pets as an associative array
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        $dashboard_error = "Error fetching pets: " . $e->getMessage();
    }
}

// 3. HTML DISPLAY: Now we can build the page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h1>Welcome to your Dashboard, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h1>

    <p><a href="logout.php">Log Out</a></p>
    
    <hr>

    <?php
    // --- ROLE-BASED CONTENT ---
    if ($_SESSION['role'] == 'owner'):
    ?>

        <h2>Owner Dashboard</h2>
        <ul>
            <li><a href='add_pet.php'>Add a New Pet</a></li>
            <li><a href='create_ad.php'>Create a New Ad</a></li>
        </ul>

        <h3>Your Pets</h3>
        
        <?php if (!empty($pets)): // Check if the $pets array has any pets in it ?>
            
            <?php foreach ($pets as $pet): // Loop through each pet ?>
                <div class="pet-info-box">
                    
                    <?php if (!empty($pet['pet_image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($pet['pet_image_path']); ?>" alt="Pet Photo" style="max-width: 150px; float: left; margin-right: 15px;">
                    <?php endif; ?>
                    
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($pet['name']); ?></p>
                    <p><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?></p>
                    
                    <p><strong>Health Needs:</strong> <?php echo htmlspecialchars($pet['health_needs']); ?></p>
                    <p><strong>Dietary Needs:</strong> <?php echo htmlspecialchars($pet['dietary_needs']); ?></p>
                    <p><strong>Special Requirements:</strong> <?php echo htmlspecialchars($pet['special_requirements']); ?></p>
                </div>
            <?php endforeach; ?>

        <?php else: // This runs if the $pets array was empty ?>
            <p>You have not added any pets yet.</p>
        <?php endif; ?>

    <?php 
    // This is the 'else' for the role check
    else: 
    ?>
        
        <h2>Carer Dashboard</h2>
        <p><a href='find_pet.php'>Find a Pet</a></p>

    <?php 
    // End the role check
    endif; 
    ?>

</body>
</html>