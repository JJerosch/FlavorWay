# Sistema de Receitas em Destaque

## Visão Geral

Este sistema permite que usuários autenticados adicionem receitas ao FlavorWay, que podem ser exibidas na seção "Receitas em Destaque" do index.

## Arquivos Criados/Modificados

### 1. Banco de Dados

**Arquivo:** `docs/update_receitas_table.sql` e `config/update-receitas-table.php`

Adiciona os seguintes campos à tabela `receitas`:
- `imagem` (VARCHAR 500): URL da imagem da receita
- `ingredientes` (TEXT): Lista de ingredientes
- `modo_preparo` (TEXT): Instruções de preparo
- `usuario_id` (INT): ID do usuário que criou a receita

**Como executar:**
```bash
php config/update-receitas-table.php
```

### 2. Página de Adicionar Receitas

**Arquivo:** `public/adicionar-receita.php`

Formulário completo para adicionar receitas com os seguintes campos:
- Nome da receita *
- Descrição *
- URL da imagem *
- Ingredientes *
- Modo de preparo *
- Tempo de preparo *
- Número de porções *
- Dificuldade (Básico, Intermediário, Avançado) *
- Região *
- Checkbox para marcar como destaque

**Recursos:**
- Validação de campos no frontend
- Mensagens de sucesso/erro
- Redirecionamento automático após 2 segundos
- Design responsivo e moderno
- Integrado com o header padrão do FlavorWay

### 3. API de Salvamento

**Arquivo:** `api/salvar-receita.php`

Endpoints para salvar receitas no banco de dados.

**Método:** POST

**Parâmetros:**
```json
{
  "nome": "string",
  "descricao": "string",
  "imagem": "url",
  "ingredientes": "text",
  "modo_preparo": "text",
  "tempo_preparo": "string",
  "pessoas": "string",
  "dificuldade": "Básico|Intermediário|Avançado",
  "regiao_id": "integer",
  "destaque": "0|1"
}
```

**Resposta de Sucesso:**
```json
{
  "success": true,
  "message": "Receita adicionada com sucesso!",
  "receita_id": 123
}
```

**Resposta de Erro:**
```json
{
  "success": false,
  "message": "Mensagem de erro"
}
```

**Validações:**
- Usuário autenticado
- Campos obrigatórios preenchidos
- URL da imagem válida
- Dificuldade válida
- Região existe no banco de dados

### 4. API de Busca de Receitas em Destaque

**Arquivo:** `api/get-receitas-destaque.php`

Endpoints para buscar receitas marcadas como destaque.

**Método:** GET

**Resposta de Sucesso:**
```json
{
  "success": true,
  "receitas": [
    {
      "id": 1,
      "nome": "Feijoada Completa",
      "descricao": "Descrição da receita",
      "culinaria": "Brasileira",
      "tempo": "3h",
      "dificuldade": "Intermediário",
      "rating": 4.9,
      "image": "https://exemplo.com/imagem.jpg"
    }
  ],
  "total": 12
}
```

**Lógica:**
1. Busca receitas com `destaque = 1`
2. Ordena por data de criação (mais recentes primeiro)
3. Limita a 12 receitas
4. Se não houver receitas em destaque, retorna as 12 mais recentes

### 5. JavaScript Atualizado

**Arquivo:** `assets/js/public.js/home-main.js`

**Alterações:**
- Removido array hardcoded de receitas
- Adicionada função `loadReceitasDestaque()` que busca receitas da API
- Modificada inicialização para carregar receitas antes de renderizar
- Mantido fallback para receitas de exemplo caso a API falhe

**Função principal:**
```javascript
async function loadReceitasDestaque() {
  try {
    const response = await fetch("../api/get-receitas-destaque.php")
    const data = await response.json()

    if (data.success && data.receitas.length > 0) {
      receitasDestaque = data.receitas
    } else {
      // Fallback para receitas de exemplo
    }
  } catch (error) {
    console.error("Erro ao carregar receitas:", error)
    // Fallback para receitas de exemplo
  }
}
```

### 6. Index.php Atualizado

**Arquivo:** `public/index.php`

**Alterações:**
- Adicionado link "Adicionar Receita" no menu de navegação
- Link acessível apenas para usuários autenticados

## Como Usar

### Para Usuários

1. **Acessar a página:**
   - Faça login no FlavorWay
   - Clique em "Adicionar Receita" no menu

2. **Preencher o formulário:**
   - Preencha todos os campos obrigatórios (marcados com *)
   - Cole a URL de uma imagem da receita
   - Marque "Marcar como receita em destaque" se desejar que apareça na home

3. **Enviar:**
   - Clique em "Adicionar Receita"
   - Aguarde a confirmação
   - Será redirecionado para a home em 2 segundos

### Para Desenvolvedores

1. **Configurar o banco de dados:**
```bash
php config/update-receitas-table.php
```

2. **Testar a API de busca:**
```bash
curl http://localhost/FlavorWay/api/get-receitas-destaque.php
```

3. **Testar a API de salvamento:**
```bash
curl -X POST http://localhost/FlavorWay/api/salvar-receita.php \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "nome=Teste&descricao=Descrição..."
```

## Fluxo do Sistema

```
┌─────────────────────┐
│  Usuário Autenticado │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ adicionar-receita.php│
│   (Formulário)       │
└──────────┬──────────┘
           │ POST
           ▼
┌─────────────────────┐
│ salvar-receita.php  │
│   (Validação e      │
│   Salvamento)       │
└──────────┬──────────┘
           │
           ▼
    ┌──────────┐
    │ Banco de │
    │  Dados   │
    └──────────┘
           │
           ▼
┌─────────────────────┐
│get-receitas-destaque│
│       .php          │
└──────────┬──────────┘
           │ JSON
           ▼
┌─────────────────────┐
│   home-main.js      │
│  (Renderização)     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│    index.php        │
│ (Receitas exibidas) │
└─────────────────────┘
```

## Segurança

- ✅ Validação de sessão (usuário autenticado)
- ✅ Prepared statements para prevenir SQL injection
- ✅ Validação de tipos de dados
- ✅ Sanitização de entrada
- ✅ Validação de URL de imagem
- ✅ Foreign keys para integridade referencial

## Melhorias Futuras

1. Upload de imagens em vez de URLs
2. Editor WYSIWYG para modo de preparo
3. Sistema de tags para receitas
4. Moderação de receitas antes de aparecerem em destaque
5. Avaliações e comentários nas receitas
6. Busca e filtros avançados
7. Página individual para cada receita
8. Sistema de favoritos

## Suporte

Para dúvidas ou problemas, entre em contato com a equipe de desenvolvimento do FlavorWay.
