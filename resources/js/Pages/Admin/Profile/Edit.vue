<script setup>
import { ref } from 'vue';
import Admin from '@/Layouts/Admin.vue';
import { Link, useForm } from '@inertiajs/vue3';

defineOptions({ layout: Admin });

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

// Formulário de atualização de perfil
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    current_password: '',
    password: '',
    password_confirmation: '',
    foto: null,
});

// Estados reativos
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const showCurrentPassword = ref(false);
const previewFoto = ref(props.user.foto ? `/storage/${props.user.foto}` : null);

// Função para submeter o formulário
const submit = () => {
    form.post(route('admin.profile.update'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset('current_password', 'password', 'password_confirmation');
        },
    });
};

// Função para lidar com upload de foto
const handleFotoUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.foto = file;
        // Criar preview
        const reader = new FileReader();
        reader.onload = (e) => {
            previewFoto.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Função para remover foto
const removeFoto = () => {
    form.delete(route('admin.profile.remove-photo'), {
        onSuccess: () => {
            previewFoto.value = null;
            form.foto = null;
        },
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Cabeçalho da seção -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-azul-oxford">Configurações</h1>
                        <p class="text-gray-600 mt-2">Gerencie suas informações pessoais e configurações da conta</p>
                    </div>
                    
                    <div class="mt-4 sm:mt-0">
                        <Link
                            :href="route('admin.profile.show')"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors flex items-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ver Perfil
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <!-- Card de Foto de Perfil -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Foto de Perfil</h2>
                        <p class="text-sm text-gray-600">Atualize sua foto de perfil</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center space-x-6">
                            <!-- Preview da foto -->
                            <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                <img
                                    v-if="previewFoto"
                                    :src="previewFoto"
                                    :alt="user.name"
                                    class="h-full w-full object-cover"
                                />
                                <svg
                                    v-else
                                    class="h-12 w-12 text-gray-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            
                            <!-- Botões de ação -->
                            <div class="flex-1">
                                <div class="flex flex-wrap gap-3">
                                    <label class="bg-azul-lazuli hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Escolher Foto
                                        <input
                                            type="file"
                                            accept="image/*"
                                            class="hidden"
                                            @change="handleFotoUpload"
                                        />
                                    </label>
                                    
                                    <button
                                        v-if="previewFoto"
                                        type="button"
                                        @click="removeFoto"
                                        class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Remover
                                    </button>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    JPG, PNG ou GIF. Máximo 2MB.
                                </p>
                            </div>
                        </div>
                        
                        <div v-if="form.errors.foto" class="text-red-600 text-sm mt-2">
                            {{ form.errors.foto }}
                        </div>
                    </div>
                </div>

                <!-- Card de Informações Pessoais -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informações Pessoais</h2>
                        <p class="text-sm text-gray-600">Atualize seu nome e e-mail</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Nome -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nome completo
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent transition-colors"
                                :class="{ 'border-red-500': form.errors.name }"
                            />
                            <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- E-mail -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                E-mail
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent transition-colors"
                                :class="{ 'border-red-500': form.errors.email }"
                            />
                            <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
                                {{ form.errors.email }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Alterar Senha -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Alterar Senha</h2>
                        <p class="text-sm text-gray-600">Deixe em branco se não desejar alterar a senha</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Senha atual -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Senha atual
                            </label>
                            <div class="relative">
                                <input
                                    id="current_password"
                                    v-model="form.current_password"
                                    :type="showCurrentPassword ? 'text' : 'password'"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent transition-colors pr-12"
                                    :class="{ 'border-red-500': form.errors.current_password }"
                                />
                                <button
                                    type="button"
                                    @click="showCurrentPassword = !showCurrentPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="showCurrentPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <div v-if="form.errors.current_password" class="text-red-600 text-sm mt-1">
                                {{ form.errors.current_password }}
                            </div>
                        </div>

                        <!-- Nova senha -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nova senha
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent transition-colors pr-12"
                                    :class="{ 'border-red-500': form.errors.password }"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">
                                {{ form.errors.password }}
                            </div>
                        </div>

                        <!-- Confirmar nova senha -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar nova senha
                            </label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent transition-colors pr-12"
                                />
                                <button
                                    type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end space-x-4">
                    <Link
                        :href="route('admin.profile.show')"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancelar
                    </Link>
                    
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-3 bg-azul-lazuli hover:bg-blue-600 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                    </button>
                </div>
            </form>
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

.focus\:ring-azul-lazuli:focus {
    --tw-ring-color: #007fff;
}
</style>
