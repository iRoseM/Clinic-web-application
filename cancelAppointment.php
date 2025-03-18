<?php
session_start();
// include 'db.php'; //database connection

// Ensure the patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: LogIn.html?error=Please log in as a patient");
    exit();
}

$appointmentID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Make sure the appointment belongs to the logged-in patient
$checkSql = "SELECT * FROM Appointment WHERE id = ? AND PatientID = ?";
$stmt = $conn->prepare($checkSql);
$stmt->bind_param("ii", $appointmentID, $_SESSION['id']);
$stmt->execute(); 
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Delete appointment
    $deleteSql = "DELETE FROM Appointment WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $appointmentID);
    if ($stmt->execute()) {
        header('Location: indexPatient.php?msg=Appointment canceled successfully');
    } else {
        echo "Error deleting appointment.";
    }
} else {
    echo "Invalid appointment or unauthorized action.";
}
?>
