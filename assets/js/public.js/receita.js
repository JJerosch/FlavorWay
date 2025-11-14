/**
 * JavaScript para página dinâmica de receita
 * Gerencia dados da receita, favoritos e avaliações
 */

// Estado da aplicação
let receitaData = null;
let notaSelecionada = 0;

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    carregarReceita();
    setupRatingInput();
    setupEventListeners();
});

// Carrega dados completos da receita
async function carregarReceita() {
    try {
        const response = await fetch(`../api/get-receita.php?id=${RECEITA_ID}`);
        const data = await response.json();

        if (data.success) {
            receitaData = data.receita;
            renderHeroReceita(receitaData);
            renderIngredientes(receitaData);
            renderModoPreparo(receitaData);
            renderNutricao(receitaData);
            renderAvaliacoes(receitaData);
            renderEstatisticasAvaliacoes(receitaData);
            atualizarTitulos(receitaData);
        } else {
            mostrarErro('Receita não encontrada');
        }
    } catch (error) {
        console.error('Erro ao carregar receita:', error);
        mostrarErro('Erro ao carregar dados da receita');
    }
}

// Renderiza Hero da receita
function renderHeroReceita(receita) {
    const heroContent = document.getElementById('hero-content');

    heroContent.innerHTML = `
        <div class="receita-header-info">
            ${receita.badge ? `<div class="receita-badge-large">${receita.badge}</div>` : ''}

            <h1 class="receita-title">${receita.nome}</h1>

            <p class="receita-description">${receita.descricao}</p>

            <div class="receita-meta-info">
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>${receita.tempo_preparo}</strong>
                        <span>Tempo de Preparo</span>
                    </div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-users"></i>
                    <div>
                        <strong>${receita.pessoas}</strong>
                        <span>Porções</span>
                    </div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-signal"></i>
                    <div>
                        <strong>${receita.dificuldade}</strong>
                        <span>Dificuldade</span>
                    </div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-star"></i>
                    <div>
                        <strong>${receita.rating}</strong>
                        <span>${receita.estatisticas_avaliacoes.total} avaliações</span>
                    </div>
                </div>
            </div>

            <div class="receita-actions">
                <button class="btn-favorito ${receita.is_favorito ? 'favorited' : ''}" onclick="toggleFavorito()" id="btn-favorito">
                    <i class="fas fa-heart"></i>
                    <span id="favorito-text">${receita.is_favorito ? 'Favoritado' : 'Favoritar'}</span>
                    <span id="favorito-count">(${receita.total_favoritos})</span>
                </button>

                <button class="btn-lista-compras" onclick="adicionarListaCompras()" id="btn-lista-compras">
                    <i class="fas fa-shopping-cart"></i>
                    Adicionar à Lista
                </button>

                ${receita.regiao_slug ? `
                    <a href="regiao.php?regiao=${receita.regiao_slug}" class="btn-regiao">
                        <i class="fas fa-map-marker-alt"></i>
                        ${receita.regiao_nome || receita.regiao}
                    </a>
                ` : ''}

                <a href="lista-compras.php" class="btn-view-lista">
                    <i class="fas fa-list"></i>
                    Ver Lista
                </a>

                <button class="btn-share" onclick="compartilhar()">
                    <i class="fas fa-share-alt"></i>
                    Compartilhar
                </button>
            </div>
        </div>
    `;
}

// Renderiza lista de ingredientes
function renderIngredientes(receita) {
    const container = document.getElementById('ingredientes-list');

    if (!receita.ingredientes || receita.ingredientes.length === 0) {
        container.innerHTML = '<p class="placeholder-text">Ingredientes em breve...</p>';
        return;
    }

    // Agrupa ingredientes por categoria
    const porCategoria = {};
    receita.ingredientes.forEach(ing => {
        if (!porCategoria[ing.categoria]) {
            porCategoria[ing.categoria] = [];
        }
        porCategoria[ing.categoria].push(ing.nome);
    });

    let html = '';
    for (const [categoria, ingredientes] of Object.entries(porCategoria)) {
        html += `
            <div class="ingredientes-categoria">
                <h4>${categoria}</h4>
                <ul>
                    ${ingredientes.map(nome => `<li><i class="fas fa-check"></i> ${nome}</li>`).join('')}
                </ul>
            </div>
        `;
    }

    container.innerHTML = html;
}

// Renderiza modo de preparo
function renderModoPreparo(receita) {
    const container = document.getElementById('preparo-steps');

    if (!receita.modo_preparo || receita.modo_preparo.trim() === '') {
        container.innerHTML = '<p class="placeholder-text">Modo de preparo em breve...</p>';
        return;
    }

    // Converte o texto em passos (dividindo por linha)
    const passos = receita.modo_preparo.split('\n').filter(p => p.trim() !== '');

    container.innerHTML = `
        <ol class="preparo-list">
            ${passos.map(passo => `<li>${passo.trim()}</li>`).join('')}
        </ol>
    `;
}

// Renderiza informações nutricionais
function renderNutricao(receita) {
    const container = document.getElementById('nutricao-info');

    const infos = [];

    if (receita.calorias) infos.push({ icon: 'fire', label: 'Calorias', value: receita.calorias });
    if (receita.proteinas) infos.push({ icon: 'drumstick-bite', label: 'Proteínas', value: receita.proteinas });
    if (receita.carboidratos) infos.push({ icon: 'bread-slice', label: 'Carboidratos', value: receita.carboidratos });
    if (receita.gorduras) infos.push({ icon: 'oil-can', label: 'Gorduras', value: receita.gorduras });

    if (infos.length === 0) {
        container.innerHTML = '<p class="placeholder-text">Informações nutricionais em breve...</p>';
        return;
    }

    container.innerHTML = infos.map(info => `
        <div class="nutricao-item">
            <i class="fas fa-${info.icon}"></i>
            <div>
                <strong>${info.value}</strong>
                <span>${info.label}</span>
            </div>
        </div>
    `).join('');
}

// Renderiza estatísticas de avaliações
function renderEstatisticasAvaliacoes(receita) {
    const container = document.getElementById('avaliacoes-stats');
    const stats = receita.estatisticas_avaliacoes;

    if (stats.total == 0) {
        container.innerHTML = `
            <div class="no-avaliacoes">
                <i class="fas fa-star"></i>
                <p>Seja o primeiro a avaliar esta receita!</p>
            </div>
        `;
        return;
    }

    const media = parseFloat(stats.media).toFixed(1);
    const total = parseInt(stats.total);

    container.innerHTML = `
        <div class="stats-resumo">
            <div class="rating-grande">
                <div class="rating-numero">${media}</div>
                <div class="rating-estrelas">
                    ${gerarEstrelas(media)}
                </div>
                <div class="rating-total">${total} avaliações</div>
            </div>

            <div class="rating-bars">
                ${gerarBarraRating(5, stats.cinco_estrelas, total)}
                ${gerarBarraRating(4, stats.quatro_estrelas, total)}
                ${gerarBarraRating(3, stats.tres_estrelas, total)}
                ${gerarBarraRating(2, stats.duas_estrelas, total)}
                ${gerarBarraRating(1, stats.uma_estrela, total)}
            </div>
        </div>
    `;
}

// Renderiza lista de avaliações
function renderAvaliacoes(receita) {
    const container = document.getElementById('avaliacoes-list');

    if (!receita.avaliacoes || receita.avaliacoes.length === 0) {
        container.innerHTML = '<p class="placeholder-text">Nenhuma avaliação ainda.</p>';
        return;
    }

    container.innerHTML = receita.avaliacoes.map(avaliacao => `
        <div class="avaliacao-card">
            <div class="avaliacao-header">
                <div class="avaliacao-usuario">
                    <div class="usuario-avatar">
                        ${avaliacao.avatar ?
                            `<img src="${avaliacao.avatar}" alt="${avaliacao.usuario_nome}">` :
                            `<i class="fas fa-user"></i>`
                        }
                    </div>
                    <div>
                        <strong>${avaliacao.usuario_nome}</strong>
                        <span>${formatarData(avaliacao.data_criacao)}</span>
                    </div>
                </div>
                <div class="avaliacao-rating">
                    ${gerarEstrelas(avaliacao.nota)}
                </div>
            </div>
            ${avaliacao.comentario ? `
                <div class="avaliacao-comentario">
                    <p>${avaliacao.comentario}</p>
                </div>
            ` : ''}
        </div>
    `).join('');
}

// Toggle favorito
async function toggleFavorito() {
    try {
        const response = await fetch('../api/toggle-favorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                receita_id: RECEITA_ID
            })
        });

        const data = await response.json();

        if (data.success) {
            // Atualiza UI
            const btn = document.getElementById('btn-favorito');
            const text = document.getElementById('favorito-text');
            const count = document.getElementById('favorito-count');

            if (data.is_favorito) {
                btn.classList.add('favorited');
                text.textContent = 'Favoritado';
            } else {
                btn.classList.remove('favorited');
                text.textContent = 'Favoritar';
            }

            count.textContent = `(${data.total_favoritos})`;

            // Feedback visual
            mostrarNotificacao(data.acao === 'adicionado' ?
                'Receita adicionada aos favoritos!' :
                'Receita removida dos favoritos!',
                'success'
            );
        } else {
            mostrarNotificacao(data.error || 'Erro ao favoritar', 'error');
        }
    } catch (error) {
        console.error('Erro ao favoritar:', error);
        mostrarNotificacao('Erro ao processar favorito', 'error');
    }
}

// Setup do input de rating (estrelas)
function setupRatingInput() {
    const stars = document.querySelectorAll('#stars-input i');
    const input = document.getElementById('nota-input');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            notaSelecionada = parseInt(this.dataset.nota);
            input.value = notaSelecionada;

            // Atualiza visual das estrelas
            stars.forEach(s => {
                const nota = parseInt(s.dataset.nota);
                if (nota <= notaSelecionada) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
        });

        // Hover effect
        star.addEventListener('mouseenter', function() {
            const nota = parseInt(this.dataset.nota);
            stars.forEach(s => {
                const n = parseInt(s.dataset.nota);
                if (n <= nota) {
                    s.classList.add('fas');
                    s.classList.remove('far');
                } else {
                    s.classList.add('far');
                    s.classList.remove('fas');
                }
            });
        });
    });

    // Restaura seleção ao sair do hover
    document.getElementById('stars-input').addEventListener('mouseleave', function() {
        stars.forEach(s => {
            const nota = parseInt(s.dataset.nota);
            if (nota <= notaSelecionada) {
                s.classList.add('fas');
                s.classList.remove('far');
            } else {
                s.classList.add('far');
                s.classList.remove('fas');
            }
        });
    });
}

// Submit avaliação
async function submitAvaliacao(event) {
    event.preventDefault();

    const nota = document.getElementById('nota-input').value;
    const comentario = document.getElementById('comentario').value;

    if (!nota) {
        mostrarNotificacao('Por favor, selecione uma nota!', 'warning');
        return;
    }

    try {
        const response = await fetch('../api/salvar-avaliacao.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                receita_id: RECEITA_ID,
                nota: parseInt(nota),
                comentario: comentario
            })
        });

        const data = await response.json();

        if (data.success) {
            mostrarNotificacao(
                data.acao === 'criada' ? 'Avaliação enviada com sucesso!' : 'Avaliação atualizada!',
                'success'
            );

            // Recarrega a receita para mostrar nova avaliação
            setTimeout(() => {
                carregarReceita();
            }, 1000);
        } else {
            mostrarNotificacao(data.error || 'Erro ao enviar avaliação', 'error');
        }
    } catch (error) {
        console.error('Erro ao enviar avaliação:', error);
        mostrarNotificacao('Erro ao processar avaliação', 'error');
    }
}

// Funções auxiliares
function gerarEstrelas(rating) {
    const nota = parseFloat(rating);
    let html = '';

    for (let i = 1; i <= 5; i++) {
        if (i <= nota) {
            html += '<i class="fas fa-star"></i>';
        } else if (i - 0.5 <= nota) {
            html += '<i class="fas fa-star-half-alt"></i>';
        } else {
            html += '<i class="far fa-star"></i>';
        }
    }

    return html;
}

function gerarBarraRating(estrelas, quantidade, total) {
    const porcentagem = total > 0 ? (quantidade / total * 100).toFixed(0) : 0;

    return `
        <div class="rating-bar">
            <span class="bar-label">${estrelas} <i class="fas fa-star"></i></span>
            <div class="bar-container">
                <div class="bar-fill" style="width: ${porcentagem}%"></div>
            </div>
            <span class="bar-count">${quantidade}</span>
        </div>
    `;
}

function formatarData(dataString) {
    const data = new Date(dataString);
    const agora = new Date();
    const diff = agora - data;
    const dias = Math.floor(diff / (1000 * 60 * 60 * 24));

    if (dias === 0) return 'Hoje';
    if (dias === 1) return 'Ontem';
    if (dias < 7) return `${dias} dias atrás`;
    if (dias < 30) return `${Math.floor(dias / 7)} semanas atrás`;

    return data.toLocaleDateString('pt-BR');
}

function compartilhar() {
    if (navigator.share) {
        navigator.share({
            title: receitaData.nome,
            text: receitaData.descricao,
            url: window.location.href
        });
    } else {
        // Fallback: copia URL
        navigator.clipboard.writeText(window.location.href);
        mostrarNotificacao('Link copiado!', 'success');
    }
}

function atualizarTitulos(receita) {
    document.getElementById('page-title').textContent = `${receita.nome} - FlavorWay`;
}

function mostrarErro(mensagem) {
    const heroContent = document.getElementById('hero-content');
    heroContent.innerHTML = `
        <div class="error-message">
            <i class="fas fa-exclamation-triangle"></i>
            <h2>${mensagem}</h2>
            <a href="culinaria-brasileira.php" class="btn btn-primary">
                Voltar
            </a>
        </div>
    `;
}

function mostrarNotificacao(mensagem, tipo = 'info') {
    // Cria elemento de notificação
    const notif = document.createElement('div');
    notif.className = `notificacao notificacao-${tipo}`;
    notif.innerHTML = `
        <i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${mensagem}</span>
    `;

    document.body.appendChild(notif);

    // Anima entrada
    setTimeout(() => notif.classList.add('show'), 10);

    // Remove após 3 segundos
    setTimeout(() => {
        notif.classList.remove('show');
        setTimeout(() => notif.remove(), 300);
    }, 3000);
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

// Adicionar ingredientes à lista de compras
async function adicionarListaCompras() {
    if (!receitaData || !receitaData.ingredientes || receitaData.ingredientes.length === 0) {
        mostrarNotificacao('Esta receita não possui ingredientes cadastrados', 'warning');
        return;
    }

    try {
        const btn = document.getElementById('btn-lista-compras');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adicionando...';

        const response = await fetch('../api/adicionar-lista-compras.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                receita_id: RECEITA_ID,
                ingredientes: receitaData.ingredientes
            })
        });

        const data = await response.json();

        if (data.success) {
            mostrarNotificacao(`${data.total} ingredientes adicionados à lista de compras!`, 'success');
            setTimeout(() => {
                window.location.href = 'lista-compras.php';
            }, 1500);
        } else {
            mostrarNotificacao(data.error || 'Erro ao adicionar à lista', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Adicionar à Lista';
        }
    } catch (error) {
        console.error('Erro ao adicionar à lista:', error);
        mostrarNotificacao('Erro ao processar requisição', 'error');
        const btn = document.getElementById('btn-lista-compras');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Adicionar à Lista';
    }
}
