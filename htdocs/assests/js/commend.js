document.addEventListener('DOMContentLoaded', function() {
    const currentUserProfilePic = '<?php echo $currentUserProfilePic; ?>';
    const currentUserName = '<?php echo $currentUserName; ?>';

    const commentsSection = document.querySelector('.comments-section');

    /**
     * Adds a new reply dynamically to the DOM, ensuring correct positioning within its thread.
     * This function can now handle arbitrary nesting levels.
     * @param {HTMLElement} parentCommentItem The HTML element of the comment being replied to.
     * @param {string} replyText The content of the new reply.
     * @param {string} authorName The name of the person making the reply.
     * @param {string} authorProfilePic The URL of the person making the reply.
     */
    function addReply(parentCommentItem, replyText, authorName, authorProfilePic) {
        const newReply = document.createElement('div');
        newReply.classList.add('comment-item'); // No 'reply-comment' class needed here, structure handles it

        let displayedReplyText = replyText;
        if (replyText.startsWith('@')) {
            const parts = replyText.split(' ');
            if (parts.length > 0) {
                const mentionedUsername = parts[0].substring(1);
                // Note: The original logic here specifically checked if mentionedUsername === parentAuthor
                // You might want to remove this specific check if you want to allow tagging any user,
                // or ensure parentAuthor is correctly fetched for any parent level.
                // For simplicity, we'll keep the tagging span for any @mention.
                displayedReplyText = `<span class="tag-user">${mentionedUsername}</span> ${parts.slice(1).join(' ')}`;
            }
        }

        newReply.innerHTML = `
            <img src="${authorProfilePic}" alt="${authorName} Profile Picture" class="comment-profile-pic">
            <div class="comment-content-bubble">
                <div class="comment-author">${authorName}</div>
                <div class="comment-text">${displayedReplyText}</div>
            </div>
            <div class="comment-actions">
                <span class="comment-time">Just now</span>
                <span class="comment-action-link like-button">Like</span>
                <span class="comment-action-link reply-button">Reply</span>
                <span class="comment-reactions">0</span>
            </div>
            <div class="reply-input-container" style="display: none;">
                <img src="${currentUserProfilePic}" alt="Your Profile Picture" class="profile-pic">
                <input type="text" class="form-control" placeholder="Write a reply...">
                <button class="send-btn" type="button">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        `;

        // Find or create the replies-container *within* the parentCommentItem
        let repliesContainer = parentCommentItem.querySelector('.replies-container');

        if (!repliesContainer) {
            repliesContainer = document.createElement('div');
            repliesContainer.classList.add('replies-container');
            // Insert the new replies container right after the parent comment's actions/reply input
            parentCommentItem.appendChild(repliesContainer);
        }

        // Append the new reply to this specific repliesContainer
        repliesContainer.appendChild(newReply);
    }

    /**
     * Hides all currently visible reply input containers and clears their text.
     */
    function hideAllReplyInputs() {
        document.querySelectorAll('.reply-input-container').forEach(container => {
            container.style.display = 'none';
            container.querySelector('input[type="text"]').value = '';
        });
    }

    /**
     * Sends a new reply to the server and then, if successful, adds it dynamically to the DOM.
     * @param {string} parentID The ID of the parent comment or reply.
     * @param {string} replyText The content of the new reply.
     * @param {HTMLElement} parentCommentItem The HTML element of the comment being replied to.
     */
    function write_reply(parentID, replyText, parentCommentItem) {
        fetch('../process/write_reply.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'parent_id=' + encodeURIComponent(parentID) + '&content=' + encodeURIComponent(replyText)
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                // If the reply was successfully saved to the database, then add it to the DOM
                addReply(parentCommentItem, replyText, currentUserName, currentUserProfilePic);
                console.log('Reply posted successfully!'); // For debugging
                location.reload();
            } else {
                alert("Failed to reply: " + data);
            }
        })
        .catch(error => {
            console.error('Error posting reply:', error);
            alert("An error occurred while posting your reply.");
        });
    }

    // --- Event Delegation for Clicks ---
    commentsSection.addEventListener('click', function(event) {
        if (event.target.classList.contains('reply-button')) {
            const commentItem = event.target.closest('.comment-item');
            const replyInputContainer = commentItem.querySelector('.reply-input-container');
            const commentAuthor = commentItem.querySelector('.comment-author').textContent;
            const replyInputField = replyInputContainer.querySelector('input[type="text"]');

            if (replyInputContainer.style.display === 'flex') {
                hideAllReplyInputs();
            } else {
                hideAllReplyInputs();
                replyInputContainer.style.display = 'flex';
                replyInputField.value = `@${commentAuthor} `;
                replyInputField.focus();
            }
        } else if (event.target.classList.contains('like-button')) {
            const reactionsSpan = event.target.closest('.comment-actions').querySelector('.comment-reactions');
            let currentLikes = parseInt(reactionsSpan.textContent) || 0;
            if (event.target.textContent === 'Like') {
                reactionsSpan.textContent = `${currentLikes + 1}`;
                event.target.textContent = 'Liked';
                event.target.classList.add('liked');
            } else {
                reactionsSpan.textContent = `${currentLikes - 1}`;
                event.target.textContent = 'Like';
                event.target.classList.remove('liked');
            }
        } else if (event.target.closest('.send-btn')) { // Handle clicks on the send button
            const sendButton = event.target.closest('.send-btn');
            const replyInputContainer = sendButton.closest('.reply-input-container');
            const replyInputField = replyInputContainer.querySelector('input[type="text"]');
            const replyText = replyInputField.value.trim();
            const commentItem = replyInputField.closest('.comment-item');

            const parentID = commentItem.dataset.commentId; // Get the ID of the parent comment/reply

            if (replyText && commentItem && parentID) {
                write_reply(parentID, replyText, commentItem);
                replyInputField.value = '';
                replyInputContainer.style.display = 'none';
            } else if (replyText === "") {
                alert("Reply cannot be empty!");
            } else {
                console.error("Could not find parent comment ID or comment item.");
            }
        }
    });

    // --- Event Delegation for Keydowns (specifically Enter key in reply inputs) ---
    commentsSection.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && event.target.matches('.reply-input-container input[type="text"]')) {

            const replyInputField = event.target;
            const replyText = replyInputField.value.trim();
            const commentItem = replyInputField.closest('.comment-item');

            // Crucial: Get the parent ID from a data attribute on the comment item
            // You MUST ensure your PHP loop renders `data-comment-id` or `data-reply-id`
            // on each .comment-item div. For example: <div class="comment-item" data-comment-id="<?php echo $comment['commentID']; ?>">
            const parentID = commentItem.dataset.commentId;

            if (replyText && commentItem && parentID) {
                // Call the server-side reply function
                write_reply(parentID, replyText, commentItem);

                // Clear input and hide container AFTER sending to server
                replyInputField.value = '';
                replyInputField.closest('.reply-input-container').style.display = 'none';
            } else if (replyText === "") {
                alert("Reply cannot be empty!");
            } else {
                console.error("Could not find parent comment ID or comment item.");
            }
        }
    });

    // Initial setup
    hideAllReplyInputs();
});

// Your existing write_comment function (no changes needed here for the reply logic)
function write_comment(postID) {
    const content = document.getElementById("comment_input").value;

    fetch('../process/write_comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + encodeURIComponent(postID) + '&content=' + encodeURIComponent(content)
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            document.getElementById("comment_input").value = ""; 
            location.reload();
        } else {
            alert("Failed to comment: " + data);
        }
    })
    .catch(error => {
        console.error('Error writing comment:', error);
        alert("An error occurred while posting your comment.");
    });
}