<script setup>
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import Admin from '@/Layouts/Admin.vue'

defineOptions({ layout: Admin });

// Form data
const form = useForm({
    nome: '',
    email: '',
    bio: '',
    foto: null
});

// Estados locais
const fotoPreview = ref(null);

// Função para preview da foto
const handleFotoChange = (event) => {
    const file = event.target.files[0];
    form.foto = file;
    
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            fotoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        fotoPreview.value = null;
    }
};

// Função para submeter o formulário
const submit = () => {
    form.post(route('admin.autores.store'), {
        forceFormData: true,
        onSuccess: () => {
            // Redirect será feito automaticamente pelo controller
        }
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Cabeçalho da seção -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <h1 class="text-3xl font-bold text-azul-oxford">
                            Criar Novo Autor
                        </h1>
                        <p class="text-gray-600 mt-2">
                            Adicione um novo autor ao Portal Bikanca
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Formulário -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <form @submit.prevent="submit" class="space-y-6 p-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome completo <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="nome"
                            v-model="form.nome"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent"
                            :class="{ 'border-red-500': form.errors.nome }"
                            placeholder="Digite o nome completo do autor"
                        />
                        <div v-if="form.errors.nome" class="mt-1 text-sm text-red-600">
                            {{ form.errors.nome }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent"
                            :class="{ 'border-red-500': form.errors.email }"
                            placeholder="Digite o email do autor"
                        />
                        <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                            Biografia
                        </label>
                        <textarea
                            id="bio"
                            v-model="form.bio"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent"
                            :class="{ 'border-red-500': form.errors.bio }"
                            placeholder="Digite uma breve biografia do autor"
                        ></textarea>
                        <div v-if="form.errors.bio" class="mt-1 text-sm text-red-600">
                            {{ form.errors.bio }}
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Descreva a experiência e especialidades do autor
                        </p>
                    </div>

                    <!-- Foto -->
                    <div>
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto do perfil
                        </label>
                        <div class="flex items-start space-x-6">
                            <!-- Preview da foto -->
                            <div class="flex-shrink-0">
                                <div class="w-24 h-24 rounded-full border-2 border-gray-300 overflow-hidden bg-gray-50 flex items-center justify-center">
                                    <img 
                                        v-if="fotoPreview" 
                                        :src="fotoPreview" 
                                        alt="Preview" 
                                        class="w-full h-full object-cover"
                                    />
                                    <svg 
                                        v-else 
                                        class="w-8 h-8 text-gray-400" 
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
                            </div>
                            
                            <!-- Input de arquivo -->
                            <div class="flex-1">
                                <input
                                    id="foto"
                                    type="file"
                                    accept="image/*"
                                    @change="handleFotoChange"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.foto }"
                                />
                                <div v-if="form.errors.foto" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.foto }}
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    PNG, JPG, GIF até 2MB
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <Link
                            :href="route('admin.autores.index')"
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-azul-lazuli hover:bg-blue-600 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Salvando...</span>
                            <span v-else>Criar Autor</span>
                        </button>
                    </div>
                </form>
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

.focus\:ring-azul-lazuli:focus {
    --tw-ring-color: #007fff;
}
</style>



