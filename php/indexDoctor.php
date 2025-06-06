<?php
//    error_reporting(E_ALL); 
//    ini_set('log_errors','1'); 
//    ini_set('display_errors','1'); 

    session_start();
    include 'db_connection.php'; 

    // Ensure the patient is logged in
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
        header("Location: ../html/index.html?error= Sign up or log in account as a doctor");
        exit();
    }

    $doctor_id = $_SESSION['user_id']; // Get the logged-in patient's ID

    

    // Fetch doctor information
    $query = "SELECT firstName, lastName, emailAddress, SpecialityID, uniqueFileName FROM Doctor WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();

    // Get speciality name
    $query = "SELECT speciality FROM Speciality WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor['SpecialityID']);
    $stmt->execute();
    $speciality_result = $stmt->get_result();
    $speciality = $speciality_result->fetch_assoc();

    // Fetch upcoming appointments
    $query = "SELECT a.id, a.date, a.time, p.firstName, p.lastName, p.Gender, p.DoB, a.reason, a.status 
            FROM Appointment a 
            JOIN Patient p ON a.PatientID = p.id 
            WHERE a.DoctorID = ? AND (a.status = 'Pending' OR a.status = 'Confirmed') 
            ORDER BY a.date, a.time";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $appointments = $stmt->get_result();

    // Fetch past patients (ONLY "Done" appointments)
    $query = "SELECT DISTINCT p.id, p.firstName, p.lastName, p.Gender, p.DoB, 
                    IFNULL(GROUP_CONCAT(DISTINCT m.MedicationName SEPARATOR ', '), 'N/A') AS Medications 
            FROM Appointment a
            JOIN Patient p ON a.PatientID = p.id 
            LEFT JOIN Prescription pr ON a.id = pr.AppointmentID
            LEFT JOIN Medication m ON pr.MedicationID = m.id
            WHERE a.DoctorID = ? AND a.status = 'Done'
            GROUP BY p.id
            ORDER BY p.lastName, p.firstName";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $patients = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>ThraFlex - Home</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../img/Logo.png" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body onload="confirmPendingAppointments(event); sortPatientsTable(event); stickyNavbar(event); sortAppointments(event);">

        <!-- Nav Bar -->
        <header class="header">
            <div class="logo">
                <a href="https://theraflix.infinityfreeapp.com/html/indexDoctor.php"><img src="../img/Logo.png" alt="logo"></a>
                <span> <a href="https://theraflix.infinityfreeapp.com/html/indexDoctor.php">TheraFlex</a></span>
            </div>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="navigation">
                <ul class="nav-links">
                    <li><a href="indexDoctor.php">Home</a></li>
                    <li><a href="indexDoctor.php#docAppointmentnav">Upcoming Appointments</a></li>
                    <li><a href="indexDoctor.php#docPatientsnav">Patients</a></li>
                    <li><a href="indexDoctor.php#contactUsnav">Contact Us</a></li>
                    <li class="logout-mobile"><a href="logout.php">Log out</a></li> <!-- Only for mobile -->
                </ul>
            </div>

            <div class="icon-container" onclick="togglePopup()">
                <svg class="icon" width="292" height="309" viewBox="0 0 292 309" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M281 297.86H11V254.49C11 208.45 48.33 171.12 94.37 171.12H197.63C243.67 171.12 281 208.45 281 254.49V297.86Z" stroke="currentColor" stroke-width="22" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M146 141C181.899 141 211 111.898 211 76.0002C211 40.1015 181.899 11 146 11C110.101 11 81 40.1015 81 76.0002C81 111.898 110.101 141 146 141Z" stroke="currentColor" stroke-width="22" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <div class="popup-card" id="popupCard">
                    <div class="popup-content">

                        <div class="doctor-image">
                            <?php
                                $imagePath = "uploads/" . htmlspecialchars($doctor['uniqueFileName']);
                            ?>
                            <img src="<?= $imagePath; ?>" alt="Doctor's Picture">

                        </div>
                        
                        <h3 id="docName"><?= htmlspecialchars($doctor['firstName'] . ' ' . $doctor['lastName']); ?></h3>
                        <p id="docSpeciality"><?= htmlspecialchars($speciality['speciality']); ?></p>
                        <p id="docEmail">Email: <?= htmlspecialchars($doctor['emailAddress']); ?></p>
<!--                        <p id="docId">ID: //<?= $doctor_id; ?></p>-->
                        <svg class="logout" width="22" height="25" viewBox="0 0 22 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <a href="logout.php"> 
                            <path d="M6.59998 12.2H21" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10.9 1H3.5C2.1 1 1 2.1 1 3.4V20.9C1 22.3 2.1 23.4 3.5 23.4H10.9" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.2 8.4L21 12.2L17.2 16" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </a> 
                        </svg>
                    </div>
            </div>

          </header>


       
        <div class="patBanner">
            <img src="../img/docBanner.png" alt="DoctorBanner">
            <h2>Welcome,<br> <?= htmlspecialchars($doctor['firstName']); ?>!</h2>
        </div>

        <div class="docAppointment" id="docAppointmentnav">
            <h2>Upcoming Appointments</h2>
            <table id="appointmentTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient's Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Reason for visit</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $appointments->fetch_assoc()) { 
                        $age = date_diff(date_create($row['DoB']), date_create('today'))->y;
                    ?>
                    <tr>
                        <td><?= $row['date']; ?></td>
                        <td><?= $row['time']; ?></td>
                        <td><?= htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['lastName']); ?></td>
                        <td><?= $age; ?></td>
                        <td><?= htmlspecialchars($row['Gender']); ?></td>
                        <td><?= htmlspecialchars($row['reason']); ?></td>

                        <td style="text-align: center; vertical-align: middle; padding: 10px;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 5px;"> 
                            <span><?= htmlspecialchars($row['status']); ?></span> 


                                <?php if ($row['status'] == 'Pending') { ?>
                                    <button class="confirm-btn" data-id="<?= $row['id']; ?>" style="background-color: darkgreen; color: white; padding: 5px 10px; border: none; cursor: pointer;">Confirm</button>
                                    <?php } elseif ($row['status'] == 'Confirmed') { ?>
                                    <a href="medication.php?id=<?= $row['id']; ?>">Prescribe</a>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
                
        <div class="docPatients" id="docPatientsnav">
            <h2>Your Patients</h2>
            <table id="patientsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Medications</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $patients->fetch_assoc()) { 
                        $age = date_diff(date_create($row['DoB']), date_create('today'))->y;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['lastName']); ?></td>
                        <td><?= $age; ?></td>
                        <td><?= htmlspecialchars($row['Gender']); ?></td>
                        <td><?= nl2br(htmlspecialchars(str_replace(', ', "\n", $row['Medications']))); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <footer id="contactUsnav">
            <a href="indexDoctor.php"><img src="../img/Logo.png" alt="logo" class="footerlogo"></a>

            <div class="firstSec">
                <h4>TheraFlex</h4>
                <p style="font-style: italic;">Your health, your movement, our mission!</p>
            </div>
        
            <div class="contactInfo">
                <div class="FooterLocation">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADB0lEQVR4nO2ZSWsUURDHyw0VF0SUKAqCKAgKenL5AN5Eb1FPngVPKnjwEJQg3iTx4PYJHHDpV0Pr4ISq6o7jJAx6CehVkWBcQDFKEqMjlYwmmGR8PXmvp0f8Q0HT/Xjdv67lbQD/NVO9vfdXEQXHRcxNEexnNm+ZzVjN9LpPn0URHtO2kDWJPNjIjNeY8YsIVm1M2zLjLaJgW7O/H8IwXMpsLoqYYVuAmUBmTMRc1r6gGSIy65iNNAowi5WI8htShsBdzPjKIcSvcHupfacCUSwGbT4gpsMUi0GbVwgiWqYh4AtCpvLmsdecmUxsvxAyBXPJW4mdT3VqIMRG4zi/1TmIjhNpQchvMzecQhDlVorg5yaADOu7HYLotCPRB4yIYFcU5fcVCoUVanrNbLprzxKEWNDuDKQ2d7KFeB3HuHvun5Lfo20S5Mp1hyDYb+uJehB/wFh5htmUXYK8s/yDXbZ9Mpurlh4ZcgaipdASZK9tn0TBflsvgyvZhkGSClOrhDYe+eoQBN+4BimXw9WWOTLoEuR5s0KLGQdcghjLv9ftOtlFzB1nIDqBs01MLa1/60/bJCggFxyCBO1JBsR6MA0MiIedgURRuJ4Zvyd4+aiGjuaBFgA1ZnNA7yXwRFXEfNOiAC4lYp7Zf4Azi6GVF1Uy5ZGzzkGIzI40IZjNj56e/BbwIRF8mh4IMvgSEZ5MMayOegTR+ZH5mEJYDVYqlSXgU8zmSgog58G3iMLNSZerCSE+Ed1bA2lIl58ePdIJaalYxE0+9riYzYdS6eFaSFM6mfPgjVOQtkql3HKXm9nM+MJ7pZpLInjCFUgUBYegWapWOxYym8r8QcwjaLZoYpGkx2cNh5SeJW6HLIitV5CzVqrTkBWFE4eiONAARF8ul1sEWRJN7oqMJ8iLkTgOdkIWJYKdCXLjDGRVRLSYGXstIApa8SDLoolJJb6vAzGkx3jQChIJjuhSdRaQcebgILSSmE3HTG/kz0GrqVqtLhDB29Oq1F29B60omtyYK4vgE6eHmvAP6ycP5K7zdxrClQAAAABJRU5ErkJggg==" alt="location-icon">   
                    <p>123 Green Street, TheraFlex City</p>
                </div>
                <div class="FooterPhone">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAA+0lEQVR4nO2aQQrCMBREs3KlN9CleEb1RqVoScBt/kCP0GsU1AsoQXGhVLLIlIrz4G8CCUz+W45z/wxwXJmF2ixcgXArMY+3/KFtm82IIXxfKsBnIN/HeFrSg6RNsELgNb4aI0iOTtuuq2bvd9OZWdhlbOVCD5LzozFW86H7bdssct5wUwiSfn14I37/M0FQYJyCZKKNQGpxkFqQWhykFqQWB6kFqcVBakFqcZBakFocpBakFgepBanFQWpBanGQWpBaHKQWpBYHqQWpxaFk/+RL8+Hs2KTiCzuIWajpQVJ7h12qMQtrNwapvZOKL6kzUjDA5VmdGifEVLkDVENS09F6B0YAAAAASUVORK5CYII=" alt="phone-icon">
                    <p>+123 456 7890</p>
                </div>
                <div class="FooterEmail">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAABGUlEQVR4nO2UsUoDQRCGg3aCPoCgzxAi+AyJIKRXLPMKKW19BVvLaxJmFtv7/70UEQ7SxdqoD2Bhp5wsZzjxNmZzJ2hxA8MOO7v/tzuzbKvV2L8xQE9IeSI1q+fyCEivBCD1vr64LiHzEmAyGe+TelNXHFBNEjn0AXbdaK303TWrlMZa6X/V+t6DZ0AvXJymskPKFalvASd+J+V6Or3dy3XkFNAHXw9KV4xj0wbk7odTz0g9Lkos0TK3EvAJeQXMMIqi7Sy73ALMAJCX0PxaQCEkaZKYozw/OgB07NzF+Zx03Brf3iCAr8ahPQoGsAAtrDXnpJ65eN36jQHc0BtAVqFEVb4HXfkgPF+F9H4DAugijk23BGjsz+wD3UK/10xk5RoAAAAASUVORK5CYII=" alt="email-icon">            
                    <p>info@TheraFlex.com</p>
                </div>
            </div>
        </footer>
        <div class="copyrightSec">
            <p>&copy; 2025 TheraFlex. All rights reserved.</p>
        </div>

        <script src="../script/script.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
    const popupCard = document.getElementById("popupCard");
    const iconContainer = document.querySelector(".icon-container");

    function togglePopup(event) {
        // Check if the popup is hidden, then toggle its visibility
        if (popupCard.style.display === "none" || popupCard.style.display === "") {
            popupCard.style.display = "block";
        } else {
            popupCard.style.display = "none";
        }

        // Stop propagation to prevent the document click handler from hiding the popup immediately
        event.stopPropagation();
    }

    function hidePopup() {
        popupCard.style.display = "none";
    }

    // Add/remove class on scroll
    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            popupCard.classList.add("no-arrow");
        } else {
            popupCard.classList.remove("no-arrow");
        }
    });

    window.addEventListener("scroll", function () {
        // Check if the scroll position is beyond half the page height
        const scrollPosition = window.scrollY;
        const halfPageHeight = document.body.scrollHeight / 3;

        if (scrollPosition > halfPageHeight && popupCard.style.display === "block") {
            popupCard.style.display = "none"; // Hide the card
        }
    });

    // Attach event listeners
    iconContainer.addEventListener("click", togglePopup);
    document.addEventListener("click", hidePopup);

    // Prevent the popup from hiding if clicked
    popupCard.addEventListener("click", function (event) {
        event.stopPropagation();
    });
});
    document.querySelectorAll(".confirm-btn").forEach(button => {
        button.addEventListener("click", async function() {
            if (!confirm("Are you sure you want to confirm this appointment?")) return;

            const id = this.dataset.id;
            const row = this.closest("tr");
            const statusSpan = row.querySelector("span");

            try {
                const response = await fetch('confirm_appointment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                });

                const text = await response.text();
                console.log("Raw response:",text); // Log the response for debugging

                const data = JSON.parse(text); // Convert to JSON
                
                if (data.success) {
                    statusSpan.textContent = "Confirmed";
                    this.outerHTML = `<a href="medication.php?id=${id}">Prescribe</a>`;
                } else {
                    alert(data.message || "Failed to confirm appointment.");
                }
            } catch (error) {
                alert("Unexpected error: " + error.message);
            }
        });
    });
        </script>
    </body>
</html>