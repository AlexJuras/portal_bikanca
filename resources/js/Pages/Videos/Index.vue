<script setup>
import { ref, computed } from "vue";
import Principal from "@/Layouts/Principal.vue";
import { Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import VideoPlayer from "@/Components/VideoPlayer.vue";
import AnuncioRenderer from "@/Components/AnuncioRenderer.vue";

defineOptions({ layout: Principal });

const props = defineProps({
    videos: {
        type: Object,
        required: true,
    },
    maisAssistidos: {
        type: Array,
        default: () => [],
    },
    categoria: {
        type: Object,
        default: null,
    },
    anuncios: {
        type: Array,
        default: () => [],
    },
});

// Função para lidar com erros de thumbnail
const handleThumbnailError = (event) => {
    console.error('Erro ao carregar thumbnail na listagem:', event);
    event.target.src = '/images/video-placeholder.svg';
};

// Função para formatar duração
const formatarDuracao = (duracao) => {
    if (!duracao) return "N/A";
    return duracao;
};

// Função para formatar visualizações
const formatarVisualizacoes = (views) => {
    if (views >= 1000000) return `${(views / 1000000).toFixed(1)}M`;
    if (views >= 1000) return `${(views / 1000).toFixed(1)}k`;
    return views?.toString() || '0';
};

// Extrair ID do YouTube da URL
const getYoutubeId = (url) => {
    if (!url) return null
    
    const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/
    const match = url.match(regex)
    return match ? match[1] : null
}
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumb -->
                <nav class="text-sm text-gray-600 mb-4">
                    <Link href="/" class="hover:text-azul-oxford transition-colors">Home</Link>
                    <span class="mx-2">></span>
                    <span v-if="categoria" class="text-azul-oxford">{{ categoria.nome }}</span>
                    <span v-else class="text-azul-oxford">Vídeos</span>
                </nav>

                <!-- Título -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-azul-oxford flex items-center gap-3">
                            <span v-if="categoria">{{ categoria.nome }}</span>
                            <span v-else>Vídeos</span>
                        </h1>
                        <p class="text-gray-600 mt-2">
                            <span v-if="categoria">
                                Vídeos da categoria {{ categoria.nome }}
                            </span>
                            <span v-else>
                                Assista aos melhores vídeos do Portal Bikanca
                            </span>
                        </p>
                    </div>
                    
                    <!-- Botão Admin (removido da área pública) -->
                    <!-- O botão de criar vídeos deve estar apenas na área admin -->
                </div>
            </div>
        </section>

        <!-- Espaço Publicitário - Banner Topo -->
        <section v-if="anuncios.length > 0 && anuncios[0]" class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <AnuncioRenderer 
                    :anuncio="anuncios[0]" 
                    layout="banner-topo"
                    wrapper-class="flex justify-center"
                />
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Lista de Vídeos -->
                <div class="lg:col-span-8">
                    <div v-if="videos.data && videos.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <article 
                            v-for="video in videos.data" 
                            :key="video.id"
                            class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow"
                        >
                            <!-- Player de vídeo -->
                            <VideoPlayer :video="video">
                                <div class="relative aspect-video bg-gray-100">
                                    <img 
                                        :src="video.thumbnail || `https://img.youtube.com/vi/${getYoutubeId(video.url_externa)}/hqdefault.jpg`" 
                                        :alt="video.titulo"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                        @error="handleThumbnailError"
                                        @load="console.log('✅ Thumbnail Index carregou:', $event.target.src)"
                                    />
                                    
                                    <!-- Play Button -->
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/30 transition-colors">
                                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Duração -->
                                    <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                        {{ formatarDuracao(video.duracao) }}
                                    </div>
                                </div>
                            </VideoPlayer>

                            <!-- Conteúdo -->
                            <div class="p-4">
                                <h3 class="font-semibold text-azul-oxford line-clamp-2 transition-colors">
                                    {{ video.titulo }}
                                </h3>
                                
                                <p class="text-gray-600 text-sm mt-2 line-clamp-2">
                                    {{ video.descricao }}
                                </p>
                                
                                <!-- Meta informações -->
                                <div class="flex items-center justify-between mt-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        👁️ {{ formatarVisualizacoes(video.visualizacoes) }}
                                    </span>
                                    <span>{{ video.categoria?.nome }}</span>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Mensagem se não há vídeos -->
                    <div v-else class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">🎥</div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhum vídeo encontrado</h3>
                        <p class="text-gray-500">
                            <span v-if="categoria">
                                Não há vídeos nesta categoria ainda.
                            </span>
                            <span v-else>
                                Ainda não há vídeos publicados.
                            </span>
                        </p>
                    </div>

                    <!-- Espaço Publicitário - Banner Meio -->
                    <div v-if="anuncios.length > 1 && anuncios[1]" class="my-8">
                        <AnuncioRenderer 
                            :anuncio="anuncios[1]" 
                            layout="banner-meio"
                            wrapper-class="flex justify-center"
                        />
                    </div>

                    <!-- Paginação -->
                    <div v-if="videos.links" class="mt-8">
                        <Pagination :links="videos.links" />
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Mais Assistidos -->
                    <div v-if="maisAssistidos && maisAssistidos.length > 0" class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-bold text-azul-oxford mb-4 flex items-center gap-2">
                            🔥 Mais Assistidos
                        </h3>
                        
                        <div class="space-y-4">
                            <article 
                                v-for="video in maisAssistidos" 
                                :key="video.id"
                                class="group"
                            >
                                <VideoPlayer :video="video">
                                    <div class="flex gap-3 cursor-pointer">
                                        <div class="relative w-20 h-12 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                                            <img 
                                                :src="video.thumbnail || getYoutubeId(video.url_externa) ? `https://img.youtube.com/vi/${getYoutubeId(video.url_externa)}/hqdefault.jpg` : '/images/video-placeholder.svg'" 
                                                :alt="video.titulo"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                                loading="lazy"
                                                @error="$event.target.src = '/images/video-placeholder.svg'"
                                            />
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-4 h-4 bg-red-600 rounded-full flex items-center justify-center">
                                                    <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-azul-oxford group-hover:text-azul-noite transition-colors line-clamp-2 text-sm">
                                                {{ video.titulo }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ formatarVisualizacoes(video.visualizacoes) }} visualizações
                                            </p>
                                        </div>
                                    </div>
                                </VideoPlayer>
                            </article>
                        </div>
                    </div>

                    <!-- Espaço Publicitário - Banner Lateral -->
                    <div v-if="anuncios.length > 2 && anuncios[2]" class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <AnuncioRenderer 
                            :anuncio="anuncios[2]" 
                            layout="banner-lateral"
                            wrapper-class="flex justify-center"
                        />
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
    line-clamp: 2;
}
</style>
