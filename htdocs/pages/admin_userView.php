<?php
include("../includes/get_all_user.php");
include("../includes/db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["userid"], $_POST["isbanned"])) {
    header('Content-Type: application/json');

    if (!$conn) {
        echo json_encode(["success" => false, "error" => "Database connection not established."]);
        exit;
    }

    $userid = intval($_POST['userid']);
    $newBanStatus = intval($_POST['isbanned']);

    error_log("Attempting to update user ID: " . $userid . " to ban status: " . $newBanStatus);

    $stmt = $conn->prepare("UPDATE users SET isBanned = ? WHERE userid = ?");

    if ($stmt === false) {
        echo json_encode(["success" => false, "error" => "Prepare failed: (" . $conn->errno . ") " . $conn->error]);
        $conn->close();
        exit;
    }

    $stmt->bind_param("ii", $newBanStatus, $userid);

    if ($stmt->execute()) {
        // Log success
        error_log("Update successful for user ID: " . $userid);
        echo json_encode(["success" => true]);
    } else {
        // Log failure
        error_log("Update failed for user ID: " . $userid . ". Error: " . $stmt->error);
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

$users = get_all_users();
$batches = [];
$result = $conn->query("SELECT batch_id, batch_name FROM batch ORDER BY batch_id");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $batches[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Table UI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .container {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
          /* Added margin for better spacing on all screens */
        }

        /* Responsive Filters - Adjusted for horizontal display on mobile */
        .filters {
            display: flex;
            flex-wrap: wrap; /* Allow filters to wrap to the next line if needed */
            gap: 12px;
            margin-bottom: 15px;
            justify-content: flex-start; /* Align items to the start of the line */
            align-items: center; /* Vertically align items */
        }

        @media (max-width: 767px) { /* Styles for screens smaller than 768px */
            /* Keep filters horizontal with flexible widths */
            input[type="search"],
            select {
                flex-grow: 1; /* Allow them to grow and take available space */
                min-width: 100px; /* Ensure they don't get too small */
                box-sizing: border-box; /* Include padding and border in the element's total width */
            }
            #batchFilterWrapper {
                flex-grow: 1;
                min-width: 100px;
            }
        }

        input[type="search"], select {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Default Table Styles (for desktop) */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f3f4f6;
        }
        .active-btn {
            padding: 6px 12px;
            font-size: 13px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .active-view-btn {
            background-color: #3b82f6;
            color: white;
            margin-right: 5px;
        }
        .active-ban-btn {
            background-color: #ef4444;
            color: white;
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

            /* No .table-responsive needed with this approach, so remove it */
            /* Also remove min-width on table if using card layout */
        }
    </style>
</head>
<body>

    <button id="menuToggle" class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <aside class="sidebar sidebar-scroll" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-chart-line"></i>
            <h1>Admin Panel</h1>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="./adminPanel.php" >
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="admin_userView.php" class="active">
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
            <button>
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </div>
    </aside>

    <div class="main-content-area" id="mainContentArea">
        <div class="dashboard-main">
            <div class="container">
                <h2>Users</h2>
  <div class="filters">
    <input type="search" id="searchInput" placeholder="Search users">
    <select id="roleFilter">
      <option value="all">All</option>
      <option value="teacher">Teacher</option>
      <option value="Student">Student</option>
    </select>
    <span id="batchFilterWrapper">
      <select id="batchFilter">
        <option value="">All Batches</option>
    <?php foreach ($batches as $batch): ?>
      <option value="<?= htmlspecialchars($batch['batch_id']) ?>">
        <?= htmlspecialchars($batch['batch_name']) ?>
            <?php endforeach; ?>
      </option>
      </select>
    </span>
  </div>


                <table id="userTable">
                    <thead><tr id="tableHeaderRow"></tr></thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>
    </div>


<script>
// Corrected to use 'userType' from your database
const users = <?php echo json_encode(array_map(function($user) {
    return [
        "name" => $user['name'],
        "email" => $user['email'],
        "userid" => $user['userid'],
        "role" => !empty($user['userType']) ? $user['userType'] : "Student", // CHANGED FROM $user['role'] TO $user['userType']
        "batch" => $user['Batch'] ?? "",
        "phone" => $user['phone'] ?? "",
        "isbanned" => $user['isBanned'] ?? 0
    ];
}, $users)); ?>;

const tableBody = document.getElementById('tableBody');
const searchInput = document.getElementById('searchInput');
const roleFilter = document.getElementById('roleFilter');
const batchFilter = document.getElementById('batchFilter');
const batchFilterWrapper = document.getElementById('batchFilterWrapper');

let currentIndex = 0;
const rowsPerLoad = 10;
let filteredUsers = [];

function renderTableHeader() {
    const headerRow = document.getElementById('tableHeaderRow');
    // For card layout, we keep the desktop headers but hide them on mobile via CSS
    headerRow.innerHTML = `
        <th>Name</th>
        <th>Role</th>
        ${(roleFilter.value === 'Student' || roleFilter.value === 'all') ? `<th>Batch</th>` : ''}
        <th>Email</th>
        <th>Action</th>
    `;
}

function applyFilters() {
    const query = searchInput.value.toLowerCase();
    const selectedRole = roleFilter.value;
    const selectedBatch = batchFilter.value;

    filteredUsers = users.filter(user =>
        user.name.toLowerCase().includes(query) &&
        (selectedRole === 'all' || user.role.toLowerCase() === selectedRole.toLowerCase()) &&
        ((selectedRole === 'Student' || selectedRole === 'all') ? (!selectedBatch || user.batch === selectedBatch) : true)
    );

    currentIndex = 0;
    tableBody.innerHTML = '';
    renderTableHeader(); // Re-render header to update based on role filter
    loadMore();
    setTimeout(checkIfScrollNeeded, 50);
}

// ... (previous code)

function loadMore() {
    const showBatch = roleFilter.value === 'Student' || roleFilter.value === 'all';
    const nextUsers = filteredUsers.slice(currentIndex, currentIndex + rowsPerLoad);

    nextUsers.forEach(user => {
        // Exclude user named "AI" and users with the role "Admin"
        if (user.name === "AI" || user.role.toLowerCase() === "admin") {
            return; // Skip this user
        }

        const row = document.createElement('tr');
        // Add data-label attributes for mobile card layout
        row.innerHTML = `
            <td data-label="Name">${user.name}</td>
            <td data-label="Role">${user.role}</td>
            ${showBatch && user.role === 'Student' ? `<td data-label="Batch">${user.batch}</td>` : (showBatch ? '<td data-label="Batch"></td>' : '')}
            <td data-label="Email">${user.email}</td>
            <td data-label="Actions">
                <button class="active-btn active-view-btn" onclick="window.location.href='otherprofile.php?user_id=${user.userid}'">View</button>
                <button class="active-btn active-ban-btn" data-userid="${user.userid}" data-isbanned="${user.isbanned}">
                    ${user.isbanned == 1 ? 'Unban' : 'Ban'}
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });

    currentIndex += rowsPerLoad;
}

tableBody.addEventListener('click', function (e) {
    if (e.target.classList.contains('active-ban-btn')) {
    const button = e.target;
    const userid = button.getAttribute('data-userid');
    const currentStatus = parseInt(button.getAttribute('data-isbanned'));
    const newStatus = currentStatus === 1 ? 0 : 1;

    const actionText = newStatus === 1 ? 'ban' : 'unban';

    if (!confirm(`Are you sure you want to ${actionText} this user?`)) {
        return; // Exit if user cancels
    }
        button.disabled = true; // Disable button to prevent multiple clicks

        fetch(window.location.href, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `userid=${userid}&isbanned=${newStatus}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                button.textContent = newStatus === 1 ? 'Unban' : 'Ban';
                button.setAttribute('data-isbanned', newStatus);
                // Update the user's ban status in the local 'users' array
                const user = users.find(u => u.userid == userid);
                if (user) user.isbanned = newStatus;
            } else {
                alert('Update failed: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(err => {
            alert('Error occurred while updating ban status.');
            console.error(err);
        })
        .finally(() => {
            button.disabled = false; // Re-enable button
        });
    }
});

function checkIfScrollNeeded() {
    // This function ensures that if there's not enough content to trigger a scrollbar initially,
    // it keeps loading more data until the screen is filled or all data is loaded.
    if (document.body.scrollHeight <= window.innerHeight && currentIndex < filteredUsers.length) {
        loadMore();
        setTimeout(checkIfScrollNeeded, 50); // Re-check after loading more
    }
}

window.addEventListener('scroll', () => {
    // Implement infinite scrolling: load more users when near the bottom of the page
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100) {
        if (currentIndex < filteredUsers.length) {
            loadMore();
        }
    }
});

searchInput.addEventListener('input', applyFilters);
batchFilter.addEventListener('change', applyFilters);
roleFilter.addEventListener('change', () => {
    const showBatch = roleFilter.value === 'Student' || roleFilter.value === 'all';
    batchFilterWrapper.style.display = showBatch ? 'inline-block' : 'none';
    applyFilters(); // Apply filters whenever role changes (also affects batch visibility)
});

// Initial render when the page loads
renderTableHeader();
roleFilter.dispatchEvent(new Event('change')); // Trigger role filter change on load to set initial batch filter visibility and apply initial filters


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