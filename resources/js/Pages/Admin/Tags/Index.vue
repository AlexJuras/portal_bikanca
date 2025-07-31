<template>
    <div class="container mx-auto p-4">
        <!-- Cabeçalho -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Gerenciar Tags
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Gerencie todas as tags do portal
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <Link
                        :href="route('admin.tags.create')"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nova Tag
                    </Link>
                </div>
            </div>
        </div>

        <!-- Mensagens de Feedback -->
        <div v-if="$page.props.flash?.success" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash?.error" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ $page.props.flash.error }}
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Pesquisar tags
                    </label>
                    <div class="relative">
                        <input
                            id="search"
                            v-model="search"
                            type="text"
                            placeholder="Digite o nome da tag..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            @input="debouncedSearch"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <button
                        v-if="search"
                        @click="clearSearch"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-purple-600">{{ tags.total }}</h3>
                    <p class="text-gray-600">Total de Tags</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-green-600">{{ totalNoticias }}</h3>
                    <p class="text-gray-600">Total de Notícias</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-blue-600">{{ tagMaisUsada?.noticias_count || 0 }}</h3>
                    <p class="text-gray-600">Tag Mais Usada</p>
                </div>
            </div>
        </div>

        <!-- Lista de Tags -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Versão Desktop -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tag
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Slug
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Notícias
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="tag in tags.data" :key="tag.id" class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 mr-3">
                                        <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">
                                            #{{ tag.nome }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            ID: {{ tag.id }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ tag.slug }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ tag.noticias_count || 0 }} notícias
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatDate(tag.updated_at) }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex justify-center space-x-2">
                                    <!-- Visualizar -->
                                    <Link 
                                        :href="route('admin.tags.show', tag.id)" 
                                        class="text-blue-600 hover:text-blue-800 transition-colors p-1"
                                        title="Visualizar"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </Link>
                                    
                                    <!-- Editar -->
                                    <Link 
                                        :href="route('admin.tags.edit', tag.id)" 
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors p-1"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </Link>
                                    
                                    <!-- Excluir -->
                                    <button 
                                        @click="confirmarExclusao(tag)"
                                        class="text-red-600 hover:text-red-800 transition-colors p-1"
                                        title="Excluir"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Versão Mobile/Tablet -->
            <div class="lg:hidden">
                <div v-for="tag in tags.data" :key="tag.id" class="border-b border-gray-200 p-4">
                    <div class="flex items-start space-x-3">
                        <div class="h-16 w-16 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900">
                                #{{ tag.nome }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ tag.slug }}
                            </p>
                            <div class="mt-2 flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ tag.noticias_count || 0 }} notícias
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ formatDate(tag.updated_at) }}
                                    </span>
                                </div>
                                <div class="flex space-x-3">
                                    <Link 
                                        :href="route('admin.tags.show', tag.id)" 
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Visualizar"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </Link>
                                    <Link 
                                        :href="route('admin.tags.edit', tag.id)" 
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </Link>
                                    <button 
                                        @click="confirmarExclusao(tag)"
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
                </div>
            </div>

            <!-- Vazio -->
            <div v-if="tags.data.length === 0" class="text-center py-12">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <p class="text-gray-600 text-lg mb-2">
                    {{ search ? 'Nenhuma tag encontrada' : 'Nenhuma tag criada ainda' }}
                </p>
                <p class="text-gray-500 mb-4">
                    {{ search ? 'Tente pesquisar com outros termos' : 'Crie sua primeira tag para começar' }}
                </p>
                <Link
                    v-if="!search"
                    :href="route('admin.tags.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Criar primeira tag
                </Link>
            </div>
        </div>

        <!-- Paginação -->
        <div v-if="tags.links && tags.data.length > 0" class="mt-6">
            <Pagination :links="tags.links" />
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div v-if="tagParaExcluir" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="cancelarExclusao">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Excluir Tag
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Tem certeza que deseja excluir a tag "<strong>{{ tagParaExcluir.nome }}</strong>"? 
                                Esta ação não pode ser desfeita.
                            </p>
                            <p v-if="tagParaExcluir.noticias_count > 0" class="text-sm text-red-600 mt-2">
                                <strong>Atenção:</strong> Esta tag possui {{ tagParaExcluir.noticias_count }} notícia(s) associada(s).
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button
                        @click="excluirTag"
                        :disabled="excluindo"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm disabled:opacity-50"
                    >
                        {{ excluindo ? 'Excluindo...' : 'Excluir' }}
                    </button>
                    <button
                        @click="cancelarExclusao"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Admin from '@/Layouts/Admin.vue'
import Pagination from '@/Components/Pagination.vue'
import { debounce } from 'lodash'

defineOptions({ layout: Admin })

const props = defineProps({
    tags: {
        type: Object,
        required: true
    },
    search: {
        type: String,
        default: ''
    }
})

// Estados locais
const search = ref(props.search || '')
const tagParaExcluir = ref(null)
const excluindo = ref(false)

// Computed properties
const totalNoticias = computed(() => {
    return props.tags.data.reduce((total, tag) => total + (tag.noticias_count || 0), 0)
})

const tagMaisUsada = computed(() => {
    return props.tags.data.reduce((prev, current) => {
        return (prev.noticias_count || 0) > (current.noticias_count || 0) ? prev : current
    }, props.tags.data[0])
})

// Funções
const formatDate = (date) => {
    if (!date) return 'N/A'
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}

const debouncedSearch = debounce(() => {
    router.get(route('admin.tags.index'), { search: search.value }, {
        preserveState: true,
        replace: true
    })
}, 300)

const clearSearch = () => {
    search.value = ''
    router.get(route('admin.tags.index'), {}, {
        preserveState: true,
        replace: true
    })
}

const confirmarExclusao = (tag) => {
    tagParaExcluir.value = tag
}

const cancelarExclusao = () => {
    tagParaExcluir.value = null
    excluindo.value = false
}

const excluirTag = () => {
    if (!tagParaExcluir.value) return
    
    excluindo.value = true
    
    router.delete(route('admin.tags.destroy', tagParaExcluir.value.id), {
        onSuccess: () => {
            cancelarExclusao()
        },
        onError: () => {
            excluindo.value = false
        }
    })
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
