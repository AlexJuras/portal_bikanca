<template>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-azul-oxford">Criar Novo Banner</h1>
            <p class="text-gray-600 mt-2">
                Adicione um novo banner publicitário ao site
            </p>
        </div>

        <!-- Formulário -->
        <form @submit.prevent="submit" class="space-y-8">
            <!-- Card Principal -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informações Básicas</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div class="md:col-span-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome do Banner *
                        </label>
                        <input
                            id="nome"
                            v-model="form.nome"
                            type="text"
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                            placeholder="Ex: Banner Principal - Home"
                        />
                        <div v-if="errors.nome" class="mt-1 text-sm text-red-600">{{ errors.nome }}</div>
                    </div>

                    <!-- Página -->
                    <div>
                        <label for="pagina" class="block text-sm font-medium text-gray-700 mb-2">
                            Página *
                        </label>
                        <select
                            id="pagina"
                            v-model="form.pagina"
                            required
                            @change="updatePositions"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                        >
                            <option value="">Selecione uma página</option>
                            <option value="inicio">Página Inicial</option>
                            <option value="noticias">Lista de Notícias</option>
                            <option value="noticia">Página da Notícia</option>
                            <option value="videos">Lista de Vídeos</option>
                            <option value="video">Página do Vídeo</option>
                        </select>
                        <div v-if="errors.pagina" class="mt-1 text-sm text-red-600">{{ errors.pagina }}</div>
                    </div>

                    <!-- Posição -->
                    <div>
                        <label for="posicao" class="block text-sm font-medium text-gray-700 mb-2">
                            Posição *
                        </label>
                        <select
                            id="posicao"
                            v-model="form.posicao"
                            required
                            @change="updateDimensions"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                            :disabled="!form.pagina"
                        >
                            <option value="">Selecione uma posição</option>
                            <option 
                                v-for="(label, key) in availablePositions" 
                                :key="key" 
                                :value="key"
                            >
                                {{ label }}
                            </option>
                        </select>
                        <div v-if="errors.posicao" class="mt-1 text-sm text-red-600">{{ errors.posicao }}</div>
                    </div>

                    <!-- Dimensões -->
                    <div>
                        <label for="largura" class="block text-sm font-medium text-gray-700 mb-2">
                            Largura (px) *
                        </label>
                        <input
                            id="largura"
                            v-model.number="form.largura"
                            type="number"
                            min="1"
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                        />
                        <div v-if="errors.largura" class="mt-1 text-sm text-red-600">{{ errors.largura }}</div>
                    </div>

                    <div>
                        <label for="altura" class="block text-sm font-medium text-gray-700 mb-2">
                            Altura (px) *
                        </label>
                        <input
                            id="altura"
                            v-model.number="form.altura"
                            type="number"
                            min="1"
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                        />
                        <div v-if="errors.altura" class="mt-1 text-sm text-red-600">{{ errors.altura }}</div>
                    </div>

                    <!-- Link -->
                    <div class="md:col-span-2">
                        <label for="link" class="block text-sm font-medium text-gray-700 mb-2">
                            Link (URL)
                        </label>
                        <input
                            id="link"
                            v-model="form.link"
                            type="url"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                            placeholder="https://exemplo.com"
                        />
                        <div v-if="errors.link" class="mt-1 text-sm text-red-600">{{ errors.link }}</div>
                        <p class="mt-1 text-sm text-gray-500">URL para onde o banner deve redirecionar quando clicado</p>
                    </div>

                    <!-- Descrição -->
                    <div class="md:col-span-2">
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição
                        </label>
                        <textarea
                            id="descricao"
                            v-model="form.descricao"
                            rows="3"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                            placeholder="Descrição opcional do banner"
                        ></textarea>
                        <div v-if="errors.descricao" class="mt-1 text-sm text-red-600">{{ errors.descricao }}</div>
                    </div>
                </div>
            </div>

            <!-- Card de Imagem -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Imagem do Banner</h2>
                
                <!-- Upload de Imagem -->
                <div class="space-y-4">
                    <div>
                        <label for="imagem" class="block text-sm font-medium text-gray-700 mb-2">
                            Arquivo de Imagem
                        </label>
                        <input
                            id="imagem"
                            ref="fileInput"
                            type="file"
                            accept="image/*"
                            @change="handleFileUpload"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                        />
                        <div v-if="errors.imagem" class="mt-1 text-sm text-red-600">{{ errors.imagem }}</div>
                        <p class="mt-1 text-sm text-gray-500">
                            Formatos aceitos: JPEG, PNG, JPG, GIF. Tamanho máximo: 2MB
                        </p>
                    </div>

                    <!-- Preview da Imagem -->
                    <div v-if="imagePreview" class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <img 
                                :src="imagePreview" 
                                alt="Preview"
                                class="max-w-full h-auto max-h-64 mx-auto rounded-lg shadow-sm"
                            />
                            <div class="text-center mt-2 text-sm text-gray-600">
                                Dimensões recomendadas: {{ form.largura || '---' }}x{{ form.altura || '---' }}px
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Configurações -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Configurações</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input
                                v-model="form.ativo"
                                type="checkbox"
                                class="rounded border-gray-300 text-azul-lazuli shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                            />
                            <span class="ml-2 text-sm text-gray-700">Banner ativo</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Desmarque para desativar temporariamente o banner</p>
                    </div>

                    <!-- Ordem -->
                    <div>
                        <label for="ordem" class="block text-sm font-medium text-gray-700 mb-2">
                            Ordem de Exibição
                        </label>
                        <input
                            id="ordem"
                            v-model.number="form.ordem"
                            type="number"
                            min="0"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                        />
                        <div v-if="errors.ordem" class="mt-1 text-sm text-red-600">{{ errors.ordem }}</div>
                        <p class="mt-1 text-sm text-gray-500">Número menor = maior prioridade</p>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex items-center justify-between">
                <Link
                    :href="route('admin.banners.index')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-azul-lazuli"
                >
                    Cancelar
                </Link>
                
                <button
                    type="submit"
                    :disabled="processing"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-azul-lazuli hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                >
                    <svg v-if="processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ processing ? 'Criando...' : 'Criar Banner' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({
    layout: 'Admin'
})

const props = defineProps({
    posicoes: Object,
    errors: Object
})

const form = useForm({
    nome: '',
    pagina: '',
    posicao: '',
    imagem: null,
    link: '',
    descricao: '',
    largura: '',
    altura: '',
    ativo: true,
    ordem: 0
})

const fileInput = ref(null)
const imagePreview = ref(null)
const processing = ref(false)

const availablePositions = computed(() => {
    return form.pagina ? props.posicoes[form.pagina] || {} : {}
})

const dimensionMap = {
    'inicio_topo': { largura: 728, altura: 90 },
    'inicio_meio': { largura: 728, altura: 120 },
    'noticias_principal': { largura: 970, altura: 250 },
    'noticias_horizontal': { largura: 728, altura: 90 },
    'noticias_lateral': { largura: 300, altura: 250 },
    'noticias_vertical': { largura: 300, altura: 600 },
    'noticia_meio': { largura: 728, altura: 90 },
    'noticia_final': { largura: 300, altura: 250 },
    'videos_topo': { largura: 728, altura: 90 },
    'videos_lateral': { largura: 300, altura: 250 },
    'video_meio': { largura: 728, altura: 90 },
    'video_final': { largura: 300, altura: 250 }
}

const updatePositions = () => {
    form.posicao = ''
    form.largura = ''
    form.altura = ''
}

const updateDimensions = () => {
    if (form.posicao && dimensionMap[form.posicao]) {
        form.largura = dimensionMap[form.posicao].largura
        form.altura = dimensionMap[form.posicao].altura
    }
}

const handleFileUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.imagem = file
        
        // Criar preview
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    }
}

const submit = () => {
    processing.value = true
    form.post(route('admin.banners.store'), {
        onFinish: () => {
            processing.value = false
        }
    })
}
</script>
