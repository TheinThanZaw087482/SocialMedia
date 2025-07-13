// --- Global Variables ---
let usersStoriesData = [];
let currentUserIndex = 0; // Index of the current user being viewed
let currentStoryInUserCollectionIndex = 0; // Index of the current story within that user's collection
let storyTimer; // Timer for automatic story progression
const STORY_DURATION = 5000; // 5 seconds per story for automatic progression

// Get modal elements and initialize Bootstrap Modal
const storyViewerModalElement = document.getElementById('storyViewerModal');
const storyViewerModal = storyViewerModalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal
    ? new bootstrap.Modal(storyViewerModalElement)
    : { // Fallback if Bootstrap isn't loaded or modal element is missing
        show: () => storyViewerModalElement && (storyViewerModalElement.style.display = 'flex'),
        hide: () => storyViewerModalElement && (storyViewerModalElement.style.display = 'none'),
        element: storyViewerModalElement
    };

const modalStoryContentViewer = document.getElementById('story-content-modal-viewer');
const modalProgressBarContainer = document.getElementById('modal-story-progress');
const modalUserName = document.getElementById('modal-user-name');
const modalUserAvatar = document.getElementById('modal-user-avatar');
const modalStoryTime = document.getElementById('modal-story-time');
const prevStoryBtn = document.getElementById('prevStory');
const nextStoryBtn = document.getElementById('nextStory');

// Get reaction buttons (these are defined in your HTML for the modal footer)
const reactionButtons = document.querySelectorAll('.story_reaction_btn');


// --- Functions ---

// Function to send a request to the backend to record a story view and/or reaction
async function recordStoryView(storyOwnerId, storyId, viewerId, reactionType = null) {
    if (!viewerId) {
        console.warn("Viewer ID is not set. Cannot record view/reaction.");
        return;
    }
    // storyId can be null for a general collection view, but must be provided for reactions
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
            console.log(`Operation successful: ${result.message}`);
        } else {
            console.error('Operation failed:', result.message);
        }
    } catch (error) {
        console.error('Error sending view/reaction record request:', error);
    }
}


// Function to create progress segments based on the current user's stories
function createProgressSegmentsForCurrentUser() {
    if (!modalProgressBarContainer) return;
    modalProgressBarContainer.innerHTML = '';
    const currentUser = usersStoriesData[currentUserIndex];
    if (currentUser && currentUser.stories) {
        currentUser.stories.forEach((story, i) => {
            const segment = document.createElement('div');
            segment.classList.add('progress-segment');
            segment.id = `modal-progress-segment-${i}`;
            segment.innerHTML = '<div class="progress-bar-inner"></div>';
            modalProgressBarContainer.appendChild(segment);
        });
    }
}

// Function to update progress bar for the current story within the user's collection
function updateProgressBar() {
    clearInterval(storyTimer);

    const progressSegments = document.querySelectorAll('#modal-story-progress .progress-segment');

    progressSegments.forEach((segment, i) => {
        const progressBarInner = segment.querySelector('.progress-bar-inner');
        if (progressBarInner) {
            progressBarInner.style.transition = 'none';
            if (i < currentStoryInUserCollectionIndex) {
                progressBarInner.style.width = '100%';
            } else {
                progressBarInner.style.width = '0%';
            }
            void progressBarInner.offsetWidth;
        }
    });

    if (currentStoryInUserCollectionIndex < progressSegments.length) {
        const currentProgressBarInner = progressSegments[currentStoryInUserCollectionIndex].querySelector('.progress-bar-inner');

        if (currentProgressBarInner) {
            setTimeout(() => {
                currentProgressBarInner.style.transition = `width ${STORY_DURATION / 1000}s linear`;
                currentProgressBarInner.style.width = '100%';
            }, 0);

            storyTimer = setTimeout(() => {
                moveToNextStoryOrUser();
            }, STORY_DURATION);
        }
    } else {
        storyViewerModal.hide();
    }
}

// Function to show a specific story within the modal for the current user
function showStoryInModal(storyIndex) {
    const currentUser = usersStoriesData[currentUserIndex];
    if (!currentUser || !currentUser.stories || currentUser.stories.length === 0) {
        storyViewerModal.hide();
        return;
    }

    // --- Navigation Logic for Users and Stories ---
    if (storyIndex < 0) {
        if (currentUserIndex > 0) {
            currentUserIndex--;
            currentStoryInUserCollectionIndex = usersStoriesData[currentUserIndex].stories.length - 1;
            createProgressSegmentsForCurrentUser();
            showStoryInModal(currentStoryInUserCollectionIndex);
            return;
        } else {
            // Loop back to the last user's last story
            currentUserIndex = usersStoriesData.length - 1;
            currentStoryInUserCollectionIndex = usersStoriesData[currentUserIndex].stories.length - 1;
            createProgressSegmentsForCurrentUser();
            showStoryInModal(currentStoryInUserCollectionIndex);
            return;
        }
    }
    if (storyIndex >= currentUser.stories.length) {
        if (currentUserIndex < usersStoriesData.length - 1) {
            currentUserIndex++;
            currentStoryInUserCollectionIndex = 0;
            createProgressSegmentsForCurrentUser();
            showStoryInModal(currentStoryInUserCollectionIndex);
            return;
        } else {
            // Loop back to the first user's first story
            currentUserIndex = 0;
            currentStoryInUserCollectionIndex = 0;
            createProgressSegmentsForCurrentUser();
            showStoryInModal(currentStoryInUserCollectionIndex);
            return;
        }
    }
    // --- End Navigation Logic ---


    currentStoryInUserCollectionIndex = storyIndex;
    const currentStory = currentUser.stories[currentStoryInUserCollectionIndex];

    // Update modal header info based on the current user AND current story
    if (modalUserName) modalUserName.textContent = currentUser.userName;
    if (modalUserAvatar) modalUserAvatar.src = currentUser.userAvatar;
    if (modalStoryTime) modalStoryTime.textContent = currentStory.time; // Time from the current story object

    // Clear previous story content and load new
    if (modalStoryContentViewer) modalStoryContentViewer.innerHTML = '';

    const storyItem = document.createElement('div');
    storyItem.classList.add('story-item', 'active');

    const contentType = currentStory.content && currentStory.content.type ? currentStory.content.type : 'image';
    const contentSrc = currentStory.content && currentStory.content.src ? currentStory.content.src : '';

    if (contentType === 'image') {
        const img = document.createElement('img');
        img.src = contentSrc;
        img.alt = `Story from ${currentUser.userName}`;
        storyItem.appendChild(img);
    } else if (contentType === 'video') {
        const video = document.createElement('video');
        video.controls = true;
        video.autoplay = true;
        video.loop = true;
        video.muted = true;
        video.classList.add('w-100', 'h-100', 'object-contain');
        const source = document.createElement('source');
        source.src = contentSrc;
        source.type = 'video/mp4';
        video.appendChild(source);
        // Do not call play() directly here for video, autoplay handles it on load.
        // If autoplay isn't reliable, you might need a video.oncanplaythrough = () => video.play();
    }

    if (modalStoryContentViewer) {
        modalStoryContentViewer.appendChild(storyItem);
    }

    // Record Individual Story as Viewed when it is displayed
    // Only record if currentStory and its ID exist, and viewer ID is available
    if (currentStory && currentStory.id && currentUser && currentUser.userId && CURRENT_LOGGED_IN_USER_ID) {
        // Record a view (with reactionType = null, as it's just a view)
        recordStoryView(currentUser.userId, currentStory.id, CURRENT_LOGGED_IN_USER_ID, null);
    } else {
        console.warn("Could not record specific story view: Missing user, story, or viewer ID.", { currentUser, currentStory, CURRENT_LOGGED_IN_USER_ID });
    }


    requestAnimationFrame(() => {
        updateProgressBar();
    });
}

// Function to move to the next story within the current user's collection or to the next user
function moveToNextStoryOrUser() {
    const currentUser = usersStoriesData[currentUserIndex];

    if (currentStoryInUserCollectionIndex + 1 < currentUser.stories.length) {
        showStoryInModal(currentStoryInUserCollectionIndex + 1);
    } else if (currentUserIndex + 1 < usersStoriesData.length) {
        currentUserIndex++;
        currentStoryInUserCollectionIndex = 0;
        createProgressSegmentsForCurrentUser();
        showStoryInModal(currentStoryInUserCollectionIndex);
    } else {
        currentUserIndex = 0;
        currentStoryInUserCollectionIndex = 0;
        createProgressSegmentsForCurrentUser();
        showStoryInModal(currentStoryInUserCollectionIndex);
    }
}

// Function to create and append story cards to the DOM
function createStoryCards() {
    const storiesContainer = document.querySelector('.stories-container');
    if (!storiesContainer) return;
    storiesContainer.innerHTML = '';

    const yourStoryCard = document.createElement('div');
    yourStoryCard.classList.add('story-card', 'add-story');
    yourStoryCard.innerHTML = `
        <div class="story-thumbnail">
            <img src="https://placehold.co/120x150/f0f2f5/e0e0e0?text=Upload" alt="Your Story Thumbnail">
            <div class="add-icon"><i class="fas fa-plus-circle"></i></div>
        </div>
        <div class="user-name">Your Story</div>
    `;
    yourStoryCard.addEventListener('click', addYourStory);
    storiesContainer.appendChild(yourStoryCard);

    usersStoriesData.forEach((user, index) => {
        const card = document.createElement('div');
        card.classList.add('story-card');
        card.dataset.userIndex = index;

        const firstStoryContentSrc = user.stories && user.stories[0]?.content?.src
            ? user.stories[0].content.src
            : 'https://placehold.co/120x180/ccc/999?text=No+Story';

        card.innerHTML = `
    <div class="story-thumbnail" style="background-image: url('${firstStoryContentSrc}');"></div>
    <div class="user-avatar-wrapper">
        <img src="${user.userAvatar}" alt="${user.userName}'s avatar" class="user-avatar">
    </div>
    <div class="user-name">${user.userName}</div>
`;
        storiesContainer.appendChild(card);
    });

    document.querySelectorAll('.story-card:not(.add-story)').forEach(card => {
        card.addEventListener('click', function () {
            currentUserIndex = parseInt(this.dataset.userIndex);
            currentStoryInUserCollectionIndex = 0;

            createProgressSegmentsForCurrentUser();
            storyViewerModal.show(); // Show the Bootstrap modal
            // showStoryInModal is called *after* modal is shown to ensure it's visible for view recording
            showStoryInModal(currentStoryInUserCollectionIndex);
        });
    });
}

function addYourStory() {
    console.log("Add Your Story clicked!");
    const messageBox = document.createElement('div');
    messageBox.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #333;
        color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        z-index: 9999;
        font-size: 1rem;
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    `;
    messageBox.textContent = "Feature to add your story will be implemented here!";
    document.body.appendChild(messageBox);

    setTimeout(() => { messageBox.style.opacity = '1'; }, 10);
    setTimeout(() => {
        messageBox.style.opacity = '0';
        messageBox.addEventListener('transitionend', () => messageBox.remove());
    }, 2000);
}


// --- Event Listeners ---

// Navigation buttons inside the modal
if (prevStoryBtn) {
    prevStoryBtn.addEventListener('click', () => {
        clearInterval(storyTimer);
        showStoryInModal(currentStoryInUserCollectionIndex - 1);
    });
}

if (nextStoryBtn) {
    nextStoryBtn.addEventListener('click', () => {
        clearInterval(storyTimer);
        showStoryInModal(currentStoryInUserCollectionIndex + 1);
    });
}

// Modal events for pausing/resuming stories and marking viewed
if (storyViewerModalElement) {
    storyViewerModalElement.addEventListener('hidden.bs.modal', function () {
        clearInterval(storyTimer); // Stop timer when modal is closed
    });

    storyViewerModalElement.addEventListener('shown.bs.modal', function (event) {
        // Get the card that triggered the modal to mark it as 'viewed'
        const clickedCard = document.querySelector(`.story-card[data-user-index="${currentUserIndex}"]`);
        if (clickedCard) {
            clickedCard.classList.add('viewed'); // Add 'viewed' class for styling
        }
    });
}

// --- Add Event Listeners for Reaction Buttons ---
// This part needs to be placed after the reactionButtons are defined globally
// and after the DOM is fully loaded, so the buttons exist.
if (reactionButtons.length > 0) {
    reactionButtons.forEach(button => {
        button.addEventListener('click', function () {
            const reactionType = this.title; // Get the 'title' attribute as the reaction type

            const currentUser = usersStoriesData[currentUserIndex];
            const currentStory = currentUser.stories[currentStoryInUserCollectionIndex];

            // Ensure all necessary data is available before attempting to record
            if (currentUser && currentStory && currentUser.userId && currentStory.id && CURRENT_LOGGED_IN_USER_ID) {
                // Call recordStoryView with the reactionType
                recordStoryView(currentUser.userId, currentStory.id, CURRENT_LOGGED_IN_USER_ID, reactionType);
                console.log(`Reacted with ${reactionType} to story ${currentStory.id}`);
                // Optional: You could add visual feedback here, like a temporary checkmark on the button
            } else {
                console.warn("Could not record reaction: Missing user, story, or viewer ID for reaction.", { currentUser, currentStory, CURRENT_LOGGED_IN_USER_ID });
            }
        });
    });
}


// --- Initial Data Fetch on DOM Load ---
document.addEventListener('DOMContentLoaded', () => {
    fetch('../process/get_stories.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            usersStoriesData = data;
            createStoryCards();
        })
        .catch(error => {
            console.error('Error fetching story data:', error);
            const storiesContainer = document.querySelector('.stories-container');
            if (storiesContainer) {
                storiesContainer.innerHTML = '<p style="color: red; text-align: center;">Failed to load stories. Please check server or network.</p>';
            }
        });
});

/*Suiko */
document.addEventListener('DOMContentLoaded', () => {
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const pickerArea = document.getElementById('pickerArea');
    const storyArea = document.getElementById('storyArea');
    const storyUploadForm = document.getElementById('storyUploadForm');

    let selectedFile = null;

    // === Image Input Change ===
    if (imageInput && imagePreview && removeImageBtn && pickerArea) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];

            if (file) {
                selectedFile = file;

                const reader = new FileReader();
                reader.onload = function (e) {
                    console.log("Image loaded:", e.target.result); // For debugging
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block'; // Show the image
                    removeImageBtn.style.display = 'block'; // Show remove button
                    pickerArea.style.display = 'none'; // Hide picker UI
                    if (storyArea) storyArea.style.backgroundImage = 'none'; // Remove blue gradient
                };
                reader.readAsDataURL(file);
            } else {
                // Reset if no file selected
                selectedFile = null;
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                removeImageBtn.style.display = 'none';
                pickerArea.style.display = 'flex';
                if (storyArea)
                    storyArea.style.backgroundImage = 'linear-gradient(60deg, rgb(77, 148, 255), rgb(35, 99, 222))';
            }
        });
    }

    // === Remove Image Button ===
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', () => {
            selectedFile = null;
            imageInput.value = '';
            imagePreview.src = '';
            imagePreview.style.display = 'none';
            removeImageBtn.style.display = 'none';
            pickerArea.style.display = 'flex';
            if (storyArea)
                storyArea.style.backgroundImage = 'linear-gradient(60deg, rgb(77, 148, 255), rgb(35, 99, 222))';
        });
    }

    // === Form Submission ===
    if (storyUploadForm) {
        storyUploadForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const formData = new FormData();
            if (selectedFile) {
                formData.append('storyImage', selectedFile);
            } else {
                alert("Please select an image to upload.");
                return;
            }

            try {
                const response = await fetch("../process/post_story.php", {
                    method: 'POST',
                    body: formData
                });

                const data = await response.text();
                if (data.trim() === 'success') {
                    alert("Successfully uploaded a story");
                    removeImageBtn.click(); // Reset
                    storyUploadForm.reset();

                    const myModalEl = document.getElementById('addMyDay');
                    if (myModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal.getInstance(myModalEl)) {
                        bootstrap.Modal.getInstance(myModalEl).hide();
                    }

                    window.location.href = "../pages/Dashboard.php";
                } else {
                    alert("Upload failed: " + data);
                }
            } catch (err) {
                console.error(err);
                alert("An error occurred during upload.");
            }
        });
    }

    // === Reset on Modal Close ===
    const addMyDayModal = document.getElementById('addMyDay');
    if (addMyDayModal && typeof bootstrap !== 'undefined') {
        addMyDayModal.addEventListener('hidden.bs.modal', () => {
            if (removeImageBtn) removeImageBtn.click();
            if (storyUploadForm) storyUploadForm.reset();
        });
    }
});
