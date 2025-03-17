<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheraFlex - Sign Up</title>
    <link rel="stylesheet" href="style.css">

    <link rel="icon" href="img/Logo.png" type="image/x-icon">
    <style>

body{
                height: auto; 
                width: auto;

            }

.image-container {
    position: relative;
    max-width: 800px;
    height: auto; 

}

.image-container img {
    width: 200%;
    height: 100%; 
    object-fit: cover; 
    background-size: cover;
}



h1 {
    margin-bottom: 1rem;
    font-size: 2rem;
    line-height: 1.4;
    margin-top: 2rem;
    color: #333;
}

input, select {
    width: 100%;
    padding: 0.5rem 0;
    font-size: 0.85rem;
    border: none;
    border-bottom: 1px solid #22372d;
    border-radius: 0;
    background: transparent;
    transition: border-color 0.3s ease-in-out;
    margin-bottom: 1rem;
}

input:focus, select:focus {
    outline: none;
    border-bottom: 3px solid #22372d;
}

button {
    width: 100%;
    padding: 0.8rem;
    font-size: 0.9rem;
    text-align: center;
    background-color: #22372d;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 10px;
    transition: transform 0.2s, background-color 0.3s;
}

button:hover {
    background-color: #555;
    transform: translateY(-3px);
}

label {
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
    display: block;
    color: #333;
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.role-selection, .form-container {
    background-color: rgb(255, 255, 255, 0.6);
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 400px;
    text-align: center;
    display: none;
}

.role-selection {
    display: block;
    color: #22372d;
}

header {
    position: absolute;
    top: -5%;
    left: 90%;
    color: white;
    background-color: rgba(159, 183, 175, 0);
    padding: 2rem;
    text-align: center;
    z-index: 1;
    max-width: 40em;
    width: 100%;
}

.form-container form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

form button, 
form input[type="submit"] {
    margin-top: 0.8rem;
    width: auto;
    padding: 0.5rem 2rem;
    font-size: 0.9rem;
    text-align: center;
    display: block;
}

.form-container label,
.form-container input,
.form-container select {
    width: 100%;
    margin-bottom: 0.6rem;
}

.form-container label {
    font-size: 0.9rem;
    text-align: left;
}

h1.title {
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

input[type="radio"] {
    margin-right: 0.5rem;
}

label {
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
    display: inline-block;
    color: #333;
}


 @media (max-width: 1024px) {

h1{
    line-height: 1.3;
}



header {
bottom: 0%;
left: 50%;
color: white;
background-color: rgba(159, 183, 175, 0);
padding: 1rem;
text-align: center;
z-index: 1;
max-width: 50em;
width: 60%;
}



nav .login-btn {
margin-bottom: 0.5rem; 
padding: 0.5rem 1rem;
border-radius: 2.5px;
display: inline-block;
font-size: 0.5rem;
}



nav .signup-text {
margin-top: 0.5rem;
font-size: 1rem;
text-align: center;
}



}



@media screen and (max-width: 768px) {

    

    

    h1{
        line-height: 1;
    }



header {
    bottom: 0%;
    left: 15%;
    color: white;
    background-color: rgba(159, 183, 175, 0);
    padding: 1rem;
    text-align: center;
    z-index: 1;
    max-width: 30em;
    width: 70%;
}
}


    </style>
</head>
<body onload="initApp()">
    <div class="image-container">
        <img src="img/backgroundHome.png" alt="TheraFlix">
        <div class="welcome">
            <header>
                <div class="container">
                    <!-- Role Selection -->
                    <div class="role-selection" id="role-selection">
                        <h1>Sign Up</h1>
                        <p style="margin-bottom: 1rem;">Select your role to proceed:</p>
                        <div>
                            <label>
                                <input type="radio" name="role" value="patient" id="role-patient" required>
                                Patient
                            </label>
                            <label>
                                <input type="radio" name="role" value="doctor" id="role-doctor" required>
                                Doctor
                            </label>
                        </div>
                        <button onclick="showForm()">Continue</button>
                    </div>

                    <!-- Patient Form -->
                    <div class="form-container" id="patient-form">
                        <h1>Patient Sign-Up</h1>
                        <form action="signUpPhp.php" method="POST">
                            <input type="hidden" name="userType" value="patient">
                            
                            <label for="psignfirst-name">First Name</label>
                            <input type="text" name="firstName" required>
                        
                            <label for="psignlast-name">Last Name</label>
                            <input type="text" name="lastName" required>
                        
                            <label for="psignid">ID</label>
                            <input type="text" name="id" required>
                        
                            <label>Gender</label>
                            <input type="radio" name="gender" value="male" required> Male
                            <input type="radio" name="gender" value="female" required> Female
                        
                            <label for="psigndob">Date of Birth</label>
                            <input type="date" name="dob" required>
                        
                            <label for="psignemail">Email Address</label>
                            <input type="email" name="email" required>
                        
                            <label for="psignpassword">Password</label>
                            <input type="password" name="password" required>
                        
                            <button type="submit" name="register">Sign Up</button>
                        </form>
                    </div>


                    <!-- Doctor Form -->
                    <div class="form-container" id="doctor-form">
                        <h1>Doctor Sign-Up</h1>
                        <form action="signUpPhp.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="userType" value="doctor">
                        
                            <label for="doctor-first-name">First Name</label>
                            <input type="text" name="firstName" required>
                        
                            <label for="doctor-last-name">Last Name</label>
                            <input type="text" name="lastName" required>
                        
                            <label for="docsignid">ID</label>
                            <input type="text" name="id" required>
                        
                            <label for="photo">Photo</label>
                            <input type="file" name="photo" accept="image/*" required>
                        
                            <label for="docsignSpeciality">Speciality</label>
                            <select name="speciality" required>
                                <option value="">Select Speciality</option>
                                <option value="cardiology">Cardiology</option>
                                <option value="neurology">Neurology</option>
                                <option value="orthopedics">Orthopedics</option>
                                <option value="pediatrics">Pediatrics</option>
                            </select>
                        
                            <label for="docsignEmail">Email Address</label>
                            <input type="email" name="email" required>
                        
                            <label for="docsignPassword">Password</label>
                            <input type="password" name="password" required>
                        
                            <button type="submit">Sign Up</button>
                        </form>
                    </div>
                </div>
            </header>
        </div>
    </div>

<script src="script.js"></script>

</body>
</html>
