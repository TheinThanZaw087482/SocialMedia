<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Modern Search Results</title>
    <style>
        /* Your existing CSS here */
        :root {
            --primary-color: #1877f2;
            --light-bg: #f0f2f5;
            --card-bg: #ffffff;
            --border-radius: 12px;
            --font-color: #1c1e21;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: var(--light-bg);
            padding: 30px;
        }

        .results-section {
            max-width: 700px;
            margin: 0 auto 25px auto;
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 20px 24px;
        }

        .results-section h4 {
            font-size: 17px;
            font-weight: 600;
            color: var(--font-color);
            margin-bottom: 16px;
            border-bottom: 1px solid #e4e6eb;
            padding-bottom: 8px;
        }

        .result-entry {
            display: flex;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .result-entry:last-child {
            border-bottom: none;
        }

        .profile-pic {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background-color: #ccc;
            margin-right: 16px;
            background-size: cover;
            background-position: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .entry-info {
            flex: 1;
        }

        .entry-info p {
            margin: 2px 0;
            font-size: 14px;
            color: #555;
        }

        .entry-info .name {
            font-weight: bold;
            font-size: 15px;
            color: #050505;
        }

        .entry-action button {
            padding: 6px 14px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            background: linear-gradient(to right, #1877f2, #4a91f2);
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 2px 6px rgba(24, 119, 242, 0.4);
        }

        .entry-action button:hover {
            background: linear-gradient(to right, #1466d3, #3a7fe0);
        }

        .see-all {
            text-align: center;
            margin-top: 12px;
        }

        .see-all button {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            padding: 6px;
        }

        .see-all button:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body style="margin-top: 30px;">

    <?php
    include("../includes/header.php");
    include("../includes/get_users.php");

    $search_query = "";
    $users = []; // Initialize $users as an empty array

    if (isset($_GET['query'])) {
        $search_query = trim($_GET['query']);
        $users = get_search_users($search_query); // Call your function
    }
    ?>

    <?php
    // Condition to display the users section:
    // - If a search query was provided AND
    // - The $users array is not empty (meaning results were found)
    if (!empty($search_query) && !empty($users)) { ?>
        <div class="results-section">
            <h4>Users matching "<?php echo htmlspecialchars($search_query); ?>"</h4>
            <?php
            foreach ($users as $user) { ?>
                <div class="result-entry">
                    <div class="profile-pic" style="background-image: url('<?php echo htmlspecialchars("../assests/images/post_images/" . ($user['ProfileimagePath'] ?? 'default_profile.jpg')); ?>');"></div>
                    <div class="entry-info">
                        <p class="name"><?php echo htmlspecialchars($user['name']); ?></p>
                        <p><?php echo htmlspecialchars($user['userType']); ?></p>
                    </div>
                    <div class="entry-action">
                        <button>Message</button>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    <?php } elseif (!empty($search_query)) { ?>
        <div class="results-section">
            <h4>Users</h4>
            <p>No users found matching "<?php echo htmlspecialchars($search_query); ?>".</p>
        </div>
    <?php } else { ?>
        <div class="results-section">
            <p>Please enter a search query to find users.</p>
        </div>
    <?php } ?>

    </body>

</html>