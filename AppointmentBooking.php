<?php
include 'db_connection.php';
session_start();

// Ensure the patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: LogIn.html?error=Please log in as a patient");
    exit();
}

$patient_id = $_SESSION['user_id']; // Get the logged-in patient's ID
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="img/Logo.png" type="image/x-icon">
        <title>TheraFlex - Booking Appointment</title>
        <link rel="stylesheet" href="style.css">
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
                    <form id="specialtyForm" onsubmit="handleSpecialtySubmit(event)">
                        <label for="specialty">Select Specialty:</label>
                        <select name="specialty" id="specialty" required>
                            <option value="">--Choose a Specialty--</option>
                            <option value="Pediatric" selected>Pediatric</option>
                            <option value="Chronic Pain">Chronic Pain</option>
                            <option value="Cardiology">Cardiology</option>
                        </select>
                        <button type="submit" id="specialtySubmit">Submit</button>
    
                    </form>
                    <br><hr><br>
                    <!-- Second Form -->
                    <form id="bookingForm" onsubmit="handleBookingSubmit(event)" style=" display: none;">
                        <label for="doctor">Select Doctor:</label>
                        <select name="doctor" id="doctor" required>
                            <option value="">--Choose a Doctor--</option>
                            

                            <!-- A drop-down list to display the doctors according to the selected specialty from the first form -->
                        </select>
                        
                        <label for="date">Select Date:</label>
                        <input type="date" id="date" name="date" required>
                        
                        <label for="time">Select Time:</label>
                        <input type="time" id="time" name="time" required>
                        
                        <label for="reason">Reason for Visit:</label>
                        <textarea id="reason" name="reason" rows="4" placeholder="Describe the reason for your visit..." required>Having severe pain.</textarea>
                        
                        <button type="submit" id="bookSubmit">Book Appointment</button>
                    </form>
                </div>
        </div>
    <script>
        const specialtyToDoctors = {
      Pediatric: ["Dr. Sara Ahmed", "Dr. Youssef Ali"],
      "Chronic Pain": ["Dr. Saleh Abdullah", "Dr. Leila Hasan"],
      Cardiology: ["Dr. Khaled Omar", "Dr. Amal Khalid"]
    };

    function handleSpecialtySubmit(event) {
      event.preventDefault();

      const selectedSpecialty = document.getElementById("specialty").value;
      if (!selectedSpecialty) {
        alert("Please select a specialty before submitting.");
        return;
      }

      const doctorSelect = document.getElementById("doctor");
      doctorSelect.innerHTML = `<option value="">--Choose a Doctor--</option>`;
      if (specialtyToDoctors[selectedSpecialty]) {
        specialtyToDoctors[selectedSpecialty].forEach((doctor) => {
          const option = document.createElement("option");
          option.value = doctor;
          option.textContent = doctor;
          doctorSelect.appendChild(option);
        });
      }

      document.getElementById("bookingForm").style.display = "block";
    }

    function handleBookingSubmit(event) {
      event.preventDefault();

      const doctor = document.getElementById("doctor").value;
      const date = document.getElementById("date").value;
      const time = document.getElementById("time").value;
      const reason = document.getElementById("reason").value;

      if (!doctor || !date || !time || !reason) {
        alert("Please fill out all the fields before submitting.");
        return;
      }

      console.log("Doctor:", doctor);
      console.log("Date:", date);
      console.log("Time:", time);
      console.log("Reason:", reason);

      window.location.href = "indexPatient.html";
    }
    </script>

    </body>
</html>