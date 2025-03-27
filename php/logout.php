<?php
// Start the session
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../html/index.html");
exit(); // Ensure no further code is executed
?>