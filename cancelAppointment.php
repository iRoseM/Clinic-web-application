<?php
session_start();
include 'db_connection.php'; // Ensure this file properly connects to your database

// Ensure the patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: LogIn.html?error=Please log in as a patient");
    exit();
}

// Validate the appointment ID
$appointmentID = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($appointmentID <= 0) {
    echo "Invalid appointment ID.";
    exit();
}

// Check if the appointment belongs to the logged-in patient
$checkSql = "SELECT * FROM Appointment WHERE id = ? AND PatientID = ?";
$stmt = $conn->prepare($checkSql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

$stmt->bind_param("ii", $appointmentID, $_SESSION['user_id']);
if (!$stmt->execute()) {
    echo "Error executing query: " . $stmt->error;
    exit();
}

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Delete the appointment
    $deleteSql = "DELETE FROM Appointment WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    $stmt->bind_param("i", $appointmentID);
    if ($stmt->execute()) {
        header('Location: indexPatient.php?msg=Appointment canceled successfully');
        exit();
    } else {
        echo "Error deleting appointment: " . $stmt->error;
        exit();
    }
} else {
    echo "Invalid appointment or unauthorized action.";
    exit();
}
?>