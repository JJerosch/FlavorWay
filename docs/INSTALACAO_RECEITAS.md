# 🚀 Guia de Instalação - Sistema de Receitas em Destaque

## Passo a Passo para Fazer as Receitas Aparecerem no Index

### ✅ Passo 1: Atualizar a Tabela do Banco de Dados

Acesse no seu navegador:
```
http://localhost/FlavorWay/public/update-database.php
```

Este script irá:
- ✅ Adicionar o campo `imagem` na tabela receitas
- ✅ Adicionar o campo `ingredientes` na tabela receitas
- ✅ Adicionar o campo `modo_preparo` na tabela receitas
- ✅ Adicionar o campo `usuario_id` na tabela receitas
- ✅ Criar índices para melhor performance

**Resultado esperado:**
```
✓ Campo 'imagem' adicionado com sucesso!
✓ Campo 'ingredientes' adicionado com sucesso!
✓ Campo 'modo_preparo' adicionado com sucesso!
✓ Campo 'usuario_id' adicionado com sucesso!
✓ Foreign key 'fk_receitas_usuario' adicionada com sucesso!
✅ Atualização concluída com sucesso!
```

---

### ✅ Passo 2: Popular Imagens nas Receitas Existentes

Acesse no seu navegador:
```
http://localhost/FlavorWay/public/populate-images.php
```

Este script irá:
- 🖼️ Adicionar imagens placeholder em todas as 25 receitas existentes
- 🖼️ Assim elas aparecerão imediatamente no index

**Resultado esperado:**
```
✓ Receita #1 - Acarajé
✓ Receita #2 - Moqueca Baiana
...
✅ 25 receitas atualizadas!
```

---

### ✅ Passo 3: Verificar se Está Funcionando

1. **Acesse o index:**
   ```
   http://localhost/FlavorWay/public/index.php
   ```

2. **Role até a seção "Receitas em Destaque"**
   - Você deve ver as 10 receitas marcadas como destaque
   - Cada uma com sua imagem placeholder

3. **Abra o Console do Navegador (F12)**
   - Procure por: `"✅ Receitas carregadas do banco: 10"`
   - Isso confirma que está carregando do banco de dados!

---

### ✅ Passo 4: Adicionar uma Receita Nova (Opcional)

Para testar o formulário de adicionar receitas:

1. **Acesse:**
   ```
   http://localhost/FlavorWay/public/adicionar-receita.php
   ```

2. **Preencha o formulário:**
   - Nome: "Minha Receita Especial"
   - Descrição: "Uma receita deliciosa"
   - URL da Imagem: `https://via.placeholder.com/280x180/FF6B35/FFFFFF?text=Minha+Receita`
   - Ingredientes: "Lista de ingredientes"
   - Modo de Preparo: "Passo a passo"
   - Tempo de Preparo: "30 min"
   - Porções: "4 pessoas"
   - Dificuldade: "Intermediário"
   - Região: Escolha uma região
   - ✅ **MARQUE** a checkbox "Marcar como receita em destaque"

3. **Clique em "Adicionar Receita"**

4. **Acesse o index novamente** e veja sua receita aparecendo em destaque!

---

## 🔍 Verificação e Testes

### Testar a API Diretamente

Acesse no navegador:
```
http://localhost/FlavorWay/api/get-receitas-destaque.php
```

Você deve ver um JSON com as receitas:
```json
{
  "success": true,
  "receitas": [
    {
      "id": 1,
      "nome": "Acarajé",
      "culinaria": "Nordeste",
      "tempo": "40 min",
      "dificuldade": "Intermediário",
      "rating": 4.8,
      "image": "/placeholder.svg?height=180&width=280&text=Acaraj%C3%A9"
    },
    ...
  ],
  "total": 10,
  "debug": {
    "has_imagem_field": true,
    "total_db": 10
  }
}
```

### Verificar a Estrutura do Banco

Acesse:
```
http://localhost/FlavorWay/api/test-api.php
```

Você deve ver:
```
✅ Campo 'imagem' existe
✅ Campo 'ingredientes' existe
✅ Campo 'modo_preparo' existe
✅ Campo 'usuario_id' existe
```

---

## 🎯 Ordem de Execução (Resumo)

```
1. update-database.php    → Adiciona campos na tabela
2. populate-images.php    → Adiciona imagens nas receitas existentes
3. index.php              → Veja as receitas em destaque!
```

---

## ❌ Problemas Comuns

### Erro: "Campo já existe"
**Solução:** Isso é normal se você executar o script mais de uma vez. Ignore.

### Erro: "Connection error"
**Solução:** Verifique se o MySQL está rodando e as credenciais em `config/database.php`

### Receitas não aparecem no index
**Solução:**
1. Verifique o Console do navegador (F12)
2. Veja se há erros na API: `api/get-receitas-destaque.php`
3. Confirme que há receitas marcadas como destaque no banco

### Imagens não aparecem
**Solução:**
1. Execute `populate-images.php` novamente
2. Ou adicione URLs reais ao criar receitas novas

---

## 📚 Arquivos Importantes

- `public/update-database.php` - Atualiza estrutura do banco
- `public/populate-images.php` - Popula imagens nas receitas
- `api/test-api.php` - Testa a API e o banco
- `api/get-receitas-destaque.php` - API que retorna receitas
- `public/adicionar-receita.php` - Formulário de adicionar receitas
- `public/index.php` - Página principal

---

## ✅ Checklist Final

- [ ] Executei `update-database.php`
- [ ] Executei `populate-images.php`
- [ ] Abri `index.php` e vi as receitas em destaque
- [ ] Verifiquei o Console (F12) e vi "✅ Receitas carregadas do banco: 10"
- [ ] Testei adicionar uma receita nova
- [ ] A receita nova apareceu no index

---

**Pronto! Seu sistema de receitas em destaque está funcionando! 🎉**
