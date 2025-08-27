<template>
    <div class="container mx-auto p-4">
        <!-- Cabeçalho -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Estatísticas das Notícias
            </h1>
            <p class="text-gray-600 mt-2">
                Visualizações, cliques e performance das notícias
            </p>
        </div>

        <!-- Estatísticas Gerais -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h3 class="text-2xl font-bold text-blue-600">
                    {{ estatisticas.total_visualizacoes?.toLocaleString() || 0 }}
                </h3>
                <p class="text-gray-600">Total Visualizações</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h3 class="text-2xl font-bold text-green-600">
                    {{ estatisticas.total_cliques?.toLocaleString() || 0 }}
                </h3>
                <p class="text-gray-600">Total Cliques</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h3 class="text-2xl font-bold text-purple-600">
                    {{ estatisticas.total_noticias || 0 }}
                </h3>
                <p class="text-gray-600">Total Notícias</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h3 class="text-2xl font-bold text-orange-600">
                    {{ Math.round(estatisticas.media_visualizacoes || 0) }}
                </h3>
                <p class="text-gray-600">Média Views</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h3 class="text-2xl font-bold text-red-600">
                    {{ Math.round(estatisticas.media_cliques || 0) }}
                </h3>
                <p class="text-gray-600">Média Cliques</p>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="md:w-48">
                    <label for="periodo" class="block text-sm font-medium text-gray-700 mb-2">
                        Período
                    </label>
                    <select
                        id="periodo"
                        v-model="filtros.periodo"
                        @change="aplicarFiltros"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Todos os tempos</option>
                        <option value="7d">Últimos 7 dias</option>
                        <option value="30d">Últimos 30 dias</option>
                        <option value="90d">Últimos 90 dias</option>
                        <option value="1y">Último ano</option>
                    </select>
                </div>
                
                <div class="md:w-48">
                    <label for="ordem" class="block text-sm font-medium text-gray-700 mb-2">
                        Ordenar por
                    </label>
                    <select
                        id="ordem"
                        v-model="filtros.ordem"
                        @change="aplicarFiltros"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="visualizacoes">Mais visualizadas</option>
                        <option value="cliques">Mais clicadas</option>
                        <option value="publicada_em">Mais recentes</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button
                        v-if="filtros.periodo || filtros.ordem !== 'visualizacoes'"
                        @click="limparFiltros"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
                    >
                        Limpar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Rankings -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Visualizações -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    Top 10 - Mais Visualizadas
                </h3>
                <div class="space-y-3">
                    <div
                        v-for="(noticia, index) in topVisualizacoes"
                        :key="`view-${noticia.id}`"
                        class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg"
                    >
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600">{{ index + 1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">
                                {{ noticia.titulo }}
                            </h4>
                            <p class="text-xs text-gray-500">
                                {{ noticia.categoria?.nome }} • {{ noticia.autor?.nome }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <div class="text-sm font-bold text-blue-600">
                                {{ noticia.visualizacoes?.toLocaleString() || 0 }}
                            </div>
                            <div class="text-xs text-gray-500">views</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Cliques -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    Top 10 - Mais Clicadas
                </h3>
                <div class="space-y-3">
                    <div
                        v-for="(noticia, index) in topCliques"
                        :key="`click-${noticia.id}`"
                        class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg"
                    >
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-green-600">{{ index + 1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">
                                {{ noticia.titulo }}
                            </h4>
                            <p class="text-xs text-gray-500">
                                {{ noticia.categoria?.nome }} • {{ noticia.autor?.nome }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <div class="text-sm font-bold text-green-600">
                                {{ noticia.cliques?.toLocaleString() || 0 }}
                            </div>
                            <div class="text-xs text-gray-500">cliques</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista Detalhada -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">
                    Estatísticas Detalhadas
                </h3>
            </div>
            
            <!-- Versão Desktop -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Notícia
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Autor
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoria
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Visualizações
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cliques
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-1">
                                    <span>CTR</span>
                                    <button 
                                        @mouseenter="showTooltipHandler($event)" 
                                        @mouseleave="hideTooltipHandler"
                                        @mousemove="updateTooltipPosition($event)"
                                        class="relative"
                                    >
                                        <svg class="w-4 h-4 text-gray-400 cursor-help hover:text-gray-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="noticia in noticias.data"
                            :key="noticia.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 mr-3">
                                        <img
                                            :src="noticia.imagem_capa?.caminho || '/logo.png'"
                                            :alt="noticia.titulo"
                                            class="h-12 w-12 object-cover rounded-lg"
                                        />
                                    </div>
                                    <div class="max-w-xs">
                                        <h3 class="text-sm font-medium text-gray-900 line-clamp-2">
                                            {{ noticia.titulo }}
                                        </h3>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ noticia.autor?.nome }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ noticia.categoria?.nome }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="text-sm font-bold text-blue-600">
                                    {{ noticia.visualizacoes?.toLocaleString() || 0 }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="text-sm font-bold text-green-600">
                                    {{ noticia.cliques?.toLocaleString() || 0 }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ calcularCTR(noticia.cliques, noticia.visualizacoes) }}%
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatarData(noticia.publicada_em) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Versão Mobile -->
            <div class="lg:hidden">
                <div
                    v-for="noticia in noticias.data"
                    :key="noticia.id"
                    class="border-b border-gray-200 p-4"
                >
                    <div class="flex items-start space-x-3">
                        <img
                            :src="noticia.imagem_capa?.caminho || '/logo.png'"
                            :alt="noticia.titulo"
                            class="h-16 w-16 object-cover rounded-lg flex-shrink-0"
                        />
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900 line-clamp-2">
                                {{ noticia.titulo }}
                            </h3>
                            <div class="mt-2 flex flex-wrap gap-2 items-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ noticia.categoria?.nome }}
                                </span>
                            </div>
                            <div class="mt-3 grid grid-cols-3 gap-3 text-center">
                                <div>
                                    <div class="text-sm font-bold text-blue-600">
                                        {{ noticia.visualizacoes?.toLocaleString() || 0 }}
                                    </div>
                                    <div class="text-xs text-gray-500">Views</div>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-green-600">
                                        {{ noticia.cliques?.toLocaleString() || 0 }}
                                    </div>
                                    <div class="text-xs text-gray-500">Cliques</div>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ calcularCTR(noticia.cliques, noticia.visualizacoes) }}%
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        CTR (Taxa de Cliques)
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                {{ noticia.autor?.nome }} • {{ formatarData(noticia.publicada_em) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginação -->
            <div
                v-if="noticias.links && noticias.links.length > 3"
                class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
            >
                <Pagination :links="noticias.links" />
            </div>
        </div>

        <!-- Tooltip CTR Global -->
        <div 
            v-if="showTooltip" 
            class="fixed z-50 bg-gray-900 text-white text-sm rounded-lg py-3 px-4 shadow-lg pointer-events-none"
            :style="tooltipStyle"
        >
            <div class="font-semibold mb-2">CTR (Click-Through Rate)</div>
            <div class="mb-1">📊 Taxa de cliques = (Cliques ÷ Visualizações) × 100</div>
            <div class="text-gray-300 text-xs">Indica quantos % dos que viram a notícia clicaram nela</div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin });

const props = defineProps({
    noticias: { type: Object, required: true },
    estatisticas: { type: Object, required: true },
    topVisualizacoes: { type: Array, default: () => [] },
    topCliques: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) }
});

const filtros = ref({
    periodo: props.filters.periodo || '',
    ordem: props.filters.ordem || 'visualizacoes'
});

// Tooltip CTR
const showTooltip = ref(false);
const tooltipStyle = ref({
    top: '0px',
    left: '0px'
});

const aplicarFiltros = () => {
    router.get(route('admin.estatisticas.noticias'), filtros.value, {
        preserveState: true,
        preserveScroll: true
    });
};

const limparFiltros = () => {
    filtros.value = {
        periodo: '',
        ordem: 'visualizacoes'
    };
    aplicarFiltros();
};

const formatarData = (data) => {
    if (!data) return '';
    return new Date(data).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const calcularCTR = (cliques, visualizacoes) => {
    if (!visualizacoes || visualizacoes === 0) return '0.0';
    const ctr = (cliques / visualizacoes) * 100;
    return ctr.toFixed(1);
};

// Funções do tooltip
const showTooltipHandler = (event) => {
    updateTooltipPosition(event);
    showTooltip.value = true;
};

const hideTooltipHandler = () => {
    showTooltip.value = false;
};

const updateTooltipPosition = (event) => {
    const offset = 15;
    tooltipStyle.value = {
        top: `${event.clientY - 80}px`,
        left: `${event.clientX + offset}px`
    };
};
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
