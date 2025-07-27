<script setup>
import { ref, reactive } from "vue";
import Admin from "@/Layouts/Admin.vue";
import { Link, router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

defineOptions({ layout: Admin });

const props = defineProps({
    autores: {
        type: Object,
        required: true,
    },
    estatisticas: {
        type: Object,
        required: true,
    },
});

// Estados reativos
const filtros = reactive({
    busca: "",
    status: "",
    ordenacao: "nome_asc",
});

const autorParaExcluir = ref(null);
const excluindoAutor = ref(false);

// Funções de formatação
const formatDate = (date) => {
    if (!date) return "N/A";
    return new Date(date).toLocaleDateString("pt-BR", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

const formatNumber = (number) => {
    if (!number) return "0";
    return new Intl.NumberFormat("pt-BR").format(number);
};

// Funções de filtro e busca
const buscarAutores = () => {
    router.get("/autores", filtros, {
        preserveState: true,
        replace: true,
    });
};

const filtrarAutores = () => {
    router.get("/autores", filtros, {
        preserveState: true,
        replace: true,
    });
};

const ordenarAutores = () => {
    router.get("/autores", filtros, {
        preserveState: true,
        replace: true,
    });
};

// Funções de exclusão
const confirmarExclusao = (autor) => {
    autorParaExcluir.value = autor;
};

const cancelarExclusao = () => {
    autorParaExcluir.value = null;
    excluindoAutor.value = false;
};

const excluirAutor = () => {
    if (!autorParaExcluir.value) return;

    excluindoAutor.value = true;

    router.delete(`/autores/${autorParaExcluir.value.id}`, {
        onSuccess: () => {
            cancelarExclusao();
        },
        onError: () => {
            excluindoAutor.value = false;
        },
        onFinish: () => {
            excluindoAutor.value = false;
        },
    });
};
</script>

<style scoped>
.text-azul-oxford {
    color: #1e3a8a;
}

.text-azul-lazuli {
    color: #007fff;
}

.bg-azul-lazuli {
    background-color: #007fff;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.focus\:ring-azul-lazuli:focus {
    --tw-ring-color: #007fff;
}
</style>
<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumb -->
                <nav class="text-sm mb-6" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li>
                            <Link
                                href="/"
                                class="text-azul-lazuli hover:text-azul-oxford transition-colors flex items-center"
                            >
                                <svg
                                    class="w-4 h-4 mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                                    />
                                </svg>
                                Home
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg
                                class="w-4 h-4 text-gray-400 mx-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span class="text-azul-oxford font-medium"
                                >Autores</span
                            >
                        </li>
                    </ol>
                </nav>

                <!-- Cabeçalho da seção -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <h1 class="text-3xl font-bold text-azul-oxford">
                            Autores
                        </h1>
                        <p class="text-gray-600 mt-2">
                            Gerencie os autores do Portal Bikanca
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <Link
                            href="/autores/create"
                            class="bg-azul-lazuli hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            Novo Autor
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Estatísticas Gerais -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-500 rounded-lg p-3">
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM5 8a2 2 0 012-2h6a2 2 0 012 2v9a2 2 0 01-2 2H7a2 2 0 01-2-2V8z"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Total de Autores
                            </p>
                            <p class="text-2xl font-bold text-azul-oxford">
                                <!-- {{ estatisticas.total_autores }} -->
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="bg-green-500 rounded-lg p-3">
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Autores Ativos
                            </p>
                            <p class="text-2xl font-bold text-green-600">
                                <!-- {{ estatisticas.autores_ativos }} -->
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-500 rounded-lg p-3">
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Total de Notícias
                            </p>
                            <p class="text-2xl font-bold text-purple-600">
                                <!-- {{ estatisticas.total_noticias }} -->
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="bg-orange-500 rounded-lg p-3">
                            <svg
                                class="w-6 h-6 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path
                                    fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Visualizações
                            </p>
                            <p class="text-2xl font-bold text-orange-600">
                                {{
                                    formatNumber(
                                        // estatisticas.total_visualizacoes
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros e Busca -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4"
                    >
                        <!-- Busca -->
                        <div class="relative">
                            <input
                                v-model="filtros.busca"
                                type="text"
                                placeholder="Buscar autores..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent"
                                @input="buscarAutores"
                            />
                            <svg
                                class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>

                        <!-- Filtro por Status -->
                        <select
                            v-model="filtros.status"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent"
                            @change="filtrarAutores"
                        >
                            <option value="">Todos os status</option>
                            <option value="ativo">Ativos</option>
                            <option value="inativo">Inativos</option>
                        </select>
                    </div>

                    <!-- Ordenação -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Ordenar por:</span>
                        <select
                            v-model="filtros.ordenacao"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent text-sm"
                            @change="ordenarAutores"
                        >
                            <option value="nome_asc">Nome (A-Z)</option>
                            <option value="nome_desc">Nome (Z-A)</option>
                            <option value="data_desc">Mais recentes</option>
                            <option value="data_asc">Mais antigos</option>
                            <option value="noticias_desc">Mais notícias</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Lista de Autores -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div
                    v-if="autores.data.length > 0"
                    class="divide-y divide-gray-200"
                >
                    <div
                        v-for="autor in autores.data"
                        :key="autor.id"
                        class="p-6 hover:bg-gray-50 transition-colors"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                        >
                            <!-- Informações do Autor -->
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="flex-shrink-0">
                                    <img
                                        :src="
                                            autor.foto_perfil ||
                                            '/images/user-placeholder.png'
                                        "
                                        :alt="autor.nome"
                                        class="w-16 h-16 rounded-full border-2 border-gray-200"
                                    />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <h3
                                            class="text-lg font-semibold text-azul-oxford truncate"
                                        >
                                            {{ autor.nome }}
                                        </h3>
                                        <span
                                            :class="[
                                                'px-2 py-1 text-xs font-medium rounded-full',
                                                autor.status === 'ativo'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-gray-100 text-gray-800',
                                            ]"
                                        >
                                            {{
                                                autor.status === "ativo"
                                                    ? "Ativo"
                                                    : "Inativo"
                                            }}
                                        </span>
                                    </div>
                                    <p
                                        v-if="autor.email"
                                        class="text-sm text-gray-600 mt-1"
                                    >
                                        {{ autor.email }}
                                    </p>
                                    <p
                                        v-if="autor.bio"
                                        class="text-sm text-gray-500 mt-2 line-clamp-2"
                                    >
                                        {{ autor.bio }}
                                    </p>
                                    <p
                                        v-else
                                        class="text-sm text-gray-500 mt-2 italic"
                                    >
                                        Jornalista do Portal Bikanca
                                    </p>

                                    <!-- Estatísticas do Autor -->
                                    <div
                                        class="flex items-center space-x-4 mt-3"
                                    >
                                        <div
                                            class="flex items-center text-sm text-gray-500"
                                        >
                                            <svg
                                                class="w-4 h-4 mr-1"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            {{
                                                autor.total_noticias || 0
                                            }}
                                            notícias
                                        </div>
                                        <div
                                            class="flex items-center text-sm text-gray-500"
                                        >
                                            <svg
                                                class="w-4 h-4 mr-1"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    d="M10 12a2 2 0 100-4 2 2 0 000 4z"
                                                />
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            {{
                                                formatNumber(
                                                    autor.total_visualizacoes ||
                                                        0
                                                )
                                            }}
                                            views
                                        </div>
                                        <div
                                            class="flex items-center text-sm text-gray-500"
                                        >
                                            <svg
                                                class="w-4 h-4 mr-1"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            Desde
                                            {{ formatDate(autor.created_at) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ações -->
                            <div
                                class="flex items-center space-x-2 mt-4 sm:mt-0"
                            >
                                <!-- Visualizar -->
                                <Link
                                    :href="route('admin.autores.show', autor.id)"
                                    class="bg-blue-50 hover:bg-blue-100 text-blue-600 p-2 rounded-lg transition-colors"
                                    title="Visualizar perfil"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M10 12a2 2 0 100-4 2 2 0 000 4z"
                                        />
                                        <path
                                            fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </Link>

                                <!-- Editar -->
                                <Link
                                    :href="route('admin.autores.edit', autor.id)"
                                    class="bg-green-50 hover:bg-green-100 text-green-600 p-2 rounded-lg transition-colors"
                                    title="Editar autor"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                        />
                                    </svg>
                                </Link>

                                <!-- Excluir -->
                                <button
                                    @click="confirmarExclusao(autor)"
                                    class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition-colors"
                                    title="Excluir autor"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado vazio -->
                <div v-else class="text-center py-12">
                    <svg
                        class="w-12 h-12 text-gray-400 mx-auto mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                    <p class="text-gray-600 mb-4">
                        {{
                            filtros.busca || filtros.status
                                ? "Nenhum autor encontrado com os filtros aplicados."
                                : "Nenhum autor cadastrado ainda."
                        }}
                    </p>
                    <Link
                        v-if="!filtros.busca && !filtros.status"
                        href="/autores/create"
                        class="bg-azul-lazuli hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Criar primeiro autor
                    </Link>
                </div>

                <!-- Paginação -->
                <div
                    v-if="autores.links && autores.data.length > 0"
                    class="px-6 py-4 border-t border-gray-200"
                >
                    <Pagination :links="autores.links" />
                </div>
            </div>
        </div>

        <!-- Modal de Confirmação de Exclusão -->
        <div
            v-if="autorParaExcluir"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 rounded-full p-3 mr-3">
                        <svg
                            class="w-6 h-6 text-red-600"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Confirmar Exclusão
                        </h3>
                        <p class="text-sm text-gray-600">
                            Esta ação não pode ser desfeita.
                        </p>
                    </div>
                </div>

                <p class="text-gray-700 mb-6">
                    Tem certeza que deseja excluir o autor
                    <strong>{{ autorParaExcluir.nome }}</strong
                    >?
                    <span
                        v-if="autorParaExcluir.total_noticias > 0"
                        class="block text-sm text-red-600 mt-2"
                    >
                        Este autor possui
                        {{ autorParaExcluir.total_noticias }} notícias
                        associadas.
                    </span>
                </p>

                <div class="flex justify-end space-x-3">
                    <button
                        @click="cancelarExclusao"
                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="excluirAutor"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                        :disabled="excluindoAutor"
                    >
                        {{ excluindoAutor ? "Excluindo..." : "Excluir" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
