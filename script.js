

/*========== Prescribe Medication  ==========*/
function navToDoctor(event){
    event.preventDefault();
    window.location.href="indexDoctor.html";
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
