// Wait for the HTML to fully load before running the script
document.addEventListener("DOMContentLoaded", () => {
    
    const btnNotifications = document.getElementById("btn-notifications");
    const btnAccount = document.getElementById("btn-account");
    const popupNotifications = document.getElementById("popup-notifications");
    const popupAccount = document.getElementById("popup-account");

    // Only run this if we are on the logged-in dashboard (where the buttons exist)
    if (btnNotifications && btnAccount) {
        
        btnNotifications.addEventListener("click", () => {
            popupNotifications.classList.remove("hidden");
        });

        btnAccount.addEventListener("click", () => {
            popupAccount.classList.remove("hidden");
        });
    }
});

// Function to close all popups (called by the Close buttons in the HTML)
function closePopups() {
    const popups = document.querySelectorAll(".popup-overlay");
    popups.forEach(popup => {
        popup.classList.add("hidden");
    });
}