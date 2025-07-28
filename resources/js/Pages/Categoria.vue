<script setup>
import { ref, onMounted, computed } from 'vue'
import Principal from '@/Layouts/Principal.vue'

defineOptions({ layout: Principal });

// Dados da categoria
const categoryData = ref({
  name: 'Política',
  description: 'Acompanhe as principais notícias sobre política brasileira, decisões governamentais, eleições e debates políticos.',
  color: 'azul-lazuli',
  icon: '🏛️'
})

// Notícia principal em destaque
const featuredNews = ref({
  id: 1,
  title: "Governo Federal anuncia novo pacote de investimentos em infraestrutura para o interior do país",
  excerpt: "O anúncio foi feito durante coletiva de imprensa em Brasília e prevê investimentos de R$ 50 bilhões em rodovias, ferrovias e aeroportos regionais. O plano deve beneficiar mais de 200 municípios brasileiros nos próximos três anos.",
  category: "Política",
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

// Lista de notícias da categoria
const politicsNews = ref([
  {
    id: 2,
    title: "Congresso Nacional aprova nova lei de transparência pública",
    excerpt: "A medida estabelece novas regras para acesso a informações governamentais e fortalece os mecanismos de controle social.",
    category: "Política",
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
    title: "Ministro da Saúde anuncia ampliação do programa de vacinação",
    excerpt: "Nova fase do programa nacional de imunização contemplará mais 15 milhões de brasileiros em todo o país.",
    category: "Política",
    publishedAt: "2 de julho de 2024",
    readTime: "3 min",
    image: "https://images.unsplash.com/photo-1584515979956-d9f6e5d493a1?w=400&h=300&fit=crop",
    author: {
      name: "Carlos Mendes",
      photo: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 4,
    title: "Governadores se reúnem para discutir políticas de segurança",
    excerpt: "Encontro em Brasília debate integração entre forças estaduais e federais para combater a criminalidade.",
    category: "Política",
    publishedAt: "1 de julho de 2024",
    readTime: "6 min",
    image: "https://images.unsplash.com/photo-1577962917302-cd874c99b3d2?w=400&h=300&fit=crop",
    author: {
      name: "Roberto Silva",
      photo: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 5,
    title: "Supremo Tribunal Federal julga importante caso sobre direitos constitucionais",
    excerpt: "Decisão pode impactar interpretação de direitos fundamentais e estabelecer novo precedente jurídico.",
    category: "Política",
    publishedAt: "30 de junho de 2024",
    readTime: "7 min",
    image: "https://images.unsplash.com/photo-1589578228447-e1a4e481c6c8?w=400&h=300&fit=crop",
    author: {
      name: "Juliana Costa",
      photo: "https://images.unsplash.com/photo-1494790108755-2616b332c96c?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 6,
    title: "Senado aprova projeto que moderniza legislação eleitoral",
    excerpt: "Novas regras visam aumentar transparência e segurança nas eleições, incluindo medidas contra fake news.",
    category: "Política",
    publishedAt: "29 de junho de 2024",
    readTime: "5 min",
    image: "https://images.unsplash.com/photo-1559526324-c1f275fbfa32?w=400&h=300&fit=crop",
    author: {
      name: "Pedro Almeida",
      photo: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 7,
    title: "Presidente participa de reunião do Mercosul em Buenos Aires",
    excerpt: "Encontro discute acordos comerciais e integração regional entre países da América do Sul.",
    category: "Política",
    publishedAt: "28 de junho de 2024",
    readTime: "4 min",
    image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=300&fit=crop",
    author: {
      name: "Luciana Ferreira",
      photo: "https://images.unsplash.com/photo-1544725176-7c40e5a71c5e?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  },
  {
    id: 8,
    title: "Câmara dos Deputados debate reforma do sistema tributário",
    excerpt: "Proposta prevê simplificação de impostos e pode impactar economia nacional nos próximos anos.",
    category: "Política",
    publishedAt: "27 de junho de 2024",
    readTime: "6 min",
    image: "https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&h=300&fit=crop",
    author: {
      name: "Marcio Bikanca",
      photo: "/User.png"
    },
    isBreaking: false
  },
  {
    id: 9,
    title: "Ministério da Educação lança novo programa de alfabetização",
    excerpt: "Iniciativa visa reduzir índices de analfabetismo e melhorar qualidade da educação básica no país.",
    category: "Política",
    publishedAt: "26 de junho de 2024",
    readTime: "4 min",
    image: "https://images.unsplash.com/photo-1606761568499-6d2451b23c66?w=400&h=300&fit=crop",
    author: {
      name: "Maria Oliveira",
      photo: "https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=50&h=50&fit=crop&crop=face"
    },
    isBreaking: false
  }
])

// Outras categorias para sidebar
const otherCategories = ref([
  {
    name: 'Economia',
    count: 24,
    color: 'green-500'
  },
  {
    name: 'Esporte',
    count: 18,
    color: 'orange-500'
  },
  {
    name: 'Saúde',
    count: 15,
    color: 'red-500'
  },
  {
    name: 'Educação',
    count: 12,
    color: 'purple-500'
  },
  {
    name: 'Tecnologia',
    count: 9,
    color: 'blue-500'
  }
])

// Notícias mais lidas
const mostReadNews = ref([
  {
    id: 10,
    title: "Governo anuncia aumento do salário mínimo para 2025",
    publishedAt: "2 de julho de 2024",
    views: 15420
  },
  {
    id: 11,
    title: "STF decide sobre marco temporal das terras indígenas",
    publishedAt: "1 de julho de 2024",
    views: 12380
  },
  {
    id: 12,
    title: "Congresso aprova orçamento para obras de infraestrutura",
    publishedAt: "30 de junho de 2024",
    views: 11250
  },
  {
    id: 13,
    title: "Eleições municipais: TSE divulga calendário oficial",
    publishedAt: "29 de junho de 2024",
    views: 10890
  },
  {
    id: 14,
    title: "Ministro da Fazenda fala sobre política econômica",
    publishedAt: "28 de junho de 2024",
    views: 9670
  }
])

// Controle de paginação
const currentPage = ref(1)
const newsPerPage = 6
const totalNews = ref(42) // Total simulado

// Computed para paginação
const paginatedNews = computed(() => {
  const start = (currentPage.value - 1) * newsPerPage
  const end = start + newsPerPage
  return politicsNews.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(totalNews.value / newsPerPage)
})

// Função para navegar páginas
const goToPage = (page) => {
  currentPage.value = page
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Função para formatar número de visualizações
const formatViews = (views) => {
  if (views >= 1000) {
    return `${(views / 1000).toFixed(1)}k`
  }
  return views.toString()
}

onMounted(() => {
  // Aqui você pode carregar as notícias da categoria via API
})
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Cabeçalho da Categoria -->
    <section class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="text-sm text-cinza mb-4">
          <span>Home</span>
          <span class="mx-2">></span>
          <span class="text-azul-oxford">{{ categoryData.name }}</span>
        </nav>

        <!-- Título da Categoria -->
        <div class="flex items-center space-x-4 mb-4">
          <div class="text-4xl">{{ categoryData.icon }}</div>
          <div>
            <h1 class="text-4xl font-bold text-azul-oxford">{{ categoryData.name }}</h1>
            <p class="text-gray-600 mt-2">{{ categoryData.description }}</p>
          </div>
        </div>

        <!-- Estatísticas -->
        <div class="flex items-center space-x-6 text-sm text-cinza">
          <span>{{ totalNews }} notícias</span>
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
          <!-- Notícia Principal em Destaque -->
          <article class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
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
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
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
                  <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
                    URGENTE
                  </span>
                </div>
              </div>
              
              <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                  <span class="bg-azul-lazuli text-white px-2 py-1 rounded text-xs font-medium">
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
          <div class="flex items-center justify-center space-x-2 mb-8">
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

          <!-- Outras Categorias -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-xl font-bold text-azul-oxford mb-4 flex items-center">
              <span class="text-2xl mr-2">📂</span>
              Outras Categorias
            </h3>
            <div class="space-y-3">
              <a 
                v-for="category in otherCategories" 
                :key="category.name"
                href="#"
                class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors"
              >
                <div class="flex items-center space-x-3">
                  <div class="w-3 h-3 rounded-full" :class="`bg-${category.color}`"></div>
                  <span class="font-medium text-azul-oxford">{{ category.name }}</span>
                </div>
                <span class="text-sm text-cinza bg-gray-100 px-2 py-1 rounded-full">
                  {{ category.count }}
                </span>
              </a>
            </div>
          </div>

          <!-- Espaço para Propaganda - Sidebar Rodapé -->
          <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
            <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
            <p class="text-gray-400 text-xs">Banner Vertical 300x600</p>
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
</style>