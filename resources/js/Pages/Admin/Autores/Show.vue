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
                                    <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"/>
                                </svg>
                                Admin
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <Link href="/admin/autores" class="text-azul-lazuli hover:text-azul-oxford transition-colors">
                                Autores
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-azul-oxford font-medium">{{ autor.nome }}</span>
                        </li>
                    </ol>
                </nav>

                <!-- Perfil do Autor -->
                <div class="bg-gradient-to-r from-azul-oxford to-azul-lazuli text-white rounded-lg p-8">
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                        <div class="flex-shrink-0">
                            <img
                                :src="autor.foto_perfil || '/images/user-placeholder.png'"
                                :alt="autor.nome"
                                class="w-32 h-32 rounded-full border-4 border-white/20"
                            />
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-3xl font-bold mb-2">{{ autor.nome }}</h1>
                            <p v-if="autor.email" class="text-blue-100 mb-4">{{ autor.email }}</p>
                            <p v-if="autor.bio" class="text-blue-100 leading-relaxed">{{ autor.bio }}</p>
                            <p v-else class="text-blue-100 italic">Jornalista do Portal Bikanca</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Estatísticas do Autor -->
                <div class="lg:col-span-3 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Total de Notícias -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-center">
                                <div class="bg-blue-500 rounded-lg p-3">
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
                                <div class="bg-purple-500 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Últimos 30 dias</p>
                                    <p class="text-2xl font-bold text-purple-600">{{ estatisticas.noticias_recentes }}</p>
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

                <!-- Notícias do Autor -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-2xl font-bold text-azul-oxford mb-6">
                            Notícias de {{ autor.nome }}
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
                                                v-if="noticia.imagem_capa?.caminho"
                                                :src="noticia.imagem_capa.caminho"
                                                :alt="noticia.titulo"
                                                class="w-full h-48 md:h-32 object-cover rounded-lg group-hover:opacity-90 transition-opacity"
                                            />
                                        </div>
                                        <div class="md:col-span-2">
                                            <span
                                                v-if="noticia.categoria"
                                                class="text-xs font-medium text-azul-lazuli bg-blue-50 px-2 py-1 rounded-full"
                                            >
                                                {{ noticia.categoria.nome }}
                                            </span>
                                            <h3 class="font-bold text-azul-oxford group-hover:text-azul-lazuli transition-colors mt-2 mb-2">
                                                {{ noticia.titulo }}
                                            </h3>
                                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                                {{ noticia.resumo }}
                                            </p>
                                            <div class="flex items-center text-xs text-gray-500 space-x-4">
                                                <span>{{ formatDate(noticia.publicada_em) }}</span>
                                                <span>{{ noticia.visualizacoes || 0 }} visualizações</span>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </article>
                        </div>

                        <div v-else class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            <p class="text-gray-600">Este autor ainda não publicou nenhuma notícia.</p>
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
                                href="/admin/autores"
                                class="w-full bg-azul-lazuli hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM5 8a2 2 0 012-2h6a2 2 0 012 2v9a2 2 0 01-2 2H7a2 2 0 01-2-2V8z"/>
                                </svg>
                                Todos os Autores
                            </Link>
                            
                            <Link 
                                href="/admin"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-azul-oxford font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"/>
                                </svg>
                                Dashboard
                            </Link>
                        </div>
                    </div>

                    <!-- Informações do Autor -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-azul-oxford mb-4">
                            Detalhes do Autor
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID:</span>
                                <span class="font-medium">#{{ autor.id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Membro desde:</span>
                                <span class="font-medium">{{ formatDate(autor.created_at) }}</span>
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
    autor: {
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
