<?php
error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('display_errors', '1');

session_start();
include 'db_connection.php'; // Ensure this file properly connects to your database

// Ensure the patient is logged in
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

// Handle first form submission (filter doctors by specialty)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['specialty'])) {
    $selectedSpecialtyID = intval($_POST['specialty']);

    // Fetch doctors for the selected specialty
    $doctorSql = "SELECT * FROM Doctor WHERE SpecialityID = ?";
    $stmt = $conn->prepare($doctorSql);
    $stmt->bind_param("i", $selectedSpecialtyID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

// Handle second form submission (book appointment)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doctor'], $_POST['date'], $_POST['time'], $_POST['reason'])) {
    $doctorID = intval($_POST['doctor']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];
    $status = 'Pending';

    // Insert the appointment into the database
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
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="img/Logo.png" type="image/x-icon">
        <title>TheraFlex - Booking Appointment</title>
        <link  rel="stylesheet" href="style.css">
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
                    <!--First Form-->
                    <form method="POST" action="AppointmentBooking.php" id="specialtyForm" onsubmit="handleSpecialtySubmit(event)">
                        <label for="specialty">Select Specialty:</label>
                        <select name="specialty" id="specialty" required>
                            <option value="">--Choose a Specialty--</option>
                            <?php foreach ($specialties as $spec) { ?>
                                <option value="<?php echo $spec['id']; ?>" <?php if (isset($_POST['specialty']) && $_POST['specialty'] == $spec['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($spec['speciality']); ?>
                                        </option>
                                    <?php } ?>
                        </select>
                        <button type="submit" id="specialtySubmit">Submit</button>
                    </form>
                    <br><hr><br>
                    <!-- Second Form -->
                    <?php if (!empty($doctors)) { ?>
                        <form method="POST" action="AppointmentBooking.php" id="bookingForm" onsubmit="handleBookingSubmit(event)" style="display: none;">
                            <label for="doctor">Select Doctor:</label>
                            <input type="hidden" name="specialty" value="<?php echo $specialtyID; ?>">
                            <select name="doctor" id="doctor" required>
                                <option value="">--Choose a Doctor--</option>
                                <?php foreach ($doctors as $doc) { ?>
                                    <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['firstName'] . ' ' . $doc['lastName']); ?></option>
                                <?php } ?>
                            </select>

                            <label for="date">Select Date:</label>
                            <input type="date" id="date" name="date" required>

                            <label for="time">Select Time:</label>
                            <input type="time" id="time" name="time" required>

                            <label for="reason">Reason for Visit:</label>
                            <textarea id="reason" name="reason" rows="4" placeholder="Describe the reason for your visit..." required>Having severe pain.</textarea>

                            <button type="submit" id="bookSubmit">Book Appointment</button>
                        </form>
                    <?php } ?>
                </div>
        </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const specialtyForm = document.getElementById("specialtyForm");
            const bookingForm = document.getElementById("bookingForm");

            // Handle first form submission (filter doctors by specialty)
            if (specialtyForm) {
                specialtyForm.addEventListener("submit", function (event) {
                    event.preventDefault(); // Prevent the default form submission

                    const selectedSpecialty = document.getElementById("specialty").value;
                    if (!selectedSpecialty) {
                        alert("Please select a specialty before submitting.");
                        return;
                    }

                    // Submit the form programmatically
                    specialtyForm.submit();
                });
            }

            // Handle second form submission (book appointment)
            if (bookingForm) {
                bookingForm.addEventListener("submit", function (event) {
                    event.preventDefault(); // Prevent the default form submission

                    const doctor = document.getElementById("doctor").value;
                    const date = document.getElementById("date").value;
                    const time = document.getElementById("time").value;
                    const reason = document.getElementById("reason").value;

                    if (!doctor || !date || !time || !reason) {
                        alert("Please fill out all the fields before submitting.");
                        return;
                    }

                    // Submit the form programmatically
                    bookingForm.submit();
                });
            }

            // Show the second form if doctors are available
            if (bookingForm && <?php echo !empty($doctors) ? 'true' : 'false'; ?>) {
                bookingForm.style.display = "block";
            }
        });
    </script>

    </body>
</html>