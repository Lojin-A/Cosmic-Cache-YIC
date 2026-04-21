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
const errorMessage = document.getElementById("error-message"); // The JS Error
const phpError = document.getElementById("php-error");         // The PHP Error
const emailBox = document.getElementById("email");
const passwordBox = document.getElementById("password");

// =========================================================
// HIDE ALL ERRORS WHEN TYPING (Your input event idea!)
// =========================================================
if (emailBox && passwordBox) {
    
    // Listen for typing in the email box
    emailBox.addEventListener("input", () => {
        if (errorMessage) errorMessage.classList.add("hidden"); // Hide JS error
        if (phpError) phpError.style.display = "none";          // Hide PHP error
    });

    // Listen for typing in the password box
    passwordBox.addEventListener("input", () => {
        if (errorMessage) errorMessage.classList.add("hidden"); // Hide JS error
        if (phpError) phpError.style.display = "none";          // Hide PHP error
    });
}

// =========================================================
// YOUR ORIGINAL SUBMIT LOGIC
// =========================================================
if (loginForm) {
    loginForm.addEventListener("submit", (event) => {
        const emailValue = emailBox.value;
        const passwordValue = passwordBox.value;

        if (emailValue === "" || passwordValue === "") {
            event.preventDefault(); 
            errorMessage.textContent = "Error: Please fill in both fields!";
            errorMessage.classList.remove("hidden");
        } 
        else if (!emailValue.includes("@")) {
            event.preventDefault(); 
            errorMessage.textContent = "Error: Please enter a valid email address!";
            errorMessage.classList.remove("hidden");
        } 
        else if (passwordValue.length < 6) {
            event.preventDefault(); 
            errorMessage.textContent = "Error: Password must be at least 6 characters!";
            errorMessage.classList.remove("hidden");
        } 
        else {
            errorMessage.classList.add("hidden");
        }
    });
}

    // =========================================================
    // 3. SIGN UP VALIDATION LOGIC (Runs only on Sign Up page)
    // =========================================================
    const signupForm = document.getElementById("signup-form");
    const signupError = document.getElementById("signup-error");
    const phpSignupError = document.getElementById("php-signup-error");
    
    const nameBox = document.getElementById("name");
    const emailSignupBox = document.getElementById("email");
    const passwordSignupBox = document.getElementById("password");
    const confirmBox = document.getElementById("confirm-password");

    // Hide errors when typing
    if (nameBox && emailSignupBox && passwordSignupBox && confirmBox) {
        const hideSignupErrors = () => {
            if (signupError) signupError.classList.add("hidden");
            if (phpSignupError) phpSignupError.style.display = "none";
        };

        nameBox.addEventListener("input", hideSignupErrors);
        emailSignupBox.addEventListener("input", hideSignupErrors);
        passwordSignupBox.addEventListener("input", hideSignupErrors);
        confirmBox.addEventListener("input", hideSignupErrors);
    }

    if (signupForm) {
        signupForm.addEventListener("submit", (event) => {
            const nameValue = nameBox.value;
            const emailValue = emailSignupBox.value;
            const passwordValue = passwordSignupBox.value;
            const confirmValue = confirmBox.value;

            if (nameValue === "" || emailValue === "" || passwordValue === "" || confirmValue === "") {
                event.preventDefault(); 
                signupError.textContent = "Error: Please fill in all fields!";
                signupError.classList.remove("hidden");
            } 
            else if (!emailValue.includes("@")) {
                event.preventDefault(); 
                signupError.textContent = "Error: Please enter a valid email address!";
                signupError.classList.remove("hidden");
            } 
            else if (passwordValue.length < 6) {
                event.preventDefault(); 
                signupError.textContent = "Error: Password must be at least 6 characters!";
                signupError.classList.remove("hidden");
            } 
            else if (passwordValue !== confirmValue) {
                event.preventDefault(); 
                signupError.textContent = "Error: Passwords do not match!";
                signupError.classList.remove("hidden");
            } 
            else {
                // Let the form submit to PHP!
                signupError.classList.add("hidden");
            }
        });
    }

    // =========================================================
    // 5. REPORT LOST ITEM VALIDATION (With Pro Checks!)
    // =========================================================
    const reportLostForm = document.getElementById("report-lost-form");
    const reportError = document.getElementById("report-error");

    if (reportLostForm) {
        reportLostForm.addEventListener("submit", (event) => {
            event.preventDefault(); 

            const itemName = document.getElementById("item-name").value.trim();
            const itemLocation = document.getElementById("item-location").value.trim();
            const itemDate = document.getElementById("item-date").value;
            const itemDesc = document.getElementById("item-description").value.trim();
            const itemPhoto = document.getElementById("item-photo").value; // Gets the file name

            // Get exactly today's date in YYYY-MM-DD format to compare
            const today = new Date().toISOString().split('T')[0];

            // Check 1: Are any required fields completely empty?
            if (itemName === "" || itemLocation === "" || itemDate === "" || itemDesc === "") {
                reportError.textContent = "Error: Please fill in all required fields!";
                reportError.classList.remove("hidden");
            } 
            // Check 2: Is the date in the future?
            else if (itemDate > today) {
                reportError.textContent = "Error: You cannot select a date in the future!";
                reportError.classList.remove("hidden");
            }
            // Check 3: If they uploaded a file, is it actually an image?
            else if (itemPhoto !== "" && !itemPhoto.match(/\.(jpg|jpeg|png|gif)$/i)) {
                reportError.textContent = "Error: Please upload a valid image file (JPG, PNG, GIF).";
                reportError.classList.remove("hidden");
            }
            // Success!
            else {
                reportError.classList.add("hidden");
                alert("Lost item reported successfully!");
                reportLostForm.reset(); 
            }
        });
    }

    // =========================================================
    // 6. POST FOUND ITEM VALIDATION
    // =========================================================
    const reportFoundForm = document.getElementById("report-found-form");
    const foundError = document.getElementById("found-error");

    if (reportFoundForm) {
        reportFoundForm.addEventListener("submit", (event) => {
            event.preventDefault(); 

            // Grab the text the user typed
            const itemName = document.getElementById("found-item-name").value.trim();
            const itemLocation = document.getElementById("found-item-location").value.trim();
            const itemDesc = document.getElementById("found-item-description").value.trim();
            const itemPhoto = document.getElementById("found-item-photo").value; 

            // Check 1: Are any required fields completely empty?
            if (itemName === "" || itemLocation === "" || itemDesc === "") {
                foundError.textContent = "Error: Please fill in all required fields!";
                foundError.classList.remove("hidden");
            } 
            // Check 2: If they uploaded a file, is it actually an image?
            else if (itemPhoto !== "" && !itemPhoto.match(/\.(jpg|jpeg|png|gif)$/i)) {
                foundError.textContent = "Error: Please upload a valid image file (JPG, PNG, GIF).";
                foundError.classList.remove("hidden");
            }
            // Success!
            else {
                foundError.classList.add("hidden");
                alert("Found item posted successfully! Thank you for helping.");
                reportFoundForm.reset(); 
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

