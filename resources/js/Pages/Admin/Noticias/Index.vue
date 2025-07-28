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
                <i class="fas fa-plus mr-2"></i>
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
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <button
                        v-if="search"
                        @click="clearSearch"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
                    >
                        <i class="fas fa-times mr-2"></i>
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
                                    <div class="flex-shrink-0 h-12 w-12 mr-3">
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
                                        <i class="fas fa-eye"></i>
                                    </Link>

                                    <!-- Editar -->
                                    <Link
                                        :href="
                                            route('noticias.edit', noticia.id)
                                        "
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors p-1"
                                        title="Editar"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>

                                    <!-- Excluir -->
                                    <button
                                        @click="confirmarExclusao(noticia)"
                                        class="text-red-600 hover:text-red-800 transition-colors p-1"
                                        title="Excluir"
                                    >
                                        <i class="fas fa-trash"></i>
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
                                        <i class="fas fa-eye"></i>
                                    </Link>
                                    <Link
                                        :href="
                                            route('noticias.edit', noticia.id)
                                        "
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors"
                                        title="Editar"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        @click="confirmarExclusao(noticia)"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Excluir"
                                    >
                                        <i class="fas fa-trash"></i>
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
                    <i
                        class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"
                    ></i>
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
