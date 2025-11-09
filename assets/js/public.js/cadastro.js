/**
 * FlavorWay - Cadastro
 * Gerencia o formulário de registro de novos usuários
 * Funções compartilhadas em: utils.js
 */

// Verificador de força da senha em tempo real
document.getElementById('password').addEventListener('input', function (e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    // Calcula força usando função compartilhada
    const result = calculatePasswordStrength(password);

    // Atualiza interface
    strengthBar.className = 'strength-fill ' + result.className;
    strengthText.textContent = result.text;
    strengthText.style.color = result.color;
});

// Submissão do formulário de cadastro
document.getElementById('cadastroForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    // Valida se as senhas coincidem
    if (password !== confirmPassword) {
        showAlert('As senhas não coincidem!', 'error');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    disableButton(submitBtn, 'Criando conta...');

    const formData = new FormData(this);

    try {
        const response = await fetch('../auth/register.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showAlert(data.message, 'success');
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 2000);
        } else {
            showAlert(data.message, 'error');
            enableButton(submitBtn);
        }
    } catch (error) {
        console.error('Erro capturado:', error);
        showAlert('Erro de rede ou conexão. Verifique sua conexão e tente novamente.', 'error');
        enableButton(submitBtn);
    }
});
