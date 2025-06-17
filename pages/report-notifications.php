<?php
session_start();
include("../includes/db.php");
include("../process/post.php");
$userid = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metro Book</title>

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

        <!-- All Notifications Section -->
        <div class="mb-4">
            <p class="text-muted text-body-tertiary fs-5 mb-2">All Notifications</p>
            <div id="notificationList"></div>
        </div>

        <!-- Today Section -->
        <div>
            <p class="text-muted text-body-tertiary fs-5 mb-2">Today</p>

            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <select class="form-select w-auto" id="reportTypeFilter">
                    <option value="">All</option>
                    <option value="Sexual Harassment">Sexual Harassment</option>
                    <option value="Violence">Violence</option>
                    <option value="Spam">Spam</option>
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

    <!-- Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportModalLabel">Report Content</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="modalReportContent">
            <!-- Full report content will appear here -->
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript Logic -->
    <script>
        const mockReports = [
            { id: 1, type: "Violence", content: "I don't like Ko Myo Aung because Ko Myo Aung makes me write code...", postId: 101 },
            { id: 2, type: "Sexual Harassment", content: "Post by user456 about ABC with very aggressive and threatening language.", postId: 102 },
            { id: 3, type: "Spam", content: "Post by user789 about JKL offering fake giveaways and repetitive links.", postId: 103 },
        ];

        let selectedReports = [];

        function renderReports() {
            const selectedType = document.getElementById("reportTypeFilter").value;
            const filtered = selectedType ? mockReports.filter(r => r.type === selectedType) : mockReports;

            const container = document.getElementById("reportList");
            container.innerHTML = "";

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

                const shortContent = report.content.length > 30
                    ? report.content.substring(0, 30) + "..."
                    : report.content;

                info.innerHTML = `
                    <strong>${report.type}</strong><br>
                    <small>${shortContent}</small>
                    ${report.content.length > 30 ? `<a href="#" class="ms-2" onclick="showReportModal('${report.content.replace(/'/g, "\\'")}')">See more...</a>` : ""}
                `;

                left.appendChild(checkbox);
                left.appendChild(info);

                const right = document.createElement("div");
                right.className = "d-flex align-items-center gap-2";

                const viewBtn = document.createElement("button");
                viewBtn.className = "btn btn-outline-primary btn-sm";
                viewBtn.textContent = "View Post";
                viewBtn.addEventListener("click", () => alert(`Viewing post #${report.postId}`));

                right.appendChild(viewBtn);

                cardBody.appendChild(left);
                cardBody.appendChild(right);
                card.appendChild(cardBody);
                container.appendChild(card);
            });

            const allSelected = filtered.length > 0 && selectedReports.length === filtered.length;
            document.getElementById("selectAllCheckbox").checked = allSelected;
            document.getElementById("manageSelectedBtn").disabled = selectedReports.length === 0;
        }

        function toggleSelect(id) {
            selectedReports = selectedReports.includes(id)
                ? selectedReports.filter(r => r !== id)
                : [...selectedReports, id];
            renderReports();
        }

        function toggleSelectAll() {
            const selectedType = document.getElementById("reportTypeFilter").value;
            const filtered = selectedType ? mockReports.filter(r => r.type === selectedType) : mockReports;

            selectedReports = selectedReports.length === filtered.length ? [] : filtered.map(r => r.id);
            renderReports();
        }

        function handleAction(action) {
            alert(`${action.toUpperCase()} reports: ${selectedReports.join(", ")}`);
        }

        function showReportModal(content) {
            const modalBody = document.getElementById("modalReportContent");
            modalBody.textContent = content;
            const modal = new bootstrap.Modal(document.getElementById('reportModal'));
            modal.show();
        }

        document.getElementById("reportTypeFilter").addEventListener("change", renderReports);
        window.addEventListener("DOMContentLoaded", renderReports);
    </script>
</body>

</html>