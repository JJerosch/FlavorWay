/**
 * FlavorWay - Utilidades JavaScript Compartilhadas
 * Funções reutilizáveis em todo o site
 */

/**
 * Alterna visibilidade da senha
 * @param {string} inputId - ID do campo de senha
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
 * Exibe mensagem de alerta para o usuário
 * @param {string} message - Mensagem a ser exibida
 * @param {string} type - Tipo do alerta ('error' ou 'success')
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
 * Calcula a força de uma senha
 * @param {string} password - Senha a ser verificada
 * @returns {Object} - {strength: number, text: string, color: string}
 */
function calculatePasswordStrength(password) {
    let strength = 0;

    // Critérios de força
    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    // Retorna resultado baseado na força
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
 * Desabilita botão durante envio de formulário
 * @param {HTMLElement} button - Botão a ser desabilitado
 * @param {string} loadingText - Texto a exibir durante carregamento
 */
function disableButton(button, loadingText) {
    button.disabled = true;
    button.dataset.originalText = button.innerHTML;
    button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${loadingText}`;
}

/**
 * Reabilita botão após envio de formulário
 * @param {HTMLElement} button - Botão a ser reabilitado
 */
function enableButton(button) {
    button.disabled = false;
    button.innerHTML = button.dataset.originalText || button.innerHTML;
}
