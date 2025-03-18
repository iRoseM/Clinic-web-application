<?php
include 'db_connection.php';


// 2️⃣ Define doctors and their image paths
$doctors = [
    5 => "img/femaleDoc2.jpg",   // Doctor 1
    6 => "img/femaleDoc.jpg", // Doctor 2
    7 => "img/femaleDoc3.jpg" // Doctor 3
];

// 3️⃣ Define the upload directory
$targetDirectory = "uploads/"; // Make sure this folder exists and has write permissions

// 4️⃣ Process each doctor and store their images
foreach ($doctors as $doctorID => $originalImagePath) {
    // Generate a unique filename
    $uniqueFileName = uniqid("doctor_") . ".jpg";
    $targetPath = $targetDirectory . $uniqueFileName;

    // Copy the image to the target folder
    if (copy($originalImagePath, $targetPath)) {
        // Update the doctor's image filename in the database
        $sql = "UPDATE Doctor SET uniqueFileName = '$uniqueFileName' WHERE id = $doctorID";

        if ($conn->query($sql) === TRUE) {
            echo "✅ Image stored successfully for Doctor ID: $doctorID as $uniqueFileName<br>";
        } else {
            echo "❌ Failed to update the image filename for Doctor ID: $doctorID. Error: " . $conn->error . "<br>";
        }
    } else {
        echo "❌ Failed to copy the image for Doctor ID: $doctorID<br>";
    }
}

// 5️⃣ Close the database connection
$conn->close();
?>
