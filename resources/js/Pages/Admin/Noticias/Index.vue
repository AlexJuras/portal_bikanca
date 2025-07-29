<template>
    <div class="container mx-auto p-4">
        <!-- Cabeçalho -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6"
        >
            <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">
                Gerenciar Notícias
            </h1>
            <Link
                :href="route('noticias.create')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition-colors flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nova Notícia
            </Link>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label
                        for="search"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Pesquisar notícias
                    </label>
                    <div class="relative">
                        <input
                            id="search"
                            v-model="search"
                            type="text"
                            placeholder="Digite o título da notícia..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            @input="debouncedSearch"
                        />
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
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
                    <h3 class="text-2xl font-bold text-blue-600">
                        {{ totalNoticias }}
                    </h3>
                    <p class="text-gray-600">Total de Notícias</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-green-600">
                        {{ noticiasPublicadas }}
                    </h3>
                    <p class="text-gray-600">Publicadas</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-yellow-600">
                        {{ noticiasRascunho }}
                    </h3>
                    <p class="text-gray-600">Rascunhos</p>
                </div>
            </div>
        </div>

        <!-- Lista de Notícias -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Versão Desktop -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Notícia
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Autor
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Categoria
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Data
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32"
                            >
                                Ações
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
                                    <div class="flex-shrink-0 h-12 w-12  mr-3">
                                        <img
                                            :src="
                                                noticia.imagemCapa?.caminho ||
                                                '/logo.png'
                                            "
                                            :alt="noticia.titulo"
                                            class="h-12 w-12 object-cover rounded-lg"
                                        />
                                    </div>
                                    <div class="max-w-xs">
                                        <h3
                                            class="text-sm font-medium text-gray-900 line-clamp-2"
                                        >
                                            {{ noticia.titulo }}
                                        </h3>
                                        <p
                                            class="text-sm text-gray-500 line-clamp-1 mt-1"
                                        >
                                            {{ noticia.resumo }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ noticia.autor?.nome }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="
                                        getCategoriaClass(
                                            noticia.categoria?.nome
                                        )
                                    "
                                >
                                    {{ noticia.categoria?.nome }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="getStatusClass(noticia.status)"
                                >
                                    {{ getStatusText(noticia.status) }}
                                </span>
                            </td>
                            <td
                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ formatDate(noticia.updated_at) }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex justify-center space-x-2">
                                    <!-- Visualizar -->
                                    <Link
                                        :href="
                                            route('noticias.show', noticia.slug)
                                        "
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
                                        :href="
                                            route('noticias.edit', noticia.id)
                                        "
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors p-1"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </Link>

                                    <!-- Excluir -->
                                    <button
                                        @click="confirmarExclusao(noticia)"
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
                <div
                    v-for="noticia in noticias.data"
                    :key="noticia.id"
                    class="border-b border-gray-200 p-4"
                >
                    <div class="flex items-start space-x-3">
                        <img
                            :src="noticia.imagemCapa?.caminho || '/logo.png'"
                            :alt="noticia.titulo"
                            class="h-16 w-16 object-cover rounded-lg flex-shrink-0"
                        />
                        <div class="flex-1 min-w-0">
                            <h3
                                class="text-sm font-medium text-gray-900 line-clamp-2"
                            >
                                {{ noticia.titulo }}
                            </h3>
                            <p class="text-sm text-gray-500 line-clamp-1 mt-1">
                                {{ noticia.resumo }}
                            </p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                    :class="
                                        getCategoriaClass(
                                            noticia.categoria?.nome
                                        )
                                    "
                                >
                                    {{ noticia.categoria?.nome }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                    :class="getStatusClass(noticia.status)"
                                >
                                    {{ getStatusText(noticia.status) }}
                                </span>
                            </div>
                            <div class="mt-2 flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    {{ noticia.autor?.nome }} •
                                    {{ formatDate(noticia.updated_at) }}
                                </div>
                                <div class="flex space-x-3">
                                    <Link
                                        :href="
                                            route('noticias.show', noticia.slug)
                                        "
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Visualizar"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </Link>
                                    <Link
                                        :href="
                                            route('noticias.edit', noticia.id)
                                        "
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors"
                                        title="Editar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </Link>
                                    <button
                                        @click="confirmarExclusao(noticia)"
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

            <!-- Paginação -->
            <div
                v-if="noticias.links && noticias.links.length > 3"
                class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
            >
                <Pagination :links="noticias.links" />
            </div>
        </div>

        <!-- Modal de Confirmação de Exclusão -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">
                        Confirmar Exclusão
                    </h3>
                </div>

                <p class="text-gray-600 mb-6">
                    Tem certeza que deseja excluir a notícia
                    <strong>"{{ noticiaParaExcluir?.titulo }}"</strong>? Esta
                    ação não pode ser desfeita.
                </p>

                <div class="flex justify-end space-x-3">
                    <button
                        @click="cancelarExclusao"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="excluirNoticia"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                        :disabled="excluindo"
                    >
                        <span v-if="excluindo">Excluindo...</span>
                        <span v-else>Excluir</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import Admin from "@/Layouts/Admin.vue";
import Pagination from "@/Components/Pagination.vue";
import { debounce } from "lodash";

defineOptions({ layout: Admin });

const props = defineProps({
    noticias: Object,
    filters: Object,
});

// Estados da pesquisa
const search = ref(props.filters?.search || "");

// Estados do modal
const showDeleteModal = ref(false);
const noticiaParaExcluir = ref(null);
const excluindo = ref(false);

// Pesquisa com debounce
const debouncedSearch = debounce(() => {
    router.get(
        route("admin.noticias"),
        {
            search: search.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}, 500);

const clearSearch = () => {
    search.value = "";
    router.get(
        route("admin.noticias"),
        {},
        {
            preserveState: true,
            replace: true,
        }
    );
};

// Estatísticas computadas
const totalNoticias = computed(
    () => props.noticias.total || props.noticias.data.length
);
const noticiasPublicadas = computed(() => {
    return props.noticias.data.filter(
        (noticia) => noticia.status === "publicada"
    ).length;
});
const noticiasRascunho = computed(() => {
    return props.noticias.data.filter(
        (noticia) => noticia.status === "rascunho"
    ).length;
});

// Funções de formatação
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getStatusText = (status) => {
    const statusMap = {
        publicada: "Publicada",
        rascunho: "Rascunho",
        arquivada: "Arquivada",
    };
    return statusMap[status] || status;
};

const getStatusClass = (status) => {
    const classMap = {
        publicada: "bg-green-100 text-green-800",
        rascunho: "bg-yellow-100 text-yellow-800",
        arquivada: "bg-gray-100 text-gray-800",
    };
    return classMap[status] || "bg-gray-100 text-gray-800";
};

const getCategoriaClass = (categoria) => {
    // Cores alternadas para diferentes categorias
    const cores = [
        "bg-blue-100 text-blue-800",
        "bg-purple-100 text-purple-800",
        "bg-pink-100 text-pink-800",
        "bg-indigo-100 text-indigo-800",
        "bg-teal-100 text-teal-800",
    ];
    const hash = categoria
        ? categoria.split("").reduce((a, b) => a + b.charCodeAt(0), 0)
        : 0;
    return cores[hash % cores.length];
};

// Funções de exclusão
const confirmarExclusao = (noticia) => {
    noticiaParaExcluir.value = noticia;
    showDeleteModal.value = true;
};

const cancelarExclusao = () => {
    showDeleteModal.value = false;
    noticiaParaExcluir.value = null;
};

const excluirNoticia = () => {
    if (!noticiaParaExcluir.value) return;

    excluindo.value = true;

    router.delete(route("noticias.destroy", noticiaParaExcluir.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            noticiaParaExcluir.value = null;
        },
        onError: (errors) => {
            console.error("Erro ao excluir notícia:", errors);
        },
        onFinish: () => {
            excluindo.value = false;
        },
    });
};
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
