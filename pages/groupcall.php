<?php
session_start();
?>

<html>

<head>
    <style>
        #root {
            width: 100vw;
            height: 100vh;
        }
    </style>
</head>


<body>
    <div id="root"></div>
</body>
<script src="https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js"></script>
<script>
window.onload = function () {
    // Define a fixed room ID for all users to join
    const roomID = "YOUR_FIXED_GROUP_CALL_ROOM_ID"; // <--- REMEMBER TO CHANGE THIS to your desired fixed room ID

    // Inject PHP session variables into JavaScript
    // Ensure string values are enclosed in quotes for JavaScript
    const userID = "<?php echo $_SESSION['userid']; ?>"; // Wrap in quotes
    const userName = "<?php echo $_SESSION['username']; ?>"; // Wrap in quotes

    // You might want to append the userID to userName for uniqueness or just use userName
    // If you want a unique username like "YourName12345", you can do:
    // const userName = "<?php echo $_SESSION['username']; ?>" + userID;

    const appID = 1469423731;
    const serverSecret = "1e5de919dde11a182aff96a4d8fe01c9";
    const kitToken = ZegoUIKitPrebuilt.generateKitTokenForTest(appID, serverSecret, roomID, userID, userName);

    
    const zp = ZegoUIKitPrebuilt.create(kitToken);
    zp.joinRoom({
        container: document.querySelector("#root"),
        sharedLinks: [{
            name: 'Personal link',
            url: window.location.protocol + '//' + window.location.host  + window.location.pathname + '?roomID=' + roomID,
        }],
        scenario: {
            mode: ZegoUIKitPrebuilt.VideoConference,
        },
            
        turnOnMicrophoneWhenJoining: true,
        turnOnCameraWhenJoining: true,
        showMyCameraToggleButton: true,
        showMyMicrophoneToggleButton: true,
        showAudioVideoSettingsButton: true,
        showScreenSharingButton: true,
        showTextChat: true,
        showUserList: true,
        maxUsers: 50,
        layout: "Grid",
        showLayoutButton: true,
    
        });
}
</script>

</html>