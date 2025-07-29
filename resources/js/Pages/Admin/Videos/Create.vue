<script setup>
import { ref, reactive } from "vue";
import Principal from "@/Layouts/Principal.vue";
import { Link, useForm } from "@inertiajs/vue3";

defineOptions({ layout: Principal });

const props = defineProps({
    categorias: {
        type: Array,
        required: true,
    },
    autores: {
        type: Array,
        required: true,
    },
});

// Formulário reativo
const form = useForm({
    titulo: '',
    descricao: '',
    url_externa: '',
    categoria_id: '',
    autor_id: '',
    duracao: '',
    status: 'rascunho',
});

// Preview do vídeo
const videoPreview = ref(null);
const showPreview = ref(false);

// Função para extrair ID do YouTube
const getYoutubeId = (url) => {
    const pattern = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
    const matches = url.match(pattern);
    return matches ? matches[1] : null;
};

// Função para gerar preview
const gerarPreview = () => {
    if (!form.url_externa) {
        showPreview.value = false;
        return;
    }

    const youtubeId = getYoutubeId(form.url_externa);
    if (youtubeId) {
        videoPreview.value = {
            id: youtubeId,
            embedUrl: `https://www.youtube.com/embed/${youtubeId}?rel=0`,
            thumbnailUrl: `https://img.youtube.com/vi/${youtubeId}/maxresdefault.jpg`
        };
        showPreview.value = true;
    } else {
        showPreview.value = false;
    }
};

// Submeter formulário
const submit = () => {
    form.post(route('admin.videos.store'));
};
</script>
<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Cabeçalho da Página -->
        <section class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumb -->
                <nav class="text-sm text-gray-600 mb-4">
                    <Link
                        href="/"
                        class="hover:text-azul-oxford transition-colors"
                        >Home</Link
                    >
                    <span class="mx-2">></span>
                    <Link
                        href="/videos"
                        class="hover:text-azul-oxford transition-colors"
                        >Vídeos</Link
                    >
                    <span class="mx-2">></span>
                    <span class="text-azul-oxford">Adicionar Vídeo</span>
                </nav>
                <!-- Título -->
                <div>
                    <h1
                        class="text-3xl font-bold text-azul-oxford flex items-center gap-3"
                    >
                        ➕ Adicionar Novo Vídeo
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Adicione um novo vídeo do YouTube ao portal
                    </p>
                </div>
            </div>
        </section>
        <!-- Formulário -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <!-- Título -->
                    <div>
                        <label
                            for="titulo"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Título *
                        </label>
                        <input
                            id="titulo"
                            v-model="form.titulo"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-azul-oxford focus:border-azul-oxford"
                            placeholder="Digite o título do vídeo"
                            required
                        />
                        <div
                            v-if="form.errors.titulo"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.titulo }}
                        </div>
                    </div>
                    <!-- URL do YouTube -->
                    <div>
                        <label
                            for="url_externa"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            URL do YouTube *
                        </label>
                        <input
                            id="url_externa"
                            v-model="form.url_externa"
                            @blur="gerarPreview"
                            type="url"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-azul-oxford focus:border-azul-oxford"
                            placeholder="https://www.youtube.com/watch?v=..."
                            required
                        />
                        <div
                            v-if="form.errors.url_externa"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.url_externa }}
                        </div>
                        <p class="text-gray-500 text-sm mt-1">
                            Cole a URL completa do vídeo do YouTube
                        </p>
                    </div>
                    <!-- Preview do Vídeo -->
                    <div
                        v-if="showPreview && videoPreview"
                        class="bg-gray-50 rounded-lg p-4"
                    >
                        <h3 class="text-lg font-medium text-gray-900 mb-3">
                            Preview do Vídeo
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <img
                                    :src="videoPreview.thumbnailUrl"
                                    alt="Thumbnail do vídeo"
                                    class="w-full h-auto rounded"
                                />
                            </div>
                            <div class="text-sm text-gray-600">
                                <p>
                                    <strong>ID do YouTube:</strong>
                                    {{ videoPreview.id }}
                                </p>
                                <p>
                                    <strong>URL de Embed:</strong>
                                    {{ videoPreview.embedUrl }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Descrição -->
                    <div>
                        <label
                            for="descricao"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Descrição *
                        </label>
                        <textarea
                            id="descricao"
                            v-model="form.descricao"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-azul-oxford focus:border-azul-oxford"
                            placeholder="Descreva o conteúdo do vídeo"
                            required
                        ></textarea>
                        <div
                            v-if="form.errors.descricao"
                            class="text-red-600 text-sm mt-1"
                        >
                            {{ form.errors.descricao }}
                        </div>
                    </div>
                    <!-- Grid para Categoria e Autor -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Categoria -->
                        <div>
                            <label
                                for="categoria_id"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Categoria *
                            </label>
                            <select
                                id="categoria_id"
                                v-model="form.categoria_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-azul-oxford focus:border-azul-oxford"
                                required
                            >
                                <option value="">
                                    Selecione uma categoria
                                </option>
                                <option
                                    v-for="categoria in categorias"
                                    :key="categoria.id"
                                    :value="categoria.id"
                                >
                                    {{ categoria.nome }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.categoria_id"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ form.errors.categoria_id }}
                            </div>
                        </div>
                        <!-- Autor -->
                        <div>
                            <label
                                for="autor_id"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Autor *
                            </label>
                            <select
                                id="autor_id"
                                v-model="form.autor_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-azul-oxford focus:border-azul-oxford"
                                required
                            >
                                <option value="">Selecione um autor</option>
                                <option
                                    v-for="autor in autores"
                                    :key="autor.id"
                                    :value="autor.id"
                                >
                                    {{ autor.nome }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.autor_id"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ form.errors.autor_id }}
                            </div>
                        </div>
                    </div>
                    <!-- Grid para Duração e Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Duração -->
                        <div>
                            <label
                                for="duracao"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Duração
                            </label>
                            <input
                                id="duracao"
                                v-model="form.duracao"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-azul-oxford focus:border-azul-oxford"
                                placeholder="Ex: 10:30"
                            />
                            <div
                                v-if="form.errors.duracao"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ form.errors.duracao }}
                            </div>
                        </div>
                        <!-- Status -->
                        <div>
                            <label
                                for="status"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Status *
                            </label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-azul-oxford focus:border-azul-oxford"
                                required
                            >
                                <option value="rascunho">Rascunho</option>
                                <option value="publicada">Publicado</option>
                            </select>
                            <div
                                v-if="form.errors.status"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ form.errors.status }}
                            </div>
                        </div>
                    </div>
                    <!-- Botões -->
                    <div
                        class="flex items-center justify-between pt-6 border-t"
                    >
                        <Link
                            href="/videos"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-azul-oxford hover:bg-azul-noite text-white px-6 py-2 rounded-lg font-medium transition-colors disabled:opacity-50"
                        >
                            <span v-if="form.processing">Salvando...</span>
                            <span v-else>Salvar Vídeo</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style scoped>
/* Estilos customizados se necessário */
</style>
