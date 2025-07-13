<?php
include("../includes/db.php");
include("../process/post.php");


$userid = $_SESSION['userid'] ?? null; // Use null coalescing for safety
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metro Book - Admin Reports</title>

    <link rel="stylesheet" href="../assests/css/noti.css">
    <link rel="stylesheet" href="../assests/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="margin-top: 80px;">
    <div>
        <?php include("../includes/header.php"); ?>
    </div>

    <div class="container">
        <h2 class="mb-4">Report Notifications</h2>

        <div>
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <select class="form-select w-auto" id="reportTypeFilter">
                    <option value="">All</option>
                    <option value="Sexual Harassment">Sexual Harassment</option>
                    <option value="Violent content">Violent content</option>
                    <option value="Spam">Spam</option>
                    <option value="Hate speech">Hate speech</option>
                </select>

                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="manageSelectedBtn"
                        data-bs-toggle="dropdown" disabled>
                        Manage Selected
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="handleAction('delete')">Delete the post</a></li>
                        <li><a class="dropdown-item" href="#" onclick="handleAction('allow')">Allow</a></li>
                        <li><a class="dropdown-item" href="#" onclick="handleAction('ban')">Ban</a></li>
                    </ul>
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="selectAllCheckbox" onclick="toggleSelectAll()">
                <label class="form-check-label" for="selectAllCheckbox">Select All</label>
            </div>

            <div id="reportList"></div>
        </div>
    </div>

    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalReportContent">
                    </div>
            </div>
        </div>
    </div>

    <?php
    $sql = "SELECT 
        rp.reportID,
        rp.reportuserID,
        rp.postID,
        rp.Message,
        rp.status,
        rpt.report_type,
        u.name AS postedUser,
        rpu.name AS reportUser
    FROM 
        report rp
    JOIN 
        report_type rpt ON rp.reportID = rpt.reportID
    JOIN 
        post p ON p.postID = rp.postID
    JOIN 
        users u ON p.userID = u.userid
    JOIN 
        users rpu ON rp.reportuserID = rpu.userid";

    $result = mysqli_query($conn, $sql);

    $reports = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Apply htmlspecialchars for XSS protection when outputting user-generated content
            // The JavaScript then uses textContent for modal, which is safer.
            // For info.innerHTML, this escaping is important.
            $row['Message'] = htmlspecialchars($row['Message'], ENT_QUOTES, 'UTF-8');
            $reports[] = $row;
        }
    } else {
        // Log the error for debugging
        error_log("Database query failed: " . mysqli_error($conn));
        // You might want to display a user-friendly error message
        // echo "<p>Error loading reports. Please try again later.</p>";
    }

    $reports_json = json_encode($reports);
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const reportsFromPHP = <?= $reports_json ?>;

        console.log("Reports loaded from PHP:", reportsFromPHP);
        const allReports = reportsFromPHP.map(r => ({
            id: r.reportID,
            type: r.report_type,
            content: r.Message, // Message is already HTML-escaped from PHP
            postId: r.postID,
            status: r.status,
            postedUser: r.postedUser,
            reportUser: r.reportUser
        }));

        console.log(allReports);

        // Remove this line as 'content' is not defined here globally.
        // content = reportUser + "reported" + postedUser + content; 

        let selectedReports = [];

        function renderReports() {
            const selectedType = document.getElementById("reportTypeFilter").value;
            // Filter from 'allReports' not 'mockReports' (renamed for clarity)
            const filtered = selectedType ? allReports.filter(r => r.type === selectedType) : allReports;

            const container = document.getElementById("reportList");
            container.innerHTML = ""; // Clear existing content

            if (filtered.length === 0) {
                container.innerHTML = '<p class="text-muted">No reports found for this filter.</p>';
                document.getElementById("selectAllCheckbox").checked = false;
                document.getElementById("selectAllCheckbox").disabled = true; // Disable if no items
                document.getElementById("manageSelectedBtn").disabled = true;
                return;
            } else {
                 document.getElementById("selectAllCheckbox").disabled = false;
            }


            filtered.forEach(report => {
                const isChecked = selectedReports.includes(report.id);

                const card = document.createElement("div");
                card.className = "card mb-3";

                const cardBody = document.createElement("div");
                cardBody.className = "card-body d-flex justify-content-between align-items-center";

                const left = document.createElement("div");
                left.className = "d-flex align-items-center gap-3";

                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.className = "form-check-input";
                checkbox.checked = isChecked;
                checkbox.addEventListener("change", () => toggleSelect(report.id));

                const info = document.createElement("div");

                // Constructing the full message for display in the list
                const fullDisplayContent = `${report.reportUser} reported ${report.postedUser}: "${report.content}"`;
                const shortContent = fullDisplayContent.length > 50 ? // Adjusted length for combined message
                    fullDisplayContent.substring(0, 50) + "..." :
                    fullDisplayContent;

                info.innerHTML = `
                    <strong>${report.type}</strong><br>
                    <small>${shortContent}</small>
                    ${fullDisplayContent.length > 50 ? `<a href="#" class="ms-2" onclick="showReportModal(
                        '${report.reportUser.replace(/'/g, "\\'")}',
                        '${report.postedUser.replace(/'/g, "\\'")}',
                        '${report.type.replace(/'/g, "\\'")}',
                        '${report.content.replace(/'/g, "\\'")}'
                    )">See more...</a>` : ""}
                `;
                // Note: The 'replace' is for escaping single quotes in string literals for onclick.
                // Since report.content is already HTML-escaped, using textContent in modal is safe.


                left.appendChild(checkbox);
                left.appendChild(info);

                const right = document.createElement("div");
                right.className = "d-flex align-items-center gap-2";

                const viewBtn = document.createElement("a"); // Changed to <a> for a proper link
                viewBtn.className = "btn btn-outline-primary btn-sm";
                viewBtn.textContent = "View Post";
                
                viewBtn.href = `../pages/comment_postframe.php?postID=${report.postId}`; 
                right.appendChild(viewBtn);

                cardBody.appendChild(left);
                cardBody.appendChild(right);
                card.appendChild(cardBody);
                container.appendChild(card);
            });

            // Update selectAllCheckbox state correctly
            const allSelected = filtered.length > 0 && selectedReports.length === filtered.map(r => r.id).filter(id => selectedReports.includes(id)).length;
            document.getElementById("selectAllCheckbox").checked = allSelected;

            document.getElementById("manageSelectedBtn").disabled = selectedReports.length === 0;
        }

        function toggleSelect(id) {
            selectedReports = selectedReports.includes(id) ?
                selectedReports.filter(r => r !== id) : [...selectedReports, id];
            renderReports();
        }

        function toggleSelectAll() {
            const selectedType = document.getElementById("reportTypeFilter").value;
            const filtered = selectedType ? allReports.filter(r => r.type === selectedType) : allReports;

            if (selectedReports.length === filtered.length) {
                // If all are currently selected, deselect all
                selectedReports = [];
            } else {
                // Otherwise, select all filtered reports
                selectedReports = filtered.map(r => r.id);
            }
            renderReports();
        }

        // Renamed 'content' parameter to 'messageContent' to avoid conflict and be more descriptive
        function showReportModal(reportUser, postedUser, reportType, messageContent) {
            const modalBody = document.getElementById("modalReportContent");
            // Format the content for the modal
            modalBody.innerHTML = `
                <p><strong>Reported by:</strong> ${reportUser}</p>
                <p><strong>Posted by:</strong> ${postedUser}</p>
                <p><strong>Reason:</strong> ${reportType}</p>
                <p><strong>Message:</strong> ${messageContent}</p>
            `; // Using innerHTML here is safe because messageContent is already HTML-escaped from PHP.

            const modal = new bootstrap.Modal(document.getElementById('reportModal'));
            modal.show();
        }

        // This function will need to be implemented fully with AJAX
        async function handleAction(action) {
            if (selectedReports.length === 0) {
                alert("Please select at least one report.");
                return;
            }

            const confirmMessage = `Are you sure you want to ${action} the selected report(s)?`;
            if (!confirm(confirmMessage)) {
                return; // User cancelled
            }

            try {
                const response = await fetch('../process/process_report_action.php', { // Create this file
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        reportIds: selectedReports,
                        action: action
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    alert("Error: " + result.message);
                    console.error("Server error:", result.message);
                }
            } catch (error) {
                console.error("Error sending action to server:", error);
                alert("An error occurred while processing your request. Check console for details.");
            }
        }


        document.getElementById("reportTypeFilter").addEventListener("change", renderReports);
        window.addEventListener("DOMContentLoaded", renderReports);
    </script>
</body>

</html>