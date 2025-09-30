<?php
include("../includes/db.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE name != 'AI' AND userType != 'admin' AND approve = 0";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userid'])) {
    $userid = intval($_POST['userid']);

    $stmt = $conn->prepare("UPDATE users SET approve = 1 WHERE userid = ?");
    $stmt->bind_param("i", $userid);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit; // Prevent the rest of the page from loading
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6; /* Light gray background */
            display: flex; /* Use flexbox for main layout */
            height: 100vh;
            overflow: hidden; /* Prevent body scrollbar */
            margin: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 236px; /* w-64 */
            background-color: #1f2937; /* bg-gray-800 */
            color: white;
            display: flex;
            flex-direction: column;
            border-top-right-radius: 0.5rem; /* rounded-r-lg */
            border-bottom-right-radius: 0.5rem; /* rounded-r-lg */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* shadow-lg */
            overflow-y: auto; /* Allow sidebar to scroll if content overflows */
            position: fixed; /* Make it fixed for mobile overlay */
            height: 100%;
            left: -256px; /* Initially off-screen for mobile */
            transition: left 0.3s ease-in-out;
            z-index: 999; /* Ensure it's above other content */ 
            margin-right:0;
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

        /* Hamburger menu button for mobile */
        .menu-toggle {
            position: absolute; 
            top: 1rem;
            right: 1rem;
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

        /* Main Content Area */
        .main-content {
            flex-grow: 1; 
            padding: 1rem; 
            transition: margin-left 0.3s ease-in-out;
            margin: 0; 
            position: relative; 
            display: flex; 
            flex-direction: column; 
            gap: 1rem; 
            width: 100%; 
            box-sizing: border-box; 
            overflow-y: auto; /* Allow main content to scroll */
            padding-bottom: 2rem; /* Add padding at the bottom */
        }

        @media (min-width: 768px) {
            .main-content {
                padding-left: 1rem; 
            }
        }

        /* Button Container */
        .button-container {
            display: flex;
            gap: 0.5rem; 
            z-index: 10;
            padding-top: 4rem; /* Adjusted for mobile to clear menu toggle */
            align-self: flex-end; 
            width: 100%; 
            justify-content: flex-end;
          
        }

        @media (min-width: 768px) {
            .button-container {
                position: absolute; 
                top: 1rem;
                right: 1rem;
                padding-top: 0; 
            }
        }

        .button-container button {
            background-color: #e2e8f0; 
            border: 1px solid #cbd5e1; 
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-weight: 500;
            color: #374151; 
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .button-container button:hover {
            background-color: #cbd5e1;
            border-color: #a0aec0;
        }

        /* User Table Styles */
        .user-table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 1.5rem; 
            display: none; 
            width: 100%; 
            overflow-x: auto; /* Keep this for table responsiveness */
            -webkit-overflow-scrolling: touch; 
            max-width: 1200px; 
            box-sizing: border-box; 
            margin-top:50px;
        }
        .user-table-container.active {
            display: block; 
        }

        .user-table {
            width: 100%; 
            border-collapse: collapse;
            min-width: 650px; 
        }

        .user-table th,
        .user-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0; 
            white-space: nowrap; 
        }

        .user-table th {
            font-weight: 600;
            color: #4a5568; 
            background-color: #f7fafc; 
        }

        .user-table tbody tr:hover {
            background-color: #f0f4f8; 
        }

        .user-table .action-buttons button {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            font-weight: 500;
            color: white;
            transition: background-color 0.2s ease;
            margin-right: 0.5rem;
        }

        .user-table .action-buttons .view-btn {
            background-color: #3b82f6; 
        }

        .user-table .action-buttons .view-btn:hover {
            background-color: #2563eb;
        }

        .user-table .action-buttons .approve-btn {
            background-color: #279342ff; 
        }

        .user-table .action-buttons .approve-btn:hover {
            background-color: #0d6645ff;
        }

        /* User Detail Card Styles */
        .user-detail-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            width: 100%;
            max-width: 400px; /* Adjust max-width as needed */
            margin: 0 auto; /* Center the card */
            display: none; /* Hidden by default */
            flex-direction: column;
            box-sizing: border-box;
            margin-top: 1rem; /* Adjust to sit below buttons naturally */
            overflow: hidden; /* Prevent internal scrollbars */
            flex-shrink: 0; /* Prevent the card from shrinking */
        }

        .user-detail-card.active {
            display: flex; /* Show when active */
        }

        .user-detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.75rem; 
            flex-wrap: wrap; /* Allow content to wrap if too long */
        }

        .user-detail-item:last-child {
            border-bottom: none; /* No border for the last item */
            padding-bottom: 0; /* Remove extra padding for the last item */
        }

        .user-detail-item .label {
            font-weight: 600;
            color: #4a5568;
            flex-shrink: 0; 
            margin-right: 1rem; 
            max-width: 40%; /* Adjust as needed */
        }

        .user-detail-item .value {
            color: #2d3748;
            text-align: right; 
            word-break: break-all; /* Break long words */
            flex-grow: 1; /* Allow value to take up remaining space */
        }

        /* Specific styling for the action buttons row within the card */
        .user-detail-item.actions-row {
            justify-content: flex-end; /* Align actions to the right */
            align-items: flex-end; /* Align actions to the bottom of the item */
            padding-top: 1rem;
            margin-top: 0.5rem; /* Add some space above the actions */
            border-top: 1px solid #e2e8f0; /* Separator for actions */
        }

        .user-detail-card .action-buttons {
            display: flex; /* Ensure buttons are side-by-side */
            gap: 0.5rem;
        }

        .user-detail-card .back-btn { /* New button style */
            background-color: #4a5568; /* Dark gray for back button */
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s ease;
            margin-top: 1.5rem; /* Space above the back button */
            align-self: flex-start; /* Align to the left/start if parent is flex-column */
        }

        .user-detail-card .back-btn:hover {
            background-color: #2d3748;
        }
        
        /* Responsive Table - Card Layout (No horizontal scroll) */
        @media (max-width: 767px) {
            table, thead, tbody, th, td, tr {
                display: block; /* Make all table elements behave like block elements */
            }

            thead tr {
                position: absolute; /* Hide the table header visually */
                top: -9999px;
                left: -9999px;
            }

            tr {
                border: 1px solid #ccc;
                margin-bottom: 15px; /* Add space between "cards" */
                border-radius: 8px; /* Rounded corners for each card */
                overflow: hidden; /* Ensure border-radius works */
            }

            td {
                border: none; /* Remove cell borders */
                border-bottom: 1px solid #eee; /* Add a separator between data points within a card */
                position: relative;
                padding-left: 50%; /* Make space for the "label" (th data) */
                text-align: right; /* Align actual data to the right */
                white-space: normal; /* Allow text to wrap within the card */
            }

            td:last-child {
                border-bottom: none; /* No border for the last item in a card */
                text-align: center; /* Center action buttons */
                padding-top: 15px;
                padding-bottom: 15px;
            }

            td::before {
                /* Create a "label" for each data point */
                content: attr(data-label); /* Use data-label attribute for the label text */
                position: absolute;
                left: 12px; /* Position the label on the left */
                width: 45%; /* Give space to the label */
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
                color: #333;
            }
        }


        /* Notification Section Styles */
        .notification-section {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            width: 100%;
            max-width: 1500px; /* Adjust as needed */
            margin-top: 4rem;
            display: none; /* Hidden by default */
            flex-direction: column;
            box-sizing: border-box;
            overflow-y: auto; /* Allow scrolling if many notifications */
        }

        .notification-section.active {
            display: flex; /* Show when active */
        }

        .notification-section h2 {
            font-size: 1.875rem; /* text-3xl */
            font-weight: 600; /* font-semibold */
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .notification-section .all-notifications-text {
            color: #6b7280; /* gray-500 */
            font-size: 0.875rem; /* text-sm */
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb; /* border-gray-200 */
            padding-bottom: 0.5rem;
        }

        .notification-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .notification-day .day-label {
            font-weight: 600;
            color: #4b5563; /* gray-700 */
            margin-bottom: 0.75rem;
            padding-top: 0.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .notification-day:first-child .day-label {
            border-top: none;
            padding-top: 0;
        }

        .notification-item {
            display: flex;
            align-items: flex-start; /* Align items to the top, so text wraps nicely */
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            background-color: #f9fafb; /* light gray background for each item */
            border: 1px solid #e5e7eb;
            position: relative; /* For positioning the more options button */
        }

        .notification-item .user-avatar {
            width: 40px; /* Adjust size as needed */
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0; /* Prevent avatar from shrinking */
        }

        .notification-item .notification-text {
            flex-grow: 1;
            font-size: 0.9375rem; /* Slightly larger text */
            color: #374151; /* Darker gray for text */
            line-height: 1.4;
        }

        .notification-item .user-name {
            font-weight: 600;
            color: #1f2937;
        }

        .notification-item .notification-time {
            font-size: 0.8125rem; /* text-xs */
            color: #6b7280; /* gray-500 */
            white-space: nowrap; /* Prevent time from wrapping */
            flex-shrink: 0; /* Prevent time from shrinking */
        }

        .notification-item .more-options-btn {
            background: none;
            border: none;
            color: #9ca3af; /* gray-400 */
            font-size: 1rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.25rem;
            transition: color 0.2s ease;
            position: absolute; /* Position relative to .notification-item */
            top: 0.5rem;
            right: 0.5rem;
        }

        .notification-item .more-options-btn:hover {
            color: #4b5563; /* Darker on hover */
        }

        .notification-item .view-info-btn {
            background-color: #3b82f6; /* Blue background */
            color: white;
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 0.375rem; /* Rounded corners */
            cursor: pointer;
            font-size: 0.875rem; /* text-sm */
            font-weight: 500;
            transition: background-color 0.2s ease;
            margin-top: 0.5rem; /* Space from text above */
            align-self: flex-end; /* Align button to the right within the item */
        }

        .notification-item .view-info-btn:hover {
            background-color: #2563eb; /* Darker blue on hover */
        }
    </style>
</head>
<body>
    <button id="menuToggle" class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-chart-line"></i>
            <h1>Admin Panel</h1>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="./adminPanel.php">
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
                    <a href="#"  class="active">
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
            <button>
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </div>
    </aside>

    <main class="main-content">
        <div class="button-container">
            <button id="newUserButton">New User</button>
            <button id="loadNotificationsBtn">Noti</button>
        </div>

        <div id="userTableContainer" class="user-table-container active">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Batch</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['userType']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Batch']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            // Ensure proper escaping for JS arguments
                            echo '<td class="action-buttons">
                                <button class="view-btn" onclick="showUserDetail(\'' . htmlspecialchars($row['name']) . '\', \'' . htmlspecialchars($row['userType']) . '\', \'' . htmlspecialchars($row['Batch']) . '\', \'' . htmlspecialchars($row['email']) . '\')">View</button>
                                <button class="approve-btn" onclick="approveUser(this, \'' . htmlspecialchars($row['userid']) . '\')">Approve</button>
                              </td>'; // Added htmlspecialchars for userid too, though json_encode also works.
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="userDetailCard" class="user-detail-card">
            <button id="backToListBtn">Back to List</button>
            <h2>User Details</h2>
            <p><strong>Name:</strong> <span id="detailName"></span></p>
            <p><strong>Role:</strong> <span id="detailRole"></span></p>
            <p><strong>Batch:</strong> <span id="detailBatch"></span></p>
            <p><strong>Email:</strong> <span id="detailEmail"></span></p>
            </div>
        <div id="notificationSection" class="notification-section">
            <p>No new notifications at the moment.</p>
        </div>
    </main>

    <script>
        // Ensure the DOM is fully loaded before trying to access elements
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar toggle for mobile
            document.getElementById('menuToggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
            });

            // Get all necessary elements
            const newUserButton = document.getElementById('newUserButton');
            const userTableContainer = document.getElementById('userTableContainer');
            const userDetailCard = document.getElementById('userDetailCard'); // Now this element exists!
            const backToListBtn = document.getElementById('backToListBtn');   // Now this element exists!
            const notificationSection = document.getElementById('notificationSection');
            const notiButton = document.getElementById('loadNotificationsBtn'); // Using the correct ID

            // Initial state: Show userTableContainer, hide others
            if (userDetailCard) userDetailCard.classList.remove('active');
            if (userTableContainer) userTableContainer.classList.add('active');
            if (notificationSection) notificationSection.classList.remove('active');

            // Event listener for "Noti" button
            if (notiButton) {
                notiButton.addEventListener('click', function() {
                    if (userTableContainer) userTableContainer.classList.remove('active');
                    if (userDetailCard) userDetailCard.classList.remove('active');
                    if (notificationSection) notificationSection.classList.add('active');
                });
            }

            // Event listener for "New User" button
            // Changed to just toggle userTableContainer active state and ensure others are hidden
            if (newUserButton) {
                newUserButton.addEventListener('click', function() {
                    if (userTableContainer) userTableContainer.classList.toggle('active');
                    
                    if (userDetailCard && userDetailCard.classList.contains('active')) {
                        userDetailCard.classList.remove('active');
                    }
                    if (notificationSection) {
                        notificationSection.classList.remove('active');
                    }
                });
            }

            // Add event listeners to all "View" buttons in the table
            // We select them AFTER the PHP has rendered the table
            const viewButtons = document.querySelectorAll('.user-table .view-btn');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr'); // Get the parent row of the clicked button
                    const name = row.children[0].textContent;
                    const role = row.children[1].textContent;
                    const batch = row.children[2].textContent;
                    const email = row.children[3].textContent;

                    // Call the globally defined showUserDetail function
                    showUserDetail(name, role, batch, email);
                });
            });

            // Event listener for the "Back to List" button
            if (backToListBtn) {
                backToListBtn.addEventListener('click', function() {
                    if (userDetailCard) userDetailCard.classList.remove('active'); // Hide the detail card
                    if (userTableContainer) userTableContainer.classList.add('active'); // Show the table
                    if (notificationSection) notificationSection.classList.remove('active'); // Hide notifications
                });
            }
        });

        // --- Global Functions (Accessible from onclick attributes) ---

        // Function to approve a user
        function approveUser(button, userid) {
            fetch('adminNoti.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'userid=' + encodeURIComponent(userid)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = button.closest('tr');
                    row.remove(); // Remove the row from the table on success
                } else {
                    alert('Failed to approve user: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
            });
        }

        // Function to show user detail card (single definition)
        function showUserDetail(name, role, batch, email) {
            // Get elements (defensive checking for null)
            const detailNameElement = document.getElementById('detailName');
            const detailRoleElement = document.getElementById('detailRole');
            const detailBatchElement = document.getElementById('detailBatch');
            const detailEmailElement = document.getElementById('detailEmail');
            const userTableContainer = document.getElementById('userTableContainer');
            const notificationSection = document.getElementById('notificationSection');
            const userDetailCard = document.getElementById('userDetailCard');

            // Populate details if elements exist
            if (detailNameElement) detailNameElement.textContent = name;
            if (detailRoleElement) detailRoleElement.textContent = role;
            if (detailBatchElement) detailBatchElement.textContent = batch;
            if (detailEmailElement) detailEmailElement.textContent = email;

            // Manage section visibility
            if (userTableContainer) userTableContainer.classList.remove('active'); // Hide the table
            if (notificationSection) notificationSection.classList.remove('active'); // Hide notifications
            if (userDetailCard) userDetailCard.classList.add('active');             // Show the detail card
            else console.error("Error: userDetailCard element not found in HTML!"); // This should no longer fire
        }
    </script>
</body>
</html>