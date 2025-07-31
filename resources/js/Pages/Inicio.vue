<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import Principal from '@/Layouts/Principal.vue'

defineOptions({ layout: Principal });

const props = defineProps({
    noticiasDestaque: { type: Array, default: () => [] },
    noticiasCarrossel: { type: Array, default: () => [] },
    noticiasPolitica: { type: Array, default: () => [] },
    noticiasEconomia: { type: Array, default: () => [] },
    noticiasEsporte: { type: Array, default: () => [] },
    noticiasMaisLidas: { type: Array, default: () => [] },
    categorias: { type: Array, default: () => [] },
});

// Carrossel
const currentSlide = ref(0);
let slideInterval = null;

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % props.noticiasCarrossel.length;
};

const prevSlide = () => {
    currentSlide.value = currentSlide.value === 0 ? props.noticiasCarrossel.length - 1 : currentSlide.value - 1;
};

const goToSlide = (index) => {
    currentSlide.value = index;
};

const startAutoSlide = () => {
    slideInterval = setInterval(nextSlide, 5000);
};

const stopAutoSlide = () => {
    if (slideInterval) {
        clearInterval(slideInterval);
        slideInterval = null;
    }
};

// Formatadores
const formatarData = (data) => {
    if (!data) return '';
    return new Date(data).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const formatarVisualizacoes = (views) => {
    if (views >= 1000000) return `${(views / 1000000).toFixed(1)}M`;
    if (views >= 1000) return `${(views / 1000).toFixed(1)}k`;
    return views?.toString() || '0';
};

onMounted(() => {
    if (props.noticiasCarrossel.length > 1) {
        startAutoSlide();
    }
});

onUnmounted(() => {
    stopAutoSlide();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section com Carrossel -->
        <section class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div v-if="noticiasCarrossel.length > 0" class="relative">
                    <!-- Carrossel Principal -->
                    <div class="relative h-96 md:h-[500px] overflow-hidden rounded-lg shadow-lg">
                        <div 
                            v-for="(noticia, index) in noticiasCarrossel" 
                            :key="`slide-${noticia.id}`"
                            class="absolute inset-0 transition-opacity duration-500"
                            :class="{ 'opacity-100': index === currentSlide, 'opacity-0': index !== currentSlide }"
                        >
                            <!-- Imagem de fundo -->
                            <div 
                                class="absolute inset-0 bg-cover bg-center"
                                :style="{ backgroundImage: `url(${noticia.imagem_capa || '/images/news-placeholder.jpg'})` }"
                            ></div>
                            
                            <!-- Overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            
                            <!-- Conteúdo -->
                            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white">
                                <div class="max-w-4xl">
                                    <!-- Categoria -->
                                    <span class="inline-block bg-azul-celeste text-white px-3 py-1 rounded-full text-sm font-medium mb-3">
                                        {{ noticia.categoria?.nome }}
                                    </span>
                                    
                                    <!-- Título -->
                                    <h1 class="text-2xl md:text-4xl font-bold mb-3 line-clamp-2">
                                        <Link 
                                            :href="`/noticias/${noticia.slug || noticia.id}`"
                                            class="hover:text-celeste transition-colors"
                                        >
                                            {{ noticia.titulo }}
                                        </Link>
                                    </h1>
                                    
                                    <!-- Resumo -->
                                    <p class="text-lg text-gray-200 mb-4 line-clamp-2">
                                        {{ noticia.resumo }}
                                    </p>
                                    
                                    <!-- Meta informações -->
                                    <div class="flex items-center space-x-4 text-sm text-gray-300">
                                        <span>{{ noticia.autor?.nome }}</span>
                                        <span>•</span>
                                        <span>{{ formatarData(noticia.publicada_em) }}</span>
                                        <span>•</span>
                                        <span>{{ formatarVisualizacoes(noticia.visualizacoes) }} visualizações</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Controles do Carrossel -->
                    <div v-if="noticiasCarrossel.length > 1" class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
                        <div class="flex space-x-2">
                            <button
                                v-for="(noticia, index) in noticiasCarrossel"
                                :key="`indicator-${noticia.id}`"
                                @click="goToSlide(index)"
                                class="w-3 h-3 rounded-full transition-colors"
                                :class="index === currentSlide ? 'bg-white' : 'bg-white/50'"
                            ></button>
                        </div>
                    </div>
                    
                    <!-- Setas de navegação -->
                    <div v-if="noticiasCarrossel.length > 1" class="absolute inset-y-0 left-0 flex items-center">
                        <button
                            @click="prevSlide"
                            class="ml-4 p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div v-if="noticiasCarrossel.length > 1" class="absolute inset-y-0 right-0 flex items-center">
                        <button
                            @click="nextSlide"
                            class="mr-4 p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Fallback se não houver notícias no carrossel -->
                <div v-else class="text-center py-20">
                    <div class="text-gray-400 text-6xl mb-4">📰</div>
                    <h2 class="text-2xl font-bold text-gray-600 mb-2">Portal Bikanca</h2>
                    <p class="text-gray-500">As principais notícias do Brasil e do mundo</p>
                </div>
            </div>
        </section>

        <!-- Espaço Publicitário - Banner Principal -->
        <section class="bg-gray-100 border-y">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="bg-gray-300 h-24 rounded-lg flex items-center justify-center text-gray-600">
                    <div class="text-center">
                        <p class="text-sm font-medium">ESPAÇO PUBLICITÁRIO</p>
                        <p class="text-xs">Banner 728x90</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Conteúdo Principal -->
                <div class="lg:col-span-8">
                    
                    <!-- Notícias em Destaque -->
                    <section class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-azul-oxford">Notícias em Destaque</h2>
                            <Link 
                                href="/noticias"
                                class="text-azul-celeste hover:text-azul-oxford transition-colors text-sm font-medium"
                            >
                                Ver todas →
                            </Link>
                        </div>
                        
                        <div v-if="noticiasDestaque.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <article 
                                v-for="noticia in noticiasDestaque.slice(0, 4)" 
                                :key="`destaque-${noticia.id}`"
                                class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow"
                            >
                                <Link :href="`/noticias/${noticia.slug || noticia.id}`" class="block group">
                                    <!-- Imagem -->
                                    <div class="relative aspect-video bg-gray-200">
                                        <img 
                                            :src="noticia.imagem_capa || '/images/news-placeholder.jpg'"
                                            :alt="noticia.titulo"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            loading="lazy"
                                        />
                                        <div class="absolute top-3 left-3">
                                            <span class="bg-azul-celeste text-white px-2 py-1 rounded text-sm font-medium">
                                                {{ noticia.categoria?.nome }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Conteúdo -->
                                    <div class="p-4">
                                        <h3 class="font-bold text-azul-oxford line-clamp-2 group-hover:text-azul-noite transition-colors mb-2">
                                            {{ noticia.titulo }}
                                        </h3>
                                        
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                            {{ noticia.resumo }}
                                        </p>
                                        
                                        <!-- Meta -->
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ noticia.autor?.nome }}</span>
                                            <span>{{ formatarData(noticia.publicada_em) }}</span>
                                        </div>
                                    </div>
                                </Link>
                            </article>
                        </div>
                        
                        <div v-else class="text-center py-12">
                            <div class="text-gray-400 text-4xl mb-4">📰</div>
                            <p class="text-gray-600">Nenhuma notícia em destaque ainda.</p>
                        </div>
                    </section>

                    <!-- Espaço Publicitário - Banner Meio -->
                    <section class="mb-8">
                        <div class="bg-gray-300 h-32 rounded-lg flex items-center justify-center text-gray-600">
                            <div class="text-center">
                                <p class="text-sm font-medium">ESPAÇO PUBLICITÁRIO</p>
                                <p class="text-xs">Banner 728x120</p>
                            </div>
                        </div>
                    </section>

                    <!-- Seções por Categoria -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Política -->
                        <section v-if="noticiasPolitica.length > 0">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-azul-oxford flex items-center">
                                    🏛️ Política
                                </h3>
                                <Link 
                                    href="/noticias/categoria/politica"
                                    class="text-azul-celeste hover:text-azul-oxford transition-colors text-sm"
                                >
                                    Ver mais →
                                </Link>
                            </div>
                            
                            <div class="space-y-4">
                                <article 
                                    v-for="noticia in noticiasPolitica.slice(0, 3)" 
                                    :key="`politica-${noticia.id}`"
                                    class="flex gap-3 hover:bg-gray-50 p-2 rounded transition-colors"
                                >
                                    <Link :href="`/noticias/${noticia.slug || noticia.id}`" class="flex-1">
                                        <h4 class="font-semibold text-sm text-azul-oxford line-clamp-2 hover:text-azul-noite transition-colors">
                                            {{ noticia.titulo }}
                                        </h4>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ formatarData(noticia.publicada_em) }}
                                        </div>
                                    </Link>
                                    <img 
                                        v-if="noticia.imagem_capa"
                                        :src="noticia.imagem_capa"
                                        :alt="noticia.titulo"
                                        class="w-16 h-12 object-cover rounded"
                                        loading="lazy"
                                    />
                                </article>
                            </div>
                        </section>

                        <!-- Economia -->
                        <section v-if="noticiasEconomia.length > 0">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-azul-oxford flex items-center">
                                    💰 Economia
                                </h3>
                                <Link 
                                    href="/noticias/categoria/economia"
                                    class="text-azul-celeste hover:text-azul-oxford transition-colors text-sm"
                                >
                                    Ver mais →
                                </Link>
                            </div>
                            
                            <div class="space-y-4">
                                <article 
                                    v-for="noticia in noticiasEconomia.slice(0, 3)" 
                                    :key="`economia-${noticia.id}`"
                                    class="flex gap-3 hover:bg-gray-50 p-2 rounded transition-colors"
                                >
                                    <Link :href="`/noticias/${noticia.slug || noticia.id}`" class="flex-1">
                                        <h4 class="font-semibold text-sm text-azul-oxford line-clamp-2 hover:text-azul-noite transition-colors">
                                            {{ noticia.titulo }}
                                        </h4>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ formatarData(noticia.publicada_em) }}
                                        </div>
                                    </Link>
                                    <img 
                                        v-if="noticia.imagem_capa"
                                        :src="noticia.imagem_capa"
                                        :alt="noticia.titulo"
                                        class="w-16 h-12 object-cover rounded"
                                        loading="lazy"
                                    />
                                </article>
                            </div>
                        </section>

                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="lg:col-span-4">
                    
                    <!-- Espaço Publicitário - Banner Lateral -->
                    <div class="bg-gray-300 h-64 rounded-lg flex items-center justify-center text-gray-600 mb-6">
                        <div class="text-center">
                            <p class="text-sm font-medium">ESPAÇO PUBLICITÁRIO</p>
                            <p class="text-xs">Banner 300x250</p>
                        </div>
                    </div>

                    <!-- Mais Lidas -->
                    <section v-if="noticiasMaisLidas.length > 0" class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h3 class="text-xl font-bold text-azul-oxford mb-4 flex items-center">
                            🔥 Mais Lidas
                        </h3>
                        
                        <div class="space-y-4">
                            <article 
                                v-for="(noticia, index) in noticiasMaisLidas" 
                                :key="`mais-lida-${noticia.id}`"
                                class="flex gap-3 hover:bg-gray-50 p-2 rounded transition-colors"
                            >
                                <div class="flex-shrink-0 w-8 h-8 bg-azul-celeste text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <Link :href="`/noticias/${noticia.slug || noticia.id}`">
                                        <h4 class="font-semibold text-sm text-azul-oxford line-clamp-2 hover:text-azul-noite transition-colors">
                                            {{ noticia.titulo }}
                                        </h4>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                            <span>{{ formatarVisualizacoes(noticia.visualizacoes) }} views</span>
                                            <span>•</span>
                                            <span>{{ formatarData(noticia.publicada_em) }}</span>
                                        </div>
                                    </Link>
                                </div>
                            </article>
                        </div>
                    </section>

                    <!-- Esporte -->
                    <section v-if="noticiasEsporte.length > 0" class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-azul-oxford flex items-center">
                                ⚽ Esporte
                            </h3>
                            <Link 
                                href="/noticias/categoria/esporte"
                                class="text-azul-celeste hover:text-azul-oxford transition-colors text-sm"
                            >
                                Ver mais →
                            </Link>
                        </div>
                        
                        <div class="space-y-4">
                            <article 
                                v-for="noticia in noticiasEsporte.slice(0, 4)" 
                                :key="`esporte-${noticia.id}`"
                                class="hover:bg-gray-50 p-2 rounded transition-colors"
                            >
                                <Link :href="`/noticias/${noticia.slug || noticia.id}`">
                                    <h4 class="font-semibold text-sm text-azul-oxford line-clamp-2 hover:text-azul-noite transition-colors mb-1">
                                        {{ noticia.titulo }}
                                    </h4>
                                    <div class="text-xs text-gray-500">
                                        {{ formatarData(noticia.publicada_em) }}
                                    </div>
                                </Link>
                            </article>
                        </div>
                    </section>

                    <!-- Espaço Publicitário - Banner Lateral Inferior -->
                    <div class="bg-gray-300 h-32 rounded-lg flex items-center justify-center text-gray-600">
                        <div class="text-center">
                            <p class="text-sm font-medium">ESPAÇO PUBLICITÁRIO</p>
                            <p class="text-xs">Banner 300x120</p>
                        </div>
                    </div>

                </aside>
            </div>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>



