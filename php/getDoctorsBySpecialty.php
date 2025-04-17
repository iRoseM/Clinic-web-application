<?php
include 'db_connection.php';

if (isset($_GET['specialty'])) {
    $specialtyID = intval($_GET['specialty']);

    $stmt = $conn->prepare("SELECT id, firstName, lastName FROM Doctor WHERE SpecialityID = ?");
    $stmt->bind_param("i", $specialtyID);
    $stmt->execute();
    $result = $stmt->get_result();

    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }

    echo json_encode($doctors);
}
?>
