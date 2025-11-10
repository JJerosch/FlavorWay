/**
 * JavaScript para página dinâmica de região
 * Carrega dados da região e receitas via API
 */

// Estado da aplicação
let regiaoData = null;
let receitasData = [];

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    carregarDadosRegiao();
    carregarReceitasRegiao();
    setupEventListeners();
});

// Carrega dados da região
async function carregarDadosRegiao() {
    try {
        const response = await fetch(`../api/get-regiao.php?slug=${REGIAO_SLUG}`);
        const data = await response.json();

        if (data.success) {
            regiaoData = data.regiao;
            renderHeroRegiao(regiaoData);
            renderSobreRegiao(regiaoData);
            atualizarTitulos(regiaoData);
        } else {
            mostrarErro('Região não encontrada');
        }
    } catch (error) {
        console.error('Erro ao carregar região:', error);
        mostrarErro('Erro ao carregar dados da região');
    }
}

// Carrega receitas da região
async function carregarReceitasRegiao() {
    try {
        const response = await fetch(`../api/get-receitas-regiao.php?slug=${REGIAO_SLUG}`);
        const data = await response.json();

        if (data.success) {
            receitasData = data.receitas;
            renderReceitas(receitasData);
        } else {
            mostrarErro('Erro ao carregar receitas');
        }
    } catch (error) {
        console.error('Erro ao carregar receitas:', error);
        mostrarErro('Erro ao carregar receitas da região');
    }
}

// Renderiza Hero da região
function renderHeroRegiao(regiao) {
    const heroContent = document.getElementById('hero-content');

    heroContent.innerHTML = `
        <h1 class="hero-title">
            Sabores do
            <span class="gradient-text-brasil">${regiao.nome}</span>
        </h1>

        <p class="hero-subtitle">
            ${regiao.descricao}
        </p>

        <div class="hero-buttons">
            <button class="btn btn-primary" onclick="scrollToSection('receitas')">
                <i class="fas fa-book-open"></i>
                Ver Receitas
                <i class="fas fa-arrow-right"></i>
            </button>
            <button class="btn btn-outline-white" onclick="scrollToSection('sobre')">
                <i class="fas fa-info-circle"></i>
                Sobre a Região
            </button>
        </div>

        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-number">${regiao.estados?.length || 0}</div>
                <div class="stat-label">Estados</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">${regiao.total_receitas}+</div>
                <div class="stat-label">Receitas</div>
            </div>
        </div>
    `;
}

// Renderiza receitas
function renderReceitas(receitas) {
    const container = document.getElementById('receitasGrid');

    if (receitas.length === 0) {
        container.innerHTML = `
            <div class="no-results">
                <i class="fas fa-search"></i>
                <p>Nenhuma receita encontrada para esta região.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = receitas.map(receita => `
        <div class="receita-card" onclick="abrirReceita(${receita.id})">
            <div class="receita-image" style="background: linear-gradient(135deg, #ea580c, #dc2626);">
                ${receita.badge ? `<div class="receita-badge">${receita.badge}</div>` : ''}
                <div class="receita-rating">
                    <i class="fas fa-star"></i>
                    ${receita.rating}
                </div>
            </div>
            <div class="receita-body">
                <h3>${receita.nome}</h3>
                <p>${receita.descricao}</p>
                <div class="receita-meta">
                    <span>
                        <i class="fas fa-clock"></i>
                        ${receita.tempo_preparo}
                    </span>
                    <span>
                        <i class="fas fa-signal"></i>
                        ${receita.dificuldade}
                    </span>
                </div>
                <div class="receita-footer">
                    <div class="receita-stats">
                        <span><i class="fas fa-heart"></i> ${receita.total_avaliacoes || 0}</span>
                    </div>
                    <button class="receita-btn" onclick="event.stopPropagation(); abrirReceita(${receita.id})">
                        Ver Receita
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Renderiza seção "Sobre a Região"
function renderSobreRegiao(regiao) {
    const container = document.getElementById('sobreContent');

    let html = `
        <div class="sobre-grid">
            <div class="sobre-card">
                <div class="sobre-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3>Localização</h3>
                <p>${regiao.descricao}</p>
            </div>

            <div class="sobre-card">
                <div class="sobre-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3>Receitas</h3>
                <p>Mais de ${regiao.total_receitas} receitas autênticas da região</p>
            </div>
    `;

    if (regiao.estados && regiao.estados.length > 0) {
        html += `
            <div class="sobre-card full-width">
                <div class="sobre-icon">
                    <i class="fas fa-list"></i>
                </div>
                <h3>Estados da Região</h3>
                <div class="estados-list">
                    ${regiao.estados.map(estado => `
                        <div class="estado-item">
                            <strong>${estado.nome}</strong>
                            ${estado.capital ? `<span>Capital: ${estado.capital}</span>` : ''}
                            ${estado.ingrediente_destaque ? `<span><i class="fas fa-star"></i> ${estado.ingrediente_destaque}</span>` : ''}
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    html += '</div>';
    container.innerHTML = html;
}

// Atualiza títulos da página
function atualizarTitulos(regiao) {
    document.getElementById('page-title').textContent = `${regiao.nome} - FlavorWay`;
    document.getElementById('header-regiao-nome').textContent = regiao.nome;
    document.getElementById('regiao-nome-titulo').textContent = `da Região ${regiao.nome}`;
    document.getElementById('sobre-regiao-nome').textContent = regiao.nome;
}

// Abre página da receita
function abrirReceita(receitaId) {
    window.location.href = `receita.php?id=${receitaId}`;
}

// Mostra erro
function mostrarErro(mensagem) {
    const heroContent = document.getElementById('hero-content');
    heroContent.innerHTML = `
        <div class="error-message">
            <i class="fas fa-exclamation-triangle"></i>
            <h2>${mensagem}</h2>
            <a href="culinaria-brasileira.php" class="btn btn-primary">
                Voltar para Culinária Brasileira
            </a>
        </div>
    `;
}

// Setup event listeners
function setupEventListeners() {
    // Navegação suave
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                scrollToSection(target.id);
            }
        });
    });

    // Scroll event
    window.addEventListener('scroll', handleScroll);
}

// Funções auxiliares (compartilhadas com outras páginas)
function toggleMenu() {
    const nav = document.getElementById('nav');
    nav.classList.toggle('active');
}

function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        const headerOffset = 80;
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
}

function handleScroll() {
    const header = document.getElementById('header');
    if (window.scrollY > 100) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
}
