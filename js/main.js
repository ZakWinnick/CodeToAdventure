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
    
    // Set current year in footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();
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
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('referralCode');
    let isValid = true;

    // Name validation
    if (!name || name.trim().length < 2) {
        showInputError(nameInput, 'Please enter a valid name (at least 2 characters)');
        isValid = false;
    } else {
        clearInputError(nameInput);
    }

    // Code validation (assuming format like ZAK1452284)
    const codeRegex = /^[A-Z]{3}\d{7}$/;
    if (!codeRegex.test(code)) {
        showInputError(codeInput, 'Please enter a valid referral code (format: ABC1234567)');
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
    const currentCode = document.querySelector('.referral-code')?.textContent || '';
    
    console.log('Starting getNewCode function');
    console.log('Current code:', currentCode);
    
    try {
        // Show loading state
        DOM.codeContainer.innerHTML = loadingHTML;
        DOM.referralButton.style.opacity = '0.7';
        
        // Make the request with cache busting
        console.log('Making request to get_new_code.php');
        const timestamp = new Date().getTime();
        const response = await fetch(`get_new_code.php?current=${encodeURIComponent(currentCode)}&t=${timestamp}`);
        console.log('Got response:', response.status);
        
        const data = await response.json();
        console.log('Parsed response data:', data);
        
        if (data.success && data.code) {
            console.log('Updating display with new code:', data.code);
            // Remove existing fade-in-up class
            DOM.codeContainer.classList.remove('fade-in-up');
            
            // Update the display
            updateCodeDisplay(data.code);
            
            // Force reflow
            void DOM.codeContainer.offsetWidth;
            
            // Add animation class back
            DOM.codeContainer.classList.add('fade-in-up');
            
            showToast('New code fetched successfully!');
        } else {
            console.error('Response indicated failure:', data);
            throw new Error(data.message || 'Error fetching new code');
        }
    } catch (err) {
        console.error('Error in getNewCode:', err);
        DOM.codeContainer.innerHTML = '<p class="error-message">Error fetching new code. Please try again.</p>';
        showToast('Error fetching new code. Please try again.');
    } finally {
        DOM.referralButton.style.opacity = '1';
    }
}

function updateCodeDisplay(codeData) {
    if (!DOM.codeContainer || !DOM.referralButton || !codeData) return;

    try {
        // Update the referral button
        DOM.referralButton.href = `https://rivian.com/configurations/list?reprCode=${encodeURIComponent(codeData.referral_code)}`;
        DOM.referralButton.innerHTML = `Use ${escapeHtml(codeData.name)}'s Code`;
        
        // Update the code container
        const newHTML = `
            <span class="referral-code">${escapeHtml(codeData.referral_code)}</span>
            <button class="copy-button" onclick="copyCode('${escapeHtml(codeData.referral_code)}')" title="Copy code">
                <span>⧉</span> Copy Code
            </button>
        `;
        
        // Apply the new content
        DOM.codeContainer.innerHTML = newHTML;
        
        // Force a reflow to ensure animation plays
        void DOM.codeContainer.offsetWidth;
        
    } catch (error) {
        console.error('Error updating code display:', error);
        showToast('Error updating display. Please refresh the page.');
    }
}

// Helper function to escape HTML and prevent XSS
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
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