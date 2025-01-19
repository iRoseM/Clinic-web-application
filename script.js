
/*========== nav bar  ==========*/

function initStickyHeader() {
    const header = document.querySelector(".header");

    // Sticky header effect
    function handleScroll() {
        if (window.scrollY > 50) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }
    }

    window.onscroll = handleScroll;
}

function initHamburgerMenu() {
    const hamburger = document.getElementsByClassName("hamburger")[0];
    const navigation = document.getElementsByClassName("navigation")[0];

    // Toggle hamburger menu
    function toggleMenu() {
        navigation.classList.toggle("active");
    }

    hamburger.onclick = toggleMenu;
}

function stickyNavbar(event) {
    event.preventDefault();
    initStickyHeader();
    initHamburgerMenu();
}




/*========== Prescribe Medication  ==========*/
function navToDoctor(event){
    event.preventDefault();
    window.location.href="indexDoctor.html";
}

function confirmPendingAppointments(event) {
    event.preventDefault();
    // Get the table containing the appointments
    const table = document.getElementById("appointmentTable");
    const rows = table.getElementsByTagName("tr");

    // Loop through all rows except the header
    for (let i = 1; i < rows.length; i++) {
        const statusCell = rows[i].getElementsByTagName("td")[6]; // Get the status column
        if (statusCell.textContent.trim() === "Pending") {
            // Create a "Confirm" button
            const confirmButton = document.createElement("input");
            confirmButton.type = "button";
            confirmButton.value = "Confirm";
            confirmButton.id = "docAppointmentsConfirm";

            confirmButton.style.padding = "5px 10px";
            confirmButton.style.margin = "5px auto"; 
            confirmButton.style.width= "100%";
            confirmButton.addEventListener("mouseover", function() {
                confirmButton.style.backgroundColor = "white"; // Change color on hover
            });
        
            confirmButton.addEventListener("mouseout", function() {
                confirmButton.style.backgroundColor = "lightGrey"; // Change back to black when not hovered
            });
            confirmButton.style.boxSizing = "border-box";

            // Replace the text content with the button
            statusCell.textContent = ""; // Clear existing text
            statusCell.appendChild(confirmButton); // Append the button
        }
    }

/*========== Home - Doctor (chronological order)  ==========*/

function sortAppointments(event) {
        event.preventDefault();
        const table = document.getElementById('appointmentTable');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        // Helper function to parse Date and Time
        function parseDateTime(date, time) {
            const [day, month, year] = date.split('/').map(Number);
            const timeParts = time.match(/(\d+):?(\d+)?\s?(AM|PM)/i);
            let hours = parseInt(timeParts[1]);
            const minutes = parseInt(timeParts[2]) || 0;
            const isPM = timeParts[3].toUpperCase() === 'PM';
            if (isPM && hours !== 12) hours += 12; // Convert PM to 24-hour format
            if (!isPM && hours === 12) hours = 0; // Handle 12 AM as midnight
            return new Date(year, month - 1, day, hours, minutes);
        }

        // Sort rows by Date and Time
        rows.sort((rowA, rowB) => {
            const dateA = rowA.children[0].textContent.trim();
            const timeA = rowA.children[1].textContent.trim();
            const dateB = rowB.children[0].textContent.trim();
            const timeB = rowB.children[1].textContent.trim();

            return parseDateTime(dateA, timeA) - parseDateTime(dateB, timeB);
        });

        // Clear and re-append sorted rows
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
    }
}

function sortPatientsTable(event) {
    event.preventDefault(event);
    const table = document.getElementById("patientsTable");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.rows); // Convert rows to an array for sorting

    // Sort rows based on the text content of the first cell (Name column)
    rows.sort((a, b) => {
        const nameA = a.cells[0].textContent.trim().toLowerCase();
        const nameB = b.cells[0].textContent.trim().toLowerCase();
        return nameA.localeCompare(nameB);
    });

    // Re-insert sorted rows into the table body
    rows.forEach(row => tbody.appendChild(row));
}


/*========== Sign up  ==========*/

function showForm() {
    const role = document.querySelector('input[name="role"]:checked');
    if (!role) {
        alert("Please select a valid role.");
        return;
    }

    document.getElementById("role-selection").style.display = "none";

    if (role.value === "patient") {
        document.getElementById("patient-form").style.display = "block";
    } else if (role.value === "doctor") {
        document.getElementById("doctor-form").style.display = "block";
    }
}

function redirectToHome(event, role) {
    event.preventDefault();
    const form = role === "patient"
        ? document.getElementById("patient-form").querySelector("form")
        : document.getElementById("doctor-form").querySelector("form");

    if (form.checkValidity()) {
        window.location.href = role === "patient" ? "indexPatient.html" : "indexDoctor.html";
    } else {
        alert("Please fill out all required fields.");
    }
}


/*========== Booking Appointment  ==========*/

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

    document.getElementById("bookingFormSection").style.display = "block";
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

    window.location.href = "/patient-homepage.html"; 
}


/*========== Home - Patient (chronological order)  ==========*/

function sortPappointments(event) {
    event.preventDefault();
    // Get the table body where rows will be updated
    const tableBody = document.querySelector(".patAppointment tbody");

    // Get all rows from the table
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    // Helper function to parse date and time into a comparable format
    function parseDateTime(date, time) {
        const [day, month, year] = date.split("/").map(Number);
        const [hour, period] = time.split(/(AM|PM)/); // Split time into hours and period (AM/PM)
        let [hours, minutes] = hour.split(":").map(Number); // Handle "HH:MM" format
        if (period === "PM" && hours !== 12) hours += 12; // Convert PM to 24-hour format
        if (period === "AM" && hours === 12) hours = 0;   // Convert midnight to 00
        return new Date(year, month - 1, day, hours, minutes || 0); // Return a Date object
    }

    // Sort rows based on date and time
    rows.sort((rowA, rowB) => {
        const dateA = rowA.children[1].textContent.trim(); // Get date from second cell
        const timeA = rowA.children[0].textContent.trim(); // Get time from first cell
        const dateB = rowB.children[1].textContent.trim();
        const timeB = rowB.children[0].textContent.trim();
        
        const dateTimeA = parseDateTime(dateA, timeA);
        const dateTimeB = parseDateTime(dateB, timeB);
        return dateTimeA - dateTimeB; // Sort ascending
    });

    // Clear the table body and append sorted rows
    tableBody.innerHTML = "";
    rows.forEach(row => tableBody.appendChild(row));
}