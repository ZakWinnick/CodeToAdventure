/**
 * Main JavaScript for Code To Adventure
 * Includes system theme detection, form handling, and UI interactions
 */

const CONFIG = {
    ANIMATION_DURATION: 300,
    TOAST_DURATION: 3000,
    API_ENDPOINTS: {
        NEW_CODE: "get_new_code.php",
        STORE_CODE: "store_code.php"
    }
};

const DOM = {
    init() {
        this.modal = document.getElementById("submitModal");
        this.toast = document.getElementById("toast");
        this.codeContainer = document.querySelector(".code-container");
        this.referralButton = document.querySelector(".referral-button");
        this.form = document.querySelector("form");
        this.getAnotherCodeButton = document.querySelector(".get-new-code-button");
    }
};

// Theme Management System
// Handles system preference detection, theme switching, and preference storage
const ThemeManager = {
    // Check if system is in dark mode
    isSystemDarkMode() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    },

    // Set theme based on preference
    setTheme(isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
        console.log(`✅ Theme set to ${isDark ? 'dark' : 'light'} mode`);
    },

    // Initialize theme based on stored preference or system preference
    initializeTheme() {
        // Check for stored preference first
        const storedTheme = localStorage.getItem('theme');
        
        if (storedTheme) {
            this.setTheme(storedTheme === 'dark');
            console.log('✅ Using stored theme preference:', storedTheme);
        } else {
            // If no stored preference, use system preference
            const systemDark = this.isSystemDarkMode();
            this.setTheme(systemDark);
            console.log('✅ Using system theme preference:', systemDark ? 'dark' : 'light');
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            // Only update if there's no stored preference
            if (!localStorage.getItem('theme')) {
                this.setTheme(e.matches);
                console.log('✅ System theme changed:', e.matches ? 'dark' : 'light');
            }
        });
    }
};

// Initialize Everything on Page Load
document.addEventListener("DOMContentLoaded", () => {
    DOM.init();
    initializeEventListeners();
    ThemeManager.initializeTheme();
if (DOM.modal) {
    DOM.modal.addEventListener('click', (e) => {
        if (e.target === DOM.modal) {
            closeModal();
        }
    });
}

    // Set current year in footer
    const yearElement = document.getElementById("currentYear");
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
});

// Set Up Event Listeners
function initializeEventListeners() {
    if (DOM.form) {
        DOM.form.addEventListener("submit", handleFormSubmit);
    }
    if (DOM.getAnotherCodeButton) {
        DOM.getAnotherCodeButton.addEventListener("click", getNewCode);
    }
}

// Theme Toggle Function
function toggleTheme() {
    const isDark = !document.documentElement.classList.contains("dark");
    ThemeManager.setTheme(isDark);
}

// Show Modal
function showModal() {
    if (!DOM.modal) {
        console.error("❌ Error: Modal element not found.");
        showToast("Error: Modal not found.", "error");
        return;
    }
    DOM.modal.classList.remove("hidden");
    DOM.modal.classList.add("flex");
    console.log("✅ Modal opened.");
}

// Close Modal
function closeModal() {
    if (!DOM.modal) {
        console.error("❌ Error: Modal not found.");
        return;
    }
    DOM.modal.classList.add("hidden");
    DOM.modal.classList.remove("flex");
    console.log("✅ Modal closed.");
}

// Copy Code Function
function copyCode() {
    const codeElement = document.querySelector(".referral-code");

    if (!codeElement) {
        console.error("❌ Error: Referral code element not found.");
        showToast("Error: No referral code found.", "error");
        return;
    }

    const code = codeElement.textContent.trim();
    
    // Use document.execCommand as fallback for mobile
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(code)
            .then(() => {
                console.log("✅ Copied code:", code);
                showToast("Referral code copied!", "success");
            })
            .catch(err => {
                console.error("❌ Copy error:", err);
                showToast("Failed to copy code. Tap and hold to copy manually.", "error");
            });
    } else {
        const tempInput = document.createElement("input");
        tempInput.value = code;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        console.log("✅ Copied using fallback:", code);
        showToast("Referral code copied!", "success");
    }
}

// Fetch New Code Function
async function getNewCode() {
    const codeElement = document.querySelector(".referral-code");
    const referralButton = document.querySelector('a[href^="track.php?code="]');

    if (!codeElement) {
        console.error("❌ Error: Referral code element not found.");
        showToast("Error: Code display missing.", "error");
        return;
    }

    try {
        // Prevent multiple taps
        codeElement.innerHTML = "Fetching...";
        if (referralButton) {
            referralButton.innerHTML = "Fetching new code...";
        }

        // Fetch new referral code
        const response = await fetch(`${CONFIG.API_ENDPOINTS.NEW_CODE}?current=${encodeURIComponent(codeElement.textContent.trim())}&t=${Date.now()}`);
        const data = await response.json();

        console.log("✅ Raw API response:", data);

        if (data.success && data.code) {
            updateCodeDisplay(data.code);
        } else {
            console.error("❌ Error fetching new code:", data.message);
            codeElement.innerHTML = '<p class="error-message text-red-600">Error fetching new code.</p>';
        }
    } catch (err) {
        console.error("❌ Fetch error:", err);
        codeElement.innerHTML = '<p class="error-message text-red-600">Error fetching new code.</p>';
    }
}

// Update Code Display Function
function updateCodeDisplay(codeData) {
    setTimeout(() => {
        const codeElement = document.querySelector(".referral-code");
        const referralButton = document.querySelector('a[href^="track.php?code="]');

        if (!codeElement || !referralButton) {
            console.error("❌ Error: Required elements missing.");
            return;
        }

        referralButton.href = `track.php?code=${encodeURIComponent(codeData.referral_code)}`;
        referralButton.innerHTML = `Use ${codeData.name}'s Code`;
        codeElement.textContent = codeData.referral_code;
    }, 100);
}

// Handle Form Submission - UPDATED
function handleFormSubmit(event) {
    event.preventDefault();

    const submitButton = document.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = "Submitting...";

    const nameInput = document.getElementById("name");
    const codeInput = document.getElementById("referralCode");

    const name = nameInput?.value.trim();
    const referralCode = codeInput?.value.trim();

    if (!name || !referralCode) {
        console.error("❌ Missing required fields");
        showToast("Please enter your name and referral code.", "error");
        submitButton.disabled = false;
        submitButton.textContent = "Submit Code";
        return;
    }

    // Validate referral code format before submission
    const codePattern = /(?=(?:.*[A-Za-z]){2})(?=(?:.*\d){7,})[A-Za-z0-9]+/;
    if (!codePattern.test(referralCode)) {
        console.error("❌ Invalid code format");
        showToast("Invalid code format. Code must contain at least 2 letters and 7 numbers.", "error");
        submitButton.disabled = false;
        submitButton.textContent = "Submit Code";
        return;
    }

    const formData = new FormData();
    formData.append("name", name);
    formData.append("referralCode", referralCode);

    // Add timestamp to prevent caching issues
    const url = `${CONFIG.API_ENDPOINTS.STORE_CODE}?t=${Date.now()}`;

    fetch(url, {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            console.error("❌ Server returned error status:", response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log("🔍 Response from store_code.php:", data);

        if (data.success) {
            console.log("✅ Code submitted:", data);
            showToast("Referral code submitted successfully!", "success");

            nameInput.value = "";
            codeInput.value = "";
            closeModal();
        } else {
            if (data.message && data.message.toLowerCase().includes("already exists")) {
                console.error("❌ Duplicate code detected");
                showToast("This referral code is already in our system. Please check and try again.", "error");
                // Highlight the code input field to draw attention
                codeInput.classList.add("border-red-500");
                setTimeout(() => {
                    codeInput.classList.remove("border-red-500");
                }, 3000);
            } else {
                showToast("Error: " + (data.message || "Unknown server error"), "error");
            }
        }
    })
    .catch(error => {
        console.error("❌ Fetch error:", error);
        showToast("Server error. Please try again later.", "error");
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = "Submit Code";
    });
}

// Toast Notification - UPDATED
function showToast(message, type = "success") {
    const toast = document.getElementById("toast");

    if (!toast) {
        console.error("❌ Error: Toast element not found.");
        return;
    }

    // Clear any existing timeouts to prevent hiding a toast too soon
    if (window.toastTimeout) {
        clearTimeout(window.toastTimeout);
    }

    // Set color based on message type
    let bgColor, textColor, borderColor;
    switch(type) {
        case "error":
            bgColor = "bg-red-100";
            textColor = "text-red-800";
            borderColor = "border-red-500";
            break;
        case "warning":
            bgColor = "bg-yellow-100";
            textColor = "text-yellow-800";
            borderColor = "border-yellow-500";
            break;
        case "success":
        default:
            bgColor = "bg-green-100";
            textColor = "text-green-800";
            borderColor = "border-green-500";
            break;
    }

    // Add appropriate icon
    let icon = '';
    if (type === "error") {
        icon = `<svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>`;
    } else if (type === "success") {
        icon = `<svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>`;
    }

    // Create toast HTML with improved styling
    toast.innerHTML = `
        <div class="flex items-center ${textColor}">
            ${icon}
            <span>${message}</span>
        </div>
    `;

    toast.className = `fixed bottom-4 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-lg shadow-lg transition-opacity duration-300 
        ${bgColor} ${textColor} border ${borderColor} z-50`;

    toast.classList.remove("hidden");
    
    // Set timeout to hide toast
    window.toastTimeout = setTimeout(() => {
        // Fade out
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.classList.add("hidden");
            toast.style.opacity = '1';
        }, 300);
    }, CONFIG.TOAST_DURATION || 3000);
}

// Toggle Mobile Menu
function toggleMobileMenu() {
    const menu = document.getElementById("mobile-menu");
    if (!menu) {
        console.error("❌ Error: Mobile menu element not found.");
        return;
    }
    menu.classList.toggle("hidden");
    menu.classList.toggle("flex");
    console.log("✅ Mobile menu toggled.");
}

// Expose Global Functions
window.toggleTheme = toggleTheme;
window.copyCode = copyCode;
window.getNewCode = getNewCode;
window.showModal = showModal;
window.closeModal = closeModal;
window.handleFormSubmit = handleFormSubmit;
window.toggleMobileMenu = toggleMobileMenu;