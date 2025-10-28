<?php
session_start(); // Find the session

// Clear all session variables
session_unset();

// Destroy the session itself
session_destroy();

// Send the user back to the login page
header("Location: login.html");
exit;