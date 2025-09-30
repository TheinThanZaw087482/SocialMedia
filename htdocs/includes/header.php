<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Header</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            margin: 0;
            padding-top: 70px;
        }

        .navIcon {
            background: linear-gradient(60deg, #00E1FD, #FC007A);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
            font-size: 1.5rem;
        }


        .custom-navbar {
            height: 75px;
            background-color: #0A0A0A;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand img {
            width: 50px;
            height: 50px;
        }

        .nav-icons {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 1rem;
            margin-right: 20px;
            margin-left: 30px;
        }

        .nav-icons .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 1.3rem;
            color: #fff;
            position: relative;
        }

        .nav-icons .nav-link:hover {
            color: #d1e7ff;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: -5px;
            transform: translate(50%, -50%);
            font-size: 0.6rem;
            padding: 0.25em 0.5em;
        }

        @media (min-width: 992px) {
            .notification-badge {
                top: -5px;
                right: -5px;
                transform: none;
                font-size: 0.65rem;
                padding: 2px 6px;
                line-height: 1;
            }
        }

        form[role="search"] .form-control {
            border: none;
            background-color: #f0f2f5;
            border-radius: 20px;
            padding-left: 15px;
            font-size: 16px;
            color: #000;
            min-width: 250px;
            max-width: 450px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        form[role="search"] .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(111, 170, 197, 0.8);
            background-color: #fff;
        }

        .mobile-nav-bar {
            display: none;
        }

        /* CSS */
        .search-input-container {
            position: relative;
            display: flex;
            align-items: center;
            background: #0a0a0a;
            border-radius: 999px;
            padding: 2px;
            height: 30px;
            box-shadow:
                0 0 15px rgba(255, 0, 255, 0.3),
                0 0 25px rgba(150, 0, 255, 0.3),
                0 0 35px rgba(150, 0, 255, 0.4);
            background-image: radial-gradient(circle at center, #1a001f 0%, #0a0a0a 100%);
        }

        .search-bar {
            flex: 1;
            padding: 16px 20px;
            font-size: 16px;
            color: #fff;
            background: transparent;
            border: none;
            outline: none;
            border-radius: 999px;
        }

        .search-icon {
            position: absolute;
            right: 18px;
            color: #ccc;
            font-size: 18px;
            pointer-events: none;
        }


        .search-icon {
            position: absolute;
            right: 15px;
            /* Adjust as needed for horizontal position */
            top: 50%;
            transform: translateY(-50%);
            /* Vertically center the icon */
            color: #888;
            /* Adjust icon color */
            pointer-events: none;
            /* Allows clicks to pass through to the input */
        }

        @media (max-width: 991.98px) {
            .custom-navbar {
                display: none;
            }

            body {
                padding-top: 70px;
            }

            .mobile-nav-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 65px;
                background-color: black;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                padding: 0 10px;
                z-index: 1050;
            }

            .mobile-nav-left,
            .mobile-nav-right {
                display: flex;
                align-items: center;
            }

            .mobile-nav-left {
                flex: 1;
                display: flex;
                align-items: center;
                gap: 6px;
                min-width: 0;
            }

            .mobile-nav-right {
                gap: 0.6rem;
                margin-left: 12px;
                flex-shrink: 0;
            }

            .mobile-nav-bar .nav-link {
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: white;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
                padding: 0;
                position: relative;
            }

            .mobile-nav-bar .nav-link.active-icon {
                background-color: #000000;
                color: #ffffff;
            }

            .mobile-nav-bar .navbar-brand img {
                width: 40px;
                height: 40px;
            }

            .mobile-nav-bar .notification-badge {
                top: -3px;
                right: -4px;
                transform: none;
                font-size: 0.65rem;
                padding: 2px 5px;
                line-height: 1;
                z-index: 1;
            }

            .mobile-search-input {
                flex-grow: 1;
                max-width: 100%;
                visibility: hidden;
                opacity: 0;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }

            .mobile-search-input.show {
                visibility: visible;
                opacity: 1;
            }

            .mobile-search-input input {
                width: 100%;
                border: none;
                border-radius: 20px;
                padding: 6px 14px;
                background-color: #F0F2F5;
                font-size: 15px;
                transition: all 0.3s ease;
            }

            .mobile-search-input input:focus {
                outline: none;
                background-color: #fff;
                box-shadow: 0 0 0 1px #ccc;
            }

            .search-icon-wrapper {
                background-color: rgb(88, 130, 202);
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                flex-shrink: 0;
                font-size: 1.2rem;
                color: #050505;
                margin-left: 6px;
            }

            .search-icon-wrapper i {
                font-size: 1.5rem;
                color: rgb(255, 255, 255);
            }
        }

        @media (min-width: 700px) and (max-width: 991.98px) {
            .custom-navbar .d-flex.align-items-center.flex-grow-1 {
                flex-wrap: wrap;
                justify-content: flex-start !important;
                margin-right: 0 !important;
                gap: 1rem !important;
            }

            .custom-navbar form[role="search"] {
                min-width: 200px !important;
                max-width: 300px !important;
            }

            .custom-navbar .nav-link i {
                font-size: 1.3rem !important;
            }

            .custom-navbar .nav-link {
                padding: 0.3rem 0.6rem;
            }
        }

        .iconWhite {
            background: linear-gradient(60deg, #00E1FD, #FC007A);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
            font-size: 1.5rem;
        }
    </style>
</head>

<body>

    <!-- Desktop Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar d-none d-lg-flex">
        <div class="container-fluid px-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3" style="min-width: 0;">
                <a class="navbar-brand me-0" href="../pages/Dashboard.php">
                    <img src="../assests/images/icon/m.jpg" alt="Logo" class="rounded-circle">
                </a>

                <!-- HTML -->
              
                <a class="nav-link p-2" href="../pages/search_result.php" style="color: #fff; font-size: 1.5rem;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>

                <form role="search" style="min-width: 350px; max-width: 600px;" action="../pages/search_result.php" method="GET">
                    <div class="input-group">
                        <input type="search" class="form-control border-0 rounded-pill px-3" placeholder="Search"
                            aria-label="Search" name="query" />
                        <button type="submit" style="display: none;"></button>
                    </div>
                </form>

            </div>

            <a class="nav-link" href="../pages/Dashboard.php" title="Home">
                <i class="fa-solid fa-home navIcon"></i>
            </a>

            <a class="nav-link" href="../pages/friend-add.php" title="Friends">
                <i class="fa-solid fa-user-group navIcon"></i>
            </a>

            <a class="nav-link position-relative" href="../pages/messanger.php" title="Messenger">
                <i class="fa-brands fa-facebook-messenger navIcon"></i>
            </a>

            <a class="nav-link position-relative" href="../pages/notione.php" title="Notifications">
                <i class="fa-solid fa-bell navIcon"></i>
            </a>

            <a class="nav-link" href="../pages/profile.php" title="User">
                <i class="fa-solid fa-circle-user navIcon" style="color: white;"></i>
            </a>

            <a class="nav-link" href="../process/logout.php" title="Logout">
                <i class="fa-solid fa-right-from-bracket navIcon"></i>
            </a>
        </div>
    </nav>

    <!-- Mobile Navbar -->
    <div class="mobile-nav-bar d-lg-none">
        <div class="mobile-nav-left">
            <a class="navbar-brand" href="../pages/Dashboard.php">
                <img src="../assests/images/icon/m.jpg" alt="Logo" class="rounded-circle" />
            </a>

            <div class="search-icon-wrapper" id="mobileSearchToggle" style="background: white;">
                <i class="fa-solid fa-magnifying-glass  iconWhite"></i>
            </div>

            <div class="mobile-search-input" id="mobileSearchInputWrapper">
                <input type="text" placeholder="Search..." id="mobileSearchInput" />
            </div>
        </div>

        <div class="mobile-nav-right">
            <a class="nav-link" href="../pages/friend-add.php"><i class="fa-solid fa-user-group iconWhite"></i></a>
            <a class="nav-link position-relative" href="../pages/messanger.php">
                <i class="fa-brands fa-facebook-messenger iconWhite"></i>
                <span class="badge bg-danger rounded-pill notification-badge">5</span>
            </a>
            <a class="nav-link position-relative" href="../pages/notione.php">
                <i class="fa-solid fa-bell  iconWhite"></i>
                <span class="badge bg-danger rounded-pill notification-badge">2</span>
            </a>
            <a class="nav-link" href="../pages/profile.php"><i class="fa-solid fa-circle-user  iconWhite"></i></a>
            <a class="nav-link" href="../pages/setting.php"><i class="fa-solid fa-gear  iconWhite"></i></a>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const searchToggle = document.getElementById("mobileSearchToggle");
        const searchInputWrapper = document.getElementById("mobileSearchInputWrapper");
        const searchInput = document.getElementById("mobileSearchInput");

        searchToggle.addEventListener("click", function (e) {
            e.preventDefault();
            searchInputWrapper.classList.toggle("show");
            if (searchInputWrapper.classList.contains("show")) {
                setTimeout(() => searchInput.focus(), 100);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>