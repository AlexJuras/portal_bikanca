<script setup>
import { ref, onMounted, computed } from 'vue'
import Principal from '@/Layouts/Principal.vue'

defineOptions({ layout: Principal });

// Dados da página
const pageData = ref({
  name: 'Todas as Notícias',
  description: 'Acompanhe todas as principais notícias do Brasil e do mundo em tempo real, organizadas por relevância e data de publicação.',
  icon: '📰'
})

// Notícia principal em destaque
const featuredNews = ref({
  id: 1,
  title: "Governo Federal anuncia novo pacote de investimentos em infraestrutura para o interior do país",
  excerpt: "O anúncio foi feito durante coletiva de imprensa em Brasília e prevê investimentos de R$ 50 bilhões em rodovias, ferrovias e aeroportos regionais. O plano deve beneficiar mais de 200 municípios brasileiros nos próximos três anos.",
  category: "Política",
  categoryColor: "azul-lazuli",
  publishedAt: "4 de julho de 2024",
  readTime: "5 min",
  image: "https://images.unsplash.com/photo-1606868306217-dbf5046868d2?w=800&h=500&fit=crop",
  author: {
    name: "Marcio Bikanca",
    role: "Repórter político",
    photo: "/User.png"
  },
  isBreaking: true
})

// Lista de todas as notícias
const allNews = ref([
  {
    id: 2,
    title: "Congresso Nacional aprova nova lei de transparência pública",
    excerpt: "A medida estabelece novas regras para acesso a informações governamentais e fortalece os mecanismos de controle social.",
    category: "Política",
    categoryColor: "blue-600",
    publishedAt: "3 de julho de 2024",
    readTime: "4 min",
    image: "https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=400&h=300&fit=crop",
    author: {
      name: "Ana Paula Santos",
      photo: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 3,
    title: "Mercado financeiro registra alta histórica na bolsa de valores",
    excerpt: "Ibovespa fecha em máxima histórica impulsionado por resultados positivos de empresas brasileiras e cenário econômico favorável.",
    category: "Economia",
    categoryColor: "green-600",
    publishedAt: "3 de julho de 2024",
    readTime: "6 min",
    image: "https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&h=300&fit=crop",
    author: {
      name: "Ricardo Ferreira",
      photo: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: true
  },
  {
    id: 4,
    title: "Seleção Brasileira se prepara para próxima rodada das Eliminatórias",
    excerpt: "Técnico convoca 23 jogadores para os próximos jogos contra Venezuela e Uruguai pelas Eliminatórias da Copa do Mundo.",
    category: "Esporte",
    categoryColor: "orange-600",
    publishedAt: "2 de julho de 2024",
    readTime: "3 min",
    image: "https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=300&fit=crop",
    author: {
      name: "Carlos Mendes",
      photo: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 5,
    title: "Ministério da Saúde anuncia ampliação do programa de vacinação",
    excerpt: "Nova fase do programa nacional de imunização contemplará mais 15 milhões de brasileiros em todo o país.",
    category: "Saúde",
    categoryColor: "red-600",
    publishedAt: "2 de julho de 2024",
    readTime: "3 min",
    image: "https://images.unsplash.com/photo-1584515979956-d9f6e5d493a1?w=400&h=300&fit=crop",
    author: {
      name: "Dr. Maria Silva",
      photo: "https://images.unsplash.com/photo-1494790108755-2616b332c96c?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 6,
    title: "Nova tecnologia revoluciona tratamento de câncer no Brasil",
    excerpt: "Hospital pioneiro implementa técnica inovadora que aumenta em 40% as chances de cura em pacientes oncológicos.",
    category: "Saúde",
    categoryColor: "red-600",
    publishedAt: "1 de julho de 2024",
    readTime: "7 min",
    image: "https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=300&fit=crop",
    author: {
      name: "Patricia Costa",
      photo: "https://images.unsplash.com/photo-1544725176-7c40e5a71c5e?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 7,
    title: "Empresas brasileiras lideram inovação em tecnologia sustentável",
    excerpt: "Startups nacionais desenvolvem soluções para energia limpa que chamam atenção de investidores internacionais.",
    category: "Tecnologia",
    categoryColor: "indigo-600",
    publishedAt: "1 de julho de 2024",
    readTime: "5 min",
    image: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=400&h=300&fit=crop",
    author: {
      name: "Lucas Santos",
      photo: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 8,
    title: "MEC lança programa nacional de educação digital",
    excerpt: "Iniciativa prevê distribuição de tablets e capacitação de professores para modernizar o ensino público brasileiro.",
    category: "Educação",
    categoryColor: "purple-600",
    publishedAt: "30 de junho de 2024",
    readTime: "4 min",
    image: "https://images.unsplash.com/photo-1606761568499-6d2451b23c66?w=400&h=300&fit=crop",
    author: {
      name: "Maria Oliveira",
      photo: "https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 9,
    title: "Brasil conquista medalha de ouro no Mundial de Natação",
    excerpt: "Atleta brasileira quebra recorde sul-americano e garante primeira colocação nos 200m livre feminino.",
    category: "Esporte",
    categoryColor: "orange-600",
    publishedAt: "30 de junho de 2024",
    readTime: "3 min",
    image: "https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=300&fit=crop",
    author: {
      name: "Roberto Silva",
      photo: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 10,
    title: "Inflação apresenta queda pelo terceiro mês consecutivo",
    excerpt: "IPCA registra 0,15% em junho, resultado abaixo das expectativas do mercado financeiro brasileiro.",
    category: "Economia",
    categoryColor: "green-600",
    publishedAt: "29 de junho de 2024",
    readTime: "4 min",
    image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=300&fit=crop",
    author: {
      name: "Pedro Almeida",
      photo: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 11,
    title: "Festival Internacional de Cinema movimenta cultura nacional",
    excerpt: "Evento reúne produções de 45 países e destaca crescimento da cinematografia brasileira no cenário mundial.",
    category: "Cultura",
    categoryColor: "pink-600",
    publishedAt: "29 de junho de 2024",
    readTime: "5 min",
    image: "https://images.unsplash.com/photo-1489599162410-2a1e3ecad4db?w=400&h=300&fit=crop",
    author: {
      name: "Juliana Costa",
      photo: "https://images.unsplash.com/photo-1494790108755-2616b332c96c?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 12,
    title: "Pesquisadores brasileiros desenvolvem vacina contra dengue",
    excerpt: "Estudo clínico mostra eficácia de 85% em prevenção da doença, com aprovação prevista para 2025.",
    category: "Saúde",
    categoryColor: "red-600",
    publishedAt: "28 de junho de 2024",
    readTime: "6 min",
    image: "https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=400&h=300&fit=crop",
    author: {
      name: "Dr. Anderson Lima",
      photo: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  }
])

// Categorias disponíveis para filtro
const categories = ref([
  { name: 'Todas', value: 'all', color: 'gray-600', count: 0 },
  { name: 'Política', value: 'Política', color: 'blue-600', count: 0 },
  { name: 'Economia', value: 'Economia', color: 'green-600', count: 0 },
  { name: 'Esporte', value: 'Esporte', color: 'orange-600', count: 0 },
  { name: 'Saúde', value: 'Saúde', color: 'red-600', count: 0 },
  { name: 'Educação', value: 'Educação', color: 'purple-600', count: 0 },
  { name: 'Tecnologia', value: 'Tecnologia', color: 'indigo-600', count: 0 },
  { name: 'Cultura', value: 'Cultura', color: 'pink-600', count: 0 }
])

// Notícias mais lidas
const mostReadNews = ref([
  {
    id: 13,
    title: "Governo anuncia aumento do salário mínimo para 2025",
    category: "Política",
    publishedAt: "2 de julho de 2024",
    views: 15420
  },
  {
    id: 14,
    title: "Bolsa de valores atinge novo recorde histórico",
    category: "Economia",
    publishedAt: "1 de julho de 2024",
    views: 12380
  },
  {
    id: 15,
    title: "Brasil conquista ouro no Mundial de Natação",
    category: "Esporte",
    publishedAt: "30 de junho de 2024",
    views: 11250
  },
  {
    id: 16,
    title: "Nova vacina contra dengue aprovada pela Anvisa",
    category: "Saúde",
    publishedAt: "29 de junho de 2024",
    views: 10890
  },
  {
    id: 17,
    title: "Startups brasileiras recebem investimento recorde",
    category: "Tecnologia",
    publishedAt: "28 de junho de 2024",
    views: 9670
  }
])

// Controles de filtro e ordenação
const selectedCategory = ref('all')
const sortBy = ref('date') // 'date' ou 'views'
const currentPage = ref(1)
const newsPerPage = 9
const totalNews = ref(156) // Total simulado

// Computed para filtrar notícias
const filteredNews = computed(() => {
  let filtered = [...allNews.value]
  
  // Filtrar por categoria
  if (selectedCategory.value !== 'all') {
    filtered = filtered.filter(news => news.category === selectedCategory.value)
  }
  
  // Ordenar
  if (sortBy.value === 'views') {
    // Simular views baseado no ID (notícias mais recentes têm mais views)
    filtered.sort((a, b) => b.id - a.id)
  } else {
    // Ordenar por data (mais recente primeiro)
    filtered.sort((a, b) => new Date(b.publishedAt) - new Date(a.publishedAt))
  }
  
  return filtered
})

// Computed para paginação
const paginatedNews = computed(() => {
  const start = (currentPage.value - 1) * newsPerPage
  const end = start + newsPerPage
  return filteredNews.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(filteredNews.value.length / newsPerPage)
})

// Função para navegar páginas
const goToPage = (page) => {
  currentPage.value = page
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Função para filtrar por categoria
const filterByCategory = (category) => {
  selectedCategory.value = category
  currentPage.value = 1
}

// Função para alterar ordenação
const changeSortBy = (sort) => {
  sortBy.value = sort
  currentPage.value = 1
}

// Função para formatar número de visualizações
const formatViews = (views) => {
  if (views >= 1000) {
    return `${(views / 1000).toFixed(1)}k`
  }
  return views.toString()
}

// Calcular contadores de categoria
const updateCategoryCounts = () => {
  categories.value.forEach(category => {
    if (category.value === 'all') {
      category.count = allNews.value.length + 1 // +1 para incluir a featured news
    } else {
      category.count = allNews.value.filter(news => news.category === category.value).length
      if (featuredNews.value.category === category.value) {
        category.count++
      }
    }
  })
}

onMounted(() => {
  updateCategoryCounts()
})
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Cabeçalho da Página -->
    <section class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="text-sm text-cinza mb-4">
          <span>Home</span>
          <span class="mx-2">></span>
          <span class="text-azul-oxford">{{ pageData.name }}</span>
        </nav>

        <!-- Título da Página -->
        <div class="flex items-center space-x-4 mb-6">
          <div class="text-4xl">{{ pageData.icon }}</div>
          <div>
            <h1 class="text-4xl font-bold text-azul-oxford">{{ pageData.name }}</h1>
            <p class="text-gray-600 mt-2">{{ pageData.description }}</p>
          </div>
        </div>

        <!-- Filtros e Controles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-4">
          <!-- Filtros de Categoria -->
          <div class="flex flex-wrap gap-2">
            <button
              v-for="category in categories"
              :key="category.value"
              @click="filterByCategory(category.value)"
              :class="[
                'px-4 py-2 rounded-full text-sm font-medium transition-all',
                selectedCategory === category.value
                  ? `bg-${category.color} text-white`
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              {{ category.name }}
              <span class="ml-1 text-xs opacity-75">({{ category.count }})</span>
            </button>
          </div>

          <!-- Ordenação -->
          <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">Ordenar por:</span>
            <select 
              v-model="sortBy"
              @change="changeSortBy(sortBy)"
              class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-azul-oxford"
            >
              <option value="date">Mais recentes</option>
              <option value="views">Mais lidas</option>
            </select>
          </div>
        </div>

        <!-- Estatísticas -->
        <div class="flex items-center space-x-6 text-sm text-cinza">
          <span>{{ filteredNews.length }} notícias encontradas</span>
          <span>Atualizado hoje</span>
          <span>Última atualização: 16:30</span>
        </div>
      </div>
    </section>

    <!-- Espaço para Propaganda - Banner Principal -->
    <section class="bg-white border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
          <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
          <p class="text-gray-400 text-xs">Banner Principal 970x250</p>
        </div>
      </div>
    </section>

    <!-- Conteúdo Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Conteúdo Principal -->
        <div class="lg:col-span-8">
          <!-- Notícia Principal em Destaque (apenas se "Todas" estiver selecionado) -->
          <article v-if="selectedCategory === 'all'" class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
            <div class="relative">
              <img 
                :src="featuredNews.image" 
                :alt="featuredNews.title"
                class="w-full h-80 object-cover"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              
              <!-- Badge de Breaking News -->
              <div v-if="featuredNews.isBreaking" class="absolute top-4 left-4">
                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold animate-pulse">
                  🔴 URGENTE
                </span>
              </div>

              <!-- Conteúdo sobreposto -->
              <div class="absolute bottom-0 left-0 right-0 p-6">
                <div class="flex items-center space-x-2 mb-2">
                  <span class="bg-azul-lazuli text-white px-2 py-1 rounded text-xs font-medium">
                    {{ featuredNews.category }}
                  </span>
                  <span class="text-white text-sm">{{ featuredNews.publishedAt }}</span>
                  <span class="text-white text-sm">{{ featuredNews.readTime }} de leitura</span>
                </div>
                
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-3 leading-tight">
                  {{ featuredNews.title }}
                </h2>
                
                <p class="text-gray-200 text-sm mb-4 line-clamp-2">
                  {{ featuredNews.excerpt }}
                </p>
                
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <img 
                      :src="featuredNews.author.photo" 
                      :alt="featuredNews.author.name"
                      class="w-8 h-8 rounded-full border-2 border-white"
                    />
                    <div>
                      <p class="text-white text-sm font-medium">{{ featuredNews.author.name }}</p>
                      <p class="text-gray-300 text-xs">{{ featuredNews.author.role }}</p>
                    </div>
                  </div>
                  
                  <button class="bg-azul-oxford hover:bg-azul-noite text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    Ler Matéria
                  </button>
                </div>
              </div>
            </div>
          </article>

          <!-- Espaço para Propaganda - Meio do Conteúdo -->
          <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-8">
            <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
            <p class="text-gray-400 text-xs">Banner Horizontal 728x90</p>
          </div>

          <!-- Grade de Notícias -->
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            <article 
              v-for="news in paginatedNews" 
              :key="news.id"
              class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden"
            >
              <div class="relative">
                <img 
                  :src="news.image" 
                  :alt="news.title"
                  class="w-full h-48 object-cover"
                />
                <div v-if="news.isBreaking" class="absolute top-2 left-2">
                  <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold animate-pulse">
                    🔴 URGENTE
                  </span>
                </div>
              </div>
              
              <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                  <span :class="`bg-${news.categoryColor} text-white px-2 py-1 rounded text-xs font-medium`">
                    {{ news.category }}
                  </span>
                  <span class="text-cinza text-xs">{{ news.publishedAt }}</span>
                </div>
                
                <h3 class="text-lg font-semibold text-azul-oxford mb-2 line-clamp-2">
                  {{ news.title }}
                </h3>
                
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                  {{ news.excerpt }}
                </p>
                
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <img 
                      :src="news.author.photo" 
                      :alt="news.author.name"
                      class="w-6 h-6 rounded-full"
                    />
                    <span class="text-sm text-cinza">{{ news.author.name }}</span>
                  </div>
                  
                  <div class="flex items-center space-x-4">
                    <span class="text-xs text-cinza">{{ news.readTime }} de leitura</span>
                    <button class="text-azul-lazuli hover:text-azul-oxford text-sm font-medium">
                      Leia mais
                    </button>
                  </div>
                </div>
              </div>
            </article>
          </div>

          <!-- Paginação -->
          <div class="flex items-center justify-center space-x-2 mb-8" v-if="totalPages > 1">
            <button 
              @click="goToPage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Anterior
            </button>
            
            <button
              v-for="page in totalPages"
              :key="page"
              @click="goToPage(page)"
              :class="[
                'px-4 py-2 border rounded-lg text-sm font-medium',
                currentPage === page
                  ? 'bg-azul-oxford text-white border-azul-oxford'
                  : 'border-gray-300 text-gray-700 hover:bg-gray-50'
              ]"
            >
              {{ page }}
            </button>
            
            <button 
              @click="goToPage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Próximo
            </button>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4">
          <!-- Espaço para Propaganda - Sidebar Topo -->
          <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-8">
            <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
            <p class="text-gray-400 text-xs">Banner Lateral 300x250</p>
          </div>

          <!-- Mais Lidas -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-xl font-bold text-azul-oxford mb-4 flex items-center">
              <span class="text-2xl mr-2">📈</span>
              Mais Lidas
            </h3>
            <div class="space-y-4">
              <article 
                v-for="(news, index) in mostReadNews" 
                :key="news.id"
                class="flex space-x-3 pb-4 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 -mx-2 px-2 py-2 rounded transition-colors cursor-pointer"
              >
                <div class="flex-shrink-0">
                  <span class="text-2xl font-bold text-celeste">{{ index + 1 }}</span>
                </div>
                <div class="flex-1">
                  <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">
                      {{ news.category }}
                    </span>
                  </div>
                  <h4 class="font-semibold text-azul-oxford text-sm mb-1 line-clamp-2">
                    {{ news.title }}
                  </h4>
                  <div class="flex items-center justify-between">
                    <p class="text-cinza text-xs">{{ news.publishedAt }}</p>
                    <span class="text-xs text-green-600 font-medium">
                      {{ formatViews(news.views) }} visualizações
                    </span>
                  </div>
                </div>
              </article>
            </div>
          </div>

          <!-- Filtro Rápido por Categoria -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-xl font-bold text-azul-oxford mb-4 flex items-center">
              <span class="text-2xl mr-2">📂</span>
              Categorias
            </h3>
            <div class="space-y-3">
              <button 
                v-for="category in categories.slice(1)" 
                :key="category.value"
                @click="filterByCategory(category.value)"
                :class="[
                  'flex items-center justify-between w-full p-3 rounded-lg transition-colors text-left',
                  selectedCategory === category.value
                    ? 'bg-azul-oxford text-white'
                    : 'hover:bg-gray-50'
                ]"
              >
                <div class="flex items-center space-x-3">
                  <div class="w-3 h-3 rounded-full" :class="`bg-${category.color}`"></div>
                  <span class="font-medium">{{ category.name }}</span>
                </div>
                <span class="text-sm opacity-75 bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                  {{ category.count }}
                </span>
              </button>
            </div>
          </div>

          <!-- Newsletter -->
          <div class="bg-azul-oxford rounded-lg p-6 text-white mb-8">
            <h3 class="text-xl font-bold mb-4 flex items-center">
              <span class="text-2xl mr-2">📧</span>
              Newsletter
            </h3>
            <p class="text-sm mb-4">
              Receba todas as principais notícias em seu email
            </p>
            <div class="space-y-3">
              <input 
                type="email" 
                placeholder="Seu email"
                class="w-full px-3 py-2 rounded text-azul-oxford"
              />
              <button class="w-full bg-celeste hover:bg-azul-lazuli text-azul-oxford font-medium py-2 rounded transition-all">
                Inscrever-se
              </button>
            </div>
          </div>

          <!-- Últimas da Política -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-xl font-bold text-azul-oxford mb-4 flex items-center">
              <span class="text-2xl mr-2">🏛️</span>
              Últimas da Política
            </h3>
            <div class="space-y-3">
              <article class="pb-3 border-b border-gray-200 last:border-b-0">
                <h4 class="font-semibold text-sm text-azul-oxford mb-1 line-clamp-2">
                  Senado aprova projeto de modernização eleitoral
                </h4>
                <p class="text-xs text-cinza">Há 2 horas</p>
              </article>
              <article class="pb-3 border-b border-gray-200 last:border-b-0">
                <h4 class="font-semibold text-sm text-azul-oxford mb-1 line-clamp-2">
                  Ministros se reúnem para discutir orçamento 2025
                </h4>
                <p class="text-xs text-cinza">Há 4 horas</p>
              </article>
              <article class="pb-3 border-b border-gray-200 last:border-b-0">
                <h4 class="font-semibold text-sm text-azul-oxford mb-1 line-clamp-2">
                  Governadores debatem segurança pública
                </h4>
                <p class="text-xs text-cinza">Há 6 horas</p>
              </article>
            </div>
            <button class="text-azul-lazuli hover:text-azul-oxford text-sm font-medium mt-3">
              Ver todas as notícias de política →
            </button>
          </div>

          <!-- Espaço para Propaganda - Sidebar Rodapé -->
          <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
            <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
            <p class="text-gray-400 text-xs">Banner Vertical 300x600</p>
          </div>
        </div>
      </div>

      <!-- Seção de Notícias por Categoria (quando não é "Todas") -->
      <div v-if="selectedCategory !== 'all'" class="mt-12">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-azul-oxford">
            Todas as notícias de {{ selectedCategory }}
          </h2>
          <button 
            @click="filterByCategory('all')"
            class="text-azul-lazuli hover:text-azul-oxford text-sm font-medium"
          >
            Ver todas as categorias
          </button>
        </div>
        
        <!-- Estatísticas da categoria selecionada -->
        <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <div class="w-4 h-4 rounded-full" :class="`bg-${categories.find(c => c.value === selectedCategory)?.color}`"></div>
              <span class="font-medium text-azul-oxford">{{ selectedCategory }}</span>
              <span class="text-sm text-cinza">{{ filteredNews.length }} notícias</span>
            </div>
            <div class="text-sm text-cinza">
              Ordenado por: {{ sortBy === 'date' ? 'Mais recentes' : 'Mais lidas' }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}

/* Personalização do select */
select:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(30, 64, 175, 0.1);
}

/* Hover effects para cards */
.hover\:shadow-md:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Smooth transitions */
* {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>