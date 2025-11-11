# FlavorWay - Documentação da API

## 📋 Índice
- [Visão Geral](#visão-geral)
- [APIs de Regiões](#apis-de-regiões)
- [APIs de Receitas](#apis-de-receitas)
- [APIs de Avaliações](#apis-de-avaliações)
- [APIs de Favoritos](#apis-de-favoritos)
- [Autenticação](#autenticação)
- [Códigos de Status](#códigos-de-status)

## Visão Geral

Todas as APIs retornam dados em formato JSON com charset UTF-8.

**Base URL**: `/api/`

**Formato de resposta**:
```json
{
  "campo1": "valor1",
  "campo2": "valor2"
}
```

**Formato de erro**:
```json
{
  "error": "Mensagem de erro",
  "message": "Detalhes adicionais (opcional)"
}
```

---

## APIs de Regiões

### GET /api/regions.php

Busca informações sobre regiões brasileiras.

#### Parâmetros

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `slug` | string | Não | Slug da região (ex: "nordeste") |
| `id` | integer | Não | ID da região |

#### Exemplos

**Listar todas as regiões:**
```
GET /api/regions.php
```

**Buscar região por slug:**
```
GET /api/regions.php?slug=nordeste
```

**Buscar região por ID:**
```
GET /api/regions.php?id=1
```

#### Resposta

```json
{
  "id": 1,
  "nome": "Nordeste",
  "slug": "nordeste",
  "descricao": "Sabores intensos e temperos marcantes",
  "ordem": 1,
  "ativo": 1,
  "total_receitas": 5,
  "estados": [
    {
      "id": 1,
      "regiao_id": 1,
      "nome": "Bahia",
      "slug": "bahia",
      "capital": "Salvador",
      "descricao": "Berço da culinária afro-brasileira",
      "ingrediente_destaque": "Azeite de dendê"
    }
  ],
  "cultura": [
    {
      "id": 1,
      "regiao_id": 1,
      "titulo": "Influência Africana",
      "descricao": "O dendê e os temperos marcantes vieram com os africanos",
      "icon": "🌍",
      "tipo": "influencia"
    }
  ],
  "tecnicas": [
    {
      "id": 1,
      "nome": "Fritura por Imersão",
      "descricao": "Técnica de fritar alimentos completamente submersos em óleo quente",
      "dificuldades_tecnica": "Intermediário"
    }
  ]
}
```

---

## APIs de Receitas

### GET /api/recipes.php

Busca receitas com diversos filtros.

#### Parâmetros

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `id` | integer | Não | ID da receita (retorna detalhes completos) |
| `regiao_id` | integer | Não | Filtrar por ID da região |
| `regiao_slug` | string | Não | Filtrar por slug da região |
| `limit` | integer | Não | Número de resultados (padrão: 20) |
| `offset` | integer | Não | Paginação (padrão: 0) |

#### Exemplos

**Listar todas as receitas:**
```
GET /api/recipes.php
```

**Buscar receita por ID:**
```
GET /api/recipes.php?id=1
```

**Buscar receitas de uma região (por slug):**
```
GET /api/recipes.php?regiao_slug=nordeste
```

**Buscar receitas com paginação:**
```
GET /api/recipes.php?limit=10&offset=20
```

#### Resposta (lista)

```json
[
  {
    "id": 1,
    "nome": "Acarajé",
    "descricao": "Bolinho de feijão-fradinho frito no dendê...",
    "tempo_preparo": "40 min",
    "pessoas": "4-6 porções",
    "rating": 4.8,
    "dificuldade": "Intermediário",
    "regiao": "Nordeste",
    "regiao_id": 1,
    "regiao_nome": "Nordeste",
    "regiao_slug": "nordeste",
    "destaque": 1,
    "badge": "Tradicional",
    "total_avaliacoes": 12,
    "media_avaliacoes": 4.8
  }
]
```

#### Resposta (detalhes - com ID)

```json
{
  "id": 1,
  "nome": "Acarajé",
  "descricao": "Bolinho de feijão-fradinho frito no dendê...",
  "tempo_preparo": "40 min",
  "tempo_cozimento": "30 min",
  "pessoas": "4-6 porções",
  "rendimento": "12 unidades",
  "rating": 4.8,
  "dificuldade": "Intermediário",
  "regiao": "Nordeste",
  "regiao_id": 1,
  "regiao_nome": "Nordeste",
  "regiao_slug": "nordeste",
  "calorias": "280 kcal",
  "proteinas": "12g",
  "carboidratos": "25g",
  "gorduras": "15g",
  "total_avaliacoes": 12,
  "media_avaliacoes": 4.8,
  "ingredientes": [
    {
      "id": 1,
      "receita_id": 1,
      "nome": "Feijão-fradinho",
      "categoria": "Grãos"
    }
  ],
  "tags": [
    {
      "id": 1,
      "nome": "Tradicional"
    }
  ]
}
```

---

## APIs de Avaliações

### GET /api/ratings.php

Busca avaliações de uma receita.

#### Parâmetros

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `receita_id` | integer | **Sim** | ID da receita |
| `limit` | integer | Não | Número de avaliações (padrão: 10) |
| `offset` | integer | Não | Paginação (padrão: 0) |

#### Exemplo

```
GET /api/ratings.php?receita_id=1
```

#### Resposta

```json
{
  "avaliacoes": [
    {
      "id": 1,
      "usuario_id": 4,
      "receita_id": 1,
      "nota": 5,
      "comentario": "Acarajé perfeito! Ficou crocante e muito saboroso.",
      "data_criacao": "2025-11-08 10:30:00",
      "usuario_nome": "Maria Silva",
      "usuario_avatar": null
    }
  ],
  "resumo": {
    "total": 12,
    "media": 4.8,
    "cinco_estrelas": 10,
    "quatro_estrelas": 2,
    "tres_estrelas": 0,
    "duas_estrelas": 0,
    "uma_estrela": 0
  }
}
```

### POST /api/ratings.php

Criar ou atualizar avaliação (requer autenticação).

#### Body (JSON)

```json
{
  "receita_id": 1,
  "nota": 5,
  "comentario": "Receita maravilhosa!"
}
```

#### Resposta

```json
{
  "success": true,
  "message": "Avaliação criada com sucesso"
}
```

### DELETE /api/ratings.php

Remover avaliação (requer autenticação).

#### Body (JSON)

```json
{
  "receita_id": 1
}
```

#### Resposta

```json
{
  "success": true,
  "message": "Avaliação removida com sucesso"
}
```

---

## APIs de Favoritos

**⚠️ Todas as operações de favoritos requerem autenticação.**

### GET /api/favorites.php

Busca favoritos do usuário autenticado.

#### Parâmetros

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `receita_id` | integer | Não | Verificar se receita específica está favoritada |

#### Exemplos

**Listar todos os favoritos:**
```
GET /api/favorites.php
```

**Verificar se receita está favoritada:**
```
GET /api/favorites.php?receita_id=1
```

#### Resposta (lista)

```json
[
  {
    "id": 1,
    "nome": "Acarajé",
    "descricao": "Bolinho de feijão-fradinho...",
    "regiao_nome": "Nordeste",
    "regiao_slug": "nordeste",
    "favoritado_em": "2025-11-08 14:20:00"
  }
]
```

#### Resposta (verificação)

```json
{
  "is_favorite": true
}
```

### POST /api/favorites.php

Adicionar receita aos favoritos.

#### Body (JSON)

```json
{
  "receita_id": 1
}
```

#### Resposta

```json
{
  "success": true,
  "message": "Receita adicionada aos favoritos"
}
```

### DELETE /api/favorites.php

Remover receita dos favoritos.

#### Body (JSON)

```json
{
  "receita_id": 1
}
```

#### Resposta

```json
{
  "success": true,
  "message": "Receita removida dos favoritos"
}
```

---

## Autenticação

As seguintes APIs requerem autenticação via sessão PHP:

- **POST** `/api/ratings.php` - Criar/atualizar avaliação
- **DELETE** `/api/ratings.php` - Remover avaliação
- **GET** `/api/favorites.php` - Listar favoritos
- **POST** `/api/favorites.php` - Adicionar favorito
- **DELETE** `/api/favorites.php` - Remover favorito

### Verificação de Autenticação

O sistema verifica se `$_SESSION['user_id']` está definido.

### Erro de Autenticação

```json
{
  "error": "Usuário não autenticado"
}
```

**Status HTTP**: `401 Unauthorized`

---

## Códigos de Status

| Código | Descrição |
|--------|-----------|
| `200` | OK - Requisição bem-sucedida |
| `400` | Bad Request - Parâmetros inválidos ou faltando |
| `401` | Unauthorized - Autenticação necessária |
| `403` | Forbidden - Sem permissão |
| `404` | Not Found - Recurso não encontrado |
| `405` | Method Not Allowed - Método HTTP não permitido |
| `500` | Internal Server Error - Erro no servidor |

---

## Exemplos de Uso com JavaScript

### Buscar região e suas receitas

```javascript
// Buscar dados da região
const response = await fetch('/api/regions.php?slug=nordeste');
const region = await response.json();

// Buscar receitas da região
const recipesResponse = await fetch(`/api/recipes.php?regiao_slug=${region.slug}`);
const recipes = await recipesResponse.json();

console.log(`${region.nome}: ${recipes.length} receitas`);
```

### Adicionar avaliação

```javascript
const response = await fetch('/api/ratings.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    receita_id: 1,
    nota: 5,
    comentario: 'Receita excelente!'
  })
});

const data = await response.json();

if (response.ok) {
  console.log(data.message);
} else {
  console.error(data.error);
}
```

### Favoritar receita

```javascript
const response = await fetch('/api/favorites.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    receita_id: 1
  })
});

const data = await response.json();
console.log(data.message);
```

---

## Notas Importantes

1. **Encoding**: Todas as respostas usam UTF-8
2. **CORS**: Configure CORS se necessário para acesso de domínios diferentes
3. **Rate Limiting**: Considere implementar rate limiting em produção
4. **Validação**: Todos os inputs são validados e sanitizados
5. **SQL Injection**: Proteção via prepared statements (PDO)
6. **XSS**: Use `htmlspecialchars()` ao exibir dados no frontend

---

**Última atualização**: 2025-11-11
**Versão da API**: 1.0
