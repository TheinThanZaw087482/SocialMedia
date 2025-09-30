<?php
session_start();
include("includes/db.php"); // Ensure this path is correct
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Login And Registration Form | Codehal</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./assests/css/login.css">
        <style>
        /* Ensure the form box is wide enough for horizontal layout */
        .form-box {
            max-width: 400px;
            /* Increased max-width to accommodate horizontal radios and new dropdown */
            /* Ensure it takes full width up to max-width */
        }

        .input-box {
            height: 30px;
            /* Standard height for mobile and smaller screens */
            position: relative;
            margin-bottom: 10px;
            /* Space below each input box */
        }

        /* Styles for the input field itself within the input-box */
        .input-box input {
            padding: 0 25px 0 8px;
            /* Adjust padding as needed for text inside the input */
            height: 90%;
            /* Make input take full height of its parent input-box */
            box-sizing: border-box;
            /* Include padding and border in the element's total width and height */
        }

        /* Styles for the icon within the input-box */
        .input-box i {
            position: absolute;
            right: 15px;
            /* Position icon to the right */
            top: 50%;
            /* Vertically center the icon */
            transform: translateY(-50%);
            font-size: 1.2rem;
            /* Standard icon size */
            color: #c1c3d9;
            /* Icon color */
            pointer-events: none;
            /* Make sure the icon doesn't interfere with input clicks */
        }

        -
        /* Media query for desktop size (e.g., screens wider than 768px) */
        @media (min-width: 768px) {
            .input-box {
                height: 68px;
                margin-bottom: 30px;
                /* **Medium height for desktop** - adjusted from 55px to 48px */
            }

            /* Keep icon size consistent or slightly adjusted if desired */
            .input-box i {
                font-size: 1.2rem;
                /* Matches default, or you can go to 1.1rem if it feels too big */
            }
        }

        /* New wrapper for horizontal radio buttons */
        .radio-options-wrapper {
            display: flex;
            flex-direction: row;
            /* Arrange items horizontally */
            justify-content: center;
            /* Center the radio buttons */
            gap: 20px;
            /* Reduced gap for smaller size */
            flex-wrap: wrap;
            /* Allow wrapping on smaller screens */
            margin-top: 20px;
            /* Space from the title */
            margin-bottom: 20px;
            /* Space before the end of the container */
            width: 100%;
            /* Ensure it takes full width within its parent */
        }

        .radio-label {
            display: flex;
            align-items: center;
            width: 40%;
            cursor: pointer;
            position: relative;
            user-select: none;
        }

        .radio-input {
            display: none;
        }

        .radio-custom {
            width: 18px;
            /* Smaller size */
            height: 18px;
            /* Smaller size */
            background-color: transparent;
            border: 2px solid #5c5e79;
            border-radius: 50%;
            margin-right: 12px;
            /* Adjusted margin */
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .radio-custom::before {
            content: "";
            position: absolute;
            width: 8px;
            /* Smaller size */
            height: 8px;
            /* Smaller size */
            background: #8a8b9f;
            border-radius: 50%;
            transform: scale(0);
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .radio-custom::after {
            content: "";
            position: absolute;
            width: 28px;
            /* Smaller size */
            height: 28px;
            /* Smaller size */
            border: 2px solid transparent;
            border-radius: 50%;
            border-top-color: #00a6ff;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.4s ease;
        }

        .radio-text {
            font-size: 0.95rem;
            /* Smaller font size */
            font-weight: 500;
            color: #c1c3d9;
            transition: color 0.3s ease;
        }

        .radio-label:hover .radio-input:not(:checked)+.radio-custom {
            transform: scale(1.1);
            border-color: #8a8daf;
        }

        .radio-label:hover .radio-text {
            color: #e2e4f4;
        }

        .radio-input:checked+.radio-custom {
            border-color: #00a6ff;
            transform: scale(0.9);
        }

        .radio-input:checked+.radio-custom::before {
            transform: scale(1);
            background-color: #00a6ff;
        }

        .radio-input:checked+.radio-custom::after {
            opacity: 1;
            transform: scale(1.3);
            animation: orbit 2.5s infinite linear;
            box-shadow:
                0 0 30px #00a6ff,
                0 0 80px rgba(0, 166, 255, 0.2);
        }

        .radio-input:checked~.radio-text {
            color: #ffffff;
            font-weight: 700;
        }

        /* Specific styles for the second radio button (Student) */
        .radio-options-wrapper .radio-label:nth-child(2) .radio-input:checked+.radio-custom {
            border-color: #e900ff;
            box-shadow: none;
        }

        .radio-options-wrapper .radio-label:nth-child(2) .radio-input:checked+.radio-custom::before {
            background-color: #e900ff;
        }

        .radio-options-wrapper .radio-label:nth-child(2) .radio-input:checked+.radio-custom::after {
            border-top-color: #e900ff;
            box-shadow:
                0 0 30px #e900ff,
                0 0 80px rgba(233, 0, 255, 0.2);
        }

        .radio-options-wrapper .radio-label:nth-child(2) .radio-input:checked~.radio-text {
            color: #ffffff;
        }

        /* This rule is for a third radio button, not currently in use but kept for completeness */
        .radio-options-wrapper .radio-label:nth-child(3) .radio-input:checked+.radio-custom {
            border-color: #00ffc2;
            box-shadow: none;
        }

        .radio-options-wrapper .radio-label:nth-child(3) .radio-input:checked+.radio-custom::before {
            background-color: #00ffc2;
        }

        .radio-options-wrapper .radio-label:nth-child(3) .radio-input:checked+.radio-custom::after {
            border-top-color: #00ffc2;
            box-shadow:
                0 0 30px #00ffc2,
                0 0 80px rgba(0, 255, 194, 0.2);
        }

        .radio-options-wrapper .radio-label:nth-child(3) .radio-input:checked~.radio-text {
            color: #ffffff;
        }

        @keyframes orbit {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }


        /* Mobile-First: Default styles for phone screens (small screens) */
        /* These styles will apply to screens smaller than 640px (phone size) */
        .date-and-batch-wrapper {
            display: flex;
            /* Use flexbox for horizontal layout */
            flex-direction: row;
            /* Arrange items horizontally on phone screens */
            justify-content: space-between;
            /* Distribute space evenly between items */
            gap: 15px;
            /* Space between the two elements */
            flex-wrap: wrap;
            /* Allow wrapping if the screen is extremely narrow, preventing overflow */
            align-items: center;
            /* Vertically align items in the middle */
            margin-top: 10px;
            /* Add some top margin for spacing from previous elements */
            margin-bottom: 20px;
            /* Add some bottom margin for spacing to next elements */
            width: 100%;
            /* Ensure the wrapper takes full available width */
            box-sizing: border-box;
            /* Include padding and border in the element's total width and height */
        }

        .batch-selection-container,
        .input-box.date-input-box {
            flex: 1;
            /* Allow both containers to grow and shrink, taking equal space */
            min-width: 130px;
            /* Ensure they don't get too small on phones */
            margin-bottom: 0;
            /* Remove any individual bottom margins when side-by-side */
            box-sizing: border-box;
            /* Crucial for consistent sizing */
        }

        /* Specific styling for the select dropdown (ensure consistency) */
        .batch-selection-container select {
            width: 100%;
            padding: 8px 10px;
            /* Consistent padding for better appearance */
            border: 1px solid #5c5e79;
            border-radius: 5px;
            background-color: transparent;
            color: #c1c3d9;
            font-size: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            -webkit-appearance: none;
            /* Remove default browser styling for dropdown */
            -moz-appearance: none;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23c1c3d9%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13.2-6.4H18.2c-7.9%200-14.8%205.8-16.1%2013.7-1.3%207.9%202.2%2015.8%208.5%2020.1l131.7%20131.7c4.7%204.7%2012.3%204.7%2017%200l131.7-131.7c6.3-4.3%209.8-12.2%208.5-20.1z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 10px top 50%;
            background-size: 12px auto;
            height: 38px;
            /* Consistent height for visual alignment with date input */
            line-height: normal;
            /* Ensure text aligns correctly */
            padding-right: 30px;
            /* Make space for the custom dropdown arrow */
            box-sizing: border-box;
        }

        /* Adjust the input field inside the date input box for consistency */
        .input-box.date-input-box input {
            width: 100%;
            padding: 8px 10px;
            /* Consistent padding */
            border: 1px solid #5c5e79;
            border-radius: 5px;
            background-color: transparent;
            color: #c1c3d9;
            box-sizing: border-box;
            height: 38px;
            /* Consistent height */
            line-height: normal;
        }


        /* Media Query for Desktop/Larger Screens (e.g., screens wider than 640px) */
        /* These styles will override the mobile-first styles when the screen is larger */
        @media (min-width: 640px) {
            .date-and-batch-wrapper {
                flex-direction: column;
                /* Stack vertically on desktop */
                gap: 10px;
                /* Adjust gap for vertical stacking */
                align-items: flex-start;
                /* Align items to the left when stacked */
            }

            .batch-selection-container,
            .input-box.date-input-box {
                width: 100%;
                /* Take full width when stacked vertically */
                /* You might want a small margin-bottom here if you prefer more space between stacked items */
                margin-bottom: 0;
            }
        }

        .textWHite {
            color: white;
        }

        /*Suiko*/

        .toggle-box::before {
            background: linear-gradient(85deg, #FF69B4, #5dade2);
        }

        .toggle-left {
            padding-left: 25px;
        }

        .toggle-right {
            padding-right: 25px;
        }

        @media screen and (max-width: 650px) {
            .form-box {
                margin-bottom: 45px;
                margin-right: 30px;
            }

            .form-box.login {
                margin-right: 50px;
            }

            .leftText {
                margin-bottom: 350px;
            }

            .rightText {
                margin-right: 100px;
                margin-bottom: 100px;
            }

            .toggle-panel.toggle-right {
                width: 200px;
            }

            .input-box.SignIn input {
                height: 35px;
                margin-top: 15px;
                padding: 0 30px 0 20px;
            }

            .input-box i {
                position: absolute;
                left: 240px;
                top: 18%;
                font-size: 20px;
            }

            .uiverse {
                margin-left: 70px;
            }

            p {
                max-width: 250px;
                width: 200px;
            }

            .toggle-box {
                height: 119%;
                pointer-events: none;
            }

            .form-box.register {
                padding-top: 130px;

            }

            .input-box.SignUp input {
                margin-top: 15px;
                height: 25px;

            }

            .input-box.SignUp i {
                position: absolute;
                left: 240px;
                top: 24%;
                font-size: 20px;

            }

            .textWHite.Register {
                margin-top: 20px;
            }

            /* neeew */

            .register-btn {
                pointer-events: auto;
                position: relative;
                z-index: 2;
            }

            .login-btn {
                pointer-events: auto;
                position: relative;
                z-index: 2;
            }

            
        }
    </style>
</head>

<body style="background:grey;">
    <div class="container" style="background:black;">
        <div class="form-box login textWHite" style="background:black;">
            <form id="loginForm" method="POST">
                <h1 class="textWHite" style="margin-bottom: 20px;">Login</h1>
                <div class="input-box SignIn">
                    <input type="text" placeholder="Id or Email" required name="signin_email">
                    <i class='bx bxs-user'></i>
                    <label id="emailError"></label>
                </div>

                <div class="input-box SignIn">
                    <input type="password" placeholder="Password" required name="signin_password">
                    <i class='bx bxs-lock-alt'></i>
                    <label id="passwordError"></label>
                </div><br>

                <div class="forgot-link">
                    <a href="#">Forgot password?</a><br>
                </div>
                <label id="loginMessage"></label>

                <button type="submit" class="uiverse" name="Login">
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
            </form>
        </div>

        <div class="form-box register" style="background:black;">
            <form id="register_form" method="POST">
                <h1 class="textWHite Register">Registration</h1>
                <div class="input-box SignUp">
                    <input type="text" placeholder="ID" required name="userID">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box SignUp">
                    <input type="text" placeholder="Username" required name="userName">
                    <i class="fa-solid fa-address-card"></i>
                </div>
                <div class="input-box SignUp">
                    <input type="email" placeholder="Email" required name="Email">
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-box SignUp">
                    <input type="password" placeholder="Password" required name="password">
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="radio-inputs" style="margin:10px 0px;">
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

                <div class="radio-options-wrapper" style="margin-bottom: 20px;">
                    <label for="studentRole" class="radio-label">
                        <input type="radio" id="studentRole" name="role" value="student" class="radio-input" required>
                        <div class="radio-custom"></div>
                        <span class="radio-text">Student</span>
                    </label>

                    <label for="otherRole" class="radio-label">
                        <input type="radio" id="otherRole" name="role" value="other" class="radio-input">
                        <div class="radio-custom"></div>
                        <span class="radio-text">Other</span>
                    </label>
                </div>

                <div class="date-and-batch-wrapper">
                    <div class="batch-selection-container">
                        <select id="batch" name="batch">
                            <option value="" disabled selected>Select option</option>
                        </select>
                    </div>

                    <div class="input-box date-input-box">
                        <input type="date" id="dob" name="dob" style="background-color: white;" required>
                    </div>
                </div>

                <button type="submit" class="uiverse SignUp" name="sign_up">
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
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <div class="leftText">
                    <h1>Hello, Welcome!</h1>
                    <p>Don't have an account?</p>

                    <button class="btn register-btn" style="background: linear-gradient(60deg, #884ea0, #f1948a);">
                        Register
                        <div class="arrow-wrapper">
                            <div class="arrow"></div>

                        </div>
                    </button>
                </div>

            </div>
            <div class="toggle-panel toggle-right" style="width: 300px;">
                <div class="rightText">
                    <h1>Welcome Back</h1>
                    <p>Already have an account?</p>

                    <button class="btn login-btn" style="background: linear-gradient(60deg, #8e44ad, #cd6155);">
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
    <script src="./assests/js/register.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const studentRoleRadio = document.getElementById('studentRole');
            const otherRoleRadio = document.getElementById('otherRole');
            const batchSelect = document.getElementById('batch');

            const allBatchOptions = {
                student: [{
                        value: "bt11",
                        text: "Batch 11"
                    },
                    {
                        value: "bt12",
                        text: "Batch 12"
                    },
                    {
                        value: "bt13",
                        text: "Batch 13"
                    },
                    {
                        value: "bt14",
                        text: "Batch 14"
                    },
                    {
                        value: "bt15",
                        text: "Batch 15"
                    }
                ],
                other: [{
                        value: "admin",
                        text: "Admin"
                    },
                    {
                        value: "teacher",
                        text: "Teacher"
                    } // Changed value to 'teacher'
                ]
            };

            function updateBatchOptions() {
                batchSelect.innerHTML = '<option value="" disabled selected>Select option</option>';

                let selectedOptions = [];
                if (studentRoleRadio.checked) {
                    selectedOptions = allBatchOptions.student;
                } else if (otherRoleRadio.checked) {
                    selectedOptions = allBatchOptions.other;
                }

                selectedOptions.forEach(optionData => {
                    const option = document.createElement('option');
                    option.value = optionData.value;
                    option.textContent = optionData.text;
                    batchSelect.appendChild(option);
                });
            }

            studentRoleRadio.addEventListener('change', updateBatchOptions);
            otherRoleRadio.addEventListener('change', updateBatchOptions);

            // Set initial state based on default checked radio (if any) or assume 'student' initially
            // If neither is checked by default, this will set it for 'student'
            if (studentRoleRadio.checked || !otherRoleRadio.checked) { // Default to student if none checked
                studentRoleRadio.checked = true;
            }
            updateBatchOptions();
        });
    </script>
</body>

</html>