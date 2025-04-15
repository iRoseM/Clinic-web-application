<?php
session_start();
include 'db_connection.php'; 

// Set JSON header
header('Content-Type: application/json');

// Ensure the patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: ../html/LogIn.html?error=Please log in as a patient");
    exit();
}
// Validate appointment ID from POST
$appointmentID = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($appointmentID <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid appointment ID']);
    exit();
}

try {
    // Check if appointment belongs to patient
    $checkSql = "SELECT * FROM Appointment WHERE id = ? AND PatientID = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $appointmentID, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Appointment not found or unauthorized']);
        exit();
    }

    // Delete the appointment
    $deleteSql = "DELETE FROM Appointment WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $appointmentID);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Appointment canceled successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to cancel appointment']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>