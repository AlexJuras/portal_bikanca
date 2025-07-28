<script setup>
import { ref, onMounted } from 'vue'
import Principal from '@/Layouts/Principal.vue'

defineOptions({ layout: Principal });

// Dados mockados da notícia
const newsData = ref({
  id: 1,
  title: "Governo Federal anuncia novo pacote de investimentos em infraestrutura para o interior do país",
  summary: "O anúncio foi feito durante coletiva de imprensa em Brasília e prevê investimentos de R$ 50 bilhões em rodovias, ferrovias e aeroportos regionais. O plano deve beneficiar mais de 200 municípios brasileiros nos próximos três anos.",
  category: "Política",
  publishedAt: "4 de julho de 2024",
  readTime: "5 min de leitura",
  author: {
    name: "Marcio Bikanca",
    role: "Repórter político",
    photo: "/User.png",
    bio: "Jornalista especializado em cobertura política, com mais de 15 anos de experiência em grandes veículos de comunicação."
  },
  images: [
    {
      url: "https://images.unsplash.com/photo-1606868306217-dbf5046868d2?w=1200&h=600&fit=crop",
      alt: "Coletiva de imprensa em Brasília",
      caption: "Ministro durante coletiva de imprensa sobre o novo pacote de investimentos"
    },
    {
      url: "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=500&fit=crop",
      alt: "Obras de infraestrutura",
      caption: "Obras de infraestrutura que serão contempladas pelo novo pacote"
    },
    {
      url: "https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=800&h=500&fit=crop",
      alt: "Rodovia em construção",
      caption: "Rodovia em construção que faz parte do plano de investimentos"
    }
  ],
  content: `
    <p>O Governo Federal anunciou nesta quinta-feira (4) um novo pacote de investimentos em infraestrutura voltado especificamente para o interior do país. O anúncio foi feito durante coletiva de imprensa em Brasília, com a presença do Ministro da Infraestrutura e diversos secretários estaduais.</p>

    <p>O plano prevê investimentos de R$ 50 bilhões distribuídos ao longo dos próximos três anos, contemplando rodovias, ferrovias e aeroportos regionais. Segundo o ministério, mais de 200 municípios brasileiros serão beneficiados diretamente pela iniciativa.</p>

    <h3>Principais investimentos previstos</h3>

    <p>Entre as principais obras anunciadas estão:</p>

    <ul>
      <li><strong>Rodovias:</strong> Pavimentação e duplicação de mais de 2.000 km de estradas federais</li>
      <li><strong>Ferrovias:</strong> Construção de 800 km de novas linhas férreas</li>
      <li><strong>Aeroportos:</strong> Modernização de 15 aeroportos regionais</li>
      <li><strong>Pontes e viadutos:</strong> Construção de 50 novas estruturas</li>
    </ul>

    <p>O ministro destacou que o foco está em regiões que historicamente receberam menos investimentos em infraestrutura, priorizando o desenvolvimento econômico dessas áreas.</p>

    <h3>Impacto econômico esperado</h3>

    <p>De acordo com estudos do ministério, o pacote deve gerar aproximadamente 300 mil empregos diretos e indiretos. Além disso, estima-se que o PIB das regiões contempladas possa crescer entre 2% e 4% nos próximos cinco anos.</p>

    <p>O secretário de Planejamento explicou que os investimentos seguirão um cronograma rigoroso, com metas trimestrais para cada projeto. "Nosso objetivo é garantir que os recursos sejam aplicados de forma eficiente e transparente", afirmou.</p>

    <h3>Próximos passos</h3>

    <p>O cronograma prevê que os primeiros projetos sejam licitados ainda no segundo semestre de 2024. As obras devem começar no início de 2025, com previsão de conclusão até 2027.</p>

    <p>Estados e municípios interessados em participar do programa devem apresentar suas propostas até o dia 30 de agosto, seguindo os critérios estabelecidos pelo ministério.</p>

    <p>A iniciativa faz parte do programa mais amplo de desenvolvimento regional do governo, que já destinou R$ 200 bilhões para infraestrutura desde 2023.</p>
  `,
  tags: ["Infraestrutura", "Governo Federal", "Investimentos", "Interior", "Desenvolvimento Regional"]
})

// Notícias relacionadas
const relatedNews = ref([
  {
    id: 2,
    title: "Ministro da Saúde anuncia ampliação do programa de vacinação",
    publishedAt: "3 de julho de 2024",
    image: "https://images.unsplash.com/photo-1584515979956-d9f6e5d493a1?w=300&h=200&fit=crop",
    category: "Saúde"
  },
  {
    id: 3,
    title: "Congresso Nacional aprova nova lei de transparência pública",
    publishedAt: "2 de julho de 2024",
    image: "https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=300&h=200&fit=crop",
    category: "Política"
  },
  {
    id: 4,
    title: "Governadores se reúnem para discutir políticas de segurança",
    publishedAt: "1 de julho de 2024",
    image: "https://images.unsplash.com/photo-1577962917302-cd874c99b3d2?w=300&h=200&fit=crop",
    category: "Segurança"
  }
])

// Funções de compartilhamento
const shareOnWhatsApp = () => {
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`${newsData.value.title} - Portal Bikanca`)
  window.open(`https://wa.me/?text=${text} ${url}`, '_blank')
}

const shareOnFacebook = () => {
  const url = encodeURIComponent(window.location.href)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank')
}

const shareOnInstagram = () => {
  // Instagram não permite compartilhamento direto via URL, então copiamos o link
  navigator.clipboard.writeText(window.location.href)
  alert('Link copiado! Cole no Instagram para compartilhar.')
}

const copyLink = () => {
  navigator.clipboard.writeText(window.location.href)
  alert('Link copiado para a área de transferência!')
}

onMounted(() => {
  // Aqui você pode carregar os dados da notícia via API
  // com base no ID da notícia na URL
})
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Cabeçalho da Notícia -->
    <article class="bg-white shadow-sm">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="text-sm text-cinza mb-4">
          <span>Home</span>
          <span class="mx-2">></span>
          <span>{{ newsData.category }}</span>
          <span class="mx-2">></span>
          <span class="text-azul-oxford">{{ newsData.title.substring(0, 50) }}...</span>
        </nav>

        <!-- Categoria e Data -->
        <div class="flex items-center justify-between mb-6">
          <span class="bg-azul-lazuli text-white px-3 py-1 rounded-full text-sm font-medium">
            {{ newsData.category }}
          </span>
          <div class="flex items-center space-x-4 text-sm text-cinza">
            <span>{{ newsData.publishedAt }}</span>
            <span>{{ newsData.readTime }}</span>
          </div>
        </div>

        <!-- Título -->
        <h1 class="text-3xl md:text-4xl font-bold text-azul-oxford leading-tight mb-6">
          {{ newsData.title }}
        </h1>

        <!-- Resumo -->
        <p class="text-lg text-gray-700 leading-relaxed mb-8 bg-gray-50 p-4 rounded-lg border-l-4 border-celeste">
          {{ newsData.summary }}
        </p>

        <!-- Autor -->
        <div class="flex items-center space-x-4 mb-8 p-4 bg-gray-50 rounded-lg">
          <img 
            :src="newsData.author.photo" 
            :alt="newsData.author.name"
            class="w-16 h-16 rounded-full border-2 border-celeste"
          />
          <div>
            <h3 class="font-semibold text-azul-oxford">{{ newsData.author.name }}</h3>
            <p class="text-sm text-cinza">{{ newsData.author.role }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ newsData.author.bio }}</p>
          </div>
        </div>

        <!-- Botões de Compartilhamento -->
        <div class="flex items-center space-x-4 mb-8 p-4 bg-gray-50 rounded-lg">
          <span class="text-sm font-medium text-azul-oxford">Compartilhar:</span>
          <button 
            @click="shareOnWhatsApp"
            class="flex items-center space-x-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
            </svg>
            <span>WhatsApp</span>
          </button>
          
          <button 
            @click="shareOnFacebook"
            class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            <span>Facebook</span>
          </button>
          
          <button 
            @click="shareOnInstagram"
            class="flex items-center space-x-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-4 py-2 rounded-lg transition-all"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
            <span>Instagram</span>
          </button>
          
          <button 
            @click="copyLink"
            class="flex items-center space-x-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span>Copiar Link</span>
          </button>
        </div>
      </div>
    </article>

    <!-- Conteúdo Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Coluna Principal -->
        <div class="lg:col-span-8">
          <!-- Imagem Principal -->
          <div class="mb-8">
            <img 
              :src="newsData.images[0].url" 
              :alt="newsData.images[0].alt"
              class="w-full h-80 object-cover rounded-lg shadow-md"
            />
            <p class="text-sm text-cinza mt-2 italic">{{ newsData.images[0].caption }}</p>
          </div>

          <!-- Conteúdo da Notícia -->
          <div class="prose prose-lg max-w-none mb-8">
            <div 
              v-html="newsData.content"
              class="text-gray-700 leading-relaxed"
            ></div>
          </div>

          <!-- Imagens Adicionais -->
          <div v-if="newsData.images.length > 1" class="mb-8">
            <h3 class="text-xl font-semibold text-azul-oxford mb-4">Galeria de Imagens</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div 
                v-for="(image, index) in newsData.images.slice(1)" 
                :key="index"
                class="space-y-2"
              >
                <img 
                  :src="image.url" 
                  :alt="image.alt"
                  class="w-full h-48 object-cover rounded-lg shadow-md"
                />
                <p class="text-sm text-cinza italic">{{ image.caption }}</p>
              </div>
            </div>
          </div>

          <!-- Tags -->
          <div class="mb-8">
            <h3 class="text-lg font-semibold text-azul-oxford mb-3">Tags</h3>
            <div class="flex flex-wrap gap-2">
              <span 
                v-for="tag in newsData.tags" 
                :key="tag"
                class="bg-celeste text-azul-oxford px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ tag }}
              </span>
            </div>
          </div>

          <!-- Espaço para Propaganda 1 -->
          <div class="mb-8 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
            <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
            <p class="text-gray-400 text-xs">Propaganda 728x90</p>
          </div>

          <!-- Notícias Relacionadas -->
          <div class="mb-8">
            <h3 class="text-2xl font-bold text-azul-oxford mb-6">Notícias Relacionadas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <article 
                v-for="news in relatedNews" 
                :key="news.id"
                class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden"
              >
                <img 
                  :src="news.image" 
                  :alt="news.title"
                  class="w-full h-32 object-cover"
                />
                <div class="p-4">
                  <span class="bg-azul-lazuli text-white px-2 py-1 rounded text-xs font-medium">
                    {{ news.category }}
                  </span>
                  <h4 class="font-semibold text-azul-oxford mt-2 mb-1 line-clamp-2">
                    {{ news.title }}
                  </h4>
                  <p class="text-cinza text-sm">{{ news.publishedAt }}</p>
                </div>
              </article>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4">
          <!-- Espaço para Propaganda 2 -->
          <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-8">
            <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
            <p class="text-gray-400 text-xs">Propaganda 300x250</p>
          </div>

          <!-- Ao Vivo -->
          <div class="bg-azul-oxford rounded-lg p-6 text-white mb-8">
            <h3 class="text-xl font-bold mb-4">Ao Vivo</h3>
            <p class="text-sm mb-4">Acompanhe nossa live!</p>
            <div class="space-y-3">
              <button class="w-full bg-celeste hover:bg-azul-lazuli text-azul-oxford font-medium py-2 rounded transition-all">
                Assistir
              </button>
            </div>
          </div>

          <!-- Mais Lidas -->
          <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-xl font-bold text-azul-oxford mb-4">Mais Lidas</h3>
            <div class="space-y-4">
              <article 
                v-for="(news, index) in relatedNews" 
                :key="news.id"
                class="flex space-x-3 pb-4 border-b border-gray-200 last:border-b-0"
              >
                <span class="text-2xl font-bold text-celeste">{{ index + 1 }}</span>
                <div class="flex-1">
                  <h4 class="font-semibold text-azul-oxford text-sm mb-1 line-clamp-2">
                    {{ news.title }}
                  </h4>
                  <p class="text-cinza text-xs">{{ news.publishedAt }}</p>
                </div>
              </article>
            </div>
          </div>

          <!-- Espaço para Propaganda 3 -->
          <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
            <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
            <p class="text-gray-400 text-xs">Propaganda 300x600</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
