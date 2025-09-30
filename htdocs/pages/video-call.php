<?php
session_start();

// Default fallback if session is missing
$userID = $_GET['userID'] ?? $_SESSION['userid'] ?? 'guest';
$userName = $_GET['userName'] ?? $_SESSION['username'] ?? 'Guest';
$roomID = $_GET['roomID'] ?? 'default_room';

// Escape strings for safe output
$userID = htmlspecialchars($userID);
$userName = htmlspecialchars($userName);
$roomID = htmlspecialchars($roomID);
?>

<!DOCTYPE html>
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

    <script src="https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js"></script>
    <script>
        window.onload = function () {
            const appID = 1469423731;
            const serverSecret = "1e5de919dde11a182aff96a4d8fe01c9";

            // Values injected from PHP
            const userID = "<?php echo $userID ?>";
            const userName = "<?php echo $userName ?>";
            const roomID = "<?php echo $roomID ?>";

            const kitToken = ZegoUIKitPrebuilt.generateKitTokenForTest(
                appID,
                serverSecret,
                roomID,
                userID,
                userName
            );

            const zp = ZegoUIKitPrebuilt.create(kitToken);
            zp.joinRoom({
                container: document.querySelector("#root"),
                sharedLinks: [{
                    name: 'Personal link',
                    url: window.location.protocol + '//' + window.location.host + window.location.pathname + '?roomID=' + roomID,
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
        };
    </script>
</body>
</html>
