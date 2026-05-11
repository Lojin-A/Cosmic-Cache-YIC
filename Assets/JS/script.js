document.addEventListener("DOMContentLoaded", () => {
    
    // =========================================================
    // 1. POPUP LOGIC 
    // =========================================================
    const btnNotifications = document.getElementById("btn-notifications");
    const btnAccount = document.getElementById("btn-account");
    const popupNotifications = document.getElementById("popup-notifications");
    const popupAccount = document.getElementById("popup-account");
    const unreadDot = document.getElementById("unread-dot"); 

    if (btnNotifications && btnAccount) {
        if (unreadDot) {
            if (typeof currentUserId !== 'undefined' && localStorage.getItem('unread_' + currentUserId) === 'true') {
                unreadDot.style.display = "block"; 
            } else {
                unreadDot.style.display = "none";  
            }
        }
        
        btnNotifications.addEventListener("click", (event) => {
            event.preventDefault(); 
            
            if (unreadDot) {
                unreadDot.style.display = "none"; 
                localStorage.setItem('unread_' + currentUserId, 'false'); 
            }
            
            const notifContainer = document.getElementById("notif-content");
            if (notifContainer) {
                notifContainer.innerHTML = ""; 

                const storedJson = localStorage.getItem('cosmic_notifs');
                const allNotifs = storedJson ? JSON.parse(storedJson) : {};
                const myMessages = allNotifs[currentUserId] || [];

                if (myMessages.length === 0) {
                    notifContainer.innerHTML = "<p style='text-align:center;'>You have no new notifications at this time.</p>";
                } else {
                    myMessages.forEach(msg => {
                        notifContainer.innerHTML += `<p style="border-bottom: 2px dashed #92aae3; padding-bottom: 10px; font-size: 20px; color: #31365a;">✨ ${msg}</p>`;
                    });
                }
            }
            popupNotifications.classList.remove("hidden");
        });
        btnAccount.addEventListener("click", (event) => {
            event.preventDefault(); 
            popupAccount.classList.remove("hidden");
        });
        const btnClearNotifs = document.getElementById("btn-clear-notifs");
        if (btnClearNotifs) {
            btnClearNotifs.addEventListener("click", (event) => {
                event.preventDefault();
                
                const storedJson = localStorage.getItem('cosmic_notifs');
                if (storedJson && typeof currentUserId !== 'undefined') {
                    let allNotifs = JSON.parse(storedJson);
                    allNotifs[currentUserId] = []; // Empty the array!
                    localStorage.setItem('cosmic_notifs', JSON.stringify(allNotifs));
                    
                    const notifContainer = document.getElementById("notif-content");
                    if (notifContainer) {
                        notifContainer.innerHTML = "<p style='text-align:center;'>You have no new notifications at this time.</p>";
                    }
                }
            });
        }
    }

    // =========================================================
    // 2. LOGIN VALIDATION LOGIC (Runs only on the Login page)
    // =========================================================
    const loginForm = document.getElementById("login-form");
const errorMessage = document.getElementById("error-message"); 
const phpError = document.getElementById("php-error");         
const emailBox = document.getElementById("email");
const passwordBox = document.getElementById("password");

if (emailBox && passwordBox) {
    emailBox.addEventListener("input", () => {
        if (errorMessage) errorMessage.classList.add("hidden"); 
        if (phpError) phpError.style.display = "none";         
    });

    passwordBox.addEventListener("input", () => {
        if (errorMessage) errorMessage.classList.add("hidden"); 
        if (phpError) phpError.style.display = "none";         
    });
}

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
                signupError.classList.add("hidden");
            }
        });
    }

    // =========================================================
// 5. REPORT LOST ITEM VALIDATION 
// =========================================================
const reportLostForm = document.getElementById("report-lost-form");
const reportError = document.getElementById("report-error");

if (reportLostForm) {
    reportLostForm.addEventListener("submit", (event) => {

        const itemName = document.getElementById("item-name").value.trim();
        const itemLocation = document.getElementById("item-location").value.trim();
        const itemDate = document.getElementById("item-date").value;
        const itemDesc = document.getElementById("item-description").value.trim();
        const itemPhoto = document.getElementById("item-photo").value; 
        const today = new Date().toISOString().split('T')[0];
        if (itemName === "" || itemLocation === "" || itemDate === "" || itemDesc === "") {
            reportError.textContent = "Error: Please fill in all required fields!";
            reportError.classList.remove("hidden");
        } 
        else if (itemDate > today) {
            event.preventDefault(); 
            reportError.textContent = "Error: You cannot select a date in the future!";
            reportError.classList.remove("hidden");
        }
        else if (itemPhoto !== "" && !itemPhoto.match(/\.(jpg|jpeg|png|gif)$/i)) {
            event.preventDefault(); 
            reportError.textContent = "Error: Please upload a valid image file (JPG, PNG, GIF).";
            reportError.classList.remove("hidden");
        }
        else {
            reportError.classList.add("hidden");
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

        const itemName = document.getElementById("found-item-name").value.trim();
        const itemLocation = document.getElementById("found-item-location").value.trim();
        const itemDesc = document.getElementById("found-item-description").value.trim();
        const itemPhoto = document.getElementById("found-item-photo").value; 

        if (itemName === "" || itemLocation === "" || itemDesc === "") {
            event.preventDefault(); 
            foundError.textContent = "Error: Please fill in all required fields!";
            foundError.classList.remove("hidden");
        } 
        else if (itemPhoto !== "" && !itemPhoto.match(/\.(jpg|jpeg|png|gif)$/i)) {
            event.preventDefault(); 
            foundError.textContent = "Error: Please upload a valid image file (JPG, PNG, GIF).";
            foundError.classList.remove("hidden");
        }
        else {
            foundError.classList.add("hidden");
        }
    });
}
});
// =========================================================
// 4. GLOBAL FUNCTIONS
// =========================================================
function closePopups() {
    const popups = document.querySelectorAll(".popup-overlay");
    popups.forEach(popup => {
        popup.classList.add("hidden");
    });
    
    
}

