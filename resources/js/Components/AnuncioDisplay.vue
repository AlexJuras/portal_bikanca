<template>
  <div 
    v-if="anuncio" 
    :class="containerClass"
    :style="containerStyle"
    @click="handleClick"
  >
    <!-- Anúncio do tipo Imagem -->
    <div v-if="anuncio.tipo === 'imagem'" class="anuncio-imagem">
      <a 
        v-if="anuncio.link" 
        :href="anuncio.link" 
        :target="anuncio.nova_aba ? '_blank' : '_self'"
        @click="registrarClique"
        class="block"
      >
        <img 
          :src="anuncio.imagem_url" 
          :alt="anuncio.nome"
          :style="imageStyle"
          class="w-full h-auto object-cover"
        />
      </a>
      <img 
        v-else
        :src="anuncio.imagem_url" 
        :alt="anuncio.nome"
        :style="imageStyle"
        class="w-full h-auto object-cover"
      />
    </div>

    <!-- Anúncio do tipo HTML -->
    <div 
      v-else-if="anuncio.tipo === 'html'" 
      class="anuncio-html"
      v-html="anuncio.html_content"
    ></div>

    <!-- Anúncio do tipo Script -->
    <div 
      v-else-if="anuncio.tipo === 'script'" 
      class="anuncio-script"
      ref="scriptContainer"
    ></div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import axios from 'axios'

const props = defineProps({
  posicao: {
    type: String,
    required: true
  },
  pagina: {
    type: String,
    required: true
  },
  maxWidth: {
    type: String,
    default: null
  },
  maxHeight: {
    type: String,
    default: null
  },
  className: {
    type: String,
    default: ''
  }
})

const anuncio = ref(null)
const scriptContainer = ref(null)

const containerClass = computed(() => {
  const baseClass = 'anuncio-container'
  return `${baseClass} ${props.className}`.trim()
})

const containerStyle = computed(() => {
  const style = {}
  
  if (anuncio.value) {
    if (props.maxWidth) {
      style.maxWidth = props.maxWidth
    } else if (anuncio.value.largura) {
      style.maxWidth = `${anuncio.value.largura}px`
    }
    
    if (props.maxHeight) {
      style.maxHeight = props.maxHeight
    } else if (anuncio.value.altura) {
      style.maxHeight = `${anuncio.value.altura}px`
    }
  }
  
  return style
})

const imageStyle = computed(() => {
  const style = {}
  
  if (anuncio.value) {
    if (anuncio.value.largura) {
      style.width = `${anuncio.value.largura}px`
    }
    if (anuncio.value.altura) {
      style.height = `${anuncio.value.altura}px`
    }
  }
  
  return style
})

const carregarAnuncio = async () => {
  try {
    const response = await axios.get(`/api/anuncios/${props.posicao}/${props.pagina}`)
    
    if (response.data && response.data.length > 0) {
      anuncio.value = response.data[0] // Pega o primeiro anúncio ativo
      
      // Registra impressão
      registrarImpressao()
      
      // Se for script, executar após o DOM atualizar
      if (anuncio.value.tipo === 'script') {
        await nextTick()
        executarScript()
      }
    }
  } catch (error) {
    console.warn('Erro ao carregar anúncio:', error)
  }
}

const registrarImpressao = async () => {
  if (anuncio.value) {
    try {
      await axios.post(`/api/anuncios/${anuncio.value.id}/impressao`)
    } catch (error) {
      console.warn('Erro ao registrar impressão:', error)
    }
  }
}

const registrarClique = async () => {
  if (anuncio.value) {
    try {
      await axios.post(`/api/anuncios/${anuncio.value.id}/clique`)
    } catch (error) {
      console.warn('Erro ao registrar clique:', error)
    }
  }
}

const handleClick = () => {
  if (anuncio.value && anuncio.value.link && anuncio.value.tipo === 'html') {
    registrarClique()
    if (anuncio.value.nova_aba) {
      window.open(anuncio.value.link, '_blank')
    } else {
      window.location.href = anuncio.value.link
    }
  }
}

const executarScript = () => {
  if (anuncio.value && anuncio.value.script_content && scriptContainer.value) {
    try {
      const script = document.createElement('script')
      script.innerHTML = anuncio.value.script_content
      scriptContainer.value.appendChild(script)
    } catch (error) {
      console.warn('Erro ao executar script do anúncio:', error)
    }
  }
}

onMounted(() => {
  carregarAnuncio()
})
</script>

<style scoped>
.anuncio-container {
  display: block;
  margin: 0 auto;
  text-align: center;
}

.anuncio-imagem img {
  border-radius: 8px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.anuncio-imagem a:hover img {
  transform: scale(1.02);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.anuncio-html {
  border-radius: 8px;
  overflow: hidden;
}

.anuncio-script {
  min-height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Responsividade */
@media (max-width: 768px) {
  .anuncio-container {
    max-width: 100% !important;
  }
  
  .anuncio-imagem img {
    max-width: 100%;
    height: auto !important;
    width: auto !important;
  }
}
</style>
