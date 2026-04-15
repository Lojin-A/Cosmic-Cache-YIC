document.addEventListener("DOMContentLoaded", () => {
    
    // =========================================================
    // 1. POPUP LOGIC (Runs only on pages with the top buttons)
    // =========================================================
    const btnNotifications = document.getElementById("btn-notifications");
    const btnAccount = document.getElementById("btn-account");
    const popupNotifications = document.getElementById("popup-notifications");
    const popupAccount = document.getElementById("popup-account");

    if (btnNotifications && btnAccount) {
        btnNotifications.addEventListener("click", (event) => {
            event.preventDefault(); 
            popupNotifications.classList.remove("hidden");
        });

        btnAccount.addEventListener("click", (event) => {
            event.preventDefault(); 
            popupAccount.classList.remove("hidden");
        });
    }

    // =========================================================
    // 2. LOGIN VALIDATION LOGIC (Runs only on the Login page)
    // =========================================================
    const loginForm = document.getElementById("login-form");
    const errorMessage = document.getElementById("error-message");

    if (loginForm) {
        loginForm.addEventListener("submit", (event) => {
            // Stop the form from refreshing the page immediately
            event.preventDefault(); 

            const emailValue = document.getElementById("email").value;
            const passwordValue = document.getElementById("password").value;

            if (emailValue === "" || passwordValue === "") {
                errorMessage.textContent = "Error: Please fill in both fields!";
                errorMessage.classList.remove("hidden");
            } 
            else if (!emailValue.includes("@")) {
                errorMessage.textContent = "Error: Please enter a valid email address!";
                errorMessage.classList.remove("hidden");
            } 
            else if (passwordValue.length < 6) {
                errorMessage.textContent = "Error: Password must be at least 6 characters!";
                errorMessage.classList.remove("hidden");
            } 
            else {
                errorMessage.classList.add("hidden");
                alert("Login Successful! Welcome to Cosmic Cache.");
                window.location.href = "../index.html"; 
            }
        })
    }
    // =========================================================
    // 3. SIGN UP VALIDATION LOGIC (Runs only on Sign Up page)
    // =========================================================
    const signupForm = document.getElementById("signup-form");
    const signupError = document.getElementById("signup-error");

    if (signupForm) {
        signupForm.addEventListener("submit", (event) => {
            event.preventDefault(); 

            const nameValue = document.getElementById("name").value;
            const emailValue = document.getElementById("email").value;
            const passwordValue = document.getElementById("password").value;
            const confirmValue = document.getElementById("confirm-password").value;

            if (nameValue === "" || emailValue === "" || passwordValue === "" || confirmValue === "") {
                signupError.textContent = "Error: Please fill in all fields!";
                signupError.classList.remove("hidden");
            } 
            else if (!emailValue.includes("@")) {
                signupError.textContent = "Error: Please enter a valid email address!";
                signupError.classList.remove("hidden");
            } 
            else if (passwordValue.length < 6) {
                signupError.textContent = "Error: Password must be at least 6 characters!";
                signupError.classList.remove("hidden");
            } 
            else if (passwordValue !== confirmValue) {
                signupError.textContent = "Error: Passwords do not match!";
                signupError.classList.remove("hidden");
            } 
            else {
                signupError.classList.add("hidden");
                alert("Account Created Successfully! Welcome Lojin."); 
                window.location.href = "../index.html"; 
            }
        });
    }
});

// =========================================================
// 4. GLOBAL FUNCTIONS
// =========================================================
// Function to close all popups (called by the Close buttons in the HTML)
function closePopups() {
    const popups = document.querySelectorAll(".popup-overlay");
    popups.forEach(popup => {
        popup.classList.add("hidden");
    });
}