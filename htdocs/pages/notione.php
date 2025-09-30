<?php
 // Add session_start() if you are using $_SESSION variables
include("../includes/db.php");
include("../process/post.php");
session_reset(); // This is unusual, session_reset() typically clears all session variables.

$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : null; // Check if userid is set
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assests/css/noti.css">
    <link rel="stylesheet" href="../assests/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        @media (max-width: 575.98px) {
            /* Targets mobile devices (extra small breakpoint) */

            .modal-dialog {
                max-width: 60%;
                /* Keep modal width constrained */
                width: auto;
                margin: 0.5rem auto;
                /* Reduce top/bottom margin further */
            }

            .modal-content {
                max-height: calc(100vh - 1rem);
                /* Tighter max-height, 100vh minus total dialog margin */
                overflow-y: auto;
                /* Still allow scrolling if content overflows this very tight height */
                -webkit-overflow-scrolling: touch;
            }

            .modal-header,
            .modal-footer {
                padding: 0.5rem 0.5rem !important;
                /* Significantly reduce header/footer padding */
            }

            .modal-title {
                font-size: 0.9rem !important;
                /* Smaller title font */
            }

            .btn-close {
                font-size: 0.7rem !important;
                /* Smaller close button if needed */
                padding: 0.2rem !important;
                /* Reduce padding around close button */
            }

            .modal-body {
                height: 50%; /* This height will make the modal body take 50% of the modal content's height */
                overflow-y: visible !important;
                /* Let modal-content handle scrolling */
                padding: 0.5rem 0.75rem !important;
                /* Significantly reduce modal body padding */
            }

            /* Target labels and inputs for smaller size and reduced spacing */
            .form-label {
                font-size: 0.75rem !important;
                /* Even smaller label font */
                margin-bottom: 0.1rem !important;
                /* Reduce space below labels to minimum */
            }

            .form-control,
            .form-select {
                font-size: 0.8rem !important;
                /* Smaller input/select font */
                padding: 0.25rem 0.5rem !important;
                /* Reduced padding for inputs/selects */
                min-height: auto !important;
                /* Prevent Bootstrap from setting a minimum height */
            }

            /* Reduce margins between form groups */
            .mb-1 {
                margin-bottom: 0.4rem !important;
                /* Reduce margin between fields */
            }

            /* Ensure the button is also compact */
            .btn {
                font-size: 0.8rem !important;
                /* Smaller button font */
                padding: 0.35rem 0.75rem !important;
                /* Reduced button padding */
            }

            /* Adjust row gutter if needed to bring columns closer horizontally */
            .row.gx-1>[class*="col-"] {
                padding-right: 0.1rem !important;
                padding-left: 0.1rem !important;
            }
        }
    </style>
</head>

<body style="margin-top: 80px;">
    <div>
        <?php include("../includes/header.php") ?>
    </div>

    <div class="container">
        <h2>Notifications</h2>
        <div class="section">
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
            const userType = escapeHTML(noti.userType || ""); // Ensure userType is available and escaped
            const modalId = `modal_user_info_${noti.id}`; // More specific ID for clarity

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

        <div class="modal fade" id="${modalId}" tabindex="-1" aria-labelledby="${modalId}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white py-1">
                        <h5 class="modal-title fs-6" id="${modalId}Label">User Information</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-1">
                        <form method="POST" action="../process/editprofile.php" enctype="multipart/form-data">
                            <div class="row gx-1">
                                <div class="col-12 col-sm-6">
                                    <div class="mb-1">
                                        <label for="profileId_${noti.id}" class="form-label form-label-sm fw-bold mb-0">ID</label>
                                        <input type="text" class="form-control form-control-sm" name="profileId"
                                            id="profileId_${noti.id}" value="${senderID}" readonly aria-label="User ID">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="mb-1">
                                        <label for="profileName_${noti.id}" class="form-label form-label-sm fw-bold mb-0">Username</label>
                                        <input type="text" class="form-control form-control-sm" name="profileName"
                                            id="profileName_${noti.id}" value="${name}" aria-label="Username">
                                    </div>
                                </div>
                            </div>

                            <div class="row gx-1">
                                <div class="col-12 col-sm-6">
                                    <div class="mb-1">
                                        <label for="profileEmail_${noti.id}" class="form-label form-label-sm fw-bold mb-0">Email</label>
                                        <input type="email" class="form-control form-control-sm" name="profileEmail"
                                            id="profileEmail_${noti.id}" value="${email}" aria-label="Email address">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="mb-1">
                                        <label for="profileGender_${noti.id}" class="form-label form-label-sm fw-bold mb-0">Gender</label>
                                        <input type="text" class="form-control form-control-sm" name="profileGender"
                                            id="profileGender_${noti.id}" value="${gender}" aria-label="Gender">
                                    </div>
                                </div>
                            </div>

                            <div class="row gx-1">
                                <div class="col-12 col-sm-6">
                                    <div class="mb-1">
                                        <label for="profileBatch_${noti.id}" class="form-label form-label-sm fw-bold mb-0">Batch</label>
                                        <input type="text" class="form-control form-control-sm" name="profileBatch"
                                            id="profileBatch_${noti.id}" value="${batch}" aria-label="Batch">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="mb-1">
                                        <label for="profileBirthday_${noti.id}" class="form-label form-label-sm fw-bold mb-0">Birthday</label>
                                        <input type="date" class="form-control form-control-sm"
                                            name="profileBirthday" id="profileBirthday_${noti.id}" value="${birthdate}" aria-label="Birthday">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-1">
                                <label for="profileRole_${noti.id}" class="form-label form-label-sm fw-bold mb-0">Role</label>
                                <select id="profileRole_${noti.id}" name="profileRole" class="form-select form-select-sm"
                                    aria-label="Select user role" required>
                                    <option value="" disabled ${userType === "" ? "selected" : ""}>Select User Role</option>
                                    <option value="admin" ${userType === "admin" ? "selected" : ""}>Admin</option>
                                    <option value="Teacher" ${userType === "Teacher" ? "selected" : ""}>Teacher</option>
                                    <option value="Student" ${userType === "Student" ? "selected" : ""}>Student</option>
                                </select>
                            </div>

                            <div class="d-grid mt-2">
                                <button type="submit" class="btn btn-success btn-sm" name="btn_user_accept">Save Changes</button>
                            </div>
                        </form>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>`;

              case "post":
    const userImg = "../assests/images/post_images/" + noti.profile;
    const postLink = `comment_postframe.php?postID=${noti.link}`; // Construct the link with postid

    return `
        <div class="notification d-flex align-items-center p-2 border-bottom">
            <img src="${userImg}" alt="user" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
            <div class="content flex-grow-1">
                <a href="${postLink}" class="text-decoration-none text-dark d-block"> <div class="text"><strong>${name}</strong> added new post.</div>
                    <div class="time text-muted small">${time}</div>
                </a>
            </div>
            <div class="menu-icon dropdown ms-auto">
                <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.stopPropagation();">
                    <i class="fa-solid fa-bars"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" onclick="event.stopPropagation();">
                    <li><a class="dropdown-item" href="#">Delete the notification</a></li>
                    <li><a class="dropdown-item" href="#">Turn off notifications about this status</a></li>
                    <li><a class="dropdown-item" href="#">Report issue to Notifications Team</a></li>
                </ul>
            </div>
        </div>`;


         case "Report":
    const respostimg = "../assests/images/icon/report.png";
    const postLink1 = `comment_postframe.php?postID=${noti.link}`; // Construct the link with postid

    return `
        <div class="notification d-flex align-items-center p-2 border-bottom">
            <img src="${respostimg}" alt="user" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
            <div class="content flex-grow-1">
                <a href="${postLink1}" class="text-decoration-none text-dark d-block"> <div class="text"><strong>${noti.type}</strong></div>
                    <div class="time text-muted small">${time}</div>
                </a>
            </div>
            <div class="menu-icon dropdown ms-auto">
                <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.stopPropagation();">
                    <i class="fa-solid fa-bars"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" onclick="event.stopPropagation();">
                    <li><a class="dropdown-item" href="#">Delete the notification</a></li>
                    <li><a class="dropdown-item" href="#">Turn off notifications about this status</a></li>
                    <li><a class="dropdown-item" href="#">Report issue to Notifications Team</a></li>
                </ul>
            </div>
        </div>`;
            }
        }

        function fetchNotifications() {
            fetch('../process/fetch_notifications.php')
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
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

                    // Reinitialize Bootstrap modals that were just added to the DOM
                    // This is important because dynamically added elements need to be recognized by Bootstrap's JS
                    var myModalEl = document.querySelectorAll('.modal');
                    myModalEl.forEach(function(modalEl) {
                        new bootstrap.Modal(modalEl, {
                            // options can go here if needed
                        });
                    });

                })
                .catch(error => {
                    console.error("Error fetching notifications:", error);
                    const container = document.getElementById("notificationList");
                    container.innerHTML = "<p>Error loading notifications. Please try again.</p>";
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