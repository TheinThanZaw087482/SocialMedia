<?php
session_start();
include("includes/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Login And Registration Form Using HTML CSS & Javascript | Codehal</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assests/css/login.css">
</head>
<body>
    <div class="container">
        <div class="form-box login">
            <form id="loginForm" method="POST">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" placeholder="Id or Email" required name="signin_email">
                    <i class='bx bxs-user'></i>
                    <label id="emailError"></label>
                </div>

                <div class="input-box">
                    <input type="password" placeholder="Password" required name="signin_password">
                    <i class='bx bxs-lock-alt'></i>
                    <label id="passwordError"></label>
                </div>

                <div class="forgot-link">
                    <a href="#">Forgot password?</a>
                </div>
                <label id="loginMessage"></label>

        

                <button type="submit" class="uiverse" name="Login" >
                            <div class="wrapper">
                                <span>Sign In</span>
                                <div class="circle circle-12"></div>
                                <div class="circle circle-11"></div>
                                <div class="circle circle-10"></div>
                                <div class="circle circle-9"></div>
                                <div class="circle circle-8"></div>
                                <div class="circle circle-7"></div>
                                <div class="circle circle-6"></div>
                                <div class="circle circle-5"></div>
                                <div class="circle circle-4"></div>
                                <div class="circle circle-3"></div>
                                <div class="circle circle-2"></div>
                                <div class="circle circle-1"></div>
                            </div>
                </button>
                    <!-- <p>or login with social platforms</p>
                <div class="social-icons">
                   <a href="#"><i class='bx bxl-google'></i></a>
                   <a href="#"><i class='bx bxl-facebook'></i></a>
                   <a href="#"><i class='bx bxl-github'></i></a>
                   <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div> -->

            </form>
        </div>

         <div class="form-box register">
            <form id="register_form" method="POST">
                <h1>Registration</h1>
                <div class="input-box">
                    <input type="text" placeholder="ID" required  name="userID">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" placeholder="Username" required name="userName">
                    <i class='bx bxs-user'></i>
                </div>
                 <div class="input-box">
                    <input type="email" placeholder="Email" required name="Email">
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-box">
                    <input type="password" placeholder="Password" required name="password">
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="radio-inputs" style="margin-bottom: 20px;">
                        <label class="radio">
                            <input type="radio" name="gender" value="male" required />
                            <span class="name">Male</span>
                        </label>
                        <label class="radio">
                            <input type="radio" name="gender" value="female" />
                            <span class="name">Female</span>
                        </label>
                        <label class="radio">
                            <input type="radio" name="gender" value="other" />
                            <span class="name">Other</span>
                        </label>
                    </div>


                    <div style="display: flex; align-items: center; margin-bottom: 10px;" class="inline">
                        <label for="batch">Batch:</label>
                            <select id="batch" name="batch" style="margin-bottom: 10px;">
                                <option value="" disabled selected>Select your batch</option>
                                <option value="bt11">Batch 11</option>
                                <option value="bt12">Batch 12</option>
                                <option value="bt13">Batch 13</option>
                                <option value="bt14">Batch 14</option>
                                <option value="bt15">Batch 15</option>
                            </select>
                    </div>

                    <div style="display: flex; align-items: center; margin-bottom: 10px;" class="inline">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" style="margin-bottom: 20px;">
                    </div>


                

                <button type="submit" class="uiverse" name="sign_up" >
                            <div class="wrapper">
                                <span>Sign Up</span>
                                <div class="circle circle-12"></div>
                                <div class="circle circle-11"></div>
                                <div class="circle circle-10"></div>
                                <div class="circle circle-9"></div>
                                <div class="circle circle-8"></div>
                                <div class="circle circle-7"></div>
                                <div class="circle circle-6"></div>
                                <div class="circle circle-5"></div>
                                <div class="circle circle-4"></div>
                                <div class="circle circle-3"></div>
                                <div class="circle circle-2"></div>
                                <div class="circle circle-1"></div>
                            </div>
                    </button>

                    <!-- <p>or register with social platforms</p>
                <div class="social-icons">
                   <a href="#"><i class='bx bxl-google'></i></a>
                   <a href="#"><i class='bx bxl-facebook'></i></a>
                   <a href="#"><i class='bx bxl-github'></i></a>
                   <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div> -->

            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
            <h1>Hello, Welcome!</h1>
            <p>Don't have an account?</p>
            
                <button class="btn register-btn">
                    Register
                        <div class="arrow-wrapper">
                        <div class="arrow"></div>

                        </div>
                </button>
            
            </div>
            <div class="toggle-panel toggle-right">
            <h1>Welcome Back</h1>
            <p>Already have an account?</p>
           

            <button class="btn login-btn">
                 Sign In
                    <div class="arrow-wrapper">
                    <div class="arrow"></div>

                    </div>
           </button>
            </div>
        </div>
    </div>
</div>

    <script src="./assests/js/login.js"></script>
</body>
</html>
