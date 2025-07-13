<!DOCTYPE html>
<html>
<head>
    <title>Loader Effect</title>
    <style type="text/css">
        body {
            height: 100vh; /* Takes full viewport height */
            background-color: #222; /* Dark background */
            display: flex; /* Use flexbox for centering */
            flex-direction: column; /* Stack items vertically */
            justify-content: center; /* Center vertically */
            align-items: center; /* Center horizontally */
            margin: 0; /* Remove default body margin */
            overflow: hidden; /* Prevent scrollbars if content overflows (though unlikely here) */
            font-family: "Inter", sans-serif; /* Use Inter font */
        }

        ul {
            list-style: none; /* Remove bullet points */
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
            display: flex; /* Use flexbox for the list items */
            border-bottom: 10px solid #fff; /* White border at the bottom */
            margin-bottom: 30px; /* Add space below the loader */
        }

        ul li {
            width: 30px; /* Fixed width for each circle */
            height: 30px; /* Fixed height for each circle */
            background-color: #fff; /* White background for circles */
            border-radius: 50%; /* Make them perfectly round */
            margin: 0 10px; /* Horizontal margin between circles */
            animation: myani 0.8s linear infinite; /* Apply the animation */
        }

        /* Keyframe animation for the bouncing effect */
        @keyframes myani {
            0% {
                transform: translateY(0px); /* Start at original position */
            }
            50% {
                transform: translateY(-150px); /* Move up */
            }
            100% {
                transform: translateY(0px); /* Return to original position */
            }
        }

        /* Apply different animation delays to create a staggered effect */
        ul li:nth-child(1) {
            animation-delay: 0.45s;
        }

        ul li:nth-child(2) {
            animation-delay: 0.6s;
        }

        ul li:nth-child(3) {
            animation-delay: 0.3s;
        }

        ul li:nth-child(4) {
            animation-delay: 0s;
        }

        ul li:nth-child(5) {
            animation-delay: 0.1s;
        }

        /* Style for the message text */
        .message {
            color: #fff; /* White text color */
            font-size: 1.2em; /* Slightly larger font size */
            text-align: center; /* Center the text */
            padding: 0 20px; /* Add some horizontal padding */
            max-width: 80%; /* Limit width for better readability on large screens */
            position: absolute; /* Position it absolutely */
            top: 50px; /* Distance from the top */
            left: 50%; /* Start from the horizontal center */
            transform: translateX(-50%); /* Adjust to truly center the element */
        }

        /* Container for the buttons to manage their layout */
        .button-container {
            display: flex; /* Use flexbox to put buttons side-by-side */
            gap: 20px; /* Space between buttons */
            margin-top: 30px; /* Space above the buttons */
        }

        button {
            background-color: #007bff; /* Blue background for buttons */
            color: #fff; /* White text color */
            border: none; /* No border */
            padding: 10px 20px; /* Padding inside buttons */
            font-size: 1em; /* Font size */
            border-radius: 5px; /* Slightly rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: all 0.3s ease; /* Smooth transition for hover effects */
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-3px); /* Lift button slightly on hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add a subtle shadow on hover */
        }

        /* Media query for phone screen sizes */
        @media (max-width: 768px) {
            ul li {
                width: 20px; /* Smaller width for circles on small screens */
                height: 20px; /* Smaller height for circles on small screens */
                margin: 0 7px; /* Adjust margin for smaller circles */
            }

            @keyframes myani {
                0% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-100px); /* Adjust bounce height for smaller circles */
                }
                100% {
                    transform: translateY(0px);
                }
            }

            ul {
                border-bottom: 7px solid #fff; /* Adjust border thickness for smaller screens */
                margin-bottom: 20px; /* Adjust space below loader for smaller screens */
            }

            .message {
                font-size: 1em; /* Adjust font size for smaller screens */
                top: 30px; /* Adjust distance from top for smaller screens */
            }

            .button-container {
                flex-direction: column; /* Stack buttons vertically on small screens */
                gap: 15px; /* Adjust gap for vertical stacking */
                display:inline;
            }
        }
    </style>
</head>
<body>
    <p class="message" id ="Loading_message">Loging successful But Wait for admin approve. </p>
    <ul>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    <div class="button-container">
        <a href="../index.php"><button style="display:inline" >Back</button></a>
        <a href="aboutus.php"><button style="display:inline">Contact</button></a>
        
    </div>
    <script src="../assests/js/register.js"></script>
</body>
</html>