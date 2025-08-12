<script setup>
import { computed } from 'vue';
import Admin from '@/Layouts/Admin.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: Admin });

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

// Função para formatar data
const formatDate = (date) => {
    if (!date) return 'Não disponível';
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Função para obter texto do tipo de usuário
const tipoUsuario = computed(() => {
    const tipos = {
        'admin': 'Administrador',
        'editor': 'Editor',
        'redator': 'Redator',
        'moderador': 'Moderador',
    };
    return tipos[props.user.tipo] || 'Usuário';
});

// Função para obter cor do badge do tipo
const badgeColor = computed(() => {
    const cores = {
        'admin': 'bg-red-100 text-red-800',
        'editor': 'bg-blue-100 text-blue-800',
        'redator': 'bg-green-100 text-green-800',
        'moderador': 'bg-yellow-100 text-yellow-800',
    };
    return cores[props.user.tipo] || 'bg-gray-100 text-gray-800';
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Cabeçalho da seção -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Foto de perfil -->
                        <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                            <img
                                v-if="user.foto"
                                :src="`/storage/${user.foto}`"
                                :alt="user.name"
                                class="h-full w-full object-cover"
                            />
                            <svg
                                v-else
                                class="h-12 w-12 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        
                        <div>
                            <h1 class="text-3xl font-bold text-azul-oxford">
                                {{ user.name }}
                            </h1>
                            <p class="text-gray-600 mt-2">
                                Perfil do usuário
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 sm:mt-0">
                        <Link
                            :href="route('admin.profile.edit')"
                            class="bg-azul-lazuli hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                            Editar Perfil
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Header do Card -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informações do Perfil</h2>
                    <p class="text-sm text-gray-600">Visualize suas informações pessoais e de conta</p>
                </div>

                <!-- Conteúdo do Card -->
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nome completo -->
                        <div>
                            <dt class="flex items-center text-sm font-medium text-gray-500">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Nome completo
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ user.name }}</dd>
                        </div>

                        <!-- Email -->
                        <div>
                            <dt class="flex items-center text-sm font-medium text-gray-500">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                E-mail
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ user.email }}</dd>
                        </div>

                        <!-- ID do usuário -->
                        <div>
                            <dt class="flex items-center text-sm font-medium text-gray-500">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                ID do usuário
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">#{{ user.id }}</dd>
                        </div>

                        <!-- Nível de permissão -->
                        <div>
                            <dt class="flex items-center text-sm font-medium text-gray-500">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Nível de permissão
                            </dt>
                            <dd class="mt-1">
                                <span :class="badgeColor" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ tipoUsuario }}
                                </span>
                            </dd>
                        </div>

                        <!-- Data de cadastro -->
                        <div>
                            <dt class="flex items-center text-sm font-medium text-gray-500">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                Data de cadastro
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ formatDate(user.created_at) }}</dd>
                        </div>

                        <!-- Último acesso -->
                        <div>
                            <dt class="flex items-center text-sm font-medium text-gray-500">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                Último acesso
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ formatDate(user.last_login) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>

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

.hover\:text-azul-oxford:hover {
    color: #1e3a8a;
}
</style>
