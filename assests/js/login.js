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

    // Clear previous error messages
    document.getElementById('emailError').textContent = '';
    document.getElementById('passwordError').textContent = '';
    document.getElementById('loginMessage').textContent = '';

    try {
        const res = await fetch('./process/login_process.php', {
            method: 'POST',
            body: formData
        });

        const contentType = res.headers.get("content-type");
        if (!res.ok) throw new Error('Server error');

        let data = {};
        if (contentType && contentType.includes("application/json")) {
            data = await res.json();
        } else {
            throw new Error("Invalid server response format");
        }

        if (data.success) {
            window.location.href = './pages/Dashboard.php';
        } else {
            if (data.error?.email) {
                document.getElementById('emailError').textContent = data.error.email;
            }
            if (data.error?.password) {
                document.getElementById('passwordError').textContent = data.error.password;
            }
            if (data.error?.approve) {
                document.getElementById('loginMessage').textContent = data.error.approve;
            }
            if (data.message) {
                document.getElementById('loginMessage').textContent = data.message;
            }
        }

    } catch (error) {
        document.getElementById('loginMessage').textContent = '❌ Something went wrong. Please try again later.';
        console.error("Login Error:", error);
    }
});

// ---------------------------
// Register Validation
// ---------------------------
document.getElementById('register_form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    // Clear previous error messages
    document.getElementById('registerEmailError').textContent = '';
    document.getElementById('registerIdError').textContent = '';
    document.getElementById('registerMessage').textContent = '';

    try {
        const res = await fetch('./process/register_process.php', {
            method: 'POST',
            body: formData
        });

        const contentType = res.headers.get("content-type");
        if (!res.ok) throw new Error('Server error');

        let data = {};
        if (contentType && contentType.includes("application/json")) {
            data = await res.json();
        } else {
            throw new Error("Invalid server response format");
        }

        if (data.success) {
            document.getElementById('registerMessage').textContent = "✅ Registered successfully. Please wait for admin approval.";
            form.reset();
        } else {
            if (data.error?.email) {
                document.getElementById('registerEmailError').textContent = data.error.email;
            }
            if (data.error?.id) {
                document.getElementById('registerIdError').textContent = data.error.id;
            }
            if (data.error?.approve) {
                document.getElementById('registerMessage').textContent = data.error.approve;
            }
            if (data.error?.message || data.message) {
                document.getElementById('registerMessage').textContent = data.error?.message || data.message;
            }
        }

    } catch (error) {
        document.getElementById('registerMessage').textContent = '❌ Something went wrong. Please try again later.';
        console.error("Register Error:", error);
    }
});
