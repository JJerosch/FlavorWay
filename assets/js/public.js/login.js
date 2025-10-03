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

document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Entrando...';

    const formData = new FormData(this);

    try {
        const response = await fetch('../auth/login.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showAlert(data.message, 'success');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            showAlert(data.message, 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Entrar';
        }
    } catch (error) {
        showAlert('Erro ao conectar com o servidor. Tente novamente.', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Entrar';
    }
});