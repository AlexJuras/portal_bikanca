<template>
    <div 
        v-if="banner" 
        class="ad-banner flex items-center justify-center"
        :style="{ 
            width: `${banner.largura}px`, 
            height: `${banner.altura}px`,
            maxWidth: '100%'
        }"
    >
        <a 
            v-if="banner.link" 
            :href="banner.link"
            target="_blank"
            rel="noopener noreferrer"
            class="block w-full h-full"
        >
            <img 
                v-if="banner.imagem"
                :src="banner.imagem_url"
                :alt="banner.nome"
                class="w-full h-full object-cover rounded-lg shadow-sm"
            />
            <div 
                v-else
                class="w-full h-full bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors"
            >
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm font-medium">{{ banner.nome }}</p>
                <p class="text-xs">{{ banner.largura }}x{{ banner.altura }}</p>
            </div>
        </a>
        
        <div 
            v-else
            class="w-full h-full"
        >
            <img 
                v-if="banner.imagem"
                :src="banner.imagem_url"
                :alt="banner.nome"
                class="w-full h-full object-cover rounded-lg shadow-sm"
            />
            <div 
                v-else
                class="w-full h-full bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-gray-500"
            >
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm font-medium">{{ banner.nome }}</p>
                <p class="text-xs">{{ banner.largura }}x{{ banner.altura }}</p>
            </div>
        </div>
    </div>
    
    <!-- Placeholder quando não há banner configurado -->
    <div 
        v-else-if="showPlaceholder"
        class="ad-banner flex items-center justify-center bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg"
        :style="{ 
            width: `${placeholderWidth}px`, 
            height: `${placeholderHeight}px`,
            maxWidth: '100%'
        }"
    >
        <div class="text-center text-gray-400">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm">Espaço Publicitário</p>
            <p class="text-xs">{{ placeholderWidth }}x{{ placeholderHeight }}</p>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    banner: {
        type: Object,
        default: null
    },
    showPlaceholder: {
        type: Boolean,
        default: true
    },
    placeholderWidth: {
        type: Number,
        default: 728
    },
    placeholderHeight: {
        type: Number,
        default: 90
    }
})
</script>

<style scoped>
.ad-banner {
    margin: 0 auto;
}

.ad-banner img {
    transition: transform 0.3s ease;
}

.ad-banner a:hover img {
    transform: scale(1.02);
}
</style>
