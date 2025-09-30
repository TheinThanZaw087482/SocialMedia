<?php
include("../includes/db.php");
include("../includes/get_adminPanel.php");

// ===== User registrations per day =====
$userData = [];
$userSql = "
    SELECT DATE(created_at) AS reg_date, COUNT(*) AS count
    FROM users
    GROUP BY DATE(created_at)
    ORDER BY reg_date ASC
";

$userResult = $conn->query($userSql);

if ($userResult) {
    while ($row = $userResult->fetch_assoc()) {
        $userData[] = $row;
    }
} else {
    die("User query failed: " . $conn->error);
}

// ===== Posts per day =====
$postData = [];
$postSql = "
    SELECT DATE(postdate) AS post_date, COUNT(*) AS count
    FROM post
    GROUP BY DATE(postdate)
    ORDER BY post_date ASC
";

$postResult = $conn->query($postSql);

if ($postResult) {
    while ($row = $postResult->fetch_assoc()) {
        $postData[] = $row;
    }
} else {
    die("Post query failed: " . $conn->error);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Admin Dashboard</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Inter font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6; /* Light gray background */
            display: flex;
            height: 100vh;
            overflow: hidden;
            margin: 0;
        }

        /* Custom scrollbar for sidebar */
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f7fafc; /* thumb and track color */
        }
        .sidebar-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 10px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
            border: 2px solid #f7fafc;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 206px; /* w-64 */
            background-color: #1f2937; /* bg-gray-800 */
            color: white;
            display: flex;
            flex-direction: column;
            border-top-right-radius: 0.5rem; /* rounded-r-lg */
            border-bottom-right-radius: 0.5rem; /* rounded-r-lg */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* shadow-lg */
            overflow-y: auto;
            position: fixed; /* Make it fixed for mobile overlay */
            height: 100%;
            left: -256px; /* Initially off-screen for mobile */
            transition: left 0.3s ease-in-out;
            z-index: 999; /* Ensure it's above other content */
        }

        .sidebar.active {
            left: 0; /* Show sidebar */
        }

        @media (min-width: 768px) { /* On larger screens, sidebar is always visible */
            .sidebar {
                position: relative;
                left: 0;
                border-radius: 0.5rem; /* Apply border-radius for desktop */
            }
        }

        .sidebar-header {
            padding: 1.5rem; /* p-6 */
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #374151; /* border-b border-gray-700 */
        }
        .sidebar-header i {
            font-size: 2rem; /* text-3xl */
            color: #60a5fa; /* text-blue-400 */
            margin-right: 0.5rem; /* mr-2 */
        }
        .sidebar-header h1 {
            font-size: 1.5rem; /* text-2xl */
            font-weight: 700; /* font-bold */
        }
        .sidebar-nav {
            flex-grow: 1;
            padding: 1rem; /* p-4 */
        }
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-nav li {
            margin-bottom: 0.5rem; /* mb-2 */
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.75rem; /* p-3 */
            border-radius: 0.5rem; /* rounded-lg */
            color: white;
            text-decoration: none;
            transition: background-color 0.2s ease; /* transition-colors duration-200 */
        }
        .sidebar-nav a:hover {
            background-color: #374151; /* hover:bg-gray-700 */
        }
        .sidebar-nav a.active {
            background-color: #374151; /* bg-gray-700 */
            color: #93c5fd; /* text-blue-300 */
        }
        .sidebar-nav a i {
            margin-right: 0.75rem; /* mr-3 */
        }
        .sidebar-nav span {
            margin-left: auto; /* ml-auto */
            background-color: #ef4444; /* bg-red-500 */
            color: white;
            font-size: 0.75rem; /* text-xs */
            font-weight: 600; /* font-semibold */
            padding: 0.25rem 0.5rem; /* px-2 py-1 */
            border-radius: 9999px; /* rounded-full */
        }
        .sidebar-footer {
            padding: 1rem; /* p-4 */
            border-top: 1px solid #374151; /* border-t border-gray-700 */
        }
        .sidebar-footer button {
            width: 100%; /* w-full */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem; /* p-3 */
            border-radius: 0.5rem; /* rounded-lg */
            background-color: #dc2626; /* bg-red-600 */
            color: white;
            font-weight: 700; /* font-bold */
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease; /* transition-colors duration-200 */
        }
        .sidebar-footer button:hover {
            background-color: #b91c1c; /* hover:bg-red-700 */
        }
        .sidebar-footer button i {
            margin-right: 0.5rem; /* mr-2 */
        }

        /* Main Content Area */
        .main-content-area {
            flex: 1; /* flex-1 */
            display: flex;
            flex-direction: column;
            overflow: hidden;
            margin-left: 0; /* Default for mobile */
            transition: margin-left 0.3s ease-in-out;
        }

        @media (min-width: 768px) {
            .main-content-area {
                margin-left: 0px; /* Adjust for fixed sidebar on desktop */
            }
        }

        .dashboard-main {
            flex: 1; /* flex-1 */
            overflow-y: auto;
            padding: 1.5rem; /* p-6 */
            background-color: #f3f4f6; /* bg-gray-100 */
            border-bottom-right-radius: 0.5rem; /* rounded-br-lg */
        }
        .dashboard-main h2 {
            font-size: 1.875rem; /* text-3xl */
            font-weight: 700; /* font-bold */
            color: #1f2937; /* text-gray-800 */
            margin-bottom: 1.5rem; /* mb-6 */
        }

        /* Hamburger menu button for mobile */
        .menu-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem; /* Changed from left to right */
            z-index: 1000;
            background-color: #1f2937;
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            display: block; /* Show on mobile */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        @media (min-width: 768px) {
            .menu-toggle {
                display: none; /* Hide on desktop */
            }
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr; /* grid-cols-1 for mobile */
            gap: 1.5rem; /* gap-6 */
            margin-bottom: 2rem; /* mb-8 */
        }
        @media (min-width: 768px) { /* md: */
            .stats-grid {
                grid-template-columns: repeat(2, 1fr); /* md:grid-cols-2 */
            }
        }
        @media (min-width: 1024px) { /* lg: */
            .stats-grid {
                grid-template-columns: repeat(4, 1fr); /* lg:grid-cols-4 */
            }
        }

        .stat-card {
            background-color: white;
            border-radius: 0.75rem; /* rounded-xl */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* shadow-lg */
            padding: 1.5rem; /* p-6 */
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s ease; /* transition-transform transform */
        }
        .stat-card:hover {
            transform: scale(1.05); /* hover:scale-105 */
        }
        .stat-icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 4rem; /* w-16 */
            height: 4rem; /* h-16 */
            border-radius: 9999px; /* rounded-full */
        }
        .stat-icon-container i {
            font-size: 2rem; /* text-3xl */
        }
        .stat-text-right {
            text-align: right;
        }
        .stat-text-right p {
            color: #6b7280; /* text-gray-500 */
            font-size: 0.875rem; /* text-sm */
        }
        .stat-text-right h3 {
            font-size: 1.875rem; /* text-3xl */
            font-weight: 700; /* font-bold */
            color: #1f2937; /* text-gray-800 */
        }

        /* Specific card colors */
        .stat-card:nth-child(1) .stat-icon-container {
            background-color: #dbeafe; /* bg-blue-100 */
            color: #2563eb; /* text-blue-600 */
        }
        .stat-card:nth-child(2) .stat-icon-container {
            background-color: #dcfce7; /* bg-green-100 */
            color: #16a34a; /* text-green-600 */
        }
        .stat-card:nth-child(3) .stat-icon-container {
            background-color: #ede9fe; /* bg-purple-100 */
            color: #7c3aed; /* text-purple-600 */
        }
        .stat-card:nth-child(4) .stat-icon-container {
            background-color: #fefce8; /* bg-yellow-100 */
            color: #ca8a04; /* text-yellow-600 */
        }

        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr; /* grid-cols-1 for mobile */
            gap: 1.5rem; /* gap-6 */
            margin-bottom: 2rem; /* mb-8 */
        }
        @media (min-width: 1024px) { /* lg: */
            .charts-grid {
                grid-template-columns: repeat(2, 1fr); /* lg:grid-cols-2 */
            }
        }
        .chart-card {
            background-color: white;
            border-radius: 0.75rem; /* rounded-xl */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* shadow-lg */
            padding: 1.5rem; /* p-6 */
        }
        .chart-card h3 {
            font-size: 1.25rem; /* text-xl */
            font-weight: 600; /* font-semibold */
            color: #1f2937; /* text-gray-800 */
            margin-bottom: 1rem; /* mb-4 */
        }
        .chart-placeholder {
            height: 16rem; /* h-64 */
            background-color: #f9fafb; /* bg-gray-50 */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem; /* rounded-lg */
            color: #9ca3af; /* text-gray-400 */
        }
    </style>
</head>
<body>
    <!-- Hamburger menu button for mobile -->
    <button id="menuToggle" class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar sidebar-scroll" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-chart-line"></i>
            <h1>Admin Panel</h1>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="#" class="active">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="admin_userView.php">
                        <i class="fas fa-users"></i>
                        Users
                    </a>
                </li>
                
                <li>
                    <a href="./add_Batch.php">
                        <i class="fas fa-chart-bar"></i>
                      Add Batch
                    </a>
                </li>
              
                <li>
                    <a href="./adminNoti.php">
                        <i class="fas fa-bell"></i>
                        Notifications
                        <span>5</span>
                    </a>
                </li>

                <li>
                    <a href="./Dashboard.php">
                        <i class="fa-solid fa-home"></i>
                       Home
                    </a>
                </li>
            </ul>
        </nav>


        <div class="sidebar-footer">
    <button id="logoutBtn">
        <i class="fas fa-sign-out-alt"></i>
        Logout
    </button>
</div>
        <script>
    document.getElementById('logoutBtn').addEventListener('click', function() {
        // Redirect to Dashboard.php
        window.location.href = '../index.php'; // Or '../Dashboard.php' depending on your file structure
    });
</script>
    </aside>

    
    <!-- Main Content Area -->
    <div class="main-content-area" id="mainContentArea">
        <!-- Dashboard Content -->
        <main class="dashboard-main">
            <h2>Dashboard Overview</h2>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <!-- Card 1: Total Users -->
                <div class="stat-card">
                    <div class="stat-icon-container">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-text-right">
                        <p>Total Users</p>
                            <?php
                                $totalUsers = total_Users(); // $totalUsers is already an integer
                                $count = $totalUsers; // Simply assign the integer value
                            ?>
                        <h3><?= $count ?></h3>
                    </div>
                </div>

                <!-- Card 2: New Posts -->
                <div class="stat-card">
                    <div class="stat-icon-container">
                        <i class="fas fa-plus-square"></i>
                    </div>
                    <div class="stat-text-right">
                        <p>New Posts Today</p>
                        <?php
                            $dt = new DateTime('now', new DateTimeZone('Asia/Yangon'));
                            $todayStr = $dt->format('Y-m-d');
                            $tposts = today_Posts($todayStr);
                        ?>
                        <h3><?= $tposts ?></h3>
                    </div>
                </div>

                <!-- Card 3: New Users -->
                <div class="stat-card">
                    <div class="stat-icon-container">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stat-text-right">
                        <p>New Users</p>
                        <?php
                                $newUsers = new_Users(); // $totalUsers is already an integer
                                $count = $newUsers; // Simply assign the integer value
                            ?>
                        <h3><?= $count ?></h3>
                    </div>
                </div>

                <!-- Card 4: Active Users -->
                <div class="stat-card">
                    <div class="stat-icon-container">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-text-right">
                        <p>Active Users</p>
                        <?php
                                $activeUsers = active_Users(); // $totalUsers is already an integer
                                $count = $activeUsers; // Simply assign the integer value
                            ?>
                        <h3><?= $count ?></h3>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <!-- Charts Section -->
            <div class="charts-grid">
    <!-- User Registration Chart -->
    <div class="chart-card">
        <h3>User Registrations Per Day</h3>
        <div class="chart-placeholder">
            <canvas id="userGrowthChart"></canvas>
        </div>
    </div>
            <!-- Post Activity Chart -->
                <div class="chart-card">
                    <h3>Daily Post Activity</h3>
                    <!-- Placeholder for a chart library -->
                    <div class="chart-placeholder">
                        <canvas id="postActivityChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Load Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Load your custom JS file that creates the charts -->
<script src="../assests/js/chart.js"></script>

    <script>
const userLabels = <?= json_encode(array_column($userData, 'reg_date')) ?>;
const userCounts = <?= json_encode(array_column($userData, 'count')) ?>;

const postLabels = <?= json_encode(array_column($postData, 'post_date')) ?>;
const postCounts = <?= json_encode(array_column($postData, 'count')) ?>;

        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContentArea = document.getElementById('mainContentArea');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when a navigation link is clicked on mobile
        const sidebarLinks = document.querySelectorAll('.sidebar-nav a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) { // Only close on mobile
                    sidebar.classList.remove('active');
                }
            });
        });

        // Close sidebar if clicking outside when it's open on mobile
        document.addEventListener('click', (event) => {
            if (window.innerWidth < 768 && sidebar.classList.contains('active')) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = menuToggle.contains(event.target);
                if (!isClickInsideSidebar && !isClickOnToggle) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
