<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
include 'db_connection.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Display submitted form data

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Either 'doctor' or 'patient'

    // Validate input
    if (empty($email) || empty($password) || empty($role)) {
        // Display error message as a popup
        echo "<script>alert('Please fill in all fields'); window.location.href = '../html/LogIn.html';</script>";
        exit();
    }

    // Determine which table to query based on role
    if ($role == "doctor") {
        $table = "doctor";
    } elseif ($role == "patient") {
        $table = "patient";
    } else {
        // Invalid role
        echo "<script>alert('Invalid role selected'); window.location.href = '../html/LogIn.html';</script>";
        exit();
    }

    // Prepare SQL query to fetch user data
    $stmt = $conn->prepare("SELECT id, password FROM $table WHERE emailAddress = ?");
    if (!$stmt) {
        die("Error preparing query: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User exists, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $role;

            // Use JavaScript to redirect
            if ($role == 'doctor') {
                echo "<script>alert('Login successful! Redirecting to doctor homepage.'); window.location.href = 'indexDoctor.php';</script>";
            } else {
                echo "<script>alert('Login successful! Redirecting to patient homepage.'); window.location.href = 'indexPatient.php';</script>";
            }
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Incorrect email or password'); window.location.href = '../html/LogIn.html';</script>";
            exit();
        }
    } else {
        // User does not exist
        echo "<script>alert('Incorrect email or password'); window.location.href = '../html/LogIn.html';</script>";
        exit();
    }
} else {
    // Redirect back to login page if request method is not POST
    echo "<script>alert('Invalid request'); window.location.href = '../html/LogIn.html';</script>";
    exit();
}
?>