<script setup>
import { ref, onMounted, computed, watch } from "vue";
import Principal from "@/Layouts/Principal.vue";
import { Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

// Importar imagem do usuário
const userImage = '/images/user-placeholder.png';

defineOptions({ layout: Principal });

const props = defineProps({
    noticias: {
        type: Object,
        required: true,
        default: () => ({
            data: [],
            links: [],
        }),
    },
    categorias: {
        type: Array,
        required: true,
        default: () => [],
    },
    maisLidas: {
        type: Array,
        required: true,
        default: () => [],
    },
    categoria: {
        type: Object,
        required: false,
        default: null,
    },
});

// Atualizar dados da página baseado na categoria
const pageData = ref({
    name: props.categoria ? `Categoria: ${props.categoria.nome}` : "Todas as Notícias",
    description: props.categoria 
        ? `Acompanhe as principais notícias da categoria ${props.categoria.nome}.`
        : "Acompanhe todas as principais notícias do Brasil e do mundo em tempo real, organizadas por relevância e data de publicação.",
    icon: "📰",
});

// Detectar se estamos visualizando uma categoria específica
const isVisualizandoCategoria = computed(() => !!props.categoria);

// Controles de filtro e ordenação (apenas para página geral)
const categoriaSelecionada = ref("all");
const sortBy = ref("date"); // 'date' ou 'views'
const searchQuery = ref("");
const currentPage = ref(1);
const newsPerPage = 9;
const totalNews = ref(156); // Total simulado

// Computed para filtrar notícias
const noticiasFiltradas = computed(() => {
    let filtro = props.noticias.data;

    // Se estamos visualizando uma categoria específica, aplicar apenas filtro de busca
    if (isVisualizandoCategoria.value) {
        // Filtrar por busca no título (funciona em páginas de categoria)
        if (searchQuery.value.trim()) {
            const query = searchQuery.value.toLowerCase().trim();
            filtro = filtro.filter(noticia => 
                noticia.titulo.toLowerCase().includes(query)
            );
        }
        return filtro;
    }

    // Para página geral, aplicar todos os filtros
    // Filtrar por categoria (apenas na página geral)
    if (categoriaSelecionada.value !== "all") {
        filtro = filtro.filter(
            noticia => noticia.categoria_id === categoriaSelecionada.value
        );
    }

    // Filtrar por busca no título
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase().trim();
        filtro = filtro.filter(noticia => 
            noticia.titulo.toLowerCase().includes(query)
        );
    }

    return filtro;
});

// Computed para a notícia em destaque
const noticiaDestaque = computed(() => {
    if (!props.noticias.data || props.noticias.data.length === 0) {
        return null;
    }
    return props.noticias.data[0];
});

// Computed para nome da categoria selecionada
const categoriaSelecionadaNome = computed(() => {
    if (categoriaSelecionada.value === "all") return "Todas";
    const categoria = props.categorias.find(cat => cat.id === categoriaSelecionada.value);
    return categoria?.nome || "Todas";
});

// Função para navegar páginas
const goToPage = (page) => {
    currentPage.value = page;
    window.scrollTo({ top: 0, behavior: "smooth" });
};

// Função para filtrar por categoria
const filterByCategory = (category) => {
    categoriaSelecionada.value = category;
    currentPage.value = 1;
    // Limpar busca quando trocar categoria
    searchQuery.value = "";
};

// Função para alterar ordenação
const changeSortBy = (sort) => {
    sortBy.value = sort;
    currentPage.value = 1;
};

// Função para limpar filtros
const clearFilters = () => {
    categoriaSelecionada.value = "all";
    searchQuery.value = "";
    sortBy.value = "date";
    currentPage.value = 1;
};

// Função para formatar número de visualizações
const formatViews = (views) => {
    return views > 999 ? `${(views/1000).toFixed(1)}k` : views;
};

// Calcular contadores de categoria
const updateCategoryCounts = () => {
    if (!props.noticias?.data) return;
    
    props.categorias.forEach((categoria) => {
        categoria.count = props.noticias.data.filter(
            (noticia) => noticia.categoria_id === categoria.id
        ).length;
    });
};

//Formatar data
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Watch para atualizar contadores quando a busca mudar
watch([searchQuery, categoriaSelecionada], () => {
    currentPage.value = 1;
});

onMounted(() => {
    updateCategoryCounts();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumb Melhorado -->
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
                            <span class="text-gray-600">Notícias</span>
                        </li>
                        <li v-if="categoriaSelecionada !== 'all'" class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-azul-oxford font-medium">{{ categoriaSelecionadaNome }}</span>
                        </li>
                    </ol>
                </nav>

                <!-- Título da Página -->
                <div class="flex items-center space-x-4 mb-6">
                    <div class="text-4xl">{{ pageData.icon }}</div>
                    <div>
                        <h1 class="text-4xl font-bold text-azul-oxford">
                            {{ categoriaSelecionada === 'all' ? pageData.name : `Notícias de ${categoriaSelecionadaNome}` }}
                        </h1>
                        <p class="text-gray-600 mt-2">
                            {{ pageData.description }}
                        </p>
                    </div>
                </div>

                <!-- Barra de Pesquisa -->
                <div class="mb-6">
                    <div class="relative max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="categoria ? `Pesquisar em ${categoria.nome}...` : 'Pesquisar notícias pelo título...'"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-azul-oxford focus:border-transparent"
                        />
                        <div v-if="searchQuery" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button
                                @click="searchQuery = ''"
                                class="text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filtros e Controles (apenas na página geral) -->
                <div v-if="!isVisualizandoCategoria" class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 mb-4">
                    <!-- Filtros de Categoria -->
                    <div class="flex flex-wrap gap-2">
                        <button
                            @click="filterByCategory('all')"
                            :class="[
                                'px-4 py-2 rounded-full text-sm font-medium transition-all',
                                categoriaSelecionada === 'all'
                                    ? 'bg-azul-oxford text-white shadow-md'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            ]"
                        >
                            Todas
                            <span class="ml-1 text-xs opacity-75">({{ props.noticias.data.length }})</span>
                        </button>
                        <button
                            v-for="categoria in categorias"
                            :key="categoria.id"
                            @click="filterByCategory(categoria.id)"
                            :class="[
                                'px-4 py-2 rounded-full text-sm font-medium transition-all',
                                categoriaSelecionada === categoria.id
                                    ? 'bg-azul-oxford text-white shadow-md'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            ]"
                        >
                            {{ categoria.nome }}
                            <span class="ml-1 text-xs opacity-75">({{ categoria.count || 0 }})</span>
                        </button>
                    </div>

                    <!-- Controles à direita -->
                    <div class="flex items-center space-x-4">
                        <!-- Ordenação -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Ordenar por:</span>
                            <select
                                v-model="sortBy"
                                @change="changeSortBy(sortBy)"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-azul-oxford"
                            >
                                <option value="date">Mais recentes</option>
                                <option value="views">Mais lidas</option>
                            </select>
                        </div>

                        <!-- Botão limpar filtros -->
                        <button
                            v-if="categoriaSelecionada !== 'all' || searchQuery.trim()"
                            @click="clearFilters"
                            class="text-sm text-azul-lazuli hover:text-azul-oxford font-medium flex items-center space-x-1"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Limpar filtros</span>
                        </button>
                    </div>
                </div>

                <!-- Informações da categoria específica (quando visualizando uma categoria) -->
                <div v-if="isVisualizandoCategoria" class="bg-gradient-to-r from-azul-oxford to-azul-lazuli text-white rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-2">{{ categoria.nome }}</h2>
                            <p class="text-blue-100 mb-3">
                                {{ searchQuery.trim() 
                                    ? `Pesquisando por "${searchQuery}" em ${categoria.nome.toLowerCase()}`
                                    : `Visualizando todas as notícias da categoria ${categoria.nome.toLowerCase()}`
                                }}
                            </p>
                            <div class="flex items-center space-x-4 text-sm text-blue-100">
                                <span>
                                    {{ searchQuery.trim() ? noticiasFiltradas.length : props.noticias.data.length }} 
                                    {{ (searchQuery.trim() ? noticiasFiltradas.length : props.noticias.data.length) === 1 ? 'notícia' : 'notícias' }}
                                    {{ searchQuery.trim() ? 'encontrada' : '' }}
                                </span>
                                <span>•</span>
                                <span>Atualizado hoje</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="space-y-2">
                                <Link 
                                    href="/noticias"
                                    class="inline-flex items-center bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Ver todas as notícias
                                </Link>
                                <div v-if="searchQuery.trim()" class="block">
                                    <button
                                        @click="searchQuery = ''"
                                        class="inline-flex items-center bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        Limpar pesquisa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas e Alertas (apenas na página geral) -->
                <div v-if="!isVisualizandoCategoria" class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                    <div class="flex items-center space-x-6 text-sm text-cinza">
                        <span class="font-medium">
                            {{ noticiasFiltradas.length }} 
                            {{ noticiasFiltradas.length === 1 ? 'notícia encontrada' : 'notícias encontradas' }}
                        </span>
                        <span>Atualizado hoje</span>
                        <span>Última atualização: 16:30</span>
                    </div>
                    
                    <!-- Alerta quando não há resultados -->
                    <div v-if="searchQuery.trim() && noticiasFiltradas.length === 0" class="text-sm text-amber-600 bg-amber-50 px-3 py-1 rounded-full">
                        Nenhuma notícia encontrada para "{{ searchQuery }}"
                    </div>
                </div>
            </div>
        </section>

        <!-- Espaço para Propaganda - Banner Principal -->
        <section class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
                    <p class="text-gray-400 text-xs">Banner Principal 970x250</p>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Conteúdo Principal -->
                <div class="lg:col-span-8">
                    <!-- Notícia Principal em Destaque (apenas se "Todas" estiver selecionado e sem busca) -->
                    <article
                        v-if="categoriaSelecionada === 'all' && !searchQuery.trim() && noticiaDestaque"
                        class="bg-white rounded-lg shadow-sm overflow-hidden mb-8"
                    >
                        <div class="relative">
                            <Link :href="route('noticias.show', noticiaDestaque.slug)">
                                <img
                                    v-if="noticiaDestaque.imagem_capa"
                                    :src="noticiaDestaque.imagem_capa?.caminho"
                                    :alt="noticiaDestaque.titulo"
                                    class="w-full h-80 object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
                                />
                            </Link>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

                            <!-- Conteúdo sobreposto -->
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span
                                        v-if="noticiaDestaque.categoria"
                                        class="bg-azul-lazuli text-white px-2 py-1 rounded text-xs font-medium"
                                    >
                                        {{ noticiaDestaque.categoria.nome }}
                                    </span>
                                    <span class="text-white text-sm">
                                        {{ formatDate(noticiaDestaque.publicada_em) }}
                                    </span>
                                </div>

                                <Link :href="route('noticias.show', noticiaDestaque.slug)">
                                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-3 leading-tight cursor-pointer hover:text-celeste transition-colors">
                                        {{ noticiaDestaque.titulo }}
                                    </h2>
                                </Link>

                                <p class="text-gray-200 text-sm mb-4 line-clamp-2">
                                    {{ noticiaDestaque.resumo }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <img
                                            :src="userImage"
                                            :alt="noticiaDestaque.autor?.nome"
                                            class="w-8 h-8 rounded-full border-2 border-white"
                                        />
                                        <div>
                                            <p class="text-white text-sm font-medium">
                                                {{ noticiaDestaque.autor?.nome }}
                                            </p>
                                            <p class="text-gray-300 text-xs">Jornalista</p>
                                        </div>
                                    </div>

                                    <Link
                                        :href="route('noticias.show', noticiaDestaque.slug)"
                                        class="bg-azul-oxford hover:bg-azul-noite text-white px-4 py-2 rounded-lg text-sm font-medium transition-all"
                                    >
                                        Ler Matéria
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Espaço para Propaganda - Meio do Conteúdo -->
                    <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-8">
                        <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
                        <p class="text-gray-400 text-xs">Banner Horizontal 728x90</p>
                    </div>

                    <!-- Grade de Notícias -->
                    <div v-if="noticiasFiltradas.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                        <article
                            v-for="noticia in noticiasFiltradas"
                            :key="noticia.id"
                            class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden"
                        >
                            <div class="relative overflow-hidden">
                                <Link :href="route('noticias.show', noticia.slug)">
                                    <img
                                        :src="noticia.imagem_capa?.caminho"
                                        :alt="noticia.titulo"
                                        class="w-full h-48 object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
                                    />
                                </Link>
                            </div>

                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span
                                        v-if="noticia.categoria"
                                        class="bg-azul-lazuli text-white px-2 py-1 rounded text-xs font-medium"
                                    >
                                        {{ noticia.categoria.nome }}
                                    </span>
                                    <span class="text-cinza text-xs">
                                        {{ formatDate(noticia.publicada_em) }}
                                    </span>
                                </div>

                                <Link :href="route('noticias.show', noticia.slug)">
                                    <h3 class="text-lg font-semibold text-azul-oxford mb-2 line-clamp-2 cursor-pointer hover:text-azul-lazuli transition-colors">
                                        {{ noticia.titulo }}
                                    </h3>
                                </Link>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ noticia.resumo }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <img
                                            :src="userImage"
                                            :alt="noticia.autor?.nome"
                                            class="w-6 h-6 rounded-full"
                                        />
                                        <span class="text-sm text-cinza">{{ noticia.autor?.nome }}</span>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <Link
                                            :href="route('noticias.show', noticia.slug)"
                                            class="text-azul-lazuli hover:text-azul-oxford text-sm font-medium"
                                        >
                                            Leia mais
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Estado vazio quando não há resultados -->
                    <div v-else class="text-center py-12">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold text-azul-oxford mb-2">
                            Nenhuma notícia encontrada
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Não encontramos notícias que correspondam aos seus critérios de pesquisa.
                        </p>
                        <button
                            @click="clearFilters"
                            class="bg-azul-oxford hover:bg-azul-noite text-white px-6 py-3 rounded-lg font-medium transition-all"
                        >
                            Limpar filtros e ver todas as notícias
                        </button>
                    </div>

                    <!-- Paginação Melhorada -->
                    <div v-if="noticiasFiltradas.length > 0" class="flex items-center justify-center mb-8">
                        <Pagination :links="noticias.links" class="bg-white rounded-lg shadow-sm p-4" />
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4">
                    <!-- Espaço para Propaganda - Sidebar Topo -->
                    <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-8">
                        <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
                        <p class="text-gray-400 text-xs">Banner Lateral 300x250</p>
                    </div>

                    <!-- Mais Lidas -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <h3 class="text-xl font-bold text-azul-oxford mb-4 flex items-center">
                            <span class="text-2xl mr-2">📈</span>
                            Mais Lidas
                        </h3>
                        <div class="space-y-4">
                            <article
                                v-for="(noticia, index) in maisLidas"
                                :key="noticia.id"
                                class="flex space-x-3 pb-4 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 -mx-2 px-2 py-2 rounded transition-colors cursor-pointer"
                            >
                                <div class="flex-shrink-0">
                                    <span class="text-2xl font-bold text-celeste">{{ index + 1 }}</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span
                                            v-if="noticia.categoria"
                                            class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded"
                                        >
                                            {{ noticia.categoria.nome }}
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-azul-oxford text-sm mb-1 line-clamp-2">
                                        {{ noticia.titulo }}
                                    </h4>
                                    <div class="flex items-center justify-between">
                                        <p class="text-cinza text-xs">
                                            {{ formatDate(noticia.publicada_em) }}
                                        </p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>

                    <!-- Filtro Rápido por Categoria -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <h3 class="text-xl font-bold text-azul-oxford mb-4 flex items-center">
                            <span class="text-2xl mr-2">📂</span>
                            Categorias
                        </h3>
                        <div class="space-y-3">
                            <button
                                @click="filterByCategory('all')"
                                :class="[
                                    'flex items-center justify-between w-full p-3 rounded-lg transition-colors text-left',
                                    categoriaSelecionada === 'all'
                                        ? 'bg-azul-oxford text-white'
                                        : 'hover:bg-gray-50',
                                ]"
                            >
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 rounded-full bg-azul-lazuli"></div>
                                    <span class="font-medium">Todas</span>
                                </div>
                                <span
                                    :class="[
                                        'text-sm px-2 py-1 rounded-full',
                                        categoriaSelecionada === 'all'
                                            ? 'bg-white/20 text-white'
                                            : 'bg-gray-100 text-gray-700'
                                    ]"
                                >
                                    {{ props.noticias.data.length }}
                                </span>
                            </button>
                            <button
                                v-for="categoria in categorias"
                                :key="categoria.id"
                                @click="filterByCategory(categoria.id)"
                                :class="[
                                    'flex items-center justify-between w-full p-3 rounded-lg transition-colors text-left',
                                    categoriaSelecionada === categoria.id
                                        ? 'bg-azul-oxford text-white'
                                        : 'hover:bg-gray-50',
                                ]"
                            >
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 rounded-full bg-azul-lazuli"></div>
                                    <span class="font-medium">{{ categoria.nome }}</span>
                                </div>
                                <span
                                    :class="[
                                        'text-sm px-2 py-1 rounded-full',
                                        categoriaSelecionada === categoria.id
                                            ? 'bg-white/20 text-white'
                                            : 'bg-gray-100 text-gray-700'
                                    ]"
                                >
                                    {{ categoria.count || 0 }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Espaço para Propaganda - Sidebar Rodapé -->
                    <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                        <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
                        <p class="text-gray-400 text-xs">Banner Vertical 300x600</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Personalização do select e inputs */
select:focus,
input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(30, 64, 175, 0.1);
}

/* Hover effects para cards */
.hover\:shadow-md:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Smooth transitions */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Personalização da paginação */
/* .pagination-wrapper {
    @apply flex items-center justify-center space-x-2;
} */

/* Efeitos de hover para botões */
button:hover {
    transform: translateY(-1px);
}

button:active {
    transform: translateY(0);
}

/* Estilo para breadcrumb links */
nav a:hover {
    text-decoration: underline;
}

/* Animação para o estado vazio */
.empty-state {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsividade melhorada para filtros */
/* @media (max-width: 640px) {
    .category-filters {
        @apply flex-col space-y-2;
    }
    
    .category-filters button {
        @apply w-full justify-center;
    }
} */

/* Estilo para campo de pesquisa com foco */
input[type="text"]:focus {
    border-color: #1e40af;
    box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
}

/* Melhor contraste para textos */
.text-cinza {
    color: #6b7280;
}

/* Efeito de elevação para cards ativos */
.category-button-active {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transform: translateY(-1px);
}
</style>