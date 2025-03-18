<?php
// Start the session
session_start();

// Debugging: Display session data before logout
echo "<pre>";
echo "Session Data Before Logout:\n";
print_r($_SESSION);
echo "</pre>";

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Debugging: Display session data after logout
echo "<pre>";
echo "Session Data After Logout:\n";
print_r($_SESSION);
echo "</pre>";

// Redirect to the login page
header("Location: index.html");
exit(); // Ensure no further code is executed
?>