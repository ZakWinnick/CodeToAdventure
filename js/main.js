/**
 * Main JavaScript for Code To Adventure
 * Handles UI interactions, animations, and AJAX requests
 */

// ==========================================================================
// Configuration
// ==========================================================================
const CONFIG = {
    ANIMATION_DURATION: 300, // in milliseconds
    TOAST_DURATION: 3000,    // in milliseconds
    API_ENDPOINTS: {
        NEW_CODE: 'get_new_code.php',
        STORE_CODE: 'store_code.php'
    }
};

// ==========================================================================
// DOM Elements
// ==========================================================================
const DOM = {
    init() {
        this.mainContent = document.querySelector('.main-content');
        this.modal = document.getElementById('submitModal');
        this.toast = document.getElementById('toast');
        this.codeContainer = document.querySelector('.code-container');
        this.referralButton = document.querySelector('.referral-button');
        this.form = this.modal?.querySelector('form');
    }
};

// ==========================================================================
// Event Listeners
// ==========================================================================
document.addEventListener('DOMContentLoaded', () => {
    DOM.init();
    initializeEventListeners();
    initializeAnimations();
});

function initializeEventListeners() {
    // Modal event listeners
    window.onclick = handleWindowClick;
    
    // Form validation
    if (DOM.form) {
        DOM.form.addEventListener('submit', handleFormSubmit);
    }
}

// ==========================================================================
// Modal Functions
// ==========================================================================
function showModal() {
    DOM.modal.style.display = 'block';
    DOM.modal.classList.add('animate-in');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    DOM.modal.style.display = 'none';
    DOM.modal.classList.remove('animate-in');
    document.body.style.overflow = 'auto';
}

function handleWindowClick(event) {
    if (event.target === DOM.modal) {
        closeModal();
    }
}

// ==========================================================================
// Form Handling
// ==========================================================================
function handleFormSubmit(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const name = formData.get('name');
    const referralCode = formData.get('referralCode');

    if (!validateForm(name, referralCode)) {
        return;
    }

    submitForm(formData);
}

function validateForm(name, code) {
    // Name validation
    if (!name || name.trim().length < 2) {
        showToast('Please enter a valid name (at least 2 characters)');
        return false;
    }

    // Code validation (assuming format like ZAK1452284)
    const codeRegex = /^[A-Z]{3}\d{7}$/;
    if (!codeRegex.test(code)) {
        showToast('Please enter a valid referral code (format: ABC1234567)');
        return false;
    }

    return true;
}

async function submitForm(formData) {
    try {
        const response = await fetch(CONFIG.API_ENDPOINTS.STORE_CODE, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showToast('Code submitted successfully!');
            closeModal();
            // Optional: Reset form
            DOM.form.reset();
        } else {
            showToast(data.message || 'Error submitting code. Please try again.');
        }
    } catch (error) {
        console.error('Form submission error:', error);
        showToast('Error submitting code. Please try again.');
    }
}

// ==========================================================================
// Copy Functions
// ==========================================================================
async function copyCode(code) {
    const button = document.querySelector('.copy-button');
    const originalText = button.innerHTML;
    
    try {
        await navigator.clipboard.writeText(code);
        button.innerHTML = '<span>✓</span> Copied!';
        showToast('Code copied to clipboard!');
        
        // Reset button after animation
        setTimeout(() => {
            button.innerHTML = originalText;
        }, 2000);
    } catch (err) {
        showToast('Failed to copy code. Please try again.');
    }
}

// ==========================================================================
// Code Fetching Functions
// ==========================================================================
async function getNewCode() {
    if (!DOM.codeContainer || !DOM.referralButton) return;

    const loadingHTML = '<div class="loading-spinner"></div>';
    
    try {
        // Show loading state
        DOM.codeContainer.innerHTML = loadingHTML;
        DOM.referralButton.style.opacity = '0.7';
        
        const response = await fetch(CONFIG.API_ENDPOINTS.NEW_CODE);
        const data = await response.json();
        
        if (data.success) {
            updateCodeDisplay(data.code);
            showToast('New code fetched successfully!');
        } else {
            showToast('Error fetching new code. Please try again.');
        }
    } catch (err) {
        console.error('Error fetching new code:', err);
        showToast('Error fetching new code. Please try again.');
    } finally {
        DOM.referralButton.style.opacity = '1';
    }
}

function updateCodeDisplay(codeData) {
    if (!DOM.codeContainer || !DOM.referralButton) return;

    DOM.referralButton.href = `https://rivian.com/configurations/list?reprCode=${codeData.referral_code}`;
    DOM.referralButton.innerHTML = `Use ${codeData.name}'s Code`;
    
    DOM.codeContainer.innerHTML = `
        <span class="referral-code">${codeData.referral_code}</span>
        <button class="copy-button" onclick="copyCode('${codeData.referral_code}')" title="Copy code">
            <span>⧉</span> Copy Code
        </button>
    `;
    DOM.codeContainer.classList.add('fade-in-up');
}

// ==========================================================================
// UI Utilities
// ==========================================================================
function showToast(message) {
    if (!DOM.toast) return;

    DOM.toast.textContent = message;
    DOM.toast.style.display = 'block';

    setTimeout(() => {
        DOM.toast.style.display = 'none';
    }, CONFIG.TOAST_DURATION);
}

// ==========================================================================
// Animation Functions
// ==========================================================================
function initializeAnimations() {
    if (DOM.mainContent) {
        DOM.mainContent.classList.add('animate-in');
    }
}

// Make functions available globally
window.showModal = showModal;
window.closeModal = closeModal;
window.copyCode = copyCode;
window.getNewCode = getNewCode;