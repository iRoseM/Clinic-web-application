<?php
//error_reporting(E_ALL); 
//ini_set('log_errors','1'); 
//ini_set('display_errors','1'); 

session_start();
 // database connection
include 'db_connection.php';

// Ensure the patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: index.html?error= Sign up or log in account as a patient");
    exit();
}

$patientID = $_SESSION['user_id'];

// Fetch patient appointments
$appointmentSql = "SELECT a.*, d.firstName AS doctorFirstName, d.lastName AS doctorLastName, d.uniqueFileName
                   FROM Appointment a
                   JOIN Doctor d ON a.DoctorID = d.id
                   WHERE a.PatientID = ?
                   ORDER BY a.date, a.time";
$stmt = $conn->prepare($appointmentSql);
$stmt->bind_param("i", $patientID);
$stmt->execute();
$appointments = $stmt->get_result();

// Fetch patient information
$patientSql = "SELECT * FROM patient WHERE id = ?"; 
$stmt = $conn->prepare($patientSql);
$stmt->bind_param('i', $patientID);
$stmt->execute();
$patientResult = $stmt->get_result();
$patient = $patientResult->fetch_assoc();
?>

<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta charset="utf-8">
        <title>TheraFlex - Home</title>
        <link rel="stylesheet" href="../css/style.css">  
        <link rel="icon" href="../img/Logo.png" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    </head> 
    <body onload="stickyNavbar(event); sortPappointments(event); " > 
    
        <!-- Nav Bar -->
        <header class="header">
            <div class="logo">
                <a href="https://theraflix.infinityfreeapp.com/html/indexPatient.php"><img src="../img/Logo.png" alt="logo"></a>
                <span> <a href="https://theraflix.infinityfreeapp.com/html/indexPatient.php">TheraFlex</a></span>
            </div>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="navigation">
              <ul class="nav-links">
                <li><a href="indexPatient.php">Home</a></li>
                <li><a href="#patAppointmentnav">Appointments</a></li>
                <li><a href="#contactUsnav">Contact Us</a></li>
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
                        <h3 id="patName">Name: <?php echo htmlspecialchars($patient['firstName'] . ' ' . $patient['lastName']); ?></h3>
                        <p id="patEmail">Email: <?php echo htmlspecialchars($patient['emailAddress']); ?></p>
<!--                        <p id="patId">ID: <?php echo htmlspecialchars($patient['id']); ?></p>-->
                        <p id="patGender"><?php echo htmlspecialchars($patient['Gender']); ?></p>
<!--                        <p id="patDOB">DOB: <?php echo htmlspecialchars($patient['DoB']); ?></p>-->

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
            <img src="../img/patBanner.png" alt="Patient Banner">
            <h2>Welcome, <br><?php echo htmlspecialchars($patient['firstName']); ?>!</h2>
        </div>
        
        <div class="patAppointment" id="patAppointmentnav"> 
            <h2>Your Appointments</h2>
            <table> 
                <thead>
                    <tr> 
                        <th>Time</th>
                        <th>Date</th>
                        <th>Doctor's Name</th>
                        <th>Doctor's Photo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $appointments->fetch_assoc()) { 
                        if ($row['status'] === 'Done') {
                            continue; // Skip rows with status "Done"
                        }
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['time']); ?></td>
                        <td><?php echo htmlspecialchars($row['doctorFirstName'] . ' ' . $row['doctorLastName']); ?></td>
                        <td><img src="uploads/<?php echo htmlspecialchars($row['uniqueFileName']); ?>" width="50" height="50"></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><button class="cancel-btn" data-id="<?php echo $row['id']; ?>">Cancel</button></td>
                    </tr>
                    <?php } ?>
                    </tbody>
            </table>
            <p class="BookAppointment"><span><a href="AppointmentBooking.php">Book an appointment</a></span></p>
        </div>
        
        <script src="../script/script.js"> </script>
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
        </script>
    
    <footer id="contactUsnav">
        <a href="indexPatient.php"><img src="../img/Logo.png" alt="logo" class="footerlogo"></a>

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
    
    <?php if (isset($_GET['msg'])): ?>
    <script>
        alert("<?= htmlspecialchars($_GET['msg'], ENT_QUOTES) ?>");

        // Remove ?msg=... from the URL after showing the alert
        if (history.replaceState) {
            const cleanUrl = window.location.origin + window.location.pathname;
            history.replaceState(null, '', cleanUrl);
        }
    </script>
    <?php endif; ?>
    <script>
        document.querySelectorAll(".cancel-btn").forEach(button => {
        button.addEventListener("click", async function() {
        if (!confirm("Are you sure you want to cancel this appointment?")) return;
        
        const id = this.dataset.id;
        const row = this.closest("tr");
        
        try {
            const response = await fetch('cancelAppointment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                row.remove();
                alert(data.message);
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            alert(error.message);
        }
    });
});
</script>
</body>
</html>