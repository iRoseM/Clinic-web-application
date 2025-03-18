<?php
session_start();
// include 'db.php'; database connection
include 'db_connection.php';

// Check if patient is logged in
if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'patient') {
    header('Location: LogIn.html');
    exit();
}

$specialties = [];
$doctors = [];

// Fetch specialties
$specialtySql = "SELECT * FROM Speciality";
$specialtyResult = $conn->query($specialtySql);
while ($row = $specialtyResult->fetch_assoc()) {
    $specialties[] = $row;
}

// If specialty is selected
if (isset($_POST['specialty'])) {
    $specialtyID = intval($_POST['specialty']);
    $doctorSql = "SELECT * FROM Doctor WHERE SpecialityID = ?";
    $stmt = $conn->prepare($doctorSql);
    $stmt->bind_param("i", $specialtyID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

// Handle appointment booking
if (isset($_POST['doctor'], $_POST['date'], $_POST['time'], $_POST['reason'])) {
    $doctorID = intval($_POST['doctor']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];
    $status = 'Pending';
    $patientID = $_SESSION['id'];

    $insertSql = "INSERT INTO Appointment (PatientID, DoctorID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("iissss", $patientID, $doctorID, $date, $time, $reason, $status);
    if ($stmt->execute()) {
        header('Location: indexPatient.php?msg=Appointment booked successfully');
        exit();
    } else {
        echo "Error booking appointment.";
    }
}
?>
