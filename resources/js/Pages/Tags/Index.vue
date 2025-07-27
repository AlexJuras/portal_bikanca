<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin });

  name: 'Inicio'

    const currentSlide = ref(0)
    let slideInterval = null
    
    // Dados mockados da manchete principal
    const featuredNews = {
      id: 1,
      title: "Governo Federal anuncia novo pacote de investimentos em infraestrutura para o interior do país",
      excerpt: "O anúncio foi feito durante coletiva de imprensa em Brasília e prevê investimentos de R$ 50 bilhões em rodovias, ferrovias e aeroportos regionais. O plano deve beneficiar mais de 200 municípios brasileiros nos próximos três anos.",
      category: "Política",
      publishedAt: "4 de julho de 2024",
      author: {
        name: "Marcio Bikanca",
        role: "Repórter político",
        photo: "https://images.unsplash.com/photo-1494790108755-2616b332c96c?w=100&h=100&fit=crop&crop=face"
      }
    }
    
    // Imagens do carrossel
    const featuredImages = [
      {
        url: "https://images.unsplash.com/photo-1606868306217-dbf5046868d2?w=800&h=600&fit=crop",
        alt: "Coletiva de imprensa em Brasília"
      },
      {
        url: "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop",
        alt: "Obras de infraestrutura"
      },
      {
        url: "https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=800&h=600&fit=crop",
        alt: "Rodovia em construção"
      },
      {
        url: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop",
        alt: "Aeroporto regional"
      }
    ]
    
    // Notícias em destaque
    const highlightedNews = [
      {
        id: 2,
        title: "Prefeito de São Paulo visita obras do novo hospital municipal",
        excerpt: "A unidade deve ser inaugurada no segundo semestre de 2024 e atenderá mais de 50 mil pessoas por mês na zona sul da capital.",
        category: "Municípios",
        publishedAt: "3 de julho de 2024",
        image: "https://images.unsplash.com/photo-1551601651-2a8555f1a136?w=400&h=300&fit=crop",
        author: {
          name: "João Silva",
          photo: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=50&h=50&fit=crop&crop=face"
        }
      },
      {
        id: 3,
        title: "Operação da Polícia Federal combate esquema de corrupção em licitações",
        excerpt: "Foram cumpridos 15 mandados de prisão e 32 de busca e apreensão em seis estados brasileiros.",
        category: "Criminalidade",
        publishedAt: "2 de julho de 2024",
        image: "https://images.unsplash.com/photo-1589578228447-e1a4e481c6c8?w=400&h=300&fit=crop",
        author: {
          name: "Ana Costa",
          photo: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=50&h=50&fit=crop&crop=face"
        }
      },
      {
        id: 4,
        title: "Seleção Brasileira se prepara para próxima rodada das Eliminatórias",
        excerpt: "Time comandado por Dorival Júnior treina forte visando os confrontos contra Venezuela e Uruguai.",
        category: "Esporte",
        publishedAt: "1 de julho de 2024",
        image: "https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=300&fit=crop",
        author: {
          name: "Carlos Oliveira",
          photo: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=50&h=50&fit=crop&crop=face"
        }
      }
    ]
    
    // Notícias por categoria
    const politicsNews = [
      {
        id: 5,
        title: "Congresso Nacional aprova nova lei de transparência pública",
        publishedAt: "30 de junho de 2024",
        image: "https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=200&h=150&fit=crop"
      },
      {
        id: 6,
        title: "Ministro da Saúde anuncia ampliação do programa de vacinação",
        publishedAt: "29 de junho de 2024",
        image: "https://images.unsplash.com/photo-1584515979956-d9f6e5d493a1?w=200&h=150&fit=crop"
      },
      {
        id: 7,
        title: "Governadores se reúnem para discutir políticas de segurança",
        publishedAt: "28 de junho de 2024",
        image: "https://images.unsplash.com/photo-1577962917302-cd874c99b3d2?w=200&h=150&fit=crop"
      }
    ]
    
    const sportsNews = [
      {
        id: 8,
        title: "Flamengo contrata novo técnico para a próxima temporada",
        publishedAt: "30 de junho de 2024",
        image: "https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=200&h=150&fit=crop"
      },
      {
        id: 9,
        title: "Brasil garante vaga nas Olimpíadas de Paris no vôlei feminino",
        publishedAt: "29 de junho de 2024",
        image: "https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=200&h=150&fit=crop"
      },
      {
        id: 10,
        title: "Fórmula 1 confirma GP do Brasil em Interlagos até 2030",
        publishedAt: "28 de junho de 2024",
        image: "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=200&h=150&fit=crop"
      }
    ]
    
    const economyNews = [
      {
        id: 11,
        title: "Banco Central mantém taxa Selic em 10,50% ao ano",
        publishedAt: "30 de junho de 2024",
        image: "https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=200&h=150&fit=crop"
      },
      {
        id: 12,
        title: "Dólar fecha em alta após divulgação de dados econômicos",
        publishedAt: "29 de junho de 2024",
        image: "https://images.unsplash.com/photo-1559526324-c1f275fbfa32?w=200&h=150&fit=crop"
      },
      {
        id: 13,
        title: "Setor agropecuário registra crescimento de 8% no primeiro semestre",
        publishedAt: "28 de junho de 2024",
        image: "https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=200&h=150&fit=crop"
      }
    ]
    
    // Funções do carrossel
    const nextSlide = () => {
      currentSlide.value = (currentSlide.value + 1) % featuredImages.length
    }
    
    const previousSlide = () => {
      currentSlide.value = currentSlide.value === 0 ? featuredImages.length - 1 : currentSlide.value - 1
    }
    
    // Auto-play do carrossel
    const startAutoPlay = () => {
      slideInterval = setInterval(nextSlide, 3000)
    }
    
    const stopAutoPlay = () => {
      if (slideInterval) {
        clearInterval(slideInterval)
      }
    }
    
    onMounted(() => {
      startAutoPlay()
    })
    
    onUnmounted(() => {
      stopAutoPlay()
    })
</script>

<style scoped>
</style>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Manchete Principal -->
    <section class="bg-white shadow-sm">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="relative">
          <!-- Carrossel de Imagens -->
          <div class="relative overflow-hidden rounded-lg shadow-lg">
            <div 
              class="flex transition-transform duration-500 ease-in-out"
              :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
            >
              <div 
                v-for="(image, index) in featuredImages" 
                :key="index"
                class="w-full flex-shrink-0"
              >
                <img 
                  :src="image.url" 
                  :alt="image.alt"
                  class="w-full h-120 object-cover"
                />
              </div>
            </div>
            
            <!-- Overlay para melhor legibilidade -->
            <div class="absolute inset-0 bg-black opacity-50"></div>
            
            <!-- Navegação do Carrossel -->
            <button 
              @click="previousSlide"
              class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-azul-oxford bg-opacity-70 hover:bg-opacity-90 text-white p-2 rounded-full transition-all duration-200 z-20"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            
            <button 
              @click="nextSlide"
              class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-azul-oxford bg-opacity-70 hover:bg-opacity-90 text-white p-2 rounded-full transition-all duration-200 z-20"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </button>
            
            <!-- Indicadores -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
              <button
                v-for="(image, index) in featuredImages"
                :key="index"
                @click="currentSlide = index"
                class="w-3 h-3 rounded-full transition-all duration-200"
                :class="currentSlide === index ? 'bg-celeste' : 'bg-white bg-opacity-50'"
              ></button>
            </div>
          </div>
          
          <!-- Conteúdo da Manchete Sobreposto -->
          <div class="absolute inset-0 flex items-center justify-center z-10">
            <div class="text-center space-y-6 max-w-4xl mx-auto px-8">
              <div class="space-y-2">
                <div class="flex items-center justify-center space-x-2">
                  <span class="absolute top-4 left-4 bg-azul-lazuli text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ featuredNews.category }}
                  </span>
                  <span class="absolute top-4 right-4 text-white font-bold text-sm">{{ featuredNews.publishedAt }}</span>
                </div>
                <h1 class="absolute bottom-20 left-0 w-full text-4xl md:text-6xl font-bold text-white leading-tight drop-shadow-lg">
                  {{ featuredNews.title }}
                </h1>
              </div>
              
              <!-- <p class="text-lg text-white leading-relaxed drop-shadow-md max-w-3xl mx-auto">
                {{ featuredNews.excerpt }}
              </p> -->
              
              <div class="absolute bottom-10 left-1/2 -translate-x-1/2 items-center justify-center">
                <div class="flex items-center space-x-2">
                  <img 
                    :src="'/User.png'" 
                    :alt="featuredNews.author.name"
                    class="w-10 h-10 rounded-full border-2 border-white"
                  />
                  <div>
                    <p class="font-medium text-white">{{ featuredNews.author.name }}</p>
                    <p class="text-sm text-gray-200">{{ featuredNews.author.role }}</p>
                  </div>
                </div>
                <!-- <button class="bg-azul-oxford hover:bg-azul-noite text-white px-8 py-3 rounded-lg font-medium transition-all shadow-lg">
                  Ler Matéria Completa
                </button> -->
              </div>
              
              
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Notícias em Destaque -->
    <section class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-3xl font-bold text-azul-oxford">Notícias em Destaque</h2>
          <div class="h-1 flex-1 bg-celeste ml-6"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article 
            v-for="news in highlightedNews" 
            :key="news.id"
            class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden"
          >
            <img 
              :src="news.image" 
              :alt="news.title"
              class="w-full h-48 object-cover"
            />
            <div class="p-6">
              <div class="flex items-center justify-between mb-2">
                <span class="bg-azul-lazuli text-white px-2 py-1 rounded text-xs font-medium">
                  {{ news.category }}
                </span>
                <span class="text-cinza text-sm">{{ news.publishedAt }}</span>
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
                <button class="text-azul-lazuli hover:text-azul-oxford text-sm font-medium">
                  Leia mais
                </button>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>
    
    <!-- Seções por Categoria -->
    <section class="py-12 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Política -->
          <div class="space-y-6">
            <div class="flex items-center space-x-3">
              <h3 class="text-2xl font-bold text-azul-oxford">Política</h3>
              <div class="h-1 flex-1 bg-azul-lazuli"></div>
            </div>
            <div class="space-y-4">
              <article 
                v-for="news in politicsNews" 
                :key="news.id"
                class="flex space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors"
              >
                <img 
                  :src="news.image" 
                  :alt="news.title"
                  class="w-20 h-20 object-cover rounded-lg flex-shrink-0"
                />
                <div class="flex-1">
                  <h4 class="font-semibold text-azul-oxford text-sm mb-1 line-clamp-2">
                    {{ news.title }}
                  </h4>
                  <p class="text-cinza text-xs">{{ news.publishedAt }}</p>
                </div>
              </article>
            </div>
          </div>
          
          <!-- Esporte -->
          <div class="space-y-6">
            <div class="flex items-center space-x-3">
              <h3 class="text-2xl font-bold text-azul-oxford">Esporte</h3>
              <div class="h-1 flex-1 bg-azul-lazuli"></div>
            </div>
            <div class="space-y-4">
              <article 
                v-for="news in sportsNews" 
                :key="news.id"
                class="flex space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors"
              >
                <img 
                  :src="news.image" 
                  :alt="news.title"
                  class="w-20 h-20 object-cover rounded-lg flex-shrink-0"
                />
                <div class="flex-1">
                  <h4 class="font-semibold text-azul-oxford text-sm mb-1 line-clamp-2">
                    {{ news.title }}
                  </h4>
                  <p class="text-cinza text-xs">{{ news.publishedAt }}</p>
                </div>
              </article>
            </div>
          </div>
          
          <!-- Economia -->
          <div class="space-y-6">
            <div class="flex items-center space-x-3">
              <h3 class="text-2xl font-bold text-azul-oxford">Economia</h3>
              <div class="h-1 flex-1 bg-azul-lazuli"></div>
            </div>
            <div class="space-y-4">
              <article 
                v-for="news in economyNews" 
                :key="news.id"
                class="flex space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors"
              >
                <img 
                  :src="news.image" 
                  :alt="news.title"
                  class="w-20 h-20 object-cover rounded-lg flex-shrink-0"
                />
                <div class="flex-1">
                  <h4 class="font-semibold text-azul-oxford text-sm mb-1 line-clamp-2">
                    {{ news.title }}
                  </h4>
                  <p class="text-cinza text-xs">{{ news.publishedAt }}</p>
                </div>
              </article>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Newsletter -->
    <section class="py-12 bg-azul-oxford">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-6">
          <h2 class="text-3xl font-bold text-white">Fique por dentro das notícias</h2>
          <p class="text-cinza max-w-2xl mx-auto">
            Receba as principais notícias do Portal Bikanca diretamente em seu email. 
            Cadastre-se em nossa newsletter e não perca nenhuma informação importante.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <input 
              type="email" 
              placeholder="Digite seu email"
              class="flex-1 px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-celeste"
            />
            <button class="bg-celeste hover:bg-azul-lazuli text-azul-oxford font-medium px-6 py-3 rounded-lg transition-all">
              Inscrever-se
            </button>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>



