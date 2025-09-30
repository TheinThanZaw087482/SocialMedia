<?php
session_start();
require_once("../includes/db.php");
require_once("../process/post.php");
$userid = $_SESSION['userid'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="../assests/css/noti.css">
  <link rel="stylesheet" href="../assests/css/style.css">
  <!-- font-awesome link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <title>Metro Book</title>
</head>

<body style=" margin-top: 80px;">
  <div>
    <!-- nav start -->
    <?php
    include("../includes/header.php")
    ?>

  </div>

  <div class="container">
    <h2>Notifications</h2>

    <div class="section">
      <p class="section-title">All Notifications</p>
      <div id="notificationList"></div>
    </div>


    <div class="section">
      <p class="section-title">Today</p>

      <div class="notification">
        <img src="./img/bbh.avif" alt="user">
        <div class="content">
          <div class="text"><strong>Su Latt</strong> and <strong>Myo Aung</strong> reacted to a video.</div>
          <div class="time">13h</div>
        </div>
        <div class="menu-icon">...</div>
      </div>

      <div class="notification">
        <img src="./img/bbh.avif" alt="user">
        <div class="content">
          <div class="text"><strong>Han Htet Soe</strong> posted photo in "MetroMedia"</div>
          <div class="time">19h</div>
        </div>
        <div class="menu-icon">...</div>
      </div>

      <div class="notification">
        <img src="./img/bbh.avif" alt="user">
        <div class="content">
          <div class="text"><strong>Myo Aung</strong>, <strong>Thu Raine Kyaw</strong> and 3 others reacted: "broooo"</div>
          <div class="time">13h</div>
        </div>
        <div class="menu-icon">...</div>
      </div>
    </div>

    <div class="section">
      <p class="section-title">Earlier</p>

      <div class="notification">
        <img src="./img/bbh.avif" alt="user">
        <div class="content">
          <div class="text"><strong>Twinki Soo</strong>, <strong>Kashi</strong> and 17 others reacted: "wow"</div>
          <div class="time">1d</div>
        </div>
        <div class="menu-icon">...</div>
      </div>

      <div class="notification">
        <img src="./img/bbh.avif" alt="user">
        <div class="content">
          <div class="text"><strong>Aung Thu Lwin</strong> invited you to like <strong>Next Stop: Z</strong></div>
          <div class="buttons">
            <button class="accept">Accept</button>
            <button class="decline">Decline</button>
          </div>
          <div class="time">1d</div>
        </div>
        <div class="menu-icon">...</div>
      </div>
    </div>

    <div class="see-more">
      <div class="see-more-block"></div>
      See previous notifications
    </div>
  </div>

  <!-- Add this inside <body>, after .container div  -->

  <div id="modal" class="modal">
    <div class="modal-content">
      <span class="modal-close">&times;</span>
      <div class="modal-drag"></div>
      <div class="modal-header">
        <img id="modal-img" src="./img/bbh.avif" alt="user">
        <p id="modal-text"></p>
      </div>
      <hr>
      <div class="modal-option"> Delete this notification</div>
      <div class="modal-option"> Turn off notifications about this status</div>
      <div class="modal-option"> Report issue to Notifications Team</div>
    </div>
  </div>



  <script src="../assests/js/noti.js"></script>

  <script src="../assests/js/script.js"></script>
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

    function renderNotification(noti) {
      const time = timeAgo(noti.created_at);
      const userImg = `../assests/images/post_images/${noti.profile}`;
      const name = noti.name;

      switch (noti.type) {
        case "login":
          return `
        <div class="notification">
          <img src="${userImg}" alt="user">
          <div class="content">
            <div class="text"><strong>${name}</strong> posted a photo in "MetroMedia"</div>
            <div class="time">${time}</div>
          </div>
          <div class="menu-icon">...</div>
        </div>
      `;

        case "new post":
          return `
        <div class="notification">
          <img src="${userImg}" alt="user">
          <div class="content">
            <div class="text"><strong>${name}</strong> added new post</div>
            <div class="time">${time}</div>
          </div>
          <div class="menu-icon">...</div>
        </div>
      `;

        case "group_post":
          return `
        <div class="notification">
          <img src="${userImg}" alt="user">
          <div class="content">
            <div class="text"><strong>${name}</strong> and ${noti.extra_count} others posted in your batch</div>
            <div class="time">${time}</div>
          </div>
          <div class="menu-icon">...</div>
        </div>
      `;

        case "invite":
          return `
        <div class="notification">
          <img src="${userImg}" alt="user">
          <div class="content">
            <div class="text"><strong>${name}</strong> invited you to like <strong>${noti.link_text}</strong></div>
            <div class="buttons">
              <button class="accept">Accept</button>
              <button class="decline">Decline</button>
            </div>
            <div class="time">${time}</div>
          </div>
          <div class="menu-icon">...</div>
        </div>
      `;

        default:
          return `
        <div class="notification">
          <img src="${userImg}" alt="user">
          <div class="content">
            <div class="text"><strong>${name}</strong> ${noti.type}</div>
            <div class="time">${time}</div>
          </div>
          <div class="menu-icon">...</div>
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
    setInterval(fetchNotifications, 5000); // Refresh every 5 sec
  </script>


</body>

</html>