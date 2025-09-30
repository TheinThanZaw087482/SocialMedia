document.getElementById('register_form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('./process/register_process.php', {
            method: 'POST',
            body: formData
        });

        // Check if the response is OK before parsing JSON
        if (!response.ok) {
            // Handle HTTP errors (e.g., 500 server error)
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json(); // Parse the JSON response

        if (data.success) {
            window.location.href = "./pages/loading.php";
            form.reset();
        } else {
            // Display specific errors
            if (data.error?.email) {
                alert(data.error.email);
            }
            if (data.error?.id) {
                alert(data.error.id);
            }
            if (data.error?.approve) {
                
                window.location.href = "./pages/loading.php"; 
            }
            if (data.error?.message || data.message) {
                alert(data.error.message || data.message);
            }
        }

    } catch (error) {
        alert("Something went wrong. Please try again later.");
        console.error("Register Error:", error);
    }
});