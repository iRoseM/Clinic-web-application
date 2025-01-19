

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
                confirmButton.style.borderColor = "white"; // Change color on hover
            });
        
            confirmButton.addEventListener("mouseout", function() {
                confirmButton.style.borderColor = "grey"; // Change back to black when not hovered
            });
            confirmButton.style.boxSizing = "border-box";

            // Replace the text content with the button
            statusCell.textContent = ""; // Clear existing text
            statusCell.appendChild(confirmButton); // Append the button
        }
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
