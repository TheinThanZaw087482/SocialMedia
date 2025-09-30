// Switch Forms
const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});

// ---------------------------
// Login Validation
// ---------------------------
document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch('./process/login_process.php', {
            method: 'POST',
            body: formData
        });

        const contentType = res.headers.get("content-type");
        // Ensure content-type is JSON; otherwise, it's an unexpected server response
        if (!contentType || !contentType.includes("application/json")) {
            alert("Server returned an unexpected response. Please try again.");
            console.error("Invalid server response format. Expected JSON. Received:", contentType);
            return; // Stop execution
        }

        const data = await res.json();

        // Check if the response was NOT OK (e.g., HTTP status 4xx or 5xx)
        if (!res.ok) {
            // Prioritize specific error messages
            if (data.error?.email) {
                alert(data.error.email);
            } else if (data.error?.password) {
                alert(data.error.password);
            } else if (data.error?.approve) {
                alert(data.error.approve);
            }
            // Fallback for general messages or unhandled errors from PHP
            else if (data.message) {
                alert(data.message);
            } else {
                alert("An unexpected server error occurred. Please try again.");
            }
            console.error("Login Error (Server response not OK):", data);
            return; // Stop execution as there was an error
        }

        // If the response IS OK (HTTP status 200) but 'success' is false
        if (data.success) {
            window.location.href = './pages/Dashboard.php';
        } else {
            // This block is for when PHP returns 200 OK, but success: false (e.g., due to specific validation errors)
            if (data.error?.email) {
                alert(data.error.email);
            } else if (data.error?.password) {
                alert(data.error.password);
            } else if (data.error?.approve) {
                alert(data.error.approve);
            } else if (data.message) {
                alert(data.message);
            } else {
                alert("Login failed due to an unknown reason. Please try again.");
            }
            console.warn("Login Failed (Success false, but HTTP OK):", data);
        }

    } catch (error) {
        // This catch block is for network errors, parsing errors, or any other unexpected JavaScript errors
        alert("A network error occurred or the server is unreachable. Please check your internet connection or try again later.");
        console.error("Network or Client-side Error:", error);
    }
});