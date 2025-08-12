<template>
    <Admin>
        <div class="space-y-6">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <Link 
                        :href="route('admin.videos.index')"
                        class="text-azul-oxford hover:text-azul-noite transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold text-azul-oxford">Editar Vídeo</h1>
                        <p class="text-gray-600 mt-1">Editando: {{ video.titulo }}</p>
                    </div>
                </div>
            </div>

            <!-- Formulário -->
            <div class="max-w-4xl">
                <form @submit.prevent="submit" class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                    
                    <!-- Título -->
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-azul-oxford mb-2">
                            Título *
                        </label>
                        <input 
                            id="titulo"
                            v-model="form.titulo"
                            type="text" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                            :class="{ 
                                'border-red-500 bg-red-50': form.errors.titulo,
                                'border-green-500 bg-green-50': form.titulo && !form.errors.titulo
                            }"
                            placeholder="Digite o título do vídeo"
                            required
                        />
                        <p v-if="form.errors.titulo" class="text-sm text-red-600 font-medium mt-1">
                            {{ form.errors.titulo }}
                        </p>
                    </div>

                    <!-- URL do YouTube -->
                    <div>
                        <label for="url_externa" class="block text-sm font-medium text-azul-oxford mb-2">
                            URL do YouTube *
                        </label>
                        <input 
                            id="url_externa"
                            v-model="form.url_externa"
                            @blur="gerarPreview"
                            type="url" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                            :class="{ 
                                'border-red-500 bg-red-50': form.errors.url_externa,
                                'border-green-500 bg-green-50': form.url_externa && !form.errors.url_externa
                            }"
                            placeholder="https://www.youtube.com/watch?v=..."
                            required
                        />
                        <p v-if="form.errors.url_externa" class="text-sm text-red-600 font-medium mt-1">
                            {{ form.errors.url_externa }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Cole a URL completa do vídeo do YouTube
                        </p>
                    </div>

                    <!-- Preview do Vídeo -->
                    <div v-if="showPreview && videoPreview" class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-azul-oxford text-white p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-azul-oxford">Preview do Vídeo</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <img 
                                    :src="videoPreview.thumbnailUrl" 
                                    alt="Thumbnail do vídeo"
                                    class="w-full h-auto rounded-lg shadow-sm"
                                />
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200 space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">ID do YouTube</label>
                                    <p class="text-sm text-azul-oxford font-mono bg-gray-50 px-3 py-2 rounded mt-1">{{ videoPreview.id }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">URL de Embed</label>
                                    <p class="text-sm text-azul-oxford font-mono bg-gray-50 px-3 py-2 rounded mt-1 break-all">{{ videoPreview.embedUrl }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-azul-oxford mb-2">
                            Descrição *
                        </label>
                        <textarea 
                            id="descricao"
                            v-model="form.descricao"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors resize-vertical"
                            :class="{ 
                                'border-red-500 bg-red-50': form.errors.descricao,
                                'border-green-500 bg-green-50': form.descricao && !form.errors.descricao
                            }"
                            placeholder="Descreva o conteúdo do vídeo"
                            required
                        ></textarea>
                        <p v-if="form.errors.descricao" class="text-sm text-red-600 font-medium mt-1">
                            {{ form.errors.descricao }}
                        </p>
                    </div>

                    <!-- Thumbnail Personalizada -->
                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-azul-oxford mb-2">
                            Thumbnail Personalizada (Opcional)
                        </label>
                        <input 
                            id="thumbnail"
                            v-model="form.thumbnail"
                            type="url"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                            :class="{ 
                                'border-red-500 bg-red-50': form.errors.thumbnail,
                                'border-green-500 bg-green-50': form.thumbnail && !form.errors.thumbnail
                            }"
                            placeholder="https://exemplo.com/thumbnail.jpg"
                        />
                        <p class="text-sm text-gray-600 mt-1">
                            Se não fornecida, será usada a thumbnail padrão do YouTube
                        </p>
                        <p v-if="form.errors.thumbnail" class="text-sm text-red-600 font-medium mt-1">
                            {{ form.errors.thumbnail }}
                        </p>
                    </div>

                    <!-- Grid para Categoria e Autor -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Categoria -->
                        <div>
                            <label for="categoria_id" class="block text-sm font-medium text-azul-oxford mb-2">
                                Categoria *
                            </label>
                            <select 
                                id="categoria_id"
                                v-model="form.categoria_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                                :class="{ 
                                    'border-red-500 bg-red-50': form.errors.categoria_id,
                                    'border-green-500 bg-green-50': form.categoria_id && !form.errors.categoria_id
                                }"
                                required
                            >
                                <option value="">Selecione uma categoria</option>
                                <option 
                                    v-for="categoria in categorias" 
                                    :key="categoria.id" 
                                    :value="categoria.id"
                                >
                                    {{ categoria.nome }}
                                </option>
                            </select>
                            <p v-if="form.errors.categoria_id" class="text-sm text-red-600 font-medium mt-1">
                                {{ form.errors.categoria_id }}
                            </p>
                        </div>

                        <!-- Autor -->
                        <div>
                            <label for="autor_id" class="block text-sm font-medium text-azul-oxford mb-2">
                                Autor *
                            </label>
                            <select 
                                id="autor_id"
                                v-model="form.autor_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                                :class="{ 
                                    'border-red-500 bg-red-50': form.errors.autor_id,
                                    'border-green-500 bg-green-50': form.autor_id && !form.errors.autor_id
                                }"
                                required
                            >
                                <option value="">Selecione um autor</option>
                                <option 
                                    v-for="autor in autores" 
                                    :key="autor.id" 
                                    :value="autor.id"
                                >
                                    {{ autor.nome }}
                                </option>
                            </select>
                            <p v-if="form.errors.autor_id" class="text-sm text-red-600 font-medium mt-1">
                                {{ form.errors.autor_id }}
                            </p>
                        </div>
                    </div>

                    <!-- Grid para Duração e Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Duração -->
                        <div>
                            <label for="duracao" class="block text-sm font-medium text-azul-oxford mb-2">
                                Duração
                            </label>
                            <input 
                                id="duracao"
                                v-model="form.duracao"
                                type="text" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                                :class="{ 
                                    'border-red-500 bg-red-50': form.errors.duracao,
                                    'border-green-500 bg-green-50': form.duracao && !form.errors.duracao
                                }"
                                placeholder="Ex: 10:30"
                            />
                            <p v-if="form.errors.duracao" class="text-sm text-red-600 font-medium mt-1">
                                {{ form.errors.duracao }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-azul-oxford mb-2">
                                Status *
                            </label>
                            <select 
                                id="status"
                                v-model="form.status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                                :class="{ 
                                    'border-red-500 bg-red-50': form.errors.status,
                                    'border-green-500 bg-green-50': form.status && !form.errors.status
                                }"
                                required
                            >
                                <option value="rascunho">Rascunho</option>
                                <option value="publicada">Publicado</option>
                            </select>
                            <p v-if="form.errors.status" class="text-sm text-red-600 font-medium mt-1">
                                {{ form.errors.status }}
                            </p>
                        </div>
                    </div>

                    <!-- Informações de Status Atual -->
                    <div class="bg-gradient-to-r from-azul-lazuli/10 to-azul-oxford/10 border border-azul-lazuli/20 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-azul-oxford text-white p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-azul-oxford">Informações do Vídeo</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm text-gray-600 mb-1">Visualizações</div>
                                <div class="text-2xl font-bold text-azul-oxford">{{ video.visualizacoes || 0 }}</div>
                            </div>
                            <div v-if="video.publicada_em" class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm text-gray-600 mb-1">Publicado em</div>
                                <div class="text-lg font-semibold text-azul-oxford">{{ new Date(video.publicada_em).toLocaleDateString('pt-BR') }}</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="text-sm text-gray-600 mb-1">Status atual</div>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                          :class="video.status === 'publicada' 
                                                  ? 'bg-green-100 text-green-800' 
                                                  : 'bg-yellow-100 text-yellow-800'">
                                        <span class="w-2 h-2 rounded-full mr-2"
                                              :class="video.status === 'publicada' ? 'bg-green-400' : 'bg-yellow-400'"></span>
                                        {{ video.status === 'publicada' ? 'Publicado' : 'Rascunho' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <Link 
                            :href="route('admin.videos.index')" 
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors focus:ring-2 focus:ring-azul-lazuli focus:ring-offset-2"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </Link>
                        
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="inline-flex items-center px-8 py-3 bg-azul-oxford hover:bg-azul-noite text-white rounded-lg font-medium transition-all duration-200 focus:ring-2 focus:ring-azul-lazuli focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                        >
                            <svg v-if="!form.processing" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg v-if="form.processing" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-if="form.processing">Salvando...</span>
                            <span v-else>Atualizar Vídeo</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Admin>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { router, useForm, usePage, Link } from '@inertiajs/vue3'
import Admin from '@/Layouts/Admin.vue'

// Props recebidas do backend
const props = defineProps({
    video: Object,
    categorias: Array,
    autores: Array
})

// Estados reativos para o preview
const showPreview = ref(false)
const videoPreview = ref(null)

// Formulário com dados iniciais do vídeo
const form = useForm({
    titulo: props.video.titulo,
    descricao: props.video.descricao,
    url_externa: props.video.url_externa,
    thumbnail: props.video.thumbnail,
    categoria_id: props.video.categoria_id,
    autor_id: props.video.autor_id,
    duracao: props.video.duracao,
    status: props.video.status
})

// Função para gerar preview do YouTube
const gerarPreview = () => {
    if (!form.url_externa) {
        showPreview.value = false
        videoPreview.value = null
        return
    }

    // Extrair ID do YouTube da URL
    const extractYouTubeId = (url) => {
        const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i
        const match = url.match(regex)
        return match ? match[1] : null
    }

    const videoId = extractYouTubeId(form.url_externa)
    
    if (videoId) {
        videoPreview.value = {
            id: videoId,
            thumbnailUrl: `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg`,
            embedUrl: `https://www.youtube.com/embed/${videoId}`
        }
        showPreview.value = true
    } else {
        showPreview.value = false
        videoPreview.value = null
    }
}

// Função para enviar o formulário
const submit = () => {
    form.put(route('admin.videos.update', props.video.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirecionar para a listagem ou mostrar mensagem de sucesso
        }
    })
}

// Gerar preview inicial se já houver URL
if (props.video.url_externa) {
    gerarPreview()
}
</script>

<style scoped>
/* Estilos customizados se necessário */
</style>
