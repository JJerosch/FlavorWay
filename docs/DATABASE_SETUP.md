# FlavorWay - Configuração do Banco de Dados

## 📋 Índice
- [Pré-requisitos](#pré-requisitos)
- [Criação do Banco de Dados](#criação-do-banco-de-dados)
- [População com Dados](#população-com-dados)
- [Dados Inseridos](#dados-inseridos)
- [Credenciais de Acesso](#credenciais-de-acesso)
- [Segurança](#segurança)

## 🔧 Pré-requisitos

Antes de começar, certifique-se de ter:

- **XAMPP/WAMP/MAMP** instalado e rodando
- **MySQL** ativo (porta 3306)
- **PHP 7.4+** instalado
- Navegador web

## 📦 Criação do Banco de Dados

### Passo 1: Criar a Estrutura do Banco

1. Certifique-se de que o MySQL está rodando
2. Abra seu navegador e acesse:
   ```
   http://localhost/FlavorWay/config/createtable.php
   ```
3. Aguarde a execução do script
4. Você verá uma página com:
   - ✓ Banco de dados `flavor_way` criado
   - ✓ Todas as tabelas criadas
   - ✓ Foreign keys configuradas
   - ✓ Usuário admin criado

### Estrutura Criada:
- **17 tabelas** no total
- Relacionamentos configurados
- Índices otimizados
- Usuário administrador padrão

## 🌟 População com Dados

### Passo 2: Popular o Banco com Dados de Exemplo

1. Acesse no navegador:
   ```
   http://localhost/FlavorWay/config/populate_database.php
   ```
2. O script irá inserir automaticamente:
   - 25 receitas (5 por região)
   - ~120 ingredientes
   - 20 tags
   - 10 técnicas culinárias
   - 18 estados brasileiros
   - 15 informações culturais
   - Usuários de exemplo
   - Avaliações e favoritos de exemplo

### ⏱️ Tempo Estimado
- Criação do banco: ~5 segundos
- População dos dados: ~10 segundos

## 📊 Dados Inseridos

### Receitas por Região

#### 🌴 Nordeste (5 receitas)
- Acarajé
- Moqueca Baiana
- Baião de Dois
- Carne de Sol com Macaxeira
- Tapioca Recheada

#### 🏙️ Sudeste (5 receitas)
- Feijoada Completa
- Pão de Queijo
- Virado à Paulista
- Tutu de Feijão
- Frango com Quiabo

#### ❄️ Sul (5 receitas)
- Churrasco Gaúcho
- Barreado
- Arroz de Carreteiro
- Polenta com Galinha Caipira
- Cuca Alemã

#### 🌳 Norte (5 receitas)
- Tacacá
- Pato no Tucupi
- Pirarucu de Casaca
- Açaí na Tigela
- Maniçoba

#### 🌾 Centro-Oeste (5 receitas)
- Arroz com Pequi
- Pacu Assado
- Empadão Goiano
- Maria Isabel
- Doce de Leite Caseiro

### Dados Adicionais

- **20 Tags**: Tradicional, Prático, Festa, Vegano, Vegetariano, etc.
- **10 Técnicas**: Fritura, Cozimento Lento, Refogado, Ensopado, etc.
- **18 Estados**: Distribuídos nas 5 regiões brasileiras
- **15 Culturas**: Informações históricas e culturais de cada região

## 🔐 Credenciais de Acesso

### Usuário Administrador
- **Email**: `admin@flavorway.com`
- **Senha**: `admin123`
- **Nível**: Super Admin

### Usuários de Exemplo (Estudantes)
Todos com senha: `password`

1. **Maria Silva**
   - Email: `maria.silva@email.com`
   - Progresso: 15%

2. **João Santos**
   - Email: `joao.santos@email.com`
   - Progresso: 30%

3. **Ana Costa**
   - Email: `ana.costa@email.com`
   - Progresso: 45%

## 🛡️ Segurança

### ⚠️ IMPORTANTE - Após Execução:

1. **DELETE os arquivos de setup**:
   ```bash
   rm config/createtable.php
   rm config/populate_database.php
   ```

2. **Altere a senha do admin**:
   - Faça login como admin
   - Vá em configurações de perfil
   - Altere a senha padrão

3. **Configure o arquivo .env** (se aplicável):
   ```env
   DB_HOST=localhost
   DB_NAME=flavor_way
   DB_USER=root
   DB_PASS=
   ```

## 📝 Estrutura do Banco

### Tabelas Principais

1. **usuarios** - Gerenciamento de usuários
2. **receitas** - Receitas cadastradas
3. **ingredientes** - Ingredientes das receitas
4. **regioes** - Regiões brasileiras
5. **avaliacoes** - Avaliações dos usuários
6. **favoritos** - Receitas favoritas
7. **tags** - Tags para categorização
8. **tecnicas** - Técnicas culinárias

### Relacionamentos

```
usuarios (1) ──→ (N) avaliacoes ←── (1) receitas
    │                                      │
    ├─→ estudantes (1) ──→ (N) favoritos ─┤
    │                                      │
    └─→ administradores              ingredientes
                                            │
regioes (1) ──→ (N) receitas              │
    │              │                       │
    ├─→ estados    └──→ (N) receita_tags ─┤
    ├─→ cultura                            │
    └─→ tecnicas_regiao                  tags
```

## 🔍 Verificação

Para verificar se tudo foi instalado corretamente:

```sql
-- Acesse o phpMyAdmin ou MySQL
USE flavor_way;

-- Verificar receitas por região
SELECT r.nome, COUNT(rec.id) as total_receitas
FROM regioes r
LEFT JOIN receitas rec ON rec.regiao_id = r.id
GROUP BY r.id;

-- Verificar total de dados
SELECT
    (SELECT COUNT(*) FROM receitas) as receitas,
    (SELECT COUNT(*) FROM ingredientes) as ingredientes,
    (SELECT COUNT(*) FROM usuarios) as usuarios,
    (SELECT COUNT(*) FROM avaliacoes) as avaliacoes;
```

## 🐛 Solução de Problemas

### Erro: "Access denied for user"
- Verifique se o MySQL está rodando
- Confirme usuário/senha no arquivo de conexão

### Erro: "Table already exists"
- O banco já foi criado anteriormente
- Use `DROP DATABASE flavor_way;` antes de recriar

### Script não executa
- Verifique se o PHP está ativo
- Confirme o caminho do projeto no localhost
- Veja os logs de erro do PHP

## 📞 Suporte

Em caso de problemas:
1. Verifique os logs de erro do PHP
2. Consulte a documentação do MySQL
3. Entre em contato com a equipe de desenvolvimento

---

**Última atualização**: 2025-11-10
**Versão do Banco**: 1.0
