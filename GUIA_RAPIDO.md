# 📖 FlavorWay - Guia Rápido

## 🗂️ Estrutura Simplificada do Projeto

```
FlavorWay/
├── config/
│   ├── database.php        → Conexão com banco (PDO)
│   ├── helpers.php         → ⭐ NOVO: Funções reutilizáveis PHP
│   └── createtable.php     → Script de criação das tabelas
│
├── auth/
│   ├── login.php           → ✅ Simplificado: Processa login
│   ├── register.php        → ✅ Simplificado: Processa cadastro
│   ├── logout.php          → Logout e destruição de sessão
│   └── session.php         → Gerenciamento de sessão
│
├── public/
│   ├── login.php           → Página de login
│   ├── cadastro.php        → Página de cadastro
│   ├── index.php           → Dashboard (requer login)
│   └── culinaria-brasileira.php
│
├── admin/
│   ├── gerenciar-usuarios.php   → Gestão de usuários (admin only)
│   └── gerenciar-regioes.php    → Gestão de conteúdo (admin only)
│
└── assets/
    ├── js/public.js/
    │   ├── utils.js        → ⭐ NOVO: Funções compartilhadas JS
    │   ├── login.js        → ✅ Simplificado
    │   └── cadastro.js     → ✅ Simplificado
    └── css/...
```

---

## 🚀 Como Começar

### 1. Configurar Banco de Dados
```bash
# Acessar: http://localhost/FlavorWay/config/createtable.php
# Isso criará automaticamente:
# - Banco de dados 'flavor_way'
# - 17 tabelas com relacionamentos
```

### 2. Criar Primeiro Usuário Admin (via SQL)
```sql
-- Execute no phpMyAdmin ou MySQL Workbench
USE flavor_way;

-- Criar usuário
INSERT INTO usuarios (nome, username, email, senha, ativo)
VALUES ('Admin', 'admin', 'admin@flavorway.com',
        '$2y$10$exemplo_hash_aqui', 1);

-- Tornar administrador (pegue o ID gerado acima)
INSERT INTO administradores (usuario_id, nivel)
VALUES (1, 'super_admin');
```

### 3. Fazer Login
```
URL: http://localhost/FlavorWay/public/login.php
Email: admin@flavorway.com
Senha: [sua senha]
```

---

## 💻 Exemplos de Código

### JavaScript - Usando Funções Compartilhadas

```html
<!-- Incluir utils.js ANTES dos scripts específicos -->
<script src="../assets/js/public.js/utils.js"></script>
<script src="../assets/js/public.js/login.js"></script>
```

```javascript
// Alternar visibilidade de senha
togglePassword('password');

// Mostrar alerta
showAlert('Cadastro realizado com sucesso!', 'success');
showAlert('Erro ao processar', 'error');

// Verificar força da senha
const result = calculatePasswordStrength('Senha123!');
// result = {strength: 5, className: 'strength-strong', text: 'Senha forte', color: '#16a34a'}

// Desabilitar botão durante envio
const btn = document.getElementById('submitBtn');
disableButton(btn, 'Enviando...');

// Reabilitar botão
enableButton(btn);
```

---

### PHP - Usando Funções Helper

```php
<?php
require_once '../config/database.php';
require_once '../config/helpers.php';

// ========================================
// VALIDAÇÃO
// ========================================

// Validar campos obrigatórios
$validation = validateRequiredFields([
    'nome' => $nome,
    'email' => $email,
    'senha' => $senha
]);

if (!$validation['valid']) {
    jsonResponse(false, 'Preencha todos os campos');
}

// Validar email
if (!isValidEmail($email)) {
    jsonResponse(false, 'Email inválido');
}

// Validar força da senha
$passwordCheck = validatePasswordStrength($senha);
if (!$passwordCheck['valid']) {
    jsonResponse(false, $passwordCheck['message']);
}

// ========================================
// SEGURANÇA
// ========================================

// Sanitizar entrada (previne XSS)
$nome = sanitizeInput($_POST['nome']);

// Hash de senha
$hash = hashPassword($senha);

// Verificar senha
if (verifyPassword($senhaDigitada, $hashDoBanco)) {
    // Senha correta
}

// ========================================
// AUTENTICAÇÃO
// ========================================

// Verificar se está logado
if (isLoggedIn()) {
    // Usuário autenticado
}

// Verificar se é admin
if (isAdmin()) {
    // É administrador
}

// Proteger rota (redireciona se não logado)
requireLogin();

// Proteger rota admin (redireciona se não for admin)
requireAdmin();

// ========================================
// BANCO DE DADOS
// ========================================

// Verificar email duplicado
if (emailExists($pdo, $email)) {
    jsonResponse(false, 'Email já cadastrado');
}

// Verificar username duplicado
if (usernameExists($pdo, $username)) {
    jsonResponse(false, 'Username em uso');
}

// Buscar usuário por email ou username
$user = findUserByEmailOrUsername($pdo, $emailOrUsername);

// Atualizar último acesso
updateLastAccess($pdo, $userId);

// ========================================
// RESPOSTA JSON
// ========================================

// Resposta de sucesso
jsonResponse(true, 'Operação realizada com sucesso!');

// Resposta de erro
jsonResponse(false, 'Algo deu errado');

// Resposta com dados adicionais
jsonResponse(true, 'Login bem-sucedido', [
    'redirect' => '../admin/dashboard.php',
    'user_id' => 123
]);
?>
```

---

## 🔒 Proteção de Rotas

### Página Pública (requer login)
```php
<?php
session_start();
require_once '../config/helpers.php';
requireLogin(); // Redireciona para login se não autenticado
?>
```

### Página Admin (requer admin)
```php
<?php
session_start();
require_once '../config/helpers.php';
requireAdmin(); // Redireciona se não for admin
?>
```

### Verificação Manual
```php
<?php
session_start();

// Jeito antigo (ainda funciona)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Jeito novo (mais limpo)
require_once '../config/helpers.php';
requireLogin();
?>
```

---

## 📝 Criando Novos Formulários

### Frontend (HTML)
```html
<form id="meuForm">
    <input type="email" id="email" name="email" required>
    <input type="password" id="senha" name="senha" required>
    <button type="submit" id="submitBtn">Enviar</button>
</form>

<div id="alertContainer"></div>

<script src="../assets/js/public.js/utils.js"></script>
<script>
document.getElementById('meuForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn = document.getElementById('submitBtn');
    disableButton(btn, 'Enviando...');

    const formData = new FormData(this);

    try {
        const response = await fetch('../auth/meu_endpoint.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message, 'error');
            enableButton(btn);
        }
    } catch (error) {
        showAlert('Erro de conexão', 'error');
        enableButton(btn);
    }
});
</script>
```

### Backend (PHP)
```php
<?php
session_start();
require_once '../config/database.php';
require_once '../config/helpers.php';

// Aceitar apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Método não permitido');
}

// Capturar e sanitizar
$email = sanitizeInput($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Validar
$validation = validateRequiredFields([
    'email' => $email,
    'senha' => $senha
]);

if (!$validation['valid']) {
    jsonResponse(false, 'Preencha todos os campos');
}

if (!isValidEmail($email)) {
    jsonResponse(false, 'Email inválido');
}

// Processar
try {
    // Sua lógica aqui
    jsonResponse(true, 'Sucesso!');
} catch (Exception $e) {
    jsonResponse(false, 'Erro no servidor');
}
?>
```

---

## 🎨 Sistema de Alertas

```javascript
// Alerta de sucesso (verde)
showAlert('Operação realizada com sucesso!', 'success');

// Alerta de erro (vermelho)
showAlert('Algo deu errado', 'error');

// Alerta personalizado (padrão: erro)
showAlert('Mensagem genérica');
```

O HTML já precisa ter o container:
```html
<div id="alertContainer"></div>
```

---

## 📚 Referência Rápida de Funções

### JavaScript (`utils.js`)
| Função | Parâmetros | Retorno |
|--------|-----------|---------|
| `togglePassword(inputId)` | ID do input | void |
| `showAlert(message, type)` | mensagem, 'success'/'error' | void |
| `calculatePasswordStrength(password)` | senha | {strength, className, text, color} |
| `disableButton(button, loadingText)` | elemento, texto | void |
| `enableButton(button)` | elemento | void |

### PHP (`helpers.php`)
| Função | Uso |
|--------|-----|
| `jsonResponse($success, $message, $data)` | Retorna JSON e encerra |
| `validateRequiredFields($fields)` | Valida array de campos |
| `isValidEmail($email)` | Retorna bool |
| `validatePasswordStrength($password)` | Retorna array |
| `hashPassword($password)` | Retorna hash |
| `verifyPassword($password, $hash)` | Retorna bool |
| `isLoggedIn()` | Retorna bool |
| `isAdmin()` | Retorna bool |
| `requireLogin()` | Redireciona se não logado |
| `requireAdmin()` | Redireciona se não admin |
| `sanitizeInput($data)` | Retorna string limpa |
| `emailExists($pdo, $email)` | Retorna bool |
| `usernameExists($pdo, $username)` | Retorna bool |
| `findUserByEmailOrUsername($pdo, $value)` | Retorna array ou false |
| `updateLastAccess($pdo, $userId)` | void |

---

## 🔍 Solução de Problemas

### Erro: "Call to undefined function"
```
Solução: Adicione require_once '../config/helpers.php';
```

### Erro: "utils.js functions not found"
```
Solução: Incluir <script src="../assets/js/public.js/utils.js"></script>
         ANTES dos outros scripts
```

### Login não funciona
```
1. Verificar se o banco foi criado (createtable.php)
2. Verificar credenciais em config/database.php
3. Verificar se o hash da senha está correto
4. Conferir console do navegador (F12) para erros JS
```

---

## 📞 Suporte

Para dúvidas sobre a estrutura simplificada, consulte:
- `CHANGELOG.md` - Lista completa de mudanças
- `config/helpers.php` - Documentação inline de todas as funções
- `assets/js/public.js/utils.js` - Documentação das funções JS

---

**Última atualização:** 09/11/2025
