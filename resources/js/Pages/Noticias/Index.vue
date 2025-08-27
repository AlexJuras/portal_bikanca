<script setup>
import { ref, onMounted, computed, watch } from "vue";
import Principal from "@/Layouts/Principal.vue";
import { Link, router } from "@inertiajs/vue3";
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

// Função para incrementar cliques
const incrementarClique = async (noticia) => {
    try {
        // Usar slug se disponível, senão usar ID
        const identificador = noticia.slug || noticia.id;
        await fetch(route('noticias.increment-clique', identificador), {
            method: 'GET'
        });
    } catch (error) {
        console.log('Erro ao incrementar clique:', error);
    }
};

// Função para lidar com cliques em notícias
const handleNoticiaClick = (noticia) => {
    incrementarClique(noticia);
};
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

// Função para navegar para categoria
const navigateToCategory = (categorySlug) => {
    router.visit(`/noticias/categoria/${categorySlug}`);
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
            <!-- Notícia Principal em Destaque (apenas se "Todas" estiver selecionado e sem busca) -->
            <section
                v-if="categoriaSelecionada === 'all' && !searchQuery.trim() && noticiaDestaque"
                class="mb-12"
            >
                <article class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Imagem da notícia destaque -->
                        <div class="relative">
                            <Link :href="route('noticias.show', noticiaDestaque.slug)" @click="handleNoticiaClick(noticiaDestaque)">
                                <img
                                    v-if="noticiaDestaque.imagem_capa"
                                    :src="noticiaDestaque.imagem_capa?.caminho"
                                    :alt="noticiaDestaque.titulo"
                                    class="w-full h-80 lg:h-96 object-cover cursor-pointer hover:scale-105 transition-transform duration-300 rounded-lg"
                                />
                            </Link>
                            <div class="absolute top-4 left-4">
                                <span
                                    v-if="noticiaDestaque.categoria"
                                    class="bg-azul-lazuli text-white px-3 py-1 rounded-full text-sm font-medium"
                                >
                                    {{ noticiaDestaque.categoria.nome }}
                                </span>
                            </div>
                        </div>

                        <!-- Conteúdo da notícia destaque -->
                        <div class="p-8 flex flex-col justify-center">
                            <div class="mb-4">
                                <span class="text-azul-lazuli text-sm font-medium">
                                    {{ formatDate(noticiaDestaque.publicada_em) }}
                                </span>
                            </div>

                            <Link :href="route('noticias.show', noticiaDestaque.slug)" @click="handleNoticiaClick(noticiaDestaque)">
                                <h1 class="text-3xl lg:text-4xl font-bold text-azul-oxford mb-4 leading-tight cursor-pointer hover:text-azul-lazuli transition-colors">
                                    {{ noticiaDestaque.titulo }}
                                </h1>
                            </Link>

                            <p class="text-gray-600 text-lg mb-6 line-clamp-3">
                                {{ noticiaDestaque.resumo }}
                            </p>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img
                                        :src="noticiaDestaque.autor?.foto ? `/storage/${noticiaDestaque.autor.foto}` : userImage"
                                        :alt="noticiaDestaque.autor?.nome"
                                        class="w-10 h-10 rounded-full border-2 border-gray-200 object-cover"
                                        @error="$event.target.src = userImage"
                                    />
                                    <div>
                                        <p class="text-azul-oxford text-sm font-medium">
                                            {{ noticiaDestaque.autor?.nome }}
                                        </p>
                                        <p class="text-gray-500 text-xs">Jornalista</p>
                                    </div>
                                </div>

                                <Link
                                    :href="route('noticias.show', noticiaDestaque.slug)"
                                    class="bg-azul-oxford hover:bg-azul-noite text-white px-6 py-3 rounded-lg text-sm font-medium transition-all flex items-center space-x-2"
                                >
                                    <span>Ler Matéria</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </article>
            </section>

            <!-- Grade Principal de Notícias -->
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                <!-- Conteúdo Principal - 3 colunas -->
                <div class="xl:col-span-3">
                    <!-- Espaço para Banner Horizontal -->
                    <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-8">
                        <p class="text-gray-500 text-sm mb-2">ESPAÇO PUBLICITÁRIO</p>
                        <p class="text-gray-400 text-xs">Banner Horizontal 728x90</p>
                    </div>

                    <!-- Grade de Notícias - Layout Masonry -->
                    <div v-if="noticiasFiltradas.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <article
                            v-for="(noticia, index) in noticiasFiltradas"
                            :key="noticia.id"
                            :class="[
                                'bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group',
                                // Destaque para a primeira notícia se não houver destaque principal
                                index === 0 && (categoriaSelecionada !== 'all' || searchQuery.trim()) ? 'md:col-span-2 lg:col-span-2' : ''
                            ]"
                        >
                            <div class="relative overflow-hidden">
                                <Link :href="route('noticias.show', noticia.slug)" @click="handleNoticiaClick(noticia)">
                                    <img
                                        :src="noticia.imagem_capa?.caminho"
                                        :alt="noticia.titulo"
                                        :class="[
                                            'w-full object-cover cursor-pointer group-hover:scale-105 transition-transform duration-300',
                                            index === 0 && (categoriaSelecionada !== 'all' || searchQuery.trim()) ? 'h-64' : 'h-48'
                                        ]"
                                    />
                                </Link>
                                <div class="absolute top-3 left-3">
                                    <span
                                        v-if="noticia.categoria"
                                        class="bg-azul-lazuli text-white px-2 py-1 rounded-full text-xs font-medium"
                                    >
                                        {{ noticia.categoria.nome }}
                                    </span>
                                </div>
                            </div>

                            <div :class="[
                                'p-6',
                                index === 0 && (categoriaSelecionada !== 'all' || searchQuery.trim()) ? 'p-8' : ''
                            ]">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-azul-lazuli text-xs font-medium">
                                        {{ formatDate(noticia.publicada_em) }}
                                    </span>
                                </div>

                                <Link :href="route('noticias.show', noticia.slug)" @click="handleNoticiaClick(noticia)">
                                    <h3 :class="[
                                        'font-semibold text-azul-oxford mb-3 cursor-pointer group-hover:text-azul-lazuli transition-colors line-clamp-2',
                                        index === 0 && (categoriaSelecionada !== 'all' || searchQuery.trim()) ? 'text-xl lg:text-2xl mb-4' : 'text-lg'
                                    ]">
                                        {{ noticia.titulo }}
                                    </h3>
                                </Link>

                                <p :class="[
                                    'text-gray-600 mb-4 line-clamp-3',
                                    index === 0 && (categoriaSelecionada !== 'all' || searchQuery.trim()) ? 'text-base' : 'text-sm'
                                ]">
                                    {{ noticia.resumo }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <img
                                            :src="noticia.autor?.foto ? `/storage/${noticia.autor.foto}` : userImage"
                                            :alt="noticia.autor?.nome"
                                            class="w-6 h-6 rounded-full object-cover"
                                            @error="$event.target.src = userImage"
                                        />
                                        <span class="text-sm text-gray-500">{{ noticia.autor?.nome }}</span>
                                    </div>

                                    <Link
                                        :href="route('noticias.show', noticia.slug)"
                                        class="text-azul-lazuli hover:text-azul-oxford text-sm font-medium flex items-center space-x-1 group-hover:translate-x-1 transition-transform"
                                    >
                                        <span>Leia mais</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Estado vazio quando não há resultados -->
                    <div v-else class="text-center py-16">
                        <div class="text-8xl mb-6">🔍</div>
                        <h3 class="text-2xl font-bold text-azul-oxford mb-4">
                            Nenhuma notícia encontrada
                        </h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Não encontramos notícias que correspondam aos seus critérios de pesquisa. Tente outras palavras-chave ou limpe os filtros.
                        </p>
                        <button
                            @click="clearFilters"
                            class="bg-azul-oxford hover:bg-azul-noite text-white px-8 py-3 rounded-lg font-medium transition-all inline-flex items-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Limpar filtros</span>
                        </button>
                    </div>

                    <!-- Paginação -->
                    <div v-if="noticiasFiltradas.length > 0" class="mt-12">
                        <Pagination :links="noticias.links" class="bg-white rounded-lg shadow-sm p-6" />
                    </div>
                </div>

                <!-- Sidebar Otimizada -->
                <div class="xl:col-span-1">
                    <!-- Banner Lateral Topo -->
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-8 sticky top-8">
                        <div class="text-4xl mb-3">📢</div>
                        <p class="text-gray-600 text-sm font-medium mb-2">ESPAÇO PUBLICITÁRIO</p>
                        <p class="text-gray-500 text-xs">Banner Lateral 300x250</p>
                        <div class="mt-4 text-xs text-gray-400">
                            Anuncie aqui e alcance milhares de leitores
                        </div>
                    </div>

                    <!-- Categorias Rápidas (apenas na página geral) -->
                    <div v-if="!isVisualizandoCategoria" class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <h3 class="text-lg font-bold text-azul-oxford mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Navegue por Categoria
                        </h3>
                        <div class="space-y-2">
                            <button
                                v-for="categoria in categorias.slice(0, 6)"
                                :key="categoria.id"
                                @click="navigateToCategory(categoria.slug)"
                                class="w-full text-left px-3 py-2 rounded-lg hover:bg-azul-oxford hover:text-white transition-colors text-sm border border-gray-200 hover:border-azul-oxford"
                            >
                                <div class="flex items-center justify-between">
                                    <span>{{ categoria.nome }}</span>
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">
                                        {{ categoria.noticias_count || 0 }}
                                    </span>
                                </div>
                            </button>
                        </div>
                        <Link 
                            href="/noticias"
                            class="block mt-4 text-center text-azul-lazuli hover:text-azul-oxford text-sm font-medium"
                        >
                            Ver todas as categorias →
                        </Link>
                    </div>

                    <!-- Banner Lateral Rodapé -->
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="text-4xl mb-3">🎯</div>
                        <p class="text-gray-600 text-sm font-medium mb-2">ESPAÇO PUBLICITÁRIO</p>
                        <p class="text-gray-500 text-xs">Banner Vertical 300x600</p>
                        <div class="mt-4 text-xs text-gray-400">
                            Ideal para campanhas de longo prazo
                        </div>
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

/* Hover effects melhorados */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\:text-azul-lazuli {
    color: #007fff;
}

.group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
}

/* Sticky sidebar */
.sticky {
    position: sticky;
    top: 2rem;
}

/* Grid responsivo melhorado */
@media (min-width: 768px) {
    .md\:col-span-2 {
        grid-column: span 2 / span 2;
    }
}

@media (min-width: 1024px) {
    .lg\:col-span-2 {
        grid-column: span 2 / span 2;
    }
    
    .lg\:col-span-3 {
        grid-column: span 3 / span 3;
    }
}

/* Hover effects para cards */
.hover\:shadow-lg:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Banner publicitário */
.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

/* Melhor contraste em botões */
button:focus,
a:focus {
    outline: 2px solid #007fff;
    outline-offset: 2px;
}
</style>