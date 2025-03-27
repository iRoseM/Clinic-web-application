<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
ini_set('log_errors', '1');

session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: LogIn.html?error=Please log in as a patient");
    exit();
}

$patientID = $_SESSION['user_id'];
$specialties = [];
$doctors = [];
$selectedSpecialtyID = null;

// Fetch all specialties
$specialtySql = "SELECT * FROM Speciality";
$specialtyResult = $conn->query($specialtySql);
while ($row = $specialtyResult->fetch_assoc()) {
    $specialties[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // First form submitted: filter doctors by selected specialty
    if (isset($_POST['specialty']) && !isset($_POST['date'])) {
        $selectedSpecialtyID = intval($_POST['specialty']);
        $stmt = $conn->prepare("SELECT * FROM Doctor WHERE SpecialityID = ?");
        $stmt->bind_param("i", $selectedSpecialtyID);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
    }

    // Second form submitted: book appointment
    if (isset($_POST['doctor'], $_POST['date'], $_POST['time'], $_POST['reason'])) {
        $doctorID = intval($_POST['doctor']);
        $date = $_POST['date'];
        $time = $_POST['time'];
        $reason = $_POST['reason'];
        $status = 'Pending';

        $insertSql = "INSERT INTO Appointment (PatientID, DoctorID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("iissss", $patientID, $doctorID, $date, $time, $reason, $status);
        if ($stmt->execute()) {
            header('Location: indexPatient.php?msg=Appointment+booked+successfully');
            exit();
        } else {
            echo "Error booking appointment.";
        }
    }
} else {
    // Initial GET: show all doctors
    $doctorSql = "SELECT * FROM Doctor";
    $doctorResult = $conn->query($doctorSql);
    while ($row = $doctorResult->fetch_assoc()) {
        $doctors[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>TheraFlex - Booking Appointment</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/Logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            background: url('img/appointment2.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<div class="main-container">
    <div id="AppointmentsTitle">
        <h1>Schedule Your <span>Appointment</span></h1>
        <hr>
    </div>

    <div class="Formdiv">
        <h1 class="FormTitle">Appointment Booking</h1>
        <br>

        <?php if (isset($_GET['msg'])): ?>
            <p style="color: green;"><?= htmlspecialchars($_GET['msg']); ?></p>
        <?php endif; ?>

        <!-- First Form: Select Specialty -->
        <form method="POST" action="AppointmentBooking.php" id="specialtyForm">
            <label for="specialty">Select Specialty:</label>
            <select id="specialty" name="specialty" required>
                <option value="">--Choose a Specialty--</option>
                <?php foreach ($specialties as $spec): ?>
                    <option value="<?= $spec['id']; ?>" <?= ($selectedSpecialtyID == $spec['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($spec['speciality']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" id="specialtySubmit">Submit</button>
        </form>

        <br><hr><br>

        <!-- Second Form: Book Appointment -->
        <form method="POST" action="AppointmentBooking.php" id="bookingForm">
            <label for="doctor">Select Doctor:</label>
            <select name="doctor" id="doctor" required>
                <option value="">--Choose a Doctor--</option>
                <?php foreach ($doctors as $doc): ?>
                    <option value="<?= $doc['id']; ?>" data-specialty="<?= $doc['SpecialityID']; ?>">
                        <?= htmlspecialchars($doc['firstName'] . ' ' . $doc['lastName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="time">Select Time:</label>
            <input type="time" id="time" name="time" required>

            <label for="reason">Reason for Visit:</label>
            <textarea id="reason" name="reason" rows="4" placeholder="Describe the reason for your visit..." required></textarea>

            <button type="submit" id="bookSubmit">Book Appointment</button>
        </form>
    </div>
</div>
</body>
</html>
