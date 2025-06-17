<?php
session_start();
include("../includes/db.php");
include("../includes/get_users.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metro Chat</title>
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assests/css/messanger.css">
    <style>
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
            <div class="search-global">
                <input type="text" placeholder="Search Messenger">
            </div>
            <ul class="global-nav">
                <li><a href="#"><i class="fas fa-comment"></i></a></li>
                <li><a href="#"><i class="fas fa-users"></i></a></li>
                <li><a href="#"><i class="fas fa-compass"></i></a></li>
                <li><a href="#"><i class="fas fa-bell"><span class="badge">5</span></i></a></li>
                <li><a href="#"><i class="fas fa-user-circle"></i></a></li>
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
                            data-user-name="<?php echo $user['name'] ?>" data-user-status="Active now">
                            <div class="contact-avatar">
                                <img src="../assests/images/post_images/<?php echo $user['ProfileimagePath'] ?>"
                                    alt="Judi Avatar">
                                <span class="status-dot online"></span>
                            </div>
                            <div class="contact-info">
                                <div class="name"><?php echo htmlspecialchars($user['name']); ?></div>
                                <div class="last-message-preview">How are you today?</div>
                            </div>
                            <div class="timestamp">21 Apr</div>
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
                    <i class="fas fa-video" id="callButton" style="cursor: pointer;"></i> <button id="hangupButton"
                        class="btn btn-danger ms-3" style="display: none;"><i class="fas fa-phone-slash"></i> Hang
                        Up</button> <i class="fas fa-info-circle" id="infoIcon"></i>
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

    <!-- video call modal -->
    <div id="videoCallModal" class="modal" style="display: none;">
        <div class="video-call-ui d-flex flex-column align-items-center justify-content-center"
            style="background-color: rgba(0,0,0,0.85); width: 100vw; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;">
            <img id="callUserImage" src="https://placehold.co/100x100" class="rounded-circle mb-3"
                style="width: 170px; height: 170px; object-fit: cover; border: 3px solid white;" alt="User Image">
            <h4 id="callUserName" class="text-white mb-1">Judi</h4>
            <p class="text-white mb-4" style="font-weight: 300;">Calling...</p>
            <div class="d-flex gap-4">
                <button id="toggleVideoBtn" class="btn btn-light rounded-circle">
                    <i class="fas fa-video"></i>
                </button>
                <button id="toggleMicBtn" class="btn btn-light rounded-circle">
                    <i class="fas fa-microphone"></i>
                </button>
                <button id="callBtn" class="btn btn-danger rounded-circle">
                    <i class="fas fa-phone"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        window.myUserId = "<?php echo htmlspecialchars($_SESSION['userid']); ?>";
    </script>

    <script>
        window.myUserId = "<?php echo htmlspecialchars($_SESSION['userid']); ?>";

        const videoIcon = document.getElementById("callButton");
        const videoCallModal = document.getElementById("videoCallModal");
        const callBtn = document.getElementById("callBtn");
        const micBtn = document.getElementById("toggleMicBtn");
        const videoBtn = document.getElementById("toggleVideoBtn");

        let callActive = false;
        let micEnabled = true;
        let videoEnabled = true;

        function setCallUserInfo(name, imageUrl) {
            document.getElementById("callUserName").textContent = name;
            document.getElementById("callUserImage").src = imageUrl;
        }

        videoIcon.addEventListener("click", () => {
            setCallUserInfo("Judi", "https://placehold.co/100x100/555555/fff?text=J");
            videoCallModal.style.display = "flex";
            callActive = false;
            callBtn.classList.remove("btn-secondary");
            callBtn.classList.add("btn-danger");
            callBtn.innerHTML = '<i class="fas fa-phone"></i>';
        });

        callBtn.addEventListener("click", () => {
            // Simulate call initiated, then hide modal
            videoCallModal.style.display = "none";
            callActive = false;
            callBtn.innerHTML = '<i class="fas fa-phone"></i>';
            callBtn.classList.remove("btn-secondary");
            callBtn.classList.add("btn-danger");

            // logic here 
        });

        micBtn.addEventListener("click", () => {
            micEnabled = !micEnabled;
            micBtn.innerHTML = `<i class="fas fa-microphone${micEnabled ? '' : '-slash'}"></i>`;
        });

        videoBtn.addEventListener("click", () => {
            videoEnabled = !videoEnabled;
            videoBtn.innerHTML = `<i class="fas fa-video${videoEnabled ? '' : '-slash'}"></i>`;
        });
    </script>
    <script src="../assests/js/messanger.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>