
<?php
session_start();
include 'db_connection.php';

// Check if appointment ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Invalid appointment ID.");
}

// Get the appointment ID from the URL
$appointment_id = intval($_GET['id']);

// Check if the appointment exists
$query = "SELECT * FROM Appointment WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Appointment ID does not exist.");
}

// Update the appointment status to "Confirmed"
$query = "UPDATE Appointment SET status = 'Confirmed' WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();

// Redirect to the doctor's homepage
header("Location: indexDoctor.php");
exit();

// Close the database connection
$stmt->close();
$conn->close();
?>
