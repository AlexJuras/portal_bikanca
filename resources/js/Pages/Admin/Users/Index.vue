<script setup>
import { ref, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Admin from "@/Layouts/Admin.vue";

defineOptions({ layout: Admin });

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
});

const showDeleteModal = ref(false);
const userToDelete = ref(null);

const openDeleteModal = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    userToDelete.value = null;
};

const deleteUser = () => {
    if (userToDelete.value) {
        router.delete(route('admin.users.destroy', userToDelete.value.id), {
            onSuccess: () => {
                closeDeleteModal();
            },
        });
    }
};

const loginAsUser = (user) => {
    if (confirm(`Tem certeza que deseja fazer login como ${user.name}?`)) {
        router.post(route('admin.users.login-as', user.id));
    }
};

const getTipoLabel = (tipo) => {
    const tipos = {
        admin: 'Administrador',
        editor: 'Editor'
    };
    return tipos[tipo] || tipo;
};

const getTipoBadgeClass = (tipo) => {
    const classes = {
        admin: 'bg-red-100 text-red-800',
        editor: 'bg-blue-100 text-blue-800'
    };
    return classes[tipo] || 'bg-gray-100 text-gray-800';
};

const formatDate = (date) => {
    if (!date) return 'Nunca';
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <Head title="Gerenciar Usuários" />

            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-azul-oxford">
                            Gerenciar Usuários
                        </h1>
                        <p class="text-gray-600 mt-2">
                            Gerencie os usuários do sistema
                        </p>
                    </div>
                    <Link
                        :href="route('admin.users.create')"
                    class="bg-azul-oxford hover:bg-azul-noite text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Usuário
                </Link>
            </div>
        </div>

        <!-- Lista de Usuários -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div v-if="users.data.length === 0" class="p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M9 20H4v-2a3 3 0 015.196-2.121M15 10a3 3 0 11-6 0 3 3 0 016 0zm4 2v-2a4 4 0 00-8 0v2"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum usuário encontrado</h3>
                <p class="text-gray-600 mb-6">Comece criando um novo usuário.</p>
                <Link
                    :href="route('admin.users.create')"
                    class="bg-azul-oxford hover:bg-azul-noite text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200"
                >
                    Criar Primeiro Usuário
                </Link>
            </div>

            <div v-else>
                <!-- Cabeçalho da Tabela -->
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                    <div class="grid grid-cols-12 gap-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="col-span-3">Nome</div>
                        <div class="col-span-3">Email</div>
                        <div class="col-span-2">Tipo</div>
                        <div class="col-span-2">Criado em</div>
                        <div class="col-span-2">Ações</div>
                    </div>
                </div>

                <!-- Lista de Usuários -->
                <div class="divide-y divide-gray-200">
                    <div
                        v-for="user in users.data"
                        :key="user.id"
                        class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200"
                    >
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- Nome -->
                            <div class="col-span-3">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-azul-oxford text-white flex items-center justify-center text-sm font-medium">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-span-3">
                                <p class="text-sm text-gray-600">{{ user.email }}</p>
                            </div>

                            <!-- Tipo -->
                            <div class="col-span-2">
                                <span 
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="getTipoBadgeClass(user.tipo)"
                                >
                                    {{ getTipoLabel(user.tipo) }}
                                </span>
                            </div>

                            <!-- Data de Criação -->
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600">{{ formatDate(user.created_at) }}</p>
                            </div>

                            <!-- Ações -->
                            <div class="col-span-2">
                                <div class="flex items-center gap-2">
                                    <!-- Ver Usuário -->
                                    <Link
                                        :href="route('admin.users.show', user.id)"
                                        class="text-azul-oxford hover:text-azul-noite transition-colors"
                                        title="Ver usuário"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </Link>

                                    <!-- Editar Usuário -->
                                    <Link
                                        :href="route('admin.users.edit', user.id)"
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Editar usuário"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </Link>

                                    <!-- Login como usuário -->
                                    <button
                                        v-if="user.id !== $page.props.auth.user.id"
                                        @click="loginAsUser(user)"
                                        class="text-green-600 hover:text-green-800 transition-colors"
                                        title="Fazer login como este usuário"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </button>

                                    <!-- Excluir Usuário -->
                                    <button
                                        v-if="user.id !== $page.props.auth.user.id"
                                        @click="openDeleteModal(user)"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Excluir usuário"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paginação -->
                <div v-if="users.last_page > 1" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link
                                v-if="users.prev_page_url"
                                :href="users.prev_page_url"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Anterior
                            </Link>
                            <Link
                                v-if="users.next_page_url"
                                :href="users.next_page_url"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Próximo
                            </Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Mostrando
                                    <span class="font-medium">{{ users.from }}</span>
                                    até
                                    <span class="font-medium">{{ users.to }}</span>
                                    de
                                    <span class="font-medium">{{ users.total }}</span>
                                    resultados
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <Link
                                        v-if="users.prev_page_url"
                                        :href="users.prev_page_url"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Anterior</span>
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </Link>
                                    <span
                                        v-for="page in users.links.slice(1, -1)"
                                        :key="page.label"
                                    >
                                        <Link
                                            v-if="page.url"
                                            :href="page.url"
                                            :class="page.active ? 'bg-azul-oxford text-white' : 'bg-white text-gray-500 hover:bg-gray-50'"
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
                                            v-html="page.label"
                                        />
                                        <span
                                            v-else
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500"
                                            v-html="page.label"
                                        />
                                    </span>
                                    <Link
                                        v-if="users.next_page_url"
                                        :href="users.next_page_url"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Próximo</span>
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </Link>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmação de Exclusão -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center"
            @click="closeDeleteModal"
        >
            <div
                @click.stop
                class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-4"
            >
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 text-center mb-2">
                    Confirmar Exclusão
                </h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    Tem certeza que deseja excluir o usuário <strong>{{ userToDelete?.name }}</strong>?
                    Esta ação não pode ser desfeita.
                </p>
                <div class="flex justify-center gap-3">
                    <button
                        @click="closeDeleteModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-azul-oxford"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteUser"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        Excluir
                    </button>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>
