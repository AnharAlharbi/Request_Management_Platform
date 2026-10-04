// ------------------------------
// Switch between Login / Sign Up
// ------------------------------
document.getElementById('tab-login').addEventListener('click', function() {
    document.getElementById('pane-login').classList.add('active');
    document.getElementById('pane-signup').classList.remove('active');

    this.setAttribute('aria-selected', 'true');
    document.getElementById('tab-signup').setAttribute('aria-selected', 'false');
});

document.getElementById('tab-signup').addEventListener('click', function() {
    document.getElementById('pane-signup').classList.add('active');
    document.getElementById('pane-login').classList.remove('active');

    this.setAttribute('aria-selected', 'true');
    document.getElementById('tab-login').setAttribute('aria-selected', 'false');
});


// ------------------------------
// Sign Up (AJAX)
// ------------------------------
document.getElementById("pane-signup").addEventListener("submit", function(e) {
    e.preventDefault(); // Prevent page reload

    const form = this;
    const formData = new FormData(form);
    const msgBox = document.getElementById("suMsg");

    fetch("php/signup.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(response => {

        if (response.success) {
            msgBox.style.color = "green";
            msgBox.innerText = "Account created successfully!";
            form.reset();
        } else {
            msgBox.style.color = "red";
            msgBox.innerText = response.message;
        }

    })
    .catch(err => {
        msgBox.style.color = "red";
        msgBox.innerText = "An unexpected error occurred.";
    });
});


// ------------------------------
// Login (AJAX)
// ------------------------------
document.getElementById("pane-login").addEventListener("submit", function(e) {
    e.preventDefault(); // Prevent reload

    const form = this;
    const formData = new FormData(form);
    const msgBox = document.getElementById("loginMsg");

    fetch("php/login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(response => {

        if (response.success) {
            msgBox.style.color = "green";
            msgBox.innerText = "Login successful! Redirecting...";

            setTimeout(() => {
                window.location.href = response.redirect || "index.html";
            }, 1000);
        } else {
            msgBox.style.color = "red";
            msgBox.innerText = response.message;
        }

    })
    .catch(err => {
        msgBox.style.color = "red";
        msgBox.innerText = "An unexpected error occurred.";
    });
});
