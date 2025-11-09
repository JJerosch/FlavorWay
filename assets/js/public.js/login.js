/**
 * FlavorWay - Login
 * Gerencia o formulário de autenticação
 * Funções compartilhadas em: utils.js
 */

document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    disableButton(submitBtn, 'Entrando...');

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
                window.location.href = data.data.redirect;
            }, 1000);
        } else {
            showAlert(data.message, 'error');
            enableButton(submitBtn);
        }
    } catch (error) {
        showAlert('Erro ao conectar com o servidor. Tente novamente.', 'error');
        enableButton(submitBtn);
    }
});
