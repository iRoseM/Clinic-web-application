
<?php
error_reporting(E_ALL); 
ini_set('log_errors','1'); 
ini_set('display_errors','1'); 

include 'db_connection.php';


// Check if appointment ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No appointment ID provided.");
}
$appointment_id = intval($_GET['id']);

// Retrieve the Patient ID from the Appointment
$query = "SELECT PatientID FROM Appointment WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if (!$appointment) {
    die("Error: Appointment not found.");
}

$patient_id = $appointment['PatientID']; // Get the Patient ID

// Retrieve Patient Info
$query = "SELECT firstName, lastName, Gender, DoB FROM Patient WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    die("Error: Patient not found.");
}

// Retrieve Medications
$query = "SELECT id, MedicationName FROM Medication";
$medications = $conn->query($query);

// If form is submitted, update the database
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selected_medications = isset($_POST['pMedication']) ? $_POST['pMedication'] : [];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update the appointment status to "Done"
        $query = "UPDATE Appointment SET status = 'Done' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();

        // Insert selected medications into Prescription table
        foreach ($selected_medications as $med_id) {
            $query = "INSERT INTO Prescription (AppointmentID, MedicationID) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $appointment_id, $med_id);
            $stmt->execute();
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to Doctor's Homepage
        header("Location: indexDoctor.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();
        die("Error processing prescription: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TheraFlex - Medication</title>
        <link rel="icon" href="img/Logo.png" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">

        <style>
            body {
                margin: 0;
                background: url('img/medication.png') no-repeat center center fixed;
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
                <div id="prescribtionTitle">
                    <h1>Patient's Medication <span>Prescribtion</span></h1>
                    <hr>
                </div>

            <div class="Formdiv">
                <h1 class="FormTitle">Patient's Medications</h1>
                <form  action="" method="post" id="PatientMedForm">

                <!-- Hidden input to store patient ID -->
                <input type="hidden" name="patient_id" value="<?= $patient_id; ?>">

                <label>Patient's Name: <input type="text" name="pName" value="<?= htmlspecialchars($patient['firstName'] . ' ' . $patient['lastName']); ?>"></label>
                    <label>Age: <input type="number" name="pAge" value="<?= date_diff(date_create($patient['DoB']), date_create('today'))->y; ?>"></label>

                    <label>Gender:</label>
                    <ul>
                        <li><input type="radio" name="pGender" value="male" <?= ($patient['Gender'] == 'Male') ? 'checked' : ''; ?> disabled>Male</li>
                        <li><input type="radio" name="pGender" value="female" <?= ($patient['Gender'] == 'Female') ? 'checked' : ''; ?> disabled>Female</li>
                    </ul>

                    <label>Medications:</label>
                    <ul>
                        <?php foreach ($medications as $med) { ?>
                            <li>
                                <input type="checkbox" name="pMedication[]" value="<?= $med['id']; ?>"> 
                                <?= htmlspecialchars($med['MedicationName']); ?>
                            </li>
                        <?php } ?>
                    </ul>
                    <input type="submit" value="Submit" id="pSubmit">
                </form>
            </div>
        </div>
            <script src="script.js"></script>
    </body>
</html>