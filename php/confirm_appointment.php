<?php
session_start();
include 'db_connection.php';

// Return JSON response
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in as doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Validate appointment ID
$appointmentID = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($appointmentID <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid appointment ID']);
    exit();
}

try {
    // Check if the appointment belongs to this doctor
    $checkSql = "SELECT * FROM Appointment WHERE id = ? AND DoctorID = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $appointmentID, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Appointment not found or unauthorized']);
        exit();
    }

    // Update the status to 'Confirmed'
    $updateSql = "UPDATE Appointment SET status = 'Confirmed' WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $appointmentID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Appointment confirmed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to confirm appointment']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error']);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
