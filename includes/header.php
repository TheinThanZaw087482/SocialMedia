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

        .custom-navbar {
            height: 65px;
            background-color: rgb(111, 170, 197);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand img {
            width: 50px;
            height: 50px;
        }

        .custom-search {
            height: 45px;
            max-width: 450px;
            width: 100%;
            flex-grow: 1;
        }

        .custom-search input:focus {
            font-size: 18px;
            outline: none;
            box-shadow: none;
            border-color: transparent;
        }

        .nav-icons {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 7rem;
            margin-right: 100px;
        }

        .nav-icons .nav-link {
            padding: 0.3rem;
            font-size: 1.3rem;
        }

        .notification-badge {
            position: absolute;
            top: 4;
            right: 0;
            transform: translate(40%, -50%);
            font-size: 0.6rem;
            padding: 0.25em 0.5em;
        }

        @media (max-width: 768px) {
            .custom-search {
                max-width: 300px;
                flex-grow: 1;
                min-width: 150px;
            }

            .nav-icons {
                gap: 1.5rem;
                margin-right: 20px;
            }
        }

        @media (max-width: 550px) {
            .custom-search {
                max-width: 220px;
                min-width: 160px;
                margin-left: auto;
                margin-right: auto;
                flex-grow: 0;
            }

            .nav-icons {
                gap: 1rem;
                margin-right: 0;
                flex-wrap: nowrap;
                overflow-x: auto;
            }

            .nav-icons .nav-link {
                padding: 0.3rem 0.5rem;
                font-size: 1.2rem;
            }

            .container-fluid.px-3 {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
        }

        @media (max-width: 991.98px) {
            #navbarNav {
                position: absolute;
                top: 100%;
                right: 1rem;
                background-color: #fff;
                z-index: 1000;
                padding: 0.25rem;
                border-radius: 0.5rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                width: fit-content;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: opacity 0.3s ease, transform 0.5s ease, visibility 0.5s;
            }

            #navbarNav.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            #navbarNav ul {
                flex-direction: column;
                align-items: stretch;
                padding: 0.25rem 0.15rem;
                margin: 0;
                list-style: none;
                width: auto;
            }

            #navbarNav .nav-item {
                padding: 0.1rem 0;
            }

            #navbarNav .nav-link {
                padding: 0.25rem 0.4rem;
                width: 100%;
                white-space: nowrap;
                display: block;
            }

            #navbarNav .nav-icons {
                gap: 0.3rem;
            }

            .custom-search {
                max-width: 350px;
                margin: 0 auto;
            }

            .notification-badge {
                top: -3px;
                right: 2px;
            }
        }

        @media (max-width: 576px) {
            .custom-navbar .container-fluid {
                flex-wrap: wrap;
                row-gap: 0.5rem;
            }

            .navbar-toggler {
                margin-left: auto;
                order: 3;
            }

            .custom-search {
                order: 2;
                width: 100%;
            }

            #navbarNav {
                right: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container-fluid px-3 d-flex align-items-center justify-content-start gap-3">
            <a class="navbar-brand me-0" href="#"><img src="../assests/images/icon/m.jpg" alt="Logo"
                    class="rounded-circle"></a>
            <form class="d-none d-lg-flex" role="search" style="flex-grow:1; max-width:500px;">
                <div class="input-group custom-search">
                    <span class="input-group-text bg-light border-0"><i class="fa fa-search"></i></span>
                    <input type="search" class="form-control border-0 bg-light" placeholder="Search" />
                </div>
            </form>
            <ul class="navbar-nav nav-icons d-none d-lg-flex flex-row">
                <li class="nav-item"><a class="nav-link" href="../pages/Dashboard.php"><i
                            class="fa-solid fa-house"></i></a></li>
                <li class="nav-item"><a class="nav-link" href="../pages/friend-add.php"><i
                            class="fa-solid fa-user-group"></i></a></li>
                <li class="nav-item"><a class="nav-link" href="../pages/messanger.php"><i
                            class="fa-brands fa-facebook-messenger"></i></a></li>
                <li class="nav-item position-relative">
                    <a class="nav-link notification-link" href="../pages/notione.php" id="notificationBell">
                        <i class="fa-solid fa-bell"></i>
                        <span class="badge bg-danger rounded-pill notification-badge" id="notificationBadge">5</span>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="../pages/profile.php"><i
                            class="fa-solid fa-circle-user"></i></a></li>
                <li class="nav-item"><a class="nav-link" href="../process/logout.php" title="Logout"><i
                            class="fa-solid fa-right-from-bracket"></i></a></li>
            </ul>
            <form class="d-block d-lg-none flex-grow-1" role="search">
                <div class="input-group custom-search">
                    <span class="input-group-text bg-light border-0"><i class="fa fa-search"></i></span>
                    <input type="search" class="form-control border-0 bg-light" placeholder="Search" />
                </div>
            </form>
            <button class="navbar-toggler ms-auto" type="button" id="burgerToggle"><span
                    class="navbar-toggler-icon"></span></button>
            <div class="navbar-collapse flex-grow-0" id="navbarNav">
                <ul class="navbar-nav nav-icons d-lg-none">
                    <li class="nav-item"><a class="nav-link" href="../pages/Dashboard.php"><i
                                class="fa-solid fa-house"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="../pages/friend-add.php"><i
                                class="fa-solid fa-user-group"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="../pages/messanger.php"><i
                                class="fa-brands fa-facebook-messenger"></i></a></li>
                    <li class="nav-item position-relative">
                        <a class="nav-link notification-link" href="../pages/notione.php" id="notificationBellDesktop">
                            <i class="fa-solid fa-bell"></i>
                            <span class="badge bg-danger rounded-pill notification-badge"
                                id="notificationBadgeDesktop">5</span>
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="../pages/profile.php"><i
                                class="fa-solid fa-circle-user"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="../pages/setting.php"><i
                                class="fa-solid fa-gear"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('burgerToggle');
        const navbarNav = document.getElementById('navbarNav');
        toggleBtn.addEventListener('click', () => {
            navbarNav.classList.toggle('show');
        });

        const notificationBell = document.getElementById('notificationBell');
        const notificationBadge = document.getElementById('notificationBadge');
        if (notificationBell && notificationBadge) {
            notificationBell.addEventListener('click', () => {
                notificationBadge.style.display = 'none';
            });
        }

        const mobileBell = document.getElementById('notificationBellDesktop');
        const mobileBadge = document.getElementById('notificationBadgeDesktop');
        if (mobileBell && mobileBadge) {
            mobileBell.addEventListener('click', () => {
                mobileBadge.style.display = 'none';
            });
        }
    </script>
</body>

</html>