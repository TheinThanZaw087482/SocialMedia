const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');
const postButton = document.getElementById('uploadBtn');
const removeImageBtn = document.getElementById('removeImageBtn'); // Make sure this is defined
const storyContent = document.querySelector('.story-textarea'); // Get the textarea for content

let selectedFile = null; // To store the file object for upload

imageInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        selectedFile = file; // Store the selected file
        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            removeImageBtn.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        selectedFile = null; // Clear if no file is selected
    }
});

removeImageBtn.addEventListener('click', function () {
    imagePreview.src = '';
    imagePreview.style.display = 'none';
    removeImageBtn.style.display = 'none';
    imageInput.value = null; // Clear the file input
    selectedFile = null; // Clear the selected file
});

postButton.addEventListener('click', async function(event){ // Use async for await
    event.preventDefault(); // Prevent default form submission if this button is inside a <form>

    const formData = new FormData();
    formData.append('content', storyContent.value); // Get the value from the textarea

    if (selectedFile) {
        formData.append('storyImage', selectedFile); // Append the actual file
    }

    try {
        const response = await fetch("../process/post_story.php", {
            method: 'POST', // 'POST' must be a string
            // When sending FormData, DO NOT set Content-Type header manually.
            // The browser sets it automatically with the correct boundary.
            body: formData // Send FormData directly
        });

        const data = await response.text();

        if (data.trim() === 'success') {
            alert("Successfully uploaded a story");
            window.location.href = "../pages/Dashboard.php";
        } else if (data.trim() === 'error') {
            alert("Fail to upload a story");
        } else {
            alert("An unexpected error occurred: " + data); // Log server response for debugging
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred during upload. Please try again.');
    }
});