<?php
session_start();
include("../process/get_stories.php");
$current_viewer_id = $_SESSION['userid'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Main Story</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assests/css/story-style.css">
</head>

<body>
    <div class="stories-container">

        <?php
        $user_stories_data = get_current_user_stories();

        // Check if $user_stories_data is NOT null and has at least one segment (meaning stories exist)
        if ($user_stories_data && !empty($user_stories_data['segments'])) {
            // Get the first segment's content for the thumbnail
            $first_story_segment = $user_stories_data['segments'][0];
        ?>
            <div class="story has-new-story" data-story-id="my_day">
                <div class="story-thumbnail">
                    <img src="<?= htmlspecialchars($first_story_segment['content']) ?>" alt="My Day Thumbnail">
                    <div class="user-avatar-overlay has-new-story">
                        <img src="<?= htmlspecialchars($user_stories_data['user_avatar']) ?>" alt="My Day Avatar">
                    </div>
                </div>
                <p>Your Story</p>
            </div>
        <?php
        }
        $stories =  get_stories();

        foreach ($stories as $story) {
            $new_class = $story['has_new'] ? 'has-new-story' : '';
            $story_thumbnail = !empty($story['segments'][0]['content']) ? str_replace('\\', '/', $story['segments'][0]['content']) : '../assests/images/default_thumbnail.png';

            echo '<div class="story ' . $new_class . '" data-story-id="' . htmlspecialchars($story['id']) . '">';
            echo '    <div class="story-thumbnail">';
            echo '        <img src="' . htmlspecialchars($story_thumbnail) . '" alt="' . htmlspecialchars($story['user_name']) . ' Story">';
            echo '        <div class="user-avatar-overlay ' . $new_class . '">';
            echo '            <img src="' . htmlspecialchars(str_replace('\\', '/', $story['user_avatar'])) . '" alt="' . htmlspecialchars($story['user_name']) . '">';
            echo '        </div>';
            echo '    </div>';
            echo '    <p>' . htmlspecialchars($story['user_name']) . '</p>';
            echo '</div>';
        }


        // A helper function to process segments for JSON encoding
        function formatSegmentsForJs($segments)
        {
            return array_map(function ($segment) {
                if (isset($segment['content'])) {
                    // Replace backslashes for URL consistency on web
                    $segment['content'] = str_replace('\\', '/', $segment['content']);
                }
                // Ensure duration is always set, default to 5000ms if not provided
                $segment['duration'] = $segment['duration'] ?? 5000;
                // Ensure time_ago is set
                $segment['time_ago'] = $segment['time_ago'] ?? 'Just now';
                return $segment;
            }, $segments);
        }

        // A helper function to process viewers for JSON encoding
        function formatViewersForJs($viewers_data)
        {
            if (!is_array($viewers_data)) {
                return []; // Return empty array if not array
            }
            return array_map(function ($viewer) {
                $formatted_viewer = [
                    'user_id' => $viewer['user_id'] ?? null,
                    'user_name' => $viewer['user_name'] ?? 'Unknown Viewer',
                    'user_avatar' => str_replace('\\', '/', $viewer['user_avatar'] ?? ''),
                    'reaction_emoji' => $viewer['reaction_emoji'] ?? 'view_icon.png',
                    'status' => $viewer['status'] ?? ''
                ];
                return $formatted_viewer;
            }, $viewers_data);
        }


        // --- Construct the complete JavaScript allStoriesData array in PHP ---
        $jsAllStoriesData = [];

        // 1. Add the 'Create Story' card
        $jsAllStoriesData[] = [
            'id' => 'create',
            'user_name' => "Create Story",
            'user_avatar' => '../assests/images/post_images/1749587110_684894a6d1428.png',
            'segments' => [[
                'type' => 'image',
                'content' => '../assests/images/post_images/Haha.png',
                'duration' => 5000
            ]],
            'has_new' => false,
            'reaction_emoji' => null
        ];
        $currentLoggedInUserID = $_SESSION['userid'] ?? null;


        if (!empty($user_stories_data)) {
            $myDayStoryID = $user_stories_data['segments'][0]['story_ID'] ?? null;

            $rawViewersData = [];
            if ($myDayStoryID !== null) {
                $rawViewersData = get_story_viewer($myDayStoryID);
            }
            $formattedViewers = formatViewersForJs($rawViewersData);

            $myDayStory = [
                'id' => 'my_day', // Keep 'my_day' as the unique identifier for the card type
                'owner_user_id' => $currentLoggedInUserID, // <--- ADD THIS LINE!
                'user_name' => $user_stories_data['user_name'] ?? 'My Day',
                'user_avatar' => str_replace('\\', '/', $user_stories_data['user_avatar'] ?? '../assests/images/icon/Haha.png'), // Corrected 'assests'
                'segments' => formatSegmentsForJs($user_stories_data['segments'] ?? []),
                'has_new' => $user_stories_data['has_new'] ?? false,
                'reaction_emoji' => $user_stories_data['reaction_emoji'],
                'viewers' => $formattedViewers
            ];
            $jsAllStoriesData[] = $myDayStory;
        }
        foreach ($stories as $story) {
            $jsAllStoriesData[] = [
                'id' => $story['id'], // This should be the other user's ID
                'owner_user_id' => $story['id'], // You can explicitly add this for consistency, or rely on 'id' if it's always the user ID
                'user_name' => $story['user_name'],
                'user_avatar' => str_replace('\\', '/', $story['user_avatar']),
                'segments' => formatSegmentsForJs($story['segments']),
                'has_new' => $story['has_new'],
                'reaction_emoji' => $story['reaction_emoji'] ?? null,
            ];
        }


        ?>

        <div class="story-navigation-arrow">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>

    <div class="modal fade" id="storyViewerModal" tabindex="-1" aria-labelledby="storyViewerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex align-items-center">
                    <img id="storyViewerAvatar" src="" alt="User Avatar" class="rounded-circle me-2"
                        style="width: 40px; height: 40px; object-fit: cover;">
                    <div class="me-auto">
                        <h5 id="storyViewerUserName" class="modal-title text-white mb-0"></h5>
                        <small id="storyViewerTime" class="text-white-50"></small>
                    </div>
                    <div id="storyProgressBars" class="story-progress-container">
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body d-flex justify-content-center align-items-center flex-column position-relative">
                    <img id="storyViewerContentImage" src="" alt="Story Content" class="img-fluid"
                        style="object-fit: contain; max-width: 100%; max-height: 80vh;">
                    <button id="storyViewerPrev" class="story-nav-arrow story-nav-arrow-left">
                        <i class="fas fa-chevron-left"></i> </button>
                    <button id="storyViewerNext" class="story-nav-arrow story-nav-arrow-right">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <div id="defaultStoryFooter" class="d-flex flex-column w-100 align-items-center">
                        <div class="story-reactions mb-2">
                            <img src="../assests/images/icon/Like.png" alt="Like" class="reaction-icon">
                            <img src="../assests/images/icon/Love.png" alt="Heart" class="reaction-icon">
                            <img src="../assests/images/icon/Haha.png" alt="Haha" class="reaction-icon">
                            <img src="../assests/images/icon/Wow.png" alt="Wow" class="reaction-icon">
                            <img src="../assests/images/icon/Care.png" alt="Care" class="reaction-icon">
                            <img src="../assests/images/icon/Angry.png" alt="Angry" class="reaction-icon">
                        </div>
                    </div>

                    <div id="myDayStoryFooter" class="d-none w-100">
                        <div class="my-day-pull-up-handle">
                            <i class="fas fa-chevron-up"></i>
                        </div>
                        <p class="my-day-viewers-count text-white mb-0">2 viewers</p>
                        <div id="myDayViewerProfiles" class="d-flex">
                        </div>
                    </div>
                </div>

                <div id="storyDetailsPullUp">
                    <div class="modal-header">
                        <h5 class="modal-title">Story details</h5>
                        <button type="button" class="btn-close" id="closeStoryDetailsPullUp"
                            aria-label="Close"></button>
                    </div>
                    <div id="storyDetailsContent">
                        <p class="story-details-viewer-count"><i class="fas fa-eye"></i> <span
                                id="storyDetailsNumViewers">2 viewers</span></p>
                        <div id="storyDetailsViewerList" class="story-details-viewer-list">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Element References
            const storiesContainer = document.querySelector('.stories-container');
            const rightArrow = document.querySelector('.story-navigation-arrow');
            const storyViewerModal = new bootstrap.Modal(document.getElementById('storyViewerModal'));
            const storyViewerAvatar = document.getElementById('storyViewerAvatar');
            const storyViewerUserName = document.getElementById('storyViewerUserName');
            const storyViewerTime = document.getElementById('storyViewerTime');
            const storyViewerContentImage = document.getElementById('storyViewerContentImage');
            const storyViewerPrev = document.getElementById('storyViewerPrev');
            const storyViewerNext = document.getElementById('storyViewerNext');
            const storyProgressBarsContainer = document.getElementById('storyProgressBars');

            // Footer elements
            const defaultStoryFooter = document.getElementById('defaultStoryFooter');
            const myDayStoryFooter = document.getElementById('myDayStoryFooter');
            const myDayViewerProfiles = document.getElementById('myDayViewerProfiles');
            const myDayViewersCount = document.querySelector('.my-day-viewers-count');
            const myDayPullUpHandle = document.querySelector('.my-day-pull-up-handle');

            // Story Details Pull-Up Modal elements
            const storyDetailsPullUp = document.getElementById('storyDetailsPullUp');
            const closeStoryDetailsPullUp = document.getElementById('closeStoryDetailsPullUp');
            const storyDetailsNumViewers = document.getElementById('storyDetailsNumViewers');
            const storyDetailsViewerList = document.getElementById('storyDetailsViewerList');

            // PHP-generated data and VIEWER_ID
            const VIEWER_ID = <?php echo json_encode($current_viewer_id ?? null); ?>;
            console.log("VIEWER_ID:", VIEWER_ID);

            const allStoriesData = <?php echo json_encode($jsAllStoriesData); ?>;
            console.log("All Stories Data:", allStoriesData);

            // State Variables for Story Viewer
            let currentStoryUserIndex = 0;
            let currentStorySegmentIndex = 0;
            let segmentTimeout; // Declared for clearTimeout usage
            let progressInterval; // Declared for cancelAnimationFrame usage
            let currentProgressStartTime = 0;
            let pausedProgress = 0; // To store the elapsed time when progress is paused

            // --- recordStoryView function ---
            async function recordStoryView(storyOwnerId, storyId, viewerId, reactionType = null) {
                if (!viewerId) {
                    console.warn("Viewer ID is not set. Cannot record view/reaction.");
                    return;
                }
                if (reactionType !== null && storyId === null) {
                    console.warn("Cannot record reaction: Story ID is missing for reaction type:", reactionType);
                    return;
                }

                try {
                    const response = await fetch('../process/record_story_view.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            storyOwnerId: storyOwnerId,
                            storyId: storyId,
                            viewerId: viewerId,
                            reactionType: reactionType
                        }),
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}. Details: ${errorText}`);
                    }

                    const result = await response.json();
                    if (result.success) {
                        console.log("Operation successful:", result.message);
                    } else {
                        console.error("Operation failed:", result.message);
                    }
                } catch (error) {
                    console.error('There was a problem with the fetch operation:', error);
                }
            }

            // --- renderStoryCards function ---
            function renderStoryCards() {
                if (!storiesContainer) {
                    console.error("Stories container not found.");
                    return;
                }
                storiesContainer.innerHTML = '';

                allStoriesData.forEach(story => {
                    const storyElement = document.createElement('div');
                    storyElement.classList.add('story');
                    storyElement.dataset.storyId = story.id;

                    if (story.has_new) {
                        storyElement.classList.add('has-new-story');
                    }

                    if (story.id === 'create') {
                        storyElement.innerHTML = `
                  <div class="story create-story" data-story-id="create">
                <div class="create-story-top-visual">
                    <img src="../assests/images/post_images/1748078306_68318ee2d443f.jpg" alt="Your Profile"
                        class="create-story-profile-thumbnail">
                </div>
                <div class="create-story-plus-btn" onclick="location.href='../pages/profile.php?openAddMyDay=true'">
    <i class="fas fa-plus"></i>
</div>

                <div class="create-story-text-area">
                    <p>Create story</p>
                </div>
            </div>

           
              `;
                    } else {
                        const thumbnailUrl = story.segments && story.segments.length > 0 ?
                            story.segments[0].content :
                            story.user_avatar; // Fallback to avatar if no segments

                        storyElement.innerHTML = `
                    <img src="${thumbnailUrl}" alt="${story.user_name}" class="story-image">
                    <div class="user-avatar-overlay ${story.has_new ? 'has-new-story' : ''}">
                        <img src="${story.user_avatar}" alt="${story.user_name}">
                        ${story.reaction_emoji ? `<span class="story-reaction-emoji">${story.reaction_emoji}</span>` : ''}
                    </div>
                    <span>${story.user_name}</span>
                `;
                    }
                    storiesContainer.appendChild(storyElement);
                });
            }

            // --- renderProgressBars function ---
            function renderProgressBars() {
                storyProgressBarsContainer.innerHTML = '';
                const userStory = allStoriesData[currentStoryUserIndex];
                if (!userStory || !userStory.segments || userStory.segments.length === 0) {
                    console.warn("No segments found for current user story. Hiding modal.");
                    storyViewerModal.hide();
                    return;
                }
                userStory.segments.forEach((segment, index) => {
                    const progressBar = document.createElement('div');
                    progressBar.classList.add('story-progress-bar');
                    progressBar.dataset.segmentIndex = index;

                    const progressFill = document.createElement('div');
                    progressFill.classList.add('progress-fill');
                    progressBar.appendChild(progressFill);

                    storyProgressBarsContainer.appendChild(progressBar);
                });
            }

            // --- startProgressBar function ---
            function startProgressBar() {
                // Clear any previous animation frames or timeouts
                cancelAnimationFrame(progressInterval);
                clearTimeout(segmentTimeout);

                const userStory = allStoriesData[currentStoryUserIndex];
                if (!userStory || !userStory.segments || !userStory.segments[currentStorySegmentIndex]) {
                    console.error("Invalid segment to start progress bar for.");
                    return;
                }

                const currentSegment = userStory.segments[currentStorySegmentIndex];
                const timeAgo = currentSegment.time_ago;
                if (storyViewerTime) {
                    storyViewerTime.textContent = timeAgo;
                }

                const duration = currentSegment.duration || 5000;

                const allProgressFills = storyProgressBarsContainer.querySelectorAll('.progress-fill');

                allProgressFills.forEach((fill, index) => {
                    if (index > currentStorySegmentIndex) {
                        fill.style.width = '0%';
                    } else if (index < currentStorySegmentIndex) {
                        fill.style.width = '100%';
                    } else {
                        // Only reset to 0% if starting a new segment (not resuming from pause)
                        if (pausedProgress === 0) {
                            fill.style.width = '0%';
                        }
                    }
                });

                const currentProgressBarFill = storyProgressBarsContainer.querySelector(`.story-progress-bar[data-segment-index="${currentStorySegmentIndex}"] .progress-fill`);
                if (!currentProgressBarFill) {
                    console.error("Current progress bar fill element not found.");
                    return;
                }

                // Set the start time, accounting for any paused progress
                currentProgressStartTime = performance.now() - pausedProgress;
                pausedProgress = 0; // Reset pausedProgress once animation starts

                const animateProgress = (currentTime) => {
                    if (storyDetailsPullUp.classList.contains('show')) {
                        // If pull-up is active, pause progress and return
                        pausedProgress = currentTime - currentProgressStartTime;
                        cancelAnimationFrame(progressInterval);
                        return;
                    }

                    const elapsedTime = currentTime - currentProgressStartTime;
                    let progress = (elapsedTime / duration);
                    if (progress > 1) progress = 1;

                    currentProgressBarFill.style.width = `${progress * 100}%`;

                    if (progress < 1) {
                        progressInterval = requestAnimationFrame(animateProgress);
                    } else {
                        nextSegment(); // Move to the next segment when current one finishes
                    }
                };
                progressInterval = requestAnimationFrame(animateProgress);
            }

            // --- showStorySegment function ---
            function showStorySegment() {
                const userStory = allStoriesData[currentStoryUserIndex];
                if (!userStory || !userStory.segments || !userStory.segments[currentStorySegmentIndex]) {
                    console.warn("Attempted to show invalid story segment, trying next user story.");
                    currentStoryUserIndex++;
                    currentStorySegmentIndex = 0;
                    if (currentStoryUserIndex < allStoriesData.length) {
                        renderProgressBars();
                        showStorySegment();
                    } else {
                        storyViewerModal.hide();
                    }
                    return;
                }

                const segment = userStory.segments[currentStorySegmentIndex];

                storyViewerAvatar.src = userStory.user_avatar;
                storyViewerUserName.textContent = userStory.user_name;
                storyViewerTime.textContent = "Just now";

                if (segment.type === 'image') {
                    storyViewerContentImage.src = segment.content;
                    storyViewerContentImage.style.display = 'block';
                } else if (segment.type === 'video') {
                    console.warn("Video playback not yet implemented.");
                    storyViewerContentImage.src = '';
                    storyViewerContentImage.style.display = 'none';
                }

                // Determine if the current story belongs to the logged-in viewer
                // Use 'owner_user_id' if available, otherwise fall back to 'id' if 'id' consistently holds user ID for others
                // Note: For 'create' story, owner_user_id will be undefined or null, and id is 'create'.
                const isMyOwnStory = (userStory.owner_user_id === VIEWER_ID);

                // Record view for other users' stories (not 'create' or your own story)
                if (!isMyOwnStory && userStory.id !== 'create' && VIEWER_ID) {
                    console.log(`Recording view for story by ${userStory.user_name} (ID: ${userStory.id})`);
                    recordStoryView(userStory.id, userStory.segments[currentStorySegmentIndex].story_ID || null, VIEWER_ID);
                }

                // Show/hide footers based on whether it's 'My Day' (i.e., your own story)
                if (isMyOwnStory) {
                    defaultStoryFooter.classList.add('d-none');
                    myDayStoryFooter.classList.remove('d-none');
                    myDayStoryFooter.classList.add('d-flex');

                    myDayViewerProfiles.innerHTML = '';
                    if (userStory.viewers && userStory.viewers.length > 0) {
                        myDayViewersCount.textContent = `${userStory.viewers.length} viewers`;
                        userStory.viewers.forEach(viewer => {
                            const viewerImg = document.createElement('img');
                            viewerImg.src = "../assests/images/post_images/" + viewer.user_avatar;
                            viewerImg.alt = 'Viewer Avatar';
                            viewerImg.classList.add('viewer-avatar-thumbnail'); // Add a class for styling
                            myDayViewerProfiles.appendChild(viewerImg);
                        });
                    } else {
                        myDayViewersCount.textContent = 'No viewers yet';
                    }

                } else { // This handles other users' stories AND the 'create' story
                    defaultStoryFooter.classList.remove('d-none');
                    myDayStoryFooter.classList.add('d-none');
                    myDayStoryFooter.classList.remove('d-flex');
                    myDayViewerProfiles.innerHTML = '';
                }

                if (currentStorySegmentIndex === userStory.segments.length - 1) {
                    if (userStory.has_new) {
                        console.log(`Story ID: ${userStory.id} has reached its LAST segment. Removing has-new-story class now.`);
                        const storyElement = document.querySelector(`.story[data-story-id="${userStory.id}"]`);
                        if (storyElement) {
                            storyElement.classList.remove('has-new-story');
                            if (userStory.id === 'create') {
                                const plusBtn = storyElement.querySelector('.create-story-plus-btn');
                                if (plusBtn) {
                                    plusBtn.classList.remove('has-new-story');
                                }
                            } else {
                                const avatarOverlay = storyElement.querySelector('.user-avatar-overlay');
                                if (avatarOverlay) {
                                    avatarOverlay.classList.remove('has-new-story');
                                }
                            }
                        }
                        userStory.has_new = false; // Update the JS data to reflect no new stories
                    }
                }
                // This is where startProgressBar is called for the current segment
                startProgressBar();
            }

            // --- Add event listeners for reactions ---
            const reactionIcons = document.querySelectorAll('.reaction-icon');
            reactionIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const userStory = allStoriesData[currentStoryUserIndex];
                    const currentSegment = userStory.segments[currentStorySegmentIndex];
                    const isMyOwnStory = (userStory.owner_user_id === VIEWER_ID);

                    // Only record reactions if it's NOT your own story, NOT 'create', and VIEWER_ID is set
                    if (!isMyOwnStory && userStory.id !== 'create' && VIEWER_ID) {
                        const storyOwnerId = userStory.id; // This 'id' should be the actual user ID of the story owner
                        const storyId = currentSegment.story_ID;
                        const reactionType = icon.alt;

                        if (storyId) {
                            recordStoryView(storyOwnerId, storyId, VIEWER_ID, reactionType);
                        } else {
                            console.warn("Story ID missing for reaction. Cannot record reaction.");
                        }
                    } else {
                        console.warn("Cannot record reaction: Not applicable for own story or create story, or Viewer ID invalid.");
                    }
                });
            });

            // --- Navigation functions ---
            function nextSegment() {
                clearTimeout(segmentTimeout);
                cancelAnimationFrame(progressInterval);
                pausedProgress = 0; // Reset pausedProgress on manual navigation

                const userStory = allStoriesData[currentStoryUserIndex];
                if (!userStory) {
                    storyViewerModal.hide();
                    return;
                }

                currentStorySegmentIndex++;

                if (currentStorySegmentIndex < userStory.segments.length) {
                    showStorySegment();
                } else {
                    currentStoryUserIndex++;
                    currentStorySegmentIndex = 0;

                    if (currentStoryUserIndex < allStoriesData.length) {
                        renderProgressBars();
                        showStorySegment();
                    } else {
                        storyViewerModal.hide();
                    }
                }
            }

            function prevSegment() {
                clearTimeout(segmentTimeout);
                cancelAnimationFrame(progressInterval);
                pausedProgress = 0; // Reset pausedProgress on manual navigation

                if (currentStorySegmentIndex > 0) {
                    currentStorySegmentIndex--;
                    showStorySegment();
                } else {
                    currentStoryUserIndex--;
                    if (currentStoryUserIndex >= 0) {
                        const prevUserStory = allStoriesData[currentStoryUserIndex];
                        currentStorySegmentIndex = prevUserStory.segments.length - 1;
                        renderProgressBars();
                        showStorySegment();
                    } else {
                        storyViewerModal.hide();
                    }
                }
            }

            function showStory(storyId) {
                const foundIndex = allStoriesData.findIndex(story => story.id == storyId);

                if (foundIndex === -1) {
                    console.error("Story user not found:", storyId);
                    return;
                }
                if (storyId == 'create') {
                    return;
                }

                currentStoryUserIndex = foundIndex;
                currentStorySegmentIndex = 0;

                renderProgressBars();
                // showStorySegment() is called by the modal 'shown.bs.modal' event,
                // which ensures elements are ready.
                storyViewerModal.show();
            }

            // Initial render call after all data and functions are defined
            renderStoryCards();

            // --- Event Listeners ---
            if (storiesContainer) {
                storiesContainer.addEventListener('click', function(event) {
                    const clickedStory = event.target.closest('.story');
                    if (clickedStory) {
                        const storyId = clickedStory.dataset.storyId;
                        showStory(storyId);
                    }
                });
            }

            storyViewerPrev.addEventListener('click', prevSegment);
            storyViewerNext.addEventListener('click', nextSegment);

            const myModalEl = document.getElementById('storyViewerModal');
            if (myModalEl) {
                myModalEl.addEventListener('shown.bs.modal', event => {
                    console.log("Story modal is fully shown. Starting/resuming segment.");
                    // Ensure any previous timeouts/animations are cleared before starting new segment
                    clearTimeout(segmentTimeout);
                    cancelAnimationFrame(progressInterval);
                    pausedProgress = 0; // Fresh start for segment when modal is shown

                    if (allStoriesData[currentStoryUserIndex] && allStoriesData[currentStoryUserIndex].segments.length > 0) {
                        showStorySegment(); // This will call startProgressBar
                    } else {
                        storyViewerModal.hide();
                    }
                });

                myModalEl.addEventListener('hidden.bs.modal', event => {
                    console.log("Story modal hidden. Clearing timers.");
                    clearTimeout(segmentTimeout);
                    cancelAnimationFrame(progressInterval);
                    pausedProgress = 0; // Reset pausedProgress when modal is fully closed
                    storyDetailsPullUp.classList.remove('show'); // Ensure pull-up is hidden
                });
            }

            if (rightArrow && storiesContainer) {
                rightArrow.addEventListener('click', function() {
                    storiesContainer.scrollBy({
                        left: 300,
                        behavior: 'smooth'
                    });
                });
            }

            // --- Pull-Up Modal Logic ---
            myDayPullUpHandle.addEventListener('click', function() {
                const userStory = allStoriesData[currentStoryUserIndex];
                const isMyOwnStory = (userStory.owner_user_id === VIEWER_ID);

                // Only show pull-up if it's your own story
                if (isMyOwnStory) {
                    cancelAnimationFrame(progressInterval); // Pause the progress bar animation
                    // Calculate pausedProgress based on current fill width for accurate resume
                    const currentProgressBarFill = storyProgressBarsContainer.querySelector(`.story-progress-bar[data-segment-index="${currentStorySegmentIndex}"] .progress-fill`);
                    if (currentProgressBarFill) {
                        const currentWidth = parseFloat(currentProgressBarFill.style.width);
                        const duration = userStory.segments[currentStorySegmentIndex].duration || 5000;
                        pausedProgress = (currentWidth / 100) * duration;
                    }

                    storyDetailsNumViewers.textContent = `${userStory.viewers.length} viewers`;
                    storyDetailsViewerList.innerHTML = '';

                    userStory.viewers.forEach(viewer => {
                        const viewerItem = document.createElement('div');
                        viewerItem.classList.add('viewer-item');

                        const viewerImg = document.createElement('img');
                        viewerImg.src = "../assests/images/post_images/" + viewer.user_avatar;
                        viewerImg.alt = viewer.user_name;
                        viewerItem.appendChild(viewerImg);

                        const viewerInfo = document.createElement('div');
                        viewerInfo.classList.add('viewer-info');
                        const viewerName = document.createElement('div');
                        viewerName.classList.add('viewer-name');
                        viewerName.textContent = viewer.user_name;
                        const viewerStatus = document.createElement('div');
                        viewerStatus.classList.add('viewer-status');
                        viewerStatus.textContent = viewer.status;
                        viewerInfo.appendChild(viewerName);
                        viewerItem.appendChild(viewerInfo);

                        const viewerReaction = document.createElement('img'); // Create an <img> element
                        viewerReaction.classList.add('viewer-reaction-emoji');


                        viewerReaction.src = "../assests/images/icon/" + (viewer.reaction_emoji || 'default_reaction') + ".png"; // Provide a fallback if viewer.reaction_emoji is empty

                        viewerItem.appendChild(viewerReaction);
                        storyDetailsViewerList.appendChild(viewerItem);
                    });

                    storyDetailsPullUp.classList.add('show');
                } else {
                    storyDetailsPullUp.classList.remove('show');
                }
            });

            closeStoryDetailsPullUp.addEventListener('click', function() {
                storyDetailsPullUp.classList.remove('show');
                // Resume progress bar only if it was paused and the modal is still open
                const modalElement = document.getElementById('storyViewerModal');
                const isModalOpen = modalElement ? modalElement.classList.contains('show') : false;

                if (pausedProgress > 0 && isModalOpen) {
                    startProgressBar(); // Resume the progress bar from where it left off
                }
            });
        });
    </script>
</body>

</html>