<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connection.php'; // Ensure this file properly connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['userType'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Secure password hashing

    if ($userType == "doctor") {
        $speciality = trim($_POST['speciality']); // Expecting SpecialityID
        $photo = $_FILES['photo'];

        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT id FROM Doctor WHERE emailAddress = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            header("Location: signUp.php?error=Email already registered");
            exit();
        }

        // Handle file upload securely
        $photoExtension = pathinfo($photo["name"], PATHINFO_EXTENSION);
        $photoName = uniqid("doctor_", true) . "." . $photoExtension;
        $photoPath = "uploads/" . $photoName;
        move_uploaded_file($photo["tmp_name"], $photoPath);

        // Insert new doctor
        $stmt = $conn->prepare("INSERT INTO Doctor (id, firstName, lastName, uniqueFileName, SpecialityID, emailAddress, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $id, $firstName, $lastName, $photoName, $speciality, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_type'] = "doctor";
            header("Location: indexDoctor.php");
            exit();
        }
    } elseif ($userType == "patient") {
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];

        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT id FROM Patient WHERE emailAddress = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            header("Location: signUp.php?error=Email already registered");
            exit();
        }

        // Insert new patient
        $stmt = $conn->prepare("INSERT INTO Patient (id, firstName, lastName, emailAddress, password, DoB, Gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $id, $firstName, $lastName, $email, $hashedPassword, $dob, $gender);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_type'] = "patient";
            header("Location: indexPatient.php");
            exit();
        }
    }
}

// Redirect in case of an error
header("Location: signUp.php?error=Something went wrong");
exit();
?>