const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});


//login Valitation
document.getElementById('loginForm').addEventListener('submit', function (e) {
    const form = e.target;
    const formData = new FormData(form);

    // Clear previous error messages
    document.getElementById('emailError').textContent = '';
    document.getElementById('passwordError').textContent = '';
    document.getElementById('loginMessage').textContent = '';

    fetch('./process/login_process.php', {
        method: 'POST',
        body: formData
    })
        .then(async res => {
            const contentType = res.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return res.json();
            } else {
                throw new Error("Invalid response: not JSON");
            }
        })
        .then(data => {
            if (data.success) {
                window.location.href = './pages/Dashboard.php';
            } else {
                if (data.error?.email) {
                   alert( data.error.email);
                }
                if (data.error?.password) {
                    alert( data.error.password);
                }
                    if (data.error?.approve) {
                    alert( data.error.approve);
                
                }
                if (data.message) {
                    alert( data.error.message);
                }
            }
        })
        .catch(error => {
             alert('Something went wrong: ' + error.message);
            console.error(error);
        });
});

//Register Valitation
document.getElementById('register_form').addEventListener("submit", function (e) {
    const form = e.target;
    const formData = new FormData(form);

    fetch('process/register_process.php', {
        method: 'POST',
        body: formData
    })
        .then(async res => {
            const contentType = res.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return res.json(); // ✅ call the function
            } else {
                throw new Error("Invalid Response: Not JSON");
            }
        })
        .then(data => {
            if (data.success) {
                alert("✅ You have registered successfully. Please wait for approval from the administrator.");
            } else {
                if (data.error?.email) {
                    alert(data.error.email);
                }
                if (data.error?.id) {
                    alert(data.error.id); 
                }
                if (data.error?.approve) {
                    alert(data.error.approve);
                }
                if (data.error?.message) {
                    alert(data.error.message); 
                }
            }
        })
        .catch(error => {
            alert("Something went wrong: " + error.message);
            console.error(error);
        });
});



