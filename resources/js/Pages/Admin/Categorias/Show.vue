<template>
    <div class="container mx-auto p-4">
        <!-- Cabeçalho -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        {{ categoria.nome }}
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Detalhes da categoria e suas notícias
                    </p>
                </div>
                <div class="flex space-x-3">
                    <Link 
                        :href="route('admin.categorias.edit', categoria.slug)"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </Link>
                    <Link 
                        :href="route('admin.categorias.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar
                    </Link>
                </div>
            </div>
        </div>

        <!-- Informações da Categoria -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações Básicas</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nome:</label>
                            <p class="text-gray-900 font-medium">{{ categoria.nome }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Slug:</label>
                            <p class="text-gray-900 font-mono text-sm">{{ categoria.slug }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Descrição:</label>
                            <p class="text-gray-900">{{ categoria.descricao || 'Sem descrição' }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estatísticas</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Total de Notícias:</span>
                            <span class="text-lg font-bold text-blue-600">{{ categoria.noticias ? categoria.noticias.length : 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Criado em:</span>
                            <span class="text-sm text-green-600">{{ formatDate(categoria.created_at) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Atualizado em:</span>
                            <span class="text-sm text-purple-600">{{ formatDate(categoria.updated_at) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Notícias -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Notícias desta Categoria
                    <span v-if="categoria.noticias && categoria.noticias.length > 0" class="text-sm font-normal text-gray-600">
                        ({{ categoria.noticias.length }} {{ categoria.noticias.length > 10 ? 'mais recentes' : 'notícias' }})
                    </span>
                </h3>
            </div>

            <div v-if="categoria.noticias && categoria.noticias.length > 0" class="divide-y divide-gray-200">
                <div v-for="noticia in categoria.noticias" :key="noticia.id" class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-4">
                        <!-- Imagem -->
                        <div class="flex-shrink-0">
                            <img 
                                v-if="noticia.imagem_capa && noticia.imagem_capa.caminho" 
                                :src="`/storage/${noticia.imagem_capa.caminho}`" 
                                :alt="noticia.titulo"
                                class="w-24 h-24 object-cover rounded-lg"
                            >
                            <div v-else class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Conteúdo -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">
                                        {{ noticia.titulo }}
                                    </h4>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                        {{ noticia.resumo || 'Sem resumo disponível' }}
                                    </p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span v-if="noticia.autor">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ noticia.autor.nome }}
                                        </span>
                                        <span>
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ formatDate(noticia.created_at) }}
                                        </span>
                                        <span v-if="noticia.publicada" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Publicada
                                        </span>
                                        <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Rascunho
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Ações -->
                                <div class="flex space-x-2 ml-4">
                                    <Link 
                                        :href="route('noticias.show', noticia.slug)"
                                        class="text-blue-600 hover:text-blue-800 transition-colors p-1"
                                        title="Ver Notícia"
                                        target="_blank"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </Link>
                                    <Link 
                                        :href="route('admin.noticias.edit', noticia.id)"
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors p-1"
                                        title="Editar Notícia"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado Vazio -->
            <div v-else class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma notícia encontrada</h3>
                <p class="text-gray-500 mb-4">
                    Esta categoria ainda não possui notícias associadas.
                </p>
                <Link 
                    :href="route('admin.noticias.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Criar Nova Notícia
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin })

const props = defineProps({
    categoria: Object
})

// Função de formatação de data
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
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
