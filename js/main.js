/**
 * Main JavaScript for Code To Adventure
 */

const CONFIG = {
    ANIMATION_DURATION: 300,
    TOAST_DURATION: 3000,
    API_ENDPOINTS: {
        NEW_CODE: 'get_new_code.php',
        STORE_CODE: 'store_code.php'
    }
};

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

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    DOM.init();
    initializeEventListeners();
    initializeAnimations();
    
    // Set current year in footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();
});

function initializeEventListeners() {
    window.onclick = handleWindowClick;
    if (DOM.form) {
        DOM.form.addEventListener('submit', handleFormSubmit);
    }
}

// Modal Functions
function showModal() {
    DOM.modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    DOM.modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

function handleWindowClick(event) {
    if (event.target === DOM.modal) {
        closeModal();
    }
}

// Form Handling
function handleFormSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    if (!validateForm(formData.get('name'), formData.get('referralCode'))) {
        return;
    }
    submitForm(formData);
}

function validateForm(name, code) {
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('referralCode');
    let isValid = true;

    if (!name || name.trim().length < 2) {
        showInputError(nameInput, 'Please enter a valid name (at least 2 characters)');
        isValid = false;
    } else {
        clearInputError(nameInput);
    }

    const codeRegex = /^(?=(?:.*[A-Za-z]){2,})(?=(?:.*\d){7,})[A-Za-z0-9]+$/;
    if (!codeRegex.test(code)) {
        showInputError(codeInput, 'Please enter a valid referral code (must contain at least 2 letters and 7 numbers)');
        isValid = false;
    } else {
        clearInputError(codeInput);
    }

    return isValid;
}

function showInputError(input, message) {
    const errorDiv = input.nextElementSibling?.classList.contains('error-message') 
        ? input.nextElementSibling 
        : document.createElement('div');
    
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    if (!input.nextElementSibling?.classList.contains('error-message')) {
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }
    
    input.classList.add('input-error');
    showToast(message);
}

function clearInputError(input) {
    const errorDiv = input.nextElementSibling;
    if (errorDiv?.classList.contains('error-message')) {
        errorDiv.remove();
    }
    input.classList.remove('input-error');
}

async function submitForm(formData) {
    const submitButton = DOM.form.querySelector('button[type="submit"]');
    
    // Check if already submitting
    if (submitButton.disabled) {
        console.log('Form is already being submitted');
        return;
    }

    try {
        // Disable submit button and show loading state
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';

        const response = await fetch(CONFIG.API_ENDPOINTS.STORE_CODE, {
            method: 'POST',
            body: formData,
            headers: {
                'Cache-Control': 'no-cache'
            }
        });

        let data;
        try {
            data = await response.json();
        } catch (e) {
            console.error('Error parsing JSON response:', e);
            throw new Error('Server response was not in the expected format');
        }

        if (data.success) {
            showToast('Code submitted successfully!');
            closeModal();
            DOM.form.reset();
        } else {
            showToast(data.message || 'Error submitting code. Please try again.');
        }
    } catch (error) {
        console.error('Form submission error:', error);
        showToast('Error submitting code. Please try again.');
    } finally {
        // Short delay before re-enabling the button to prevent accidental double-clicks
        setTimeout(() => {
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Code';
        }, 1000);
    }
}

// Copy Function
async function copyCode(code) {
    const button = document.querySelector('.copy-button');
    const originalText = button.innerHTML;
    
    try {
        await navigator.clipboard.writeText(code);
        button.innerHTML = '<span>⧉</span> Copied!';
        showToast('Code copied to clipboard!');
        
        setTimeout(() => {
            button.innerHTML = originalText;
        }, 2000);
    } catch (err) {
        showToast('Failed to copy code. Please try again.');
    }
}

// Code Fetching
async function getNewCode() {
    if (!DOM.codeContainer || !DOM.referralButton) return;

    const loadingHTML = '<div class="loading-spinner"></div>';
    const currentCode = document.querySelector('.referral-code')?.textContent || '';
    
    try {
        DOM.codeContainer.innerHTML = loadingHTML;
        DOM.referralButton.style.opacity = '0.7';
        
        const timestamp = new Date().getTime();
        const response = await fetch(`${CONFIG.API_ENDPOINTS.NEW_CODE}?current=${encodeURIComponent(currentCode)}&t=${timestamp}`);
        const data = await response.json();
        
        if (data.success && data.code) {
            DOM.codeContainer.classList.remove('fade-in-up');
            updateCodeDisplay(data.code);
            void DOM.codeContainer.offsetWidth;
            DOM.codeContainer.classList.add('fade-in-up');
            showToast('New code fetched successfully!');
        } else {
            throw new Error(data.message || 'Error fetching new code');
        }
    } catch (err) {
        DOM.codeContainer.innerHTML = '<p class="error-message">Error fetching new code. Please try again.</p>';
        showToast('Error fetching new code. Please try again.');
    } finally {
        DOM.referralButton.style.opacity = '1';
    }
}

function updateCodeDisplay(codeData) {
    if (!DOM.codeContainer || !DOM.referralButton || !codeData) return;

    try {
        DOM.referralButton.href = `track.php?code=${encodeURIComponent(codeData.referral_code)}`;
        DOM.referralButton.innerHTML = `Use ${escapeHtml(codeData.name)}'s Code`;
        
        const newHTML = `
            <span class="referral-code">${escapeHtml(codeData.referral_code)}</span>
            <button class="copy-button" onclick="copyCode('${escapeHtml(codeData.referral_code)}')" title="Copy code">
                <span>⧉</span> Copy Code
            </button>
        `;
        
        DOM.codeContainer.innerHTML = newHTML;
        void DOM.codeContainer.offsetWidth;
        
    } catch (error) {
        console.error('Error updating code display:', error);
        showToast('Error updating display. Please refresh the page.');
    }
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function showToast(message) {
    if (!DOM.toast) return;

    DOM.toast.textContent = message;
    DOM.toast.style.display = 'block';

    setTimeout(() => {
        DOM.toast.style.display = 'none';
    }, CONFIG.TOAST_DURATION);
}

function initializeAnimations() {
    if (DOM.mainContent) {
        DOM.mainContent.classList.add('animate-in');
    }
}

// Global functions
window.showModal = showModal;
window.closeModal = closeModal;
window.copyCode = copyCode;
window.getNewCode = getNewCode;