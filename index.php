<?php
include 'db_connection.php';
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>TheraFlex - Home</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css">
        <link rel="icon" href="img/Logo.png" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Caveat:wght@400..700&family=Cinzel:wght@400..900&family=Dancing+Script:wght@400..700&family=Edu+AU+VIC+WA+NT+Pre:wght@400..700&family=Indie+Flower&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Satisfy&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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

             .help-text {
                color: #fff;
                font-size: 1.2rem;
                text-align: center;
            }
    
             .logo {
                max-width: 300px;
                margin-bottom: 10rem;
                margin-left: 10rem;
                
            }
    
            header {
                position: absolute;
                bottom: 20%;
                left: 85%;
                color: white;
                background-color: rgba(159, 183, 175, 0);
                text-align: center;
                z-index: 1;
                max-width: 40em;
                width: 100%;
            }
    
             .login-btn {
                margin-top: 1rem; 
                text-decoration: none;
                color: white;
                background-color: #22372d;
                padding: 0.8rem 1.8rem;
                border-radius: 5px;
                display: inline-block;
                font-size: 1.2rem;
            }
    
            .login-btn:hover {
                background-color: #8b8787;
            }
    
             .signup-text {
                margin-top: 1rem;
                color: #fff;
                font-size: 0.99rem;
                text-align: center;
            }
    
             .signup-text a {
                color: #22372d;
                text-decoration: underline;
            }
    
             .signup-text a:hover {
                color: #8b8787;
            }
    
            h1 {
                position: absolute;
                top: 35%; 
                left: 5%; 
                color: #22372d; 
                font-size: 2.3rem; 
                line-height: 0.9;
                margin: 0;
                max-width: 40em; 
                z-index: 2;
            }
    
            h1 span {
                display: block; 
            }
    
            #h1 {
                font-size: 3rem; 
                font-family: 'Dancing Script', cursive;
            }
    
            #h2 {
                font-size: 2.2rem;
                color: white; 
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
             
    
    
            }
    
    
            .auth-section {
        border-radius: 15%;
        background-color: rgba(90, 95, 73,0.6);
        padding: 3rem 2rem; 
    margin-left: 7rem;
        max-width: 25rem; 
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.6); 
    }
    
    
    
    
    
    
    
            @media (max-width: 1024px) {
                #h1 {
                    font-size: 3.7rem; 
                }
    
                #h2 {
                    font-size: 1rem; 
    
                }
    
                h1 {
                    line-height: 1;
                }
    
                .help-text {
                    margin-bottom: 0.5rem;
                    font-size: 0.9rem;
                }
    
                 .logo {
                    max-width: 250px;
                    margin-left: 7rem;        
                    }
    
                header {
                   
                    padding: 1rem;
                   
    
                bottom: 30%;
                left: 70%;
                max-width: 30em;
                width: 70%;
    
                }
    
                 .login-btn {
                    padding: 0.5rem 1rem;
                    font-size: 0.9rem;
                    display: inline-block;
                    margin-top: 0rem; 
    
    
                }
    
                 .signup-text {
                    margin-top: 0.5rem;
                    font-size: 0.8rem;
                }
    
                .auth-section {
        padding: 2rem 1rem; 
        max-width: 20rem; 
        margin-right: 1.6rem;
        margin-top: 10rem;
    }
    
            }
    
            @media screen and (max-width: 768px) {
                #h1 {
                    font-size: 3.3rem; 
                }
    
                #h2 {
                    font-size: 0.9rem; 
    
                }
    
                h1 {
                    line-height: 0.8;
                }
    
                .help-text {
                    margin-bottom: 0.5rem;
                    font-size: 0.9rem;
                }
    
                 .logo {
                    max-width: 230px;
                    margin-left: 7rem;        
                    }
    
                header {
                   
                    padding: 1rem;
                   
    
                bottom: 30%;
                left: 10%;
                max-width: 30em;
                width: 70%;
    
                }
    
                 .login-btn {
                    padding: 0.5rem 1rem;
                    font-size: 0.9rem;
                    display: inline-block;
                    margin-top: 0rem; 
    
    
                }
    
                 .signup-text {
                    margin-top: 0.5rem;
                    font-size: 0.8rem;
                }
    
                .auth-section {
        padding: 2rem 1rem; 
        max-width: 20rem; 
        margin-right: 1.6rem;
        margin-top: 10rem;
    }
    
            }
        </style>
    </head>
    <body>
        <div class="image-container">
            <img src="img/backgroundHome.png" alt="TheraFlix">
            <header>
                    <img src="img/Logo.png" alt="TheraFlix Logo" class="logo" style="opacity: 1;">
                    <h1 class="welcome-heading">
                        <span id="h1">Welcome to TheraFlix!</span>
                        <br> <span id="h2">clinic for personalized physical therapy and recovery.</span> 
                    </h1>
                    <div class="auth-section">
                        <div class="help-text">
                            Please log in to access your account.
                        </div>
                        <a href="LogIn.html" class="login-btn">Login</a>
                        <div class="signup-text">
                            Don't have an account? <a href="SignUp.html">Sign up</a>
                        </div>
                    </div>
                
            </header>
        </div>
    </body>
    </html>
    