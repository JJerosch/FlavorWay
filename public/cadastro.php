<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/cadastro.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <a href="index.php" class="back-home">
                Voltar ao Início
            </a>
            <h1>Criar Conta</h1>
            <p>Comece sua jornada culinária hoje!</p>
        </div>

        <div class="auth-body">
            <div id="alertContainer"></div>

            <form id="cadastroForm">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            class="form-control" 
                            placeholder="João Silva"
                            required
                            minlength="3"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Nome de Usuário</label>
                    <div class="input-wrapper">
                        <i class="fas fa-at"></i>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-control" 
                            placeholder="joaosilva"
                            required
                            minlength="3"
                            pattern="[a-zA-Z0-9_]+"
                            title="Apenas letras, números e underscore"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="seu@email.com"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="••••••••"
                            required
                            minlength="6"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthBar"></div>
                        </div>
                        <span id="strengthText"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar Senha</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="form-control" 
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye" id="confirm_password-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn" id="submitBtn">
                    <i class="fas fa-user-plus"></i>
                    Criar Conta
                </button>
            </form>
            <div class="auth-footer">
                <p>Já tem uma conta? <a href="login.php">Faça login aqui</a></p>
            </div>
        </div>
    </div>
    <script src= "../assets/js/public.js/cadastro.js"></script>
</body>
</html>