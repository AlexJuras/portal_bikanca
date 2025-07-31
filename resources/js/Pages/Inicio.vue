<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import Principal from '@/Layouts/Principal.vue'
import VideoPlayer from '@/Components/VideoPlayer.vue'

defineOptions({ layout: Principal });

// Imagem padrão para usuários
const userImage = '/images/user-placeholder.png';

const props = defineProps({
    noticiasDestaque: { type: Array, default: () => [] },
    noticiasCarrossel: { type: Array, default: () => [] },
    noticiasMaisLidas: { type: Array, default: () => [] },
    videosDestaque: { type: Array, default: () => [] },
    categorias: { type: Array, default: () => [] },
    noticiasPorCategoria: { type: Object, default: () => ({}) },
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

// Função para obter ícone da categoria
const obterIconeCategoria = (nome) => {
    const nomeNormalizado = nome.toLowerCase().trim();
    
    const icones = {
        'política': '🏛️',
        'politica': '🏛️',
        'esporte': '⚽',
        'esportes': '⚽',
        'economia': '💰',
        'tecnologia': '💻',
        'saúde': '🏥',
        'saude': '🏥',
        'educação': '📚',
        'educacao': '📚',
        'cultura': '🎭',
        'meio ambiente': '🌱',
        'internacional': '🌍',
        'nacional': '🇧🇷',
        'entretenimento': '🎬',
        'ciência': '🔬',
        'ciencia': '🔬',
        'turismo': '✈️',
        'segurança': '🛡️',
        'seguranca': '🛡️',
    };
    
    // Procura por palavras-chave no nome da categoria
    for (const [palavra, icone] of Object.entries(icones)) {
        if (nomeNormalizado.includes(palavra)) {
            return icone;
        }
    }
    
    return '📰'; // Ícone padrão
};

// Funções de compartilhamento social
const gerarUrlNoticia = (noticia) => {
    return `${window.location.origin}/noticias/${noticia.slug || noticia.id}`;
};

const compartilharFacebook = (noticia) => {
    const url = gerarUrlNoticia(noticia);
    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    window.open(facebookUrl, '_blank', 'width=600,height=400');
};

const compartilharTwitter = (noticia) => {
    const url = gerarUrlNoticia(noticia);
    const texto = `${noticia.titulo} - Portal Bikanca`;
    const twitterUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(texto)}`;
    window.open(twitterUrl, '_blank', 'width=600,height=400');
};

const compartilharWhatsApp = (noticia) => {
    const url = gerarUrlNoticia(noticia);
    const texto = `${noticia.titulo} - Portal Bikanca\n${url}`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(texto)}`;
    window.open(whatsappUrl, '_blank');
};

const copiarLink = async (noticia) => {
    const url = gerarUrlNoticia(noticia);
    try {
        await navigator.clipboard.writeText(url);
        alert('Link copiado para a área de transferência!');
    } catch (err) {
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = url;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Link copiado para a área de transferência!');
    }
};

// Função para extrair ID do YouTube da URL
const getYoutubeId = (url) => {
    if (!url) return null
    
    const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/
    const match = url.match(regex)
    return match ? match[1] : null
}

// Controle do dropdown
const dropdownAberto = ref({});

const toggleDropdown = (noticiaId) => {
    dropdownAberto.value = {
        ...dropdownAberto.value,
        [noticiaId]: !dropdownAberto.value[noticiaId]
    };
};

const fecharTodosDropdowns = () => {
    dropdownAberto.value = {};
};

onMounted(() => {
    if (props.noticiasCarrossel.length > 1) {
        startAutoSlide();
    }
    
    // Fechar dropdowns quando clicar fora
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown-compartilhar')) {
            fecharTodosDropdowns();
        }
    });
});

onUnmounted(() => {
    stopAutoSlide();
    
    // Remover listener
    document.removeEventListener('click', fecharTodosDropdowns);
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
                            <!-- Link para toda a área do slide -->
                            <Link 
                                :href="`/noticias/${noticia.slug || noticia.id}`" 
                                class="absolute inset-0 z-10 cursor-pointer"
                                :title="`Ler: ${noticia.titulo}`"
                            ></Link>
                            
                            <!-- Imagem de fundo -->
                            <div 
                                class="absolute inset-0 bg-cover bg-center"
                                :style="{ backgroundImage: `url(${noticia.imagem_capa?.caminho || '/logo.png'})` }"
                            ></div>
                            
                            <!-- Overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            
                            <!-- Conteúdo -->
                            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white z-20 pointer-events-none">
                                <div class="max-w-4xl">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <span class="bg-azul-celeste px-3 py-1 rounded-full text-sm font-medium">
                                            {{ noticia.categoria?.nome }}
                                        </span>
                                        <span class="text-sm opacity-90">
                                            {{ formatarData(noticia.publicada_em) }}
                                        </span>
                                    </div>
                                    
                                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 leading-tight hover:text-celeste transition-colors">
                                        {{ noticia.titulo }}
                                    </h2>
                                    
                                    <p class="text-lg md:text-xl text-gray-200 mb-6 line-clamp-2">
                                        {{ noticia.resumo }}
                                    </p>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <img
                                                :src="noticia.autor?.foto ? `/storage/${noticia.autor.foto}` : userImage"
                                                :alt="noticia.autor?.nome"
                                                class="w-10 h-10 rounded-full object-cover border-2 border-white/30"
                                                @error="$event.target.src = userImage"
                                            />
                                            <div class="text-sm">
                                                <p class="font-medium">{{ noticia.autor?.nome }}</p>
                                                <p class="opacity-75">Jornalista</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Controles do Carrossel -->
                    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3 z-30">
                        <button 
                            v-for="(noticia, index) in noticiasCarrossel" 
                            :key="`dot-${noticia.id}`"
                            @click="goToSlide(index)"
                            class="w-3 h-3 rounded-full transition-all"
                            :class="index === currentSlide ? 'bg-white' : 'bg-white/50'"
                        ></button>
                    </div>
                    
                    <!-- Botões Prev/Next -->
                    <button 
                        @click="prevSlide"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-3 rounded-full transition-all z-30"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <button 
                        @click="nextSlide"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-3 rounded-full transition-all z-30"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal - Layout sem Sidebar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Notícias em Destaque -->
            <section class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-azul-oxford">Notícias em Destaque</h2>
                    <Link 
                        href="/noticias"
                        class="text-azul-celeste hover:text-azul-oxford transition-colors text-sm font-medium"
                    >
                        Ver todas →
                    </Link>
                </div>
                
                <div v-if="noticiasDestaque.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <article 
                        v-for="noticia in noticiasDestaque.slice(0, 8)" 
                        :key="`destaque-${noticia.id}`"
                        class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow relative"
                    >
                        <!-- Botão de Compartilhamento -->
                        <div class="absolute top-3 right-3 z-10 dropdown-compartilhar">
                            <button 
                                @click.stop="toggleDropdown(`destaque-${noticia.id}`)"
                                class="bg-white/90 hover:bg-white p-2 rounded-full shadow-sm transition-all"
                                title="Compartilhar"
                            >
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                            </button>
                            
                            <!-- Dropdown de compartilhamento -->
                            <div 
                                v-if="dropdownAberto[`destaque-${noticia.id}`]"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-20"
                            >
                                <button 
                                    @click="compartilharFacebook(noticia)"
                                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </button>
                                <button 
                                    @click="compartilharTwitter(noticia)"
                                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Twitter
                                </button>
                                <button 
                                    @click="compartilharWhatsApp(noticia)"
                                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    WhatsApp
                                </button>
                                <hr class="my-1">
                                <button 
                                    @click="copiarLink(noticia)"
                                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Copiar Link
                                </button>
                            </div>
                        </div>

                        <Link :href="`/noticias/${noticia.slug || noticia.id}`" class="block group">
                            <!-- Imagem -->
                            <div class="relative aspect-video bg-gray-200">
                                <img 
                                    :src="noticia.imagem_capa?.caminho || '/logo.png'"
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
            <section class="mb-12">
                <div class="bg-gray-300 h-32 rounded-lg flex items-center justify-center text-gray-600">
                    <div class="text-center">
                        <p class="text-sm font-medium">ESPAÇO PUBLICITÁRIO</p>
                        <p class="text-xs">Banner 728x120</p>
                    </div>
                </div>
            </section>

            <!-- Seção de Vídeos -->
            <section class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-azul-oxford flex items-center gap-2">
                        <span>Vídeos em Destaque</span>
                    </h2>
                    <Link 
                        href="/videos"
                        class="text-azul-celeste hover:text-azul-oxford transition-colors text-sm font-medium"
                    >
                        Ver todos →
                    </Link>
                </div>
                
                <div v-if="videosDestaque.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <article 
                        v-for="video in videosDestaque.slice(0, 6)" 
                        :key="`video-${video.id}`"
                        class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow group"
                    >
                        <!-- Player de vídeo -->
                        <VideoPlayer :video="video">
                            <div class="relative aspect-video bg-gray-200">
                                <img 
                                    :src="video.thumbnail || `https://img.youtube.com/vi/${getYoutubeId(video.url_externa)}/hqdefault.jpg`"
                                    :alt="video.titulo"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                    @error="$event.target.src = '/logo.png'"
                                />
                                <!-- Play Button -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-black/70 rounded-full p-4 group-hover:bg-azul-oxford/90 transition-colors">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="absolute top-3 left-3">
                                    <span class="bg-red-600 text-white px-2 py-1 rounded text-sm font-medium">
                                        {{ video.categoria?.nome || 'Vídeo' }}
                                    </span>
                                </div>
                                <!-- Duração do vídeo -->
                                <div v-if="video.duracao" class="absolute bottom-3 right-3">
                                    <span class="bg-black/80 text-white px-2 py-1 rounded text-xs">
                                        {{ video.duracao }}
                                    </span>
                                </div>
                            </div>
                        </VideoPlayer>
                        
                        <!-- Conteúdo -->
                        <div class="p-4">
                            <h3 class="font-bold text-azul-oxford line-clamp-2 mb-2">
                                {{ video.titulo }}
                            </h3>
                            
                            <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                {{ video.descricao }}
                            </p>
                            
                            <!-- Meta -->
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ video.autor?.nome }}</span>
                                <span>{{ formatarData(video.publicada_em) }}</span>
                            </div>
                        </div>
                    </article>
                </div>
                
                <div v-else class="text-center py-12">
                    <div class="text-gray-400 text-4xl mb-4">🎥</div>
                    <p class="text-gray-600">Nenhum vídeo em destaque ainda.</p>
                </div>
            </section>

            <!-- Seções Dinâmicas por Categoria -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <section 
                    v-for="categoria in categorias.slice(0, 6)" 
                    :key="`categoria-${categoria.id}`"
                    v-show="categoria.noticias && categoria.noticias.length > 0"
                    class="bg-white rounded-lg shadow-sm p-6"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-azul-oxford flex items-center gap-2">
                            <span>{{ obterIconeCategoria(categoria.nome) }}</span>
                            <span>{{ categoria.nome }}</span>
                        </h3>
                        <Link 
                            :href="`/noticias/categoria/${categoria.slug || categoria.id}`"
                            class="text-azul-celeste hover:text-azul-oxford transition-colors text-sm"
                        >
                            Ver mais →
                        </Link>
                    </div>
                    
                    <!-- Primeira notícia em destaque -->
                    <article 
                        v-if="categoria.noticias[0]"
                        class="mb-6 group relative"
                    >
                        <!-- Botão de Compartilhamento -->
                        <div class="absolute top-3 right-3 z-10 dropdown-compartilhar">
                            <button 
                                @click.stop="toggleDropdown(`categoria-${categoria.id}-principal`)"
                                class="bg-white/90 hover:bg-white p-2 rounded-full shadow-sm transition-all"
                                title="Compartilhar"
                            >
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                            </button>
                            
                            <!-- Dropdown de compartilhamento -->
                            <div 
                                v-if="dropdownAberto[`categoria-${categoria.id}-principal`]"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-20"
                            >
                                <button 
                                    @click="compartilharFacebook(categoria.noticias[0])"
                                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </button>
                                <button 
                                    @click="compartilharTwitter(categoria.noticias[0])"
                                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Twitter
                                </button>
                                <button 
                                    @click="compartilharWhatsApp(categoria.noticias[0])"
                                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    WhatsApp
                                </button>
                                <hr class="my-1">
                                <button 
                                    @click="copiarLink(categoria.noticias[0])"
                                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Copiar Link
                                </button>
                            </div>
                        </div>

                        <Link :href="`/noticias/${categoria.noticias[0].slug || categoria.noticias[0].id}`">
                            <div class="relative aspect-video bg-gray-200 rounded-lg overflow-hidden mb-3">
                                <img 
                                    :src="categoria.noticias[0].imagem_capa?.caminho || '/logo.png'"
                                    :alt="categoria.noticias[0].titulo"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                <div class="absolute bottom-3 left-3 right-3">
                                    <h4 class="text-white font-bold text-lg line-clamp-2 group-hover:text-celeste transition-colors">
                                        {{ categoria.noticias[0].titulo }}
                                    </h4>
                                    <div class="text-white/80 text-sm mt-1">
                                        {{ formatarData(categoria.noticias[0].publicada_em) }}
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </article>
                    
                    <!-- Lista das próximas notícias -->
                    <div class="space-y-4">
                        <article 
                            v-for="noticia in categoria.noticias.slice(1, 4)" 
                            :key="`categoria-${categoria.id}-noticia-${noticia.id}`"
                            class="flex gap-3 hover:bg-gray-50 p-2 rounded transition-colors group relative"
                        >
                            <!-- Botão de compartilhamento pequeno -->
                            <div class="absolute top-2 right-2 dropdown-compartilhar">
                                <button 
                                    @click.stop="toggleDropdown(`categoria-${categoria.id}-noticia-${noticia.id}`)"
                                    class="bg-white hover:bg-gray-50 p-1 rounded-full shadow-sm transition-all opacity-0 group-hover:opacity-100"
                                    title="Compartilhar"
                                >
                                    <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown de compartilhamento -->
                                <div 
                                    v-if="dropdownAberto[`categoria-${categoria.id}-noticia-${noticia.id}`]"
                                    class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-20"
                                >
                                    <button 
                                        @click="compartilharFacebook(noticia)"
                                        class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                    >
                                        <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        Facebook
                                    </button>
                                    <button 
                                        @click="compartilharTwitter(noticia)"
                                        class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                    >
                                        <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                        Twitter
                                    </button>
                                    <button 
                                        @click="compartilharWhatsApp(noticia)"
                                        class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                    >
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                        </svg>
                                        WhatsApp
                                    </button>
                                    <hr class="my-1">
                                    <button 
                                        @click="copiarLink(noticia)"
                                        class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                    >
                                        <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        Copiar Link
                                    </button>
                                </div>
                            </div>

                            <Link :href="`/noticias/${noticia.slug || noticia.id}`" class="flex-1">
                                <h5 class="font-semibold text-sm text-azul-oxford line-clamp-2 group-hover:text-azul-noite transition-colors mb-1">
                                    {{ noticia.titulo }}
                                </h5>
                                <div class="text-xs text-gray-500">
                                    {{ formatarData(noticia.publicada_em) }}
                                </div>
                            </Link>
                            <div class="flex-shrink-0">
                                <img 
                                    v-if="noticia.imagem_capa?.caminho"
                                    :src="noticia.imagem_capa.caminho"
                                    :alt="noticia.titulo"
                                    class="w-16 h-12 object-cover rounded group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                />
                                <div 
                                    v-else
                                    class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center"
                                >
                                    <span class="text-gray-400 text-xs">{{ obterIconeCategoria(categoria.nome) }}</span>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </div>

            <!-- Seção Mais Lidas (integrada no conteúdo principal) -->
            <section v-if="noticiasMaisLidas.length > 0" class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-azul-oxford flex items-center gap-2">
                        <span>🔥</span>
                        <span>Mais Lidas</span>
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <article 
                        v-for="(noticia, index) in noticiasMaisLidas.slice(0, 8)" 
                        :key="`mais-lida-${noticia.id}`"
                        class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow group relative"
                    >
                        <!-- Botão de Compartilhamento -->
                        <div class="absolute top-3 right-3 z-10 dropdown-compartilhar">
                            <button 
                                @click.stop="toggleDropdown(`mais-lida-${noticia.id}`)"
                                class="bg-white hover:bg-gray-50 p-1.5 rounded-full shadow-sm transition-all opacity-0 group-hover:opacity-100"
                                title="Compartilhar"
                            >
                                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                            </button>
                            
                            <!-- Dropdown de compartilhamento -->
                            <div 
                                v-if="dropdownAberto[`mais-lida-${noticia.id}`]"
                                class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-20"
                            >
                                <button 
                                    @click="compartilharFacebook(noticia)"
                                    class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </button>
                                <button 
                                    @click="compartilharTwitter(noticia)"
                                    class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Twitter
                                </button>
                                <button 
                                    @click="compartilharWhatsApp(noticia)"
                                    class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    WhatsApp
                                </button>
                                <hr class="my-1">
                                <button 
                                    @click="copiarLink(noticia)"
                                    class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Copiar Link
                                </button>
                            </div>
                        </div>

                        <Link :href="`/noticias/${noticia.slug || noticia.id}`" class="block">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-azul-celeste text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm text-azul-oxford line-clamp-2 group-hover:text-azul-noite transition-colors mb-1">
                                        {{ noticia.titulo }}
                                    </h4>
                                    <div class="text-xs text-gray-500">
                                        {{ formatarData(noticia.publicada_em) }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Imagem pequena -->
                            <div v-if="noticia.imagem_capa?.caminho" class="aspect-video bg-gray-200 rounded overflow-hidden">
                                <img 
                                    :src="noticia.imagem_capa.caminho"
                                    :alt="noticia.titulo"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                />
                            </div>
                        </Link>
                    </article>
                </div>
            </section>
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
