

document.addEventListener('DOMContentLoaded', function () {
    // JavaScript for the provided dropdown
    const dropdownButton = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    // Add a click event listener to the button
    dropdownButton.addEventListener('click', function () {
        // Toggle the 'show' class on the dropdown menu
        dropdownMenu.classList.toggle('show');
    });

    // Close the dropdown if the user clicks outside of it
    window.addEventListener('click', function (event) {
        // Check if the click was outside the dropdown container
        if (!event.target.closest('.dropdown')) {
            // If the dropdown menu is currently shown, hide it
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        }
    });

    // New JavaScript for Post Modal
    const postInputContainer = document.querySelector('.post-input-container');
    const postModalOverlay = document.querySelector('.post-modal-overlay');
    const closeModalButton = document.querySelector('.close-modal');

    // Show modal when post input area is clicked
    postInputContainer.addEventListener('click', function () {
        postModalOverlay.style.display = 'flex'; // Use flex to center the modal
    });

    // Hide modal when close button is clicked
    closeModalButton.addEventListener('click', function () {
        postModalOverlay.style.display = 'none';
    });

    // Hide modal when clicking outside the modal content
    postModalOverlay.addEventListener('click', function (event) {
        if (event.target === postModalOverlay) { // Check if the click was directly on the overlay
            postModalOverlay.style.display = 'none';
        }
    });

    // Note: The JavaScript for the settings dropdown was removed
    // as there were no corresponding HTML elements in the provided snippet.
});

document.addEventListener('DOMContentLoaded', function () {
    // Existing JavaScript for settings dropdown
    const settingsIcon = document.querySelector('.settings-icon');
    const settingsDropdownMenu = document.querySelector('.settings-dropdown-menu');

    // Toggle dropdown visibility on icon click
    settingsIcon.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default link behavior
        // Toggle the display property
        if (settingsDropdownMenu.style.display === 'block') {
            settingsDropdownMenu.style.display = 'none';
        } else {
            settingsDropdownMenu.style.display = 'block';
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        // Check if the click was outside the dropdown menu AND outside the settings icon
        if (!settingsDropdownMenu.contains(event.target) && !settingsIcon.contains(event.target)) {
            settingsDropdownMenu.style.display = 'none';
        }
    });

    // Optional: Close dropdown when clicking on a dropdown item
    const dropdownItems = settingsDropdownMenu.querySelectorAll('.dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('click', function () {
            // You might want to keep the menu open for some items,
            // or navigate to a new page. If you just want to close it, uncomment below:
            // settingsDropdownMenu.style.display = 'none';
        });
    });

    // New JavaScript for Post Modal
    const postInputContainer = document.querySelector('.post-input-container');
    const postModalOverlay = document.querySelector('.post-modal-overlay');
    const closeModalButton = document.querySelector('.close-modal');

    // Show modal when post input area is clicked
    postInputContainer.addEventListener('click', function () {
        postModalOverlay.style.display = 'flex'; // Use flex to center the modal
    });

    // Hide modal when close button is clicked
    closeModalButton.addEventListener('click', function () {
        postModalOverlay.style.display = 'none';
    });

    // Hide modal when clicking outside the modal content
    postModalOverlay.addEventListener('click', function (event) {
        if (event.target === postModalOverlay) { // Check if the click was directly on the overlay
            postModalOverlay.style.display = 'none';
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.reaction-img').forEach(img => {
        img.addEventListener('click', async () => {
            const reaction = img.getAttribute('data-reaction');
            const src = img.getAttribute('src');

            const parentGroup = img.closest('.group');
            const iconContainer = parentGroup.querySelector('.icon-container');
            iconContainer.innerHTML = `
            <img src="${src}" alt="${reaction}" style="width: 20px; height: 20px; display: block;" />
            <span class="reaction-text" style="color: gray;">${reaction}</span>
            
        `;

            const form = img.closest('form');
            const postID = form.querySelector('input[name="post_id"]').value;

            try {
                const res = await fetch('../includes/store_reaction.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        post_id: postID,
                        reaction: reaction
                    })
                });

                const data = await res.json();
                console.log('Reaction saved:', data);

                const summaryRes = await fetch(`../includes/get_reaction_summary.php?post_id=${postID}`);
                const summaryHTML = await summaryRes.text();

                const summarySpan = form.querySelector('.reaction-summary');
                if (summarySpan) {
                    summarySpan.innerHTML = summaryHTML;
                }

                const likesCountDiv = document.querySelector(`.likes-count[data-postid="${postID}"]`);
                if (likesCountDiv) {
                    const likesSpan = likesCountDiv.querySelector('span');
                    if (likesSpan) {
                        likesSpan.innerHTML = summaryHTML;
                    }
                }

            } catch (error) {
                console.error('Error saving reaction:', error);
            }
        });
    });
});

function readUrl(input) {
    const previewContainer = document.getElementById("preview-container");
    previewContainer.innerHTML = ""; // clear previous previews

    if (input.files && input.files.length > 0) {
        const fileCount = input.files.length;
        previewContainer.setAttribute('data-count', fileCount); // for styling

        Array.from(input.files).forEach(file => {
            if (!file.type.startsWith("image/")) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
}



function displayGallery(images) {
    const totalImages = images.length;
    const container = document.getElementById("preview-container");
    container.innerHTML = "";

    let html = "";

    if (totalImages >= 6) {
        html += '<div class="image-gallery six-images">';
        html += '<div class="top-row">';
        for (let i = 0; i < 2; i++) {
            html += `<img src="${images[i]}" alt="Image">`;
        }
        html += '</div><div class="bottom-row">';
        for (let i = 2; i < 6; i++) {
            html += '<div class="image-overlay">';
            html += `<img src="${images[i]}" alt="Image">`;
            if (i === 5 && totalImages > 6) {
                html += `<div class="overlay-text">+${totalImages - 6} more</div>`;
            }
            html += '</div>';
        }
        html += '</div></div>';
    } else if (totalImages === 5) {
        html += '<div class="image-gallery five-images">';
        html += '<div class="top-row">';
        for (let i = 0; i < 2; i++) {
            html += `<img src="${images[i]}" alt="Image">`;
        }
        html += '</div><div class="bottom-row">';
        for (let i = 2; i < 5; i++) {
            html += `<img src="${images[i]}" alt="Image">`;
        }
        html += '</div></div>';
    } else if (totalImages === 4) {
        html += '<div class="image-gallery four-images">';
        html += '<div class="top-row">';
        for (let i = 0; i < 2; i++) {
            html += `<img src="${images[i]}" alt="Image">`;
        }
        html += '</div><div class="bottom-row">';
        for (let i = 2; i < 4; i++) {
            html += `<img src="${images[i]}" alt="Image">`;
        }
        html += '</div></div>';
    } else if (totalImages === 3) {
        html += '<div class="image-gallery three-images">';
        html += `<img src="${images[0]}" alt="Image">`;
        html += '<div class="bottom-row">';
        html += `<img src="${images[1]}" alt="Image">`;
        html += `<img src="${images[2]}" alt="Image">`;
        html += '</div></div>';
    } else if (totalImages === 2) {
        html += '<div class="image-gallery two-images">';
        for (let i = 0; i < 2; i++) {
            html += `<img src="${images[i]}" alt="Image">`;
        }
        html += '</div>';
    } else if (totalImages === 1) {
        html += '<div class="image-gallery single-image">';
        html += `<img src="${images[0]}" alt="Image">`;
        html += '</div>';
    }

    container.innerHTML = html;
}


function choice_privacy(value) {
    document.getElementById("privacy-input").value = value;
    document.getElementById("selected").textContent = value.charAt(0).toUpperCase() + value.slice(1).replace('_', ' ');
}

document.addEventListener('DOMContentLoaded', function () {
    const likeCountButton = document.querySelector('.likes-count button');
    const reactionsModal = document.getElementById('reactionsModal_post123');
    const closeButton = reactionsModal.querySelector('.close-button');

    if (likeCountButton) {
        likeCountButton.addEventListener('click', function () {
            reactionsModal.style.display = 'block';
        });
    }

    if (closeButton) {
        closeButton.addEventListener('click', function () {
            reactionsModal.style.display = 'none';
        });
    }

    // Close the modal if the user clicks outside of it
    window.addEventListener('click', function (event) {
        if (event.target === reactionsModal) {
            reactionsModal.style.display = 'none';
        }
    });
});


const toggleButton = document.getElementById("selected");
const menu = toggleButton.nextElementSibling;

toggleButton.addEventListener("click", () => {
    menu.classList.toggle("show");
});

// Close dropdown if clicked outside
window.addEventListener("click", (e) => {
    if (!toggleButton.parentElement.contains(e.target)) {
        menu.classList.remove("show");
    }
});

// Handle selection
function choice_privacy(option) {
    toggleButton.textContent = option; // Update button text
    menu.classList.remove("show");     // Hide menu
    // Add any logic for what happens on privacy selection
    console.log("Privacy set to:", option);
}


function hidePost(postId) {
    fetch('../process/hide_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + encodeURIComponent(postId)
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                document.getElementById(postId).style.display = 'none';
                alert('The post is no longer visible to you.');
            } else {
                alert('Failed to hide post');
            }
        });
}

function unhidePost(hide_ID, post_ID) {
    fetch('../process/unhidepost.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + encodeURIComponent(hide_ID)
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                document.getElementById(post_ID).style.display = 'none';
                alert('You unhide this post.');
            } else {
                alert('Failed to unhide post');
            }
        });
}
function unsavepost(postID) {
    fetch('../process/unsavepost.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + encodeURIComponent(postID)
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                document.getElementById(postID).style.display = 'none';
                alert('You unsaved this post')
            } else {
                alert('Failed to unsave post');
            }
        });
}
function savepost($postID) {
    fetch('../process/savepost.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + encodeURIComponent($postID)

    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                alert("You saved this post")
            } else {
                alert("Fail to save post");
            }
        });


}
function ban_post(postId) { // Renamed parameter to postId for consistency
    // Optional: Add a confirmation dialog for better UX
    if (!confirm('Are you sure you want to ban this post?')) {
        return; // User cancelled the action
    }

    fetch('../process/ban_post.php', {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + encodeURIComponent(postId)
    })
    .then(response => {
        // Check if the network request was successful (HTTP status 200-299)
        if (!response.ok) {
            // If the server responded with an HTTP error status (e.g., 404, 500)
            // Try to parse JSON for more detailed error, but fallback to a general message
            return response.json().catch(() => {
                throw new Error(`HTTP error! Status: ${response.status}`);
            });
        }
        // Parse the JSON response from the PHP script
        return response.json();
    })
    .then(data => {
        // 'data' is now the JavaScript object returned by PHP's json_encode
        if (data.status === 'success' || data.status === 'info') { // Handle 'info' status as well
            alert(data.message); // Display the success/info message from the server
            
            // Optional: Update the UI to reflect the banned status
            const postElement = document.getElementById(`post-${postId}`); // Assuming post elements have IDs like 'post-123'
            if (postElement) {
                postElement.classList.add('banned-post'); // Add a class to style the banned post
                // Example: Change the ban button to an unban button or disable it
                const banButton = postElement.querySelector('.ban-button'); // Assuming a button with class 'ban-button'
                if (banButton) {
                    banButton.textContent = 'Unban Post';
                    banButton.onclick = function() { unban_post(postId); }; // If you have an unban function
                    // You might also want to change its class or style to indicate it's an unban button now
                    banButton.classList.remove('ban-button');
                    banButton.classList.add('unban-button');
                }
            }

        } else {
            // Display the error message from the server
            alert(data.message || 'Failed to ban post.');
            console.error('Server error:', data.message); // Log the detailed error
        }
    })
    .catch(error => {
        // Handle network errors or issues with parsing the JSON
        alert('An error occurred while communicating with the server. Please try again. ' + error.message);
        console.error('Fetch error:', error);
    });
}
function deletePost(postId) {
    if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        return; // User cancelled the deletion
    }

    fetch('../process/delete_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + encodeURIComponent(postId)
    })
    .then(response => {
        // Check if the response is OK (status in the 200s)
        if (!response.ok) {
            // If the server responded with an HTTP error status (e.g., 404, 500)
            // Try to parse JSON for more detailed error, but fallback to general message
            return response.json().catch(() => {
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        // If response is OK, always try to parse as JSON
        return response.json();
    })
    .then(data => {
        // 'data' will now be the parsed JSON object (e.g., {status: 'success', message: '...' })
        if (data.status === 'success') {
            // Assuming 'postId' is also the ID of the HTML element you want to remove
            const postElement = document.getElementById(postId);
            if (postElement) {
                postElement.style.display = 'none'; // Or postElement.remove();
            }
            alert(data.message || 'Post deleted successfully!'); // Use the message from the server
        } else {
            // Handle errors reported by the server (e.g., 'Post ID is missing.')
            alert(data.message || 'Failed to delete post.');
            console.error('Server error:', data.message); // Log the server's error message
        }
    })
    .catch(error => {
        // Handle network errors or errors thrown during response parsing
        alert('An error occurred while trying to delete the post. Please try again. ' + error.message);
        console.error('Fetch error:', error);
    });
}
function submitProfilePicForm() {
    const form = document.getElementById('profilePicForm');
    const fileInput = document.getElementById('profilePicInput');

    if (fileInput.files.length > 0) {
        // Optional: Preview image instantly
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('previewImage').src = e.target.result;
        };
        reader.readAsDataURL(fileInput.files[0]);

        form.submit();
    }
}
function submitCoverPhotoForm() {
    const form = document.getElementById('coverPhotoForm');
    const fileInput = document.getElementById('coverPhotoInput');

    if (fileInput.files.length > 0) {
        // Optional: Preview image instantly
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('coverImg').src = e.target.result;
        };
        reader.readAsDataURL(fileInput.files[0]);
        form.submit();
        // Submit the form to PHP
    }
}

let currentpostID = null;



function loadReactedUsers(postId, type) {
    const listContainer = document.getElementById('reactionGiversList');
    const spinner = document.getElementById('loadingSpinner');

    if (!listContainer || !spinner) {
        console.error('Modal elements not found!');
        return;
    }

    listContainer.innerHTML = '';
    spinner.style.display = 'block';

    // 🔁 First: Set PHP session postID
    fetch('../process/set_post_session.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `post_id=${postId}`
    });

    // 🔁 Then: Load reacted users
    fetch(`../process/specific_reacted_user.php?post_id=${currentpostID}&type=${type}`)
        .then(response => response.json())
        .then(data => {
            spinner.style.display = 'none';

            if (data.length === 0) {
                listContainer.innerHTML = "<li class='list-group-item'>No reactions yet.</li>";
                return;
            }

            data.forEach(user => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = `
                   <div class="reactioned-user">
                        <div class="profile-wrapper">
                            <img src="../assests/images/post_images/${user.ProfileimagePath}" alt="profile" class="profile-pic">
                            <img src="../assests/images/icon/${user.type}.png" alt="reaction" class="reaction_user_img">
                        </div>
                        <div class="user-name"><span>${user.name}</span></div>
                    </div>
                `;
                listContainer.appendChild(li);
            });
        })
        .catch(error => {
            spinner.style.display = 'none';
            console.error('Error loading reactions:', error);
        });

    fetch(`../process/get_reaction_data.php?post_id=${postId}`)
        .then(response => response.json())
        .then(data => {
            // console.log(data); // For debugging
            updateReactionTabs(data);
        })
        .catch(error => {
            console.error('Error loading reaction counts:', error);
        });
}


function setSessionAndLoad(postID, type) {
    currentpostID = postID;
    fetch('../process/set_post_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `postID=${postID}`
    })
        .then(() => {
            loadReactedUsers(postID, type);  // After setting session, load reactions
        })
        .catch(error => console.error('Error setting session:', error));
}
function updateReactionTabs(counts) {
    const reactionMap = {
        'Like': 'Like.png',
        'Love': 'love_react',
        'Haha': 'haa_react',
        'Wow': 'wow_react',
        'Angry': 'angry_react',
        'Sad': 'sad_react'
    };

    let total = 0;

    for (const [reaction, idOrImg] of Object.entries(reactionMap)) {
        const count = counts[reaction] || 0;
        total += count;

        let span = null;

        if (idOrImg.endsWith('.png')) {
            // Like (uses src matching)
            const imgs = document.querySelectorAll(`.reaction-tab img[src$="${idOrImg}"]`);
            imgs.forEach(img => {
                span = img.nextElementSibling;
                img.parentElement.style.display = count > 0 ? 'flex' : 'none';
                if (span) span.innerText = `${count}`;
            });
        } else {
            // Others (have unique IDs)
            const img = document.getElementById(idOrImg);
            if (img) {
                span = img.nextElementSibling;
                img.parentElement.style.display = count > 0 ? 'flex' : 'none';
                if (span) span.innerText = ` ${count}`;
            }
        }
    }

    // Set All tab count
    const allTabSpan = document.querySelector('.reaction-tab.active span');
    if (allTabSpan) {
        allTabSpan.innerText = `All ${total}`;
    }
}
function specific_reacted_user(type) {
    const listContainer = document.getElementById('reactionGiversList');
    const spinner = document.getElementById('loadingSpinner');

    if (!listContainer || !spinner) {
        console.error('Modal elements not found!');
        return;
    }

    listContainer.innerHTML = '';
    spinner.style.display = 'block';
    fetch(`../process/specific_reacted_user.php?post_id=${currentpostID}&type=${type}`)
        .then(response => response.json())
        .then(data => {
            spinner.style.display = 'none';

            if (data.length === 0) {
                listContainer.innerHTML = "<li class='list-group-item'>No reactions yet.</li>";
                return;
            }

            data.forEach(user => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = `
                   <div class="reactioned-user">
                        <div class="profile-wrapper">
                            <img src="../assests/images/post_images/${user.ProfileimagePath}" alt="profile" class="profile-pic">
                            <img src="../assests/images/icon/${user.type}.png" alt="reaction" class="reaction_user_img">
                        </div>
                        <div class="user-name"><span>${user.name}</span></div>
                    </div>
                `;
                listContainer.appendChild(li);
            });
        })


        .catch(error => {
            alert("error")
            spinner.style.display = 'none';
            console.error('Error loading reactions:', error);
        });
}

function choice_privacy(value) {
    document.getElementById("privacy-input").value = value;

    let displayText = '';
    if (value === 'public') displayText = '🌍 Public';
    else if (value === 'batch') displayText = '🎓 Batch';
    else if (value === 'only_me') displayText = '🔒 Only Me';
    
    document.getElementById("selected").innerHTML = displayText + ' <i class="fas fa-chevron-down"></i>';
}

function choice_privacy(value) {
    document.getElementById("privacy-input").value = value;
    document.getElementById("selected").textContent = value.charAt(0).toUpperCase() + value.slice(1).replace('_', ' ');
}