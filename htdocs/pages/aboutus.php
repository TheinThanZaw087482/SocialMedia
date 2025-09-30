<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Your Company Name</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom CSS for About Us Page */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            /* Light background */
            color: #343a40;
            /* Darker text for readability */
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Smooth transition for theme change */
            /* Theme-specific variables */
            --navbar-back-button-color: #343a40;
            /* Default color for back button text */
            --navbar-back-button-border: #343a40;
            /* Default border color for back button */
            --title-stroke-color: rgb(159, 196, 228);
            /* Muted grey for light theme stroke */
            --title-fill-color: rgb(243, 109, 152);
            /* Primary blue for light theme fill */
            --title-cursor-color: rgb(243, 109, 152);
            /* Cursor color for light theme */
        }

        /* Dark Theme Styles */
        body.dark-theme {
            background-color: #1a1a1a;
            /* Dark background */
            color: #e0e0e0;
            /* Light text */
            --navbar-back-button-color: #e0e0e0;
            /* Light color for back button text in dark theme */
            --navbar-back-button-border: #e0e0e0;
            /* Light border color for back button in dark theme */
            --title-stroke-color: #b0b0b0;
            /* Lighter grey for dark theme stroke */
            --title-fill-color: #82b1ff;
            /* Lighter blue for dark theme fill */
            --title-cursor-color: #82b1ff;
            /* Cursor color for dark theme */
        }

        .navbar {
            background-color: #ffffff;
            /* White navbar */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        body.dark-theme .navbar {
            background-color: #2c2c2c;
            /* Darker navbar */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        body.dark-theme .navbar-brand,
        body.dark-theme .nav-link {
            color: #e0e0e0 !important;
            /* Light text for navbar items in dark mode */
        }

        body.dark-theme .navbar-toggler-icon {
            filter: invert(1);
            /* Invert color for dark theme toggle icon */
        }

        /* Back button styling */
        .navbar .btn-outline-dark {
            color: var(--navbar-back-button-color);
            border-color: var(--navbar-back-button-border);
            transition: color 0.3s ease, border-color 0.3s ease;
        }

        .navbar .btn-outline-dark:hover {
            color: #fff;
            /* White text on hover */
            background-color: var(--navbar-back-button-border);
            /* Fill with border color on hover */
        }


        .hero-section {
            background: linear-gradient(to right, rgb(241, 83, 146), #2575fc);
            /* Gradient background */
            color: white;
            padding: 80px 0;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            margin-bottom: 40px;
            text-align: center;
            transition: background 0.3s ease;
        }

        body.dark-theme .hero-section {
            background: linear-gradient(to right, rgb(241, 83, 146), #2575fc);
            /* Darker gradient for hero */
        }

        .hero-section h1 {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 3rem;
            /* Larger heading */
        }

        .hero-section p {
            font-size: 1.15rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .content-section {
            padding: 40px 0;
        }

        .card {
            border: none;
            border-radius: 15px;
            /* Rounded corners for cards */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease, color 0.3s ease;
            background-color: #ffffff;
            /* Light card background */
        }

        body.dark-theme .card {
            background-color: #2c2c2c;
            /* Dark card background */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            color: #e0e0e0;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        body.dark-theme .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }


        /* Animated Title Styles (Typing Effect) */
        .animated-title {
            font-size: 2.2rem;
            /* Adjusted for h2 */
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: center;
            color: transparent;
            /* Make base text transparent */
            -webkit-text-stroke: 1px var(--title-stroke-color);
            /* Apply stroke */
            position: relative;
            white-space: nowrap;
            overflow: hidden;
            display: block;
            /* Ensures it takes its own line for centering */
            margin-bottom: 15px;
            /* Adjust margin as needed */
            width: fit-content;
            /* Allow width to adjust to content */
            margin-left: auto;
            /* Center the block */
            margin-right: auto;
            /* Center the block */
            line-height: 1.2;
            /* Adjust line height if needed */
        }

        .animated-title::before {
            content: attr(data-title);
            /* Use data-title for content */
            color: var(--title-fill-color);
            /* Filled color */
            border-right: 3px solid var(--title-cursor-color);
            /* Cursor effect */
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            /* Ensure it covers the text height */
            overflow: hidden;
            animation: typing-animation 6s linear infinite;
            /* Adjusted duration for smoother effect */
            box-sizing: border-box;
            /* Include padding and border in the element's total width and height */
        }

        @keyframes typing-animation {
            0% {
                width: 0%;
            }

            40% {
                width: 100%;
            }

            /* Type out */
            60% {
                width: 100%;
            }

            /* Hold */
            100% {
                width: 0%;
            }

            /* Erase */
        }

        /* Delay for subsequent titles (adjust as needed) */
        .card:nth-child(1) .animated-title::before {
            animation-delay: 0s;
        }

        .card:nth-child(2) .animated-title::before {
            animation-delay: 6s;
        }

        /* Starts after first one finishes a cycle */
        .card:nth-child(3) .animated-title::before {
            animation-delay: 12s;
        }

        .card:nth-child(4) .animated-title::before {
            animation-delay: 18s;
        }


        .team-member-card {
            text-align: center;
        }

        .team-member-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            /* Circular images */
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #007bff;
            /* Blue border */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        body.dark-theme .team-member-card img {
            border-color: #82b1ff;
            /* Lighter blue border in dark mode */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        .team-member-card h5 {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 5px;
            transition: color 0.3s ease;
        }

        body.dark-theme .team-member-card h5 {
            color: #e0e0e0;
            /* Light text for names */
        }

        .team-member-card p {
            color: #6c757d;
            /* Muted text for roles */
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        body.dark-theme .team-member-card p {
            color: #b0b0b0;
            /* Lighter muted text for roles */
        }

        .footer {
            background-color: #343a40;
            /* Dark footer */
            color: white;
            padding: 30px 0;
            text-align: center;
            margin-top: 50px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            transition: background-color 0.3s ease;
        }

        body.dark-theme .footer {
            background-color: #2c2c2c;
            /* Darker footer */
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .theme-toggle-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        body.dark-theme .theme-toggle-btn {
            background-color: #82b1ff;
            color: #1a1a1a;
        }

        body.dark-theme .theme-toggle-btn:hover {
            background-color: #5c8ce6;
        }


        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }

            .hero-section h1 {
                font-size: 2.5rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            .team-member-card img {
                width: 100px;
                height: 100px;
            }

            .theme-toggle-btn {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
                bottom: 15px;
                right: 15px;
            }

            .animated-title {
                font-size: 1.8rem;
                letter-spacing: 1px;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                padding: 40px 0;
            }

            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section p {
                font-size: 0.9rem;
            }

            .card {
                margin-bottom: 20px;
            }

            .animated-title {
                font-size: 1.5rem;
                letter-spacing: 0.5px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar (Optional - you can replace with your actual header.php content) -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">

            <?php
            // Make sure session_start() is at the very top of your PHP file,
            // before any HTML or output.
            // session_start(); // Assuming it's already at the top of your script

            if (isset($_SESSION['userid'])) {
                echo '<a class="btn btn-outline-dark fw-bold me-3" href="Dashboard.php">Back</a>';
            } else {
                echo '<a class="btn btn-outline-dark fw-bold me-3" href="../index.php">Back</a>';
            }
            ?>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#hero-section">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#content-section">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contactUs">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section" id="hero-section">
        <div class="container">
            <h1>About Our Website</h1>
            <p style="font-size:22px">We are a thoughtful team creating a gentle, student-centered space for Metro.
                Our platform makes it easy to stay informed, share moments, and connect in meaningful ways.
                Discover a digital environment where student life feels simple, warm, and truly yours.</p>
        </div>
    </header>

    <!-- Main Content Section -->
    <main class="content-section" id="content-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Our Story Card -->
                    <div class="card p-4">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4 animated-title" data-title="Our Story">Our Story</h2>
                            <p>Founded with a shared passion, we set out to build a unique digital world just for our Metro campus.Born in the classroom, built with passion.
                                We turned what we learned—HTML, CSS, JS, PHP—into something real.
                                Every feature reflects the lessons we’ve learned from our teachers and the challenges we’ve overcome together.
                                Working side by side, we built a digital home for Metro students.</p>
                            <p>Our website isn’t just a website —it’s a symbol of unity, creativity, and school spirit.
                                It’s more than code—it’s connection.
                                And this is only the beginning.
                                We believe in making campus life more connected, more joyful, and just a little more magical.</p>
                        </div>
                    </div>

                    <!-- Mission & Vision Card -->
                    <div class="card p-4">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4 animated-title" data-title="Mission & Vision">Mission & Vision</h2>
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <h4 class="text-center text-light mb-3">Our Mission</h4>
                                    <p class="text-center">
                                        To empower Metro students by offering a user-friendly platform that simplifies communication, shares valuable updates, and strengthens school connections.</p>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-center text-light mb-3">Our Vision</h4>
                                    <p class="text-center">To be the leading digital hub for student communities—built on collaboration, innovation, and a deep commitment to student life</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Our Team Section -->
                    <div class="card p-4">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4 animated-title" data-title="Meet Our Team">Meet Our Team</h2>
                            <p class="text-center mb-4">We're a diverse group of talented professionals united by a shared passion for this website .</p>
                            <div class="row text-center">
                                <!-- Team Member 1 -->
                                <div class="col-6 col-md-4 mb-4 team-member-card">
                                    <img src="../assests/images/members/hhs.png" alt="Team Member 1">
                                    <h5>Han Htet Soe</h5>
                                    <p class="text-lightmuted">Leader</p>
                                </div>
                                <!-- Team Member 2 -->
                                <div class="col-6 col-md-4 mb-4 team-member-card">
                                    <img src="../assests/images/members/ttz.jpg" alt="Team Member 2">
                                    <h5>Thein Than Zaw</h5>
                                    <p class="text-lightmuted">Backend</p>
                                </div>
                                <!-- Team Member 3 -->
                                <div class="col-6 col-md-4 mb-4 team-member-card">
                                    <img src="../assests/images/members/su.jpg" alt="Team Member 3">
                                    <h5>Su Latt</h5>
                                    <p class="text-lightmuted">Backend</p>
                                </div>
                                <!-- Team Member 4 -->
                                <div class="col-6 col-md-4 mb-4 team-member-card">
                                    <img src="../assests/images/members/myoaung.jpg" alt="Team Member 4">
                                    <h5>Myo Aung</h5>
                                    <p class="text-lightmuted">Frontend & Powerpoint</p>
                                </div>
                                <!-- Team Member 5 -->
                                <div class="col-6 col-md-4 mb-4 team-member-card">
                                    <img src="../assests/images/members/kp.jpg
                                    " alt="Team Member 5">
                                    <h5>Kyi Pyar Myat Htet</h5>
                                    <p class="text-lightmuted">Frontend</p>
                                </div>
                                <!-- Team Member 6 -->
                                <div class="col-6 col-md-4 mb-4 team-member-card">
                                    <img src="../assests/images/members/pytn.jpg" alt="Team Member 6">
                                    <h5>Phoo Yati Naing</h5>
                                    <p class="text-lightmuted">Frontend</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info Card -->
                    <div class="card p-4" id="contactUs">
                        <div class="card-body text-center">
                            <h2 class="card-title text-center mb-4 animated-title" data-title="Contact Us">Contact Us</h2>
                            <p>Have questions or want to learn more? Feel free to reach out!</p>
                            <p>Email: <a href="mailto:hanhtetsoe71823@gmail.com" class="text-decoration-none">hanhtetsoe71823@gmail.com</a></p>
                            <p>Phone: <a href="tel:+959770954586" class="text-decoration-none">+959 770954586</a></p>
                            <p>Address: Metro It and Japanese Langage Center ,Tharketa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Metro Media. All rights reserved.</p>
        </div>
    </footer>

    <!-- Theme Toggle Button -->
    <button id="themeToggle" class="theme-toggle-btn">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Bootstrap JS (bundle includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtn = document.getElementById('themeToggle');
            const body = document.body;

            // Set dark theme as default on load
            body.classList.add('dark-theme');
            localStorage.setItem('theme', 'dark');
            themeToggleBtn.innerHTML = '<i class="fas fa-sun"></i>'; // Change icon to sun

            // Event listener for theme toggle button
            themeToggleBtn.addEventListener('click', () => {
                if (body.classList.contains('dark-theme')) {
                    body.classList.remove('dark-theme');
                    localStorage.setItem('theme', 'light');
                    themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i>'; // Change icon to moon
                } else {
                    body.classList.add('dark-theme');
                    localStorage.setItem('theme', 'dark');
                    themeToggleBtn.innerHTML = '<i class="fas fa-sun"></i>'; // Change icon to sun
                }
            });
        });
    </script>
</body>

</html>