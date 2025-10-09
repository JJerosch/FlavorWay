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

function showAlert(message, type = 'error') {
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = `
                <div class="alert alert-${type}">
                    <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
                    ${message}
                </div>
            `;
}

// Verificar força da senha
document.getElementById('password').addEventListener('input', function (e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    strengthBar.className = 'strength-fill';

    if (strength <= 2) {
        strengthBar.classList.add('strength-weak');
        strengthText.textContent = 'Senha fraca';
        strengthText.style.color = '#dc2626';
    } else if (strength <= 4) {
        strengthBar.classList.add('strength-medium');
        strengthText.textContent = 'Senha média';
        strengthText.style.color = '#eab308';
    } else {
        strengthBar.classList.add('strength-strong');
        strengthText.textContent = 'Senha forte';
        strengthText.style.color = '#16a34a';
    }
});

document.getElementById('cadastroForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (password !== confirmPassword) {
        showAlert('As senhas não coincidem!', 'error');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Criando conta...';

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
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> Criar Conta';
        }
    // ...
} catch (networkError) {
    // A LINHA MAIS IMPORTANTE PARA O DEBUG:
    console.error('ERRO CAPTURADO PELO CATCH:', networkError);

    showAlert('Erro de rede ou conexão. Verifique o console (F12) para detalhes.', 'error');

    // Reativa o botão para o usuário poder tentar de novo
    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> Criar Conta';
}
});