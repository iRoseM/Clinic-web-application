<?php
include 'db_connection.php';

// 2️⃣ Define doctors and their image paths
$doctors = [
    5 => "../img/femaleDoc2.jpg",
    6 => "../img/femaleDoc.jpg",
    7 => "../img/femaleDoc3.jpg"
];

// 3️⃣ Define the upload directory
$targetDirectory = "uploads/";

// 4️⃣ Ensure the target directory exists
if (!is_dir($targetDirectory)) {
    mkdir($targetDirectory, 0777, true);
}

// 5️⃣ Process each doctor and store their images
foreach ($doctors as $doctorID => $originalImagePath) {
    // Check if the original image exists before copying
    if (!file_exists($originalImagePath)) {
        echo "❌ Original image not found: $originalImagePath for Doctor ID: $doctorID<br>";
        continue;
    }

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

// 6️⃣ Close the database connection (ensure it's open before closing)
if ($conn) {
    $conn->close();
}
?>