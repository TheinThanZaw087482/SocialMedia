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
    <title>Video Call</title>
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assests/css/messanger.css">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #000;
            overflow: hidden;
        }

        .video-call-container {
            position: relative;
            width: 100vw;
            height: 100vh;
        }

        .main-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .caller-video {
            position: absolute;
            top: 80px;
            right: 30px;
            width: 200px;
            height: 150px;
            border: 2px solid white;
            border-radius: 10px;
            overflow: hidden;
            background-color: black;
        }

        .caller-video video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .control-buttons {
            position: absolute;
            bottom: 90px;
            right: 660px;
            display: flex;
            gap: 25px;
        }

        .control-buttons button {
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
            font-size: 20px;
        }

        .control-buttons callBtn {
            color: white;
        }
    </style>
</head>

<body>

    <div class="video-call-container">
        <!-- user being called -->
        <video class="main-video" id="remoteVideo" autoplay muted></video>

        <!-- Caller video -->
        <div class="caller-video">
            <video id="localVideo" autoplay muted></video>
        </div>

        <!-- Control buttons -->
        <div class="control-buttons">
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

    <script>
        let micEnabled = true;
        let videoEnabled = true;

        const callBtn = document.getElementById("callBtn");
        const micBtn = document.getElementById("toggleMicBtn");
        const videoBtn = document.getElementById("toggleVideoBtn");

        callBtn.addEventListener("click", () => {
            console.log("End call");
            callBtn.innerHTML = '<i class="fas fa-phone"></i>';
            callBtn.classList.remove("btn-secondary");
            callBtn.classList.add("btn-danger");
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


</body>

</html>