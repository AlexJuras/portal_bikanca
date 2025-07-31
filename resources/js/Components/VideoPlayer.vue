<template>
    <div class="video-player-container">
        <!-- Modal para reprodução de vídeo -->
        <div 
            v-if="isPlaying" 
            class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
            @click="closePlayer"
        >
            <div 
                class="relative bg-black rounded-lg overflow-hidden max-w-4xl w-full"
                @click.stop
            >
                <!-- Botão de fechar -->
                <button 
                    @click="closePlayer"
                    class="absolute top-4 right-4 z-10 text-white hover:text-gray-300 transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                
                <!-- Player do YouTube -->
                <div class="aspect-video">
                    <iframe
                        :src="youtubeEmbedUrl"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="w-full h-full"
                    ></iframe>
                </div>
                
                <!-- Informações do vídeo -->
                <div class="p-4 text-white">
                    <h3 class="text-xl font-bold mb-2">{{ video.titulo }}</h3>
                    <p class="text-gray-300 text-sm">{{ video.descricao }}</p>
                    
                    <div class="flex items-center justify-between mt-4 text-sm text-gray-400">
                        <span>{{ video.autor?.nome }}</span>
                        <span>{{ video.duracao }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thumbnail clicável -->
        <div 
            class="relative cursor-pointer group"
            @click="playVideo"
        >
            <slot>
                <!-- Thumbnail padrão se não houver slot customizado -->
                <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                    <img 
                        :src="video.thumbnail || getYoutubeThumbnail(video.url_externa)"
                        :alt="video.titulo"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        loading="lazy"
                    />
                    
                    <!-- Play Button -->
                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/30 transition-colors">
                        <div v-if="!isLoading" class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                            <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                        </div>
                        <!-- Loading spinner -->
                        <div v-else class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="animate-spin w-6 h-6 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Duração -->
                    <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                        {{ video.duracao || 'N/A' }}
                    </div>
                </div>
            </slot>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    video: {
        type: Object,
        required: true
    }
})

const isPlaying = ref(false)
const isLoading = ref(false)

// Extrair ID do YouTube da URL
const getYoutubeId = (url) => {
    if (!url) return null
    
    const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/
    const match = url.match(regex)
    return match ? match[1] : null
}

// Gerar thumbnail do YouTube
const getYoutubeThumbnail = (url) => {
    const youtubeId = getYoutubeId(url)
    return youtubeId ? `https://img.youtube.com/vi/${youtubeId}/hqdefault.jpg` : '/images/video-placeholder.svg'
}

// URL para embed do YouTube
const youtubeEmbedUrl = computed(() => {
    const youtubeId = getYoutubeId(props.video.url_externa)
    return youtubeId ? `https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0` : null
})

// Iniciar reprodução
const playVideo = async () => {
    if (youtubeEmbedUrl.value) {
        isLoading.value = true
        isPlaying.value = true
        // Prevenir scroll da página quando o modal estiver aberto
        document.body.style.overflow = 'hidden'
        
        // Incrementar visualizações
        await incrementViews()
        isLoading.value = false
    }
}

// Incrementar visualizações do vídeo
const incrementViews = async () => {
    try {
        await fetch(`/api/videos/${props.video.id}/view`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
    } catch (error) {
        console.log('Erro ao incrementar visualizações:', error)
    }
}

// Fechar player
const closePlayer = () => {
    isPlaying.value = false
    // Restaurar scroll da página
    document.body.style.overflow = 'auto'
}

// Fechar com ESC
const handleKeydown = (event) => {
    if (event.key === 'Escape' && isPlaying.value) {
        closePlayer()
    }
}

// Adicionar/remover listener do teclado
import { onMounted, onUnmounted } from 'vue'

onMounted(() => {
    document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown)
    // Garantir que o scroll seja restaurado se o componente for desmontado
    document.body.style.overflow = 'auto'
})
</script>

<style scoped>
/* Estilos adicionais se necessário */
</style>
