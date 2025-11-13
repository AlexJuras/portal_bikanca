<template>
    <div v-if="anuncio" class="anuncio-display" :class="containerClass">
        <!-- Anúncio tipo imagem -->
        <a 
            v-if="anuncio.tipo === 'imagem'" 
            :href="anuncio.link" 
            :target="anuncio.nova_aba ? '_blank' : '_self'" 
            rel="noopener noreferrer" 
            class="block overflow-hidden"
            :class="wrapperClass"
            @click="registrarClique"
        >
            <img 
                :src="getImagePath(anuncio.imagem)" 
                :alt="anuncio.nome" 
                :class="computedImageClass"
                :style="imageStyle"
                @load="registrarImpressao"
            />
        </a>
        
        <!-- Anúncio tipo script/AdSense -->
        <div 
            v-else-if="anuncio.tipo === 'script'" 
            v-html="anuncio.script_content"
            class="adsense-container"
            @vue:mounted="registrarImpressao"
        ></div>
        
        <!-- Anúncio tipo HTML -->
        <div 
            v-else-if="anuncio.tipo === 'html'" 
            v-html="anuncio.html_content"
            class="html-ad-container"
            @vue:mounted="registrarImpressao"
        ></div>
    </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'

const props = defineProps({
    anuncio: {
        type: Object,
        required: true
    },
    // Classe customizada para a imagem (sobrescreve o padrão)
    imageClass: {
        type: String,
        default: null
    },
    // Tipo de layout: 'banner-topo', 'banner-lateral', 'banner-meio', 'quadrado'
    layout: {
        type: String,
        default: 'banner-topo'
    },
    // Container class para o wrapper externo
    containerClass: {
        type: String,
        default: ''
    },
    // Wrapper class para o link
    wrapperClass: {
        type: String,
        default: ''
    }
})

// Classes responsivas baseadas no layout
const computedImageClass = computed(() => {
    if (props.imageClass) {
        return props.imageClass
    }
    
    const layouts = {
        'banner-topo': 'w-full h-auto object-contain max-h-24 md:max-h-32 rounded-lg hover:opacity-95 transition-opacity',
        'banner-meio': 'w-full h-auto object-contain max-h-32 md:max-h-48 rounded-lg hover:opacity-95 transition-opacity',
        'banner-lateral': 'w-full h-auto object-contain max-h-60 rounded-lg hover:opacity-95 transition-opacity',
        'quadrado': 'w-full h-auto object-cover aspect-square rounded-lg hover:opacity-95 transition-opacity',
        'retangular': 'w-full h-auto object-cover aspect-video rounded-lg hover:opacity-95 transition-opacity'
    }
    
    return layouts[props.layout] || layouts['banner-topo']
})

// Estilo inline para dimensões específicas do anúncio
const imageStyle = computed(() => {
    if (props.anuncio.largura && props.anuncio.altura) {
        return {
            maxWidth: `${props.anuncio.largura}px`,
            maxHeight: `${props.anuncio.altura}px`
        }
    }
    return {}
})

const getImagePath = (imagePath) => {
    // Se for uma URL completa, retorna como está
    if (imagePath && imagePath.startsWith('http')) {
        return imagePath
    }
    
    // Se for um caminho local (upload), adiciona o prefixo do storage
    if (imagePath && !imagePath.startsWith('/')) {
        return `/storage/${imagePath}`
    }
    
    return imagePath
}

const registrarImpressao = () => {
    // Registrar impressão do anúncio
    if (props.anuncio && props.anuncio.id) {
        fetch(`/api/anuncios/${props.anuncio.id}/impressao`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        }).catch(error => {
            console.error('Erro ao registrar impressão:', error)
        })
    }
}

const registrarClique = () => {
    // Registrar clique do anúncio
    if (props.anuncio && props.anuncio.id) {
        fetch(`/api/anuncios/${props.anuncio.id}/clique`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        }).catch(error => {
            console.error('Erro ao registrar clique:', error)
        })
    }
}

onMounted(() => {
    // Para anúncios que não são imagem, registrar impressão automaticamente
    if (props.anuncio && props.anuncio.tipo !== 'imagem') {
        registrarImpressao()
    }
})
</script>

<style scoped>
.adsense-container {
    text-align: center;
}

.html-ad-container {
    width: 100%;
}

/* Garantir que scripts AdSense sejam executados */
.adsense-container :deep(script) {
    display: block !important;
}
</style>
