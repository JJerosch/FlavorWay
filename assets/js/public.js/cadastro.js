/**
 * FlavorWay - Register
 * Manages the registration form for new users
 * Shared functions in: utils.js
 */

// Real-time password strength checker
document.getElementById('password').addEventListener('input', function (e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    // Calculate strength using shared function
    const result = calculatePasswordStrength(password);

    // Update interface
    strengthBar.className = 'strength-fill ' + result.className;
    strengthText.textContent = result.text;
    strengthText.style.color = result.color;
});

// Register form submission
document.getElementById('cadastroForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    // Validate if passwords match
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
        console.error('Error caught:', error);
        showAlert('Erro de rede ou conexão. Verifique sua conexão e tente novamente.', 'error');
        enableButton(submitBtn);
    }
});
