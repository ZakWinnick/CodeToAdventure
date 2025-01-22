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
// ==========================================================================
// Theme Management
// ==========================================================================
function initThemeToggle() {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;

    // Get body element
    const body = document.body;
    body.classList.add('dark-theme');  // Set default theme

    themeToggle.addEventListener('click', () => {
        if (body.classList.contains('dark-theme')) {
            body.classList.remove('dark-theme');
            body.classList.add('light-theme');
            themeToggle.textContent = '🌓 Dark Mode';
        } else {
            body.classList.remove('light-theme');
            body.classList.add('dark-theme');
            themeToggle.textContent = '🌓 Light Mode';
        }
    });
}

// Add to our DOMContentLoaded event
document.addEventListener('DOMContentLoaded', () => {
    DOM.init();
    initializeEventListeners();
    initializeAnimations();
    initThemeToggle();  // Initialize theme toggle
    
    // Set current year in footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();
});

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
}

function updateThemeIcon(theme) {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;
    
    themeToggle.innerHTML = theme === 'light' 
        ? `<svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
           </svg>`
        : `<svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
           </svg>`;
}

// Initialize theme and event listeners
document.addEventListener('DOMContentLoaded', () => {
    DOM.init();
    initializeEventListeners();
    initializeAnimations();
    initializeTheme();
    
    // Set current year in footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();
    
    // Add theme toggle listener
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
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