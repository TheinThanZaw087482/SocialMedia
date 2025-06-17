<?php
session_reset();
include("../includes/db.php");
include("../process/post.php");
$userid = $_SESSION['userid'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assests/css/noti.css">
    <link rel="stylesheet" href="../assests/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Metro Book</title>
    <style>
        /* Optional: Custom styling for the dropdown or icon */
        .notification .menu-icon .dropdown-toggle::after {
            display: none;
            /* Hide default caret if you only want the icon */
        }

        .notification .menu-icon .fa-bars {
            cursor: pointer;
            padding: 5px;
            /* Add some padding to make it easier to click */
            color: #333;
            /* Example color */
        }

        .notification .menu-icon .dropdown-menu {
            min-width: auto;
            /* Adjust if needed */
        }
    </style>
</head>

<body style="margin-top: 80px;">
    <div>
        <?php
        include("../includes/header.php")
        ?>
    </div>

    <div class="container">
        <h2>Notifications</h2>

        <div class="section">
            <p class="section-title">All Notifications</p>
            <div id="notificationList">

            </div>
        </div>
    </div>
    <script src="../assests/js/noti.js"></script>
    <script>
        function timeAgo(dateStr) {
            const date = new Date(dateStr);
            const seconds = Math.floor((new Date() - date) / 1000);

            const intervals = {
                year: 31536000,
                month: 2592000,
                week: 604800,
                day: 86400,
                hour: 3600,
                minute: 60,
                second: 1
            };

            for (let key in intervals) {
                const interval = Math.floor(seconds / intervals[key]);
                if (interval >= 1) {
                    return interval + " " + key + (interval > 1 ? "s" : "") + " ago";
                }
            }

            return "just now";
        }

        function escapeHTML(str) {
            return String(str)
                .replace(/&/g, "&amp;")
                .replace(/"/g, "&quot;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");
        }

        function renderNotification(noti) {
            const time = timeAgo(noti.created_at);
            const name = escapeHTML(noti.name);
            const link = noti.link;
            const email = escapeHTML(noti.email);
            const gender = escapeHTML(noti.gender);
            const address = escapeHTML(noti.address);
            const birthdate = noti.birthdate;
            const senderID = noti.senderID;
            const batch = escapeHTML(noti.batch);
            const userType = escapeHTML(noti.userType || "");
            const modalId = `videoModal_${noti.id}`;

            switch (noti.type) {
                case "Register":
                    return `
    <div class="notification d-flex align-items-center p-2 border-bottom">
        <div class="content flex-grow-1">
            <div class="text"><strong>${name}</strong> requested to join Metro Media.</div>
            <div class="time text-muted small">${time}</div>
            <button type="button" class="btn btn-sm btn-outline-primary mt-1" data-bs-toggle="modal"
                data-bs-target="#${modalId}">
                View Info
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-labelledby="${modalId}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="../process/editprofile.php" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="${modalId}Label">User Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <label for="profileId" class="form-label">ID</label>
                                <input type="text" class="form-control" name="profileId" value="${senderID}" readonly>
                            </div>
                            <div class="mb-2">
                                <label for="profileName" class="form-label">Username</label>
                                <input type="text" class="form-control" name="profileName" value="${name}">
                            </div>
                            <div class="mb-2">
                                <label for="profileEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" name="profileEmail" value="${email}">
                            </div>
                            <div class="mb-2">
                                <label for="profileGender" class="form-label">Gender</label>
                                <input type="text" class="form-control" name="profileGender" value="${gender}">
                            </div>
                            <div class="mb-2">
                                <label for="profileBatch" class="form-label">Batch</label>
                                <input type="text" class="form-control" name="profileBatch" value="${batch}">
                            </div>
                            <div class="mb-2">
                                <label for="profileBirthday" class="form-label">Birthday</label>
                                <input type="date" class="form-control" name="profileBirthday" value="${birthdate}">
                            </div>
                            <div class="mb-2">
                                <label for="profileRole" class="form-label">Role</label>
                                <select id="profileRole" name="profileRole" class="form-select" required>
                                    <option value="" disabled ${userType === "" ? "selected" : ""}>Select User Role</option>
                                    <option value="admin" ${userType === "admin" ? "selected" : ""}>Admin</option>
                                    <option value="Teacher" ${userType === "Teacher" ? "selected" : ""}>Teacher</option>
                                    <option value="Student" ${userType === "Student" ? "selected" : ""}>Student</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" name="btn_user_accept">Accept</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Dropdown Menu -->
        <div class="menu-icon dropdown ms-auto" style="padding-left: 10px;">
            <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Delete the notification</a></li>
                <li><a class="dropdown-item" href="#">Turn off notifications about this status</a></li>
                <li><a class="dropdown-item" href="#">Report issue to Notifications Team</a></li>
            </ul>
        </div>
    </div>`;


                case "post":
                    return `
        <div class="notification d-flex align-items-center p-2 border-bottom">
                <img src="${userImg}" alt="user" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                <div class="content flex-grow-1">
                    <div class="text"><strong>${name}</strong> added new post.</div>
                    <div class="time text-muted small">${time}</div>
                </div>
                <div class="menu-icon dropdown ms-auto">
                    <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bars"></i>
                        </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item " href="#">Delete the notification</a></li>
                    <li><a class="dropdown-item" href="#">Turn off notifications about this status</a></li>
                    <li><a class="dropdown-item" href="#">Report issue to Notifications Team</a></li>
                    </ul>
                </div>
            </div>
      `;


            }
        }

        function fetchNotifications() {
            fetch('../process/fetch_notifications.php')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById("notificationList");
                    container.innerHTML = ""; // clear previous

                    if (data.length === 0) {
                        container.innerHTML = "<p>No notifications found.</p>";
                        return;
                    }

                    data.forEach(noti => {
                        container.innerHTML += renderNotification(noti);
                    });
                });
        }

        fetchNotifications();
        let intervalID = setInterval(fetchNotifications, 5000);

        document.addEventListener('shown.bs.modal', function() {
            clearInterval(intervalID);
        });

        document.addEventListener('hidden.bs.modal', function() {
            intervalID = setInterval(fetchNotifications, 5000);
        });
    </script>
</body>

</html>