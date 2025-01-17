

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
