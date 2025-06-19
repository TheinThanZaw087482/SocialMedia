document.addEventListener('DOMContentLoaded', function () {
    // --- DOM Elements ---
    const globalSidebar = document.getElementById('globalSidebar');
    const contactListColumn = document.getElementById('contactListColumn');
    const chatArea = document.getElementById('chatArea');
    const chatOptionsPanel = document.getElementById('chatOptionsPanel');

    const menuIcon = document.getElementById('menuIcon'); // Hamburger icon for mobile contact list
    const infoIcon = document.getElementById('infoIcon'); // Info icon for mobile options panel
    const closeOptionsPanelBtn = document.getElementById('closeOptionsPanel'); // Close button for options panel

    const messageInput = document.getElementById('messageInput');
    const sendMessageButton = document.getElementById('sendMessageButton');
    const messageList = document.getElementById('messageList');

    const searchInput = document.getElementById('searchInput'); // Search for contacts
    let chatUsersListItems = document.querySelectorAll('#chatUsers li'); // Use let as it will be updated



    const attachBtn = document.querySelector(".attach-btn");
    const popupMenu = document.getElementById("popupMenu");
    const imageUploadInput = document.getElementById('imageUpload');
    const fileUploadInput = document.getElementById('fileUpload');


    let myUserId; // Declared here, will be initialized from PHP or window.myJsUserId

    let currentReceiverId = null; // ID of the currently selected chat partner

    const chatHeaderName = chatArea.querySelector('.chat-header .contact-name');
    const chatHeaderAvatar = chatArea.querySelector('.chat-header .contact-avatar img');
    const chatHeaderStatus = chatArea.querySelector('.chat-header .status');

    // Polling interval ID
    let fetchMessagesIntervalId = null;


    // --- Utility Functions ---


    function scrollToBottom(element) {
        element.scrollTop = element.scrollHeight;
    }



    function createMessageElement(messageText, senderId, senderName = 'Unknown', avatarSrc = '', messageType = 'text', fileUrl = null) {
        const messageWrapper = document.createElement('div');
        messageWrapper.classList.add('message-wrapper');

        const isSentByMe = (String(senderId) === String(myUserId));

        if (isSentByMe) {
            messageWrapper.classList.add('sent-wrapper');
            const messageBubble = document.createElement('div');
            messageBubble.classList.add('message-bubble', 'sent');
            messageBubble.innerHTML = formatMessageContent(messageText, messageType, fileUrl);
            messageWrapper.appendChild(messageBubble);
        } else {
            messageWrapper.classList.add('received-wrapper');
            const messageAvatarDiv = document.createElement('div');
            messageAvatarDiv.classList.add('message-avatar');
            const img = document.createElement('img');
            img.src = "../assests/images/post_images/" + avatarSrc; // <<< ADJUST PATH IF NECESSARY for received user avatars
            img.alt = `${senderName} Avatar`;
            img.onerror = function () {
                this.src = `https://placehold.co/35x35/555555/ffffff?text=${senderName.charAt(0).toUpperCase()}`;
            };
            messageAvatarDiv.appendChild(img);

            const messageBubble = document.createElement('div');
            messageBubble.classList.add('message-bubble', 'received');
            messageBubble.innerHTML = formatMessageContent(messageText, messageType, fileUrl);
            messageWrapper.appendChild(messageAvatarDiv);
            messageWrapper.appendChild(messageBubble);
        }
        return messageWrapper;
    }

    function formatMessageContent(text, type, url) {
        if (type === 'image' && url) {
            // Adjust the image path if necessary (e.g., if PHP returns /uploads/...)
            const fullImageUrl = url.startsWith('/') ? url : `../${url}`; // Assuming 'uploads' is parallel to 'process'
            return `<img src="${fullImageUrl}" alt="Image" class="chat-image" onclick="window.open('${fullImageUrl}', '_blank')">`;
        } else if (type === 'file' && url) {
            const fullFileUrl = url.startsWith('/') ? url : `../${url}`;
            return `<a href="${fullFileUrl}" target="_blank" class="chat-file-link">
                        <i class="fas fa-file"></i> ${text}
                    </a>`;
        }
        return text; // For text messages
    }


    function updateLastMessagePreview(contactUserId, messageText) {
        const contactItem = document.querySelector(`.contact-item[data-user-id="${contactUserId}"]`);
        if (contactItem) {
            const lastMessagePreview = contactItem.querySelector('.last-message-preview');
            if (lastMessagePreview) {
                // Shorten message for preview if it's too long
                let previewText = messageText.length > 30 ? messageText.substring(0, 27) + '...' : messageText;
                lastMessagePreview.textContent = previewText;

                const timestampElement = contactItem.querySelector('.timestamp');
                if (timestampElement) {
                    const now = new Date();
                    const hours = now.getHours();
                    const minutes = now.getMinutes();
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    const formattedHours = hours % 12 || 12;
                    const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
                    timestampElement.textContent = `${formattedHours}:${formattedMinutes} ${ampm}`;
                }
            }
        }
    }

    // --- Chat Functionality ---

    function isUserAtBottom(element, threshold = 50) {
        // For column-reverse, "bottom" is scrollTop === 0
        return element.scrollTop <= threshold;
    }

    function fetchMessages() {
        if (!currentReceiverId || !myUserId) {
            console.warn("No receiver selected or myUserId is undefined.");
            return;
        }

        const shouldAutoScroll = isUserAtBottom(messageList);

        // Optional: remember previous scroll height to restore if needed
        const previousScrollHeight = messageList.scrollHeight;

        fetch(`../process/get_messages.php?receiver_id=${currentReceiverId}&my_user_id=${myUserId}`)
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (data.status === 'error' && data.message === 'Authentication required.') {
                    window.location.href = '../login.php';
                    return;
                }

                messageList.innerHTML = ""; // Clear old messages
                let latestMessageContent = '';

                data.forEach(msg => {
                    const messageElement = createMessageElement(
                        msg.message,
                        msg.sender_id,
                        msg.sender_name || 'Unknown',
                        msg.sender_avatar || '',
                        msg.message_type || 'text',
                        msg.file_url || null
                    );
                    messageList.prepend(messageElement);
                    latestMessageContent = msg.message;
                });

                if (latestMessageContent) {
                    updateLastMessagePreview(currentReceiverId, latestMessageContent);
                }

                // 👇 Scroll only if the user was already at the bottom (top in column-reverse)
                if (shouldAutoScroll) {
                    messageList.scrollTop = 0;
                }
                // ❌ Otherwise, do nothing — let the user keep reading old messages
            })
            .catch(err => console.error("Error fetching messages:", err));
    }


    function sendMessage() {
        const text = messageInput.value.trim();
        if (text === "" || !currentReceiverId || !myUserId) {
            return;
        }

        // Optimistically add the message to the UI
        const userMessageElement = createMessageElement(text, myUserId, 'Me');
        messageList.prepend(userMessageElement);
        scrollToBottom(messageList);
        messageInput.value = "";

        fetch("../process/send_message.php", { // <<< ADJUST PATH IF NECESSARY
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ receiver_id: currentReceiverId, sender_id: myUserId, message: text }), // sender_id is technically redundant if server uses session
        })
            .then(res => {
                if (!res.ok) {
                    return res.text().then(errorText => {
                        throw new Error(`HTTP error! status: ${res.status}, Response: ${errorText}`);
                    });
                }
                return res.json();
            })
            .then(data => {
                console.log("Server response:", data);
                if (data.status === 'success') {
                    updateLastMessagePreview(currentReceiverId, text);
                    // No need to call fetchMessages() here, polling handles it.
                } else {
                    console.error("Server reported error sending message:", data.message);
                    // Optionally, remove the optimistically added message or mark it as failed
                }
            })
            .catch(err => {
                console.error("Error sending message:", err);
                // Handle network errors (e.g., show "message failed to send")
            });
    }

    function sendFile(file, receiverId, messageType) {
        if (!receiverId || !myUserId) {
            console.error("Cannot send file: Receiver not selected or myUserId is missing.");
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('receiver_id', receiverId);
        // formData.append('sender_id', myUserId); // Sender ID is taken from session on server

        // Optimistically add a placeholder message to the UI
        const placeholderText = messageType === 'image' ? `Sending image: ${file.name}` : `Sending file: ${file.name}`;
        const placeholderMessageElement = createMessageElement(placeholderText, myUserId, 'Me');
        messageList.prepend(placeholderMessageElement);
        scrollToBottom(messageList);


        fetch("../process/upload_file.php", { // <<< ADJUST PATH IF NECESSARY
            method: "POST",
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(errorText => {
                        throw new Error(`HTTP error! status: ${response.status}, Response: ${errorText}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Upload response:', data);
                if (data.status === 'success') {
                    // Replace placeholder or let polling update
                    // For now, remove placeholder and rely on next fetchMessages poll
                    messageList.removeChild(placeholderMessageElement);
                    updateLastMessagePreview(currentReceiverId, messageType === 'image' ? 'Image sent' : 'File sent');
                } else {
                    alert("File upload failed: " + data.message);
                    messageList.removeChild(placeholderMessageElement); // Remove placeholder on failure
                }
            })
            .catch(error => {
                console.error('Error uploading file:', error);
                alert("An error occurred during file upload.");
                messageList.removeChild(placeholderMessageElement); // Remove placeholder on network error
            });

        popupMenu.style.display = "none"; // Close popup after selection
    }

    // --- Chat Area Header Update ---
    function updateChatHeader(name, status, avatarSrc) {
        if (chatHeaderAvatar) {
            // Adjust path for chat header avatar (if different from message avatars)
            chatHeaderAvatar.src = "../assests/images/post_images/" + avatarSrc; // Or just avatarSrc if it's a full URL
            chatHeaderAvatar.onerror = function () {
                this.src = `https://placehold.co/35x35/555555/ffffff?text=${name.charAt(0).toUpperCase()}`;
            };
        }
        if (chatHeaderName) chatHeaderName.textContent = name;
        if (chatHeaderStatus) {
            chatHeaderStatus.textContent = status;
            chatHeaderStatus.classList.remove('online', 'offline');
            if (status.toLowerCase().includes('online') || status.toLowerCase().includes('active')) {
                chatHeaderStatus.classList.add('online');
            } else {
                chatHeaderStatus.classList.add('offline');
            }
        }
    }

    // --- Event Listeners ---

    if (sendMessageButton) {
        sendMessageButton.addEventListener('click', sendMessage);
    }

    if (messageInput) {
        messageInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();
            chatUsersListItems.forEach(item => {
                const name = item.querySelector('.name').textContent.toLowerCase();
                if (name.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // --- Add New Chat Modal Functionality (Client-Side Dummy) ---
    // For a real app, this would involve searching for users and creating conversations via PHP.

    // --- Attachment Popup Menu Functionality ---
    if (attachBtn && popupMenu && imageUploadInput && fileUploadInput) {
        attachBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            popupMenu.style.display = popupMenu.style.display === "flex" ? "none" : "flex";
        });

        window.addEventListener("click", function (e) {
            if (popupMenu.style.display === "flex" && !attachBtn.contains(e.target) && !popupMenu.contains(e.target)) {
                popupMenu.style.display = "none";
            }
        });

        imageUploadInput.addEventListener('change', (e) => sendFile(e.target.files[0], currentReceiverId, 'image'));
        fileUploadInput.addEventListener('change', (e) => sendFile(e.target.files[0], currentReceiverId, 'file'));
    }

    // --- Mobile Sidebar & Options Panel Toggles ---
    function closeContactListColumn() {
        contactListColumn.classList.remove('show');
    }

    function openContactListColumn() {
        contactListColumn.classList.add('show');
    }

    function closeChatOptionsPanel() {
        chatOptionsPanel.classList.remove('show');
    }

    function openChatOptionsPanel() {
        chatOptionsPanel.classList.add('show');
    }

    if (menuIcon && contactListColumn) {
        menuIcon.addEventListener('click', function (event) {
            event.stopPropagation();
            if (window.innerWidth <= 768) {
                closeChatOptionsPanel();
                contactListColumn.classList.toggle('show');
            }
        });
    }

    if (infoIcon && chatOptionsPanel) {
        infoIcon.addEventListener('click', function (event) {
            event.stopPropagation();
            if (window.innerWidth <= 768) {
                closeContactListColumn();
                chatOptionsPanel.classList.toggle('show');
            }
        });
    }

    if (closeOptionsPanelBtn && chatOptionsPanel) {
        closeOptionsPanelBtn.addEventListener('click', function () {
            closeChatOptionsPanel();
        });
    }

    window.addEventListener('click', function (e) {
        if (contactListColumn.classList.contains('show') &&
            !contactListColumn.contains(e.target) &&
            !menuIcon.contains(e.target)) {
            closeContactListColumn();
        }
        if (chatOptionsPanel.classList.contains('show') &&
            !chatOptionsPanel.contains(e.target) &&
            !infoIcon.contains(e.target)) {
            closeChatOptionsPanel();
        }
    });

    // Handle contact item click (delegated event listener)
    const chatUsersContainer = document.getElementById('chatUsers');
    if (chatUsersContainer) {
        chatUsersContainer.addEventListener('click', function (event) {
            const contactItem = event.target.closest('.contact-item');
            if (contactItem) {
                handleContactSelection(contactItem);
            }
        });
    }

    function handleContactSelection(selectedItem) {
        document.querySelectorAll('.contact-item.active').forEach(c => c.classList.remove('active'));
        selectedItem.classList.add('active');

        const contactId = selectedItem.getAttribute('data-user-id');
        const contactName = selectedItem.getAttribute('data-user-name');
        const contactStatus = selectedItem.getAttribute('data-user-status');
        const contactAvatarImg = selectedItem.querySelector('.contact-avatar img');
        const contactAvatarSrc = contactAvatarImg ? contactAvatarImg.src.replace(/^.*[\\/]/, '') : ''; // Extract just the filename/last part of path

        currentReceiverId = contactId;

        updateChatHeader(contactName, contactStatus, contactAvatarSrc);

        // Clear existing interval before setting a new one
        if (fetchMessagesIntervalId) {
            clearInterval(fetchMessagesIntervalId);
        }
        fetchMessages(); // Fetch messages immediately
        fetchMessagesIntervalId = setInterval(fetchMessages, 2000); // Start polling for new messages

        if (window.innerWidth <= 768) {
            closeContactListColumn();
        }
        closeChatOptionsPanel();
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            closeContactListColumn();
            closeChatOptionsPanel();
        }
    });

    // --- Initial Load ---
    // Ensure myUserId is set from PHP global variable
    if (typeof window.myUserId !== 'undefined') {
        myUserId = window.myUserId;
    } else {
        console.error("myUserId is not defined in the HTML. Please ensure it's set by PHP.");
        // Fallback or disable features if myUserId is critical and missing
    }

    // Select the first contact by default and load its messages
    const initialActiveContact = document.querySelector('.contact-item.active');
    if (initialActiveContact) {
        handleContactSelection(initialActiveContact);
    } else if (chatUsersListItems.length > 0) {
        handleContactSelection(chatUsersListItems[0]);
    } else {
        console.warn("No contacts found to select initially.");
    }

    // --- Start of NEW Agora Video Call Functions (Add this block to your existing messenger.js) ---

    // --- Video Call DOM Elements (Get references to the new elements) ---
    // Note: callButton now targets your existing video icon
    const callButton = document.getElementById('callButton');
    const hangupButton = document.getElementById('hangupButton');
    const localVideoContainer = document.getElementById('localVideoContainer');
    const remoteVideoContainer = document.getElementById('remoteVideoContainer');
    const videoCallModal = document.getElementById('videoCallModal');
    const closeVideoModalBtn = document.getElementById('closeVideoModal');
    const hangupButtonBottom = document.getElementById('hangupButtonBottom'); // Button inside the modal


    // --- Agora Video Call Variables ---
    let agoraClient = null;
    let localAgoraTracks = null; // Changed from localAgoraStream to localAgoraTracks for clarity
    const agoraAppId = '146c7d3c642d4dfb8c34513818129f6b'; // Your Agora App ID (same as in PHP)
    // Note: App Certificate is NOT exposed client-side!


    // --- Agora Video Call Core Functions ---

    async function initializeAgoraClient() {
        if (!agoraAppId) {
            console.error("Agora App ID is not defined.");
            alert("Video call cannot start: Agora App ID is missing.");
            return null;
        }
        // Create a new client if one doesn't exist or if it's been reset
        agoraClient = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });

        // Set up event listeners for the Agora client
        agoraClient.on("user-published", handleUserPublished);
        agoraClient.on("user-unpublished", handleUserUnpublished);
        agoraClient.on("user-joined", handleUserJoined);
        agoraClient.on("user-left", handleUserLeft);
        agoraClient.on("connection-state-change", (newState, reason) => {
            console.log(`Agora connection state changed to ${newState}, reason: ${reason}`);
        });

        console.log("Agora client initialized.");
        return agoraClient;
    }

    async function startVideoCall() {
        // Ensure currentReceiverId and myUserId are defined from your existing chat logic
        if (!currentReceiverId || !myUserId) {
            alert("Please select a contact to call.");
            return;
        }

        // Prevent starting a new call if already connected
        if (agoraClient && agoraClient.connectionState === 'CONNECTED') {
            alert("You are already in a call.");
            return;
        }

        // Initialize Agora client if not already done
        if (!agoraClient) {
            agoraClient = await initializeAgoraClient();
            if (!agoraClient) return; // Exit if client couldn't be initialized
        }

        // Generate a consistent channel name for the two participants
        // Ensures both users join the same channel regardless of who calls whom
        const channelName = [String(myUserId), String(currentReceiverId)].sort().join('-');
        const uid = parseInt(myUserId); // Agora UID (must be an integer)
        console.log(`Attempting to join Agora room: ${channelName} with UID: ${uid}`);

        // Show the video call modal/interface
        videoCallModal.style.display = 'flex';

        try {
            // 1. Get an Agora RTC Token from your PHP backend
            const response = await fetch('../process/generate_agora_token.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                // Send channel_name and uid as JSON, as your PHP expects $_POST from JSON
                body: JSON.stringify({ channel_name: channelName, uid: uid })
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP error! status: ${response.status}, Response: ${errorText}`);
            }

            const data = await response.json();

            if (data.success && data.token) {
                const token = data.token;
                console.log("Received Agora Token:", token);

                // 2. Join the Agora Channel
                await agoraClient.join(agoraAppId, channelName, token, uid);
                console.log(`Joined channel: ${channelName}`);

                // 3. Create and publish local tracks (video and audio)
                localAgoraTracks = await AgoraRTC.createMicrophoneAndCameraTracks();

                // Clear existing content and create a div for the local video
                localVideoContainer.innerHTML = '';
                const localPlayer = document.createElement('div');
                localPlayer.id = 'local-player-' + uid;
                localPlayer.classList.add('local-player'); // Add a class for potential styling
                localVideoContainer.appendChild(localPlayer);
                localAgoraTracks[1].play(localPlayer); // Play video track (index 1 is video, 0 is audio)

                await agoraClient.publish(localAgoraTracks);
                console.log("Published local tracks.");

                // Update UI
                callButton.style.display = 'none'; // Hide call button
                hangupButton.style.display = 'inline-block'; // Show header hangup button
                hangupButtonBottom.style.display = 'inline-block'; // Show modal hangup button

            } else {
                console.error("Failed to get Agora token:", data.message);
                alert("Could not start video call: " + data.message);
                videoCallModal.style.display = 'none'; // Hide modal on token failure
            }
        } catch (error) {
            console.error('Error starting Agora video call:', error);
            alert('Failed to start video call: ' + error.message);
            videoCallModal.style.display = 'none';
            hangUpVideoCall(); // Clean up on error
        }
    }

    async function hangUpVideoCall() {
        // Stop and close local tracks (camera and microphone)
        if (localAgoraTracks) {
            localAgoraTracks.forEach(track => track.stop());
            localAgoraTracks.forEach(track => track.close());
            localAgoraTracks = null;
        }

        // Leave the Agora channel if connected
        if (agoraClient && agoraClient.connectionState === 'CONNECTED') {
            await agoraClient.leave();
            console.log("Left Agora channel.");
        }
        agoraClient = null; // Reset client object

        // Clear video containers in the UI
        localVideoContainer.innerHTML = '<p style="color: white; text-align: center; line-height: 180px;">Your Video</p>';
        remoteVideoContainer.innerHTML = '<p style="color: white; text-align: center; line-height: 200px;">Remote Video</p>';

        // Update UI button visibility
        callButton.style.display = 'inline-block'; // Show call button
        hangupButton.style.display = 'none'; // Hide header hangup button
        hangupButtonBottom.style.display = 'none'; // Hide modal hangup button
        videoCallModal.style.display = 'none'; // Hide the modal
    }

    // --- Agora Event Handlers ---

    function handleUserPublished(user, mediaType) {
        console.log(`User ${user.uid} published ${mediaType} track.`);
        agoraClient.subscribe(user, mediaType)
            .then(() => {
                if (mediaType === "video") {
                    // Create a div for the remote user's video
                    const remotePlayer = document.createElement('div');
                    remotePlayer.id = `remote-player-${user.uid}`;
                    remotePlayer.classList.add('remote-player'); // Add a class for styling
                    remoteVideoContainer.innerHTML = ''; // Clear previous remote video if any
                    remoteVideoContainer.appendChild(remotePlayer); // Append to the remote container
                    user.videoTrack.play(remotePlayer); // Play the remote user's video
                }
                if (mediaType === "audio") {
                    user.audioTrack.play(); // Play the remote user's audio
                }
            })
            .catch(err => {
                console.error("Failed to subscribe to user:", err);
            });
    }

    function handleUserUnpublished(user) {
        console.log(`User ${user.uid} unpublished.`);
        const remotePlayer = document.getElementById(`remote-player-${user.uid}`);
        if (remotePlayer) {
            remotePlayer.remove(); // Remove the remote user's video element
            remoteVideoContainer.innerHTML = '<p style="color: white; text-align: center; line-height: 200px;">Remote Video</p>'; // Restore placeholder
        }
    }

    function handleUserJoined(user) {
        console.log(`User ${user.uid} joined the channel.`);
        // You could add a notification here to the user
    }

    function handleUserLeft(user) {
        console.log(`User ${user.uid} left the channel.`);
        const remotePlayer = document.getElementById(`remote-player-${user.uid}`);
        if (remotePlayer) {
            remotePlayer.remove(); // Remove the remote user's video element
            remoteVideoContainer.innerHTML = '<p style="color: white; text-align: center; line-height: 200px;">Remote Video</p>'; // Restore placeholder
        }
        // You could add a notification here
    }

    // --- Event Listeners for Video Call UI (add these at the end of your messenger.js) ---
    // Ensure these are outside any function that might redefine them
    document.addEventListener('DOMContentLoaded', () => {
        // Check if the elements exist before adding listeners
        if (callButton) {
            callButton.addEventListener('click', startVideoCall);
        }
        if (hangupButton) {
            hangupButton.addEventListener('click', hangUpVideoCall);
        }
        if (closeVideoModalBtn) {
            closeVideoModalBtn.addEventListener('click', hangUpVideoCall);
        }
        if (hangupButtonBottom) {
            hangupButtonBottom.addEventListener('click', hangUpVideoCall);
        }

        // Add listener for clicking outside the modal to close it (and hang up)
        if (videoCallModal) {
            videoCallModal.addEventListener('click', (e) => {
                if (e.target === videoCallModal) {
                    hangUpVideoCall();
                }
            });
        }
    });

    // --- End of NEW Agora Video Call Functions ---
});