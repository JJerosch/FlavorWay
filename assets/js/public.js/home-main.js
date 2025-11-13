// Dados das culinárias
const culinarias = [
  {
    id: "brasileira",
    nome: "Brasileira",
    descricao: "Explore a diversidade dos sabores brasileiros, das praias do Nordeste aos pampas do Sul.",
    flag: "🇧🇷",
    receitas: 500,
    estados: 27,
    image: "../assets/images/culinariabrasileira.jpg",
    link: "culinaria-brasileira.php",
  },
  {
    id: "italiana",
    nome: "Italiana",
    descricao: "Descubra as tradições milenares da culinária italiana, de Roma à Sicília.",
    flag: "🇮🇹",
    receitas: 350,
    regioes: 20,
    image: "../assets/images/culinariaitaliana.jpg",
    link: "#",
  },
  {
    id: "japonesa",
    nome: "Japonesa",
    descricao: "Mergulhe nos sabores delicados e técnicas precisas da culinária japonesa.",
    flag: "🇯🇵",
    receitas: 280,
    pratos: 150,
    image: "../assets/images/culinariajaponesa.jpg",
    link: "#",
  },
  {
    id: "francesa",
    nome: "Francesa",
    descricao: "Aprenda as técnicas clássicas e refinadas da alta gastronomia francesa.",
    flag: "🇫🇷",
    receitas: 400,
    tecnicas: 200,
    image: "../assets/images/culinariafrancesa.jpg",
    link: "#",
  },
  {
    id: "mexicana",
    nome: "Mexicana",
    descricao: "Saboreie a intensidade dos temperos e tradições da culinária mexicana.",
    flag: "🇲🇽",
    receitas: 320,
    ingredientes: 180,
    image: "../assets/images/culinariamexicana.jpg",
    link: "#",
  },
  {
    id: "tailandesa",
    nome: "Tailandesa",
    descricao: "Experimente o equilíbrio perfeito entre doce, azedo, salgado e picante.",
    flag: "🇹🇭",
    receitas: 250,
    pratos: 120,
    image: "../assets/images/culinariatailandesa.jpg",
    link: "#",
  },
]

// Receitas em destaque (serão carregadas do banco de dados)
let receitasDestaque = []

// Inicialização
document.addEventListener("DOMContentLoaded", async () => {
  renderCulinarias()
  await loadReceitasDestaque() // Carrega receitas do banco de dados
  renderDestaques()
  setupEventListeners()
  handleScroll()
})

// Renderizar culinárias
function renderCulinarias() {
  const container = document.getElementById("culinariasGrid")
  if (!container) return

  container.innerHTML = culinarias
    .map(
      (culinaria) => `
        <a href="${culinaria.link}" class="culinaria-card">
            <div class="culinaria-image" style="background-image: url('${culinaria.image}')">
            </div>
            <div class="culinaria-body">
                <h3>${culinaria.nome}</h3>
                <p>${culinaria.descricao}</p>
                <div class="culinaria-stats">
                    <span>
                        <i class="fas fa-utensils"></i>
                        ${culinaria.receitas} receitas
                    </span>
                    <span>
                        <i class="fas fa-map-marker-alt"></i>
                        ${culinaria.estados || culinaria.regioes || culinaria.pratos || culinaria.ingredientes} ${culinaria.estados ? "estados" : "itens"}
                    </span>
                </div>
            </div>
        </a>
    `,
    )
    .join("")
}

// Carrega receitas em destaque do banco de dados
async function loadReceitasDestaque() {
  try {
    console.log("Carregando receitas em destaque...")
    const response = await fetch("../api/get-receitas-destaque.php")
    console.log("Status da resposta:", response.status)

    const data = await response.json()
    console.log("Dados recebidos:", data)

    if (data.success && data.receitas && data.receitas.length > 0) {
      receitasDestaque = data.receitas
      console.log("✅ Receitas carregadas do banco:", receitasDestaque.length)
    } else {
      console.log("⚠️ Nenhuma receita no banco, usando receitas de exemplo")
      // Se não houver receitas no banco, usa receitas de exemplo
      receitasDestaque = [
        {
          id: 1,
          nome: "Feijoada Completa",
          culinaria: "Brasileira",
          tempo: "3h",
          dificuldade: "Intermediário",
          rating: 4.9,
          image: "/placeholder.svg?height=180&width=280&text=Feijoada",
        },
        {
          id: 2,
          nome: "Lasanha Bolonhesa",
          culinaria: "Italiana",
          tempo: "2h",
          dificuldade: "Intermediário",
          rating: 4.8,
          image: "/placeholder.svg?height=180&width=280&text=Lasanha",
        },
        {
          id: 3,
          nome: "Sushi Variado",
          culinaria: "Japonesa",
          tempo: "1h 30min",
          dificuldade: "Avançado",
          rating: 4.9,
          image: "/placeholder.svg?height=180&width=280&text=Sushi",
        },
      ]
    }
  } catch (error) {
    console.error("❌ Erro ao carregar receitas em destaque:", error)
    // Em caso de erro, usa receitas de exemplo
    receitasDestaque = [
      {
        id: 1,
        nome: "Feijoada Completa",
        culinaria: "Brasileira",
        tempo: "3h",
        dificuldade: "Intermediário",
        rating: 4.9,
        image: "/placeholder.svg?height=180&width=280&text=Feijoada",
      },
    ]
  }
}

// Renderizar destaques
function renderDestaques() {
  const container = document.getElementById("destaquesGrid")
  if (!container) return

  container.innerHTML = receitasDestaque
    .map(
      (receita) => `
        <div class="destaque-card">
            <div class="destaque-image" style="background-image: url('${receita.image}')">
                <div class="destaque-badge">${receita.culinaria}</div>
            </div>
            <div class="destaque-body">
                <h3>${receita.nome}</h3>
                <p>${receita.dificuldade} • ${receita.tempo}</p>
                <div class="destaque-meta">
                    <span>
                        <i class="fas fa-star" style="color: #fbbf24;"></i>
                        ${receita.rating}
                    </span>
                    <span>
                        <i class="fas fa-clock"></i>
                        ${receita.tempo}
                    </span>
                    <span>
                        <i class="fas fa-signal"></i>
                        ${receita.dificuldade}
                    </span>
                </div>
            </div>
        </div>
    `,
    )
    .join("")
}

// Event Listeners
function setupEventListeners() {
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

  // Active nav links
  updateActiveNavLink()
  window.addEventListener("scroll", updateActiveNavLink)
}

// Toggle menu mobile
function toggleMenu() {
  const nav = document.getElementById("nav")
  nav.classList.toggle("active")
}

// Toggle search
function toggleSearch() {
  const searchContainer = document.getElementById("searchContainer")
  searchContainer.classList.toggle("active")

  if (searchContainer.classList.contains("active")) {
    searchContainer.querySelector(".search-input").focus()
  }
}

// Scroll suave para seção
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

// Handle scroll
function handleScroll() {
  const header = document.getElementById("header")
  if (window.scrollY > 100) {
    header.classList.add("scrolled")
  } else {
    header.classList.remove("scrolled")
  }
}

// Update active nav link
function updateActiveNavLink() {
  const sections = document.querySelectorAll("section[id]")
  const navLinks = document.querySelectorAll(".nav-link")

  let current = ""

  sections.forEach((section) => {
    const sectionTop = section.offsetTop
    const sectionHeight = section.clientHeight
    if (window.pageYOffset >= sectionTop - 200) {
      current = section.getAttribute("id")
    }
  })

  navLinks.forEach((link) => {
    link.classList.remove("active")
    if (link.getAttribute("href") === `#${current}`) {
      link.classList.add("active")
    }
  })
}

// Submit newsletter
function submitNewsletter(event) {
  event.preventDefault()
  alert("Obrigado por se inscrever! Em breve você receberá nossas receitas exclusivas.")
  event.target.reset()
}
