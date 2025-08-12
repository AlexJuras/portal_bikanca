<script setup>
import { ref, computed } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import Admin from "@/Layouts/Admin.vue";

defineOptions({ layout: Admin });

// Form data usando Inertia
const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    tipo: "editor",
});

// Estados locais
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

// Função para traduzir erros
const traduzirErro = (campo, erro) => {
    const traducoes = {
        'The name field is required.': 'O nome é obrigatório.',
        'The email field is required.': 'O e-mail é obrigatório.',
        'The email must be a valid email address.': 'Digite um e-mail válido.',
        'The email has already been taken.': 'Este e-mail já está sendo usado.',
        'The password field is required.': 'A senha é obrigatória.',
        'The password must be at least 8 characters.': 'A senha deve ter pelo menos 8 caracteres.',
        'The password confirmation does not match.': 'A confirmação da senha não confere.',
        'The tipo field is required.': 'O tipo de usuário é obrigatório.',
    };
    return traducoes[erro] || erro;
};

// Computed para erros traduzidos
const errosTraduzidos = computed(() => {
    const erros = {};
    Object.keys(form.errors).forEach(campo => {
        erros[campo] = traduzirErro(campo, form.errors[campo]);
    });
    return erros;
});

// Submeter formulário
const submit = () => {
    form.post(route('admin.users.store'), {
        onSuccess: () => {
            // Redirecionar será feito pelo controlador
        },
    });
};

// Gerar senha aleatória
const generateRandomPassword = () => {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';
    for (let i = 0; i < 12; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    form.password = password;
    form.password_confirmation = password;
};

const tiposUsuario = [
    { value: 'editor', label: 'Editor' },
    { value: 'admin', label: 'Administrador' }
];
</script>

<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <Head title="Criar Usuário" />

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <router-link
                        :href="route('admin.users.index')"
                        class="text-azul-oxford hover:text-azul-noite transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </router-link>
                    <div>
                        <h1 class="text-3xl font-bold text-azul-oxford">Criar Usuário</h1>
                        <p class="text-gray-600 mt-1">Adicione um novo usuário ao sistema</p>
                    </div>
            </div>
        </div>

        <!-- Formulário -->
        <div class="max-w-2xl">
            <form @submit.prevent="submit" class="bg-white rounded-lg shadow-sm p-6">
                <!-- Nome -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-azul-oxford mb-2">
                        Nome Completo *
                    </label>
                    <input
                        id="name"
                        type="text"
                        v-model="form.name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                        :class="{ 
                            'border-red-500 bg-red-50': errosTraduzidos.name,
                            'border-green-500 bg-green-50': form.name && !errosTraduzidos.name
                        }"
                        placeholder="Digite o nome completo"
                        required
                    />
                    <p v-if="errosTraduzidos.name" class="text-sm text-red-600 font-medium mt-1">
                        {{ errosTraduzidos.name }}
                    </p>
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-azul-oxford mb-2">
                        E-mail *
                    </label>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                        :class="{ 
                            'border-red-500 bg-red-50': errosTraduzidos.email,
                            'border-green-500 bg-green-50': form.email && !errosTraduzidos.email
                        }"
                        placeholder="Digite o e-mail"
                        required
                    />
                    <p v-if="errosTraduzidos.email" class="text-sm text-red-600 font-medium mt-1">
                        {{ errosTraduzidos.email }}
                    </p>
                </div>

                <!-- Tipo de Usuário -->
                <div class="mb-6">
                    <label for="tipo" class="block text-sm font-medium text-azul-oxford mb-2">
                        Tipo de Usuário *
                    </label>
                    <select
                        id="tipo"
                        v-model="form.tipo"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                        :class="{ 
                            'border-red-500 bg-red-50': errosTraduzidos.tipo,
                            'border-green-500 bg-green-50': form.tipo && !errosTraduzidos.tipo
                        }"
                        required
                    >
                        <option value="">Selecione o tipo</option>
                        <option 
                            v-for="tipo in tiposUsuario" 
                            :key="tipo.value" 
                            :value="tipo.value"
                        >
                            {{ tipo.label }}
                        </option>
                    </select>
                    <p v-if="errosTraduzidos.tipo" class="text-sm text-red-600 font-medium mt-1">
                        {{ errosTraduzidos.tipo }}
                    </p>
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="space-y-1">
                            <p><strong>Usuário:</strong> Acesso limitado ao sistema</p>
                            <p><strong>Editor:</strong> Pode criar e editar conteúdo</p>
                            <p><strong>Administrador:</strong> Acesso total ao sistema</p>
                        </div>
                    </div>
                </div>

                <!-- Senha -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-medium text-azul-oxford">
                            Senha *
                        </label>
                        <button
                            type="button"
                            @click="generateRandomPassword"
                            class="text-sm text-azul-oxford hover:text-azul-noite font-medium"
                        >
                            Gerar Senha Aleatória
                        </button>
                    </div>
                    <div class="relative">
                        <input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                            :class="{ 
                                'border-red-500 bg-red-50': errosTraduzidos.password,
                                'border-green-500 bg-green-50': form.password && form.password.length >= 8 && !errosTraduzidos.password
                            }"
                            placeholder="Digite a senha (mín. 8 caracteres)"
                            required
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                        >
                            <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L12 12m-2.122-2.122L7.76 7.76m4.242 4.242L14.12 14.12m-4.242-4.242L14.12 14.12m0 0l2.122 2.122"></path>
                            </svg>
                        </button>
                    </div>
                    <p v-if="errosTraduzidos.password" class="text-sm text-red-600 font-medium mt-1">
                        {{ errosTraduzidos.password }}
                    </p>
                </div>

                <!-- Confirmação de Senha -->
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-sm font-medium text-azul-oxford mb-2">
                        Confirmar Senha *
                    </label>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            :type="showPasswordConfirmation ? 'text' : 'password'"
                            v-model="form.password_confirmation"
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-azul-lazuli transition-colors"
                            :class="{ 
                                'border-red-500 bg-red-50': errosTraduzidos.password_confirmation,
                                'border-green-500 bg-green-50': form.password_confirmation && form.password === form.password_confirmation && !errosTraduzidos.password_confirmation
                            }"
                            placeholder="Confirme a senha"
                            required
                        />
                        <button
                            type="button"
                            @click="showPasswordConfirmation = !showPasswordConfirmation"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                        >
                            <svg v-if="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L12 12m-2.122-2.122L7.76 7.76m4.242 4.242L14.12 14.12m-4.242-4.242L14.12 14.12m0 0l2.122 2.122"></path>
                            </svg>
                        </button>
                    </div>
                    <p v-if="errosTraduzidos.password_confirmation" class="text-sm text-red-600 font-medium mt-1">
                        {{ errosTraduzidos.password_confirmation }}
                    </p>
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end gap-4">
                    <router-link
                        :href="route('admin.users.index')"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors duration-200"
                    >
                        Cancelar
                    </router-link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-3 bg-azul-oxford hover:bg-azul-noite text-white rounded-lg font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                    >
                        <svg v-if="form.processing" class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ form.processing ? 'Criando...' : 'Criar Usuário' }}
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
</template>
