<template>
    <div class="container mx-auto p-4">
        <!-- Cabeçalho -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Gerenciar Notícias
            </h1>
            <p class="text-gray-600 mt-2">
                Gerencie todas as notícias do portal
            </p>
        </div>

        <!-- Barra de Pesquisa e Filtros -->
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
                
                <!-- Filtro por Status -->
                <div class="md:w-48">
                    <label
                        for="statusFilter"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Filtrar por Status
                    </label>
                    <select
                        id="statusFilter"
                        v-model="statusFilter"
                        @change="applyFilters"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Todos os Status</option>
                        <option value="publicada">Publicadas</option>
                        <option value="rascunho">Rascunhos</option>
                        <option value="arquivada">Arquivadas</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button
                        v-if="search || statusFilter"
                        @click="clearFilters"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpar Filtros
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
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Homepage
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
                                                noticia.imagem_capa?.caminho ||
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
                                <div class="relative">
                                    <button
                                        @click="toggleStatusDropdown(noticia.id)"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer hover:opacity-80 transition-opacity relative"
                                        :class="[
                                            getStatusClass(noticia.status),
                                            atualizandoStatus ? 'opacity-70' : ''
                                        ]"
                                        :disabled="atualizandoStatus"
                                    >
                                        {{ getStatusText(noticia.status) }}
                                        <svg 
                                            v-if="!atualizandoStatus"
                                            class="w-3 h-3 ml-1" 
                                            fill="currentColor" 
                                            viewBox="0 0 20 20"
                                        >
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg 
                                            v-else
                                            class="animate-spin w-3 h-3 ml-1" 
                                            fill="none" 
                                            viewBox="0 0 24 24"
                                        >
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div
                                        v-if="activeDropdown === noticia.id && !atualizandoStatus"
                                        class="absolute top-full left-0 mt-1 w-36 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                                    >
                                        <div class="py-1">
                                            <button
                                                v-for="status in statusOptions"
                                                :key="status.value"
                                                @click="alterarStatus(noticia, status.value)"
                                                class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 transition-colors flex items-center"
                                                :class="noticia.status === status.value ? 'bg-gray-50 font-medium' : ''"
                                            >
                                                <div 
                                                    class="w-3 h-3 rounded-full mr-2"
                                                    :class="status.color"
                                                ></div>
                                                {{ status.label }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td
                                class="px-4 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ formatDate(noticia.updated_at) }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        class="sr-only peer"
                                        :checked="noticia.destaque_home"
                                        @change="toggleDestaque(noticia)"
                                        :disabled="atualizandoDestaque === noticia.id"
                                    >
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span v-if="atualizandoDestaque === noticia.id" class="ml-2 text-xs text-gray-500">
                                        <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    <span v-else class="ml-2 text-xs text-gray-600">
                                        {{ noticia.destaque_home ? 'Sim' : 'Não' }}
                                    </span>
                                </label>
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
                                            route('admin.noticias.edit', noticia.id)
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
                            :src="noticia.imagem_capa?.caminho || '/logo.png'"
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
                            <div class="mt-2 flex flex-wrap gap-2 items-center">
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
                                
                                <!-- Status Dropdown Mobile -->
                                <div class="relative">
                                    <button
                                        @click="toggleStatusDropdown(noticia.id)"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium cursor-pointer hover:opacity-80 transition-opacity relative"
                                        :class="[
                                            getStatusClass(noticia.status),
                                            atualizandoStatus ? 'opacity-70' : ''
                                        ]"
                                        :disabled="atualizandoStatus"
                                    >
                                        {{ getStatusText(noticia.status) }}
                                        <svg 
                                            v-if="!atualizandoStatus"
                                            class="w-3 h-3 ml-1" 
                                            fill="currentColor" 
                                            viewBox="0 0 20 20"
                                        >
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg 
                                            v-else
                                            class="animate-spin w-3 h-3 ml-1" 
                                            fill="none" 
                                            viewBox="0 0 24 24"
                                        >
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Dropdown Menu Mobile -->
                                    <div
                                        v-if="activeDropdown === noticia.id && !atualizandoStatus"
                                        class="absolute top-full left-0 mt-1 w-36 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                                    >
                                        <div class="py-1">
                                            <button
                                                v-for="status in statusOptions"
                                                :key="status.value"
                                                @click="alterarStatus(noticia, status.value)"
                                                class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 transition-colors flex items-center"
                                                :class="noticia.status === status.value ? 'bg-gray-50 font-medium' : ''"
                                            >
                                                <div 
                                                    class="w-3 h-3 rounded-full mr-2"
                                                    :class="status.color"
                                                ></div>
                                                {{ status.label }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
                                            route('admin.noticias.edit', noticia.id)
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
import { ref, computed, watch, onUnmounted } from "vue";
import { Link, router } from "@inertiajs/vue3";
import Admin from "@/Layouts/Admin.vue";
import Pagination from "@/Components/Pagination.vue";
import { debounce } from "lodash";

defineOptions({ layout: Admin });

const props = defineProps({
    noticias: Object,
    filters: Object,
});

// Estados da pesquisa e filtros
const search = ref(props.filters?.search || "");
const statusFilter = ref(props.filters?.status || "");

// Estados do modal e dropdown
const showDeleteModal = ref(false);
const noticiaParaExcluir = ref(null);
const excluindo = ref(false);
const activeDropdown = ref(null);
const atualizandoStatus = ref(false);
const atualizandoDestaque = ref(null);

// Opções de status
const statusOptions = [
    { value: 'publicada', label: 'Publicada', color: 'bg-green-500' },
    { value: 'rascunho', label: 'Rascunho', color: 'bg-yellow-500' },
    { value: 'arquivada', label: 'Arquivada', color: 'bg-gray-500' }
];

// Pesquisa com debounce
const debouncedSearch = debounce(() => {
    applyFilters();
}, 500);

const applyFilters = () => {
    router.get(
        route("admin.noticias.index"),
        {
            search: search.value,
            status: statusFilter.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const clearFilters = () => {
    search.value = "";
    statusFilter.value = "";
    router.get(
        route("admin.noticias.index"),
        {},
        {
            preserveState: true,
            replace: true,
        }
    );
};

// Funções de dropdown de status
const toggleStatusDropdown = (noticiaId) => {
    activeDropdown.value = activeDropdown.value === noticiaId ? null : noticiaId;
};

const alterarStatus = async (noticia, novoStatus) => {
    if (atualizandoStatus.value || noticia.status === novoStatus) {
        activeDropdown.value = null;
        return;
    }

    atualizandoStatus.value = true;
    
    // Atualizar o status localmente para feedback imediato
    const statusAnterior = noticia.status;
    noticia.status = novoStatus;
    activeDropdown.value = null;
    
    try {
        router.patch(route('admin.noticias.update', noticia.id), {
            status: novoStatus
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                console.log('Status atualizado com sucesso para:', novoStatus);
            },
            onError: (errors) => {
                // Reverter o status em caso de erro
                noticia.status = statusAnterior;
                console.error('Erro ao alterar status:', errors);
                alert('Erro ao alterar status: ' + (errors.status || 'Erro desconhecido'));
            },
            onFinish: () => {
                atualizandoStatus.value = false;
            }
        });
    } catch (error) {
        // Reverter o status em caso de erro
        noticia.status = statusAnterior;
        console.error('Erro ao alterar status:', error);
        alert('Erro ao alterar status. Tente novamente.');
        atualizandoStatus.value = false;
    }
};

// Toggle destaque homepage
const toggleDestaque = async (noticia) => {
    if (atualizandoDestaque.value === noticia.id) {
        return;
    }

    atualizandoDestaque.value = noticia.id;
    
    // Atualizar o estado localmente para feedback imediato
    const destaqueAnterior = noticia.destaque_home;
    noticia.destaque_home = !noticia.destaque_home;
    
    try {
        await router.patch(route('admin.noticias.update', noticia.id), {
            destaque_home: noticia.destaque_home
        }, {
            preserveState: true,
            preserveScroll: true,
            onError: (errors) => {
                // Reverter o estado em caso de erro
                noticia.destaque_home = destaqueAnterior;
                console.error('Erro ao alterar destaque da homepage:', errors);
                alert('Erro ao alterar destaque da homepage. Tente novamente.');
            },
            onFinish: () => {
                atualizandoDestaque.value = null;
            }
        });
    } catch (error) {
        // Reverter o estado em caso de erro
        noticia.destaque_home = destaqueAnterior;
        console.error('Erro ao alterar destaque da homepage:', error);
        alert('Erro ao alterar destaque da homepage. Tente novamente.');
        atualizandoDestaque.value = null;
    }
};

// Fechar dropdown ao clicar fora
const closeDropdownOnClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
        activeDropdown.value = null;
    }
};

// Adicionar listener para fechar dropdown
watch(() => activeDropdown.value, (newValue) => {
    if (newValue) {
        document.addEventListener('click', closeDropdownOnClickOutside);
    } else {
        document.removeEventListener('click', closeDropdownOnClickOutside);
    }
});

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

    router.delete(route("admin.noticias.destroy", noticiaParaExcluir.value.id), {
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

// Limpeza dos event listeners
onUnmounted(() => {
    document.removeEventListener('click', closeDropdownOnClickOutside);
});
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
