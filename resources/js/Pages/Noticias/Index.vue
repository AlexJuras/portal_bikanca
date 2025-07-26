<script setup>
import { ref, onMounted, computed } from "vue";
import Principal from "@/Layouts/Principal.vue";
import { Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

// Importar imagem do usuário
const userImage = '/images/user-placeholder.png';

defineOptions({ layout: Principal });

// Dados da página
const pageData = ref({
    name: "Todas as Notícias",
    description:
        "Acompanhe todas as principais notícias do Brasil e do mundo em tempo real, organizadas por relevância e data de publicação.",
    icon: "📰",
});

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
});

// Controles de filtro e ordenação
const categoriaSelecionada = ref("all");
const sortBy = ref("date"); // 'date' ou 'views'
const currentPage = ref(1);
const newsPerPage = 9;
const totalNews = ref(156); // Total simulado

// Computed para filtrar notícias
const noticiasFiltradas = computed(() => {
    let filtro = props.noticias.data;

    // Filtrar por categoria
    if (categoriaSelecionada.value !== "all") {
        filtro = filtro.filter(
            noticia => noticia.categoria_id === categoriaSelecionada.value
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

// Computed para paginação
// const noticiasPaginadas = computed(() => {
//     const start = (currentPage.value - 1) * newsPerPage;
//     const end = start + newsPerPage;
//     return noticiasFiltradas.value.slice(start, end);
// });

// const totalPages = computed(() => {
//     return Math.ceil(noticiasFiltradas.value.length / newsPerPage);
// });

// Função para navegar páginas
const goToPage = (page) => {
    currentPage.value = page;
    window.scrollTo({ top: 0, behavior: "smooth" });
};

// Função para filtrar por categoria
const filterByCategory = (category) => {
    categoriaSelecionada.value = category;
    currentPage.value = 1;
};

// Função para alterar ordenação
const changeSortBy = (sort) => {
    sortBy.value = sort;
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

onMounted(() => {
    updateCategoryCounts();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumb -->
                <nav class="text-sm text-cinza mb-4">
                    <span>Home</span>
                    <span class="mx-2">></span>
                    <span class="text-azul-oxford">{{ pageData.name }}</span>
                </nav>

                <!-- Título da Página -->
                <div class="flex items-center space-x-4 mb-6">
                    <div class="text-4xl">{{ pageData.icon }}</div>
                    <div>
                        <h1 class="text-4xl font-bold text-azul-oxford">
                            {{ pageData.name }}
                        </h1>
                        <p class="text-gray-600 mt-2">
                            {{ pageData.description }}
                        </p>
                    </div>
                </div>

                <!-- Filtros e Controles -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-4"
                >
                    <!-- Filtros de Categoria -->
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="categoria in categorias"
                            :key="categoria.id"
                            @click="filterByCategory(categoria.id)"
                            :class="[
                                'px-4 py-2 rounded-full text-sm font-medium transition-all',
                                categoriaSelecionada === categoria.id
                                    ? 'bg-azul-lazuli text-white'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            ]"
                        >
                            {{ categoria.nome }}
                            <span class="ml-1 text-xs opacity-75"
                                >({{ categoria.count || 0 }})</span
                            >
                        </button>
                    </div>

                    <!-- Ordenação -->
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Ordenar por:</span>
                        <select
                            v-model="sortBy"
                            @change="changeSortBy(sortBy)"
                            class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-azul-oxford"
                        >
                            <option value="date">Mais recentes</option>
                            <option value="views">Mais lidas</option>
                        </select>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="flex items-center space-x-6 text-sm text-cinza">
                    <span
                        >{{ noticiasFiltradas.length }} notícias
                        encontradas</span
                    >
                    <span>Atualizado hoje</span>
                    <span>Última atualização: 16:30</span>
                </div>
            </div>
        </section>

        <!-- Espaço para Propaganda - Banner Principal -->
        <section class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div
                    class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center"
                >
                    <p class="text-gray-500 text-sm mb-2">
                        ESPAÇO PUBLICITÁRIO
                    </p>
                    <p class="text-gray-400 text-xs">
                        Banner Principal 970x250
                    </p>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Conteúdo Principal -->
                <div class="lg:col-span-8">
                    <!-- Notícia Principal em Destaque (apenas se "Todas" estiver selecionado) -->
                    <article
                        v-if="categoriaSelecionada === 'all' && noticiaDestaque"
                        class="bg-white rounded-lg shadow-sm overflow-hidden mb-8"
                    >
                        <div class="relative">
                            <img
                                v-if="noticiaDestaque.imagem_capa"
                                :src="noticiaDestaque.imagem_capa?.caminho"
                                :alt="noticiaDestaque.titulo"
                                class="w-full h-80 object-cover"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
                            ></div>

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

                                <h2
                                    class="text-2xl md:text-3xl font-bold text-white mb-3 leading-tight"
                                >
                                    {{ noticiaDestaque.titulo }}
                                </h2>

                                <p
                                    class="text-gray-200 text-sm mb-4 line-clamp-2"
                                >
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
                                            <p
                                                class="text-white text-sm font-medium"
                                            >
                                                {{ noticiaDestaque.autor?.nome }}
                                            </p>
                                            <p class="text-gray-300 text-xs">
                                                Jornalista
                                            </p>
                                        </div>
                                    </div>

                                    <Link
                                        :href="route('noticias.show', noticiaDestaque.id)"
                                        class="bg-azul-oxford hover:bg-azul-noite text-white px-4 py-2 rounded-lg text-sm font-medium transition-all"
                                    >
                                        Ler Matéria
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Espaço para Propaganda - Meio do Conteúdo -->
                    <div
                        class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-8"
                    >
                        <p class="text-gray-500 text-sm mb-2">
                            ESPAÇO PUBLICITÁRIO
                        </p>
                        <p class="text-gray-400 text-xs">
                            Banner Horizontal 728x90
                        </p>
                    </div>

                    <!-- Grade de Notícias -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8"
                    >
                        <article
                            v-for="noticia in noticias.data"
                            :key="noticia.id"
                            class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden"
                        >
                            <div class="relative">
                                <img
                                    :src="noticia.imagem_capa?.caminho"
                                    :alt="noticia.titulo"
                                    class="w-full h-48 object-cover"
                                />
                                <!-- <div
                                    v-if="noticia.isBreaking"
                                    class="absolute top-2 left-2"
                                >
                                    <span
                                        class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold animate-pulse"
                                    >
                                        🔴 URGENTE
                                    </span>
                                </div> -->
                            </div>

                            <div class="p-6">
                                <div
                                    class="flex items-center justify-between mb-3"
                                >
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

                                <h3
                                    class="text-lg font-semibold text-azul-oxford mb-2 line-clamp-2"
                                >
                                    {{ noticia.titulo }}
                                </h3>

                                <p
                                    class="text-gray-600 text-sm mb-4 line-clamp-3"
                                >
                                    {{ noticia.resumo }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <img
                                            :src="userImage"
                                            :alt="noticia.autor?.nome"
                                            class="w-6 h-6 rounded-full"
                                        />
                                        <span class="text-sm text-cinza">{{
                                            noticia.autor?.nome
                                        }}</span>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <Link
                                            :href="route('noticias.show', noticia.id)"
                                            class="text-azul-lazuli hover:text-azul-oxford text-sm font-medium"
                                        >
                                            Leia mais
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Paginação -->
                    <div
                        class="flex items-center justify-center space-x-2 mb-8"
                         > 1"
                    >
                        <Pagination :links="noticias.links" class="mt-6"/>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4">
                    <!-- Espaço para Propaganda - Sidebar Topo -->
                    <div
                        class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-8"
                    >
                        <p class="text-gray-500 text-sm mb-2">
                            ESPAÇO PUBLICITÁRIO
                        </p>
                        <p class="text-gray-400 text-xs">
                            Banner Lateral 300x250
                        </p>
                    </div>

                    <!-- Mais Lidas -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <h3
                            class="text-xl font-bold text-azul-oxford mb-4 flex items-center"
                        >
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
                                    <span
                                        class="text-2xl font-bold text-celeste"
                                        >{{ index + 1 }}</span
                                    >
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="flex items-center space-x-2 mb-1"
                                    >
                                        <span
                                            v-if="noticia.categoria"
                                            class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded"
                                        >
                                            {{ noticia.categoria.nome }}
                                        </span>
                                    </div>
                                    <h4
                                        class="font-semibold text-azul-oxford text-sm mb-1 line-clamp-2"
                                    >
                                        {{ noticia.titulo }}
                                    </h4>
                                    <div
                                        class="flex items-center justify-between"
                                    >
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
                        <h3
                            class="text-xl font-bold text-azul-oxford mb-4 flex items-center"
                        >
                            <span class="text-2xl mr-2">📂</span>
                            Categorias
                        </h3>
                        <div class="space-y-3">
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
                                    <div
                                        class="w-3 h-3 rounded-full bg-azul-lazuli"
                                    ></div>
                                    <span class="font-medium">{{
                                        categoria.nome
                                    }}</span>
                                </div>
                                <span
                                    class="text-sm opacity-75 bg-gray-100 text-gray-700 px-2 py-1 rounded-full"
                                >
                                    {{ categoria.count || 0 }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="bg-azul-oxford rounded-lg p-6 text-white mb-8">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <span class="text-2xl mr-2">📧</span>
                            Newsletter
                        </h3>
                        <p class="text-sm mb-4">
                            Receba todas as principais notícias em seu email
                        </p>
                        <div class="space-y-3">
                            <input
                                type="email"
                                placeholder="Seu email"
                                class="w-full px-3 py-2 rounded text-azul-oxford"
                            />
                            <button
                                class="w-full bg-celeste hover:bg-azul-lazuli text-azul-oxford font-medium py-2 rounded transition-all"
                            >
                                Inscrever-se
                            </button>
                        </div>
                    </div>

                    <!-- Últimas da Política -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                        <h3
                            class="text-xl font-bold text-azul-oxford mb-4 flex items-center"
                        >
                            <span class="text-2xl mr-2">🏛️</span>
                            Últimas da Política
                        </h3>
                        <div class="space-y-3">
                            <article
                                class="pb-3 border-b border-gray-200 last:border-b-0"
                            >
                                <h4
                                    class="font-semibold text-sm text-azul-oxford mb-1 line-clamp-2"
                                >
                                    Senado aprova projeto de modernização
                                    eleitoral
                                </h4>
                                <p class="text-xs text-cinza">Há 2 horas</p>
                            </article>
                            <article
                                class="pb-3 border-b border-gray-200 last:border-b-0"
                            >
                                <h4
                                    class="font-semibold text-sm text-azul-oxford mb-1 line-clamp-2"
                                >
                                    Ministros se reúnem para discutir orçamento
                                    2025
                                </h4>
                                <p class="text-xs text-cinza">Há 4 horas</p>
                            </article>
                            <article
                                class="pb-3 border-b border-gray-200 last:border-b-0"
                            >
                                <h4
                                    class="font-semibold text-sm text-azul-oxford mb-1 line-clamp-2"
                                >
                                    Governadores debatem segurança pública
                                </h4>
                                <p class="text-xs text-cinza">Há 6 horas</p>
                            </article>
                        </div>
                        <button
                            class="text-azul-lazuli hover:text-azul-oxford text-sm font-medium mt-3"
                        >
                            Ver todas as notícias de política →
                        </button>
                    </div>

                    <!-- Espaço para Propaganda - Sidebar Rodapé -->
                    <div
                        class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center"
                    >
                        <p class="text-gray-500 text-sm mb-2">
                            ESPAÇO PUBLICITÁRIO
                        </p>
                        <p class="text-gray-400 text-xs">
                            Banner Vertical 300x600
                        </p>
                    </div>
                </div>
            </div>

            <!-- Seção de Notícias por Categoria (quando não é "Todas") -->
            <div v-if="categoriaSelecionada !== 'all'" class="mt-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-azul-oxford">
                        Todas as notícias de {{ categorias.find(cat => cat.id === categoriaSelecionada)?.nome }}
                    </h2>
                    <button
                        @click="filterByCategory('all')"
                        class="text-azul-lazuli hover:text-azul-oxford text-sm font-medium"
                    >
                        Ver todas as categorias
                    </button>
                </div>

                <!-- Estatísticas da categoria selecionada -->
                <div
                    class="bg-white rounded-lg p-4 mb-6 border border-gray-200"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-4 h-4 rounded-full"
                                :class="{
                                    'bg-azul-lazuli': true
                                }"
                            ></div>
                            <span class="font-medium text-azul-oxford">{{
                                categorias.find(cat => cat.id === categoriaSelecionada)?.nome || 'Todas'
                            }}</span>
                            <span class="text-sm text-cinza"
                                >{{ noticiasFiltradas.length }} notícias</span
                            >
                        </div>
                        <div class="text-sm text-cinza">
                            Ordenado por:
                            {{
                                sortBy === "date"
                                    ? "Mais recentes"
                                    : "Mais lidas"
                            }}
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
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Personalização do select */
select:focus {
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
</style>
