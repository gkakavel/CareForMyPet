<?php

// 1. Database Connection Details
$host = 'localhost';
$db_name = 'CareForMyPet';
$username = 'root';
$password = 'root';

// 2. Create Connection (PDO)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    #echo "Connected successfully!"; // A test message

    // --- 1. Get Data ---
    // Get all the data from the form
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email_address'];
    $password   = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role       = $_POST['role'];

    // --- 2. Validate Password ---
    // Check if the two passwords match
    if ($password != $confirm_password) {
        die("ERROR: Passwords do not match.");
    }

    // --- 3. Secure the Password ---
    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // --- 4. Insert the Data ---
    try {
        // SQL query template
        $sql = "INSERT INTO users (first_name, last_name, email_address, role, password) 
                VALUES (?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $pdo->prepare($sql);
        
        // Execute the statement, passing in our variables
        // The order MUST match the question marks in the $sql string
        $stmt->execute([
            $first_name, 
            $last_name, 
            $email, 
            $role, 
            $hashed_password
        ]);

        // If we get this far, the user was created!
        echo "Registration successful! You can now log in.";
        
        // Registration was successful, so redirect to the login page
        header("Location: login.html");
        exit;

    } catch(PDOException $e) {
        // Handle errors (e.g., if the email is already in use)
        die("ERROR: Could not execute query. " . $e->getMessage());
    }
    
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// 3. We will add the data-saving code here...

?>