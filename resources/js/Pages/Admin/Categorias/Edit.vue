<script setup>
import { ref } from 'vue';
import Admin from '@/Layouts/Admin.vue';
import { Link, useForm } from '@inertiajs/vue3';

defineOptions({ layout: Admin });

const props = defineProps({
    categoria: {
        type: Object,
        required: true,
    },
});

// Formulário de edição
const form = useForm({
    nome: props.categoria.nome,
    slug: props.categoria.slug,
    descricao: props.categoria.descricao || '',
});

// Função para gerar slug automaticamente
const generateSlug = () => {
    if (form.nome) {
        const slug = form.nome
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        form.slug = slug;
    }
};

// Submeter formulário
const submit = () => {
    form.put(route('admin.categorias.update', props.categoria.slug));
};
</script>

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
                                href="/admin"
                                class="text-azul-lazuli hover:text-azul-oxford transition-colors flex items-center"
                            >
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                                Admin
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <Link
                                :href="route('admin.categorias.index')"
                                class="text-azul-lazuli hover:text-azul-oxford transition-colors"
                            >
                                Categorias
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-azul-oxford font-medium">Editar: {{ categoria.nome }}</span>
                        </li>
                    </ol>
                </nav>

                <!-- Cabeçalho da seção -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-azul-oxford">Editar Categoria</h1>
                        <p class="text-gray-600 mt-2">Atualize as informações da categoria "{{ categoria.nome }}"</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form @submit.prevent="submit">
                <!-- Card Principal -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informações da Categoria</h2>
                        <p class="text-sm text-gray-600">Atualize os dados da categoria abaixo</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Nome -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                Nome da Categoria *
                            </label>
                            <input
                                id="nome"
                                v-model="form.nome"
                                @input="generateSlug"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent transition-colors"
                                :class="{ 'border-red-500': form.errors.nome }"
                                placeholder="Digite o nome da categoria"
                            />
                            <div v-if="form.errors.nome" class="text-red-600 text-sm mt-1">
                                {{ form.errors.nome }}
                            </div>
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug (URL) *
                            </label>
                            <input
                                id="slug"
                                v-model="form.slug"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent transition-colors"
                                :class="{ 'border-red-500': form.errors.slug }"
                                placeholder="categoria-exemplo"
                            />
                            <p class="text-sm text-gray-500 mt-1">
                                URL amigável para a categoria. Será gerada automaticamente baseada no nome.
                            </p>
                            <div v-if="form.errors.slug" class="text-red-600 text-sm mt-1">
                                {{ form.errors.slug }}
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                                Descrição
                            </label>
                            <textarea
                                id="descricao"
                                v-model="form.descricao"
                                rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-azul-lazuli focus:border-transparent transition-colors resize-none"
                                :class="{ 'border-red-500': form.errors.descricao }"
                                placeholder="Descreva brevemente esta categoria (opcional)"
                            ></textarea>
                            <div v-if="form.errors.descricao" class="text-red-600 text-sm mt-1">
                                {{ form.errors.descricao }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Informações Adicionais -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informações</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Criada em:</p>
                                <p class="text-sm text-gray-600">{{ new Date(categoria.created_at).toLocaleDateString('pt-BR') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Última atualização:</p>
                                <p class="text-sm text-gray-600">{{ new Date(categoria.updated_at).toLocaleDateString('pt-BR') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end space-x-4 mt-8">
                    <Link
                        :href="route('admin.categorias.index')"
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
