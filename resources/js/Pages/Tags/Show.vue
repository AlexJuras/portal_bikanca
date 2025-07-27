<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumb -->
                <nav class="text-sm mb-6" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li>
                            <Link href="/admin" class="text-azul-lazuli hover:text-azul-oxford transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Admin
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <Link :href="route('admin.tags.index')" class="text-azul-lazuli hover:text-azul-oxford transition-colors">
                                Tags
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-azul-oxford font-medium">{{ tag.nome }}</span>
                        </li>
                    </ol>
                </nav>

                <!-- Cabeçalho da Tag -->
                <div class="bg-gradient-to-r from-azul-oxford to-azul-lazuli text-white rounded-lg p-8">
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-3xl font-bold mb-2">#{{ tag.nome }}</h1>
                            <p v-if="tag.descricao" class="text-blue-100 leading-relaxed mb-4">{{ tag.descricao }}</p>
                            <p v-else class="text-blue-100 italic mb-4">Explore todas as notícias relacionadas a esta tag</p>
                            <div class="inline-flex items-center bg-white/20 rounded-full px-4 py-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                </svg>
                                {{ estatisticas.total_noticias }} notícia{{ estatisticas.total_noticias !== 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Estatísticas da Tag -->
                <div class="lg:col-span-3 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Total de Notícias -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-center">
                                <div class="bg-purple-500 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total de Notícias</p>
                                    <p class="text-2xl font-bold text-azul-oxford">{{ estatisticas.total_noticias }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Notícias Publicadas -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-center">
                                <div class="bg-green-500 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Publicadas</p>
                                    <p class="text-2xl font-bold text-green-600">{{ estatisticas.noticias_publicadas }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Notícias Recentes -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-center">
                                <div class="bg-blue-500 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Últimos 30 dias</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ estatisticas.noticias_recentes }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Mais Visualizada -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-center">
                                <div class="bg-orange-500 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Mais vista</p>
                                    <p class="text-2xl font-bold text-orange-600">{{ estatisticas.mais_visualizada?.visualizacoes || 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notícias com esta Tag -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-2xl font-bold text-azul-oxford mb-6">
                            Notícias com a tag "{{ tag.nome }}"
                        </h2>

                        <div v-if="noticias.data.length > 0" class="space-y-6">
                            <article
                                v-for="noticia in noticias.data"
                                :key="noticia.id"
                                class="border-b pb-6 last:border-b-0 last:pb-0"
                            >
                                <Link :href="route('noticias.show', noticia.id)" class="block group">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="md:col-span-1">
                                            <img
                                                v-if="noticia.imagemCapa?.caminho"
                                                :src="noticia.imagemCapa.caminho"
                                                :alt="noticia.titulo"
                                                class="w-full h-48 md:h-32 object-cover rounded-lg group-hover:opacity-90 transition-opacity"
                                            />
                                        </div>
                                        <div class="md:col-span-2">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span
                                                    v-if="noticia.categoria"
                                                    class="text-xs font-medium text-azul-lazuli bg-blue-50 px-2 py-1 rounded-full"
                                                >
                                                    {{ noticia.categoria.nome }}
                                                </span>
                                                <span
                                                    v-if="noticia.autor"
                                                    class="text-xs text-gray-500"
                                                >
                                                    por {{ noticia.autor.nome }}
                                                </span>
                                            </div>
                                            <h3 class="font-bold text-azul-oxford group-hover:text-azul-lazuli transition-colors mb-2">
                                                {{ noticia.titulo }}
                                            </h3>
                                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                                {{ noticia.resumo }}
                                            </p>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center text-xs text-gray-500 space-x-4">
                                                    <span>{{ formatDate(noticia.publicada_em) }}</span>
                                                    <span>{{ noticia.visualizacoes || 0 }} visualizações</span>
                                                </div>
                                                <!-- Tags da notícia -->
                                                <div v-if="noticia.tags && noticia.tags.length > 0" class="flex flex-wrap gap-1">
                                                    <span
                                                        v-for="noticiaTag in noticia.tags.slice(0, 3)"
                                                        :key="noticiaTag.id"
                                                        class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full"
                                                        :class="{ 'bg-purple-100 text-purple-700': noticiaTag.id === tag.id }"
                                                    >
                                                        #{{ noticiaTag.nome }}
                                                    </span>
                                                    <span v-if="noticia.tags.length > 3" class="text-xs text-gray-400">
                                                        +{{ noticia.tags.length - 3 }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </article>
                        </div>

                        <div v-else class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <p class="text-gray-600">Ainda não há notícias com esta tag.</p>
                        </div>

                        <!-- Paginação -->
                        <div v-if="noticias.links && noticias.data.length > 0" class="mt-8">
                            <Pagination :links="noticias.links" />
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Ações Rápidas -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-azul-oxford mb-4">
                            Ações Rápidas
                        </h3>
                        <div class="space-y-3">
                            <Link 
                                :href="route('admin.tags.index')"
                                class="w-full bg-azul-lazuli hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                Todas as Tags
                            </Link>
                            
                            <Link 
                                href="/noticias"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-azul-oxford font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                </svg>
                                Todas as Notícias
                            </Link>
                        </div>
                    </div>

                    <!-- Tags Relacionadas -->
                    <div v-if="tagsRelacionadas && tagsRelacionadas.length > 0" class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-azul-oxford mb-4">
                            Tags Relacionadas
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <Link
                                v-for="tagRelacionada in tagsRelacionadas"
                                :key="tagRelacionada.id"
                                :href="route('admin.tags.show', tagRelacionada.id)"
                                class="text-sm bg-gray-100 hover:bg-azul-lazuli hover:text-white text-gray-700 px-3 py-2 rounded-full transition-colors"
                            >
                                #{{ tagRelacionada.nome }}
                            </Link>
                        </div>
                    </div>

                    <!-- Informações da Tag -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-azul-oxford mb-4">
                            Detalhes da Tag
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID:</span>
                                <span class="font-medium">#{{ tag.id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Criada em:</span>
                                <span class="font-medium">{{ formatDate(tag.created_at) }}</span>
                            </div>
                            <div v-if="tag.slug" class="flex justify-between">
                                <span class="text-gray-600">Slug:</span>
                                <span class="font-medium">{{ tag.slug }}</span>
                            </div>
                            <div v-if="estatisticas.mais_visualizada" class="border-t pt-3 mt-3">
                                <span class="text-gray-600 text-xs">Notícia mais vista:</span>
                                <p class="font-medium text-sm mt-1">{{ estatisticas.mais_visualizada.titulo }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import Admin from "@/Layouts/Admin.vue";
import { Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

defineOptions({ layout: Admin });

const props = defineProps({
    tag: {
        type: Object,
        required: true,
    },
    noticias: {
        type: Object,
        required: true,
    },
    estatisticas: {
        type: Object,
        required: true,
    },
    tagsRelacionadas: {
        type: Array,
        default: () => [],
    },
});

// Formatar data
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('pt-BR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>

<style scoped>
.text-azul-oxford {
    color: #1e3a8a;
}

.text-azul-lazuli {
    color: #007FFF;
}

.bg-azul-lazuli {
    background-color: #007FFF;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
