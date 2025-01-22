// Performance optimization: Use requestIdleCallback for non-critical operations
const CONFIG = {
    ANIMATION_DURATION: 300,
    TOAST_DURATION: 3000,
    API_ENDPOINTS: {
        NEW_CODE: 'get_new_code.php',
        STORE_CODE: 'store_code.php'
    },
    RATE_LIMIT: {
        MAX_REQUESTS: 5,
        TIME_WINDOW: 60000 // 1 minute
    }
};

// Rate limiting implementation
class RateLimiter {
    constructor(maxRequests, timeWindow) {
        this.requests = [];
        this.maxRequests = maxRequests;
        this.timeWindow = timeWindow;
    }

    canMakeRequest() {
        const now = Date.now();
        this.requests = this.requests.filter(time => now - time < this.timeWindow);
        return this.requests.length < this.maxRequests;
    }

    addRequest() {
        this.requests.push(Date.now());
    }
}

const rateLimiter = new RateLimiter(CONFIG.RATE_LIMIT.MAX_REQUESTS, CONFIG.RATE_LIMIT.TIME_WINDOW);

// Enhanced DOM management with error boundaries
class DOMManager {
    static init() {
        try {
            this.mainContent = document.querySelector('.main-content');
            this.modal = document.getElementById('submitModal');
            this.toast = document.getElementById('toast');
            this.bindKeyboardEvents();
        } catch (error) {
            console.error('DOM initialization failed:', error);
            this.showErrorMessage('Application initialization failed. Please refresh.');
        }
    }

    static bindKeyboardEvents() {
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && this.modal?.style.display === 'block') {
                closeModal();
            }
        });
    }

    static showErrorMessage(message) {
        const errorContainer = document.createElement('div');
        errorContainer.className = 'error-message';
        errorContainer.setAttribute('role', 'alert');
        errorContainer.textContent = message;
        document.body.appendChild(errorContainer);
    }
}

// Enhanced form handling with validation
class FormValidator {
    static validateReferralCode(code) {
        const codeRegex = /^[A-Z]{3}\d{7}$/;
        if (!codeRegex.test(code)) {
            throw new Error('Invalid referral code format');
        }
        return true;
    }

    static sanitizeInput(input) {
        return input.trim().replace(/[<>]/g, '');
    }
}

// Enhanced API calls with retry mechanism
async function fetchWithRetry(url, options, retries = 3) {
    for (let i = 0; i < retries; i++) {
        try {
            if (!rateLimiter.canMakeRequest()) {
                throw new Error('Rate limit exceeded. Please try again later.');
            }
            rateLimiter.addRequest();
            
            const response = await fetch(url, {
                ...options,
                headers: {
                    ...options?.headers,
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            if (i === retries - 1) throw error;
            await new Promise(resolve => setTimeout(resolve, 1000 * Math.pow(2, i)));
        }
    }
}

// Enhanced code copying with fallback
async function copyCode(code) {
    try {
        if (!navigator.clipboard) {
            throw new Error('Clipboard API not available');
        }
        await navigator.clipboard.writeText(code);
        showToast('Code copied successfully!');
    } catch (error) {
        const textArea = document.createElement('textarea');
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showToast('Code copied successfully!');
        } catch (err) {
            showToast('Failed to copy code. Please try manually selecting it.');
        }
        document.body.removeChild(textArea);
    }
}

// Performance optimized toast notifications
const showToast = (() => {
    let timeout;
    return message => {
        if (!DOMManager.toast) return;
        
        clearTimeout(timeout);
        DOMManager.toast.textContent = message;
        DOMManager.toast.setAttribute('aria-hidden', 'false');
        DOMManager.toast.style.display = 'block';
        
        timeout = setTimeout(() => {
            DOMManager.toast.style.display = 'none';
            DOMManager.toast.setAttribute('aria-hidden', 'true');
        }, CONFIG.TOAST_DURATION);
    };
})();

// Initialize on load with error boundary
window.addEventListener('load', () => {
    try {
        DOMManager.init();
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .catch(error => console.error('ServiceWorker registration failed:', error));
        }
    } catch (error) {
        console.error('Initialization failed:', error);
    }
});