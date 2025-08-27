<template>
    <div v-if="anuncio" class="anuncio-display">
        <!-- Anúncio tipo imagem -->
        <a 
            v-if="anuncio.tipo === 'imagem'" 
            :href="anuncio.link" 
            :target="anuncio.nova_aba ? '_blank' : '_self'" 
            rel="noopener noreferrer" 
            class="block"
            @click="registrarClique"
        >
            <img 
                :src="getImagePath(anuncio.imagem)" 
                :alt="anuncio.nome" 
                :class="imageClass"
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
import { onMounted } from 'vue'

const props = defineProps({
    anuncio: {
        type: Object,
        required: true
    },
    imageClass: {
        type: String,
        default: 'w-full h-auto object-cover rounded-lg hover:opacity-95 transition-opacity'
    }
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
