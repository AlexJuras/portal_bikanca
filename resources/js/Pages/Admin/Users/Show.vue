<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Admin from "@/Layouts/Admin.vue";

defineOptions({ layout: Admin });

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const showDeleteModal = ref(false);

const openDeleteModal = () => {
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

const deleteUser = () => {
    router.delete(route('admin.users.destroy', props.user.id), {
        onSuccess: () => {
            // Redirecionamento será feito pelo controlador
        },
    });
};

const loginAsUser = () => {
    if (confirm(`Tem certeza que deseja fazer login como ${props.user.name}?`)) {
        router.post(route('admin.users.login-as', props.user.id));
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
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <Head :title="`Usuário: ${user.name}`" />

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <Link
                        :href="route('admin.users.index')"
                        class="text-azul-oxford hover:text-azul-noite transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </Link>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-azul-oxford">{{ user.name }}</h1>
                        <p class="text-gray-600 mt-1">Detalhes do usuário</p>
                    </div>
                    <div class="flex gap-3">
                        <!-- Editar -->
                        <Link
                        :href="route('admin.users.edit', user.id)"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </Link>
                    
                    <!-- Login como usuário -->
                    <button
                        v-if="user.id !== $page.props.auth.user.id"
                        @click="loginAsUser"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Fazer Login
                    </button>

                    <!-- Excluir -->
                    <button
                        v-if="user.id !== $page.props.auth.user.id"
                        @click="openDeleteModal"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Excluir
                    </button>
                </div>
            </div>
        </div>

        <!-- Informações do Usuário -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações Principais -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-azul-oxford mb-6">Informações Pessoais</h2>
                    
                    <div class="space-y-6">
                        <!-- Avatar e Nome -->
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-full bg-azul-oxford text-white flex items-center justify-center text-2xl font-medium">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ user.name }}</h3>
                                <span 
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                    :class="getTipoBadgeClass(user.tipo)"
                                >
                                    {{ getTipoLabel(user.tipo) }}
                                </span>
                            </div>
                        </div>

                        <!-- Detalhes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">E-mail</label>
                                <p class="text-gray-900">{{ user.email }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo de Usuário</label>
                                <p class="text-gray-900">{{ getTipoLabel(user.tipo) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Data de Criação</label>
                                <p class="text-gray-900">{{ formatDate(user.created_at) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Última Atualização</label>
                                <p class="text-gray-900">{{ formatDate(user.updated_at) }}</p>
                            </div>

                            <div v-if="user.last_login">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Último Login</label>
                                <p class="text-gray-900">{{ formatDate(user.last_login) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-azul-oxford mb-6">Ações Rápidas</h2>
                    
                    <div class="space-y-4">
                        <!-- Editar Usuário -->
                        <Link
                            :href="route('admin.users.edit', user.id)"
                            class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Editar Usuário</p>
                                <p class="text-sm text-gray-600">Alterar informações e permissões</p>
                            </div>
                        </Link>

                        <!-- Login como Usuário -->
                        <button
                            v-if="user.id !== $page.props.auth.user.id"
                            @click="loginAsUser"
                            class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <p class="font-medium text-gray-900">Fazer Login</p>
                                <p class="text-sm text-gray-600">Entrar como este usuário</p>
                            </div>
                        </button>

                        <!-- Resetar Senha -->
                        <Link
                            :href="route('admin.users.edit', user.id)"
                            class="w-full flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Resetar Senha</p>
                                <p class="text-sm text-gray-600">Alterar senha do usuário</p>
                            </div>
                        </Link>

                        <!-- Excluir Usuário -->
                        <button
                            v-if="user.id !== $page.props.auth.user.id"
                            @click="openDeleteModal"
                            class="w-full flex items-center gap-3 p-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors text-red-600"
                        >
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <p class="font-medium">Excluir Usuário</p>
                                <p class="text-sm text-red-500">Remover permanentemente</p>
                            </div>
                        </button>
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
                    Tem certeza que deseja excluir o usuário <strong>{{ user.name }}</strong>?
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
