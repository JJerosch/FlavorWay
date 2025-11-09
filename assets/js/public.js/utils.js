/**
 * FlavorWay - Shared JavaScript Utilities
 * Reusable functions across the entire site
 */

/**
 * Toggles password visibility
 * @param {string} inputId - Password field ID
 */
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

/**
 * Displays alert message to user
 * @param {string} message - Message to display
 * @param {string} type - Alert type ('error' or 'success')
 */
function showAlert(message, type = 'error') {
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = `
        <div class="alert alert-${type}">
            <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
            ${message}
        </div>
    `;
}

/**
 * Calculates password strength
 * @param {string} password - Password to verify
 * @returns {Object} - {strength: number, className: string, text: string, color: string}
 */
function calculatePasswordStrength(password) {
    let strength = 0;

    // Strength criteria
    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    // Return result based on strength
    if (strength <= 2) {
        return {
            strength: strength,
            className: 'strength-weak',
            text: 'Senha fraca',
            color: '#dc2626'
        };
    } else if (strength <= 4) {
        return {
            strength: strength,
            className: 'strength-medium',
            text: 'Senha média',
            color: '#eab308'
        };
    } else {
        return {
            strength: strength,
            className: 'strength-strong',
            text: 'Senha forte',
            color: '#16a34a'
        };
    }
}

/**
 * Disables button during form submission
 * @param {HTMLElement} button - Button to disable
 * @param {string} loadingText - Text to display during loading
 */
function disableButton(button, loadingText) {
    button.disabled = true;
    button.dataset.originalText = button.innerHTML;
    button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${loadingText}`;
}

/**
 * Re-enables button after form submission
 * @param {HTMLElement} button - Button to re-enable
 */
function enableButton(button) {
    button.disabled = false;
    button.innerHTML = button.dataset.originalText || button.innerHTML;
}
