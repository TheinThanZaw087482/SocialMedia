<?php
include("../includes/db.php"); // Ensure this includes your database connection
include("../process/post.php");
$userid = $_SESSION['userid'];

// For demonstration, using static data to match the image, now with more entries
$stories = [
    // ... (Your existing $stories array remains unchanged) ...
    ['id' => 1, 'image' => '../assests/images/post_images/story-img-3.jpg', 'date' => 'May 31'], // Girl in blue dress
    ['id' => 2, 'image' => '../assests/images/post_images/story-img-1.jpg', 'date' => 'May 31'], // Woman in pink top
    ['id' => 3, 'image' => '../assests/images/post_images/story-img-4.jpg', 'date' => 'May 31'], // Smiling woman, hair up
    ['id' => 4, 'image' => '../assests/images/post_images/story-img-5.jpg', 'date' => 'May 31'], // Woman taking selfie
    ['id' => 5, 'image' => '../assests/images/post_images/story-img-6.jpg', 'date' => 'May 31'], // Example 5th image (use an actual one if you have)
    ['id' => 6, 'image' => '../assests/images/post_images/story-img-3.jpg', 'date' => 'May 31'], // Example 6th image
    ['id' => 7, 'image' => '../assests/images/post_images/story-img-1.jpg', 'date' => 'May 31'], // Example 7th image
    ['id' => 8, 'image' => '../assests/images/post_images/story-img-4.jpg', 'date' => 'May 31'], // Example 8th image
];

// --- Mock function to simulate fetching reactors from the database ---
// Added a 'total_views' parameter to the returned array for demonstration
function getStoryReactors($storyId, $conn) {
    // In a real application, you would do something like:
    // $stmt = $conn->prepare("SELECT u.username, u.profile_picture, COUNT(sr.id) AS total_views FROM story_reactions sr JOIN users u ON sr.user_id = u.id WHERE sr.story_id = ? GROUP BY sr.story_id ORDER BY sr.created_at DESC");
    // $stmt->bind_param("i", $storyId);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // return $result->fetch_all(MYSQLI_ASSOC); // This would return one row with total_views if grouped
    // OR if you need individual reactors AND total count:
    // $stmt_reactors = $conn->prepare("SELECT u.username, u.profile_picture FROM story_reactions sr JOIN users u ON sr.user_id = u.id WHERE sr.story_id = ? ORDER BY sr.created_at DESC");
    // $stmt_reactors->bind_param("i", $storyId);
    // $stmt_reactors->execute();
    // $reactors = $stmt_reactors->get_result()->fetch_all(MYSQLI_ASSOC);

    // $stmt_count = $conn->prepare("SELECT COUNT(id) AS total_views FROM story_reactions WHERE story_id = ?");
    // $stmt_count->bind_param("i", $storyId);
    // $stmt_count->execute();
    // $total_views_row = $stmt_count->get_result()->fetch_assoc();
    // $total_views = $total_views_row['total_views'] ?? 0;
    // return ['reactors' => $reactors, 'total_views' => $total_views];


    // For demonstration, return static reactor data with enough entries to trigger scroll
    // and a simulated total_views count
    $dummy_reactors_data = [
        1 => [
            'reactors' => [
                ['username' => 'Alice_Reacts_1', 'profile_picture' => '../assests/images/profile/profile-1.png'],
                ['username' => 'Bob_LikesIt_2', 'profile_picture' => '../assests/images/profile/profile-2.png'],
                ['username' => 'Charlie_Awesome_3', 'profile_picture' => '../assests/images/profile/profile-3.png'],
                ['username' => 'Diana_Viewer_4', 'profile_picture' => '../assests/images/profile/profile-4.png'],
                ['username' => 'Ethan_Fan_5', 'profile_picture' => '../assests/images/profile/profile-5.png'],
                ['username' => 'Fiona_Good_6', 'profile_picture' => '../assests/images/profile/profile-6.png'],
                ['username' => 'George_Reacted_7', 'profile_picture' => '../assests/images/profile/profile-7.png'],
                ['username' => 'Hannah_Loves_8', 'profile_picture' => '../assests/images/profile/profile-8.png'],
                ['username' => 'Ivy_Watch_9', 'profile_picture' => '../assests/images/profile/profile-1.png'],
                ['username' => 'Jack_Cool_10', 'profile_picture' => '../assests/images/profile/profile-2.png'],
                ['username' => 'Kelly_Wow_11', 'profile_picture' => '../assests/images/profile/profile-3.png'],
            ],
            'total_views' => 15 // Example total views for story 1
        ],
        2 => [
            'reactors' => [
                ['username' => 'David_Views_1', 'profile_picture' => '../assests/images/profile/profile-4.png'],
                ['username' => 'Eve_Seeit_2', 'profile_picture' => '../assests/images/profile/profile-5.png'],
                ['username' => 'Frank_Reacted_3', 'profile_picture' => '../assests/images/profile/profile-6.png'],
            ],
            'total_views' => 3 // Example total views for story 2
        ],
        3 => [
            'reactors' => [
                ['username' => 'Frank_Official_1', 'profile_picture' => '../assests/images/profile/profile-6.png'],
                ['username' => 'Grace_Fan_2', 'profile_picture' => '../assests/images/profile/profile-7.png'],
                ['username' => 'Heidi_Seen_3', 'profile_picture' => '../assests/images/profile/profile-8.png'],
                ['username' => 'Ivan_Reacts_4', 'profile_picture' => '../assests/images/profile/profile-1.png'],
                ['username' => 'Liam_Love_5', 'profile_picture' => '../assests/images/profile/profile-2.png'],
                ['username' => 'Mia_View_6', 'profile_picture' => '../assests/images/profile/profile-3.png'],
                ['username' => 'Noah_Cool_7', 'profile_picture' => '../assests/images/profile/profile-4.png'],
            ],
            'total_views' => 10 // Example total views for story 3
        ],
        4 => [
            'reactors' => [
                ['username' => 'Uma_U_1', 'profile_picture' => '../assests/images/profile/profile-2.png'],
                ['username' => 'Victor_V_2', 'profile_picture' => '../assests/images/profile/profile-3.png'],
                ['username' => 'Wendy_W_3', 'profile_picture' => '../assests/images/profile/profile-4.png'],
                ['username' => 'Xavier_X_4', 'profile_picture' => '../assests/images/profile/profile-5.png'],
                ['username' => 'Yara_Y_5', 'profile_picture' => '../assests/images/profile/profile-6.png'],
            ],
            'total_views' => 5 // Example total views for story 4
        ],
        5 => [
            'reactors' => [
                ['username' => 'Zoe_Z_1', 'profile_picture' => '../assests/images/profile/profile-7.png'],
                ['username' => 'Adam_A_2', 'profile_picture' => '../assests/images/profile/profile-8.png'],
                ['username' => 'Bella_B_3', 'profile_picture' => '../assests/images/profile/profile-1.png'],
                ['username' => 'Caleb_C_4', 'profile_picture' => '../assests/images/profile/profile-2.png'],
                ['username' => 'Daisy_D_5', 'profile_picture' => '../assests/images/profile/profile-3.png'],
                ['username' => 'Erin_E_6', 'profile_picture' => '../assests/images/profile/profile-4.png'],
            ],
            'total_views' => 8 // Example total views for story 5
        ],
        6 => [
            'reactors' => [
                ['username' => 'Leo_L_1', 'profile_picture' => '../assests/images/profile/profile-4.png'],
                ['username' => 'Mia_M_2', 'profile_picture' => '../assests/images/profile/profile-5.png'],
                ['username' => 'Noah_N_3', 'profile_picture' => '../assests/images/profile/profile-6.png'],
            ],
            'total_views' => 3 // Example total views for story 6
        ],
        7 => [
            'reactors' => [
                ['username' => 'Peter_P_1', 'profile_picture' => '../assests/images/profile/profile-8.png'],
                ['username' => 'Quinn_Q_2', 'profile_picture' => '../assests/images/profile/profile-1.png'],
                ['username' => 'Ryan_R_3', 'profile_picture' => '../assests/images/profile/profile-2.png'],
                ['username' => 'Sara_S_4', 'profile_picture' => '../assests/images/profile/profile-3.png'],
                ['username' => 'Tom_T_5', 'profile_picture' => '../assests/images/profile/profile-4.png'],
            ],
            'total_views' => 6 // Example total views for story 7
        ],
        8 => [
            'reactors' => [
                ['username' => 'Sophia_S_1', 'profile_picture' => '../assests/images/profile/profile-3.png'],
                ['username' => 'Thomas_T_2', 'profile_picture' => '../assests/images/profile/profile-4.png'],
                ['username' => 'Ursula_U_3', 'profile_picture' => '../assests/images/profile/profile-5.png'],
                ['username' => 'Vincent_V_4', 'profile_picture' => '../assests/images/profile/profile-6.png'],
                ['username' => 'Willow_W_5', 'profile_picture' => '../assests/images/profile/profile-7.png'],
                ['username' => 'Xander_X_6', 'profile_picture' => '../assests/images/profile/profile-8.png'],
            ],
            'total_views' => 7 // Example total views for story 8
        ],
    ];

    return $dummy_reactors_data[$storyId] ?? ['reactors' => [], 'total_views' => 0];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assests/css/profile.css">
    <link rel="stylesheet" href="../assests/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Metro Book</title>
</head>

<body style=" margin-top: 80px;">
    <div>
        <?php
        include("../includes/header.php")
        ?>

        <div class="container mt-4">
            <h2>Story History</h2>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">

                <?php foreach ($stories as $story) : ?>
                    <div class="col">
                        <a href="#" class="story-archive-item-link" data-bs-toggle="modal" data-bs-target="#storyArchiveModal_<?php echo $story['id']; ?>">
                            <div class="card text-white story-archive-item">
                                <img src="<?php echo $story['image']; ?>" class="card-img story-archive-img" alt="Story from <?php echo $story['date']; ?>">
                                <div class="card-img-overlay d-flex flex-column">
                                    <h5 class="card-title story-date mt-auto"><?php echo $story['date']; ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <?php foreach ($stories as $story) : ?>
            <div class="modal fade" id="storyArchiveModal_<?php echo $story['id']; ?>" tabindex="-1" aria-labelledby="storyArchiveModal_<?php echo $story['id']; ?>Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="storyArchiveModal_<?php echo $story['id']; ?>Label">Story from <?php echo $story['date']; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="<?php echo $story['image']; ?>" class="img-fluid mb-3" alt="Full Story from <?php echo $story['date']; ?>">
                            <p>Full story content and text for <?php echo $story['date']; ?> would go here.</p>

                            <div class="reacter-list-section mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6>People who reacted:</h6>
                                    <?php
                                    // Fetch reactors and total views for the current story
                                    $story_data = getStoryReactors($story['id'], $conn);
                                    $reactors = $story_data['reactors'] ?? [];
                                    $total_views = $story_data['total_views'] ?? 0;
                                    ?>
                                    <h6 class="text-white mb-1">Views: <?php echo $total_views; ?></h6>
                                </div>

                                <div class="reacter-list-scroll-container">
                                    <div class="reacter-avatars">
                                        <?php
                                        if (!empty($reactors)) {
                                            foreach ($reactors as $reactor) {
                                                echo '<div class="reacter-item">';
                                                echo '<img src="' . htmlspecialchars($reactor['profile_picture']) . '" alt="' . htmlspecialchars($reactor['username']) . '" class="rounded-circle reacter-avatar">';
                                                echo '<small class="text-truncate">' . htmlspecialchars($reactor['username']) . '</small>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<p class="text-muted">No reactions yet.</p>';
                                        }
                                        ?>
                                    </div>
                                </div> </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <style>
            /* General styles for story archive items */
            .story-archive-item {
                border-radius: 15px;
                overflow: hidden;
                position: relative;
                height: 300px;
            }

            .story-archive-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .story-archive-item:hover .story-archive-img {
                transform: scale(1.05);
            }

            .story-archive-item .card-img-overlay {
                background: linear-gradient(to bottom, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0) 40%, rgba(0, 0, 0, 0) 60%, rgba(0, 0, 0, 0.7) 100%);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 0.8rem;
            }

            .story-date {
                font-weight: bold;
                align-self: flex-start;
                background-color: rgba(0, 0, 0, 0.4);
                padding: 0.25rem 0.5rem;
                border-radius: 5px;
                font-size: 0.9rem;
            }

            /* Modal specific styles */
            .modal-content {
                background-color: #212529;
                color: white;
            }

            .modal-header {
                border-bottom: 1px solid #495057;
            }

            .btn-close {
                filter: invert(1) grayscale(100%) brightness(200%);
            }

            .modal-body img {
                max-height: 60vh;
                width: auto;
                max-width: 100%;
            }

            /* --- Styles for Reacter List View in Modals --- */
            .reacter-list-section {
                border-top: 1px solid #495057;
                margin-top: 1rem;
                padding-top: 0.75rem;
            }

            /* The container that controls the scroll behavior */
            .reacter-list-scroll-container {
                max-height: 200px; /* Adjust this value to control how many items are visible (e.g., 5-6 items) */
                overflow-y: auto;   /* This enables vertical scrolling if content exceeds max-height */
                padding-right: 10px; /* Provides space for the scrollbar */

                /* Custom scrollbar styling for Webkit browsers (Chrome, Safari) */
                &::-webkit-scrollbar {
                    width: 8px; /* Width of the vertical scrollbar */
                }
                &::-webkit-scrollbar-track {
                    background: #212529; /* Color of the scrollbar track */
                }
                &::-webkit-scrollbar-thumb {
                    background-color: #6c757d; /* Color of the scroll thumb */
                    border-radius: 4px; /* Roundness of the scroll thumb */
                    border: 2px solid #212529; /* Space around the scroll thumb */
                }

                /* Custom scrollbar styling for Firefox */
                scrollbar-width: thin; /* "auto" or "thin" */
                scrollbar-color: #6c757d #212529; /* thumb and track color */
            }


            .reacter-avatars {
                padding: 0;
                list-style: none;
            }

            .reacter-item {
                display: flex; /* Makes each item a flex container for avatar and name */
                align-items: center; /* Vertically aligns avatar and name */
                margin-bottom: 8px; /* Spacing between each reacter line */
                width: 100%; /* Ensures each item takes full width */
            }

            .reacter-avatar {
                width: 30px;
                height: 30px;
                object-fit: cover;
                border: 2px solid #5cb85c;
                border-radius: 50%;
                margin-right: 10px; /* Space between avatar and name */
                margin-bottom: 0; /* No bottom margin here */
            }

            .reacter-item small {
                font-size: 0.9rem;
                color: #ced4da;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                flex-grow: 1; /* Allows username to take remaining horizontal space */
                text-align: left; /* Aligns text to the left */
            }
        </style>
    </div>
</body>

</html>