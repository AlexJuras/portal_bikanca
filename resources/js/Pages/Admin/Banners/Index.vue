<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Cabeçalho da seção -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-azul-oxford">Banners Publicitários</h1>
                <p class="text-gray-600 mt-2">
                    Gerencie todos os anúncios e banners do site
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <Link
                    :href="route('admin.banners.create')"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-azul-lazuli hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Novo Banner
                </Link>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Página</label>
                    <select 
                        v-model="filters.pagina"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                    >
                        <option value="">Todas as páginas</option>
                        <option value="inicio">Página Inicial</option>
                        <option value="noticias">Lista de Notícias</option>
                        <option value="noticia">Página da Notícia</option>
                        <option value="videos">Lista de Vídeos</option>
                        <option value="video">Página do Vídeo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select 
                        v-model="filters.ativo"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-azul-lazuli focus:ring-azul-lazuli"
                    >
                        <option value="">Todos</option>
                        <option value="true">Ativo</option>
                        <option value="false">Inativo</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button
                        @click="clearFilters"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors"
                    >
                        Limpar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de Banners -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ filteredBanners.length }} banner(s) encontrado(s)
                </h3>
            </div>

            <div v-if="filteredBanners.length > 0" class="divide-y divide-gray-200">
                <div 
                    v-for="banner in filteredBanners" 
                    :key="banner.id"
                    class="p-6 hover:bg-gray-50 transition-colors"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <!-- Preview da imagem -->
                            <div class="flex-shrink-0">
                                <div 
                                    class="w-24 h-16 rounded-lg border overflow-hidden bg-gray-100 flex items-center justify-center"
                                >
                                    <img 
                                        v-if="banner.imagem"
                                        :src="`/storage/${banner.imagem}`"
                                        :alt="banner.nome"
                                        class="w-full h-full object-cover"
                                    />
                                    <svg 
                                        v-else
                                        class="w-8 h-8 text-gray-400" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Informações do banner -->
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-lg font-medium text-gray-900">{{ banner.nome }}</h3>
                                    <span 
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            banner.ativo 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-red-100 text-red-800'
                                        ]"
                                    >
                                        {{ banner.ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                                <div class="mt-1 text-sm text-gray-600">
                                    <p><strong>Página:</strong> {{ getPageName(banner.pagina) }}</p>
                                    <p><strong>Posição:</strong> {{ getPositionName(banner.posicao) }}</p>
                                    <p><strong>Dimensões:</strong> {{ banner.largura }}x{{ banner.altura }}px</p>
                                    <p v-if="banner.link"><strong>Link:</strong> {{ banner.link }}</p>
                                    <p v-if="banner.descricao" class="mt-2">{{ banner.descricao }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex items-center space-x-2">
                            <Link
                                :href="route('admin.banners.show', banner.id)"
                                class="text-azul-lazuli hover:text-azul-oxford transition-colors"
                                title="Visualizar"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </Link>
                            <Link
                                :href="route('admin.banners.edit', banner.id)"
                                class="text-indigo-600 hover:text-indigo-800 transition-colors"
                                title="Editar"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </Link>
                            <button
                                @click="confirmDelete(banner)"
                                class="text-red-600 hover:text-red-800 transition-colors"
                                title="Excluir"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado vazio -->
            <div v-else class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum banner encontrado</h3>
                <p class="text-gray-600 mb-6">Comece criando seu primeiro banner publicitário.</p>
                <Link
                    :href="route('admin.banners.create')"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-azul-lazuli hover:bg-blue-700"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Criar Banner
                </Link>
            </div>
        </div>
    </div>

    <!-- Modal de confirmação de exclusão -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">Confirmar Exclusão</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Tem certeza que deseja excluir o banner "{{ bannerToDelete?.nome }}"? 
                        Esta ação não pode ser desfeita.
                    </p>
                </div>
                <div class="flex justify-center space-x-4 px-4 py-3">
                    <button
                        @click="cancelDelete"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteBanner"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

defineOptions({
    layout: 'Admin'
})

const props = defineProps({
    banners: Array
})

const filters = ref({
    pagina: '',
    ativo: ''
})

const showDeleteModal = ref(false)
const bannerToDelete = ref(null)

const filteredBanners = computed(() => {
    return props.banners.filter(banner => {
        if (filters.value.pagina && banner.pagina !== filters.value.pagina) {
            return false
        }
        if (filters.value.ativo !== '' && banner.ativo.toString() !== filters.value.ativo) {
            return false
        }
        return true
    })
})

const clearFilters = () => {
    filters.value = {
        pagina: '',
        ativo: ''
    }
}

const getPageName = (pagina) => {
    const pages = {
        'inicio': 'Página Inicial',
        'noticias': 'Lista de Notícias',
        'noticia': 'Página da Notícia',
        'videos': 'Lista de Vídeos',
        'video': 'Página do Vídeo'
    }
    return pages[pagina] || pagina
}

const getPositionName = (posicao) => {
    const positions = {
        'inicio_topo': 'Banner Topo',
        'inicio_meio': 'Banner Meio',
        'noticias_principal': 'Banner Principal',
        'noticias_horizontal': 'Banner Horizontal',
        'noticias_lateral': 'Banner Lateral',
        'noticias_vertical': 'Banner Vertical',
        'noticia_meio': 'Banner Meio da Notícia',
        'noticia_final': 'Banner Final da Notícia',
        'videos_topo': 'Banner Topo Vídeos',
        'videos_lateral': 'Banner Lateral Vídeos',
        'video_meio': 'Banner Meio do Vídeo',
        'video_final': 'Banner Final do Vídeo'
    }
    return positions[posicao] || posicao
}

const confirmDelete = (banner) => {
    bannerToDelete.value = banner
    showDeleteModal.value = true
}

const cancelDelete = () => {
    bannerToDelete.value = null
    showDeleteModal.value = false
}

const deleteBanner = () => {
    if (bannerToDelete.value) {
        router.delete(route('admin.banners.destroy', bannerToDelete.value.id), {
            onSuccess: () => {
                bannerToDelete.value = null
                showDeleteModal.value = false
            }
        })
    }
}
</script>
