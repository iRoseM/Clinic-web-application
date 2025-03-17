
<?php
session_start();
include 'db.php';

// Check if appointment ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Invalid appointment ID.");
}

// Get the appointment ID from the URL
$appointment_id = intval($_GET['id']);

// Debug: Check if ID is being received correctly
echo "<p>Debug: Received Appointment ID = $appointment_id</p>";

// Check if the appointment exists
$query = "SELECT * FROM Appointment WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Appointment ID $appointment_id does not exist.");
}

// Update the appointment status to "Confirmed"
$query = "UPDATE Appointment SET status = 'Confirmed' WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);

if ($stmt->execute()) {
    echo "<p>Success: Appointment confirmed! Redirecting...</p>";
    header("refresh:2;url=indexDoctor.php"); // Redirect after 2 seconds
    exit();
} else {
    die("Error updating appointment status: " . $stmt->error);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
