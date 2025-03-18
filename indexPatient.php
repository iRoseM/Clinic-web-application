<?php
ini_set('display_errors', '1');
session_start();
// include 'db.php'; // database connection
include 'db_connection.php';


// Check if patient is logged in
if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'patient') {
    header('Location: LogIn.html'); // Redirect to login if not logged in as patient
    exit();
}

$patientID = $_SESSION['id'];

// Fetch patient information
$patientSql="SELECR * FROM patient WHERE id = ?";
$stmt=$conn->prepare($patientSql);
$stmt->bind_param('i',$patientID);
$stmt->execute();
$patientResult=$stmt->get_result();
$patient=$patientResult->fetch_assoc();

// Fetch patient appointments
$appointmentSql = "SELECT a.*, d.firstName AS doctorFirstName, d.lastName AS doctorLastName, d.uniqueFileName
                   FROM Appointment a
                   JOIN Doctor d ON a.DoctorID = d.id
                   WHERE a.PatientID = ?
                   ORDER BY a.date, a.time";
$stmt=$conn->prepare($appointmentSql);
$stmt->bind_param("i",$patientID);
$stmt->execute();
$appointments=$stmt->get_result();

?>