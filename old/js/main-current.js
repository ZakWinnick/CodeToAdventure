/**
 * Main JavaScript for Code To Adventure
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

// ✅ Initialize Event Listeners
document.addEventListener("DOMContentLoaded", () => {
    DOM.init();
    initializeEventListeners();

    // Set current year in footer
    const yearElement = document.getElementById("currentYear");
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }

    // Apply stored theme preference
    if (localStorage.getItem("theme") === "dark") {
        document.documentElement.classList.add("dark");
    }
});

// ✅ Set Up Event Listeners
function initializeEventListeners() {
    if (DOM.form) {
        DOM.form.addEventListener("submit", handleFormSubmit);
    }
    if (DOM.getAnotherCodeButton) {
        DOM.getAnotherCodeButton.addEventListener("click", getNewCode);
    }
}

// ✅ Show Modal (Restored)
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

// ✅ Close Modal (Restored)
function closeModal() {
    if (!DOM.modal) {
        console.error("❌ Error: Modal not found.");
        return;
    }
    DOM.modal.classList.add("hidden");
    DOM.modal.classList.remove("flex");
    console.log("✅ Modal closed.");
}

// ✅ Theme Toggle
function toggleTheme() {
    document.documentElement.classList.toggle("dark");
    localStorage.setItem("theme", document.documentElement.classList.contains("dark") ? "dark" : "light");
}

// ✅ Copy Code Function (Restored Toast)
function copyCode() {
    const codeElement = document.querySelector(".referral-code");

    if (!codeElement) {
        console.error("❌ Error: Referral code element not found.");
        showToast("Error: No referral code found.", "error");
        return;
    }

    const code = codeElement.textContent.trim();

    navigator.clipboard.writeText(code)
        .then(() => {
            console.log("✅ Copied code:", code);
            showToast("Referral code copied!", "success");
        })
        .catch(err => {
            console.error("❌ Copy error:", err);
            showToast("Failed to copy code.", "error");
        });
}

// ✅ Fetch New Code Function
async function getNewCode() {
    const codeElement = document.querySelector(".referral-code");
    const referralButton = document.querySelector('a[href^="track.php?code="]');

    if (!codeElement) {
        console.error("❌ Error: Referral code element not found.");
        return;
    }

    try {
        codeElement.innerHTML = "Fetching...";
        if (referralButton) {
            referralButton.innerHTML = "Fetching new code...";
        }

        const response = await fetch(`${CONFIG.API_ENDPOINTS.NEW_CODE}?current=${encodeURIComponent(codeElement.textContent.trim())}&t=${Date.now()}`);
        const data = await response.json();

        console.log("✅ Raw API response:", data);

        if (data.success && data.code) {
            updateCodeDisplay(data.code);
        } else {
            console.error("❌ Error fetching new code:", data.message);
            codeElement.innerHTML = '<p class="error-message">Error fetching new code.</p>';
        }
    } catch (err) {
        console.error("❌ Fetch error:", err);
        codeElement.innerHTML = '<p class="error-message">Error fetching new code.</p>';
    }
}

// ✅ Update Code Display Function
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

// ✅ Handle Form Submission
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

    const formData = new FormData();
    formData.append("name", name);
    formData.append("referralCode", referralCode);

    fetch(CONFIG.API_ENDPOINTS.STORE_CODE, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("🔍 Full response from store_code.php:", data);

        if (data.success) {
            console.log("✅ Code submitted:", data);
            showToast("Referral code submitted successfully!", "success");

            nameInput.value = "";
            codeInput.value = "";
            closeModal();
        } else if (data.message.toLowerCase().includes("duplicate")) {
            showToast("This referral code is already in the system.", "error");
        } else {
            showToast("Error submitting code: " + data.message, "error");
        }
    })
    .catch(error => {
        showToast("Server error. Please try again.", "error");
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = "Submit Code";
    });
}

// ✅ Toast Notification (Restored)
function showToast(message, type = "success") {
    const toast = document.getElementById("toast");

    if (!toast) {
        console.error("❌ Error: Toast element not found.");
        return;
    }

    toast.textContent = message;
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg transition-opacity duration-300 
        ${type === "error" ? "bg-red-600 text-white" : "bg-green-600 text-white"}`;

    toast.classList.remove("hidden");
    setTimeout(() => {
        toast.classList.add("hidden");
    }, 3000);
}

// ✅ Expose Global Functions
window.toggleTheme = toggleTheme;
window.copyCode = copyCode;
window.getNewCode = getNewCode;
window.showModal = showModal;
window.closeModal = closeModal;
window.handleFormSubmit = handleFormSubmit;
