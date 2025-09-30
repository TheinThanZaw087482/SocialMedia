<?php
session_start();
include("../includes/db.php");
include("../includes/get_users.php");

// Get the logged-in user's information from the session
$myUserId = $_SESSION['userid'] ?? 'my_user_id'; // Fallback for testing if session not set
$myUserName = $_SESSION['username'] ?? 'Me'; // Assuming you have username in session
$myProfileImage = $_SESSION['profileImagePath'] ?? 'https://placehold.co/45x45'; // Assuming profile image path
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metro Chat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assests/css/messanger.css">
    <style>
        /* ... (your existing styles) ... */
        .video-call-ui p {
            font-size: 18px;
            margin-top: -10px;
            margin-bottom: 30px;
        }

        .modal {
            transition: all 0.3s ease-in-out;
        }

        .video-call-ui button {
            width: 60px;
            height: 60px;
            font-size: 20px;
        }
    </style>
</head>

<body>

    <div class="chat-container">
        <div class="global-sidebar" id="globalSidebar">
            <ul class="global-nav">
                <li class="nav-item"><a class="nav-link" href="../pages/Dashboard.php"><i
                            class="fa-solid fa-house"></i></a></li>

                <li><a class="nav-link" href="../pages/friend-add.php" title="Friends">
                        <i class="fa-solid fa-user-group" style="font-size: 1.5rem; color: #fff;"></i>
                    </a></li>
                <li><a class="nav-link" href="../pages/profile.php" title="User">
                        <i class="fa-solid fa-circle-user" style="font-size: 1.5rem; color: #fff;"></i>
                    </a></li>
                <li> <a class="nav-link" href="../pages/setting.php" title="Settings">
                        <i class="fa-solid fa-gear" style="font-size: 1.5rem; color: #fff;"></i>
                    </a></li>
            </ul>
        </div>

        <div class="contact-list-column" id="contactListColumn">
            <div class="top-bar">
                <h2>Chats</h2>
                <button id="addButton"><i class="fas fa-plus-circle"></i></button>
            </div>
            <div class="search-contacts">
                <input type="text" id="searchInput" placeholder="Search">
            </div>

            <ul class="chat-users" id="chatUsers">
                <?php
                $users = get_all_users();
                foreach ($users as $user) {
                    if ($user['userid'] != $_SESSION['userid']) {
                ?>
                        <li class="contact-item active" data-user-id="<?php echo $user['userid'] ?>"
                            data-user-name="<?php echo htmlspecialchars($user['name']) ?>"
                            data-user-image="<?php echo htmlspecialchars($user['ProfileimagePath']) ?>"
                            data-user-status="Active now">
                            <div class="contact-avatar">
                                <img src="../assests/images/post_images/<?php echo $user['ProfileimagePath'] ?>"
                                    alt="User Avatar">
                                <span class="status-dot online"></span>
                            </div>
                            <div class="contact-info">
                                <div class="name"><?php echo htmlspecialchars($user['name']); ?></div>
                                
                            </div>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>
        <div class="chat-area" id="chatArea">
            <div class="chat-header">
                <i class="fas fa-bars menu-icon" id="menuIcon"></i>
                <div class="contact-avatar">
                    <img src="https://placehold.co/45x45/555555/ffffff?text=J" alt="Contact Avatar">
                </div>
                <div class="contact-info">
                    <div class="contact-name">Judi</div>
                    <div class="status online">Active now</div>
                </div>
                <div class="header-icons">
                    <i class="fas fa-phone-alt"></i>
                    <i class="fas fa-video" id="callButton" style="cursor: pointer;"></i>
                    <button id="hangupButton" class="btn btn-danger ms-3" style="display: none;"><i
                            class="fas fa-phone-slash"></i> Hang Up</button>
                    <i class="fas fa-info-circle" id="infoIcon"></i>
                </div>
            </div>
            <div class="message-list" id="messageList">
                <div class="message-wrapper">
                    <div class="message-avatar">
                        <img src="https://placehold.co/35x35/555555/ffffff?text=J" alt="Judi Avatar">
                    </div>
                    <div class="message-bubble received">
                        How are you today ? Are you feeling good ?
                    </div>
                </div>
                <div class="message-wrapper sent-wrapper">
                    <div class="message-bubble sent">
                        Sorry Judi ! I was so busy
                    </div>
                </div>
            </div>
            <div class="message-input-area">
                <div class="input-icons-left">
                    <i class="fas fa-plus attach-btn"></i>
                    <i class="fas fa-image"></i>
                    <i class="fas fa-grin"></i>
                    <i class="fas fa-gift"></i>
                </div>
                <input type="text" class="form-control message-input" id="messageInput" placeholder="Type a message">
                <div class="input-icons-right">
                    <i class="fas fa-microphone"></i>
                    <button class="btn-send" id="sendMessageButton"><i class="fas fa-paper-plane"></i></button>
                </div>

                <div id="popupMenu">
                    <label for="imageUpload">
                        <i class="fas fa-image"></i> Attach Image
                        <input type="file" id="imageUpload" accept="image/*" style="display: none;">
                    </label>
                    <label for="fileUpload">
                        <i class="fas fa-file-alt"></i> Attach File
                        <input type="file" id="fileUpload" style="display: none;">
                    </label>
                </div>
            </div>
        </div>

        <div class="chat-options-panel" id="chatOptionsPanel">
            <div class="options-header">
                <h3>Details</h3>
                <i class="fas fa-times close-options-panel" id="closeOptionsPanel"></i>
            </div>
            <div class="options-content">
                <div class="option-section">
                    <h4>Search in Conversation</h4>
                    <input type="text" placeholder="Search">
                </div>
                <div class="option-section">
                    <h4>Manage Messages</h4>
                    <ul>
                        <li>Delete Chat</li>
                        <li>Archive Chat</li>
                    </ul>
                </div>
                <div class="option-section">
                    <h4>Notifications</h4>
                    <ul>
                        <li>Turn on/off</li>
                    </ul>
                </div>
                <div class="option-section">
                    <h4>Shared Media</h4>
                    <div
                        style="height: 100px; background-color: #3a3a3a; display: flex; align-items: center; justify-content: center; color: #aaaaaa;">
                        (Shared Media Gallery)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="videoCallModal" class="modal" style="display: none;">
        <div class="video-call-ui d-flex flex-column align-items-center justify-content-center"
            style="background-color: rgba(0,0,0,0.85); width: 100vw; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;">
            <img id="callUserImage" src="https://placehold.co/100x100" class="rounded-circle mb-3"
                style="width: 170px; height: 170px; object-fit: cover; border: 3px solid white;" alt="User Image">
            <h4 id="callUserName" class="text-white mb-1"></h4>
            <p class="text-white mb-4" style="font-weight: 300;">Calling...</p>
            <div class="d-flex gap-4">
                <button id="toggleVideoBtnModal" class="btn btn-light rounded-circle">
                    <i class="fas fa-video"></i>
                </button>
                <button id="toggleMicBtnModal" class="btn btn-light rounded-circle">
                    <i class="fas fa-microphone"></i>
                </button>
                <button id="cancelCallBtnModal" class="btn btn-danger rounded-circle">
                    <i class="fas fa-phone"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // PHP variables made available in JavaScript
        window.myUserId = "<?php echo htmlspecialchars($myUserId); ?>";
        window.myUserName = "<?php echo htmlspecialchars($myUserName); ?>";
        window.myProfileImage = "<?php echo htmlspecialchars($myProfileImage); ?>";
    </script>

    <script>
        const videoIcon = document.getElementById("callButton");
        const videoCallModal = document.getElementById("videoCallModal");
        const cancelCallBtnModal = document.getElementById("cancelCallBtnModal");
        const micBtnModal = document.getElementById("toggleMicBtnModal");
        const videoBtnModal = document.getElementById("toggleVideoBtnModal");

        let micEnabledModal = true;
        let videoEnabledModal = true;

        function setCallUserInfo(name, imageUrl) {
            document.getElementById("callUserName").textContent = name;
            document.getElementById("callUserImage").src = imageUrl;
        }

        videoIcon.addEventListener("click", () => {
            const activeContactItem = document.querySelector('.contact-item.active');
            let targetUserId = null;
            let targetUserName = "Unknown";
            let targetUserImage = "https://placehold.co/100x100"; // Default image

            if (activeContactItem) {
                targetUserId = activeContactItem.dataset.userId;
                targetUserName = activeContactItem.dataset.userName;
                targetUserImage = `../assests/images/post_images/${activeContactItem.dataset.userImage}`; // Get full path
            } else {
                alert("Please select a user to call.");
                return;
            }

            if (!targetUserId) {
                alert("Could not determine recipient for the call.");
                return;
            }

            // Set info in the "calling..." modal
            setCallUserInfo(targetUserName, targetUserImage);
            videoCallModal.style.display = "flex"; // Show the "calling..." modal

            // Simulate a short delay before redirecting to the actual video call page
            // In a real application, you'd send a signaling message here and wait for acceptance
            // before redirecting.
            setTimeout(() => {
                // Redirect to video-call.php with necessary parameters for ZEGOCLOUD Call Kit
                // roomID: A unique identifier for the call (e.g., sorted combination of user IDs)
                // userID: Your user ID
                // userName: Your user name
                // targetUserID: The person you are calling
                // targetUserName: The person you are calling's name
                // targetUserImage: The person you are calling's image
                const roomID = [window.myUserId, targetUserId].sort().join('_');
                const redirectUrl = `video-call.php?roomID=${roomID}&userID=${window.myUserId}&userName=${encodeURIComponent(window.myUserName)}&targetUserID=${targetUserId}&targetUserName=${encodeURIComponent(targetUserName)}&targetUserImage=${encodeURIComponent(targetUserImage)}&myProfileImage=${encodeURIComponent(window.myProfileImage)}`;
                window.location.href = redirectUrl;
            }, 2000); // Show "calling..." for 2 seconds before redirecting
        });

        // Event listener for the "End Call" button inside the modal (before redirection)
        cancelCallBtnModal.addEventListener("click", () => {
            videoCallModal.style.display = "none";
            // If you had a signaling mechanism, you'd cancel the call request here.
        });

        micBtnModal.addEventListener("click", () => {
            micEnabledModal = !micEnabledModal;
            micBtnModal.innerHTML = `<i class="fas fa-microphone${micEnabledModal ? '' : '-slash'}"></i>`;
            // This only updates the icon in the modal. Actual mic state will be handled in video-call.php
        });

        videoBtnModal.addEventListener("click", () => {
            videoEnabledModal = !videoEnabledModal;
            videoBtnModal.innerHTML = `<i class="fas fa-video${videoEnabledModal ? '' : '-slash'}"></i>`;
            // This only updates the icon in the modal. Actual video state will be handled in video-call.php
        });
    </script>
    <script src="../assests/js/messanger.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>