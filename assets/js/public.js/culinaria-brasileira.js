// Dados das regiões brasileiras
const regioesBrasil = [
  {
    id: "nordeste",
    nome: "Nordeste",
    descricao: "Sabores intensos e temperos marcantes da culinária afro-brasileira",
    estados: 9,
    receitas: 150,
    ingredientes: 45,
    image: "../assets/images/culinarianordestina.jpg",
    destaque: ["Acarajé", "Moqueca", "Tapioca", "Baião de Dois"],
    link: "regiao.php?regiao=nordeste",
  },
  {
    id: "sudeste",
    nome: "Sudeste",
    descricao: "Tradição e modernidade em harmonia com influências de diversas culturas",
    estados: 4,
    receitas: 180,
    ingredientes: 38,
    image: "../assets/images/culinariasudeste.jpg",
    destaque: ["Feijoada", "Pão de Queijo", "Virado à Paulista", "Moqueca Capixaba"],
    link: "regiao.php?regiao=sudeste",
  },
  {
    id: "sul",
    nome: "Sul",
    descricao: "Tradições europeias e sabores gaúchos em perfeita harmonia",
    estados: 3,
    receitas: 120,
    ingredientes: 32,
    image: "../assets/images/culinariasul.jpg",
    destaque: ["Churrasco", "Barreado", "Cuca Alemã", "Entrevero"],
    link: "regiao.php?regiao=sul",
  },
  {
    id: "norte",
    nome: "Norte",
    descricao: "Sabores exóticos da Amazônia com ingredientes únicos",
    estados: 7,
    receitas: 90,
    ingredientes: 52,
    image: "../assets/images/culinarianorte.jpg",
    destaque: ["Tacacá", "Pato no Tucupi", "Açaí", "Pirarucu"],
    link: "regiao.php?regiao=norte",
  },
  {
    id: "centro-oeste",
    nome: "Centro-Oeste",
    descricao: "Sabores do pantanal e cerrado com peixes de água doce",
    estados: 4,
    receitas: 70,
    ingredientes: 28,
    image: "../assets/images/culinariacentrooeste.jpg",
    destaque: ["Pacu Assado", "Pequi com Frango", "Farofa de Banana", "Mojica"],
    link: "regiao.php?regiao=centro-oeste",
  },
]

// Receitas brasileiras
const receitasBrasil = [
  {
    id: 1,
    nome: "Feijoada Completa",
    regiao: "sudeste",
    descricao: "O prato mais tradicional do Brasil com feijão preto e carnes",
    tempo: "3h 30min",
    dificuldade: "Intermediário",
    rating: 4.9,
    image: "../assets/images/feijoada.jpg",
  },
  {
    id: 2,
    nome: "Acarajé",
    regiao: "nordeste",
    descricao: "Bolinho de feijão fradinho frito no dendê, típico da Bahia",
    tempo: "2h",
    dificuldade: "Intermediário",
    rating: 4.8,
    image: "../assets/images/acaraje1.jpg",
  },
  {
    id: 3,
    nome: "Churrasco Gaúcho",
    regiao: "sul",
    descricao: "Carnes assadas na brasa ao estilo tradicional do Sul",
    tempo: "2h",
    dificuldade: "Intermediário",
    rating: 4.9,
    image: "../assets/images/churrascogaucho.jpg",
  },
  {
    id: 4,
    nome: "Tacacá",
    regiao: "norte",
    descricao: "Sopa paraense com tucupi, jambu e camarão",
    tempo: "1h",
    dificuldade: "Básico",
    rating: 4.6,
    image: "../assets/images/tacaca.jpg",
  },
  {
    id: 5,
    nome: "Pequi com Frango",
    regiao: "centro-oeste",
    descricao: "Prato tradicional goiano com o fruto típico do cerrado",
    tempo: "1h 30min",
    dificuldade: "Intermediário",
    rating: 4.5,
    image: "../assets/images/pequi.jpg",
  },
  {
    id: 6,
    nome: "Pão de Queijo",
    regiao: "sudeste",
    descricao: "Quitanda mineira feita com polvilho e queijo",
    tempo: "45min",
    dificuldade: "Básico",
    rating: 4.8,
    image: "../assets/images/paodequeijo.jpg",
  },
  {
    id: 7,
    nome: "Baião de Dois",
    regiao: "nordeste",
    descricao: "Arroz com feijão de corda e carne de sol",
    tempo: "1h 30min",
    dificuldade: "Intermediário",
    rating: 4.6,
    image: "../assets/images/baiaode2.jpg",
  },
  {
    id: 8,
    nome: "Barreado",
    regiao: "sul",
    descricao: "Prato paranaense cozido em panela de barro por horas",
    tempo: "6h",
    dificuldade: "Avançado",
    rating: 4.7,
    image: "../assets/images/barreado.jpg",
  },
  {
    id: 9,
    nome: "Pato no Tucupi",
    regiao: "norte",
    descricao: "Prato amazonense com pato e molho de mandioca",
    tempo: "2h 30min",
    dificuldade: "Avançado",
    rating: 4.7,
    image: "../assets/images/patonotucupi.jpg",
  },
]

// Ingredientes típicos
const ingredientesBrasil = [
  {
    id: 1,
    nome: "Dendê",
    subtitle: "Óleo sagrado da Bahia",
    descricao: "Extraído da palma africana, essencial na culinária baiana",
    icon: "🌴",
  },
  {
    id: 2,
    nome: "Queijo Minas",
    subtitle: "Tradição mineira",
    descricao: "Queijo fresco e cremoso, base do pão de queijo",
    icon: "🧀",
  },
  {
    id: 3,
    nome: "Carne de Sol",
    subtitle: "Proteína do sertão",
    descricao: "Carne salgada e seca ao sol, técnica tradicional nordestina",
    icon: "🥩",
  },
  {
    id: 4,
    nome: "Tucupi",
    subtitle: "Molho amazônico",
    descricao: "Caldo amarelo extraído da mandioca brava",
    icon: "🌿",
  },
  {
    id: 5,
    nome: "Pequi",
    subtitle: "Fruto do cerrado",
    descricao: "Fruto aromático típico do Centro-Oeste brasileiro",
    icon: "🍈",
  },
  {
    id: 6,
    nome: "Açaí",
    subtitle: "Energia da Amazônia",
    descricao: "Fruto energético rico em antioxidantes",
    icon: "🫐",
  },
]

let filtroAtivo = "todas"

// Inicialização
document.addEventListener("DOMContentLoaded", () => {
  renderRegioes()
  renderReceitas()
  renderIngredientes()
  setupEventListeners()
  handleScroll()
})

// Renderizar regiões
function renderRegioes() {
  const container = document.getElementById("regioesGrid")
  if (!container) return

  container.innerHTML = regioesBrasil
    .map(
      (regiao) => `
        <div class="regiao-card ${regiao.id}" onclick="window.location.href='${regiao.link}'">
            <div class="regiao-header" style="background-image: url('${regiao.image}');">
            </div>
            <div class="regiao-body">
                <div class="regiao-title">
                    <h3>${regiao.nome}</h3>
                </div>
                <p>${regiao.descricao}</p>
                <div class="regiao-stats">
                    <div class="regiao-stat">
                        <div class="regiao-stat-number">${regiao.estados}</div>
                        <div class="regiao-stat-label">Estados</div>
                    </div>
                    <div class="regiao-stat">
                        <div class="regiao-stat-number">${regiao.receitas}+</div>
                        <div class="regiao-stat-label">Receitas</div>
                    </div>
                    <div class="regiao-stat">
                        <div class="regiao-stat-number">${regiao.ingredientes}+</div>
                        <div class="regiao-stat-label">Ingredientes</div>
                    </div>
                </div>
                <div class="regiao-tags">
                    ${regiao.destaque
          .slice(0, 3)
          .map((prato) => `<span class="regiao-tag">${prato}</span>`)
          .join("")}
                </div>
            </div>
        </div>
    `,
    )
    .join("")
}

// Renderizar receitas
function renderReceitas() {
  const container = document.getElementById("receitasGrid")
  if (!container) return

  const receitasFiltradas =
    filtroAtivo === "todas" ? receitasBrasil : receitasBrasil.filter((r) => r.regiao === filtroAtivo)

  container.innerHTML = receitasFiltradas
    .map(
      (receita) => `
        <div class="receita-card">
            <div class="receita-image" style="background-image: url('${receita.image}')">
                <div class="receita-badge">${getNomeRegiao(receita.regiao)}</div>
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
                        ${receita.tempo}
                    </span>
                    <span>
                        <i class="fas fa-signal"></i>
                        ${receita.dificuldade}
                    </span>
                </div>
                <div class="receita-footer">
                    <span class="receita-region">${getNomeRegiao(receita.regiao)}</span>
                    <button class="receita-btn">
                        Ver Receita
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    `,
    )
    .join("")
}

// Renderizar ingredientes
function renderIngredientes() {
  const container = document.getElementById("ingredientesGrid")
  if (!container) return

  container.innerHTML = ingredientesBrasil
    .map(
      (ingrediente) => `
        <div class="ingrediente-card">
            <div class="ingrediente-icon">${ingrediente.icon}</div>
            <h3>${ingrediente.nome}</h3>
            <div class="subtitle">${ingrediente.subtitle}</div>
            <p>${ingrediente.descricao}</p>
        </div>
    `,
    )
    .join("")
}

// Setup event listeners
function setupEventListeners() {
  // Filtros de receitas
  document.querySelectorAll(".filtro-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      document.querySelectorAll(".filtro-btn").forEach((b) => b.classList.remove("active"))
      this.classList.add("active")
      filtroAtivo = this.dataset.regiao
      renderReceitas()
    })
  })

  // Navegação suave
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        scrollToSection(target.id)
      }
    })
  })

  // Scroll event
  window.addEventListener("scroll", handleScroll)
}

// Funções auxiliares
function getNomeRegiao(slug) {
  const regiao = regioesBrasil.find((r) => r.id === slug)
  return regiao ? regiao.nome : slug
}

function toggleMenu() {
  const nav = document.getElementById("nav")
  nav.classList.toggle("active")
}

function toggleSearch() {
  const searchContainer = document.getElementById("searchContainer")
  searchContainer.classList.toggle("active")

  if (searchContainer.classList.contains("active")) {
    searchContainer.querySelector(".search-input").focus()
  }
}

function scrollToSection(sectionId) {
  const element = document.getElementById(sectionId)
  if (element) {
    const headerOffset = 80
    const elementPosition = element.getBoundingClientRect().top
    const offsetPosition = elementPosition + window.pageYOffset - headerOffset

    window.scrollTo({
      top: offsetPosition,
      behavior: "smooth",
    })
  }
}

function handleScroll() {
  const header = document.getElementById("header")
  if (window.scrollY > 100) {
    header.classList.add("scrolled")
  } else {
    header.classList.remove("scrolled")
  }
}
