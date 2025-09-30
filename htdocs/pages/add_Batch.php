<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Batch</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
     <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            width: 236px; /* w-64 */
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

   
        .card {
            border: none;
        
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
       
            width: 100%;
        }
        .card-header {
            background-color: #141515ff;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            font-weight: 600;
            padding: 1.5rem;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
            color: #343a40;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
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
                    <a href="./add_Batch.php"  class="active">
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

<div class="card">
    <div class="card-header">
        <h2>Add New Batch</h2>
    </div>
    <div class="card-body p-4">
        <form id="batchForm">
            <div class="mb-4">
                <label for="batchName" class="form-label">Batch Name</label>
                <input type="text" class="form-control" id="batchName" placeholder="e.g., Batch 15" required>
            </div>
            <div class="d-grid d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Add Batch</button>
            </div>
        </form>
        <div id="message" class="mt-3"></div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("batchForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Prevent the default form submission

    const batchName = document.getElementById("batchName").value.trim();
    const messageDiv = document.getElementById("message");
    messageDiv.textContent = ""; // Clear previous messages
    messageDiv.className = "mt-3"; // Reset classes

    // Client-side validation: Check if batchName contains only numbers
    // This regex checks if the string contains ONLY digits from start to end.
    if (/^\d+$/.test(batchName)) {
        messageDiv.innerHTML = `<div class="alert alert-danger">Add again, not match with format (e.g., Batch 15).</div>`;
        return; // Stop the function here, do not send to server
    }

    // Display a "Saving..." message while waiting for the server response
    messageDiv.innerHTML = `<div class="alert alert-info">Saving...</div>`;

    fetch("save_batch.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        // Use URLSearchParams to properly encode the data for application/x-www-form-urlencoded
        body: new URLSearchParams({
            action: "add_batch",
            new_batch_name: batchName
        })
    })
    .then(response => {
        // Check if the response is OK (status 200-299)
        if (!response.ok) {
            // If not OK, throw an error to be caught by the .catch block
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json(); // Parse the JSON response
    })
    .then(data => {
        // Handle the JSON data from the PHP script
        if (data.success) {
            messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            document.getElementById("batchName").value = ""; // Clear the input field
        } else {
            messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    })
    .catch(err => {
        // Catch any errors during the fetch operation (network issues, server errors, JSON parsing errors)
        console.error("Fetch error:", err); // Log the error for debugging
        messageDiv.innerHTML = `<div class="alert alert-danger">An error occurred: ${err.message || err}. Please try again.</div>`;
    });
});



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
