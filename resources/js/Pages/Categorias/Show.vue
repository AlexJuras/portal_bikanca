<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumb -->
                <nav class="text-sm mb-6" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li>
                            <Link href="/" class="text-azul-lazuli hover:text-azul-oxford transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Home
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <Link href="/categorias" class="text-azul-lazuli hover:text-azul-oxford transition-colors">
                                Categorias
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-azul-oxford font-medium">{{ categoria.nome }}</span>
                        </li>
                    </ol>
                </nav>

                <!-- Título e Descrição -->
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-azul-oxford mb-4">
                        {{ categoria.nome }}
                    </h1>
                    <p v-if="categoria.descricao" class="text-xl text-gray-600 max-w-3xl mx-auto">
                        {{ categoria.descricao }}
                    </p>
                    <p v-else class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Explore todas as notícias da categoria {{ categoria.nome.toLowerCase() }}.
                    </p>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Informações da Categoria -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-azul-oxford mb-6">
                            Informações da Categoria
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Card de Total de Notícias -->
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-blue-100 text-sm font-medium">Total de Notícias</p>
                                        <p class="text-3xl font-bold">{{ estatisticas.total_noticias }}</p>
                                    </div>
                                    <div class="bg-white/20 rounded-full p-3">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Card de Notícias Publicadas -->
                            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-100 text-sm font-medium">Publicadas</p>
                                        <p class="text-3xl font-bold">{{ estatisticas.noticias_publicadas }}</p>
                                    </div>
                                    <div class="bg-white/20 rounded-full p-3">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Card de Notícias Recentes -->
                            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-purple-100 text-sm font-medium">Últimos 30 dias</p>
                                        <p class="text-3xl font-bold">{{ estatisticas.noticias_recentes }}</p>
                                    </div>
                                    <div class="bg-white/20 rounded-full p-3">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Card da Notícia Mais Vista -->
                            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-orange-100 text-sm font-medium">Mais visualizada</p>
                                        <p class="text-lg font-bold">{{ estatisticas.mais_visualizada?.visualizacoes || 0 }} visualizações</p>
                                    </div>
                                    <div class="bg-white/20 rounded-full p-3">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notícia Mais Vista -->
                        <div v-if="estatisticas.mais_visualizada" class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-azul-oxford mb-4">
                                Notícia Mais Vista da Categoria
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="font-medium text-azul-oxford mb-2">
                                    {{ estatisticas.mais_visualizada.titulo }}
                                </h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Publicado em {{ formatDate(estatisticas.mais_visualizada.publicada_em) }}
                                </p>
                                <p class="text-sm text-gray-700 mb-4">
                                    {{ estatisticas.mais_visualizada.resumo }}
                                </p>
                                <Link 
                                    :href="route('noticias.show', estatisticas.mais_visualizada.id)"
                                    class="inline-flex items-center text-azul-lazuli hover:text-azul-oxford font-medium"
                                >
                                    Ler notícia completa
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </Link>
                            </div>
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
                                :href="route('categorias.noticias', categoria.slug || categoria.id)"
                                class="w-full bg-azul-lazuli hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                </svg>
                                Ver Todas as Notícias
                            </Link>
                            
                            <Link 
                                href="/categorias"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-azul-oxford font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Todas as Categorias
                            </Link>
                        </div>
                    </div>

                    <!-- Informações Adicionais -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-azul-oxford mb-4">
                            Detalhes da Categoria
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Slug:</span>
                                <span class="font-medium">{{ categoria.slug }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID:</span>
                                <span class="font-medium">#{{ categoria.id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Criada em:</span>
                                <span class="font-medium">{{ formatDate(categoria.created_at) }}</span>
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
import Principal from "@/Layouts/Principal.vue";
import { Link } from "@inertiajs/vue3";

defineOptions({ layout: Principal });

const props = defineProps({
    categoria: {
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
</style>
