<?php
    session_start();

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
            $email      = $_POST['email_address'];
            $password   = $_POST['password'];

            // --- 2. Retrieve User ---
            try {
                // SQL query template
                $sql = "SELECT * FROM users WHERE email_address = ?";   
                
                // Prepare the statement
                $stmt = $pdo->prepare($sql);
                
                // Execute the statement, passing in our variables
                $stmt->execute([$email]);         
                
                // --- 3. Verify the User and Password ---
    
                // Fetch the user from the database
                $user = $stmt->fetch();

                // Check if a user was found AND if the password matches the hash
                if ($user && password_verify($password, $user['password'])) {
                    
                    // --- 4. LOGIN SUCCESSFUL ---
                    echo "Login successful! Welcome, " . $user['first_name'] . ".";
                    
                    // Store user data in the session "box"
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['role'] = $user['role']; // We'll use this later!

                    // Redirect the user to their dashboard
                    header("Location: dashboard.php");
                    exit; // Always call exit() after a header redirect

                } else {
                    // --- 5. LOGIN FAILED ---
                    // Either the user wasn't found or the password was wrong
                    echo "Invalid email or password.";
                }
            } catch(PDOException $e) {
                die("ERROR: Could not execute query. " . $e->getMessage());
            }
    } catch(PDOException $e) {
        die("ERROR: Could not connect. " . $e->getMessage());
    }    